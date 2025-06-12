<x-app-layout>
    <div class="container mx-auto px-4 py-6">

        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Harga - {{ $room->name }}</h1>
            <p class="text-sm text-gray-600">Properti: {{ $room->property_name }}</p>
        </div>

        <!-- Price Form Card -->
        <div class="bg-white rounded-lg shadow p-6">
            <form action="{{ route('rooms.prices.update', $room->idrec) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Weekday Price -->
                    <div>
                        <label for="weekday_price" class="block text-sm font-medium text-gray-700">Harga Hari Biasa</label>
                        <input type="number" name="weekday_price" id="weekday_price"
                            value="{{ old('weekday_price', $room->weekday_price) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('weekday_price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Weekend Price -->
                    <div>
                        <label for="weekend_price" class="block text-sm font-medium text-gray-700">Harga Akhir Pekan</label>
                        <input type="number" name="weekend_price" id="weekend_price"
                            value="{{ old('weekend_price', $room->weekend_price) }}"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        @error('weekend_price')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Optional: Custom Date Pricing -->
                    <div class="md:col-span-2">
                        <label for="custom_prices" class="block text-sm font-medium text-gray-700 mb-2">
                            Harga Khusus (Opsional)
                        </label>
                        <textarea name="custom_prices" id="custom_prices" rows="3"
                            class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Format: YYYY-MM-DD=Harga (contoh: 2025-06-01=750000)">
                            {{ old('custom_prices', $room->custom_prices ?? '') }}
                        </textarea>
                        <p class="text-sm text-gray-500 mt-1">Pisahkan dengan baris baru untuk tanggal berbeda.</p>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-6">
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                        Simpan Harga
                    </button>
                    <a href="{{ route('rooms.index') }}"
                        class="ml-3 text-gray-600 hover:text-gray-800 underline">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
