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
    
    // Error modal
    public $showErrorModal = false;
    public $errorMessage = '';
    
    // Success modal
    public $showSuccessModal = false;

    // New vehicle form
    public $showNewVehicleForm = false;
    public $newVehicle = [
        'make' => '',
        'model' => '',
        'year' => '',
        'plate_number' => '',
        'vin_number' => ''
    ];

    // Car brands list
    public $carBrands = [
        'Acura', 'Alfa Romeo', 'Aston Martin', 'Audi', 'Bentley', 'BMW', 'Bugatti',
        'Buick', 'Cadillac', 'Chevrolet', 'Chrysler', 'Citroën', 'Dodge', 'Ferrari',
        'Fiat', 'Ford', 'Genesis', 'GMC', 'Honda', 'Hummer', 'Hyundai', 'Infiniti',
        'Isuzu', 'Jaguar', 'Jeep', 'Kia', 'Lamborghini', 'Land Rover', 'Lexus',
        'Lincoln', 'Lotus', 'Maserati', 'Mazda', 'McLaren', 'Mercedes-Benz', 'MG',
        'Mini', 'Mitsubishi', 'Nissan', 'Opel', 'Peugeot', 'Porsche', 'Ram',
        'Renault', 'Rolls-Royce', 'Saab', 'Subaru', 'Suzuki', 'Tesla', 'Toyota',
        'Volkswagen', 'Volvo'
    ];

    public function mount()
    {
        $this->bookingDate = now()->addDay()->format('Y-m-d');
        $this->bookingTime = '09:00';
    }

    public function updatedSelectedServices()
    {
        $this->calculateTotal();
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

    public function addNewVehicle()
    {
        $this->validate([
            'newVehicle.make' => 'required|string|max:255',
            'newVehicle.model' => 'required|string|max:255',
            'newVehicle.year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'newVehicle.plate_number' => 'required|string|unique:vehicles,plate_number',
        ]);

        $customer = Customer::where('user_id', Auth::id())->first();
        
        try {
            $vehicle = Vehicle::create([
                'customer_id' => $customer->id,
                'make' => $this->newVehicle['make'],
                'model' => $this->newVehicle['model'],
                'year' => $this->newVehicle['year'],
                'plate_number' => $this->newVehicle['plate_number'],
                'vin_number' => $this->newVehicle['vin_number'],
            ]);

            $this->selectedVehicle = $vehicle->id;
            $this->showNewVehicleForm = false;
            $this->reset('newVehicle');
            session()->flash('success', 'Vehicle added successfully!');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                $this->showErrorModal = true;
                $this->errorMessage = 'This plate number is already registered. Please use a different plate number or select the existing vehicle from the dropdown.';
                return;
            }
            
            throw $e;
        }
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
        
        // Validate with error handling
        try {
            $this->validate([
                'selectedVehicle' => 'required|exists:vehicles,id',
                'selectedServices' => 'required|array|min:1',
                'bookingDate' => 'required|date|after_or_equal:today',
                'bookingTime' => 'required',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->validator->errors()->all();
            $this->showErrorModal = true;
            $this->errorMessage = implode(' ', $errors);
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

            // Show success modal instead of redirecting immediately
            $this->showSuccessModal = true;
        } catch (\Exception $e) {
            $this->showErrorModal = true;
            $this->errorMessage = 'An error occurred while creating your booking. Please try again.';
            return;
        }
    }

    public function render()
    {
        $categories = ServiceCategory::with('services')->get();
        
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

        // Generate year options (from current year to 1950)
        $currentYear = date('Y');
        $years = range($currentYear + 1, 1950);

        return view('livewire.customer.booking-form', [
            'categories' => $categories,
            'allSelectedServices' => $allSelectedServices,
            'categoryCounts' => $categoryCounts,
            'vehicles' => $vehicles,
            'years' => $years,
        ]);
    }
}
