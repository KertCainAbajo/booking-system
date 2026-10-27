<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\ServiceCategory;
use App\Models\Service;
use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

#[Layout('layouts.customer')]
class BookingForm extends Component
{
    public $selectedCategory = '';
    public $selectedServices = [];
    public $selectedVehicle = '';
    public $bookingDate = '';
    public $bookingTime = '';
    public $notes = '';
    public $estimatedTotal = 0;
    public $expandedCategories = []; // Track which categories are expanded
    public $servicesConfirmed = false; // Track if user clicked Save & Continue
    public $savedCategories = []; // Track which categories have saved services
    public $policyConsent = false;

    // Customer information
    public $customerName = '';
    public $customerPhone = '';
    public $customerAddress = '';

    // Error modal
    public $showErrorModal = false;
    public $errorMessage = '';

    // Success modal
    public $showSuccessModal = false;

    // New vehicle: entered inline and saved together with the booking
    public $showNewVehicleForm = false;
    public $carName = ''; // e.g. "Toyota Vios" - split into make/model on save
    public $newVehicle = [
        'year' => '',
        'plate_number' => '',
        'vin_number' => ''
    ];

    // Car models by brand, used for the "Your car" suggestions
    protected array $carModels = [
        'Toyota' => ['Vios', 'Wigo', 'Innova', 'Fortuner', 'Hilux', 'Rush', 'Avanza', 'Veloz', 'Raize', 'Corolla Altis', 'Corolla Cross', 'Camry', 'Hiace', 'Land Cruiser', 'RAV4', 'Yaris', 'Prius', 'Tamaraw'],
        'Mitsubishi' => ['Mirage', 'Mirage G4', 'Xpander', 'Xpander Cross', 'Montero Sport', 'Strada', 'L300', 'Adventure', 'Outlander', 'Eclipse Cross'],
        'Honda' => ['City', 'Civic', 'Jazz', 'BR-V', 'HR-V', 'CR-V', 'Brio', 'Accord', 'Mobilio', 'Odyssey'],
        'Nissan' => ['Almera', 'Navara', 'Terra', 'Urvan', 'Kicks', 'Patrol', 'Juke', 'Sentra', 'X-Trail', 'Livina'],
        'Ford' => ['Ranger', 'Everest', 'Territory', 'EcoSport', 'Raptor', 'Mustang', 'Explorer', 'Fiesta', 'Focus', 'F-150', 'Expedition'],
        'Isuzu' => ['D-Max', 'mu-X', 'Crosswind', 'Traviz', 'N-Series', 'Alterra'],
        'Suzuki' => ['Ertiga', 'Swift', 'Celerio', 'Dzire', 'Jimny', 'S-Presso', 'XL7', 'APV', 'Carry', 'Vitara'],
        'Hyundai' => ['Accent', 'Reina', 'Creta', 'Stargazer', 'Tucson', 'Santa Fe', 'Starex', 'Elantra', 'Kona', 'Palisade'],
        'Kia' => ['Picanto', 'Soluto', 'Stonic', 'Seltos', 'Sportage', 'Sorento', 'Carnival', 'Rio', 'K2500'],
        'Chevrolet' => ['Trailblazer', 'Colorado', 'Spark', 'Sail', 'Trax', 'Tracker', 'Captiva', 'Suburban', 'Tahoe'],
        'Mazda' => ['Mazda2', 'Mazda3', 'Mazda6', 'CX-3', 'CX-30', 'CX-5', 'CX-9', 'BT-50', 'MX-5'],
        'Subaru' => ['Forester', 'XV', 'Outback', 'Impreza', 'WRX', 'BRZ', 'Legacy'],
        'Geely' => ['Coolray', 'Okavango', 'Emgrand', 'Azkarra'],
        'MG' => ['MG5', 'ZS', 'RX5', 'MG3', 'HS'],
        'Chery' => ['Tiggo 2', 'Tiggo 5x', 'Tiggo 7 Pro', 'Tiggo 8'],
        'BYD' => ['Atto 3', 'Dolphin', 'Seal', 'Sealion'],
        'Foton' => ['Gratour', 'Thunder', 'Toplander', 'Tornado'],
        'Volkswagen' => ['Santana', 'Lavida', 'Lamando', 'T-Cross', 'Tiguan', 'Golf', 'Jetta'],
        'BMW' => ['3 Series', '5 Series', '7 Series', 'X1', 'X3', 'X5', 'X7'],
        'Mercedes-Benz' => ['A-Class', 'C-Class', 'E-Class', 'S-Class', 'GLA', 'GLC', 'GLE', 'Sprinter'],
        'Audi' => ['A3', 'A4', 'A6', 'Q3', 'Q5', 'Q7'],
        'Lexus' => ['ES', 'IS', 'LS', 'RX', 'NX', 'LX'],
        'Jeep' => ['Wrangler', 'Grand Cherokee', 'Cherokee', 'Compass', 'Gladiator'],
        'Peugeot' => ['2008', '3008', '5008'],
        'Volvo' => ['S60', 'S90', 'XC40', 'XC60', 'XC90'],
        'Land Rover' => ['Defender', 'Discovery', 'Range Rover', 'Range Rover Sport', 'Range Rover Evoque'],
        'Porsche' => ['911', 'Cayenne', 'Macan', 'Panamera', 'Taycan'],
        'Tesla' => ['Model 3', 'Model Y', 'Model S', 'Model X'],
        'Mini' => ['Cooper', 'Countryman', 'Clubman'],
        'Dodge' => ['Charger', 'Challenger', 'Durango'],
        'GMC' => ['Sierra', 'Yukon', 'Canyon'],
        'Ram' => ['1500', '2500'],
    ];

