<div x-data="{
    confirmToggle(currentStatus) {
        const roomNo = '{{ $roomNo }}';
        if (currentStatus == 1) {
            Swal.fire({
                title: `Nonaktifkan Kamar No. ${roomNo}?`,
                html: `
                    <label for='reason'>Pilih status baru:</label>
                    <select id='reason' class='swal2-input'>
                        <option value=''>-- Pilih --</option>
                        <option value='0'>Inactive</option>
                        <option value='2'>Deleted</option>
                        <option value='3'>Under Maintenance</option>
                        <option value='4'>Reserved</option>
                    </select>
                `,
                showCancelButton: true,
                confirmButtonText: 'Ubah Status',
                cancelButtonText: 'Batal',
                preConfirm: () => {
                    const newStatus = document.getElementById('reason').value;
                    if (!newStatus) {
                        Swal.showValidationMessage('Status harus dipilih');
                        return false;
                    }
                    @this.call('toggleStatus', parseInt(newStatus));
                }
            });
        } else {
            Swal.fire({
                title: `Aktifkan Kamar No. ${roomNo}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Aktifkan',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    @this.call('toggleStatus', 1);
                }
            });
        }
    }
}">
    <label class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox"
            class="sr-only peer"
            :checked="{{ $status }} == 1"
            @click.prevent="confirmToggle({{ $status }})">
        <div
            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
        </div>
        <span class="ml-3 text-sm font-medium text-gray-900">
            {{ $status ? 'Active' : 'Inactive' }}
        </span>
    </label>

    <div class="mt-1 text-sm text-left">
        @php
            $statusLabels = [
                0 => 'Inactive',
                1 => 'Active',
                2 => 'Deleted',
                3 => 'Under Maintenance',
                4 => 'Reserved',
            ];
        @endphp
        <span class="{{ $status == 1 ? 'text-green-600' : 'text-red-600' }}">
            {{ $statusLabels[$status] ?? 'Unknown' }}
        </span>
    </div>
</div>



{{-- <div>
    <button wire:change="updatedStatus(true)">Set On</button>
</div> --}}