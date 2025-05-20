<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Property;
use App\Models\RoomType;

class PropertyRoomSelector extends Component
{
    public $properties;
    public $selectedProperty = null;
    public $levelOptions = [];
    public $roomTypes = [];

    public $selectedLevel = null;
    public $selectedRoomType = null;

    public function mount()
    {
        $this->properties = Property::all(); // Load all properties at start
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
    }

    public function render()
    {
        return view('livewire.property-room-selector');
    }
}
