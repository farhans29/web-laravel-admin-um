<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-semibold">AJU Management</h2>
        </div>

        <!-- First Container: Input Fields -->
        <div id="containerAccount" class="bg-white shadow-md rounded-lg overflow-hidden mt-8">
            <div class="flex justify-between items-center px-6 py-4 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">AJU # - Details</h2>
            </div>
            <div class="modal-content text-xs px-5 py-4">
                <form id="ajuForm" method="POST" action="{{ route('aju.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="grid md:grid-cols-2 md:gap-6">
                        <!-- Input Date -->
                        <div class="relative z-0 w-full mb-5 group">
                            <input type="date" name="date" id="date" autocomplete="off"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                value="{{ isset($aju->date) ? $aju->date : '' }}"
                                @if (!empty($aju)) disabled @endif required />
                            <label for="date"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                Select Date
                            </label>
                        </div>


                        <!-- Input AJU Number + Button -->
                        <div class="relative z-0 w-full mb-5 flex items-center space-x-2">
                            <div class="w-full">
                                <input name="id_aju" id="id_aju" autocomplete="off"
                                    value="{{ $aju->no_docs ?? '' }}"
                                    class="block py-2.5 px-3 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                    placeholder=" " required @if (!empty($aju)) disabled @endif
                                    onkeypress="return event.key !== ' '"
                                    oninput="this.value = this.value.replace(/\s/g, '')" />
                                <label for="id_aju"
                                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                    AJU Number
                                </label>
                            </div>

                            @if (empty($aju))
                                <button type="button" id="suggest_id_aju"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 cursor-pointer">
                                    #
                                </button>
                            @endif
                        </div>

                    </div>

                    <div class="grid md:grid-cols-2 md:gap-6">
                        <!-- Department -->
                        <div class="relative z-0 w-full mb-5 group">
                            <select name="dep" id="dep"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                required @if (!empty($aju)) disabled @endif>
                                <option value="" disabled selected>Select Department</option>
                                @foreach ($deps as $department)
                                    <option value="{{ $department->id }}"
                                        @if (isset($aju) && $department->id == $aju->department->pid) selected @endif>
                                        {{ $department->name }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="dep"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                Department
                            </label>
                        </div>

                        <!-- Sub Department -->
                        <div class="relative z-0 w-full mb-5 group">
                            <select name="sub_dep" id="sub_dep"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                required @if (!empty($aju)) disabled @endif>
                                <option value="" disabled selected>Select Sub Department</option>
                                @if (isset($aju) && isset($subDeps))
                                    @foreach ($subDeps as $subDep)
                                        <option value="{{ $subDep->id }}"
                                            @if ($aju->id_department == $subDep->id) selected @endif>
                                            {{ $subDep->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <label for="sub_dep"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                Sub Department
                            </label>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2 w-full mb-5">
                        <div class="relative z-0 w-full group">
                            <input type="text" name="description" id="description"
                                value="{{ isset($aju) ? $aju->description : '' }}" autocomplete="off"
                                class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                placeholder=" " required @if (!empty($aju)) disabled @endif />
                            <label for="description"
                                class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                Description
                            </label>
                        </div>
                    </div>

                    <!-- Footer dengan tombol Save -->
                    @if (empty($aju))
                        <div class="flex justify-end px-6 py-4 bg-gray-50">
                            <button type="submit" id="save"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 cursor-pointer">
                                Save
                            </button>
                        </div>
                    @endif

                </form>
            </div>
        </div>

        <!-- Second Container: Imported Documents Table -->
        <div id="containerDocuments" class="bg-white shadow-md rounded-lg overflow-hidden mt-8">
            <div class="flex justify-between items-center px-6 py-4 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">AJU - Documents</h2>
                <div class="flex gap-x-4">
                    <div x-data="{ modalOpenArchive: false }">
                        <!-- Trigger Button -->
                        <button
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 cursor-pointer disabled:bg-blue-800 disabled:cursor-not-allowed"
                            type="button"
                            @click.prevent="
                                                modalOpenArchive = true;
                                                document.querySelectorAll('[id^=id_aju_modal_archive_]').forEach(input => {
                                                    input.value = document.getElementById('id_aju').value;
                                                });
                                            "
                            aria-controls="archive-modal" @if (empty($aju->no_docs))
                            disabled
                            @endif>
                            Add Archive
                        </button>

                        <!-- Modal Backdrop -->
                        <div class="fixed inset-0 backdrop-blur bg-opacity-30 z-50 transition-opacity"
                            x-show="modalOpenArchive" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-out duration-100" x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0" aria-hidden="true" x-cloak></div>

                        <!-- Modal Dialog - Diperlebar menjadi max-w-7xl dan dimodifikasi layoutnya -->
                        <div id="archive-modal"
                            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center px-4 sm:px-6 py-4"
                            role="dialog" aria-modal="true" x-show="modalOpenArchive"
                            x-transition:enter="transition ease-in-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in-out duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-4" x-cloak>
                            <div class="bg-white rounded-lg shadow-xl overflow-hidden w-3/4 max-h-[70vh] flex flex-col"
                                @click.outside="modalOpenArchive = false"
                                @keydown.escape.window="modalOpenArchive = false">
                                <!-- Modal Header dengan padding lebih lebar -->
                                <div class="px-6 py-3 border-b border-slate-200 flex justify-between items-center">
                                    <h2 class="font-semibold text-slate-800 text-lg">Archive Data</h2>
                                    <div class="flex items-center space-x-4">
                                        <div class="relative mb-4">
                                            <input type="text" id="searchArchive" placeholder="Search document..."
                                                class="form-input pl-4 pr-10 py-2 border border-gray-300 rounded w-64">
                                            <button id="btnSearchArchive" type="button"
                                                class="absolute right-3 top-2 text-gray-400 hover:text-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                            </button>
                                        </div>

                                        <button type="button"
                                            class="text-slate-400 hover:text-slate-500 focus:outline-none"
                                            @click="modalOpenArchive = false" aria-label="Close modal">
                                            <svg class="w-5 h-5 fill-current">
                                                <path
                                                    d="M7.95 6.536l4.242-4.243a1 1 0 111.415 1.414L9.364 7.95l4.243 4.242a1 1 0 11-1.415 1.415L7.95 9.364l-4.243 4.243a1 1 0 01-1.414-1.415L6.536 7.95 2.293 3.707a1 1 0 011.414-1.414L7.95 6.536z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Modal Content dengan padding lebih lebar -->
                                <div class="flex-1 overflow-y-auto px-6 py-4">
                                    <div class="container mx-auto">
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                                            Doc Type</th>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                                            Date</th>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                                            Description</th>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                                            Doc Number</th>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                                            Department</th>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                                            Sub Department</th>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                                            File Name</th>
                                                        <th
                                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">
                                                            Actions</th>
                                                    </tr>
                                                </thead>

                                                <tbody id="archiveTableBody"
                                                    class="bg-white divide-y divide-gray-200">
                                                    @foreach ($archives as $archive)
                                                        <tr>
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                {{ $archive->doc_type }}</td>
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                {{ $archive->date }}</td>
                                                            <td class="px-6 py-4 text-sm text-gray-500">
                                                                {{ $archive->description }}</td>
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                {{ $archive->no_document ?? '-' }}</td>
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                {{ $archive->subDepartment->parent->name ?? '-' }}
                                                            </td>
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                {{ $archive->subDepartment->name ?? '-' }}
                                                            </td>
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                                <a onclick="openPdfInNewTab('{{ $archive->pdfblob }}')"
                                                                    class="text-indigo-600 hover:text-indigo-900 cursor-pointer">{{ $archive->file_name }}</a>
                                                            </td>
                                                            <td
                                                                class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                                <form method="POST"
                                                                    action="{{ route('aju.storeNewModalArchive') }}">
                                                                    @csrf
                                                                    <input type="hidden" name="id_aju_modal_archive"
                                                                        id="id_aju_modal_archive_{{ $archive->idrec }}"
                                                                        value="">
                                                                    <input type="hidden" name="id_archieve"
                                                                        value="{{ $archive->idrec }}">
                                                                    <button type="submit"
                                                                        class="text-indigo-600 hover:text-indigo-900 mr-3">Add</button>
                                                                </form>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Footer -->
                                <div class="border-t border-slate-200 px-6 py-3 flex justify-between items-center">

                                </div>
                            </div>
                        </div>
                    </div>

                    <div x-data="modal()">
                        <button
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 cursor-pointer disabled:bg-blue-800 disabled:cursor-not-allowed"
                            type="button"
                            @click.prevent="
                                                modalOpenDetail = true;
                                                document.getElementById('id_aju_modal').value = document.getElementById('id_aju').value;
                                            "
                            aria-controls="feedback-modal1" @if (empty($aju->no_docs)) disabled @endif>
                            Add New PDF
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
                            <div class="bg-white rounded shadow-lg overflow-auto w-3/4 max-h-full"
                                @click.outside="modalOpenDetail = false"
                                @keydown.escape.window="modalOpenDetail = false">
                                <!-- Modal header -->
                                <div class="px-5 py-3 border-b border-slate-200" id="modalAddLpjDetail">
                                    <div class="flex justify-between items-center">
                                        <div class="font-semibold text-slate-800">New Document</div>
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
                                    <form id="documentForm" method="POST" action="{{ route('aju.storeModal') }}"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="grid md:grid-cols-1 md:gap-6">
                                            <div class="grid md:grid-cols-2 md:gap-6">
                                                <!-- Input Date -->
                                                <div class="relative z-0 w-full mb-5 group">
                                                    <input type="date" name="date_modal" id="date_modal" required
                                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" />
                                                    <label for="date_modal"
                                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                        Select Date
                                                    </label>
                                                </div>
                                                <div class="relative z-0 w-full mb-5 flex items-center space-x-2">
                                                    <div class="w-full">
                                                        <input name="id_document" id="id_document" autocomplete="off"
                                                            class="block py-2.5 px-3 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                            placeholder=" " required
                                                            onkeypress="return event.key !== ' '"
                                                            oninput="this.value = this.value.replace(/\s/g, '')" />
                                                        <label for="id_document"
                                                            class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                            Document Number
                                                        </label>
                                                    </div>
                                                    <button type="button" id="suggest_id_document"
                                                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400 cursor-pointer">
                                                        #
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="grid md:grid-cols-4 md:gap-6">
                                                <div class="relative z-0 w-full mb-5 group">
                                                    <select name="dep_modal" id="dep_modal"
                                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                        required>
                                                        <option value="" disabled selected>Select Department
                                                        </option>
                                                        @foreach ($deps as $department)
                                                            <option value="{{ $department->id }}"
                                                                @if (isset($aju) && $department->id == $aju->department->pid) selected @endif>
                                                                {{ $department->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <label for="dep_modal"
                                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                        Department
                                                    </label>
                                                </div>

                                                <!-- Sub Department -->
                                                <div class="relative z-0 w-full mb-5 group">
                                                    <select name="sub_dep_modal" id="sub_dep_modal"
                                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                        required>
                                                        <option value="" disabled selected>Select Sub Department
                                                        </option>
                                                        @if (isset($aju) && isset($subDeps))
                                                            @foreach ($subDeps as $subDep)
                                                                <option value="{{ $subDep->id }}"
                                                                    @if ($aju->id_department == $subDep->id) selected @endif>
                                                                    {{ $subDep->name }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <label for="sub_dep_modal"
                                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                        Sub Department
                                                    </label>
                                                </div>
                                                <div class="relative z-0 w-full mb-5 flex items-center space-x-2">
                                                    <select name="type_docs_modal" id="type_docs_modal" required
                                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                                                        <option value="" data-code="">Select Document Type
                                                        </option>
                                                        @foreach ($documentTypes as $doc)
                                                            <option value="{{ $doc->name }}"
                                                                data-code="{{ $doc->code }}">
                                                                {{ $doc->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <label for="type_docs_modal"
                                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                        Type Document
                                                    </label>
                                                </div>
                                                <div class="relative z-0 w-full mb-5 group">
                                                    <select name="user_email" id="user_email"
                                                        class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                        required>
                                                        <option value="" disabled
                                                            {{ !isset($aju) ? 'selected' : '' }}>Select User Email
                                                        </option>
                                                        @if (isset($users))
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->id }}"
                                                                    @if ((isset($aju) && $aju->created_by == $user->id) || (!isset($aju) && Auth::id() == $user->id)) selected @endif>
                                                                    {{ $user->name }} ({{ $user->email }})
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <label for="user_email"
                                                        class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                        User Email
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="id_aju_modal" id="id_aju_modal" value="">
                                        <div class="flex items-center space-x-2 w-full mb-5">
                                            <div class="relative z-0 w-full group">
                                                <input name="description_modal" id="description_modal"
                                                    autocomplete="off"
                                                    class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                    placeholder=" " required />
                                                <label for="description_modal"
                                                    class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">
                                                    Description
                                                </label>
                                            </div>
                                        </div>
                                        <input type="hidden" name="file_names" id="file_names_input"
                                            value="">
                                        <!-- Dropzone for file uploads -->
                                        <div class="relative z-0 w-full mb-5 group border-2 border-gray-300 border-dashed rounded-lg p-6"
                                            id="dropzone" @dragover.prevent @dragenter.prevent @dragleave.prevent
                                            @drop.prevent="handleDrop($event)"
                                            x-bind:class="{ 'border-blue-500': isDragging }" x-show="!fileUploaded">
                                            <input type="file" name="files[]" id="files"
                                                class="absolute inset-0 w-full h-full opacity-0 z-50" multiple
                                                @change="handleFiles($event)"
                                                accept="application/pdf, image/jpeg, image/jpg" />
                                            <div class="text-center">
                                                <img class="mx-auto h-12 w-12"
                                                    src="https://www.svgrepo.com/show/357902/image-upload.svg"
                                                    alt="">
                                                <h3 class="mt-2 text-sm font-medium text-gray-900">
                                                    <label for="files" class="relative cursor-pointer">
                                                        <span>Drag and drop</span>
                                                        <span class="text-indigo-600"> or browse</span>
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
                                            <template x-for="(file, index) in files" :key="index">
                                                <div
                                                    class="relative w-full flex items-center justify-between rounded-lg bg-[#e3f2fd] p-4 border border-[#90caf9]">

                                                    <!-- Icon Folder -->
                                                    <svg class="w-8 h-8 text-[#1976d2] mr-4"
                                                        xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 7a2 2 0 012-2h4l2 2h6a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V7z" />
                                                    </svg>

                                                    <!-- Nama File -->
                                                    <span class="truncate text-sm font-medium text-[#07074D] flex-1">
                                                        <span x-text="file.name"></span>
                                                    </span>

                                                    <!-- Tombol Hapus -->
                                                    <button class="text-[#d32f2f] hover:text-red-700 ml-4"
                                                        @click="removeFile(index)">
                                                        <svg width="16" height="16" viewBox="0 0 10 10"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M0.279337 0.279338C0.651787 -0.0931121 1.25565 -0.0931121 1.6281 0.279338L9.72066 8.3719C10.0931 8.74435 10.0931 9.34821 9.72066 9.72066C9.34821 10.0931 8.74435 10.0931 8.3719 9.72066L0.279337 1.6281C-0.0931125 1.25565 -0.0931125 0.651788 0.279337 0.279338Z"
                                                                fill="currentColor" />
                                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                                d="M0.279337 9.72066C-0.0931125 9.34821 -0.0931125 8.74435 0.279337 8.3719L8.3719 0.279338C8.74435 -0.0931127 9.34821 -0.0931123 9.72066 0.279338C10.0931 0.651787 10.0931 1.25565 9.72066 1.6281L1.6281 9.72066C1.25565 10.0931 0.651787 10.0931 0.279337 9.72066Z"
                                                                fill="currentColor" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </template>
                                        </div>


                                        <!-- Modal footer -->
                                        <div class="px-5 py-3 border-t border-slate-200 flex justify-end mt-4">
                                            <button type="submit"
                                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">Upload</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-content text-xs px-5 py-4">
                <table id="tablePDF" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">Document Number</th>
                            <th scope="col" class="px-6 py-3">Document Type</th>
                            <th scope="col" class="px-6 py-3">Date</th>
                            <th scope="col" class="px-6 py-3">Description</th>
                            {{-- <th scope="col" class="px-6 py-3">Department</th>
                            <th scope="col" class="px-6 py-3">Sub Department</th> --}}
                            <th scope="col" class="px-6 py-3">User</th>
                            <th scope="col" class="px-6 py-3">File Name</th>
                            <th scope="col" class="px-6 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ajuDetails as $detail)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                <td class="px-6 py-4">{{ $detail->archive->no_document ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $detail->archive->doc_type ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $detail->archive->date ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $detail->archive->description ?? '-' }}</td>
                                {{-- <td class="px-6 py-4">
                                    {{ $detail->archive->subDepartment->parent->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $detail->archive->subDepartment->name ?? '-' }}
                                </td> --}}
                                <td class="px-6 py-4">{{ $detail->archive->createdByUser->name ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    @if ($detail->archive && $detail->archive->file_name)
                                        <a onclick="openPdfInNewTab('{{ $detail->archive->pdfblob }}')"
                                            class="text-indigo-600 hover:text-indigo-900 cursor-pointer">{{ $detail->archive->file_name }}</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <button class="text-green-600 hover:text-red-900 cursor-pointer"
                                        onclick="openPdfInNewTab('{{ $detail->archive->pdfblob }}')">
                                        View
                                    </button>
                                    <button class="text-red-600 hover:text-red-900 cursor-pointer"
                                        onclick="confirmDelete('{{ $detail->idrec }}', '{{ $detail->archive->file_name }}')">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('modal', () => ({
                modalOpenDetail: false,
                files: [],
                isDragging: false,
                fileUploaded: false,

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

                    // Update hidden input with file names
                    this.updateFileNames();

                    // Notification for file deletion
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

        document.addEventListener("DOMContentLoaded", function() {
            let today = new Date();
            let formattedDate = today.toISOString().split('T')[0];
            document.getElementById("date").value = formattedDate;
            document.getElementById("date_modal").value = formattedDate;
        });

        document.addEventListener('DOMContentLoaded', function() {
            const departmentSelect = document.getElementById('dep');
            const subDepartmentSelect = document.getElementById('sub_dep');

            departmentSelect.addEventListener('change', function() {
                const departmentId = this.value;

                // Clear existing options
                subDepartmentSelect.innerHTML =
                    '<option value="" disabled selected>Select Sub Department</option>';

                if (departmentId) {
                    fetch(`/archive/get-sub-departments/${departmentId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(subDep => {
                                const option = document.createElement('option');
                                option.value = subDep.id;
                                option.textContent = subDep.name;
                                subDepartmentSelect.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching sub-departments:', error);
                        });
                }
            });
        });

        document.getElementById('id_aju').addEventListener('input', function() {
            const ajuNumber = this.value;

            if (ajuNumber) {
                fetch(`/archive/check-aju-number?id_aju=${ajuNumber}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            // Tampilkan pesan error menggunakan Toastify
                            Toastify({
                                text: "AJU Number already exists! Please use a different number.",
                                duration: 3000,
                                gravity: "top", // Posisi notifikasi
                                position: "right", // Bisa "left", "right", "center"
                                backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)", // Warna background
                                stopOnFocus: true, // Berhenti otomatis saat user mengklik notifikasi
                            }).showToast();
                        }
                    })
                    .catch(error => {
                        console.error('Error checking AJU Number:', error);
                    });
            }
        });

        document.getElementById('suggest_id_aju')?.addEventListener('click', function() {
            // Get date input value
            const dateInput = document.getElementById('date').value;

            // Validate date is selected
            if (!dateInput) {
                alert('Please select a date first');
                return;
            }

            // Disable button during request to prevent multiple clicks
            const suggestButton = this;
            suggestButton.disabled = true;
            suggestButton.innerHTML = 'Generating...';

            // Show loading indicator
            const originalText = suggestButton.textContent;

            // Send request to backend
            fetch('/archive/suggest-aju-number', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        date: dateInput
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.suggested_id_aju) {
                        document.getElementById('id_aju').value = data.suggested_id_aju;
                    } else {
                        throw new Error('No suggestion received from server');
                    }
                })
                .catch(error => {
                    console.error('Error suggesting AJU Number:', error);
                    alert('Failed to generate AJU number. Please try again or enter manually.');
                })
                .finally(() => {
                    // Re-enable button regardless of success/failure
                    suggestButton.disabled = false;
                    suggestButton.innerHTML = '#';
                });
        });

        function openPdfInNewTab(base64Data) {
            if (base64Data) {
                // Convert base64 to a blob
                const byteCharacters = atob(base64Data);
                const byteNumbers = new Array(byteCharacters.length);
                for (let i = 0; i < byteCharacters.length; i++) {
                    byteNumbers[i] = byteCharacters.charCodeAt(i);
                }
                const byteArray = new Uint8Array(byteNumbers);
                const blob = new Blob([byteArray], {
                    type: 'application/pdf'
                });

                // Create a blob URL and open it in a new tab
                const blobUrl = URL.createObjectURL(blob);
                window.open(blobUrl, '_blank');

                // Revoke the blob URL after opening to free up memory
                URL.revokeObjectURL(blobUrl);
            } else {
                // Show an error message if the PDF data is missing or invalid
                Toastify({
                    text: "PDF data is missing or invalid.",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "#ff4d4d",
                        color: "#fff",
                        borderRadius: "5px",
                        padding: "10px"
                    }
                }).showToast();
            }
        }

        document.getElementById('ajuForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Mencegah form dari pengiriman biasa

            Swal.fire({
                title: "Are you sure?",
                text: "Once saved, data cannot be changed!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, save!",
                cancelButtonText: "Cancelled"
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData(document.getElementById('ajuForm'));

                    fetch("{{ route('aju.store') }}", {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                'Accept': 'application/json' // Pastikan respons JSON
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                // Tampilkan notifikasi sukses
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: data.message,
                                    icon: "success",
                                    timer: 3000,
                                    showConfirmButton: false
                                });

                                // Nonaktifkan input setelah berhasil
                                document.querySelectorAll(
                                    '#date, #id_aju, #dep, #sub_dep, #description').forEach(
                                    element => {
                                        element.disabled = true;
                                    });

                                // Sembunyikan tombol Suggest
                                document.getElementById('suggest_id_aju').style.display = 'none';
                                document.getElementById('save').style.display = 'none';
                                document.querySelector('[aria-controls="archive-modal"]').disabled =
                                    false;
                                document.querySelector('[aria-controls="feedback-modal1"]').disabled =
                                    false;

                            } else {
                                Swal.fire({
                                    title: "Gagal!",
                                    text: data.message,
                                    icon: "error",
                                    confirmButtonText: "OK"
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: "Error!",
                                text: "Terjadi kesalahan saat mengirim data.",
                                icon: "error",
                                confirmButtonText: "OK"
                            });
                        });
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Get the button and both input fields
            const addPdfButton = document.querySelector('[aria-controls="feedback-modal1"]');
            const idAjuInput = document.getElementById('id_aju');
            const idAjuModalInput = document.getElementById('id_aju_modal');

            // Add click event listener to the button
            if (addPdfButton && idAjuInput && idAjuModalInput) {
                addPdfButton.addEventListener('click', function() {
                    // Copy the value from id_aju to id_aju_modal
                    idAjuModalInput.value = idAjuInput.value;
                });
            }
        });

        document.getElementById('id_document').addEventListener('input', function() {
            const docNumber = this.value.trim();
            const docTypeSelect = document.getElementById('type_docs_modal');
            const selectedDocType = docTypeSelect.options[docTypeSelect.selectedIndex].value;

            // Only check if document number is not empty and document type is selected
            if (docNumber && selectedDocType) {
                // Show loading indicator (optional)
                this.classList.add('checking-number');

                fetch(
                        `/archive/check-document-number?id_document=${encodeURIComponent(docNumber)}&doc_type=${encodeURIComponent(selectedDocType)}`
                    )
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.exists) {
                            Toastify({
                                text: "Document number already exists for this document type! Please use a different number.",
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                                stopOnFocus: true,
                            }).showToast();

                            // Optionally highlight the field
                            this.classList.add('border-red-500');
                        } else {
                            this.classList.remove('border-red-500');
                        }
                    })
                    .catch(error => {
                        console.error('Error checking document number:', error);
                        Toastify({
                            text: "Error checking document number availability",
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                            stopOnFocus: true,
                        }).showToast();
                    })
                    .finally(() => {
                        this.classList.remove('checking-number');
                    });
            }
        });

        document.getElementById('suggest_id_document')?.addEventListener('click', function() {
            // Get date input value and selected document type
            const dateInput = document.getElementById('date_modal').value;
            const docTypeSelect = document.getElementById('type_docs_modal');
            const docType = docTypeSelect.options[docTypeSelect.selectedIndex].value;

            // Disable button during request to prevent multiple clicks
            const suggestButton = this;
            suggestButton.disabled = true;
            suggestButton.innerHTML = 'Generating...';

            // Send request to backend
            fetch('/archive/suggest-document-number', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        date: dateInput,
                        doc_type: docType
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.suggested_id_document) {
                        document.getElementById('id_document').value = data.suggested_id_document;
                    } else {
                        throw new Error('No suggestion received from server');
                    }
                })
                .catch(error => {
                    console.error('Error suggesting Document Number:', error);
                    Toastify({
                        text: "Failed to generate Document number. Please try again or enter manually.",
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                        stopOnFocus: true,
                    }).showToast();
                })
                .finally(() => {
                    // Re-enable button regardless of success/failure
                    suggestButton.disabled = false;
                    suggestButton.innerHTML = '#';
                });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const addButton = document.querySelector('button[type="button"][aria-controls="feedback-modal1"]');

            addButton.addEventListener('click', function() {
                const depSelect = document.getElementById('dep');
                const subDepSelect = document.getElementById('sub_dep');

                const depValue = depSelect.value;
                const subDepValue = subDepSelect.value;

                const depModalSelect = document.getElementById('dep_modal');
                const subDepModalSelect = document.getElementById('sub_dep_modal');

                if (depValue && subDepValue) {
                    depModalSelect.value = depValue;
                    subDepModalSelect.value = subDepValue;

                    depModalSelect.dispatchEvent(new Event('change'));
                    subDepModalSelect.dispatchEvent(new Event('change'));

                    console.log("Department and Sub Department copied successfully.");

                } else {
                    alert('Please select both Department and Sub Department before adding a new PDF.');
                }
            });
        });



        document.addEventListener('DOMContentLoaded', function() {
            const departmentSelect = document.getElementById('dep_modal');
            const subDepartmentSelect = document.getElementById('sub_dep_modal');

            departmentSelect.addEventListener('change', function() {
                const departmentId = this.value;

                // Clear existing options
                subDepartmentSelect.innerHTML =
                    '<option value="" disabled selected>Select Sub Department</option>';

                if (departmentId) {
                    fetch(`/archive/get-sub-departments/${departmentId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(subDep => {
                                const option = document.createElement('option');
                                option.value = subDep.id;
                                option.textContent = subDep.name;
                                subDepartmentSelect.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching sub-departments:', error);
                        });
                }
            });
        });

        document.getElementById('btnSearchArchive').addEventListener('click', function() {
            let query = document.getElementById('searchArchive').value;
            let idAju = document.getElementById('id_aju').value; // Ambil nilai id_aju dari input tersembunyi

            fetch(`{{ route('aju.archive.search') }}?search=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    let tableBody = document.getElementById('archiveTableBody');
                    tableBody.innerHTML = '';

                    // Dapatkan CSRF token dari meta tag
                    let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    data.archives.forEach(archive => {
                        let row = document.createElement('tr');
                        row.innerHTML = `
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${archive.doc_type}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${archive.date}</td>
                                            <td class="px-6 py-4 text-sm text-gray-500">${archive.description}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${archive.no_document || '-'}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${archive.sub_department?.parent?.name || '-'}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${archive.sub_department?.name || '-'}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <a onclick="openPdfInNewTab('${archive.pdfblob}')" class="text-indigo-600 hover:text-indigo-900 cursor-pointer">${archive.file_name}</a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <form method="POST" action="{{ route('aju.storeModalArchive.update') }}">
                                                    <input type="hidden" name="_token" value="${csrfToken}">
                                                    <input type="hidden" name="id_aju_modal_archive" id="id_aju_modal_archive_${archive.idrec}" value="${idAju}">
                                                    <input type="hidden" name="id_archieve" value="${archive.idrec}">
                                                    <button type="submit" class="text-indigo-600 hover:text-indigo-900 mr-3">Add</button>
                                                </form>
                                            </td>
                                        `;
                        tableBody.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error fetching search results:', error);
                });
        });

        // Also add event listener for Enter key
        document.getElementById('searchArchive').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('btnSearchArchive').click();
            }
        });

        function confirmDelete(idrec, fileName) {
            Swal.fire({
                title: 'Confirm Delete',
                html: `Are you sure you want to delete the file: <strong>${fileName}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, Delete!',
                cancelButtonText: 'Cancelled',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    // Kirim request DELETE
                    fetch(`/archive/aju-detail/${idrec}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Gagal menghapus data');
                            }
                            return response.json();
                        })
                        .then(data => {
                            Swal.fire(
                                'Deleted!',
                                `File ${fileName} successfully deleted.`,
                                'success'
                            ).then(() => {
                                window.location.reload();
                            });
                        })
                        .catch(error => {
                            Swal.fire(
                                'Fail!',
                                `Failed to delete file ${fileName}: ${error.message}`,
                                'error'
                            );
                        });
                }
            });
        }
    </script>
</x-app-layout>
