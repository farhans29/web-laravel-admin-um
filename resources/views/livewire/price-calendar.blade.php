<!-- livewire/price-calendar.blade.php -->
<div>
    <x-datepicker wire:model="selectedDate" />

    <div class="mt-4 text-sm">
        Harga untuk tanggal {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}:
        <strong>{{ $price }}</strong>
    </div>
</div>