<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1
                    class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                    Pengaturan Akun
                </h1>
            </div>
            <div x-data="{ modalOpenDetail: false, showPassword: false, showConfirmPassword: false }">
                <!-- Button -->
                <button
                    class="px-5 py-2.5 bg-indigo-600 rounded-xl font-semibold text-sm text-white shadow hover:bg-indigo-700 transition-all duration-200 ease-in-out"
                    type="button" @click.prevent="modalOpenDetail = true">
                    + Tambah Pengguna Baru
                </button>

                <!-- Modal backdrop -->
                <div class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 transition-opacity" x-show="modalOpenDetail"
                    x-transition.opacity aria-hidden="true" x-cloak>
                </div>

                <!-- Modal dialog -->
                <div class="fixed inset-0 z-50 flex items-center justify-center px-4 sm:px-6" x-show="modalOpenDetail"
                    x-transition x-cloak @keydown.escape.window="modalOpenDetail = false">
                    <div class="bg-white rounded-xl shadow-lg w-full max-w-md animate-modal relative z-50"
                        @click.outside="modalOpenDetail = false">

                        <!-- Header -->
                        <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center">
                            <h2 class="text-xl font-semibold text-gray-900">Tambah Pengguna Baru</h2>
                            <button class="text-gray-500 text-xl hover:text-gray-700 transition-colors"
                                @click="modalOpenDetail = false">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="p-6">
                            <form id="userForm" method="POST" action="{{ route('users.store') }}"
                                enctype="multipart/form-data" autocomplete="off">
                                @csrf

                                <!-- First Name & Last Name -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="mb-5">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Depan <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" name="first_name" id="first_name" required
                                            class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm 
                                            focus:border-indigo-500 focus:ring-3 focus:ring-indigo-200 transition-all duration-200"
                                            placeholder="Masukkan nama depan" value="{{ old('first_name') }}">
                                        @error('first_name')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="mb-5">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Belakang <span
                                                class="text-red-500">*</span></label>
                                        <input type="text" name="last_name" id="last_name" required
                                            class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm 
                                            focus:border-indigo-500 focus:ring-3 focus:ring-indigo-200 transition-all duration-200"
                                            placeholder="Masukkan nama belakang" value="{{ old('last_name') }}">
                                        @error('last_name')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Username -->
                                <div class="mb-5">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Username <span
                                            class="text-red-500">*</span></label>
                                    <input type="text" name="username" id="username" required
                                        class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm 
                                        focus:border-indigo-500 focus:ring-3 focus:ring-indigo-200 transition-all duration-200"
                                        placeholder="Masukkan username" value="{{ old('username') }}">
                                    @error('username')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="mb-5">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span
                                            class="text-red-500">*</span></label>
                                    <input type="email" name="email" id="email" required
                                        class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm 
                                        focus:border-indigo-500 focus:ring-3 focus:ring-indigo-200 transition-all duration-200"
                                        placeholder="Masukkan alamat email" value="{{ old('email') }}">
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="mb-5">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi <span
                                            class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input :type="showPassword ? 'text' : 'password'" name="password" id="password"
                                            minlength="8" required
                                            class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm pr-10
                                            focus:border-indigo-500 focus:ring-3 focus:ring-indigo-200 transition-all duration-200"
                                            placeholder="Masukkan kata sandi">
                                        <span
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer"
                                            @click="showPassword = !showPassword">
                                            <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                        </span>
                                    </div>
                                    <p class="text-gray-500 text-xs mt-1">Harus mengandung huruf besar, kecil, angka, simbol, dan minimal 8 karakter</p>
                                    @error('password')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-5">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Kata Sandi <span
                                            class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input :type="showConfirmPassword ? 'text' : 'password'"
                                            name="password_confirmation" id="password_confirmation" minlength="8"
                                            required
                                            class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm pr-10
                                            focus:border-indigo-500 focus:ring-3 focus:ring-indigo-200 transition-all duration-200"
                                            placeholder="Konfirmasi kata sandi">
                                        <span
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer"
                                            @click="showConfirmPassword = !showConfirmPassword">
                                            <i class="fas"
                                                :class="showConfirmPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                        </span>
                                    </div>
                                </div>

                                <!-- Role -->
                                <div class="mb-5">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Peran <span class="text-red-500">*</span>
                                    </label>
                                    <select name="role" id="role" required
                                        class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-3 focus:ring-indigo-200 transition-all duration-200">
                                        <option value="">Pilih Peran</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}"
                                                {{ old('role') == $role->id ? 'selected' : '' }}>{{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Footer -->
                                <div class="mt-6 flex justify-end gap-3">
                                    <button type="button" @click="modalOpenDetail = false"
                                        class="px-4 py-2 rounded-lg border text-sm font-medium text-gray-600 hover:bg-gray-100 transition">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition-all duration-200 flex items-center gap-2">
                                        <i class="fas fa-plus"></i>
                                        <span>Buat Pengguna</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rest of your code remains the same -->
        <div id="containerAccount" class="bg-white shadow-md rounded-lg overflow-hidden mt-8">
            <div class="flex justify-between items-center px-6 py-4 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Daftar Akun</h2>
                <div class="flex items-center gap-6">
                    <!-- Search Form -->
                    <div class="relative">
                        <form method="GET" action="{{ route('users-newManagement') }}" id="searchForm">
                            <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                                placeholder="Cari pengguna..."
                                class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 w-56">
                            <div class="absolute left-3 top-2.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </form>
                    </div>

                    <!-- Per Page Form -->
                    <form method="GET" action="{{ route('users-newManagement') }}">
                        <div class="flex items-center">
                            <label for="per_page" class="mr-2 text-sm font-medium text-gray-600">Tampilkan:</label>
                            <select name="per_page" id="per_page" onchange="this.form.submit()"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 w-32">
                                <option value="8" {{ $perPage == 8 ? 'selected' : '' }}>8</option>
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="overflow-x-auto rounded-lg">
                <table class="w-full border border-gray-200 rounded-lg overflow-hidden">
                    <thead>
                        <tr class="bg-gray-200 text-left text-xs font-semibold text-gray-800 uppercase tracking-wider">
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Peran</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Dibuat Pada</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white text-sm text-gray-700">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50/80 transition-colors duration-150">
                                @php
                                    $colors = [
                                        'bg-red-500/35',
                                        'bg-blue-500/35',
                                        'bg-green-500/35',
                                        'bg-purple-500/35',
                                        'bg-pink-500/35',
                                        'bg-yellow-500/35',
                                        'bg-indigo-500/35',
                                        'bg-teal-500/35',
                                        'bg-orange-500/35',
                                    ];
                                    $bgColor = $colors[array_rand($colors)];
                                @endphp

                                <!-- Name -->
                                <td class="px-4 py-4 text-sm text-gray-900">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 flex items-center justify-center rounded-full text-white font-semibold {{ $bgColor }}">
                                            {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div>
                                                {{ $user->first_name }} {{ $user->last_name }}
                                            </div>
                                            <div class="text-gray-500 text-xs">
                                                {{ '@' . $user->username }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Email -->
                                <td class="px-4 py-4 text-sm text-gray-900">
                                    {{ $user->email }}
                                </td>
                                <!-- Role -->
                                <td class="px-4 py-4">
                                    <span
                                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">

                                        @switch($user->role->name)
                                            @case('Owner')
                                                <i class="fas fa-crown"></i>
                                            @break

                                            @case('Manager')
                                                <i class="fas fa-user-tie"></i>
                                            @break

                                            @case('Front Desk')
                                                <i class="fas fa-concierge-bell"></i>
                                            @break

                                            @case('Finance')
                                                <i class="fas fa-wallet"></i>
                                            @break

                                            @case('CS')
                                                <i class="fas fa-headset"></i>
                                            @break

                                            @case('Sales')
                                                <i class="fas fa-chart-line"></i>
                                            @break

                                            @case('Property Owner')
                                                <i class="fas fa-building"></i>
                                            @break

                                            @default
                                                <i class="fas fa-user"></i>
                                        @endswitch

                                        {{ $user->role->name }}
                                    </span>
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-4">
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $user->status == 1 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $user->status == 1 ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                </td>
                                <!-- Created At -->
                                <td class="px-4 py-4 text-sm text-gray-900">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($user->created_at)->format('Y M d') }}</div>
                                    <div class="text-xs text-gray-400">
                                        {{ \Carbon\Carbon::parse($user->created_at)->format('H:i') }}</div>
                                </td>
                                <!-- Actions -->
                                <td class="px-4 py-4">
                                    <div class="flex gap-2">
                                        <div x-data="{ modalOpenEdit{{ $user->id }}: false }">
                                            <!-- Edit button -->
                                            <button class="p-2 rounded-xl text-indigo-600 hover:bg-gray-100 transition"
                                                type="button"
                                                @click.prevent="modalOpenEdit{{ $user->id }} = true"
                                                aria-controls="modal-edit-{{ $user->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <!-- Modal backdrop -->
                                            <div class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 transition-opacity"
                                                x-show="modalOpenEdit{{ $user->id }}" x-transition.opacity
                                                aria-hidden="true" x-cloak></div>

                                            <!-- Modal dialog -->
                                            <div id="modal-edit-{{ $user->id }}"
                                                class="fixed inset-0 z-50 flex items-center justify-center px-4 sm:px-6"
                                                x-show="modalOpenEdit{{ $user->id }}" x-transition x-cloak
                                                @keydown.escape.window="modalOpenEdit{{ $user->id }} = false">
                                                <div class="bg-white rounded-xl shadow-lg w-full max-w-md animate-modal relative z-50"
                                                    @click.outside="modalOpenEdit{{ $user->id }} = false">

                                                    <!-- Header -->
                                                    <div
                                                        class="px-6 py-5 border-b border-gray-200 flex justify-between items-center">
                                                        <h2 class="text-xl font-semibold text-gray-900">Edit Pengguna</h2>
                                                        <button
                                                            class="text-gray-500 text-xl hover:text-gray-700 transition-colors"
                                                            @click="modalOpenEdit{{ $user->id }} = false">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>

                                                    <!-- Body -->
                                                    <div class="p-6">
                                                        <form method="POST"
                                                            action="{{ route('users.update', $user->id) }}"
                                                            class="space-y-5">
                                                            @csrf
                                                            @method('PUT')

                                                            <!-- First & Last Name -->
                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                                <!-- First Name -->
                                                                <div>
                                                                    <label for="first_name_{{ $user->id }}"
                                                                        class="block text-sm font-medium text-gray-700 mb-1">
                                                                        Nama Depan <span class="text-red-500">*</span>
                                                                    </label>
                                                                    <input type="text" name="first_name"
                                                                        id="first_name_{{ $user->id }}"
                                                                        value="{{ $user->first_name ?? '' }}" required
                                                                        class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-3 focus:ring-indigo-200 transition-all duration-200">
                                                                </div>

                                                                <!-- Last Name -->
                                                                <div>
                                                                    <label for="last_name_{{ $user->id }}"
                                                                        class="block text-sm font-medium text-gray-700 mb-1">
                                                                        Nama Belakang <span class="text-red-500">*</span>
                                                                    </label>
                                                                    <input type="text" name="last_name"
                                                                        id="last_name_{{ $user->id }}"
                                                                        value="{{ $user->last_name ?? '' }}" required
                                                                        class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-3 focus:ring-indigo-200 transition-all duration-200">
                                                                </div>
                                                            </div>

                                                            <!-- Username / Name -->
                                                            <div class="mt-4">
                                                                <label for="name_{{ $user->id }}"
                                                                    class="block text-sm font-medium text-gray-700 mb-1">
                                                                    Username <span class="text-red-500">*</span>
                                                                </label>
                                                                <input type="text" name="name"
                                                                    id="name_{{ $user->id }}"
                                                                    value="{{ $user->username }}" required
                                                                    class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-3 focus:ring-indigo-200 transition-all duration-200">
                                                            </div>

                                                            <!-- Email -->
                                                            <div class="mt-4">
                                                                <label for="email_{{ $user->id }}"
                                                                    class="block text-sm font-medium text-gray-700 mb-1">
                                                                    Email <span class="text-red-500">*</span>
                                                                </label>
                                                                <input type="email" name="email"
                                                                    id="email_{{ $user->id }}"
                                                                    value="{{ $user->email }}" required
                                                                    class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-3 focus:ring-indigo-200 transition-all duration-200">
                                                            </div>


                                                            <!-- Role -->
                                                            <div class="grid grid-cols-1 gap-4">
                                                                <div>
                                                                    <label for="role_{{ $user->id }}"
                                                                        class="block text-sm font-medium text-gray-700 mb-1">
                                                                        Peran <span class="text-red-500">*</span>
                                                                    </label>
                                                                    <select name="role"
                                                                        id="role_{{ $user->id }}" required
                                                                        class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-3 focus:ring-indigo-200 transition-all duration-200">
                                                                        <option value="">Pilih Peran</option>
                                                                        @foreach ($roles as $role)
                                                                            <option value="{{ $role->id }}"
                                                                                {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                                                {{ $role->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>


                                                            <!-- Status -->
                                                            <div>
                                                                <label for="status_{{ $user->id }}"
                                                                    class="block text-sm font-medium text-gray-700 mb-1">
                                                                    Status
                                                                </label>
                                                                <select name="status"
                                                                    id="status_{{ $user->id }}"
                                                                    class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-3 focus:ring-indigo-200 transition-all duration-200">
                                                                    <option value="1"
                                                                        {{ $user->status == 1 ? 'selected' : '' }}>
                                                                        Aktif</option>
                                                                    <option value="0"
                                                                        {{ $user->status == 0 ? 'selected' : '' }}>
                                                                        Tidak Aktif</option>
                                                                </select>
                                                            </div>

                                                            <!-- Footer -->
                                                            <div class="mt-6 flex justify-end gap-3">
                                                                <button type="button"
                                                                    @click="modalOpenEdit{{ $user->id }} = false"
                                                                    class="px-4 py-2 rounded-lg border text-sm font-medium text-gray-600 hover:bg-gray-100 transition">
                                                                    Batal
                                                                </button>
                                                                <button type="submit"
                                                                    class="px-5 py-2.5 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition-all duration-200 flex items-center gap-2">
                                                                    <i class="fas fa-save"></i>
                                                                    <span>Perbarui</span>
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-gray-50 rounded p-4">
            <div class="px-6 py-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Toastify({
                    text: "{{ session('success') }}",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "linear-gradient(to right, #00b09b, #96c93d)"
                    },
                    stopOnFocus: true,
                }).showToast();
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                @foreach ($errors->all() as $error)
                    Toastify({
                        text: "{{ $error }}",
                        duration: 5000,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "linear-gradient(to right, #ff5f6d, #ffc371)"
                        },
                        stopOnFocus: true,
                    }).showToast();
                @endforeach

                // Buka modal jika ada error
                @if ($errors->any())
                    Alpine.data('modal', () => ({
                        modalOpenDetail: true,
                    }));
                @endif
            });
        </script>
    @endif

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('modal', () => ({
                modalOpenDetail: false,
                files: [],
                fileUploaded: false,

                loadFiles(archives) {
                    this.files = [];
                }
            }));
        });

        // Validasi form dan penanganan submit
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('userForm');
            const password = document.getElementById('password');
            const passwordConfirmation = document.getElementById('password_confirmation');
            const emailInput = document.getElementById('email');

            // Buat elemen pesan error jika belum ada
            if (!document.getElementById('password-error')) {
                const passwordError = document.createElement('p');
                passwordError.id = 'password-error';
                passwordError.className = 'text-xs text-red-500 mt-1 hidden';
                password.parentNode.parentNode.appendChild(passwordError);
            }

            if (!document.getElementById('password-match-error')) {
                const passwordMatchError = document.createElement('p');
                passwordMatchError.id = 'password-match-error';
                passwordMatchError.className = 'text-xs text-red-500 mt-1 hidden';
                passwordMatchError.textContent = 'Kata sandi tidak cocok';
                passwordConfirmation.parentNode.parentNode.appendChild(passwordMatchError);
            }

            const passwordError = document.getElementById('password-error');
            const passwordMatchError = document.getElementById('password-match-error');

            // Validasi kompleksitas password
            function validatePasswordComplexity(pwd) {
                const hasUpperCase = /[A-Z]/.test(pwd);
                const hasLowerCase = /[a-z]/.test(pwd);
                const hasNumber = /[0-9]/.test(pwd);
                const hasSpecialChar = /[^A-Za-z0-9]/.test(pwd);
                const isLongEnough = pwd.length >= 8;

                return {
                    isValid: hasUpperCase && hasLowerCase && hasNumber && hasSpecialChar && isLongEnough,
                    errors: {
                        hasUpperCase,
                        hasLowerCase,
                        hasNumber,
                        hasSpecialChar,
                        isLongEnough
                    }
                };
            }

            // Update pesan error real-time
            password.addEventListener('input', function() {
                const result = validatePasswordComplexity(this.value);

                if (this.value.length > 0 && !result.isValid) {
                    passwordError.classList.remove('hidden');

                    // Update pesan error detail
                    let errorMessages = [];
                    if (!result.errors.hasUpperCase) errorMessages.push("huruf besar");
                    if (!result.errors.hasLowerCase) errorMessages.push("huruf kecil");
                    if (!result.errors.hasNumber) errorMessages.push("angka");
                    if (!result.errors.hasSpecialChar) errorMessages.push("simbol");
                    if (!result.errors.isLongEnough) errorMessages.push("min. 8 karakter");

                    passwordError.textContent = `Harus mengandung: ${errorMessages.join(", ")}`;
                } else {
                    passwordError.classList.add('hidden');
                }

                validatePasswordMatch();
            });

            // Validasi kesesuaian password
            function validatePasswordMatch() {
                if (password.value !== passwordConfirmation.value && passwordConfirmation.value.length > 0) {
                    passwordMatchError.classList.remove('hidden');
                    passwordConfirmation.setCustomValidity("Kata sandi tidak cocok");
                    return false;
                } else {
                    passwordMatchError.classList.add('hidden');
                    passwordConfirmation.setCustomValidity('');
                    return true;
                }
            }

            passwordConfirmation.addEventListener('input', validatePasswordMatch);

            // Validasi email
            emailInput.addEventListener('blur', function() {
                const email = this.value;

                if (email.length > 0) {
                    fetch("{{ route('check.email') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                email: email
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.exists) {
                                Toastify({
                                    text: "Email sudah terdaftar!",
                                    duration: 3000,
                                    close: true,
                                    gravity: "top",
                                    position: "right",
                                    stopOnFocus: true,
                                    style: {
                                        background: "#dc2626",
                                        color: "#fff",
                                        fontWeight: "bold",
                                        borderRadius: "8px",
                                        boxShadow: "0 4px 6px rgba(0, 0, 0, 0.1)",
                                    }
                                }).showToast();

                                emailInput.focus();
                            }
                        });
                }
            });

            // Validasi saat form disubmit
            form.addEventListener('submit', function(e) {
                const passwordValue = password.value;
                const result = validatePasswordComplexity(passwordValue);
                const passwordsMatch = validatePasswordMatch();

                if (!result.isValid) {
                    e.preventDefault();
                    passwordError.classList.remove('hidden');

                    // Tampilkan notifikasi error detail
                    let missingRequirements = [];
                    if (!result.errors.hasUpperCase) missingRequirements.push("huruf besar");
                    if (!result.errors.hasLowerCase) missingRequirements.push("huruf kecil");
                    if (!result.errors.hasNumber) missingRequirements.push("angka");
                    if (!result.errors.hasSpecialChar) missingRequirements.push("simbol");
                    if (!result.errors.isLongEnough) missingRequirements.push("min. 8 karakter");

                    showErrorToast(`Kata sandi harus mengandung: ${missingRequirements.join(", ")}`);
                    password.focus();
                    return;
                }

                if (!passwordsMatch) {
                    e.preventDefault();
                    showErrorToast("Kata sandi dan konfirmasi tidak cocok!");
                    passwordConfirmation.focus();
                }
            });

            // Fungsi untuk menampilkan notifikasi
            function showErrorToast(message) {
                Toastify({
                    text: message,
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: {
                        background: "linear-gradient(to right, #ff5f6d, #ffc371)",
                        fontWeight: "bold"
                    }
                }).showToast();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchForm = document.getElementById('searchForm');
            let searchTimeout;

            // Fungsi untuk submit form
            function submitSearchForm() {
                clearTimeout(searchTimeout);
                searchForm.submit();
            }

            // Event listener untuk input
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(submitSearchForm, 500); // Debounce 500ms
            });

            // Optional: Prevent form submission on enter to avoid page reload
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                submitSearchForm();
            });
        });
    </script>
</x-app-layout>
