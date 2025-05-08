<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-8">
            <div>
                <h1
                    class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                    Purchase Request Management
                </h1>
                <p class="mt-1 text-gray-500 dark:text-gray-400">
                    Streamline your procurement process with our Purchase Request System.
                </p>
            </div>
        </div>

        <!-- First Container: Input Fields -->
        <div id="containerAccount" class="bg-white shadow-md rounded-lg overflow-hidden mt-8">
            <div class="flex justify-between items-center px-6 py-4 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Purchase Request - Input</h2>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- User (Applicant) - Full width -->
                    <div class="relative col-span-3">
                        <input name="applicant" id="applicant" value="{{ Auth::user()->name }}" readonly
                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-white rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " />
                        <label for="applicant"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                            User (Applicant)<span class="text-red-500">*</span>
                        </label>
                    </div>
                    <!-- Company, RAB Type, RAB Period -->
                    <div class="relative">
                        <select name="company" id="company" required
                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                            <option value="" disabled selected>Select Company...</option>
                            @foreach ($companies as $company)
                                <option value="{{ $company->id_company }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                        <label for="company"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                            Company<span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="relative">
                        <select name="rab-type" id="rab-type" required
                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                            <option value="" disabled selected>Select Type...</option>
                            <option value="Project Service">Project Service</option>
                            <option value="Non Project Service">Non Project Service</option>
                            <option value="Emergency Fund (Inventory)">Emergency Fund (Inventory)</option>
                        </select>
                        <label for="rab-type"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                            RAB Type<span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="relative">
                        <input type="month" name="rab-period" id="rab-period" required
                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " />
                        <label for="rab-period"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                            RAB Period<span class="text-red-500">*</span>
                        </label>
                    </div>
                    <!-- PR Date, Payment Source, Delivery Date -->
                    <div class="relative">
                        <input type="date" name="pr-date" id="pr-date" required
                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " />
                        <label for="pr-date"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                            PR Date<span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="relative">
                        <input type="date" name="delivery-date" id="delivery-date" required
                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " />
                        <label for="delivery-date"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                            Delivery Date<span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="relative">
                        <input name="payment-source" id="payment-source" value="HO" readonly
                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" " />
                        <label for="payment-source"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                            Payment Source<span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="flex gap-6 col-span-3">
                        <!-- Currency -->
                        <div class="w-1/2 relative">
                            <input name="currency" id="currency" value="IDR" readonly
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " />
                            <label for="currency"
                                class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                Currency<span class="text-red-500">*</span>
                            </label>
                        </div>

                        <!-- Request Level -->
                        <div class="w-1/2 relative">
                            <select name="request-level" id="request-level" required
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                                <option value="" disabled selected>Select Level...</option>
                                <option value="Normal">Normal</option>
                                <option value="Important">Important</option>
                                <option value="Urgent">Urgent</option>
                            </select>
                            <label for="request-level"
                                class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                Request Level<span class="text-red-500">*</span>
                            </label>
                        </div>
                    </div>


                    <div class="relative col-span-3">
                        <textarea name="notes" id="notes" rows="3"
                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                            placeholder=" "></textarea>
                        <label for="notes"
                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                            Notes
                        </label>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                    <div class="flex items-center">
                        <div class="relative flex-grow mr-4">
                            <input name="delivery-to" id="delivery-to" required
                                class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " readonly />
                            <label for="delivery-to"
                                class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                Delivery To<span class="text-red-500">*</span>
                            </label>
                        </div>
                        <div x-data="modal()" class="relative group">
                            <button
                                class="flex items-center px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm cursor-pointer"
                                type="button" @click.prevent="modalOpenDetail = true;"
                                aria-controls="feedback-modal1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 2C8.686 2 6 4.686 6 8c0 5.25 6 13 6 13s6-7.75 6-13c0-3.314-2.686-6-6-6z" />
                                    <circle cx="12" cy="8" r="2" fill="currentColor" />
                                </svg>
                                Select Location
                            </button>

                            <!-- Modern Modal -->
                            <div class="fixed inset-0 z-50 overflow-y-auto" x-show="modalOpenDetail" x-cloak
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                                <!-- Modal backdrop with blur effect -->
                                <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" aria-hidden="true"
                                    @click="modalOpenDetail = false"></div>

                                <!-- Modal container -->
                                <div class="flex min-h-screen items-center justify-center p-4">
                                    <!-- Modal dialog -->
                                    <div class="relative w-full max-w-5xl rounded-2xl bg-white shadow-2xl transition-all"
                                        @click.stop x-show="modalOpenDetail"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100 scale-100"
                                        x-transition:leave-end="opacity-0 scale-95">

                                        <!-- Modal header -->
                                        <div class="flex items-center justify-between p-6 border-b border-gray-200">
                                            <h3 class="text-xl font-semibold text-gray-900">Select Delivery Address
                                            </h3>
                                            <button type="button"
                                                class="text-gray-400 hover:text-gray-500 rounded-full p-1"
                                                @click="modalOpenDetail = false">
                                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>

                                        <!-- Modal content -->
                                        <div class="p-6 overflow-y-auto max-h-[70vh]">
                                            <div class="w-full">
                                                <!-- Search input -->
                                                <div class="mb-4">
                                                    {{-- <div class="relative max-w-xs">
                                                        <label for="table-search" class="sr-only">Search</label>
                                                        <div
                                                            class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                                            <svg class="w-4 h-4 text-gray-500" aria-hidden="true"
                                                                xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 20 20">
                                                                <path stroke="currentColor" stroke-linecap="round"
                                                                    stroke-linejoin="round" stroke-width="2"
                                                                    d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                                            </svg>
                                                        </div>
                                                        <input type="text" id="table-search"
                                                            class="block w-full p-2 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                                                            placeholder="Search addresses..." x-model="search"
                                                            @input="searchData()">
                                                    </div> --}}
                                                </div>

                                                <!-- Table -->
                                                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                                                    <table class="w-full text-sm text-left text-gray-500">
                                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                                            <tr>
                                                                <th scope="col" class="px-6 py-3 cursor-pointer"
                                                                    @click="sort('company')">
                                                                    <div class="flex items-center">
                                                                        Company
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="w-3 h-3 ml-1" aria-hidden="true"
                                                                            fill="currentColor" viewBox="0 0 320 512">
                                                                            <path
                                                                                d="M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z" />
                                                                        </svg>
                                                                    </div>
                                                                </th>
                                                                <th scope="col" class="px-6 py-3 cursor-pointer"
                                                                    @click="sort('address')">
                                                                    <div class="flex items-center">
                                                                        Address
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="w-3 h-3 ml-1" aria-hidden="true"
                                                                            fill="currentColor" viewBox="0 0 320 512">
                                                                            <path
                                                                                d="M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z" />
                                                                        </svg>
                                                                    </div>
                                                                </th>
                                                                <th scope="col" class="px-6 py-3 cursor-pointer"
                                                                    @click="sort('city')">
                                                                    <div class="flex items-center">
                                                                        City
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="w-3 h-3 ml-1" aria-hidden="true"
                                                                            fill="currentColor" viewBox="0 0 320 512">
                                                                            <path
                                                                                d="M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z" />
                                                                        </svg>
                                                                    </div>
                                                                </th>
                                                                <th scope="col" class="px-6 py-3 cursor-pointer"
                                                                    @click="sort('province')">
                                                                    <div class="flex items-center">
                                                                        Province
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="w-3 h-3 ml-1" aria-hidden="true"
                                                                            fill="currentColor" viewBox="0 0 320 512">
                                                                            <path
                                                                                d="M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z" />
                                                                        </svg>
                                                                    </div>
                                                                </th>
                                                                <th scope="col" class="px-6 py-3 cursor-pointer"
                                                                    @click="sort('country')">
                                                                    <div class="flex items-center">
                                                                        Country
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="w-3 h-3 ml-1" aria-hidden="true"
                                                                            fill="currentColor" viewBox="0 0 320 512">
                                                                            <path
                                                                                d="M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z" />
                                                                        </svg>
                                                                    </div>
                                                                </th>
                                                                <th scope="col" class="px-6 py-3 cursor-pointer"
                                                                    @click="sort('postalCode')">
                                                                    <div class="flex items-center">
                                                                        Postal Code
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="w-3 h-3 ml-1" aria-hidden="true"
                                                                            fill="currentColor" viewBox="0 0 320 512">
                                                                            <path
                                                                                d="M27.66 224h264.7c24.6 0 36.89-29.78 19.54-47.12l-132.3-136.8c-5.406-5.406-12.47-8.107-19.53-8.107c-7.055 0-14.09 2.701-19.45 8.107L8.119 176.9C-9.229 194.2 3.055 224 27.66 224zM292.3 288H27.66c-24.6 0-36.89 29.77-19.54 47.12l132.5 136.8C145.9 477.3 152.1 480 160 480c7.053 0 14.12-2.703 19.53-8.109l132.3-136.8C329.2 317.8 316.9 288 292.3 288z" />
                                                                        </svg>
                                                                    </div>
                                                                </th>
                                                                <th scope="col" class="px-6 py-3">
                                                                    Action
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @forelse ($companies as $company)
                                                                <tr class="bg-white border-b hover:bg-gray-50 cursor-pointer"
                                                                    onclick="selectCompany('{{ $company->id_company }}', '{{ $company->name }}', '{{ $company->address }}', '{{ $company->city }}', '{{ $company->npwp_city }}', '{{ $company->country }}', '{{ $company->zip_code }}')">
                                                                    <td class="px-6 py-4">{{ $company->name }}
                                                                    </td>
                                                                    <td class="px-6 py-4">{{ $company->address }}
                                                                    </td>
                                                                    <td class="px-6 py-4">{{ $company->city }}
                                                                    </td>
                                                                    <td class="px-6 py-4">
                                                                        {{ $company->npwp_city }}
                                                                    </td>
                                                                    <td class="px-6 py-4">{{ $company->country }}
                                                                    </td>
                                                                    <td class="px-6 py-4">{{ $company->zip_code }}
                                                                    </td>
                                                                    <td class="px-6 py-4">
                                                                        <div class="flex space-x-2">
                                                                            <button type="button"
                                                                                onclick="event.stopPropagation(); selectCompany('{{ $company->id_company }}', '{{ $company->name }}', '{{ $company->address }}', '{{ $company->city }}', '{{ $company->npwp_city }}', '{{ $company->country }}', '{{ $company->zip_code }}')"
                                                                                class="font-medium text-blue-600 hover:underline"
                                                                                @click="modalOpenDetail = false">
                                                                                Select
                                                                            </button>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="7"
                                                                        class="px-6 py-4 text-center text-gray-500">
                                                                        No companies found
                                                                    </td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <!-- Pagination -->
                                                <div class="flex items-center justify-between pt-4">
                                                    @if ($companies->hasPages())
                                                        <div class="flex-1 flex items-center justify-between">
                                                            <div>
                                                                <p class="text-sm text-gray-700">
                                                                    Showing
                                                                    <span
                                                                        class="font-medium">{{ $companies->firstItem() }}</span>
                                                                    to
                                                                    <span
                                                                        class="font-medium">{{ $companies->lastItem() }}</span>
                                                                    of
                                                                    <span
                                                                        class="font-medium">{{ $companies->total() }}</span>
                                                                    results
                                                                </p>
                                                            </div>
                                                            <div>
                                                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                                                    aria-label="Pagination">
                                                                    <!-- Previous Page Link -->
                                                                    @if ($companies->onFirstPage())
                                                                        <span
                                                                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-300 cursor-not-allowed">
                                                                            <span class="sr-only">Previous</span>
                                                                            <svg class="h-5 w-5"
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                viewBox="0 0 20 20"
                                                                                fill="currentColor"
                                                                                aria-hidden="true">
                                                                                <path fill-rule="evenodd"
                                                                                    d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                                                    clip-rule="evenodd" />
                                                                            </svg>
                                                                        </span>
                                                                    @else
                                                                        <a href="{{ $companies->previousPageUrl() }}"
                                                                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                                            <span class="sr-only">Previous</span>
                                                                            <svg class="h-5 w-5"
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                viewBox="0 0 20 20"
                                                                                fill="currentColor"
                                                                                aria-hidden="true">
                                                                                <path fill-rule="evenodd"
                                                                                    d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                                                    clip-rule="evenodd" />
                                                                            </svg>
                                                                        </a>
                                                                    @endif

                                                                    <!-- Pagination Elements -->
                                                                    @foreach ($companies->getUrlRange(1, $companies->lastPage()) as $page => $url)
                                                                        @if ($page == $companies->currentPage())
                                                                            <span aria-current="page"
                                                                                class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                                                                {{ $page }}
                                                                            </span>
                                                                        @else
                                                                            <a href="{{ $url }}"
                                                                                class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                                                                {{ $page }}
                                                                            </a>
                                                                        @endif
                                                                    @endforeach

                                                                    <!-- Next Page Link -->
                                                                    @if ($companies->hasMorePages())
                                                                        <a href="{{ $companies->nextPageUrl() }}"
                                                                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                                            <span class="sr-only">Next</span>
                                                                            <svg class="h-5 w-5"
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                viewBox="0 0 20 20"
                                                                                fill="currentColor"
                                                                                aria-hidden="true">
                                                                                <path fill-rule="evenodd"
                                                                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                                                    clip-rule="evenodd" />
                                                                            </svg>
                                                                        </a>
                                                                    @else
                                                                        <span
                                                                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-300 cursor-not-allowed">
                                                                            <span class="sr-only">Next</span>
                                                                            <svg class="h-5 w-5"
                                                                                xmlns="http://www.w3.org/2000/svg"
                                                                                viewBox="0 0 20 20"
                                                                                fill="currentColor"
                                                                                aria-hidden="true">
                                                                                <path fill-rule="evenodd"
                                                                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                                                    clip-rule="evenodd" />
                                                                            </svg>
                                                                        </span>
                                                                    @endif
                                                                </nav>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <div x-data="modal()">
                            <!-- Add this new modal for the item selection table -->
                            <div class="fixed inset-0 z-[60] overflow-hidden flex items-center my-4 justify-center px-4 sm:px-6"
                                role="dialog" aria-modal="true" x-show="itemModalOpen"
                                x-transition:enter="transition ease-in-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-4"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in-out duration-200"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-4" x-cloak>
                                <div class="bg-white rounded shadow-lg overflow-auto w-3/4 max-h-full"
                                    @click.outside="itemModalOpen = true"
                                    @keydown.escape.window="itemModalOpen = false">
                                    <!-- Modal header -->
                                    <div class="px-5 py-3 border-b border-slate-200">
                                        <div class="flex justify-between items-center">
                                            <div class="font-semibold text-slate-800">Select Inventory Item</div>
                                            <button type="button" class="text-slate-400 hover:text-slate-500"
                                                @click="itemModalOpen = false">
                                                <div class="sr-only">Close</div>
                                                <svg class="w-4 h-4 fill-current">
                                                    <path
                                                        d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Modal content -->
                                    <div class="px-5 py-4">
                                        <div class="mb-4">
                                            <input type="text" placeholder="Search items..."
                                                class="border border-gray-300 rounded-md px-3 py-2 w-full"
                                                x-model="itemSearch" @input="filterItems">
                                        </div>
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Assets Code
                                                        </th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Inventory Name
                                                        </th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Unit
                                                        </th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Price
                                                        </th>
                                                        <th scope="col"
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                            Select
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    @foreach ($inventoryItems as $item)
                                                        <tr class="hover:bg-gray-50 cursor-pointer">
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                {{ $item->assets_code }}</td>
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                {{ $item->inventory_name }}</td>
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                {{ $item->unit }}</td>
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                {{ number_format($item->price, 0, ',', '.') }}</td>
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                <button type="button"
                                                                    @click="selectItem({{ json_encode($item) }})"
                                                                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow-sm transition">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="h-4 w-4 mr-2" fill="none"
                                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M5 13l4 4L19 7" />
                                                                    </svg>
                                                                    Select
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                            <!-- Pagination -->
                                            <div class="mt-4">
                                                {{ $inventoryItems->links() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Original modal content (unchanged) -->
                            <button
                                class="px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 text-sm flex items-center cursor-pointer"
                                type="button" @click.prevent="modalOpenDetail = true;"
                                aria-controls="feedback-modal1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                Add Purchase Detail
                            </button>

                            <!-- Modal backdrop -->
                            <div class="fixed inset-0 backdrop-blur bg-opacity-30 z-50 transition-opacity"
                                x-show="modalOpenDetail" x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                x-transition:leave="transition ease-out duration-100"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                aria-hidden="true" x-cloak></div>

                            <!-- Modal dialog -->
                            <div id="feedback-modal1"
                                class="fixed inset-0 z-50 overflow-hidden flex items-center my-4 justify-center px-4 sm:px-6"
                                role="dialog" aria-modal="true" x-show="modalOpenDetail"
                                x-transition:enter="transition ease-in-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-4"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in-out duration-200"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-4" x-cloak>
                                <div class="bg-white rounded shadow-lg overflow-auto w-1/2 max-h-full"
                                    @click.outside="modalOpenDetail = true"
                                    @keydown.escape.window="modalOpenDetail = false">
                                    <!-- Modal header -->
                                    <div class="px-5 py-3 border-b border-slate-200" id="modalAddLpjDetail">
                                        <div class="flex justify-between items-center">
                                            <div class="font-semibold text-slate-800">Add New Purchase Item
                                            </div>
                                            <button type="button" class="text-slate-400 hover:text-slate-500"
                                                @click="modalOpenDetail = false">
                                                <div class="sr-only">Close</div>
                                                <svg class="w-4 h-4 fill-current">
                                                    <path
                                                        d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Modal content -->
                                    <div class="modal-content text-xs px-5 py-4">
                                        <form id="documentForm">
                                            @csrf
                                            <div class="space-y-4">
                                                <!-- Assets Code -->
                                                <div>
                                                    <label for="assets_code"
                                                        class="block text-sm font-medium text-gray-700">
                                                        Assets Code<span class="text-red-500">*</span>
                                                    </label>
                                                    <div class="mt-1 flex">
                                                        <input type="text" name="assets_code" id="assets_code"
                                                            required x-model="selectedItem.assets_code"
                                                            class="flex-1 border border-gray-300 rounded-l-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                        <button type="button"
                                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-r-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                                            @click="itemModalOpen = true">
                                                            Add Item
                                                        </button>
                                                    </div>
                                                </div>
                                                <!-- Inventory Name -->
                                                <div>
                                                    <label for="inventory_name"
                                                        class="block text-sm font-medium text-gray-700">Inventory
                                                        Name<span class="text-red-500">*</span></label>
                                                    <input type="text" name="inventory_name" id="inventory_name"
                                                        required x-model="selectedItem.inventory_name"
                                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                </div>

                                                <!-- QTY, UNIT, @Price in one row -->
                                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                                    <!-- QTY -->
                                                    <div>
                                                        <label for="qty"
                                                            class="block text-sm font-medium text-gray-700">QTY<span
                                                                class="text-red-500">*</span></label>
                                                        <input type="number" name="qty" id="qty"
                                                            min="1" step="1" required
                                                            x-model="selectedItem.qty"
                                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                            oninput="calculateTotal()">
                                                    </div>

                                                    <!-- UNIT -->
                                                    <div>
                                                        <label for="unit"
                                                            class="block text-sm font-medium text-gray-700">UNIT<span
                                                                class="text-red-500">*</span></label>
                                                        <select name="unit" id="unit" required
                                                            x-model="selectedItem.unit"
                                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                            <option value="" disabled selected>Select Unit
                                                            </option>
                                                            <option value="pcs">pcs</option>
                                                            <option value="unit">unit</option>
                                                            <option value="set">set</option>
                                                            <option value="box">box</option>
                                                            <option value="pack">pack</option>
                                                        </select>
                                                    </div>

                                                    <!-- @Price -->
                                                    <div>
                                                        <label for="price"
                                                            class="block text-sm font-medium text-gray-700">@Price<span
                                                                class="text-red-500">*</span></label>
                                                        <input type="text" name="price" id="price" required
                                                            x-model="selectedItem.price"
                                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                            onkeyup="formatCurrency(this); calculateTotal()">
                                                    </div>
                                                </div>

                                                <!-- Total -->
                                                <div>
                                                    <label for="total"
                                                        class="block text-sm font-medium text-gray-700">Total</label>
                                                    <input type="text" name="total" id="total" readonly
                                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 bg-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                </div>

                                                <!-- Remarks (Textarea) -->
                                                <div>
                                                    <label for="remarks"
                                                        class="block text-sm font-medium text-gray-700">Remarks</label>
                                                    <textarea name="remarks" id="remarks" rows="3" x-model="selectedItem.remarks"
                                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                                                </div>
                                            </div>

                                            <!-- Form Actions -->
                                            <div class="mt-6 flex justify-end space-x-3">
                                                <button type="button" @click="modalOpenDetail = false"
                                                    class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    Cancel
                                                </button>
                                                <button type="submit" @click="modalOpenDetail = false"
                                                    class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    Add Purchase Detail
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

        <!-- Second Container: Imported Documents Table -->
        <div id="containerDocuments" class="bg-white shadow-md rounded-lg overflow-hidden mt-8">
            <div class="flex justify-between items-center px-6 py-4 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Purchase Request - Detail</h2>
            </div>

            <div class="overflow-x-auto p-4">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Assets Code</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Inventory Name</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                QTY</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                UNIT</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                @Price</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Remarks</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                Grand
                                Total</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">0</td>
                            <td colspan="1"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Submit Purchase Request
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('modal', () => ({
                modalOpenDetail: false,
                itemModalOpen: false,
                selectedItem: {
                    assets_code: '',
                    inventory_name: '',
                    unit: '',
                    price: '',
                    qty: 1,
                    remarks: ''
                },
                itemSearch: '',

                selectItem(item) {
                    this.selectedItem = {
                        ...item,
                        qty: 1, // Reset quantity when selecting new item
                        price: item.price.toString().replace(/\B(?=(\d{3})+(?!\d))/g,
                            "."), // Format price
                        remarks: ''
                    };
                    this.itemModalOpen = false;
                    this.calculateTotal();
                },

                calculateTotal() {
                    const qty = parseInt(this.selectedItem.qty) || 0;
                    const price = parseFloat(this.selectedItem.price.replace(/\./g, '')) || 0;
                    const total = qty * price;
                    document.getElementById('total').value = total.toLocaleString('id-ID');
                }
            }));
        });

        // Handle form submission
        document.getElementById('documentForm').addEventListener('submit', function(e) {
            e.preventDefault();

            // Get form values
            const formData = {
                assets_code: document.getElementById('assets_code').value,
                inventory_name: document.getElementById('inventory_name').value,
                qty: document.getElementById('qty').value,
                unit: document.getElementById('unit').value,
                price: document.getElementById('price').value,
                total: document.getElementById('total').value,
                remarks: document.getElementById('remarks').value
            };

            // Add row to table
            addTableRow(formData);

            // Update grand total
            updateGrandTotal();

            // Close modal and reset form
            this.reset();
            document.getElementById('total').value = '';
        });

        function addTableRow(data) {
            const tbody = document.querySelector('#containerDocuments tbody');
            const rowCount = tbody.querySelectorAll('tr').length + 1;

            const newRow = document.createElement('tr');
            newRow.className = 'hover:bg-gray-50';
            newRow.innerHTML = `
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${rowCount}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${data.assets_code}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${data.inventory_name}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${data.qty}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${data.unit}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${data.price}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${data.total}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${data.remarks || '-'}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <button onclick="deleteRow(this)" class="text-red-600 hover:text-red-900">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                    </td>
                                `;

            tbody.appendChild(newRow);
        }

        function deleteRow(button) {
            const row = button.closest('tr');
            row.remove();
            updateRowNumbers();
            updateGrandTotal();
        }

        function updateRowNumbers() {
            const rows = document.querySelectorAll('#containerDocuments tbody tr');
            rows.forEach((row, index) => {
                row.querySelector('td:first-child').textContent = index + 1;
            });
        }

        function updateGrandTotal() {
            const totals = document.querySelectorAll('#containerDocuments tbody tr td:nth-child(7)');
            let grandTotal = 0;

            totals.forEach(td => {
                const value = parseInt(td.textContent.replace(/\./g, '')) || 0;
                grandTotal += value;
            });

            document.querySelector('#containerDocuments tfoot td:nth-child(2)').textContent =
                grandTotal.toLocaleString('id-ID');
        }

        // Currency formatting function
        function formatCurrency(input) {
            input.value = input.value.replace(/[^0-9]/g, '')
                .replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function formatCurrency(input) {
            // Remove non-numeric characters
            let value = input.value.replace(/[^0-9]/g, '');

            // Format with thousand separators
            if (value.length > 0) {
                value = parseInt(value).toLocaleString('id-ID');
            }

            input.value = value;
        }

        function calculateTotal() {
            const qty = parseInt(document.getElementById('qty').value) || 0;
            const price = parseInt(document.getElementById('price').value.replace(/[^0-9]/g, '')) || 0;
            const total = qty * price;

            document.getElementById('total').value = total.toLocaleString('id-ID');
        }

        // Set today's date as default for PR Date and Delivery Date
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();

            // Format YYYY-MM-DD
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0'); // bulan 0-indexed
            const day = String(today.getDate()).padStart(2, '0');

            const formattedDate = `${year}-${month}-${day}`;
            const formattedMonth = `${year}-${month}`; // untuk rab-period

            document.getElementById('pr-date').value = formattedDate;

            const deliveryDate = document.getElementById('delivery-date');
            if (deliveryDate) deliveryDate.value = formattedDate;

            const rabPeriod = document.getElementById('rab-period');
            if (rabPeriod) rabPeriod.value = formattedMonth;
        });

        function selectCompany(id, name, address, city, province, country, zip) {
            const fullAddress = `${name}, ${address}, ${city}, ${province} ${zip}, ${country}`;
            document.getElementById('delivery-to').value = fullAddress;
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Handle the "Submit Purchase Request" button click
            document.querySelector('#containerDocuments tfoot button').addEventListener('click', function(e) {
                e.preventDefault();
                submitPurchaseRequest();
            });

            // Set today's date as default for PR Date and Delivery Date
            const today = new Date();
            const year = today.getFullYear();
            const month = String(today.getMonth() + 1).padStart(2, '0');
            const day = String(today.getDate()).padStart(2, '0');
            const formattedDate = `${year}-${month}-${day}`;
            const formattedMonth = `${year}-${month}`;

            document.getElementById('pr-date').value = formattedDate;
            document.getElementById('delivery-date').value = formattedDate;
            document.getElementById('rab-period').value = formattedMonth;
        });

        function submitPurchaseRequest() {
            // Validate form
            if (!validateForm()) {
                return;
            }

            // Prepare main purchase request data
            const prData = {
                // From container 1
                pr_title: document.getElementById('rab-type').options[document.getElementById('rab-type').selectedIndex]
                    .text + ' - ' +
                    document.getElementById('rab-period').value,
                pr_type: document.getElementById('rab-type').value,
                pr_date: document.getElementById('pr-date').value,
                rab_date: document.getElementById('rab-period').value,
                applicant: document.getElementById('applicant').value,
                company_id: document.getElementById('company').value,
                currency: document.getElementById('currency').value,
                payment_by: document.getElementById('payment-source').value,
                reqlevel: document.getElementById('request-level').value,
                delivery_date: document.getElementById('delivery-date').value,
                note: document.getElementById('notes').value,
                delivery_address: document.getElementById('delivery-to').value,

                // Default values for required fields
                approvalstat: 'Pending',
                subtotal: 0,
                discount: 0,
                total: 0,
                ppn: 0,
                gtotal: 0,
                balance: 0,
                print_status: '0',
                price_updated: '0',

                // Details from container 2
                details: []
            };

            // Calculate totals from details
            let subtotal = 0;
            const rows = document.querySelectorAll('#containerDocuments tbody tr');

            rows.forEach(row => {
                const detail = {
                    idassets: row.cells[1].textContent,
                    name_detail: row.cells[2].textContent,
                    qty: parseFloat(row.cells[3].textContent),
                    unit: row.cells[4].textContent,
                    price: parseFloat(row.cells[5].textContent.replace(/\./g, '')),
                    total: parseFloat(row.cells[6].textContent.replace(/\./g, '')),
                    remarks: row.cells[7].textContent === '-' ? '' : row.cells[7].textContent,
                    currency: prData.currency,
                    qtyBalance: parseFloat(row.cells[3].textContent),
                    balance: parseFloat(row.cells[6].textContent.replace(/\./g, ''))
                };

                prData.details.push(detail);
                subtotal += detail.total;
            });

            // Update totals in main data
            prData.subtotal = subtotal;
            prData.total = subtotal;
            prData.gtotal = subtotal;

            // Generate unique PR ID
            const timestamp = new Date().getTime();
            prData.idreqform = 'PR-' + timestamp;

            // Send data to server
            fetch('/purchasing/purchase-requests/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(prData)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Show Toastify notification
                        Toastify({
                            text: "Purchase request submitted successfully!",
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "#4CAF50",
                            stopOnFocus: true,
                        }).showToast();

                        // Redirect to new PR page after 1.5 seconds
                        setTimeout(() => {
                            window.location.href = '/purchasing/purchase-request/new';
                        }, 1500);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error submitting purchase request: ' + error.message);
                });
        }

        function validateForm() {
            // Validate required fields in container 1
            const requiredFields = [
                'company', 'rab-type', 'rab-period', 'pr-date',
                'delivery-date', 'payment-source', 'request-level', 'delivery-to'
            ];

            for (const fieldId of requiredFields) {
                const field = document.getElementById(fieldId);
                if (!field.value) {
                    alert(`Please fill in the ${fieldId.replace('-', ' ')} field`);
                    field.focus();
                    return false;
                }
            }

            // Validate at least one item in container 2
            const itemRows = document.querySelectorAll('#containerDocuments tbody tr');
            if (itemRows.length === 0) {
                alert('Please add at least one item to the purchase request');
                return false;
            }

            return true;
        }
    </script>
</x-app-layout>
