<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <h1
                class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-500 to-indigo-500 dark:from-blue-400 dark:to-indigo-400">
                Master Promo Banners
            </h1>
            <div class="mt-4 md:mt-0">
                <button
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center transition-colors duration-200"
                    type="button" onclick="openCreateModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Tambah Banner
                </button>
            </div>
        </div>

        <!-- Info Banner Size -->
        <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm text-blue-700 dark:text-blue-300">
                    Ukuran gambar banner yang disarankan: <strong>1911px x 372px</strong>
                </span>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <!-- Search Input -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Cari Banner
                    </label>
                    <input type="text" id="search" placeholder="Cari judul atau deskripsi..."
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status_filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status
                    </label>
                    <select id="status_filter"
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="">Semua Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

                <!-- Per Page Selector -->
                <div class="md:col-start-3">
                    <div class="flex items-center justify-end gap-3">
                        <label for="per_page"
                            class="text-sm font-medium text-gray-700 dark:text-gray-300 whitespace-nowrap">
                            Tampilkan :
                        </label>
                        <select id="per_page"
                            class="min-w-[120px] border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white text-sm">
                            <option value="8">8</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div id="banners-table-container">
            @include('pages.promo-banners.partials.banner_table')
        </div>
    </div>

    <!-- Banner Modal -->
    <div id="bannerModal" class="fixed inset-0 bg-black/30 backdrop-blur-sm z-50 hidden" style="display: none;">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <h3 id="modalTitle" class="text-xl font-bold text-gray-800 dark:text-white">Tambah Banner</h3>
                    <button type="button" onclick="closeModal()"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form id="bannerForm" class="p-6" enctype="multipart/form-data">
                    <input type="hidden" id="banner_id" name="banner_id">

                    <div class="space-y-4">
                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Judul Banner <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="title" name="title" required
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                placeholder="Masukkan judul banner">
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Deskripsi
                            </label>
                            <textarea id="descriptions" name="descriptions" rows="3"
                                class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                placeholder="Masukkan deskripsi banner"></textarea>
                        </div>

                        <!-- Image Upload -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Gambar Banner <span class="text-red-500" id="image_required">*</span>
                                <span class="text-xs text-gray-500">(Ukuran: 1911px x 372px, Max: 5MB)</span>
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-lg hover:border-blue-500 transition-colors" id="dropzone">
                                <div class="space-y-1 text-center">
                                    <div id="image_preview_container" class="hidden mb-4">
                                        <img id="image_preview" src="" alt="Preview" class="mx-auto max-h-48 rounded-lg">
                                    </div>
                                    <svg class="mx-auto h-12 w-12 text-gray-400" id="upload_icon" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                        <label for="banner_image" class="relative cursor-pointer bg-white dark:bg-gray-700 rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none">
                                            <span>Upload gambar</span>
                                            <input id="banner_image" name="banner_image" type="file" class="sr-only" accept="image/*">
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF, WEBP maksimal 5MB</p>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status
                            </label>
                            <div class="flex items-center space-x-3">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="status_toggle" class="sr-only peer" checked>
                                    <div
                                        class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                                    </div>
                                </label>
                                <span id="status_label" class="text-sm font-medium text-gray-900 dark:text-white">Active</span>
                            </div>
                            <input type="hidden" id="status" name="status" value="1">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button type="button" onclick="closeModal()"
                            class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Image Preview Modal -->
    <div id="imagePreviewModal" class="fixed inset-0 bg-black/80 z-[60] hidden items-center justify-center" style="display: none;">
        <div class="relative max-w-6xl max-h-[90vh] mx-4">
            <button onclick="closeImagePreview()" class="absolute -top-10 right-0 text-white hover:text-gray-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <img id="fullImagePreview" src="" alt="Full Preview" class="max-w-full max-h-[85vh] rounded-lg">
        </div>
    </div>

    @push('scripts')
        <script>
            // Toast notification helper
            function showToast(message, type = 'success') {
                const bgColor = type === 'success' ? 'linear-gradient(to right, #00b09b, #96c93d)' :
                    type === 'error' ? 'linear-gradient(to right, #ff5f6d, #ffc371)' :
                    type === 'info' ? 'linear-gradient(to right, #4facfe, #00f2fe)' :
                    'linear-gradient(to right, #f857a6, #ff5858)';

                Toastify({
                    text: message,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: bgColor,
                    },
                    stopOnFocus: true,
                    close: true,
                }).showToast();
            }

            // Status toggle handler
            $(document).ready(function() {
                $('#status_toggle').on('change', function() {
                    const isActive = $(this).is(':checked');
                    const status = isActive ? '1' : '0';
                    $('#status').val(status);
                    $('#status_label').text(isActive ? 'Active' : 'Inactive');
                    $('#status_label').toggleClass('text-blue-600', isActive);
                    $('#status_label').toggleClass('text-red-600', !isActive);
                });

                // Image preview with 5MB validation
                const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5MB in bytes

                $('#banner_image').on('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        // Validate file size (max 5MB)
                        if (file.size > MAX_FILE_SIZE) {
                            showToast('Ukuran file terlalu besar. Maksimal 5MB.', 'error');
                            $(this).val(''); // Clear the input
                            $('#image_preview_container').addClass('hidden');
                            $('#upload_icon').removeClass('hidden');
                            return;
                        }

                        // Validate file type
                        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                        if (!allowedTypes.includes(file.type)) {
                            showToast('Format file tidak didukung. Gunakan JPEG, PNG, GIF, atau WEBP.', 'error');
                            $(this).val(''); // Clear the input
                            $('#image_preview_container').addClass('hidden');
                            $('#upload_icon').removeClass('hidden');
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            $('#image_preview').attr('src', e.target.result);
                            $('#image_preview_container').removeClass('hidden');
                            $('#upload_icon').addClass('hidden');
                        };
                        reader.readAsDataURL(file);
                    }
                });

                // Drag and drop
                const dropzone = document.getElementById('dropzone');
                const fileInput = document.getElementById('banner_image');

                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    dropzone.addEventListener(eventName, preventDefaults, false);
                });

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                ['dragenter', 'dragover'].forEach(eventName => {
                    dropzone.addEventListener(eventName, highlight, false);
                });

                ['dragleave', 'drop'].forEach(eventName => {
                    dropzone.addEventListener(eventName, unhighlight, false);
                });

                function highlight(e) {
                    dropzone.classList.add('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                }

                function unhighlight(e) {
                    dropzone.classList.remove('border-blue-500', 'bg-blue-50', 'dark:bg-blue-900/20');
                }

                dropzone.addEventListener('drop', handleDrop, false);

                function handleDrop(e) {
                    const dt = e.dataTransfer;
                    const files = dt.files;
                    if (files.length) {
                        const file = files[0];

                        // Validate file size (max 5MB)
                        if (file.size > MAX_FILE_SIZE) {
                            showToast('Ukuran file terlalu besar. Maksimal 5MB.', 'error');
                            return;
                        }

                        // Validate file type
                        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                        if (!allowedTypes.includes(file.type)) {
                            showToast('Format file tidak didukung. Gunakan JPEG, PNG, GIF, atau WEBP.', 'error');
                            return;
                        }

                        fileInput.files = files;
                        $(fileInput).trigger('change');
                    }
                }
            });

            // Filter functionality
            let debounceTimer;
            $('#search, #status_filter, #per_page').on('change keyup', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(filterBanners, 300);
            });

            function filterBanners() {
                const search = $('#search').val();
                const status = $('#status_filter').val();
                const perPage = $('#per_page').val();

                $.ajax({
                    url: '{{ route('promo-banners.filter') }}',
                    method: 'GET',
                    data: {
                        search: search,
                        status: status,
                        per_page: perPage
                    },
                    success: function(response) {
                        $('#banners-table-container').html(response.html);
                    },
                    error: function(xhr) {
                        console.error('Filter error:', xhr);
                    }
                });
            }

            // Reload table
            function reloadTable() {
                filterBanners();
            }

            // Modal functions
            function openCreateModal() {
                $('#modalTitle').text('Tambah Banner');
                $('#bannerForm')[0].reset();
                $('#banner_id').val('');
                $('#status').val('1');
                $('#status_toggle').prop('checked', true);
                $('#status_label').text('Active');
                $('#image_preview_container').addClass('hidden');
                $('#upload_icon').removeClass('hidden');
                $('#image_required').show();
                $('#bannerModal').removeClass('hidden').show();
            }

            function openEditModal(id) {
                $.ajax({
                    url: `/promo-banners/${id}`,
                    method: 'GET',
                    success: function(response) {
                        const banner = response.data;
                        $('#modalTitle').text('Edit Banner');
                        $('#banner_id').val(banner.idrec);
                        $('#title').val(banner.title);
                        $('#descriptions').val(banner.descriptions);

                        // Set status
                        const isActive = banner.status == 1;
                        $('#status').val(banner.status);
                        $('#status_toggle').prop('checked', isActive);
                        $('#status_label').text(isActive ? 'Active' : 'Inactive');
                        $('#status_label').removeClass('text-blue-600 text-red-600');
                        $('#status_label').addClass(isActive ? 'text-blue-600' : 'text-red-600');

                        // Show existing image
                        if (banner.primary_image && banner.primary_image.image_url) {
                            $('#image_preview').attr('src', banner.primary_image.image_url);
                            $('#image_preview_container').removeClass('hidden');
                            $('#upload_icon').addClass('hidden');
                        } else {
                            $('#image_preview_container').addClass('hidden');
                            $('#upload_icon').removeClass('hidden');
                        }

                        $('#image_required').hide();
                        $('#bannerModal').removeClass('hidden').show();
                    },
                    error: function(xhr) {
                        showToast('Gagal mengambil data banner', 'error');
                    }
                });
            }

            function closeModal() {
                $('#bannerModal').addClass('hidden').hide();
            }

            // Form submission
            $('#bannerForm').on('submit', function(e) {
                e.preventDefault();
                const bannerId = $('#banner_id').val();
                const url = bannerId ? `/promo-banners/${bannerId}` : '{{ route('promo-banners.store') }}';

                const formData = new FormData();
                formData.append('title', $('#title').val());
                formData.append('descriptions', $('#descriptions').val());
                formData.append('status', $('#status').val());
                formData.append('_token', '{{ csrf_token() }}');

                const imageFile = $('#banner_image')[0].files[0];
                if (imageFile) {
                    formData.append('banner_image', imageFile);
                }

                if (bannerId) {
                    formData.append('_method', 'PUT');
                }

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            closeModal();
                            showToast(response.message, 'success');
                            setTimeout(() => reloadTable(), 500);
                        }
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON?.message || 'Gagal menyimpan banner';
                        showToast(errorMsg, 'error');

                        if (xhr.responseJSON?.errors) {
                            Object.values(xhr.responseJSON.errors).forEach(errors => {
                                errors.forEach(error => showToast(error, 'error'));
                            });
                        }
                    }
                });
            });

            // Delete banner
            function deleteBanner(id) {
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus banner ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/promo-banners/${id}`,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    showToast(response.message, 'success');
                                    setTimeout(() => reloadTable(), 500);
                                }
                            },
                            error: function(xhr) {
                                showToast('Gagal menghapus banner', 'error');
                            }
                        });
                    }
                });
            }

            // Toggle status
            function toggleStatus(id, currentStatus) {
                const newStatus = currentStatus == 1 ? 0 : 1;
                const statusText = newStatus == 1 ? 'mengaktifkan' : 'menonaktifkan';

                Swal.fire({
                    title: 'Konfirmasi',
                    text: `Apakah Anda yakin ingin ${statusText} banner ini?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Ubah!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route('promo-banners.toggle-status') }}',
                            method: 'POST',
                            data: {
                                id: id,
                                status: newStatus,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    showToast(response.message || 'Status berhasil diubah', 'success');
                                    setTimeout(() => reloadTable(), 500);
                                }
                            },
                            error: function(xhr) {
                                showToast('Gagal mengubah status', 'error');
                            }
                        });
                    }
                });
            }

            // Image preview modal
            function openImagePreview(imageUrl) {
                $('#fullImagePreview').attr('src', imageUrl);
                $('#imagePreviewModal').removeClass('hidden').css('display', 'flex');
            }

            function closeImagePreview() {
                $('#imagePreviewModal').addClass('hidden').hide();
            }

            // Close preview on escape key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeImagePreview();
                    closeModal();
                }
            });

            // Close preview on click outside
            $('#imagePreviewModal').on('click', function(e) {
                if (e.target === this) {
                    closeImagePreview();
                }
            });
        </script>
    @endpush
</x-app-layout>
