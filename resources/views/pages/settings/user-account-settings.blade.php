<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto" x-data="{ activeTab: 'account' }">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
            <div>
                <h1
                    class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                    Account Settings
                </h1>
            </div>
        </div>

        <div id="containerAccount" class="bg-white shadow-md rounded-lg overflow-hidden mt-8">
            <!-- Header -->
            <div class="flex justify-between items-center px-6 py-4 bg-gray-50">
                <h3 class="text-lg font-medium text-gray-900">User Profile</h3>
                <!-- Tabs -->
                <div class="flex items-center gap-4">
                    <!-- Account -->
                    <button @click="activeTab = 'account'"
                        :class="activeTab === 'account'
                            ?
                            'bg-blue-500 text-white px-3 py-2 rounded-lg' :
                            'text-gray-600 hover:text-blue-500'"
                        class="flex items-center gap-2 text-sm font-medium transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="7" r="4" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.5 21a7.5 7.5 0 0113 0" />
                        </svg>
                        Account
                    </button>

                    @if (auth()->user()->is_admin == 1)
                        <!-- Security -->
                        <button @click="activeTab = 'security'"
                            :class="activeTab === 'security'
                                ?
                                'bg-blue-500 text-white px-3 py-2 rounded-lg' :
                                'text-gray-600 hover:text-blue-500'"
                            class="flex items-center gap-2 text-sm font-medium transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 15v2m-6-6h12a2 2 0 012 2v7a2 2 0 01-2 2H6a2 2 0 01-2-2v-7a2 2 0 012-2zm10-4V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            Security
                        </button>
                    @endif

                    <!-- History -->
                    <button @click="activeTab = 'history'"
                        :class="activeTab === 'history'
                            ?
                            'bg-blue-500 text-white px-3 py-2 rounded-lg' :
                            'text-gray-600 hover:text-blue-500'"
                        class="flex items-center gap-2 text-sm font-medium transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        History
                    </button>
                </div>
            </div>
        </div>


        <div class="p-6">
            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Left Column: Profile Card (selalu tampil) -->
                <div class="w-full lg:w-1/3 space-y-6">
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 group">
                        <div class="flex flex-col items-center text-center">
                            <!-- Avatar -->
                            <div class="relative mb-4">
                                <div
                                    class="w-28 h-28 rounded-full bg-gradient-to-r from-purple-500 to-indigo-600 flex items-center justify-center text-white text-4xl font-bold shadow-lg">
                                    @if ($user->profile_photo_path)
                                        <img src="{{ asset('storage/' . $user->profile_photo_path) }}"
                                            alt="{{ $user->first_name }} {{ $user->last_name }}"
                                            class="w-28 h-28 rounded-full object-cover shadow-lg">
                                    @else
                                        <div
                                            class="w-28 h-28 rounded-full bg-gradient-to-r from-purple-500 to-indigo-600 flex items-center justify-center text-white text-4xl font-bold shadow-lg">
                                            {{ strtoupper(substr($user->first_name, 0, 1)) }}{{ strtoupper(substr($user->last_name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-green-500 border-2 border-white shadow-md"
                                    title="Active"></div>
                            </div>

                            <!-- User Info -->
                            <h2 class="text-2xl font-bold text-gray-900 mb-1">{{ $user->first_name }}
                                {{ $user->last_name }}</h2>
                            <p class="text-gray-600 mb-3 flex items-center justify-center">
                                <svg class="w-4 h-4 mr-1 text-gray-500" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z">
                                    </path>
                                </svg>
                                {{ $user->role->name ?? 'No Role Assigned' }}
                            </p>

                            <!-- Status Badge -->
                            <div
                                class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium mb-6 shadow-sm {{ $user->status == 1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <span
                                    class="w-2.5 h-2.5 rounded-full mr-2 animate-pulse {{ $user->status == 1 ? 'bg-green-500' : 'bg-red-500' }}">
                                </span>
                                {{ $user->status == 1 ? 'Active' : 'Inactive' }}
                            </div>


                            <!-- Separator -->
                            <div class="w-full border-t border-gray-100 my-4"></div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-3 w-full">
                                <div x-data="{ modalOpenDetail: false, showPassword: false, showConfirmPassword: false }" class="w-full">
                                    <!-- Button -->
                                    <button
                                        class="w-full px-5 py-3 bg-gradient-to-r from-indigo-50 to-blue-50 text-indigo-600 rounded-xl text-sm font-medium hover:from-indigo-100 hover:to-blue-100 hover:shadow-md transition-all duration-300 flex items-center justify-center group/button"
                                        type="button" @click.prevent="modalOpenDetail = true">
                                        <svg class="w-4 h-4 mr-2 group-hover/button:scale-110 transition-transform"
                                            fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                            </path>
                                        </svg>
                                        Edit Profile
                                    </button>

                                    <!-- Modal backdrop -->
                                    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm z-40 transition-opacity"
                                        x-show="modalOpenDetail" x-transition.opacity aria-hidden="true" x-cloak>
                                    </div>

                                    <!-- Modal dialog -->
                                    <div class="fixed inset-0 z-50 flex items-center justify-center px-4 sm:px-6"
                                        x-show="modalOpenDetail" x-transition x-cloak
                                        @keydown.escape.window="modalOpenDetail = false">
                                        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md animate-modal relative z-50 overflow-hidden"
                                            @click.outside="modalOpenDetail = false">

                                            <!-- Header -->
                                            <div
                                                class="px-7 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                                                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                                    <svg class="w-5 h-5 mr-2 text-indigo-600" fill="currentColor"
                                                        viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z">
                                                        </path>
                                                    </svg>
                                                    Edit Profile
                                                </h2>
                                                <button
                                                    class="text-gray-500 hover:text-gray-700 transition-colors p-1 rounded-full hover:bg-gray-100"
                                                    @click="modalOpenDetail = false">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd"
                                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                            clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>
                                            </div>

                                            <!-- Body -->
                                            <div class="p-6">
                                                <form id="userForm" method="POST"
                                                    action="{{ route('users.store') }}" enctype="multipart/form-data"
                                                    autocomplete="off">
                                                    @csrf

                                                    <!-- First Name & Last Name -->
                                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                                                        <div class="group/input">
                                                            <label
                                                                class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                                                First Name <span class="text-red-500 ml-1">*</span>
                                                            </label>
                                                            <input type="text" name="first_name" id="first_name"
                                                                required
                                                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100 transition-all duration-200 group-hover/input:border-gray-300"
                                                                placeholder="First name"
                                                                value="{{ old('first_name') }}">
                                                            @error('first_name')
                                                                <p class="text-red-500 text-xs mt-2">{{ $message }}
                                                                </p>
                                                            @enderror
                                                        </div>
                                                        <div class="group/input">
                                                            <label
                                                                class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                                                Last Name <span class="text-red-500 ml-1">*</span>
                                                            </label>
                                                            <input type="text" name="last_name" id="last_name"
                                                                required
                                                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100 transition-all duration-200 group-hover/input:border-gray-300"
                                                                placeholder="Last name"
                                                                value="{{ old('last_name') }}">
                                                            @error('last_name')
                                                                <p class="text-red-500 text-xs mt-2">{{ $message }}
                                                                </p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <!-- Username -->
                                                    <div class="mb-5 group/input">
                                                        <label
                                                            class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                                            Username <span class="text-red-500 ml-1">*</span>
                                                        </label>
                                                        <input type="text" name="username" id="username" required
                                                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100 transition-all duration-200 group-hover/input:border-gray-300"
                                                            placeholder="Username" value="{{ old('username') }}">
                                                        @error('username')
                                                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Email -->
                                                    <div class="mb-5 group/input">
                                                        <label
                                                            class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                                            Email <span class="text-red-500 ml-1">*</span>
                                                        </label>
                                                        <input type="email" name="email" id="email" required
                                                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100 transition-all duration-200 group-hover/input:border-gray-300"
                                                            placeholder="Email address" value="{{ old('email') }}">
                                                        @error('email')
                                                            <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                                                        @enderror
                                                    </div>

                                                    <!-- Phone Number -->
                                                    <div class="mb-5 group/input">
                                                        <label
                                                            class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                                            Contact <span class="text-red-500 ml-1">*</span>
                                                        </label>
                                                        <input type="text" name="phone_number" id="phone_number"
                                                            required
                                                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:border-indigo-500 focus:ring-3 focus:ring-indigo-100 transition-all duration-200 group-hover/input:border-gray-300"
                                                            placeholder="Phone number"
                                                            value="{{ old('phone_number') }}">
                                                        @error('phone_number')
                                                            <p class="text-red-500 text-xs mt-2">{{ $message }}
                                                            </p>
                                                        @enderror
                                                    </div>
                                                    <!-- Footer -->
                                                    <div class="mt-6 flex justify-end gap-3">
                                                        <button type="button" @click="modalOpenDetail = false"
                                                            class="px-5 py-2.5 rounded-xl border text-sm font-medium text-gray-600 hover:bg-gray-100 transition-all duration-200">
                                                            Cancel
                                                        </button>
                                                        <button type="submit"
                                                            class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl shadow hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 flex items-center gap-2 hover:shadow-md">
                                                            <svg class="w-4 h-4" fill="currentColor"
                                                                viewBox="0 0 20 20"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path fill-rule="evenodd"
                                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                    clip-rule="evenodd"></path>
                                                            </svg>
                                                            <span>Save Changes</span>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: berubah sesuai tab -->
                <div class="w-full lg:w-2/3 space-y-6">
                    <!-- Account Details -->
                    <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300"
                        x-show="activeTab === 'account'">
                        <h2 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Account Details
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="group">
                                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Username
                                </label>
                                <div
                                    class="flex items-center text-sm text-gray-900 bg-gray-50 p-3 rounded-xl border border-gray-100 group-hover:bg-indigo-50 group-hover:border-indigo-100 transition-colors">
                                    <span class="text-indigo-600 font-medium">
                                        {{ $user->username ? '@' . $user->username : '' }}
                                    </span>
                                </div>

                            </div>

                            <div class="group">
                                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z">
                                        </path>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                    </svg>
                                    Email
                                </label>
                                <div
                                    class="flex items-center text-sm text-gray-900 bg-gray-50 p-3 rounded-xl border border-gray-100 group-hover:bg-indigo-50 group-hover:border-indigo-100 transition-colors">
                                    <span>{{ $user->email }}</span>
                                    @if ($user->email_verified_at)
                                        <span
                                            class="ml-2 px-1.5 py-0.5 text-xs font-medium bg-emerald-100 text-emerald-800 rounded-full">
                                            Verified
                                        </span>
                                    @else
                                        <span
                                            class="ml-2 px-1.5 py-0.5 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                            Not Verified
                                        </span>
                                    @endif
                                </div>

                            </div>

                            <div class="group">
                                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Role
                                </label>
                                <div
                                    class="text-sm text-gray-900 bg-gray-50 p-3 rounded-xl border border-gray-100 group-hover:bg-indigo-50 group-hover:border-indigo-100 transition-colors">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        {{ $user->role->name }}
                                    </span>
                                </div>
                            </div>

                            <div class="group">
                                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z">
                                        </path>
                                    </svg>
                                    Contact
                                </label>
                                <div
                                    class="flex items-center text-sm text-gray-900 bg-gray-50 p-3 rounded-xl border border-gray-100 group-hover:bg-indigo-50 group-hover:border-indigo-100 transition-colors">
                                    <span>{{ $user->phone_number }}</span>
                                </div>
                            </div>

                            <div class="group">
                                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M7 2a1 1 0 011 1v1h3a1 1 0 110 2H9.578a18.87 18.87 0 01-1.724 4.78c.29.354.596.696.917 1.026a1 1 0 11-1.44 1.389 21.034 21.034 0 01-.02-.02 19.879 19.879 0 01-3.155 3.564 1 1 0 01-1.325-1.504 17.919 17.919 0 013.057-2.897c.027-.024.053-.049.079-.074a19.088 19.088 0 01-1.73-3.617c-.317.07-.65.108-.989.108A1 1 0 013 8V7a1 1 0 011-1h1V5a1 1 0 012 0v1h3a1 1 0 110 2H6.422a18.87 18.87 0 001.724 4.78 10.141 10.141 0 00-.917-1.026 1 1 0 111.44-1.389c.007.008.014.015.02.02A19.879 19.879 0 0012.6 8.566a1 1 0 111.325 1.504 17.919 17.919 0 01-3.057 2.897l-.079.074a19.088 19.088 0 011.73 3.617c.317-.07.65-.108.989-.108a1 1 0 01.108 2l-.108.001c-.45 0-.882-.1-1.275-.28a21.037 21.037 0 01-4.434-2.716 1 1 0 01-1.325-1.504 17.919 17.919 0 013.057-2.897c.027-.024.053-.049.079-.074A19.088 19.088 0 015 8.001c0-.34.038-.672.108-.989A1 1 0 015 6V5a1 1 0 011-1h1V3a1 1 0 012 0v1h3a1 1 0 110 2H8a1 1 0 01-1-1V5H5v2a19 19 0 002.4 2.566 1 1 0 11-1.325 1.504A17.919 17.919 0 013.018 6.93l-.079-.074a19.088 19.088 0 013.617-1.73c0 .339.038.672.108.989A1 1 0 016 8v1a1 1 0 01-2 0V8H3v2a1 1 0 01-2 0V8a1 1 0 010-2h1V5a1 1 0 012 0v1h2a1 1 0 011 1v1h2a1 1 0 110 2H7a1 1 0 01-1-1V7h2a1 1 0 011-1h3a1 1 0 000-2H9V3a1 1 0 00-2 0v1H5a1 1 0 00-1 1v1H2a1 1 0 000 2h1v1a1 1 0 002 0V8h1a1 1 0 011-1h3a1 1 0 010 2H7v1a1 1 0 01-1 1H4a1 1 0 000 2h2a1 1 0 011 1v1a1 1 0 002 0v-1h2a1 1 0 011-1h1a1 1 0 110 2h-1a1 1 0 01-1-1v-1H8a1 1 0 01-1-1V9h2a1 1 0 110 2H7v1a1 1 0 01-1 1H4a1 1 0 000 2h2a1 1 0 011-1h3a1 1 0 110 2H7a1 1 0 01-1-1v-1H4a1 1 0 01-1-1v-2a1 1 0 012 0v2h2a1 1 0 011 1v1h2a1 1 0 01-1 1H7z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Languages
                                </label>
                                <div
                                    class="text-sm text-gray-900 bg-gray-50 p-3 rounded-xl border border-gray-100 group-hover:bg-indigo-50 group-hover:border-indigo-100 transition-colors">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        English
                                    </span>
                                </div>
                            </div>

                            <div class="group">
                                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Country
                                </label>
                                <div
                                    class="flex items-center text-sm text-gray-900 bg-gray-50 p-3 rounded-xl border border-gray-100 group-hover:bg-indigo-50 group-hover:border-indigo-100 transition-colors">
                                    <span class="fi fi-gb mr-2"></span>
                                    Indonesia
                                </div>
                            </div>

                            <div class="group">
                                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Joined Date
                                </label>
                                <div
                                    class="flex items-center text-sm text-gray-900 bg-gray-50 p-3 rounded-xl border border-gray-100 group-hover:bg-indigo-50 group-hover:border-indigo-100 transition-colors">
                                    <span>{{ $user->created_at->format('M d, Y') }}</span>
                                    <span
                                        class="ml-2 px-1.5 py-0.5 text-xs font-medium bg-amber-100 text-amber-800 rounded-full">
                                        {{ $user->created_at->diffForHumans(['parts' => 2, 'short' => true]) }}
                                    </span>
                                </div>
                            </div>

                            <div class="group" x-data="{ showAlert: false }">
                                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-gray-500" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Security
                                </label>

                                <div
                                    class="text-sm text-gray-900 bg-gray-50 p-3 rounded-xl border border-gray-100 group-hover:bg-indigo-50 group-hover:border-indigo-100 transition-colors">
                                    <button
                                        @click="
                                                    @if (auth()->user()->is_admin == 1) activeTab = 'security'
                                                    @else
                                                        showAlert = true
                                                        setTimeout(() => showAlert = false, 2500) @endif
                                                "
                                        :class="activeTab === 'security'
                                            ?
                                            'bg-blue-500 text-white px-3 py-2 rounded-lg flex items-center gap-2' :
                                            'text-gray-600 hover:text-blue-500 flex items-center gap-2'"
                                        class="text-sm font-medium transition">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 15v2m-6-6h12a2 2 0 012 2v7a2 2 0 01-2 2H6a2 2 0 01-2-2v-7a2 2 0 012-2zm10-4V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        Change Password
                                    </button>
                                </div>

                                <!-- Notifikasi Modern -->
                                <div x-show="showAlert" x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0 translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-300"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 translate-y-2"
                                    class="fixed bottom-5 right-5 bg-white border border-gray-200 shadow-lg rounded-xl px-4 py-3 flex items-center space-x-3 text-gray-800">
                                    <svg class="w-6 h-6 text-yellow-500 flex-shrink-0 mt-0.5" fill="none"
                                        stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                    </svg>
                                    <span class="text-sm font-medium">Hanya admin yang dapat merubah password!</span>
                                </div>
                            </div>


                        </div>
                    </div>
                    @if (auth()->user()->is_admin == 1)
                        <!-- Security -->
                        <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300"
                            x-show="activeTab === 'security'">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Change Password
                            </h2>

                            <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">Password Requirements</h3>
                                        <div class="mt-2 text-sm text-blue-700">
                                            <ul class="list-disc pl-5 space-y-1">
                                                <li>Minimum 8 characters long</li>
                                                <li>At least one uppercase letter</li>
                                                <li>At least one symbol (e.g. !@#$%^&*)</li>
                                                <li>At least one number</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form class="space-y-5">
                                <div class="group">
                                    <label for="new-password"
                                        class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="currentColor"
                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        New Password
                                    </label>
                                    <div class="relative">
                                        <input type="password" id="new-password" name="new-password"
                                            class="block w-full border border-gray-200 rounded-xl shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors group-hover:border-gray-300"
                                            placeholder="Enter your new password">
                                        <button type="button"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-indigo-600 toggle-password transition-colors">
                                            <svg class="h-5 w-5 eye-open" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg class="h-5 w-5 eye-closed hidden" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="mt-2 bg-gray-100 rounded-lg h-1.5">
                                        <div class="h-full bg-red-500 rounded-lg password-strength" style="width: 0%">
                                        </div>
                                    </div>
                                </div>

                                <div class="group">
                                    <label for="confirm-password"
                                        class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="currentColor"
                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                        Confirm New Password
                                    </label>
                                    <div class="relative">
                                        <input type="password" id="confirm-password" name="confirm-password"
                                            class="block w-full border border-gray-200 rounded-xl shadow-sm py-3 px-4 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-colors group-hover:border-gray-300"
                                            placeholder="Confirm your new password">
                                        <button type="button"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-indigo-600 toggle-password transition-colors">
                                            <svg class="h-5 w-5 eye-open" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg class="h-5 w-5 eye-closed hidden" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <button type="submit"
                                    class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 transform hover:-translate-y-0.5">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    Change Password
                                </button>
                            </form>
                        </div>
                    @endif

                   <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300"
     x-show="activeTab === 'history'">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
            <svg class="w-5 h-5 mr-2 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"
                 xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                      clip-rule="evenodd"></path>
            </svg>
            Hotel Booking Activity
        </h2>
        <div class="flex space-x-2">
            <button class="px-3 py-1.5 text-xs font-medium rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-colors">
                Filter
            </button>
            <button class="px-3 py-1.5 text-xs font-medium rounded-lg bg-indigo-100 text-indigo-700 hover:bg-indigo-200 transition-colors">
                Export
            </button>
        </div>
    </div>

    <div class="space-y-6 relative border-l border-gray-200 ml-3">
        <!-- Item 1 - Reservations -->
        <div class="ml-6 relative group">
            <div class="absolute -left-3.5 top-1.5 w-4 h-4 rounded-full bg-gradient-to-r from-purple-500 to-indigo-600 ring-2 ring-white shadow-md flex items-center justify-center">
                <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                </svg>
            </div>
            
            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 p-4 rounded-xl border border-purple-100 hover:border-purple-200 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h3 class="text-base font-semibold text-gray-900 group-hover:text-indigo-700 transition-colors">
                            5 New Reservations Confirmed
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Guests have booked deluxe and suite rooms</p>
                        
                        <div class="mt-3 flex flex-wrap gap-2">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                Deluxe Room
                            </span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                Suite Room
                            </span>
                        </div>
                        
                        <div class="mt-4 flex items-center justify-between">
                            <a href="#"
                               class="inline-flex items-center px-3 py-1.5 bg-white text-indigo-700 text-xs font-medium rounded-lg border border-indigo-200 hover:bg-indigo-50 transition-colors group/file">
                                <svg class="w-4 h-4 mr-1.5" xmlns="http://www.w3.org/2000/svg"
                                     fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M14,2H6A2,2,0,0,0,4,4V20a2,2,0,0,0,2,2H18a2,2,0,0,0,2-2V8ZM18,20H6V4h7V9h5Z" />
                                </svg>
                                booking_report.pdf
                                <svg class="w-3 h-3 ml-2 opacity-0 group-hover/file:opacity-100 transition-opacity"
                                     fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </a>
                            <span class="text-xs text-gray-500 bg-white px-2 py-1 rounded-full border border-gray-200">10 min ago</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Item 2 - Check-In -->
        <div class="ml-6 relative group">
            <div class="absolute -left-3.5 top-1.5 w-4 h-4 rounded-full bg-gradient-to-r from-green-500 to-emerald-600 ring-2 ring-white shadow-md flex items-center justify-center">
                <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                </svg>
            </div>
            
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-xl border border-green-100 hover:border-green-200 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h3 class="text-base font-semibold text-gray-900 group-hover:text-emerald-700 transition-colors">
                            Guest Check-In
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Mr. John Smith checked into Room 205</p>
                        
                        <div class="mt-3 flex items-center p-3 bg-white rounded-lg border border-emerald-100 hover:bg-emerald-50 transition-colors">
                            <div class="relative">
                                <img src="https://i.pravatar.cc/40?img=5"
                                     class="w-10 h-10 rounded-full shadow-sm" alt="avatar">
                                <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-emerald-500 border-2 border-white"></div>
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900">John Smith</p>
                                    <span class="text-xs text-emerald-600 bg-emerald-100 px-2 py-0.5 rounded-full">Checked-in</span>
                                </div>
                                <p class="text-xs text-gray-500">Room 205 • Deluxe Room</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 flex justify-end">
                    <span class="text-xs text-gray-500 bg-white px-2 py-1 rounded-full border border-gray-200">30 min ago</span>
                </div>
            </div>
        </div>

        <!-- Item 3 - Report -->
        <div class="ml-6 relative group">
            <div class="absolute -left-3.5 top-1.5 w-4 h-4 rounded-full bg-gradient-to-r from-sky-500 to-blue-600 ring-2 ring-white shadow-md flex items-center justify-center">
                <svg class="w-2 h-2 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                </svg>
            </div>
            
            <div class="bg-gradient-to-r from-sky-50 to-blue-50 p-4 rounded-xl border border-sky-100 hover:border-sky-200 transition-all duration-300">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h3 class="text-base font-semibold text-gray-900 group-hover:text-blue-700 transition-colors">
                            Monthly Report Generated
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Revenue report for August has been generated with detailed analytics</p>
                        
                        <div class="mt-3 flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex -space-x-2">
                                    <img src="https://i.pravatar.cc/40?img=7"
                                         class="w-9 h-9 rounded-full border-2 border-white shadow-sm">
                                    <img src="https://i.pravatar.cc/40?img=8"
                                         class="w-9 h-9 rounded-full border-2 border-white shadow-sm">
                                    <img src="https://i.pravatar.cc/40?img=9"
                                         class="w-9 h-9 rounded-full border-2 border-white shadow-sm">
                                </div>
                                <span class="ml-3 text-xs text-gray-600 bg-white px-2 py-1 rounded-full border border-gray-200">+2 Staff</span>
                            </div>
                            
                            <div class="flex space-x-2">
                                <button class="p-1.5 rounded-lg bg-white text-blue-600 hover:bg-blue-50 transition-colors border border-blue-200">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                    </svg>
                                </button>
                                <button class="p-1.5 rounded-lg bg-white text-blue-600 hover:bg-blue-50 transition-colors border border-blue-200">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 flex justify-end">
                    <span class="text-xs text-gray-500 bg-white px-2 py-1 rounded-full border border-gray-200">1 day ago</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- View All Button -->
    <div class="mt-6 flex justify-center">
        <button class="px-4 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors flex items-center">
            View All Activity
            <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>
</div>

                </div>
            </div>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', () => {
                const input = button.previousElementSibling;
                if (input.type === 'password') {
                    input.type = 'text';
                    button.innerHTML = '<i class="far fa-eye-slash"></i>';
                } else {
                    input.type = 'password';
                    button.innerHTML = '<i class="far fa-eye"></i>';
                }
            });
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




        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('input');
                    const eyeOpen = this.querySelector('.eye-open');
                    const eyeClosed = this.querySelector('.eye-closed');

                    if (input.type === 'password') {
                        input.type = 'text';
                        eyeOpen.classList.add('hidden');
                        eyeClosed.classList.remove('hidden');
                    } else {
                        input.type = 'password';
                        eyeOpen.classList.remove('hidden');
                        eyeClosed.classList.add('hidden');
                    }
                });
            });

            // Password strength indicator
            const passwordInput = document.getElementById('new-password');
            const strengthBar = document.querySelector('.password-strength');

            passwordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;

                if (password.length >= 8) strength += 25;
                if (/[A-Z]/.test(password)) strength += 25;
                if (/[0-9]/.test(password)) strength += 25;
                if (/[^A-Za-z0-9]/.test(password)) strength += 25;

                strengthBar.style.width = strength + '%';

                // Change color based on strength
                if (strength < 50) {
                    strengthBar.className = 'h-full bg-red-500 rounded-lg password-strength';
                } else if (strength < 100) {
                    strengthBar.className = 'h-full bg-yellow-500 rounded-lg password-strength';
                } else {
                    strengthBar.className = 'h-full bg-green-500 rounded-lg password-strength';
                }
            });
        });
    </script>
</x-app-layout>
