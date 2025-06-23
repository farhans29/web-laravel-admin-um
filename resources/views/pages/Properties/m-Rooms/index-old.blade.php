<x-app-layout>
    <div class="container mx-auto px-4 py-6">

        @if (session()->has('success'))
            <div class="bg-green-500 text-white px-4 py-2 rounded shadow-md mb-2">
                {{ session('success') }}
            </div>
            @elseif (session()->has('error'))
            <div class="bg-red-500 text-white px-4 py-2 rounded shadow-md">
                {{ session('error') }}
            </div>
        @endif

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Kamar</h1>
            <div class="mt-4 md:mt-0">
                <!-- AlpineJS Wrapper -->
                <div x-data="{ open: false, step: 1, isValid: true }">

                    <!-- Add Room Button -->
                    <button @click="open = true"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd" />
                        </svg>
                        Tambah Kamar
                    </button>

                    <!-- Modal -->
                    <div x-show="open" x-cloak  class="fixed inset-0 backdrop-blur bg-opacity-30 flex items-center justify-center z-50 transition-opacity"                    >
                        <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl p-6 relative">
                            <!-- Close Button -->
                            <button @click="open = false" class="absolute top-2 right-2 text-gray-600 hover:text-black text-2xl">&times;</button>

                            <!-- Form -->
                            <form
                                x-ref="roomForm"
                                @submit.prevent="
                                    if (step === 2) {
                                        const requireds = $refs.step2Form.querySelectorAll('[required]');
                                        let step2Valid = true;

                                        requireds.forEach(input => {
                                            if (!input.value) {
                                                input.classList.add('border-red-500');
                                                step2Valid = false;
                                            } else {
                                                input.classList.remove('border-red-500');
                                            }
                                        });

                                        if (step2Valid) {
                                            $refs.roomForm.submit(); // Submit to rooms.store
                                        } else {
                                            isValidStep2 = false;
                                        }
                                    }
                                "
                                x-data="{ open: false, step: 1, isValid: true, isValidStep2: true }"
                                method="POST"
                                action="{{ route('rooms.store') }}"
                                enctype="multipart/form-data"
                            >
                                @csrf

                                <!-- Step 1 -->
                                <div x-show="step === 1" x-transition x-ref="step1Form">
                                    <h2 class="text-xl font-semibold mb-4">Informasi Kamar</h2>

                                    <!-- Livewire component -->
                                    @livewire('property-room-selector')

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium">Nama Kamar</label>
                                        <input type="text" name="room_name" required class="w-full border rounded p-2" placeholder="Nama Kamar">
                                    </div>

                                    {{-- <!-- Checkbox For Periode -->
                                    <div class="mb-4">
                                        <div class="flex items-center gap-6">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="available_daily" class="form-checkbox text-blue-600" />
                                                <span class="ml-2">Daily</span>
                                            </label>
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="available_monthly" class="form-checkbox text-blue-600" />
                                                <span class="ml-2">Monthly</span>
                                            </label>
                                        </div>
                                    </div> --}}

                                    <div class="grid grid-cols-2 gap-6 mb-4">
                                        <!-- Daily Pricing -->
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium">Harga Original Harian</label>
                                                <input type="number" name="daily_price" required class="w-full border rounded p-2" placeholder="Harga Harian" />
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium">Harga Diskon Harian</label>
                                                <input type="number" name="daily_discount_price" required class="w-full border rounded p-2" placeholder="Harga Diskon Harian" />
                                            </div>
                                        </div>

                                        <!-- Monthly Pricing -->
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium">Harga Original Bulanan</label>
                                                <input type="number" name="monthly_price" required class="w-full border rounded p-2" placeholder="Harga Bulanan" />
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium">Harga Diskon Bulanan</label>
                                                <input type="number" name="monthly_discount_price" required class="w-full border rounded p-2" placeholder="Harga Diskon Bulanan" />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Note -->
                                    <p class="text-sm text-gray-600 italic mb-4">
                                        Masukkan harga jika kamar tersedia pada periode tersebut. (Jika tidak tersedia, maka input 0 pada original dan diskon)
                                    </p>

                                    <div class="flex gap-4 mb-4">
                                        <div class="w-full">
                                            <label class="block text-sm font-medium">Deskripsi Kamar Indonesia</label>
                                            <textarea name="description_id" required class="w-full border rounded p-2" placeholder="Deskripsi Kamar"></textarea>
                                        </div>
                                        {{-- <div class="w-1/2">
                                            <label class="block text-sm font-medium">Deskripsi Kamar English</label>
                                            <textarea name="description_en" required class="w-full border rounded p-2" placeholder="Deskripsi Kamar"></textarea>
                                        </div> --}}
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-sm font-medium">Foto Kamar</label>
                                        <input type="file" name="photo" accept="image/*" class="w-full border rounded p-2">
                                    </div>

                                    <!-- Validation message -->
                                    <div x-show="!isValid" class="text-red-600 text-sm mb-2">Semua kolom wajib diisi sebelum lanjut.</div>

                                    <div class="flex justify-end gap-2 mt-4">
                                        <button type="button" @click="open = false" class="bg-red-600 text-white px-4 py-2 rounded">Tutup</button>

                                        <!-- VALIDATE and go to Step 2 -->
                                        <button type="button"
                                            @click="
                                                const inputs = $refs.step1Form.querySelectorAll('[required]');
                                                isValid = true;
                                                inputs.forEach(input => {
                                                    if (!input.value) {
                                                        input.classList.add('border-red-500');
                                                        isValid = false;
                                                    } else {
                                                        input.classList.remove('border-red-500');
                                                    }
                                                });
                                                if (isValid) step = 2;
                                            "
                                            class="bg-green-600 text-white px-4 py-2 rounded">
                                            Selanjutnya
                                        </button>
                                    </div>
                                </div>

                                <!-- Step 2 -->
                                <div x-show="step === 2" x-transition x-ref="step2Form">
                                    <h2 class="text-xl font-semibold mb-4">Fasilitas</h2>

                                    <div class="grid grid-cols-2 gap-4 mb-4">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="wifi" class="form-checkbox text-blue-600" />
                                            <span class="ml-2">WiFi</span>
                                        </label>

                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="tv" class="form-checkbox text-blue-600" />
                                            <span class="ml-2">TV</span>
                                        </label>

                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="ac" class="form-checkbox text-blue-600" />
                                            <span class="ml-2">AC</span>
                                        </label>

                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="bathroom" class="form-checkbox text-blue-600" />
                                            <span class="ml-2">Bathroom</span>
                                        </label>
                                    </div>

                                    <div class="flex justify-end gap-2 mt-4">
                                        <button type="button" @click="step = 1" class="bg-gray-500 text-white px-4 py-2 rounded">Kembali</button>
                                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <style>
                        [x-cloak] {
                            display: none !important;
                        }
                    </style>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-lg shadow p-4 mb-6">
            <div class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4">
                <div class="flex-1">
                    <input type="text" id="search-input" placeholder="Cari kamar..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <select id="status-filter"
                        class="w-full md:w-40 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
                <div>
                    <select id="room-filter"
                        class="w-full md:w-48 px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Properti</option>
                        @foreach ($rooms as $room)
                            <option value="{{ $room->idrec }}">{{ $room->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button id="filter-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Filter
                </button>
            </div>
        </div>

        <!-- Room Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Properti</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Kamar</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tgl Penambahan</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tgl Perubahan</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ditambahkan Oleh</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="rooms-table-body">
                        @foreach ($rooms as $room)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $room->property_name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $room->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $room->type }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($room->created_at)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $room->updated_at ? \Carbon\Carbon::parse($room->updated_at)->format('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $room->created_by }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @livewire('room-status-toggle', ['roomId' => $room->idrec, 'status' => $room->status])
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href=""
                                            class="text-blue-600 hover:text-blue-900" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path
                                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                            </svg>
                                        </a>
                                        <form action="" method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus kamar ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900"
                                                title="Hapus">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    <a href="{{ $rooms->previousPageUrl() }}"
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Sebelumnya
                    </a>
                    <a href="{{ $rooms->nextPageUrl() }}"
                        class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Berikutnya
                    </a>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Menampilkan
                            <span class="font-medium">{{ $rooms->firstItem() }}</span>
                            sampai
                            <span class="font-medium">{{ $rooms->lastItem() }}</span>
                            dari
                            <span class="font-medium">{{ $rooms->total() }}</span>
                            hasil
                        </p>
                    </div>
                    <div>
                        {{ $rooms->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Toggle status
           
                // Filter functionality
                const filterBtn = document.getElementById('filter-btn');
                if (filterBtn) {
                    filterBtn.addEventListener('click', function() {
                        const search = document.getElementById('search-input').value;
                        const status = document.getElementById('status-filter').value;
                        const property = document.getElementById('property-filter').value;

                        const url = new URL(window.location.href);
                        const params = new URLSearchParams();

                        if (search) params.append('search', search);
                        if (status) params.append('status', status);
                        if (property) params.append('property', property);

                        window.location.href = url.pathname + '?' + params.toString();
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