    public function mount()
    {
        $this->bookingDate = now()->addDay()->format('Y-m-d');
        $this->bookingTime = '09:00';

        // Pre-fill customer information
        $customer = Customer::where('user_id', Auth::id())->first();
        if ($customer) {
            $this->customerName = $customer->getDisplayName();
            $this->customerPhone = $customer->getContactPhone();
            $this->customerAddress = $customer->address ?? '';

            // First-time customers go straight to entering their car; a single saved car is picked for them
            $vehicleIds = $customer->vehicles()->pluck('id');
            if ($vehicleIds->isEmpty()) {
                $this->showNewVehicleForm = true;
            } elseif ($vehicleIds->count() === 1) {
                $this->selectedVehicle = $vehicleIds->first();
            }
        }
    }

    public function updatedSelectedServices()
    {
        $this->calculateTotal();
    }

    public function chooseVehicle($vehicleId)
    {
        $this->selectedVehicle = $vehicleId;
        $this->showNewVehicleForm = false;
        $this->resetErrorBag(['selectedVehicle', 'carName', 'newVehicle.year', 'newVehicle.plate_number', 'newVehicle.vin_number']);
    }

    public function startNewVehicle()
    {
        $this->selectedVehicle = '';
        $this->showNewVehicleForm = true;
    }

    public function cancelNewVehicle()
    {
        $this->showNewVehicleForm = false;
        $this->reset(['newVehicle', 'carName']);
    }

    public function calculateTotal()
    {
        $this->estimatedTotal = Service::whereIn('id', $this->selectedServices)->sum('base_price');
    }

    public function toggleCategory($categoryId)
    {
        if (in_array($categoryId, $this->expandedCategories)) {
            $this->expandedCategories = array_values(array_diff($this->expandedCategories, [$categoryId]));
        } else {
            $this->expandedCategories[] = $categoryId;
        }
    }

    public function toggleService($serviceId)
    {
        if (in_array($serviceId, $this->selectedServices)) {
            $this->selectedServices = array_values(array_diff($this->selectedServices, [$serviceId]));
        } else {
            $this->selectedServices[] = $serviceId;
        }
        // Reset confirmation when services are modified
        $this->servicesConfirmed = false;

        // Reset saved status for the category of this service
        $service = Service::find($serviceId);
        if ($service) {
            $this->savedCategories = array_values(array_diff($this->savedCategories, [$service->service_category_id]));
        }

        $this->calculateTotal();
    }

    public function removeService($serviceId)
    {
        $this->selectedServices = array_values(array_diff($this->selectedServices, [$serviceId]));
        // Reset confirmation when services are modified
        $this->servicesConfirmed = false;

        // Reset saved status for the category of this service
        $service = Service::find($serviceId);
        if ($service) {
            $this->savedCategories = array_values(array_diff($this->savedCategories, [$service->service_category_id]));
        }

        $this->calculateTotal();
    }

