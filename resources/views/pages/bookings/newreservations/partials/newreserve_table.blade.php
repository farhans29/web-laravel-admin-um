<table class="min-w-full divide-y divide-gray-200">
    <thead class="bg-gray-50">
        <tr>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Check-in
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Check-out
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Order ID
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Name
            </th>
            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Property/Room
            </th>
            @if ($showStatus ?? true)
                <th scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Status
                </th>
            @endif
            @if ($showActions ?? true)
                <th scope="col"
                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                </th>
            @endif
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse ($checkIns as $booking)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if ($booking->transaction && $booking->transaction->check_in)
                        <div class="flex flex-col">
                            <span class="text-sm font-semibold text-gray-800">
                                {{ \Carbon\Carbon::parse($booking->transaction->check_in)->format('Y M d') }}
                            </span>
                            <span class="text-xs text-gray-500 mt-0.5">
                                {{ \Carbon\Carbon::parse($booking->transaction->check_in)->format('H:i') }}
                            </span>
                        </div>
                    @else
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            Not checked in
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                    @if ($booking->transaction->check_out)
                        <div class="text-sm font-medium text-gray-900">
                            {{ $booking->transaction->check_out->format('Y M d') }}
                        </div>
                        <div class="text-xs text-gray-400">
                            {{ $booking->transaction->check_out->format('H:i') }}
                        </div>
                    @else
                        <div class="text-sm text-gray-500 italic">Not checked out</div>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    <div class="text-sm font-medium text-indigo-600">{{ $booking->order_id }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $booking->transaction->user_name }}</div>
                            <div class="text-sm text-gray-500">{{ $booking->transaction->user_email }}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left">
                    <div class="text-sm font-medium text-gray-900">
                        {{ $booking->property->name ?? 'N/A' }}</div>
                    <div class="text-sm text-gray-500">{{ $booking->room->name ?? 'N/A' }}</div>
                </td>
                @if ($showStatus ?? true)
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                        @php
                            $statusClasses = [
                                'Waiting For Check-In' => 'bg-yellow-100 text-yellow-800',
                                'Checked-In' => 'bg-green-100 text-green-800',
                                'Checked-Out' => 'bg-blue-100 text-blue-800',
                                'Unknown' => 'bg-gray-100 text-gray-800',
                            ];
                        @endphp
                        <div class="flex flex-col items-center">
                            <span
                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ $booking->status }}
                            </span>
                            @if ($booking->check_in_at)
                                <div
                                    class="inline-flex items-center mt-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    {{ $booking->check_in_at->format('Y-m-d H:i') }}
                                </div>
                            @endif
                        </div>
                    </td>
                @endif
                @if ($showActions ?? true)
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                        @if (is_null($booking->check_in_at))
                            <div class="flex flex-col items-center space-y-2">
                                <a href="{{ route('newReserv.checkin.regist', $booking->order_id) }}" target="_blank"
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-amber-600 rounded hover:bg-amber-700 focus:outline-none">
                                    Print Regist Form
                                </a>
                                <div x-data="checkInModal('{{ $booking->order_id }}')">
                                    <!-- Tombol Trigger -->
                                    <button type="button"
                                        @click="openModal('{{ $booking->idrec }}', '{{ $booking->order_id }}')"
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-green-600 rounded hover:bg-green-700 focus:outline-none">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" stroke-width="2"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 21V3a1 1 0 011-1h5.5a1 1 0 011 1v2m0 0v14m0-14l7 2v14l-7-2">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h1"></path>
                                        </svg>
                                        Check-In
                                    </button>

                                    <!-- Backdrop Modal -->
                                    <div class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 transition-opacity"
                                        x-show="isOpen" x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition ease-out duration-200"
                                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                        aria-hidden="true" x-cloak></div>

                                    <!-- Dialog Modal -->
                                    <div class="fixed inset-0 z-50 overflow-hidden flex items-center my-4 justify-center px-4 sm:px-6"
                                        role="dialog" aria-modal="true" x-show="isOpen"
                                        x-transition:enter="transition ease-in-out duration-300"
                                        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                        x-transition:leave="transition ease-in-out duration-200"
                                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                                        x-transition:leave-end="opacity-0 translate-y-4 scale-95" x-cloak>

                                        <div class="bg-white rounded-lg shadow-xl overflow-auto w-full overflow-auto max-h-full flex flex-col text-left max-w-7xl"
                                            @click.outside="closeModal" @keydown.escape.window="closeModal">

                                            <!-- Header Modal -->
                                            <div
                                                class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
                                                <div class="flex justify-between items-center">
                                                    <div class="font-bold text-xl text-gray-800">Proses Check-In</div>
                                                    <button type="button"
                                                        class="text-gray-400 hover:text-gray-600 transition-colors duration-200"
                                                        @click="closeModal">
                                                        <div class="sr-only">Tutup</div>
                                                        <svg class="w-6 h-6 fill-current">
                                                            <path
                                                                d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
                                                        </svg>
                                                    </button>
                                                </div>
                                                <p class="text-sm text-gray-600 mt-1">Silakan tinjau informasi dan
                                                    selesaikan
                                                    proses check-in</p>
                                                <p class="text-lg font-bold text-gray-800 mt-1"
                                                    x-text="currentDateTime">
                                                </p>
                                            </div>

                                            <!-- Konten Modal -->
                                            <div class="flex-1 overflow-y-auto px-6 py-6">
                                                <!-- Konten Check-in - Diatur dalam alur logis -->
                                                <div class="space-y-8">
                                                    <!-- Bagian 1: Detail Pemesanan -->
                                                    <div class="bg-gray-50 p-6 rounded-lg shadow-sm">
                                                        <h3
                                                            class="font-semibold text-lg text-gray-800 mb-4 flex items-center">
                                                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                            </svg>
                                                            Detail Pemesanan
                                                        </h3>

                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                            <div class="flex justify-between">
                                                                <span class="text-sm font-medium text-gray-600">ID
                                                                    Pesanan:</span>
                                                                <span class="text-sm text-gray-800 font-mono"
                                                                    x-text="bookingDetails.order_id"></span>
                                                            </div>
                                                            <div class="flex justify-between">
                                                                <span class="text-sm font-medium text-gray-600">Tanggal
                                                                    Check-In:</span>
                                                                <span class="text-sm text-gray-800"
                                                                    x-text="bookingDetails.check_in"></span>
                                                            </div>
                                                            <div class="flex justify-between">
                                                                <span class="text-sm font-medium text-gray-600">Tanggal
                                                                    Check-Out:</span>
                                                                <span class="text-sm text-gray-800"
                                                                    x-text="bookingDetails.check_out"></span>
                                                            </div>
                                                            <div class="flex justify-between">
                                                                <span class="text-sm font-medium text-gray-600">Nama
                                                                    Tamu:</span>
                                                                <span class="text-sm text-gray-800"
                                                                    x-text="bookingDetails.guest_name"></span>
                                                            </div>
                                                            <div class="flex justify-between">
                                                                <span
                                                                    class="text-sm font-medium text-gray-600">Properti:</span>
                                                                <span class="text-sm text-gray-800"
                                                                    x-text="bookingDetails.property_name"></span>
                                                            </div>
                                                            <div class="flex justify-between">
                                                                <span
                                                                    class="text-sm font-medium text-gray-600">Kamar:</span>
                                                                <span class="text-sm text-gray-800"
                                                                    x-text="bookingDetails.room_name"></span>
                                                            </div>
                                                            <div class="flex justify-between">
                                                                <span
                                                                    class="text-sm font-medium text-gray-600">Durasi:</span>
                                                                <span class="text-sm text-gray-800"
                                                                    x-text="bookingDetails.duration"></span>
                                                            </div>
                                                            <div class="flex justify-between">
                                                                <span class="text-sm font-medium text-gray-600">Total
                                                                    Pembayaran:</span>
                                                                <span class="text-sm text-gray-800"
                                                                    x-text="bookingDetails.total_payment"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Bagian 2: Profil Tamu dan Unggah Identifikasi Berdampingan -->
                                                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                                        <!-- Profil Tamu -->
                                                        <div class="bg-white p-6 rounded-lg border border-gray-200">
                                                            <h3
                                                                class="font-semibold text-lg text-gray-800 mb-4 flex items-center">
                                                                <svg class="w-5 h-5 mr-2 text-purple-600"
                                                                    fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                </svg>
                                                                Profil Tamu
                                                            </h3>

                                                            <div class="space-y-4">
                                                                <!-- Foto Profil -->
                                                                <div
                                                                    class="border-2 border-dashed border-gray-300 rounded-lg p-4 bg-gray-50">
                                                                    <template x-if="profilePhotoUrl">
                                                                        <div class="w-full">
                                                                            <img :src="profilePhotoUrl"
                                                                                alt="Foto Profil"
                                                                                class="w-full h-48 object-cover rounded-lg">
                                                                            <span
                                                                                class="mt-2 text-sm text-gray-600 block text-center"
                                                                                x-text="bookingDetails.guest_name"></span>
                                                                        </div>
                                                                    </template>
                                                                    <template x-if="!profilePhotoUrl">
                                                                        <div class="flex flex-col items-center">
                                                                            <div
                                                                                class="w-24 h-24 rounded-full bg-gray-200 flex items-center justify-center">
                                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                                    class="h-12 w-12 text-gray-400"
                                                                                    fill="none" viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                                                </svg>
                                                                            </div>
                                                                            <span class="mt-2 text-sm text-gray-600"
                                                                                x-text="bookingDetails.guest_name"></span>
                                                                            <span
                                                                                class="text-xs text-red-500 mt-1">Akun
                                                                                belum
                                                                                Terverifikasi</span>
                                                                        </div>
                                                                    </template>
                                                                </div>

                                                                <!-- Informasi Kontak -->
                                                                <div>
                                                                    <h4 class="text-sm font-medium text-gray-700 mb-2">
                                                                        Informasi Kontak</h4>
                                                                    <div class="space-y-3">
                                                                        <!-- Input Nama -->
                                                                        <div>
                                                                            <label for="guestName"
                                                                                class="block text-sm font-medium text-gray-700 mb-1">Nama
                                                                                Lengkap</label>
                                                                            <input type="text" id="guestName"
                                                                                x-model="guestContact.name"
                                                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                                                placeholder="Masukkan nama lengkap"
                                                                                required>
                                                                        </div>

                                                                        <!-- Input Email -->
                                                                        <div>
                                                                            <label for="guestEmail"
                                                                                class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                                                            <input type="email" id="guestEmail"
                                                                                x-model="guestContact.email"
                                                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                                                placeholder="Masukkan alamat email"
                                                                                required>
                                                                        </div>

                                                                        <!-- Input Telepon -->
                                                                        <div>
                                                                            <label for="guestPhone"
                                                                                class="block text-sm font-medium text-gray-700 mb-1">Nomor
                                                                                Telepon</label>
                                                                            <input type="tel" id="guestPhone"
                                                                                x-model="guestContact.phone"
                                                                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                                                                placeholder="Masukkan nomor telepon"
                                                                                required>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Unggah Identifikasi -->
                                                        <div class="bg-white p-6 rounded-lg border border-gray-200">
                                                            <h3
                                                                class="font-semibold text-lg text-gray-800 mb-4 flex items-center">
                                                                <svg class="w-5 h-5 mr-2 text-green-600"
                                                                    fill="none" stroke="currentColor"
                                                                    viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                </svg>
                                                                Unggah Identifikasi
                                                            </h3>

                                                            <div class="space-y-4">
                                                                <!-- Pemilihan Jenis Dokumen -->
                                                                <div>
                                                                    <label for="documentType"
                                                                        class="block text-sm font-medium text-gray-700 mb-1">Jenis
                                                                        Dokumen</label>
                                                                    <select id="documentType"
                                                                        x-model="selectedDocType"
                                                                        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-green-500 focus:border-green-500 sm:text-sm rounded-md">
                                                                        <option value="ktp">KTP</option>
                                                                        <option value="passport">Paspor</option>
                                                                        <option value="sim">SIM</option>
                                                                        <option value="other">ID Lainnya</option>
                                                                    </select>
                                                                </div>

                                                                <!-- Tab untuk memilih metode unggah -->
                                                                <div>
                                                                    <div class="flex border-b border-gray-200">
                                                                        <button type="button"
                                                                            @click="handleUploadMethodChange('file')"
                                                                            :class="uploadMethod === 'file' ?
                                                                                'border-green-500 text-green-600' :
                                                                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                                                            class="flex-1 py-2 px-4 text-center border-b-2 font-medium text-sm">
                                                                            Upload File
                                                                        </button>
                                                                        <button type="button"
                                                                            @click="handleUploadMethodChange('camera')"
                                                                            :class="uploadMethod === 'camera' ?
                                                                                'border-green-500 text-green-600' :
                                                                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                                                            class="flex-1 py-2 px-4 text-center border-b-2 font-medium text-sm">
                                                                            Webcam
                                                                        </button>
                                                                    </div>

                                                                    <!-- Area Unggah File -->
                                                                    <div x-show="uploadMethod === 'file'"
                                                                        class="mt-4">
                                                                        <div x-show="!docPreview"
                                                                            class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-green-400 transition-colors duration-200 cursor-pointer"
                                                                            @click="$refs.docInput.click()"
                                                                            @drop.prevent="handleDocDrop($event)"
                                                                            @dragover.prevent @dragenter.prevent
                                                                            :class="{ 'border-green-400 bg-green-50': isDragging }"
                                                                            role="button" tabindex="0">
                                                                            <input type="file" id="document"
                                                                                name="document" accept="image/*,.pdf"
                                                                                class="hidden" x-ref="docInput"
                                                                                @change="handleDocUpload($event)">

                                                                            <div class="space-y-2">
                                                                                <svg class="w-12 h-12 mx-auto text-gray-400"
                                                                                    fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                                    </path>
                                                                                </svg>
                                                                                <p class="text-sm text-gray-600">
                                                                                    <span
                                                                                        class="font-medium text-green-600">Klik
                                                                                        untuk mengunggah</span> atau
                                                                                    seret
                                                                                    dan
                                                                                    lepas
                                                                                </p>
                                                                                <p class="text-xs text-gray-500">JPG,
                                                                                    PNG,
                                                                                    PDF
                                                                                    hingga 5MB</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                    <!-- Area Webcam -->
                                                                    <div x-show="uploadMethod === 'camera'"
                                                                        class="mt-4">
                                                                        <div
                                                                            class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                                                                            <!-- Preview Webcam -->
                                                                            <div x-show="!isCapturing && !webcamPhoto"
                                                                                class="text-center p-8 bg-gray-50 rounded-lg">
                                                                                <svg class="w-12 h-12 mx-auto text-gray-400"
                                                                                    fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                                                </svg>
                                                                                <p class="text-sm text-gray-600 mt-2">
                                                                                    Klik tombol di bawah untuk mengambil
                                                                                    foto
                                                                                </p>
                                                                            </div>

                                                                            <!-- Video Webcam -->
                                                                            <div x-show="isCapturing"
                                                                                class="relative">
                                                                                <video x-ref="webcamVideo"
                                                                                    class="w-full h-auto rounded-lg"
                                                                                    autoplay playsinline>
                                                                                </video>
                                                                                <div
                                                                                    class="absolute bottom-4 left-0 right-0 flex justify-center">
                                                                                    <button type="button"
                                                                                        @click="capturePhoto"
                                                                                        class="bg-white rounded-full p-3 shadow-lg hover:bg-gray-100 transition-colors">
                                                                                        <svg class="w-6 h-6 text-gray-700"
                                                                                            fill="none"
                                                                                            stroke="currentColor"
                                                                                            viewBox="0 0 24 24">
                                                                                            <path
                                                                                                stroke-linecap="round"
                                                                                                stroke-linejoin="round"
                                                                                                stroke-width="2"
                                                                                                d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                                                            <path
                                                                                                stroke-linecap="round"
                                                                                                stroke-linejoin="round"
                                                                                                stroke-width="2"
                                                                                                d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                                        </svg>
                                                                                    </button>
                                                                                </div>
                                                                            </div>

                                                                            <!-- Hasil Foto -->
                                                                            <div x-show="webcamPhoto && !isCapturing"
                                                                                class="relative">
                                                                                <img :src="webcamPhoto"
                                                                                    alt="Foto dari webcam"
                                                                                    class="w-full h-auto rounded-lg">
                                                                                <div
                                                                                    class="absolute top-2 right-2 flex space-x-2">
                                                                                    <button type="button"
                                                                                        @click="retakePhoto"
                                                                                        class="bg-white rounded-full p-2 shadow-lg hover:bg-gray-100 transition-colors">
                                                                                        <svg class="w-4 h-4 text-gray-700"
                                                                                            fill="none"
                                                                                            stroke="currentColor"
                                                                                            viewBox="0 0 24 24">
                                                                                            <path
                                                                                                stroke-linecap="round"
                                                                                                stroke-linejoin="round"
                                                                                                stroke-width="2"
                                                                                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                                                        </svg>
                                                                                    </button>
                                                                                    <button type="button"
                                                                                        @click="useWebcamPhoto"
                                                                                        class="bg-green-500 rounded-full p-2 shadow-lg hover:bg-green-600 transition-colors">
                                                                                        <svg class="w-4 h-4 text-white"
                                                                                            fill="none"
                                                                                            stroke="currentColor"
                                                                                            viewBox="0 0 24 24">
                                                                                            <path
                                                                                                stroke-linecap="round"
                                                                                                stroke-linejoin="round"
                                                                                                stroke-width="2"
                                                                                                d="M5 13l4 4L19 7" />
                                                                                        </svg>
                                                                                    </button>
                                                                                </div>
                                                                            </div>

                                                                            <!-- Tombol Mulai Webcam -->
                                                                            <div x-show="!isCapturing && !webcamPhoto"
                                                                                class="mt-4">
                                                                                <button type="button"
                                                                                    @click="startWebcam"
                                                                                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                                                                    <svg class="w-5 h-5 inline mr-2"
                                                                                        fill="none"
                                                                                        stroke="currentColor"
                                                                                        viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                                                    </svg>
                                                                                    Buka Kamera
                                                                                </button>
                                                                            </div>

                                                                            <!-- Tombol Tutup Webcam -->
                                                                            <div x-show="isCapturing" class="mt-4">
                                                                                <button type="button"
                                                                                    @click="stopWebcam"
                                                                                    class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                                                                                    <svg class="w-5 h-5 inline mr-2"
                                                                                        fill="none"
                                                                                        stroke="currentColor"
                                                                                        viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round"
                                                                                            stroke-linejoin="round"
                                                                                            stroke-width="2"
                                                                                            d="M6 18L18 6M6 6l12 12" />
                                                                                    </svg>
                                                                                    Tutup Kamera
                                                                                </button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Pratinjau Dokumen -->
                                                                <div class="mt-4" x-show="docPreview" x-transition>
                                                                    <h4 class="text-sm font-medium text-gray-700 mb-2">
                                                                        Pratinjau Dokumen (<span
                                                                            x-text="selectedDocType.toUpperCase()"></span>):
                                                                    </h4>
                                                                    <div
                                                                        class="border border-gray-200 rounded-lg p-2 bg-white">
                                                                        <template x-if="docPreviewType === 'image'">
                                                                            <img :src="docPreview"
                                                                                alt="Pratinjau Dokumen"
                                                                                class="w-full h-auto max-h-48 object-contain">
                                                                        </template>
                                                                        <template x-if="docPreviewType === 'pdf'">
                                                                            <div class="bg-gray-100 p-4 text-center">
                                                                                <svg class="w-12 h-12 mx-auto text-red-500"
                                                                                    fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                                                    </path>
                                                                                </svg>
                                                                                <p class="text-sm text-gray-600 mt-2">
                                                                                    Dokumen PDF</p>
                                                                            </div>
                                                                        </template>
                                                                        <div
                                                                            class="mt-2 flex justify-between items-center">
                                                                            <span class="text-xs text-gray-500">Dokumen
                                                                                terunggah</span>
                                                                            <button type="button" @click="removeDoc"
                                                                                class="text-red-500 hover:text-red-700 text-xs font-medium">
                                                                                Hapus
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Pesan Validasi -->
                                                                <div class="mt-3" x-show="!docPreview">
                                                                    <p class="text-sm text-red-600">
                                                                        <span class="font-medium">Catatan:</span>
                                                                        Dokumen
                                                                        identifikasi diperlukan untuk check-in.
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Footer Modal -->
                                            <div
                                                class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-between">
                                                <button type="button" @click="closeModal"
                                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                    Batal
                                                </button>
                                                <button type="button" @click="submitCheckIn"
                                                    :disabled="(!docPreview && !profilePhotoUrl) || !guestContact.name || !
                                                        guestContact.email || !guestContact.phone"
                                                    class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                                    Selesaikan Check-In
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @elseif (!is_null($booking->check_in_at) && is_null($booking->check_out_at))
                            {{-- Sudah check-in, belum check-out --}}
                            <div class="flex flex-col items-center space-y-2">
                                <span class="text-yellow-600 font-semibold">Currently Staying</span>

                                <a href="{{ route('newReserv.checkin.invoice', $booking->order_id) }}"
                                    target="_blank"
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700 focus:outline-none">
                                    View Invoice
                                </a>
                            </div>
                        @elseif (!is_null($booking->check_in_at) && !is_null($booking->check_out_at))
                            {{-- Sudah check-in dan check-out --}}
                            <span class="text-green-600">Checked-Out</span>
                        @endif
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="8" class="px-6 py-4 text-center text-sm text-gray-500">
                    No bookings found
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
