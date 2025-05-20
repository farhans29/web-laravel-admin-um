<x-app-layout>
    <!-- Modal Tambah Properti -->
    <div id="addPropertyModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                <!-- Multi-step form -->
                <form id="propertyForm" action="{{ route('properties.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <!-- Step 1: Informasi Dasar -->
                    <div id="step1" class="p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informasi Dasar Properti</h3>
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <!-- Nama Properti -->
                            <div class="sm:col-span-6">
                                <label for="property_name" class="block text-sm font-medium text-gray-700">Nama
                                    Properti*</label>
                                <input type="text" name="property_name" id="property_name" required
                                    class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <!-- Tags -->
                            <div class="sm:col-span-6">
                                <label class="block text-sm font-medium text-gray-700">Jenis Properti*</label>
                                <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-3">
                                    <div class="flex items-center">
                                        <input id="kos" name="property_type" type="radio" value="Kos"
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                        <label for="kos" class="ml-2 block text-sm text-gray-700">Kos</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="rumah" name="property_type" type="radio" value="Rumah"
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                        <label for="rumah" class="ml-2 block text-sm text-gray-700">Rumah</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="apartment" name="property_type" type="radio" value="Apartment"
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                        <label for="apartment"
                                            class="ml-2 block text-sm text-gray-700">Apartment</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="villa" name="property_type" type="radio" value="Villa"
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                        <label for="villa" class="ml-2 block text-sm text-gray-700">Villa</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="hotel" name="property_type" type="radio" value="Hotel"
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                        <label for="hotel" class="ml-2 block text-sm text-gray-700">Hotel</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Alamat -->
                            <div class="sm:col-span-6">
                                <label for="address" class="block text-sm font-medium text-gray-700">Alamat*</label>
                                <textarea id="address" name="address" rows="3"
                                    class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                            </div>

                            <!-- Provinsi -->
                            <div class="sm:col-span-3">
                                <label for="province" class="block text-sm font-medium text-gray-700">Provinsi*</label>
                                <select id="province" name="province" required
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Pilih Provinsi</option>

                                </select>
                            </div>

                            <!-- Kota -->
                            <div class="sm:col-span-3">
                                <label for="city"
                                    class="block text-sm font-medium text-gray-700">Kota/Kabupaten*</label>
                                <select id="city" name="city" required disabled
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Pilih Kota</option>
                                </select>
                            </div>

                            <!-- Kecamatan -->
                            <div class="sm:col-span-3">
                                <label for="district" class="block text-sm font-medium text-gray-700">Kecamatan*</label>
                                <input type="text" id="district" name="district" required
                                    class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <!-- Kelurahan -->
                            <div class="sm:col-span-3">
                                <label for="village" class="block text-sm font-medium text-gray-700">Kelurahan*</label>
                                <input type="text" id="village" name="village" required
                                    class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <!-- Kode Pos -->
                            <div class="sm:col-span-2">
                                <label for="postal_code" class="block text-sm font-medium text-gray-700">Kode
                                    Pos</label>
                                <input type="text" id="postal_code" name="postal_code"
                                    class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Lokasi dan Detail -->
                    <div id="step2" class="p-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Detail Lokasi</h3>
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <!-- Alamat Lengkap -->
                            <div class="sm:col-span-6">
                                <label for="full_address" class="block text-sm font-medium text-gray-700">Alamat
                                    Lengkap*</label>
                                <textarea id="full_address" name="full_address" rows="3" required
                                    class="mt-1 focus:ring-blue-500 focus:border-blue-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                            </div>

                            <!-- Pinpoint Lokasi -->
                            <div class="sm:col-span-6">
                                <label class="block text-sm font-medium text-gray-700">Pinpoint Lokasi*</label>
                                <div id="map" class="mt-1 h-64 bg-gray-200 rounded-md"></div>
                                <div id="coordinates" class="mt-2 text-sm text-gray-500"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Foto dan Fasilitas -->
                    <div id="step3" class="p-6 hidden">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Foto dan Fasilitas</h3>
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <!-- Foto Properti -->
                            <div class="sm:col-span-6">
                                <label class="block text-sm font-medium text-gray-700">Foto Properti* (Minimal 3
                                    foto)</label>
                                <div
                                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                    <div class="space-y-1 text-center">
                                        <div class="flex text-sm text-gray-600">
                                            <label for="property_images"
                                                class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                <span>Upload foto</span>
                                                <input id="property_images" name="property_images[]" type="file"
                                                    multiple accept="image/*" class="sr-only">
                                            </label>
                                            <p class="pl-1">atau drag and drop</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, JPEG up to 5MB</p>
                                    </div>
                                </div>
                                <div id="imagePreview" class="mt-2 grid grid-cols-3 gap-2 hidden">
                                    <!-- Image previews will be shown here -->
                                </div>
                            </div>

                            <!-- Fasilitas -->
                            <div class="sm:col-span-6">
                                <label class="block text-sm font-medium text-gray-700">Fasilitas</label>
                                <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-3">
                                    <!-- Fasilitas Umum -->
                                    <div class="flex items-center">
                                        <input id="wifi" name="facilities[]" type="checkbox" value="Wifi"
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        <label for="wifi" class="ml-2 block text-sm text-gray-700">Wifi</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="parking" name="facilities[]" type="checkbox" value="Parkir"
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        <label for="parking" class="ml-2 block text-sm text-gray-700">Parkir</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="ac" name="facilities[]" type="checkbox" value="AC"
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        <label for="ac" class="ml-2 block text-sm text-gray-700">AC</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="kitchen" name="facilities[]" type="checkbox" value="Dapur"
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        <label for="kitchen" class="ml-2 block text-sm text-gray-700">Dapur</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="tv" name="facilities[]" type="checkbox" value="TV"
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        <label for="tv" class="ml-2 block text-sm text-gray-700">TV</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input id="laundry" name="facilities[]" type="checkbox" value="Laundry"
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        <label for="laundry" class="ml-2 block text-sm text-gray-700">Laundry</label>
                                    </div>

                                    <!-- Fasilitas Khusus Kos -->
                                    <div class="flex items-center kos-facility hidden">
                                        <input id="wardrobe" name="facilities[]" type="checkbox" value="Lemari"
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        <label for="wardrobe" class="ml-2 block text-sm text-gray-700">Lemari</label>
                                    </div>
                                    <div class="flex items-center kos-facility hidden">
                                        <input id="bed" name="facilities[]" type="checkbox"
                                            value="Tempat Tidur"
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        <label for="bed" class="ml-2 block text-sm text-gray-700">Tempat
                                            Tidur</label>
                                    </div>

                                    <!-- Fasilitas Khusus Hotel/Villa/Apartment -->
                                    <div class="flex items-center premium-facility hidden">
                                        <input id="pool" name="facilities[]" type="checkbox"
                                            value="Kolam Renang"
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        <label for="pool" class="ml-2 block text-sm text-gray-700">Kolam
                                            Renang</label>
                                    </div>
                                    <div class="flex items-center premium-facility hidden">
                                        <input id="gym" name="facilities[]" type="checkbox" value="Gym"
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        <label for="gym" class="ml-2 block text-sm text-gray-700">Gym</label>
                                    </div>
                                    <div class="flex items-center premium-facility hidden">
                                        <input id="restaurant" name="facilities[]" type="checkbox" value="Restoran"
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        <label for="restaurant"
                                            class="ml-2 block text-sm text-gray-700">Restoran</label>
                                    </div>
                                    <div class="flex items-center premium-facility hidden">
                                        <input id="spa" name="facilities[]" type="checkbox" value="Spa"
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        <label for="spa" class="ml-2 block text-sm text-gray-700">Spa</label>
                                    </div>
                                    <div class="flex items-center premium-facility hidden">
                                        <input id="concierge" name="facilities[]" type="checkbox" value="Concierge"
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        <label for="concierge"
                                            class="ml-2 block text-sm text-gray-700">Concierge</label>
                                    </div>
                                    <div class="flex items-center premium-facility hidden">
                                        <input id="security" name="facilities[]" type="checkbox"
                                            value="Keamanan 24 Jam"
                                            class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 rounded">
                                        <label for="security" class="ml-2 block text-sm text-gray-700">Keamanan 24
                                            Jam</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Footer with Navigation Buttons -->
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="button" id="nextBtn"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Selanjutnya
                        </button>
                        <button type="button" id="prevBtn"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm hidden">
                            Sebelumnya
                        </button>
                        <button type="button" id="cancelBtn"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Tutup
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Existing content... -->
    <div class="container mx-auto px-4 py-6">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Properti</h1>
            <div class="mt-4 md:mt-0">
                <button id="openAddPropertyModal"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Tambah Property
                </button>
            </div>
        </div>


    </div>

    <!-- JavaScript for Modal Functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal elements
            const modal = document.getElementById('addPropertyModal');
            const openButton = document.getElementById('openAddPropertyModal');
            const cancelButton = document.getElementById('cancelBtn');

            // Form steps
            const steps = ['step1', 'step2', 'step3'];
            let currentStep = 0;

            // Navigation buttons
            const nextButton = document.getElementById('nextBtn');
            const prevButton = document.getElementById('prevBtn');

            // Open modal
            openButton.addEventListener('click', function() {
                modal.classList.remove('hidden');
                showStep(0);
            });

            // Close modal
            cancelButton.addEventListener('click', function() {
                modal.classList.add('hidden');
                resetForm();
            });

            // Next button
            nextButton.addEventListener('click', function() {
                if (validateStep(currentStep)) {
                    if (currentStep < steps.length - 1) {
                        currentStep++;
                        showStep(currentStep);
                    } else {
                        // Submit form menggunakan AJAX
                        const formElement = document.getElementById('propertyForm');
                        const formData = new FormData(formElement);

                        fetch(formElement.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                        .content,
                                    'Accept': 'application/json' // Explicitly ask for JSON response
                                },
                            })
                            .then(response => {
                                // First check if the response is JSON
                                const contentType = response.headers.get('content-type');
                                if (!contentType || !contentType.includes('application/json')) {
                                    return response.text().then(text => {
                                        throw new Error(`Expected JSON but got: ${text}`);
                                    });
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    modal.classList.add('hidden');
                                    resetForm();
                                    window.location.reload();
                                } else if (data.errors) {
                                    // Handle validation errors
                                    alert('Validation errors: ' + Object.values(data.errors).join(
                                    '\n'));
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                alert('Terjadi kesalahan saat menyimpan data: ' + error.message);
                            });
                    }
                }
            });

            // Previous button
            prevButton.addEventListener('click', function() {
                if (currentStep > 0) {
                    currentStep--;
                    showStep(currentStep);
                }
            });

            // Show specific step
            function showStep(stepIndex) {
                steps.forEach((step, index) => {
                    const stepElement = document.getElementById(step);
                    if (index === stepIndex) {
                        stepElement.classList.remove('hidden');
                    } else {
                        stepElement.classList.add('hidden');
                    }
                });

                // Update navigation buttons
                if (stepIndex === 0) {
                    prevButton.classList.add('hidden');
                    nextButton.textContent = 'Selanjutnya';
                } else if (stepIndex === steps.length - 1) {
                    prevButton.classList.remove('hidden');
                    nextButton.textContent = 'Simpan';
                } else {
                    prevButton.classList.remove('hidden');
                    nextButton.textContent = 'Selanjutnya';
                }
            }

            // Validate current step
            function validateStep(stepIndex) {
                let isValid = true;

                if (stepIndex === 0) {
                    // Validate step 1
                    const propertyName = document.getElementById('property_name');
                    const propertyType = document.querySelector('input[name="property_type"]:checked');
                    const address = document.getElementById('address');
                    const province = document.getElementById('province');

                    if (!propertyName.value) {
                        isValid = false;
                        propertyName.classList.add('border-red-500');
                    } else {
                        propertyName.classList.remove('border-red-500');
                    }

                    if (!propertyType) {
                        isValid = false;
                        alert('Pilih jenis properti');
                    }

                    if (!address.value) {
                        isValid = false;
                        address.classList.add('border-red-500');
                    } else {
                        address.classList.remove('border-red-500');
                    }

                    if (!province.value) {
                        isValid = false;
                        province.classList.add('border-red-500');
                    } else {
                        province.classList.remove('border-red-500');
                    }
                } else if (stepIndex === 1) {
                    // Validate step 2
                    const fullAddress = document.getElementById('full_address');
                    if (!fullAddress.value) {
                        isValid = false;
                        fullAddress.classList.add('border-red-500');
                    } else {
                        fullAddress.classList.remove('border-red-500');
                    }


                } else if (stepIndex === 2) {
                    // Validate step 3
                    const fileInput = document.getElementById('property_images');
                    if (fileInput.files.length < 1) {
                        isValid = false;
                        alert('Upload minimal 3 foto properti');
                    }
                }

                return isValid;
            }

            // Reset form
            function resetForm() {
                document.getElementById('propertyForm').reset();
                currentStep = 0;
                showStep(0);

                // Clear image preview
                document.getElementById('imagePreview').innerHTML = '';
                document.getElementById('imagePreview').classList.add('hidden');

                // Reset province and city
                document.getElementById('city').disabled = true;
            }

            // Handle property type change to show relevant facilities
            document.querySelectorAll('input[name="property_type"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    // Hide all special facilities first
                    document.querySelectorAll('.kos-facility, .premium-facility').forEach(el => {
                        el.classList.add('hidden');
                    });

                    // Show relevant facilities based on property type
                    if (this.value === 'Kos') {
                        document.querySelectorAll('.kos-facility').forEach(el => {
                            el.classList.remove('hidden');
                        });
                    } else if (['Hotel', 'Villa', 'Apartment'].includes(this.value)) {
                        document.querySelectorAll('.premium-facility').forEach(el => {
                            el.classList.remove('hidden');
                        });
                    }
                });
            });

            // Handle image upload preview
            document.getElementById('property_images').addEventListener('change', function(e) {
                const preview = document.getElementById('imagePreview');
                preview.innerHTML = '';

                if (this.files.length > 0) {
                    preview.classList.remove('hidden');

                    for (let i = 0; i < this.files.length; i++) {
                        const file = this.files[i];
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.classList.add('h-24', 'w-full', 'object-cover', 'rounded');
                            preview.appendChild(img);
                        }

                        reader.readAsDataURL(file);
                    }
                } else {
                    preview.classList.add('hidden');
                }
            });

            // Simulate province and city data (in real app, you would fetch this from API)
            const provinces = [{
                    id: '1',
                    name: 'DKI Jakarta'
                },
                {
                    id: '2',
                    name: 'Jawa Barat'
                },
                {
                    id: '3',
                    name: 'Jawa Tengah'
                },
                {
                    id: '4',
                    name: 'Jawa Timur'
                },
                {
                    id: '5',
                    name: 'Bali'
                }
            ];

            const cities = {
                'DKI Jakarta': [{
                        id: '101',
                        name: 'Jakarta Pusat'
                    },
                    {
                        id: '102',
                        name: 'Jakarta Selatan'
                    },
                    {
                        id: '103',
                        name: 'Jakarta Barat'
                    },
                    {
                        id: '104',
                        name: 'Jakarta Timur'
                    },
                    {
                        id: '105',
                        name: 'Jakarta Utara'
                    }
                ],
                'Jawa Barat': [{
                        id: '201',
                        name: 'Bandung'
                    },
                    {
                        id: '202',
                        name: 'Bogor'
                    },
                    {
                        id: '203',
                        name: 'Depok'
                    },
                    {
                        id: '204',
                        name: 'Bekasi'
                    }
                ],
                'Jawa Tengah': [{
                        id: '301',
                        name: 'Semarang'
                    },
                    {
                        id: '302',
                        name: 'Solo'
                    },
                    {
                        id: '303',
                        name: 'Salatiga'
                    }
                ],
                'Jawa Timur': [{
                        id: '401',
                        name: 'Surabaya'
                    },
                    {
                        id: '402',
                        name: 'Malang'
                    },
                    {
                        id: '403',
                        name: 'Sidoarjo'
                    }
                ],
                'Bali': [{
                        id: '501',
                        name: 'Denpasar'
                    },
                    {
                        id: '502',
                        name: 'Badung'
                    },
                    {
                        id: '503',
                        name: 'Gianyar'
                    }
                ]

            };

            // Populate province dropdown
            const provinceSelect = document.getElementById('province');
            provinces.forEach(province => {
                const option = document.createElement('option');
                option.value = province.name;
                option.textContent = province.name;
                provinceSelect.appendChild(option);
            });

            // Handle province change to populate cities
            provinceSelect.addEventListener('change', function() {
                const citySelect = document.getElementById('city');
                citySelect.innerHTML = '<option value="">Pilih Kota</option>';

                if (this.value) {
                    citySelect.disabled = false;
                    const selectedCities = cities[this.value] || [];

                    selectedCities.forEach(city => {
                        const option = document.createElement('option');
                        option.value = city.name;
                        option.textContent = city.name;
                        citySelect.appendChild(option);
                    });
                } else {
                    citySelect.disabled = true;
                }
            });

        });

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the map
            const map = L.map('map').setView([-6.2088, 106.8456], 13); // Default to Jakarta

            // Add tile layer (OpenStreetMap)
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Add geocoder control
            const geocoder = L.Control.Geocoder.nominatim();
            const marker = L.marker([-6.2088, 106.8456], {
                draggable: true
            }).addTo(map);

            // Update address when marker is moved
            marker.on('moveend', function(e) {
                updateAddressFromMarker();
            });

            // Click event on map to move marker
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                updateAddressFromMarker();
            });

            // Function to update address from marker position
            function updateAddressFromMarker() {
                const latlng = marker.getLatLng();
                document.getElementById('coordinates').innerHTML =
                    `Latitude: ${latlng.lat.toFixed(6)}, Longitude: ${latlng.lng.toFixed(6)}`;

                // Reverse geocode to get address
                geocoder.reverse(latlng, map.options.crs.scale(map.getZoom()), function(results) {
                    if (results && results.length > 0) {
                        document.getElementById('full_address').value = results[0].name || results[0]
                            .html || '';
                    }
                });
            }

            // Initial update
            updateAddressFromMarker();


        });
    </script>
</x-app-layout>