    public function saveCategoryServices($categoryId)
    {
        // Get services for this category
        $categoryServiceIds = Service::where('service_category_id', $categoryId)
            ->pluck('id')
            ->toArray();

        // Check if any services from this category are selected
        $selectedInCategory = array_intersect($this->selectedServices, $categoryServiceIds);

        if (count($selectedInCategory) > 0) {
            // Mark this category as saved
            if (!in_array($categoryId, $this->savedCategories)) {
                $this->savedCategories[] = $categoryId;
            }

            $category = ServiceCategory::find($categoryId);
            $serviceCount = count($selectedInCategory);
            $message = $serviceCount . ' service' . ($serviceCount > 1 ? 's' : '') . ' from ' . $category->name . ' saved successfully!';
            session()->flash('success', $message);

            // Collapse this category
            $this->expandedCategories = array_values(array_diff($this->expandedCategories, [$categoryId]));
        }
    }

    public function confirmServices()
    {
        if (count($this->selectedServices) > 0) {
            $wasConfirmed = $this->servicesConfirmed;
            $this->servicesConfirmed = true;
            $message = $wasConfirmed
                ? 'Services updated successfully! You can continue adding more or proceed with booking.'
                : 'Services saved! You can continue adding more services or proceed with booking details.';
            session()->flash('services-saved', $message);

            // Close all expanded categories
            $this->expandedCategories = [];
        }
    }

    public function getServiceCountByCategory($categoryId)
    {
        if (empty($this->selectedServices)) {
            return 0;
        }

        $categoryServiceIds = Service::where('service_category_id', $categoryId)
            ->pluck('id')
            ->toArray();

        return count(array_intersect($this->selectedServices, $categoryServiceIds));
    }

    /**
     * Split a typed car name like "Toyota Vios" into make and model,
     * recognising multi-word brands such as "Land Rover".
     */
    protected function splitCarName(string $name): array
    {
        $name = trim(preg_replace('/\s+/', ' ', $name));

        foreach (array_keys($this->carModels) as $brand) {
            if (Str::startsWith(Str::lower($name . ' '), Str::lower($brand . ' '))) {
                return [$brand, trim(Str::substr($name, Str::length($brand)))];
            }
        }

        $parts = explode(' ', $name, 2);

        return [Str::title($parts[0]), $parts[1] ?? ''];
    }

    protected function vehicleRules(): array
    {
        return [
            'carName' => ['required', 'string', 'max:255', function ($attribute, $value, $fail) {
                [, $model] = $this->splitCarName((string) $value);
                if ($model === '') {
                    $fail('Please include the model too, for example "Toyota Vios".');
                }
            }],
            'newVehicle.year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'newVehicle.plate_number' => 'required|string|max:20|unique:vehicles,plate_number',
            'newVehicle.vin_number' => 'nullable|string|max:50',
        ];
    }

    protected function vehicleMessages(): array
    {
        return [
            'carName.required' => 'Please tell us your car, for example "Toyota Vios".',
            'newVehicle.year.required' => 'Please choose the year of your car.',
            'newVehicle.plate_number.required' => 'Please enter your plate number.',
            'newVehicle.plate_number.unique' => 'This plate number is already registered. If it is your car, pick it from your saved cars instead.',
        ];
    }

    public function closeErrorModal()
    {
        $this->showErrorModal = false;
        $this->errorMessage = '';
    }

    public function closeSuccessModal()
    {
        $this->showSuccessModal = false;
        return redirect()->route('customer.tracker');
    }

