<div>
    <!-- Property Select -->
    <div class="mb-4">
        <label class="block text-sm font-medium">Pilih Properti</label>
        <select wire:model.lazy="selectedProperty" class="w-full border rounded p-2" required>
            <option value="">Pilih Properti</option>
            @foreach($properties as $property)
                <option value="{{ $property->idrec }}">{{ $property->name }}</option>
            @endforeach
        </select>

        <div class="text-xs mt-1 text-gray-500">Selected Property: {{ $selectedProperty }}</div>
    </div>

    <!-- Dependent Dropdowns -->
    <div class="flex gap-4 mb-4">
        <!-- Level -->
        <div class="w-1/2">
            <label class="block text-sm font-medium">Lantai</label>
            <select wire:model="selectedLevel" class="w-full border rounded p-2" @if(!$levelOptions) disabled @endif required>
                <option value="">Pilih Lantai</option>
                @foreach($levelOptions as $level)
                    <option value="{{ $level }}">{{ $level }}</option>
                @endforeach
            </select>
        </div>

        <!-- Room Type -->
        <div class="w-1/2">
            <label class="block text-sm font-medium">Jenis Kamar</label>
            <select wire:model="selectedRoomType" class="w-full border rounded p-2" @if(!$roomTypes) disabled @endif required>
                <option value="">Pilih Jenis Kamar</option>
                @foreach($roomTypes as $room)
                    <option value="{{ $room->idrec }}">{{ $room->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
