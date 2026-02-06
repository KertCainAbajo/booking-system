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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function createService()
    {
        $this->reset(['serviceId', 'name', 'description', 'service_category_id', 'base_price', 'estimated_duration_minutes', 'is_active']);
        $this->is_active = true;
        $this->showModal = true;
    }

    public function editService($id)
    {
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
            session()->flash('message', 'Service updated successfully.');
        } else {
            Service::create($validated);
            session()->flash('message', 'Service created successfully.');
        }

        $this->showModal = false;
        $this->reset(['serviceId', 'name', 'description', 'service_category_id', 'base_price', 'estimated_duration_minutes', 'is_active']);
    }

    public function deleteService($id)
    {
        Service::find($id)->delete();
        session()->flash('message', 'Service deleted successfully.');
    }

    public function toggleActive($id)
    {
        $service = Service::find($id);
        $service->update(['is_active' => !$service->is_active]);
        session()->flash('message', 'Service status updated.');
    }

    public function render()
    {
        $query = Service::with('category');

        if ($this->search) {
            $query->where('name', 'like', '%' . $this->search . '%');
        }

        if ($this->categoryFilter) {
            $query->where('service_category_id', $this->categoryFilter);
        }

        $services = $query->paginate(10);
        $categories = ServiceCategory::all();

        return view('livewire.admin.service-management', [
            'services' => $services,
            'categories' => $categories,
        ]);
    }
}
