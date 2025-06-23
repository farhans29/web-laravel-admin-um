<div class="flex flex-col items-start space-y-4">
    <label class="inline-flex items-center">
        <input type="radio" wire:model="roomType" value="daily" class="form-radio accent-gray-400" disabled />
        <span class="ml-2 text-gray-400">Harian</span>
    </label>
    <label class="inline-flex items-center">
        <input type="radio" wire:model="roomType" value="monthly" class="form-radio accent-gray-400" disabled />
        <span class="ml-2 text-gray-400">Bulanan</span>
    </label>
</div>
