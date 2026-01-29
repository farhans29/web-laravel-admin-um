<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page Header -->
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
                    <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl animate-modal relative z-50"
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
                                <div class="mb-5 relative">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Email <span
                                            class="text-red-500">*</span></label>
                                    <input type="email" name="email" id="email" required autocomplete="off"
                                        class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                        focus:border-indigo-500 focus:ring-3 focus:ring-indigo-200 transition-all duration-200"
                                        placeholder="Masukkan alamat email" value="{{ old('email') }}">
                                    <div id="emailSuggestions" class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-48 overflow-y-auto">
                                    </div>
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- NIK -->
                                <div class="mb-5">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                                    <input type="text" name="nik" id="nik" maxlength="16"
                                        class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm
                                        focus:border-indigo-500 focus:ring-3 focus:ring-indigo-200 transition-all duration-200"
                                        placeholder="Masukkan NIK (16 digit)" value="{{ old('nik') }}">
                                    @error('nik')
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
                                    <p class="text-gray-500 text-xs mt-1">Harus mengandung huruf besar, kecil, angka,
                                        simbol, dan minimal 8 karakter</p>
                                    @error('password')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-5">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Kata Sandi
                                        <span class="text-red-500">*</span></label>
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

                                <!-- User Type Selection -->
                                <div class="mb-5">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Tipe Akun <span class="text-red-500">*</span>
                                    </label>
                                    <div class="flex gap-4">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="user_type" id="user_type_ho" value="0"
                                                {{ old('user_type', '0') == '0' ? 'checked' : '' }}
                                                class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                                onchange="togglePropertyField()">
                                            <span class="ml-2 text-sm text-gray-700 flex items-center gap-1">
                                                🏢 <strong>HO (Head Office)</strong> - Akses semua properti
                                            </span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="user_type" id="user_type_site" value="1"
                                                {{ old('user_type') == '1' ? 'checked' : '' }}
                                                class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                                onchange="togglePropertyField()">
                                            <span class="ml-2 text-sm text-gray-700 flex items-center gap-1">
                                                📍 <strong>Site</strong> - Akses 1 properti tertentu
                                            </span>
                                        </label>
                                    </div>
                                    @error('user_type')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Property -->
                                <div class="mb-5" id="property_field_container">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Properti <span class="text-red-500" id="property_required_mark">*</span>
                                    </label>
                                    <select name="property_id" id="property_id"
                                        class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-3 focus:ring-indigo-200 transition-all duration-200">
                                        <option value="">Pilih Properti</option>
                                        @foreach ($properties as $property)
                                            <option value="{{ $property->idrec }}"
                                                {{ old('property_id') == $property->idrec ? 'selected' : '' }}>
                                                {{ $property->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1" id="property_field_hint">
                                        <i class="fas fa-info-circle"></i>
                                        <span id="property_hint_text">Wajib diisi untuk akun Site</span>
                                    </p>
                                    @error('property_id')
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
                    <form method="GET" action="{{ route('users-newManagement') }}" class="flex items-center gap-6">
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari pengguna..."
                                class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 w-56">
                            <div class="absolute left-3 top-2.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>

                        <!-- Status Filter -->
                        <div class="flex items-center">
                            <label for="statusFilter" class="mr-2 text-sm font-medium text-gray-600">Status:</label>
                            <select name="status" id="statusFilter" onchange="this.form.submit()"
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 w-36">
                                <option value="1" {{ $statusFilter == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ $statusFilter == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                                <option value="all" {{ $statusFilter == 'all' ? 'selected' : '' }}>Semua</option>
                            </select>
                        </div>

                        <!-- Per Page Select -->
                        <div class="flex items-center">
                            <label for="perPageSelect" class="mr-2 text-sm font-medium text-gray-600">Show:</label>
                            <select name="per_page" id="perPageSelect" onchange="this.form.submit()"
                                class="border border-gray-300 rounded-lg px-2 py-2 text-sm focus:ring-2 focus:ring-indigo-500 w-16">
                                <option value="8" {{ $perPage == 8 ? 'selected' : '' }}>8</option>
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                            </select>
                        </div>

                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition" title="Cari">
                            <i class="fas fa-search"></i>
                        </button>

                        @if(request('search') || request('status') != '1' || request('per_page'))
                            <a href="{{ route('users-newManagement') }}"
                                class="px-3 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500 transition"
                                title="Reset Filter">
                                <i class="fas fa-undo text-sm"></i>
                            </a>
                        @endif
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
                            <th class="px-4 py-3 text-center">Tipe</th>
                            <th class="px-4 py-3 text-center">Peran</th>
                            <th class="px-4 py-3">Properti</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3">Dibuat Pada</th>
                            <th class="px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody" class="divide-y divide-gray-200 bg-white text-sm text-gray-700">
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

                                <!-- User Type -->
                                <td class="px-4 py-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium
                                        {{ $user->user_type == 0 ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }}">
                                        <span class="text-base">{{ $user->user_type == 0 ? '🏢' : '📍' }}</span>
                                        {{ $user->user_type == 0 ? 'HO' : 'Site' }}
                                    </span>
                                </td>

                                @php
                                    $roleName = optional($user->role)->name ?? 'No Role';
                                @endphp
                                <!-- Role -->
                                <td class="px-4 py-4 text-center">
                                    <span
                                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">

                                        @switch($roleName)
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

                                            @case('creative')
                                                <i class="fas fa-paint-brush"></i>
                                            @break

                                            @case('administrator')
                                                <i class="fas fa-user-shield"></i> 
                                            @break

                                            @default
                                                <i class="fas fa-user"></i>
                                        @endswitch

                                        {{ $roleName }}
                                    </span>
                                </td>

                                <!-- Property -->
                                <td class="px-4 py-4 text-sm text-gray-900">
                                    @if($user->property)
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-building text-indigo-600"></i>
                                            <span>{{ $user->property->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-400 italic">Tidak ada</span>
                                    @endif
                                </td>

                                <!-- Status -->
                                <td class="px-4 py-4 whitespace-nowrap text-center">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" value="" class="sr-only peer" data-id="{{ $user->id }}"
                                            {{ $user->status == 1 ? 'checked' : '' }} onchange="toggleUserStatus(this)">
                                        <div
                                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                        </div>
                                        <span class="ml-3 text-sm font-medium text-gray-900 transition-opacity duration-200">
                                            {{ $user->status == 1 ? 'Aktif' : 'Tidak Aktif' }}
                                        </span>
                                    </label>
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
                                        <!-- Reset Password Button (Only for Superadmin) -->
                                        @if(Auth::user()->isSuperAdmin())
                                            <div x-data="{ modalOpenResetPwd{{ $user->id }}: false, showNewPassword{{ $user->id }}: false, showConfirmPassword{{ $user->id }}: false }">
                                                <button class="p-2 rounded-xl text-amber-600 hover:bg-amber-50 transition"
                                                    type="button"
                                                    title="Reset Password"
                                                    @click.prevent="modalOpenResetPwd{{ $user->id }} = true">
                                                    <i class="fas fa-key"></i>
                                                </button>

                                                <!-- Modal backdrop -->
                                                <div class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 transition-opacity"
                                                    x-show="modalOpenResetPwd{{ $user->id }}" x-transition.opacity
                                                    aria-hidden="true" x-cloak></div>

                                                <!-- Modal dialog -->
                                                <div class="fixed inset-0 z-50 flex items-center justify-center px-4 sm:px-6"
                                                    x-show="modalOpenResetPwd{{ $user->id }}" x-transition x-cloak
                                                    @keydown.escape.window="modalOpenResetPwd{{ $user->id }} = false">
                                                    <div class="bg-white rounded-xl shadow-lg w-full max-w-md animate-modal relative z-50"
                                                        @click.outside="modalOpenResetPwd{{ $user->id }} = false">

                                                        <!-- Header -->
                                                        <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center">
                                                            <div>
                                                                <h2 class="text-xl font-semibold text-gray-900">Reset Password</h2>
                                                                <p class="text-sm text-gray-500 mt-1">{{ $user->first_name }} {{ $user->last_name }} ({{ '@' . $user->username }})</p>
                                                            </div>
                                                            <button class="text-gray-500 text-xl hover:text-gray-700 transition-colors"
                                                                @click="modalOpenResetPwd{{ $user->id }} = false">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </div>

                                                        <!-- Body -->
                                                        <div class="p-6">
                                                            <form method="POST" action="{{ route('users.resetPassword', $user->id) }}" id="resetPasswordForm{{ $user->id }}">
                                                                @csrf
                                                                @method('PUT')

                                                                <div class="mb-4 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                                                                    <div class="flex gap-2">
                                                                        <i class="fas fa-exclamation-triangle text-amber-600 mt-0.5"></i>
                                                                        <div class="text-sm text-amber-800">
                                                                            <strong>Perhatian!</strong> Anda akan mereset password untuk user ini. Pastikan untuk memberitahu user password baru mereka.
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- New Password -->
                                                                <div class="mb-4">
                                                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                                                        Password Baru <span class="text-red-500">*</span>
                                                                    </label>
                                                                    <div class="relative">
                                                                        <input :type="showNewPassword{{ $user->id }} ? 'text' : 'password'"
                                                                            name="new_password"
                                                                            id="new_password_{{ $user->id }}"
                                                                            minlength="8"
                                                                            required
                                                                            class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm pr-10
                                                                            focus:border-indigo-500 focus:ring-3 focus:ring-indigo-200 transition-all duration-200"
                                                                            placeholder="Masukkan password baru">
                                                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer"
                                                                            @click="showNewPassword{{ $user->id }} = !showNewPassword{{ $user->id }}">
                                                                            <i class="fas" :class="showNewPassword{{ $user->id }} ? 'fa-eye-slash' : 'fa-eye'"></i>
                                                                        </span>
                                                                    </div>
                                                                    <p class="text-gray-500 text-xs mt-1">Harus mengandung huruf besar, kecil, angka, simbol, dan minimal 8 karakter</p>
                                                                </div>

                                                                <!-- Confirm Password -->
                                                                <div class="mb-4">
                                                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                                                        Konfirmasi Password <span class="text-red-500">*</span>
                                                                    </label>
                                                                    <div class="relative">
                                                                        <input :type="showConfirmPassword{{ $user->id }} ? 'text' : 'password'"
                                                                            name="new_password_confirmation"
                                                                            id="new_password_confirmation_{{ $user->id }}"
                                                                            minlength="8"
                                                                            required
                                                                            class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm pr-10
                                                                            focus:border-indigo-500 focus:ring-3 focus:ring-indigo-200 transition-all duration-200"
                                                                            placeholder="Konfirmasi password baru">
                                                                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 cursor-pointer"
                                                                            @click="showConfirmPassword{{ $user->id }} = !showConfirmPassword{{ $user->id }}">
                                                                            <i class="fas" :class="showConfirmPassword{{ $user->id }} ? 'fa-eye-slash' : 'fa-eye'"></i>
                                                                        </span>
                                                                    </div>
                                                                </div>

                                                                <!-- Footer -->
                                                                <div class="mt-6 flex justify-end gap-3">
                                                                    <button type="button" @click="modalOpenResetPwd{{ $user->id }} = false"
                                                                        class="px-4 py-2 rounded-lg border text-sm font-medium text-gray-600 hover:bg-gray-100 transition">
                                                                        Batal
                                                                    </button>
                                                                    <button type="submit"
                                                                        class="px-5 py-2.5 bg-amber-600 text-white rounded-lg shadow hover:bg-amber-700 transition-all duration-200 flex items-center gap-2">
                                                                        <i class="fas fa-key"></i>
                                                                        <span>Reset Password</span>
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

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
                                                <div class="bg-white rounded-xl shadow-lg w-full max-w-2xl animate-modal relative z-50"
                                                    @click.outside="modalOpenEdit{{ $user->id }} = false">

                                                    <!-- Header -->
                                                    <div
                                                        class="px-6 py-5 border-b border-gray-200 flex justify-between items-center">
                                                        <h2 class="text-xl font-semibold text-gray-900">Edit Pengguna
                                                        </h2>
                                                        <button
                                                            class="text-gray-500 text-xl hover:text-gray-700 transition-colors"
                                                            @click="modalOpenEdit{{ $user->id }} = false">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>

                                                    <!-- Body -->
                                                    <div class="p-6">
                                                        <form method="POST"
                                                            action="{{ route('users.update', $user->id) }}">
                                                            @csrf
                                                            @method('PUT')

                                                            <!-- First & Last Name -->
                                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
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

                                                            <!-- Username -->
                                                            <div class="mb-5">
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
                                                            <div class="mb-5 relative">
                                                                <label for="email_{{ $user->id }}"
                                                                    class="block text-sm font-medium text-gray-700 mb-1">
                                                                    Email <span class="text-red-500">*</span>
                                                                </label>
                                                                <input type="email" name="email" autocomplete="off"
                                                                    id="email_{{ $user->id }}"
                                                                    value="{{ $user->email }}" required
                                                                    class="email-input-edit w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-3 focus:ring-indigo-200 transition-all duration-200">
                                                                <div id="emailSuggestions_{{ $user->id }}" class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-48 overflow-y-auto">
                                                                </div>
                                                            </div>

                                                            <!-- NIK -->
                                                            <div class="mb-5">
                                                                <label for="nik_{{ $user->id }}"
                                                                    class="block text-sm font-medium text-gray-700 mb-1">
                                                                    NIK
                                                                </label>
                                                                <input type="text" name="nik"
                                                                    id="nik_{{ $user->id }}"
                                                                    value="{{ $user->nik ?? '' }}" maxlength="16"
                                                                    class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-3 focus:ring-indigo-200 transition-all duration-200"
                                                                    placeholder="Masukkan NIK (16 digit)">
                                                            </div>

                                                            <!-- Role -->
                                                            <div class="mb-5">
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

                                                            <!-- User Type Selection -->
                                                            <div class="mb-5">
                                                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                                                    Tipe Akun <span class="text-red-500">*</span>
                                                                </label>
                                                                <div class="flex gap-4">
                                                                    <label class="flex items-center cursor-pointer">
                                                                        <input type="radio" name="user_type"
                                                                            id="user_type_ho_{{ $user->id }}"
                                                                            value="0"
                                                                            {{ $user->user_type == 0 ? 'checked' : '' }}
                                                                            class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                                                            onchange="togglePropertyFieldEdit{{ $user->id }}()">
                                                                        <span class="ml-2 text-sm text-gray-700 flex items-center gap-1">
                                                                            🏢 <strong>HO (Head Office)</strong> - Akses semua properti
                                                                        </span>
                                                                    </label>
                                                                    <label class="flex items-center cursor-pointer">
                                                                        <input type="radio" name="user_type"
                                                                            id="user_type_site_{{ $user->id }}"
                                                                            value="1"
                                                                            {{ $user->user_type == 1 ? 'checked' : '' }}
                                                                            class="w-4 h-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                                                            onchange="togglePropertyFieldEdit{{ $user->id }}()">
                                                                        <span class="ml-2 text-sm text-gray-700 flex items-center gap-1">
                                                                            📍 <strong>Site</strong> - Akses 1 properti tertentu
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            </div>

                                                            <!-- Property -->
                                                            <div class="mb-5" id="property_field_container_{{ $user->id }}">
                                                                <label for="property_id_{{ $user->id }}"
                                                                    class="block text-sm font-medium text-gray-700 mb-1">
                                                                    Properti <span class="text-red-500" id="property_required_mark_{{ $user->id }}">*</span>
                                                                </label>
                                                                <select name="property_id"
                                                                    id="property_id_{{ $user->id }}"
                                                                    class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-sm focus:border-indigo-500 focus:ring-3 focus:ring-indigo-200 transition-all duration-200">
                                                                    <option value="">Pilih Properti</option>
                                                                    @foreach ($properties as $property)
                                                                        <option value="{{ $property->idrec }}"
                                                                            {{ $user->property_id == $property->idrec ? 'selected' : '' }}>
                                                                            {{ $property->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                <p class="text-xs text-gray-500 mt-1" id="property_field_hint_{{ $user->id }}">
                                                                    <i class="fas fa-info-circle"></i>
                                                                    <span id="property_hint_text_{{ $user->id }}">Wajib diisi untuk akun Site</span>
                                                                </p>
                                                            </div>

                                                            <script>
                                                                // Toggle Property Field for Edit Modal {{ $user->id }}
                                                                function togglePropertyFieldEdit{{ $user->id }}() {
                                                                    const userTypeHO = document.getElementById('user_type_ho_{{ $user->id }}');
                                                                    const propertyField = document.getElementById('property_id_{{ $user->id }}');
                                                                    const propertyRequiredMark = document.getElementById('property_required_mark_{{ $user->id }}');
                                                                    const propertyHintText = document.getElementById('property_hint_text_{{ $user->id }}');

                                                                    if (userTypeHO && userTypeHO.checked) {
                                                                        // HO selected - disable property field
                                                                        propertyField.disabled = true;
                                                                        propertyField.required = false;
                                                                        propertyField.value = '';
                                                                        propertyField.classList.add('bg-gray-100', 'cursor-not-allowed');
                                                                        propertyRequiredMark.classList.add('hidden');
                                                                        propertyHintText.textContent = 'Tidak perlu properti untuk akun HO';
                                                                    } else {
                                                                        // Site selected - enable property field
                                                                        propertyField.disabled = false;
                                                                        propertyField.required = true;
                                                                        propertyField.classList.remove('bg-gray-100', 'cursor-not-allowed');
                                                                        propertyRequiredMark.classList.remove('hidden');
                                                                        propertyHintText.textContent = 'Wajib diisi untuk akun Site';
                                                                    }
                                                                }

                                                                // Initialize on modal open
                                                                document.addEventListener('DOMContentLoaded', function() {
                                                                    togglePropertyFieldEdit{{ $user->id }}();
                                                                });
                                                            </script>

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

        <div class="bg-gray-50 rounded p-4" id="paginationContainer">
            <div class="px-6 py-4" id="paginationSection">
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
        // Toggle Property Field based on User Type
        function togglePropertyField() {
            const userTypeHO = document.getElementById('user_type_ho');
            const propertyField = document.getElementById('property_id');
            const propertyRequiredMark = document.getElementById('property_required_mark');
            const propertyHintText = document.getElementById('property_hint_text');

            if (userTypeHO && userTypeHO.checked) {
                // HO selected - disable property field
                propertyField.disabled = true;
                propertyField.required = false;
                propertyField.value = '';
                propertyField.classList.add('bg-gray-100', 'cursor-not-allowed');
                propertyRequiredMark.classList.add('hidden');
                propertyHintText.textContent = 'Tidak perlu properti untuk akun HO';
            } else {
                // Site selected - enable property field
                propertyField.disabled = false;
                propertyField.required = true;
                propertyField.classList.remove('bg-gray-100', 'cursor-not-allowed');
                propertyRequiredMark.classList.remove('hidden');
                propertyHintText.textContent = 'Wajib diisi untuk akun Site';
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            togglePropertyField();
        });

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

        // Toggle User Status Function
        function toggleUserStatus(checkbox) {
            const userId = checkbox.getAttribute('data-id');
            const newStatus = checkbox.checked ? 1 : 0;

            const statusLabel = checkbox.closest('label').querySelector('span');

            fetch(`/settings/users/${userId}/status`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        status: newStatus
                    })
                })
                .then(res => {
                    if (!res.ok) throw new Error("Gagal update status");
                    return res.json();
                })
                .then(() => {
                    // Animasi perubahan label status
                    statusLabel.classList.add('opacity-0');

                    setTimeout(() => {
                        statusLabel.textContent = newStatus === 1 ? 'Aktif' : 'Tidak Aktif';
                        statusLabel.classList.remove('opacity-0');
                        statusLabel.classList.add('opacity-100');
                    }, 200);

                    // Notifikasi Toastify
                    Toastify({
                        text: newStatus === 1 ?
                            "✓ User berhasil diaktifkan" : "⚠ User berhasil dinonaktifkan",
                        duration: 3500,
                        close: true,
                        gravity: "top",
                        position: "right",
                        stopOnFocus: true,
                        className: "shadow-lg rounded-md",
                        style: {
                            background: newStatus === 1 ?
                                "linear-gradient(to right, #4CAF50, #2E7D32)" :
                                "linear-gradient(to right, #F44336, #C62828)"
                        }
                    }).showToast();
                })
                .catch(err => {
                    // Revert checkbox jika error
                    checkbox.checked = !checkbox.checked;

                    Toastify({
                        text: "❌ Gagal mengubah status user",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "linear-gradient(to right, #ff5f6d, #ffc371)"
                        }
                    }).showToast();

                    console.error(err);
                });
        }

        // Email Autocomplete Feature
        document.addEventListener('DOMContentLoaded', function() {
            const emailDomains = [
                '@gmail.com',
                '@yahoo.com',
                '@outlook.com',
                '@hotmail.com',
                '@icloud.com',
                '@live.com',
                '@aol.com',
                '@protonmail.com',
                '@zoho.com',
                '@mail.com'
            ];

            // Setup autocomplete untuk input email utama (create user)
            const emailInput = document.getElementById('email');
            const suggestionsBox = document.getElementById('emailSuggestions');

            if (emailInput) {
                emailInput.addEventListener('input', function() {
                    setupEmailAutocomplete(emailInput, suggestionsBox, emailDomains);
                });
            }

            // Setup autocomplete untuk semua input email edit (menggunakan event delegation)
            document.addEventListener('input', function(e) {
                if (e.target && e.target.classList.contains('email-input-edit')) {
                    const userId = e.target.id.split('_')[1];
                    const editSuggestionsBox = document.getElementById(`emailSuggestions_${userId}`);
                    setupEmailAutocomplete(e.target, editSuggestionsBox, emailDomains);
                }
            });

            // Function untuk setup email autocomplete
            function setupEmailAutocomplete(input, suggestionsBox, domains) {
                const value = input.value.trim();
                const atIndex = value.indexOf('@');

                // Jika belum ada @ atau sudah ada domain lengkap, sembunyikan suggestions
                if (atIndex === -1 || (atIndex !== -1 && value.includes('.', atIndex))) {
                    if (suggestionsBox) suggestionsBox.classList.add('hidden');
                    return;
                }

                // Ambil username (sebelum @)
                const username = value.substring(0, atIndex);

                // Jika username kosong, tidak tampilkan suggestions
                if (username.length === 0) {
                    if (suggestionsBox) suggestionsBox.classList.add('hidden');
                    return;
                }

                // Buat list suggestions
                const typedDomain = value.substring(atIndex);
                const matchedDomains = domains.filter(domain =>
                    domain.toLowerCase().startsWith(typedDomain.toLowerCase())
                );

                if (matchedDomains.length > 0 && suggestionsBox) {
                    suggestionsBox.innerHTML = matchedDomains.map(domain => `
                        <div class="px-4 py-2 hover:bg-indigo-50 cursor-pointer transition-colors flex items-center justify-between group"
                             onclick="selectEmailSuggestion('${input.id}', '${username}${domain}')">
                            <span class="text-gray-700">${username}<span class="font-semibold text-indigo-600">${domain}</span></span>
                            <svg class="w-4 h-4 text-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    `).join('');
                    suggestionsBox.classList.remove('hidden');
                } else {
                    if (suggestionsBox) suggestionsBox.classList.add('hidden');
                }
            }

            // Sembunyikan suggestions saat klik di luar
            document.addEventListener('click', function(e) {
                // Sembunyikan suggestion box utama
                if (emailInput && suggestionsBox) {
                    if (!emailInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                        suggestionsBox.classList.add('hidden');
                    }
                }

                // Sembunyikan semua suggestion box edit
                document.querySelectorAll('[id^="emailSuggestions_"]').forEach(box => {
                    const userId = box.id.split('_')[1];
                    const editInput = document.getElementById(`email_${userId}`);
                    if (editInput && !editInput.contains(e.target) && !box.contains(e.target)) {
                        box.classList.add('hidden');
                    }
                });
            });

            // Sembunyikan suggestions saat tekan Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    document.querySelectorAll('[id^="emailSuggestions"]').forEach(box => {
                        box.classList.add('hidden');
                    });
                }
            });
        });

        // Fungsi untuk memilih suggestion
        function selectEmailSuggestion(inputId, email) {
            const emailInput = document.getElementById(inputId);
            let suggestionsBox;

            if (inputId === 'email') {
                suggestionsBox = document.getElementById('emailSuggestions');
            } else {
                const userId = inputId.split('_')[1];
                suggestionsBox = document.getElementById(`emailSuggestions_${userId}`);
            }

            if (emailInput) {
                emailInput.value = email;
                if (suggestionsBox) suggestionsBox.classList.add('hidden');
                emailInput.focus();
            }
        }
    </script>
</x-app-layout>
