<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use App\Models\Service;
use App\Models\ServiceCategory;

#[Layout('layouts.admin')]
class ServiceManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $showModal = false;
    public $serviceId;
    public $name;
    public $description;
    public $service_category_id;
    public $base_price;
    public $estimated_duration_minutes;
    public $is_active = true;

    /** Inline notice shown above the list: ['type' => 'message'|'error', 'text' => ...] */
    public $notice = null;

    protected function notify(string $type, string $text): void
    {
        $this->notice = ['type' => $type, 'text' => $text];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function createService()
    {
        $this->resetValidation();
        $this->reset(['serviceId', 'name', 'description', 'service_category_id', 'base_price', 'estimated_duration_minutes', 'is_active']);
        $this->is_active = true;
        $this->showModal = true;
    }

    public function editService($id)
    {
        $this->resetValidation();
        $service = Service::findOrFail($id);
        $this->serviceId = $service->id;
        $this->name = $service->name;
        $this->description = $service->description;
        $this->service_category_id = $service->service_category_id;
        $this->base_price = $service->base_price;
        $this->estimated_duration_minutes = $service->estimated_duration_minutes;
        $this->is_active = $service->is_active;
        $this->showModal = true;
    }

    public function saveService()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'service_category_id' => 'required|exists:service_categories,id',
            'base_price' => 'required|numeric|min:0',
            'estimated_duration_minutes' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        if ($this->serviceId) {
            Service::find($this->serviceId)->update($validated);
            $this->notify('message', 'Service updated successfully.');
        } else {
            Service::create($validated);
            $this->notify('message', 'Service created successfully.');
        }

        $this->showModal = false;
        $this->reset(['serviceId', 'name', 'description', 'service_category_id', 'base_price', 'estimated_duration_minutes', 'is_active']);
    }

    public function deleteService($id)
    {
        $service = Service::withCount('bookings')->findOrFail($id);

        // Removing a booked service would also strip it from past bookings and invoices
        if ($service->bookings_count > 0) {
            $this->notify('error', "{$service->name} is on {$service->bookings_count} " . str('booking')->plural($service->bookings_count) . '. Deactivate it instead so old bookings stay intact.');
            return;
        }

        $service->delete();
        $this->notify('message', 'Service deleted.');
    }

    public function toggleActive($id)
    {
        $service = Service::find($id);
        $service->update(['is_active' => !$service->is_active]);
        $this->notify('message', $service->name . ($service->is_active ? ' is now active.' : ' is now hidden from customers.'));
    }

    public function render()
    {
        $query = Service::with('category')->withCount('bookings');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->categoryFilter) {
            $query->where('service_category_id', $this->categoryFilter);
        }

        $services = $query->orderBy('service_category_id')->orderBy('name')->paginate(10);
        $categories = ServiceCategory::withCount('services')->get();

        return view('livewire.admin.service-management', [
            'services' => $services,
            'categories' => $categories,
        ]);
    }
}
