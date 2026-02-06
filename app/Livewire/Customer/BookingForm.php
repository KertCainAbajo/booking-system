<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use App\Models\ServiceCategory;
use App\Models\Service;
use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;

class BookingForm extends Component
{
    public $selectedCategory = '';
    public $selectedServices = [];
    public $selectedVehicle = '';
    public $bookingDate = '';
    public $bookingTime = '';
    public $notes = '';
    public $estimatedTotal = 0;

    // New vehicle form
    public $showNewVehicleForm = false;
    public $newVehicle = [
        'make' => '',
        'model' => '',
        'year' => '',
        'plate_number' => '',
        'vin_number' => ''
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

    public function addNewVehicle()
    {
        $this->validate([
            'newVehicle.make' => 'required|string|max:255',
            'newVehicle.model' => 'required|string|max:255',
            'newVehicle.year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'newVehicle.plate_number' => 'required|string|unique:vehicles,plate_number',
        ]);

        $customer = Customer::where('user_id', Auth::id())->first();
        
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
    }

    public function submitBooking()
    {
        $this->validate([
            'selectedVehicle' => 'required|exists:vehicles,id',
            'selectedServices' => 'required|array|min:1',
            'bookingDate' => 'required|date|after:today',
            'bookingTime' => 'required',
        ]);

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
            $booking->services()->attach($serviceId, [
                'quantity' => 1,
                'price' => $service->base_price,
            ]);
        }

        session()->flash('success', 'Booking submitted successfully! We will confirm shortly.');
        
        return redirect()->route('customer.tracker');
    }

    public function render()
    {
        $categories = ServiceCategory::with('services')->get();
        $services = $this->selectedCategory 
            ? Service::where('service_category_id', $this->selectedCategory)->get()
            : collect();
        
        $customer = Customer::where('user_id', Auth::id())->first();
        $vehicles = $customer ? $customer->vehicles : collect();

        return view('livewire.customer.booking-form', [
            'categories' => $categories,
            'services' => $services,
            'vehicles' => $vehicles,
        ]);
    }
}
