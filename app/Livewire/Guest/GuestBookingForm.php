<?php

namespace App\Livewire\Guest;

use Livewire\Component;
use Livewire\Attributes\Layout;
use App\Models\ServiceCategory;
use App\Models\Service;
use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;

#[Layout('layouts.app')]
class GuestBookingForm extends Component
{
    // Customer Information
    public $customerName = '';
    public $customerEmail = '';
    public $customerPhone = '';
    public $customerAddress = '';

    // Vehicle Information
    public $vehicleMake = '';
    public $vehicleModel = '';
    public $vehicleYear = '';
    public $vehiclePlateNumber = '';
    public $vehicleVinNumber = '';

    // Booking Information
    public $selectedCategory = '';
    public $selectedServices = [];
    public $bookingDate = '';
    public $bookingTime = '';
    public $notes = '';
    public $estimatedTotal = 0;
    public $expandedCategories = [];
    public $servicesConfirmed = false;
    public $savedCategories = []; // Track which categories have saved services
    public $currentStep = 1; // 1: Services, 2: Vehicle, 3: Customer Info, 4: Confirmation
    
    // Error modal
    public $showErrorModal = false;
    public $errorMessage = '';

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
        // Restore data from session if available
        $sessionData = session('guest_booking_data', []);
        
        if (!empty($sessionData)) {
            $this->customerName = $sessionData['customerName'] ?? '';
            $this->customerEmail = $sessionData['customerEmail'] ?? '';
            $this->customerPhone = $sessionData['customerPhone'] ?? '';
            $this->customerAddress = $sessionData['customerAddress'] ?? '';
            $this->vehicleMake = $sessionData['vehicleMake'] ?? '';
            $this->vehicleModel = $sessionData['vehicleModel'] ?? '';
            $this->vehicleYear = $sessionData['vehicleYear'] ?? '';
            $this->vehiclePlateNumber = $sessionData['vehiclePlateNumber'] ?? '';
            $this->vehicleVinNumber = $sessionData['vehicleVinNumber'] ?? '';
            $this->selectedServices = $sessionData['selectedServices'] ?? [];
            $this->bookingDate = $sessionData['bookingDate'] ?? now()->addDay()->format('Y-m-d');
            $this->bookingTime = $sessionData['bookingTime'] ?? '09:00';
            $this->notes = $sessionData['notes'] ?? '';
            $this->currentStep = $sessionData['currentStep'] ?? 1;
            $this->servicesConfirmed = $sessionData['servicesConfirmed'] ?? false;
            $this->expandedCategories = $sessionData['expandedCategories'] ?? [];
            $this->savedCategories = $sessionData['savedCategories'] ?? [];
            
            $this->calculateTotal();
        } else {
            $this->bookingDate = now()->addDay()->format('Y-m-d');
            $this->bookingTime = '09:00';
        }
    }

    public function updated($propertyName)
    {
        // Save to session whenever any property is updated
        $this->saveToSession();
    }

    public function saveToSession()
    {
        session([
            'guest_booking_data' => [
                'customerName' => $this->customerName,
                'customerEmail' => $this->customerEmail,
                'customerPhone' => $this->customerPhone,
                'customerAddress' => $this->customerAddress,
                'vehicleMake' => $this->vehicleMake,
                'vehicleModel' => $this->vehicleModel,
                'vehicleYear' => $this->vehicleYear,
                'vehiclePlateNumber' => $this->vehiclePlateNumber,
                'vehicleVinNumber' => $this->vehicleVinNumber,
                'selectedServices' => $this->selectedServices,
                'bookingDate' => $this->bookingDate,
                'bookingTime' => $this->bookingTime,
                'notes' => $this->notes,
                'currentStep' => $this->currentStep,
                'servicesConfirmed' => $this->servicesConfirmed,
                'expandedCategories' => $this->expandedCategories,
                'savedCategories' => $this->savedCategories,
            ]
        ]);
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
        $this->saveToSession();
    }

    public function toggleService($serviceId)
    {
        if (in_array($serviceId, $this->selectedServices)) {
            $this->selectedServices = array_values(array_diff($this->selectedServices, [$serviceId]));
        } else {
            $this->selectedServices[] = $serviceId;
        }
        $this->servicesConfirmed = false;
        
        // Reset saved status for the category of this service
        $service = Service::find($serviceId);
        if ($service) {
            $this->savedCategories = array_values(array_diff($this->savedCategories, [$service->service_category_id]));
        }
        
        $this->calculateTotal();
        $this->saveToSession();
    }

    public function removeService($serviceId)
    {
        $this->selectedServices = array_values(array_diff($this->selectedServices, [$serviceId]));
        $this->servicesConfirmed = false;
        
        // Reset saved status for the category of this service
        $service = Service::find($serviceId);
        if ($service) {
            $this->savedCategories = array_values(array_diff($this->savedCategories, [$service->service_category_id]));
        }
        
        $this->calculateTotal();
        $this->saveToSession();
    }

    public function confirmServices()
    {
        if (count($this->selectedServices) > 0) {
            $this->servicesConfirmed = true;
            $this->currentStep = 2;
            $this->expandedCategories = [];
            $this->saveToSession();
        }
    }

    public function goToStep($step)
    {
        if ($step == 2 && count($this->selectedServices) == 0) {
            $this->showErrorModal = true;
            $this->errorMessage = 'Please select at least one service.';
            return;
        }
        $this->currentStep = $step;
        $this->saveToSession();
    }

    public function nextStep()
    {
        if ($this->currentStep == 1) {
            if (count($this->selectedServices) == 0) {
                $this->showErrorModal = true;
                $this->errorMessage = 'Please select at least one service.';
                return;
            }
            $this->currentStep = 2;
        } elseif ($this->currentStep == 2) {
            $this->validate([
                'vehicleMake' => 'required|string|max:255',
                'vehicleModel' => 'required|string|max:255',
                'vehicleYear' => 'required|integer|min:1900|max:' . (date('Y') + 1),
                'vehiclePlateNumber' => 'required|string|max:255',
            ]);
            $this->currentStep = 3;
        } elseif ($this->currentStep == 3) {
            $this->validate([
                'customerName' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
                'customerEmail' => 'required|email|max:255',
                'customerPhone' => ['required', 'string', 'max:20', 'regex:/^[0-9]+$/'],
                'bookingDate' => 'required|date|after_or_equal:today',
                'bookingTime' => 'required',
            ], [
                'customerName.regex' => 'Full name must contain only letters and spaces.',
                'customerPhone.regex' => 'Phone number must contain only numbers.',
            ]);
            $this->currentStep = 4;
        }
        $this->saveToSession();
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
            $this->saveToSession();
        }
    }

    public function closeErrorModal()
    {
        $this->showErrorModal = false;
        $this->errorMessage = '';
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
            
            // Collapse this category
            $this->expandedCategories = array_values(array_diff($this->expandedCategories, [$categoryId]));
            $this->saveToSession();
        }
    }

    public function resetForm()
    {
        // Clear all form data
        $this->reset([
            'customerName',
            'customerEmail',
            'customerPhone',
            'customerAddress',
            'vehicleMake',
            'vehicleModel',
            'vehicleYear',
            'vehiclePlateNumber',
            'vehicleVinNumber',
            'selectedServices',
            'notes',
            'estimatedTotal',
            'expandedCategories',
            'servicesConfirmed',
            'currentStep'
        ]);
        
        // Reset defaults
        $this->bookingDate = now()->addDay()->format('Y-m-d');
        $this->bookingTime = '09:00';
        
        // Clear session data
        session()->forget('guest_booking_data');
    }

    public function submitBooking()
    {
        // Final validation
        try {
            $this->validate([
                'customerName' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s]+$/'],
                'customerEmail' => 'required|email|max:255',
                'customerPhone' => ['required', 'string', 'max:20', 'regex:/^[0-9]+$/'],
                'vehicleMake' => 'required|string|max:255',
                'vehicleModel' => 'required|string|max:255',
                'vehicleYear' => 'required|integer|min:1900|max:' . (date('Y') + 1),
                'vehiclePlateNumber' => 'required|string|max:255|unique:vehicles,plate_number',
                'selectedServices' => 'required|array|min:1',
                'bookingDate' => 'required|date|after_or_equal:today',
                'bookingTime' => 'required',
            ], [
                'customerName.regex' => 'Full name must contain only letters and spaces.',
                'customerPhone.regex' => 'Phone number must contain only numbers.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Get the first validation error message
            $errors = $e->validator->errors()->all();
            $this->showErrorModal = true;
            $this->errorMessage = implode(' ', $errors);
            return;
        }

        // Check if there's already a booking for this date
        $existingBooking = Booking::where('booking_date', $this->bookingDate)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existingBooking) {
            $this->showErrorModal = true;
            $this->errorMessage = 'Sorry, this date has already been booked by another customer. Please choose a different date.';
            return;
        }

        try {
            // Create guest customer
            $customer = Customer::create([
                'user_id' => null,
                'name' => $this->customerName,
                'email' => $this->customerEmail,
                'phone' => $this->customerPhone,
                'address' => $this->customerAddress,
                'is_guest' => true,
            ]);

            // Create vehicle with error handling
            try {
                $vehicle = Vehicle::create([
                    'customer_id' => $customer->id,
                    'make' => $this->vehicleMake,
                    'model' => $this->vehicleModel,
                    'year' => $this->vehicleYear,
                    'plate_number' => $this->vehiclePlateNumber,
                    'vin_number' => $this->vehicleVinNumber,
                ]);
            } catch (\Illuminate\Database\QueryException $e) {
                // Delete the customer if vehicle creation fails
                $customer->delete();
                
                if ($e->getCode() == 23000) {
                    $this->showErrorModal = true;
                    $this->errorMessage = 'This plate number is already registered in our system. Please check the plate number and try again.';
                    return;
                }
                
                throw $e;
            }

            // Create booking
            $booking = Booking::create([
                'customer_id' => $customer->id,
                'vehicle_id' => $vehicle->id,
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

            // Clear session data after successful booking
            session()->forget('guest_booking_data');

            // Redirect to confirmation page with booking reference
            session()->flash('booking_reference', $booking->booking_reference);
            session()->flash('customer_email', $customer->email);
            session()->flash('success', 'Booking confirmed successfully! Your reference number is: ' . $booking->booking_reference);
            
            return redirect()->route('guest.booking.confirmation', ['reference' => $booking->booking_reference]);
            
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Guest booking submission failed: ' . $e->getMessage());
            
            $this->showErrorModal = true;
            $this->errorMessage = 'An error occurred while processing your booking. Please try again or contact us for assistance.';
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

        // Generate year options (from current year to 1950)
        $currentYear = date('Y');
        $years = range($currentYear + 1, 1950);

        return view('livewire.guest.guest-booking-form', [
            'categories' => $categories,
            'allSelectedServices' => $allSelectedServices,
            'categoryCounts' => $categoryCounts,
            'years' => $years,
        ]);
    }
}
