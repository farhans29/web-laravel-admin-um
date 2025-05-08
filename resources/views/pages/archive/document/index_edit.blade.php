<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <!-- Modern Header with Gradient -->

        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
            <div>
                <h2
                    class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
                    Document Management
                </h2>
                <p class="text-gray-500 mt-1">Efficient document tracking and organization</p>
            </div>
        </div>


        <!-- Modern Card Container -->
        <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
            <!-- Card Header with Glass Effect -->
            <div
                class="flex flex-col md:flex-row md:items-center md:justify-between px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-800">Document List</h2>

                <!-- Search and Pagination -->
                <div class="flex flex-col md:flex-row md:items-center gap-4 mt-4 md:mt-0">
                    <div class="relative group">
                        <form method="GET" action="{{ route('index.editDocument') }}">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-all duration-300 group-focus-within:text-indigo-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                                placeholder="Search documents..."
                                class="block w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg bg-white shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-300 placeholder-gray-400">
                        </form>
                    </div>

                    <form method="GET" action="{{ route('index.editDocument') }}" class="flex items-center">
                        <label for="per_page" class="text-sm text-gray-600 mr-2">Show:</label>
                        <div class="relative">
                            <select name="per_page" id="per_page" onchange="this.form.submit()"
                                class="appearance-none bg-white border border-gray-200 rounded-lg px-3 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-sm">
                                <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                                <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ $perPage == 15 ? 'selected' : '' }}>15</option>
                                <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Table Container -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Description</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Doc Number</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Department</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sub Department</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                File</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Author</th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($archives as $index => $archive)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $index + $archives->firstItem() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $archive->date ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span
                                        class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $archive->doc_type ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                                    {{ $archive->description ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="font-mono text-indigo-600">{{ $archive->no_document ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $archive->subDepartment->parent->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $archive->subDepartment->name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-normal text-sm text-gray-900">
                                    <a onclick="openPdfInNewTab('{{ $archive->pdfblob }}')"
                                        class="text-indigo-600 hover:text-indigo-800 cursor-pointer flex items-center">

                                        <div class="flex-shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                            </svg>
                                        </div>

                                        <span class="whitespace-normal break-words">
                                            {{ $archive->file_name ?? '-' }}
                                        </span>

                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-medium">
                                            {{ substr($archive->createdByUser->name ?? '?', 0, 1) }}
                                        </div>
                                        <div class="ml-2">
                                            {{ $archive->createdByUser->name ?? '-' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                    <div x-data="modal({{ $archive }})" class="flex justify-center space-x-2">
                                        <button @click="modalOpenDetail = true"
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-full shadow-sm text-white bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition-all duration-200">
                                            Edit
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>

                                        <!-- Modern Modal -->
                                        <div class="fixed inset-0 z-50 overflow-y-auto" x-show="modalOpenDetail"
                                            x-cloak x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                            x-transition:leave="transition ease-in duration-200"
                                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

                                            <!-- Modal backdrop with blur effect -->
                                            <div class="fixed inset-0 bg-black/30 backdrop-blur-sm" aria-hidden="true"
                                                @click="modalOpenDetail = false"></div>

                                            <!-- Modal container -->
                                            <div class="flex min-h-screen items-center justify-center p-4">
                                                <!-- Modal dialog -->
                                                <div class="relative w-full max-w-4xl rounded-2xl bg-white shadow-2xl transition-all"
                                                    @click.stop x-show="modalOpenDetail"
                                                    x-transition:enter="transition ease-out duration-300"
                                                    x-transition:enter-start="opacity-0 scale-95"
                                                    x-transition:enter-end="opacity-100 scale-100"
                                                    x-transition:leave="transition ease-in duration-200"
                                                    x-transition:leave-start="opacity-100 scale-100"
                                                    x-transition:leave-end="opacity-0 scale-95">

                                                    <!-- Modal header -->
                                                    <div
                                                        class="flex items-center justify-between p-6 border-b border-gray-200">
                                                        <h3 class="text-xl font-semibold text-gray-900">Edit Document
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
                                                        <form method="POST"
                                                            action="{{ route('index.update', $archive->idrec) }}"
                                                            enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')

                                                            <!-- Grid layout -->
                                                            <div class="grid gap-6 mb-6 md:grid-cols-2">
                                                                <!-- Date input -->
                                                                <div class="relative">
                                                                    <input type="date" name="date"
                                                                        id="date" required
                                                                        class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                        value="{{ old('date', $archive->date) }}" />
                                                                    <label for="date"
                                                                        class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                                                        Select Date
                                                                    </label>
                                                                </div>

                                                                <!-- Document number -->
                                                                <div class="relative flex items-center">
                                                                    <div class="relative flex-1">
                                                                        <input name="id_document" id="id_document"
                                                                            required
                                                                            class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                            placeholder=" "
                                                                            value="{{ old('document', $archive->no_document) }}"
                                                                            onkeypress="return event.key !== ' '"
                                                                            oninput="this.value = this.value.replace(/\s/g, '')" />
                                                                        <label for="id_document"
                                                                            class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                                                            Document Number
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Second row -->
                                                            <div class="grid gap-6 mb-6 md:grid-cols-4">
                                                                <!-- Department dropdown -->
                                                                <div class="relative">
                                                                    <select name="dep" id="dep" required
                                                                        class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                                                                        <option value="" selected>Select
                                                                            Department</option>
                                                                        @foreach ($deps as $department)
                                                                            <option value="{{ $department->id }}"
                                                                                @if (isset($archive->subDepartment) && $department->id == $archive->subDepartment->parent->id) selected @endif>
                                                                                {{ $department->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <label for="dep"
                                                                        class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                                                        Department
                                                                    </label>
                                                                </div>

                                                                <!-- Sub Department dropdown -->
                                                                <div class="relative">
                                                                    <select name="sub_dep" id="sub_dep" required
                                                                        class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                                                                        <option value="" disabled selected>Select
                                                                            Sub Department</option>
                                                                        @if (isset($archive->subDepartment) && isset($subDeps))
                                                                            @foreach ($subDeps as $subDep)
                                                                                <option value="{{ $subDep->id }}"
                                                                                    @if ($archive->subDepartment->id == $subDep->id) selected @endif>
                                                                                    {{ $subDep->name }}
                                                                                </option>
                                                                            @endforeach
                                                                        @endif
                                                                    </select>
                                                                    <label for="sub_dep"
                                                                        class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                                                        Sub Department
                                                                    </label>
                                                                </div>

                                                                <!-- Document type dropdown -->
                                                                <div class="relative">
                                                                    <select name="type_docs_modal"
                                                                        id="type_docs_modal" required
                                                                        class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                                                                        <option value="" data-code="">Select
                                                                            Document Type</option>
                                                                        @foreach ($documentTypes as $docType)
                                                                            <option value="{{ $docType->name }}"
                                                                                data-code="{{ $docType->code }}"
                                                                                {{ old('type_docs_modal', $archive->doc_type ?? '') == $docType->name ? 'selected' : '' }}>
                                                                                {{ $docType->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <label for="type_docs_modal"
                                                                        class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                                                        Type Document
                                                                    </label>
                                                                </div>  

                                                                <!-- User email dropdown -->
                                                                <div class="relative">
                                                                    <select name="user_email" id="user_email" required
                                                                        class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer">
                                                                        <option value="" disabled selected>Select
                                                                            User Email</option>
                                                                        @foreach ($users as $user)
                                                                            <option value="{{ $user->id }}"
                                                                                @if ($archive->created_by == $user->id) selected @endif>
                                                                                {{ $user->name }}
                                                                                ({{ $user->email }})
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <label for="user_email"
                                                                        class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                                                        User Email
                                                                    </label>
                                                                </div>
                                                            </div>

                                                            <!-- Description input -->
                                                            <div class="mb-6">
                                                                <div class="relative">
                                                                    <textarea name="description" id="description" required rows="3"
                                                                        class="block px-2.5 pb-2.5 pt-4 w-full text-sm text-gray-900 bg-transparent rounded-lg border border-gray-300 appearance-none focus:outline-none focus:ring-0 focus:border-blue-600 peer"
                                                                        placeholder=" ">{{ old('description', $archive->description) }}</textarea>
                                                                    <label for="description"
                                                                        class="absolute text-sm text-gray-500 duration-300 transform -translate-y-4 scale-75 top-2 z-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-7 peer-placeholder-shown:top-6 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 left-1">
                                                                        Description
                                                                    </label>
                                                                </div>
                                                            </div>

                                                            <!-- File upload with drag and drop -->
                                                            <input type="hidden" name="file_names"
                                                                id="file_names_input" x-model="fileNames">
                                                            <!-- File upload section -->
                                                            <div class="mb-6">
                                                                <!-- Show dropzone only if no files are uploaded -->
                                                                <div x-show="files.length === 0"
                                                                    class="relative group">
                                                                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center transition-all duration-200 hover:border-blue-400 hover:bg-blue-50/50"
                                                                        id="dropzone" @dragover.prevent
                                                                        @dragenter.prevent @dragleave.prevent
                                                                        @drop.prevent="handleDrop($event)"
                                                                        x-bind:class="{ 'border-blue-500 bg-blue-50/50': isDragging }">
                                                                        <input type="file" name="files[]"
                                                                            id="files" class="hidden" multiple
                                                                            @change="handleFiles($event)"
                                                                            accept="application/pdf, image/jpeg, image/jpg" />
                                                                        <div
                                                                            class="flex flex-col items-center justify-center space-y-3">
                                                                            <div class="p-3 bg-blue-100 rounded-full">
                                                                                <svg class="w-8 h-8 text-blue-600"
                                                                                    fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                                                </svg>
                                                                            </div>
                                                                            <div class="flex text-sm text-gray-600">
                                                                                <label for="files"
                                                                                    class="relative cursor-pointer font-medium text-blue-600 hover:text-blue-500">
                                                                                    <span>Upload a file</span>
                                                                                    <input id="files"
                                                                                        name="files[]" type="file"
                                                                                        class="sr-only" multiple>
                                                                                </label>
                                                                                <p class="pl-1">or drag and drop</p>
                                                                            </div>
                                                                            <p class="text-xs text-gray-500">
                                                                                PDF, JPG up to 25MB
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Show uploaded files -->
                                                                <div id="file-names" class="mt-4 space-y-2">
                                                                    <template x-for="(file, index) in files"
                                                                        :key="index">
                                                                        <div
                                                                            class="flex items-center justify-between p-3 bg-blue-50 rounded-lg border border-blue-100">
                                                                            <div class="flex items-center space-x-3">
                                                                                <svg class="w-6 h-6 text-blue-500"
                                                                                    fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                                                </svg>
                                                                                <span
                                                                                    class="text-sm font-medium text-gray-700 truncate max-w-xs"
                                                                                    x-text="file.name"></span>
                                                                            </div>
                                                                            <button type="button"
                                                                                class="text-red-500 hover:text-red-700"
                                                                                @click="removeFile(index)">
                                                                                <svg class="w-5 h-5" fill="none"
                                                                                    stroke="currentColor"
                                                                                    viewBox="0 0 24 24">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                                                </svg>
                                                                            </button>
                                                                        </div>
                                                                    </template>
                                                                </div>
                                                            </div>

                                                            <!-- Form actions -->
                                                            <div
                                                                class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                                                                <button type="button"
                                                                    @click="modalOpenDetail = false"
                                                                    class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                                                    Cancel
                                                                </button>
                                                                <button type="submit"
                                                                    class="px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 flex items-center">
                                                                    <svg x-show="isSubmitting"
                                                                        class="animate-spin -ml-1 mr-2 h-4 w-4 text-white"
                                                                        xmlns="http://www.w3.org/2000/svg"
                                                                        fill="none" viewBox="0 0 24 24">
                                                                        <circle class="opacity-25" cx="12"
                                                                            cy="12" r="10"
                                                                            stroke="currentColor" stroke-width="4">
                                                                        </circle>
                                                                        <path class="opacity-75" fill="currentColor"
                                                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                                        </path>
                                                                    </svg>
                                                                    <span>Update Document</span>
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

            <!-- Modern Pagination -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="px-6 py-4">
                    {{ $archives->onEachSide(1)->links('vendor.pagination.tailwind') }}
                </div>
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
            Alpine.data('modal', (document) => ({
                modalOpenDetail: false,
                files: [],
                isDragging: false,
                fileUploaded: false,
                document: document,
                fileNames: document.pdfblob ? [document.file_name] : [],
                isSubmitting: false,

                init() {
                    // Jika dokumen sudah memiliki file, tambahkan ke array files
                    if (document.file_name) {
                        this.files.push({
                            name: document.file_name
                        });
                    }
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
                            if (file.type === 'application/pdf' && file.size <= 25 * 1024 * 1024) {
                                this.files.push(file);
                                this.fileUploaded = true;
                                this.updateFileNames();

                                Toastify({
                                    text: "File uploaded successfully!",
                                    duration: 3000,
                                    close: true,
                                    gravity: "top",
                                    position: "right",
                                    style: {
                                        background: "#4CAF50"
                                    }
                                }).showToast();

                                break;
                            } else {
                                Toastify({
                                    text: `File ${file.name} invalid or exceeds 25MB.`,
                                    duration: 3000,
                                    close: true,
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
                            text: "Only one file can be uploaded.",
                            duration: 3000,
                            close: true,
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
                    this.fileNames = fileNames;
                    document.getElementById('file_names_input').value = fileNames;
                },

                submitForm() {
                    this.isSubmitting = true;
                    // Your form submission logic here
                }
            }));
        });

        function openPdfInNewTab(base64Data) {
            if (base64Data) {
                const byteCharacters = atob(base64Data);
                const byteNumbers = new Array(byteCharacters.length);
                for (let i = 0; i < byteCharacters.length; i++) {
                    byteNumbers[i] = byteCharacters.charCodeAt(i);
                }
                const byteArray = new Uint8Array(byteNumbers);
                const blob = new Blob([byteArray], {
                    type: 'application/pdf'
                });
                const blobUrl = URL.createObjectURL(blob);
                window.open(blobUrl, '_blank');
                URL.revokeObjectURL(blobUrl);
            } else {
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

        document.addEventListener('DOMContentLoaded', function() {
            const departmentSelect = document.getElementById('dep');
            const subDepartmentSelect = document.getElementById('sub_dep');

            departmentSelect.addEventListener('change', function() {
                const departmentId = this.value;
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
    </script>
</x-app-layout>
