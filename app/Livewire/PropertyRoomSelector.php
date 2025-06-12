<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Property;
use App\Models\RoomType;

class PropertyRoomSelector extends Component
{
    public $properties;
    public $userProperty;
    public $selectedProperty = null;
    public $levelOptions = [];
    public $roomTypes = [];

    public $selectedLevel = null;
    public $selectedRoomType = null;

    public function mount()
    {
        if ($this->userProperty != 0) {
            $this->properties = Property::where('idrec', $this->userProperty)->get();
        } else {
            $this->properties = Property::orderBy('name', 'asc')->get();
        }

        if ($this->properties->isNotEmpty()) {
            $first = $this->properties->first();

            $this->selectedProperty = $first->idrec;

            // Set levelOptions and roomTypes based on the first property
            $this->levelOptions = range(1, $first->level_count);
            $this->roomTypes = RoomType::where('property_id', $first->idrec)->get();
        }
    }

    public function updatedSelectedProperty($value)
    {
        // logger("UPDATED selectedProperty = " . $value); // Write to laravel.log

        $property = Property::where('idrec', $value)->first();

        if ($property) {
            $this->levelOptions = range(1, $property->level_count); // Create array [1, 2, ..., level_count]
            $this->roomTypes = RoomType::where('property_id', $property->idrec)->get();
        } else {
            $this->levelOptions = [];
            $this->roomTypes = [];
        }

        $this->selectedLevel = null;
        $this->selectedRoomType = null;
        
        // Livewire 3: Correct browser event dispatch
        $this->dispatch('property-changed', [
            'property' => $this->selectedProperty,
            'level' => $this->selectedLevel,
            'roomType' => $this->selectedRoomType,
        ]);
    }

    public function getSelectedPropertyNameProperty()
    {
        $property = collect($this->properties)->firstWhere('idrec', $this->selectedProperty);
        return $property ? $property['name'] : '';
    }

    public function render()
    {
        return view('livewire.property-room-selector');
    }
}