    public function submitBooking()
    {
        // Check for services first
        if (empty($this->selectedServices) || count($this->selectedServices) == 0) {
            $this->showErrorModal = true;
            $this->errorMessage = 'Please select at least one service.';
            return;
        }

        $addingVehicle = $this->showNewVehicleForm;
        if ($addingVehicle) {
            $this->newVehicle['plate_number'] = Str::upper(trim((string) $this->newVehicle['plate_number']));
        }

        // Validate the booking and, when entering a new car, the car details in one pass
        $rules = [
            'customerName' => 'required|string|max:255',
            'customerPhone' => 'required|string|max:20',
            'customerAddress' => 'required|string|max:500',
            'selectedServices' => 'required|array|min:1',
            'selectedServices.*' => 'exists:services,id,is_active,1',
            'bookingDate' => 'required|date|after_or_equal:today',
            'bookingTime' => 'required',
            'policyConsent' => 'accepted',
        ];
        $rules += $addingVehicle
            ? $this->vehicleRules()
            : ['selectedVehicle' => 'required|exists:vehicles,id'];

        try {
            $this->validate($rules, $this->vehicleMessages() + [
                'selectedVehicle.required' => 'Please choose which car this booking is for.',
                'selectedServices.*.exists' => 'One of the services you picked is no longer offered. Please remove it and try again.',
            ]);
        } catch (ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
            $this->showErrorModal = true;
            $this->errorMessage = implode(' ', $e->validator->errors()->all());
            return;
        }

        // Check if there's already an approved booking for this day (First Come First Serve)
        // Only completed, cancelled, or no_show bookings free up the day for new bookings
        $existingBooking = Booking::where('booking_date', $this->bookingDate)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingBooking) {
            $this->showErrorModal = true;
            $this->errorMessage = 'Sorry, this date has already been booked by another customer. The booking must be completed before accepting new bookings. Please choose a different date.';
            return;
        }

        try {
            $customer = Customer::where('user_id', Auth::id())->first();

            DB::transaction(function () use ($customer, $addingVehicle) {
                if ($addingVehicle) {
                    [$make, $model] = $this->splitCarName($this->carName);

                    $vehicle = Vehicle::create([
                        'customer_id' => $customer->id,
                        'make' => $make,
                        'model' => $model,
                        'year' => $this->newVehicle['year'],
                        'plate_number' => $this->newVehicle['plate_number'],
                        'vin_number' => $this->newVehicle['vin_number'] ?: null,
                    ]);

                    $this->selectedVehicle = $vehicle->id;
                }

                $booking = Booking::create([
                    'customer_id' => $customer->id,
                    'vehicle_id' => $this->selectedVehicle,
                    'booking_date' => $this->bookingDate,
                    'booking_time' => $this->bookingTime,
                    'status' => 'pending',
                    'total_amount' => $this->estimatedTotal,
                    'notes' => $this->notes,
                ]);

                // Attach services
                foreach ($this->selectedServices as $serviceId) {
                    $service = Service::find($serviceId);
                    if ($service) {
                        $booking->services()->attach($serviceId, [
                            'quantity' => 1,
                            'price' => $service->base_price,
                        ]);
                    }
                }
            });

            if ($addingVehicle) {
                $this->showNewVehicleForm = false;
                $this->reset(['newVehicle', 'carName']);
            }

            // Broadcast event to update staff dashboard in real-time
            $this->dispatch('booking-created')->to(\App\Livewire\Staff\Dashboard::class);

            // Show success modal instead of redirecting immediately
            $this->showSuccessModal = true;
        } catch (\Exception $e) {
            if ($addingVehicle) {
                $this->selectedVehicle = '';
            }
            $this->showErrorModal = true;
            $this->errorMessage = 'An error occurred while creating your booking. Please try again.';
            return;
        }
    }

    public function render()
    {
        $categories = ServiceCategory::with(['services' => fn ($q) => $q->where('is_active', true)])->get();

        // Get all selected services (for summary sidebar)
        $allSelectedServices = !empty($this->selectedServices)
            ? Service::whereIn('id', $this->selectedServices)->get()
            : collect();

        // Get service counts per category
        $categoryCounts = [];
        foreach ($categories as $category) {
            $categoryCounts[$category->id] = $this->getServiceCountByCategory($category->id);
        }

        $customer = Customer::where('user_id', Auth::id())->first();
        $vehicles = $customer ? $customer->vehicles : collect();

        // Generate year options (from next year back to 1950)
        $currentYear = date('Y');
        $years = range($currentYear + 1, 1950);

        return view('livewire.customer.booking-form', [
            'categories' => $categories,
            'allSelectedServices' => $allSelectedServices,
            'categoryCounts' => $categoryCounts,
            'vehicles' => $vehicles,
            'years' => $years,
            'carCatalog' => $this->carModels,
        ]);
    }
}
