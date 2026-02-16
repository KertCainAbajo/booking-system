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
    
    // Customer information
    public $customerName = '';
    public $customerPhone = '';
    
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
    public $brandSelection = ''; // 'predefined' or 'other'
    public $customBrand = ''; // for custom brand input
    public $modelSelection = ''; // selected model or 'other'
    public $customModel = ''; // for custom model input
    public $availableModels = []; // models for selected brand

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

    // Car models by brand
    public $carModels = [
        'Toyota' => ['Camry', 'Corolla', 'RAV4', 'Highlander', '4Runner', 'Tacoma', 'Tundra', 'Sienna', 'Prius', 'Avalon', 'Yaris', 'C-HR', 'Venza', 'Sequoia'],
        'Honda' => ['Civic', 'Accord', 'CR-V', 'Pilot', 'HR-V', 'Odyssey', 'Ridgeline', 'Passport', 'Insight', 'Fit'],
        'Ford' => ['F-150', 'Mustang', 'Explorer', 'Escape', 'Edge', 'Expedition', 'Ranger', 'Bronco', 'Maverick', 'Transit', 'Fusion', 'Focus', 'EcoSport'],
        'Chevrolet' => ['Silverado', 'Equinox', 'Malibu', 'Traverse', 'Tahoe', 'Suburban', 'Colorado', 'Blazer', 'Trax', 'Camaro', 'Corvette', 'Impala', 'Cruze'],
        'Nissan' => ['Altima', 'Sentra', 'Rogue', 'Pathfinder', 'Frontier', 'Titan', 'Murano', 'Kicks', 'Armada', 'Maxima', 'Versa', '370Z', 'Leaf'],
        'BMW' => ['3 Series', '5 Series', '7 Series', 'X1', 'X3', 'X5', 'X7', 'Z4', 'i4', 'iX', 'M3', 'M5', '4 Series', 'X6'],
        'Mercedes-Benz' => ['C-Class', 'E-Class', 'S-Class', 'GLC', 'GLE', 'GLA', 'GLB', 'GLS', 'A-Class', 'CLA', 'EQS', 'EQE', 'G-Class'],
        'Audi' => ['A3', 'A4', 'A6', 'A8', 'Q3', 'Q5', 'Q7', 'Q8', 'e-tron', 'TT', 'R8', 'S4', 'S6', 'RS5'],
        'Lexus' => ['ES', 'IS', 'LS', 'RX', 'NX', 'GX', 'LX', 'UX', 'RC', 'LC'],
        'Hyundai' => ['Elantra', 'Sonata', 'Tucson', 'Santa Fe', 'Palisade', 'Kona', 'Venue', 'Ioniq', 'Veloster', 'Accent'],
        'Kia' => ['Forte', 'Optima', 'Sorento', 'Sportage', 'Telluride', 'Soul', 'Seltos', 'Niro', 'Stinger', 'Rio', 'Carnival'],
        'Mazda' => ['Mazda3', 'Mazda6', 'CX-3', 'CX-5', 'CX-9', 'CX-30', 'CX-50', 'MX-5 Miata'],
        'Subaru' => ['Outback', 'Forester', 'Crosstrek', 'Impreza', 'Legacy', 'Ascent', 'WRX', 'BRZ'],
        'Volkswagen' => ['Jetta', 'Passat', 'Tiguan', 'Atlas', 'Golf', 'ID.4', 'Taos', 'Arteon'],
        'Jeep' => ['Wrangler', 'Grand Cherokee', 'Cherokee', 'Compass', 'Renegade', 'Gladiator', 'Wagoneer', 'Grand Wagoneer'],
        'Ram' => ['1500', '2500', '3500', 'ProMaster'],
        'GMC' => ['Sierra', 'Terrain', 'Acadia', 'Yukon', 'Canyon'],
        'Dodge' => ['Charger', 'Challenger', 'Durango', 'Journey'],
        'Tesla' => ['Model 3', 'Model S', 'Model X', 'Model Y', 'Cybertruck'],
        'Porsche' => ['911', 'Cayenne', 'Macan', 'Panamera', 'Taycan', 'Boxster', 'Cayman'],
        'Volvo' => ['S60', 'S90', 'V60', 'V90', 'XC40', 'XC60', 'XC90'],
        'Land Rover' => ['Range Rover', 'Range Rover Sport', 'Range Rover Evoque', 'Discovery', 'Defender'],
        'Acura' => ['ILX', 'TLX', 'RLX', 'RDX', 'MDX', 'NSX'],
        'Infiniti' => ['Q50', 'Q60', 'QX50', 'QX55', 'QX60', 'QX80'],
        'Cadillac' => ['CT4', 'CT5', 'Escalade', 'XT4', 'XT5', 'XT6', 'Lyriq'],
        'Buick' => ['Encore', 'Envision', 'Enclave'],
        'Lincoln' => ['Corsair', 'Nautilus', 'Aviator', 'Navigator'],
        'Genesis' => ['G70', 'G80', 'G90', 'GV70', 'GV80'],
        'Mitsubishi' => ['Mirage', 'Outlander', 'Eclipse Cross', 'Outlander Sport'],
        'Mini' => ['Cooper', 'Countryman', 'Clubman'],
        'Alfa Romeo' => ['Giulia', 'Stelvio'],
        'Jaguar' => ['XE', 'XF', 'F-Pace', 'E-Pace', 'I-Pace'],
        'Maserati' => ['Ghibli', 'Levante', 'Quattroporte'],
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
        }
    }

    public function updatedSelectedServices()
    {
        $this->calculateTotal();
    }

    public function updatedBrandSelection($value)
    {
        // Reset model selection when brand changes
        $this->modelSelection = '';
        $this->customModel = '';
        $this->newVehicle['model'] = '';
        
        // If a predefined brand is selected, set it to newVehicle.make
        if ($value && $value !== 'other') {
            $this->newVehicle['make'] = $value;
            // Load models for this brand
            $this->availableModels = $this->carModels[$value] ?? [];
        } else {
            $this->newVehicle['make'] = '';
            $this->availableModels = [];
        }
    }

    public function updatedModelSelection($value)
    {
        // If a predefined model is selected, set it to newVehicle.model
        if ($value && $value !== 'other') {
            $this->newVehicle['model'] = $value;
        } else {
            $this->newVehicle['model'] = '';
        }
    }

    public function cancelNewVehicle()
    {
        $this->showNewVehicleForm = false;
        $this->reset(['newVehicle', 'brandSelection', 'customBrand', 'modelSelection', 'customModel', 'availableModels']);
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
        // If 'Other' is selected, use custom brand
        if ($this->brandSelection === 'other') {
            $this->newVehicle['make'] = $this->customBrand;
        }

        // If 'Other' is selected for model, use custom model
        if ($this->modelSelection === 'other') {
            $this->newVehicle['model'] = $this->customModel;
        }

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
            $this->reset(['newVehicle', 'brandSelection', 'customBrand', 'modelSelection', 'customModel', 'availableModels']);
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
                'customerName' => 'required|string|max:255',
                'customerPhone' => 'required|string|max:20',
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

            // Broadcast event to update staff dashboard in real-time
            $this->dispatch('booking-created')->to(\App\Livewire\Staff\Dashboard::class);

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
