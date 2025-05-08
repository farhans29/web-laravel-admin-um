<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-semibold">Vendor Management</h2>
        </div>

        <div id="containerAccount" class="bg-white shadow-md rounded-lg overflow-hidden mt-8">
            <div class="flex justify-between items-center px-6 py-4 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Vendor List</h2>
                <div class="relative">
                    <form method="GET" action="{{ route('indexEdit.vendor') }}">
                        <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                            placeholder="Search..."
                            class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 w-48">
                        <div class="absolute left-3 top-2.5">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Vendor's Type</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Vendor's Name</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Address</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                City</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Country</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Contact Phone</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                TOP (days)</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                POS Code</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                NPWP ID</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendors as $vendor)
                            <tr class="{{ $loop->even ? 'bg-white' : 'bg-gray-100' }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $vendor->vendor_type }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 break-words">{{ $vendor->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900 break-words">{{ $vendor->address }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900">
                                    {{ $vendor->city }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900">
                                    {{ $vendor->country }}
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900">
                                    {{ $vendor->phone }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900">
                                    {{ $vendor->termin }}</td>
                                <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900">
                                    {{ $vendor->zip_code }}
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900">
                                    {{ $vendor->npwp_id }}
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                {{ $vendor->status === 'Active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $vendor->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center whitespace-nowrap text-sm text-gray-900">
                                    <div x-data="modal()">
                                        <button
                                            class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded cursor-pointer"
                                            type="button"
                                            @click.prevent="modalOpenDetail = true; loadVendorData({{ $vendor->id }})"
                                            aria-controls="feedback-modal1" data-vendor-id="{{ $vendor->id }}">
                                            Edit
                                        </button>

                                        <!-- Modal backdrop -->
                                        <div class="fixed inset-0 backdrop-blur bg-opacity-30 z-50 transition-opacity"
                                            x-show="modalOpenDetail"
                                            x-transition:enter="transition ease-out duration-200"
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
                                            <div class="bg-white rounded shadow-lg overflow-auto w-3/4 max-h-full"
                                                @click.outside="modalOpenDetail = false"
                                                @keydown.escape.window="modalOpenDetail = false">
                                                <!-- Modal header -->
                                                <div class="px-5 py-3 border-b border-slate-200" id="modalAddLpjDetail">
                                                    <div class="flex justify-between items-center">
                                                        <div class="font-semibold text-slate-800">Edit Vendor</div>
                                                        <button type="button"
                                                            class="text-slate-400 hover:text-slate-500"
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
                                                <div class="modal-content text-left text-xs px-5 py-4">
                                                    <form id="documentForm" method="POST"
                                                        action="{{ route('vendors.update', $vendor->idsupplier) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="grid md:grid-cols-1 md:gap-6">
                                                            <!-- Vendor Type -->
                                                            <div class="relative z-0 w-full mb-5 group">
                                                                <select name="vendor_type" id="vendor_type"
                                                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                    required>
                                                                    <option value="Local"
                                                                        {{ $vendor->vendor_type == 'Local' ? 'selected' : '' }}>
                                                                        Local Vendor</option>
                                                                    <option value="International"
                                                                        {{ $vendor->vendor_type == 'International' ? 'selected' : '' }}>
                                                                        International Vendor</option>
                                                                </select>
                                                                <label for="vendor_type"
                                                                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                                    Vendor's Type<span class="text-red-500">*</span>
                                                                </label>
                                                            </div>

                                                            <div class="grid md:grid-cols-2 md:gap-6">
                                                                <!-- Vendor's Department -->
                                                                <div class="relative z-0 w-full mb-5 group">
                                                                    <select name="vendor_department"
                                                                        id="vendor_department"
                                                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                        required>
                                                                        <option value="" disabled>Select Vendor's
                                                                            Department</option>
                                                                        <option value="IT"
                                                                            {{ $vendor->vendor_department == 'IT' ? 'selected' : '' }}>
                                                                            IT Department</option>
                                                                        <option value="Finance"
                                                                            {{ $vendor->vendor_department == 'Finance' ? 'selected' : '' }}>
                                                                            Finance Department</option>
                                                                        <option value="Procurement"
                                                                            {{ $vendor->vendor_department == 'Procurement' ? 'selected' : '' }}>
                                                                            Procurement Department</option>
                                                                        <option value="HR"
                                                                            {{ $vendor->vendor_department == 'HR' ? 'selected' : '' }}>
                                                                            Human Resources</option>
                                                                        <option value="Operations"
                                                                            {{ $vendor->vendor_department == 'Operations' ? 'selected' : '' }}>
                                                                            Operations</option>
                                                                    </select>
                                                                    <label for="vendor_department"
                                                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                                        Vendor's Department<span
                                                                            class="text-red-500">*</span>
                                                                    </label>
                                                                </div>

                                                                <!-- Vendor's Name -->
                                                                <div class="relative z-0 w-full mb-5 group">
                                                                    <input name="name" id="name"
                                                                        value="{{ $vendor->name }}"
                                                                        autocomplete="off"
                                                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                        placeholder=" " required />
                                                                    <label for="name"
                                                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                                        Vendor's Name<span
                                                                            class="text-red-500">*</span>
                                                                    </label>
                                                                </div>
                                                            </div>

                                                            <!-- Address -->
                                                            <div class="relative z-0 w-full mb-5 group">
                                                                <textarea name="address" id="address" rows="3"
                                                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                    placeholder=" " required>{{ $vendor->address }}</textarea>
                                                                <label for="address"
                                                                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                                    Address<span class="text-red-500">*</span>
                                                                </label>
                                                            </div>

                                                            <div class="grid md:grid-cols-3 md:gap-6">
                                                                <!-- Country -->
                                                                <div class="relative z-0 w-full mb-5 group">
                                                                    <select name="country" id="country"
                                                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                        required>
                                                                        <option value="Indonesia" selected>Indonesia
                                                                        </option>
                                                                    </select>
                                                                    <label for="country"
                                                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                                        Country<span class="text-red-500">*</span>
                                                                    </label>
                                                                </div>

                                                                <!-- City -->
                                                                <div class="relative z-0 w-full mb-5 group">
                                                                    <select name="city" id="city"
                                                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                        required>
                                                                        <option value="" disabled>Select City
                                                                        </option>
                                                                        <option value="Jakarta"
                                                                            {{ $vendor->city == 'Jakarta' ? 'selected' : '' }}>
                                                                            Jakarta</option>
                                                                        <option value="Surabaya"
                                                                            {{ $vendor->city == 'Surabaya' ? 'selected' : '' }}>
                                                                            Surabaya</option>
                                                                        <option value="Bandung"
                                                                            {{ $vendor->city == 'Bandung' ? 'selected' : '' }}>
                                                                            Bandung</option>
                                                                        <option value="Medan"
                                                                            {{ $vendor->city == 'Medan' ? 'selected' : '' }}>
                                                                            Medan</option>
                                                                        <option value="Semarang"
                                                                            {{ $vendor->city == 'Semarang' ? 'selected' : '' }}>
                                                                            Semarang</option>
                                                                    </select>
                                                                    <label for="city"
                                                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                                        City<span class="text-red-500">*</span>
                                                                    </label>
                                                                </div>

                                                                <!-- POS Code -->
                                                                <div class="relative z-0 w-full mb-5 group">
                                                                    <input name="zip_code" id="zip_code"
                                                                        value="{{ $vendor->zip_code }}"
                                                                        type="text" autocomplete="off"
                                                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                        placeholder=" " required />
                                                                    <label for="zip_code"
                                                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                                        POS Code<span class="text-red-500">*</span>
                                                                    </label>
                                                                </div>
                                                            </div>

                                                            <div class="grid md:grid-cols-2 md:gap-6">
                                                                <!-- Contact Phone -->
                                                                <div class="relative z-0 w-full mb-5 group">
                                                                    <input name="phone" id="phone"
                                                                        value="{{ $vendor->phone }}" type="tel"
                                                                        autocomplete="off"
                                                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                        placeholder=" " />
                                                                    <label for="phone"
                                                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                                        Contact Phone
                                                                    </label>
                                                                </div>

                                                                <!-- Term of Payment -->
                                                                <div class="relative z-0 w-full mb-5 group">
                                                                    <input name="termin" id="termin"
                                                                        value="{{ $vendor->termin }}" type="number"
                                                                        autocomplete="off"
                                                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                        placeholder=" " required />
                                                                    <label for="termin"
                                                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                                        Term of Payment (days)<span
                                                                            class="text-red-500">*</span>
                                                                    </label>
                                                                </div>
                                                            </div>

                                                            <!-- Bank Information -->
                                                            <div class="grid md:grid-cols-3 md:gap-6">
                                                                <!-- Bank Name -->
                                                                <div class="relative z-0 w-full mb-5 group">
                                                                    <select name="bank_name" id="bank_name"
                                                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                        required>
                                                                        <option value="" disabled>Select Bank
                                                                        </option>
                                                                        <option value="BCA"
                                                                            {{ $vendor->bank_name == 'BCA' ? 'selected' : '' }}>
                                                                            Bank Central Asia (BCA)</option>
                                                                        <option value="Mandiri"
                                                                            {{ $vendor->bank_name == 'Mandiri' ? 'selected' : '' }}>
                                                                            Bank Mandiri</option>
                                                                        <option value="BNI"
                                                                            {{ $vendor->bank_name == 'BNI' ? 'selected' : '' }}>
                                                                            Bank Negara Indonesia (BNI)</option>
                                                                        <option value="BRI"
                                                                            {{ $vendor->bank_name == 'BRI' ? 'selected' : '' }}>
                                                                            Bank Rakyat Indonesia (BRI)</option>
                                                                        <option value="CIMB"
                                                                            {{ $vendor->bank_name == 'CIMB' ? 'selected' : '' }}>
                                                                            CIMB Niaga</option>
                                                                    </select>
                                                                    <label for="bank_name"
                                                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                                        Bank<span class="text-red-500">*</span>
                                                                    </label>
                                                                </div>

                                                                <!-- Account Number -->
                                                                <div class="relative z-0 w-full mb-5 group">
                                                                    <input name="bank_acc_num" id="bank_acc_num"
                                                                        value="{{ $vendor->bank_acc_num }}"
                                                                        type="text" autocomplete="off"
                                                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                        placeholder=" " required />
                                                                    <label for="bank_acc_num"
                                                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                                        Account Number<span
                                                                            class="text-red-500">*</span>
                                                                    </label>
                                                                </div>

                                                                <!-- Account Name -->
                                                                <div class="relative z-0 w-full mb-5 group">
                                                                    <input name="bank_acc_name" id="bank_acc_name"
                                                                        value="{{ $vendor->bank_acc_name }}"
                                                                        type="text" autocomplete="off"
                                                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                        placeholder=" " required />
                                                                    <label for="bank_acc_name"
                                                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                                        Account Name<span class="text-red-500">*</span>
                                                                    </label>
                                                                </div>
                                                            </div>

                                                            <!-- NPWP Section -->
                                                            <div class="mt-6 pt-6 border-t border-gray-200">
                                                                <h3 class="text-lg font-medium text-gray-900 mb-4">NPWP
                                                                    Information</h3>

                                                                <!-- NPWP ID -->
                                                                <div class="relative z-0 w-full mb-5 group">
                                                                    <input name="npwp_id" id="npwp_id"
                                                                        value="{{ $vendor->npwp_id }}" type="text"
                                                                        autocomplete="off"
                                                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                        placeholder=" " />
                                                                    <label for="npwp_id"
                                                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                                        NPWP ID
                                                                    </label>
                                                                </div>

                                                                <div class="grid md:grid-cols-3 md:gap-6">
                                                                    <!-- NPWP Country -->
                                                                    <div class="relative z-0 w-full mb-5 group">
                                                                        <select name="npwp_country" id="npwp_country"
                                                                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                                                                            <option value="Indonesia" selected>
                                                                                Indonesia</option>
                                                                        </select>
                                                                        <label for="npwp_country"
                                                                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                                            NPWP Country
                                                                        </label>
                                                                    </div>

                                                                    <!-- NPWP City -->
                                                                    <div class="relative z-0 w-full mb-5 group">
                                                                        <select name="npwp_city" id="npwp_city"
                                                                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                                                                            <option value="" disabled>Select NPWP
                                                                                City</option>
                                                                            <option value="Jakarta"
                                                                                {{ $vendor->npwp_city == 'Jakarta' ? 'selected' : '' }}>
                                                                                Jakarta</option>
                                                                            <option value="Surabaya"
                                                                                {{ $vendor->npwp_city == 'Surabaya' ? 'selected' : '' }}>
                                                                                Surabaya</option>
                                                                            <option value="Bandung"
                                                                                {{ $vendor->npwp_city == 'Bandung' ? 'selected' : '' }}>
                                                                                Bandung</option>
                                                                            <option value="Medan"
                                                                                {{ $vendor->npwp_city == 'Medan' ? 'selected' : '' }}>
                                                                                Medan</option>
                                                                            <option value="Semarang"
                                                                                {{ $vendor->npwp_city == 'Semarang' ? 'selected' : '' }}>
                                                                                Semarang</option>
                                                                        </select>
                                                                        <label for="npwp_city"
                                                                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                                            NPWP City
                                                                        </label>
                                                                    </div>

                                                                    <!-- NPWP POS Code -->
                                                                    <div class="relative z-0 w-full mb-5 group">
                                                                        <input name="npwp_zipcode" id="npwp_zipcode"
                                                                            value="{{ $vendor->npwp_zipcode }}"
                                                                            type="text" autocomplete="off"
                                                                            class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                            placeholder=" " />
                                                                        <label for="npwp_zipcode"
                                                                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                                            NPWP POS Code
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                <!-- NPWP Address -->
                                                                <div class="relative z-0 w-full mb-5 group">
                                                                    <textarea name="npwp_address" id="npwp_address" rows="3"
                                                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                        placeholder=" ">{{ $vendor->npwp_address }}</textarea>
                                                                    <label for="npwp_address"
                                                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                                        NPWP Address
                                                                    </label>
                                                                </div>
                                                            </div>

                                                            <!-- Status -->
                                                            <div class="relative z-0 w-full mb-5 group">
                                                                <select name="status" id="status"
                                                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                    required>
                                                                    <option value="Active"
                                                                        {{ $vendor->status == 'Active' ? 'selected' : '' }}>
                                                                        Active</option>
                                                                    <option value="Inactive"
                                                                        {{ $vendor->status == 'Inactive' ? 'selected' : '' }}>
                                                                        Inactive</option>
                                                                </select>
                                                                <label for="status"
                                                                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                                    Status<span class="text-red-500">*</span>
                                                                </label>
                                                            </div>

                                                            <!-- File Upload Section -->
                                                            <input type="hidden" name="file_names"
                                                                id="file_names_input" value="">

                                                            <!-- Dropzone for file uploads -->
                                                            <div class="relative z-0 w-full mb-5 group border-2 border-gray-300 border-dashed rounded-lg p-6"
                                                                id="dropzone" @dragover.prevent @dragenter.prevent
                                                                @dragleave.prevent @drop.prevent="handleDrop($event)"
                                                                x-bind:class="{ 'border-blue-500': isDragging }"
                                                                x-show="!fileUploaded">
                                                                <input type="file" name="files[]" id="files"
                                                                    class="absolute inset-0 w-full h-full opacity-0 z-50"
                                                                    multiple @change="handleFiles($event)" />
                                                                <div class="text-center">
                                                                    <img class="mx-auto h-12 w-12"
                                                                        src="https://www.svgrepo.com/show/357902/image-upload.svg"
                                                                        alt="">
                                                                    <h3 class="mt-2 text-sm font-medium text-gray-900">
                                                                        <label for="files"
                                                                            class="relative cursor-pointer">
                                                                            <span>Drag and drop</span>
                                                                            <span class="text-indigo-600"> or
                                                                                browse</span>
                                                                            <span>to upload</span>
                                                                        </label>
                                                                    </h3>
                                                                    <p class="mt-1 text-xs text-gray-500">
                                                                        PDF up to 25MB
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <!-- Display selected file names -->
                                                            <div id="file-names" class="mt-4 text-sm text-gray-700">
                                                                <template x-for="(file, index) in files"
                                                                    :key="index">
                                                                    <div
                                                                        class="relative w-full flex items-center justify-between rounded-lg bg-[#e3f2fd] p-4 border border-[#90caf9]">
                                                                        <!-- Icon Folder -->
                                                                        <svg class="w-8 h-8 text-[#1976d2] mr-4"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            fill="none" viewBox="0 0 24 24"
                                                                            stroke="currentColor">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M3 7a2 2 0 012-2h4l2 2h6a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                                                                        </svg>
                                                                        <!-- Nama File -->
                                                                        <span
                                                                            class="truncate text-sm font-medium text-[#07074D] flex-1">
                                                                            <span x-text="file.name"></span>
                                                                        </span>
                                                                        <!-- Tombol Hapus -->
                                                                        <button
                                                                            class="text-[#d32f2f] hover:text-red-700 ml-4"
                                                                            @click="removeFile(index)">
                                                                            <svg width="16" height="16"
                                                                                viewBox="0 0 10 10" fill="none"
                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                <path fill-rule="evenodd"
                                                                                    clip-rule="evenodd"
                                                                                    d="M0.279337 0.279338C0.651787 -0.0931121 1.25565 -0.0931121 1.6281 0.279338L9.72066 8.3719C10.0931 8.74435 10.0931 9.34821 9.72066 9.72066C9.34821 10.0931 8.74435 10.0931 8.3719 9.72066L0.279337 1.6281C-0.0931125 1.25565 -0.0931125 0.651788 0.279337 0.279338Z"
                                                                                    fill="currentColor" />
                                                                                <path fill-rule="evenodd"
                                                                                    clip-rule="evenodd"
                                                                                    d="M0.279337 9.72066C-0.0931125 9.34821 -0.0931125 8.74435 0.279337 8.3719L8.3719 0.279338C8.74435 -0.0931127 9.34821 -0.0931123 9.72066 0.279338C10.0931 0.651787 10.0931 1.25565 9.72066 1.6281L1.6281 9.72066C1.25565 10.0931 0.651787 10.0931 0.279337 9.72066Z"
                                                                                    fill="currentColor" />
                                                                            </svg>
                                                                        </button>
                                                                    </div>
                                                                </template>
                                                            </div>

                                                            <!-- Modal footer -->
                                                            <div
                                                                class="px-5 py-3 border-t border-slate-200 flex justify-end mt-4">
                                                                <button type="button"
                                                                    @click="modalOpenDetail = false"
                                                                    class="mr-2 inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                                    Cancel
                                                                </button>
                                                                <button type="submit"
                                                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                                    Update Vendor
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No vendor data found.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>

            <div class="bg-gray-50 rounded p-4">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <form method="GET" action="{{ route('indexEdit.vendor') }}">
                        <select name="per_page" id="per_page" onchange="this.form.submit()"
                            class="border border-gray-300 rounded px-4 py-2 w-32">
                            <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                            <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                            <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                            <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                        </select>
                    </form>
                </div>
                <div class="px-6 py-4">{{ $vendors->links() }}</div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('modal', () => ({
                modalOpenDetail: false,
                files: [],
                isDragging: false,
                fileUploaded: false,

                // Load vendor data when modal opens
                loadVendorData(vendorId) {
                    // You can fetch additional data via AJAX if needed
                    console.log('Loading data for vendor:', vendorId);
                    // Here you would typically fetch data via AJAX if not already passed to the view
                },

                handleFiles(event) {
                    const files = event.target.files;
                    this.processFiles(files);
                },

                handleDrop(event) {
                    event.preventDefault();
                    const files = event.dataTransfer.files;
                    this.processFiles(files);
                    this.isDragging = false;
                },

                processFiles(files) {
                    if (this.files.length === 0) {
                        for (let file of files) {
                            const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg'];
                            const maxSize = 25 * 1024 * 1024; // 25MB

                            if (allowedTypes.includes(file.type) && file.size <= maxSize) {
                                this.files.push(file);
                                this.fileUploaded = true;
                                this.updateFileNames();

                                Toastify({
                                    text: "File berhasil diunggah!",
                                    duration: 3000,
                                    gravity: "top",
                                    position: "right",
                                    style: {
                                        background: "#4CAF50"
                                    }
                                }).showToast();
                            } else {
                                Toastify({
                                    text: `File ${file.name} tidak valid (hanya PDF/JPG, maks 25MB).`,
                                    duration: 3000,
                                    gravity: "top",
                                    position: "right",
                                    style: {
                                        background: "#FF5733"
                                    }
                                }).showToast();
                            }
                        }
                    } else {
                        Toastify({
                            text: "Hanya satu file yang diizinkan.",
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            style: {
                                background: "#FFC107"
                            }
                        }).showToast();
                    }
                },

                removeFile(index) {
                    this.files.splice(index, 1);
                    if (this.files.length === 0) {
                        this.fileUploaded = false;
                    }
                    this.updateFileNames();

                    Toastify({
                        text: "File deleted.",
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "#607D8B"
                        }
                    }).showToast();
                },

                updateFileNames() {
                    const fileNames = this.files.map(file => file.name).join(',');
                    document.getElementById('file_names_input').value = fileNames;
                }
            }));
        });
    </script>
</x-app-layout>
