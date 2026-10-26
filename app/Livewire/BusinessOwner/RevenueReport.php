<?php

namespace App\Livewire\BusinessOwner;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\Booking;
use App\Support\Revenue;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

#[Layout('layouts.owner')]
class RevenueReport extends Component
{
    public $period = 'this_month'; // this_month, last_month, last_3_months, this_year, custom
    public $from = '';
    public $to = '';

    public function mount()
    {
        $this->applyPeriod();
    }

    public function updatedPeriod()
    {
        $this->applyPeriod();
    }

    public function updatedFrom()
    {
        $this->period = 'custom';
    }

    public function updatedTo()
    {
        $this->period = 'custom';
    }

    protected function applyPeriod(): void
    {
        [$from, $to] = match ($this->period) {
            'last_month' => [now()->subMonthNoOverflow()->startOfMonth(), now()->subMonthNoOverflow()->endOfMonth()],
            'last_3_months' => [now()->subMonthsNoOverflow(2)->startOfMonth(), now()->endOfMonth()],
            'this_year' => [now()->startOfYear(), now()->endOfYear()],
            'custom' => [$this->from ? Carbon::parse($this->from) : now()->startOfMonth(), $this->to ? Carbon::parse($this->to) : now()],
            default => [now()->startOfMonth(), now()->endOfMonth()],
        };

        $this->from = $from->toDateString();
        $this->to = $to->toDateString();
    }

    /** The selected range, normalised so "from" is never after "to". */
    protected function range(): array
    {
        $from = Carbon::parse($this->from ?: now()->startOfMonth())->startOfDay();
        $to = Carbon::parse($this->to ?: now())->endOfDay();

        return $from->gt($to) ? [$to->copy()->startOfDay(), $from->copy()->endOfDay()] : [$from, $to];
    }

    /** Headline numbers for a range. */
    protected function summary(Carbon $from, Carbon $to): array
    {
        $bookings = Booking::whereBetween('booking_date', [$from->toDateString(), $to->toDateString()]);
        $total = (clone $bookings)->count();
        $completed = (clone $bookings)->where('status', 'completed')->count();
        $lost = (clone $bookings)->whereIn('status', ['cancelled', 'no_show', 'not_available'])->count();
        $revenue = Revenue::between($from, $to);

        return [
            'revenue' => $revenue,
            'bookings' => $total,
            'completed' => $completed,
            'avg_job' => $completed > 0 ? $revenue / $completed : 0,
            'lost' => $lost,
            'lost_rate' => $total > 0 ? round($lost / $total * 100) : 0,
        ];
    }

    public function exportCsv()
    {
        [$from, $to] = $this->range();

        $rows = Booking::with(['customer.user', 'vehicle', 'services'])
            ->whereBetween('booking_date', [$from->toDateString(), $to->toDateString()])
            ->where('status', 'completed')
            ->orderBy('booking_date')
            ->get();

        $filename = 'dexter-revenue-' . $from->format('Ymd') . '-' . $to->format('Ymd') . '.csv';

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Date', 'Time', 'Reference', 'Customer', 'Vehicle', 'Plate', 'Services', 'Mechanic', 'Amount (PHP)']);
            foreach ($rows as $b) {
                fputcsv($out, [
                    $b->booking_date->format('Y-m-d'),
                    $b->booking_time->format('H:i'),
                    $b->booking_reference,
                    $b->customer->getDisplayName(),
                    trim(($b->vehicle?->make ?? '') . ' ' . ($b->vehicle?->model ?? '')),
                    $b->vehicle?->plate_number,
                    $b->services->pluck('name')->join('; '),
                    $b->mechanic_name,
                    number_format((float) $b->total_amount, 2, '.', ''),
                ]);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function render()
    {
        [$from, $to] = $this->range();
        $days = (int) $from->diffInDays($to) + 1;

        // Same-length period right before, for comparison
        $prevTo = $from->copy()->subDay()->endOfDay();
        $prevFrom = $prevTo->copy()->subDays($days - 1)->startOfDay();

        $current = $this->summary($from, $to);
        $previous = $this->summary($prevFrom, $prevTo);

        // Revenue over time: by day for short ranges, otherwise by month
        $byDay = $days <= 62;
        $buckets = $byDay
            ? collect(CarbonPeriod::create($from, '1 day', $to))->map(fn ($d) => [$d->copy()->startOfDay(), $d->copy()->endOfDay()])
            : collect(CarbonPeriod::create($from->copy()->startOfMonth(), '1 month', $to))->map(fn ($m) => [$m->copy()->startOfMonth()->max($from), $m->copy()->endOfMonth()->min($to)]);

        $series = $buckets->map(fn ($b) => [
            'label' => $byDay ? $b[0]->format('j') : $b[0]->format('M'),
            'full_label' => $byDay ? $b[0]->format('D, M j, Y') : $b[0]->format('F Y'),
            'revenue' => Revenue::between($b[0], $b[1]),
        ])->values();

        // Completed work broken down by service category and by service
        $lines = DB::table('booking_services')
            ->join('bookings', 'bookings.id', '=', 'booking_services.booking_id')
            ->join('services', 'services.id', '=', 'booking_services.service_id')
            ->leftJoin('service_categories', 'service_categories.id', '=', 'services.service_category_id')
            ->where('bookings.status', 'completed')
            ->whereBetween('bookings.booking_date', [$from->toDateString(), $to->toDateString()]);

        $byCategory = (clone $lines)
            ->selectRaw("COALESCE(service_categories.name, 'Other') as name, SUM(booking_services.price * booking_services.quantity) as revenue, COUNT(*) as jobs")
            ->groupBy('service_categories.name')
            ->orderByDesc('revenue')
            ->get();

        $topServices = (clone $lines)
            ->selectRaw('services.name as name, SUM(booking_services.price * booking_services.quantity) as revenue, SUM(booking_services.quantity) as times')
            ->groupBy('services.id', 'services.name')
            ->orderByDesc('revenue')
            ->limit(8)
            ->get();

        // Busiest days of the week (all bookings, not just completed)
        $weekdayCounts = Booking::whereBetween('booking_date', [$from->toDateString(), $to->toDateString()])
            ->get(['booking_date'])
            ->countBy(fn ($b) => $b->booking_date->dayOfWeekIso);
        $weekdays = collect([1 => 'Mon', 2 => 'Tue', 3 => 'Wed', 4 => 'Thu', 5 => 'Fri', 6 => 'Sat', 7 => 'Sun'])
            ->map(fn ($label, $iso) => ['label' => $label, 'count' => $weekdayCounts->get($iso, 0)])
            ->values();

        return view('livewire.business-owner.revenue-report', [
            'rangeFrom' => $from,
            'rangeTo' => $to,
            'days' => $days,
            'current' => $current,
            'previous' => $previous,
            'prevFrom' => $prevFrom,
            'prevTo' => $prevTo,
            'series' => $series,
            'byDay' => $byDay,
            'byCategory' => $byCategory,
            'topServices' => $topServices,
            'weekdays' => $weekdays,
        ]);
    }
}
