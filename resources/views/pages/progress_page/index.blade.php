<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8 px-4">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-4">Test Property Images</h1>
                <p class="text-gray-600 mb-6">Testing image display from storage</p>
                
                <div class="flex justify-center space-x-4 mb-6">
                    <a href="{{ route('dashboard') }}" class="inline-block">
                        <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Kembali ke Dashboard
                        </button>
                    </a>
                    
                    <!-- Button Force Storage Link -->
                    <button onclick="forceStorageLink()" 
                            class="px-6 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition">
                        Force Storage Link
                    </button>
                    
                    <!-- Button Check Storage -->
                    <button onclick="checkStorage()" 
                            class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                        Check Storage
                    </button>
                </div>
            </div>

            <!-- Storage Link Status -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 {{ $storageLinkResult['success'] ? 'text-green-500' : 'text-red-500' }}" 
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Storage Link Status
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="p-3 rounded-lg {{ $storageLinkResult['link_exists'] ? 'bg-green-100 border border-green-200' : 'bg-red-100 border border-red-200' }}">
                        <div class="flex items-center">
                            <span class="font-semibold mr-2">Storage Link:</span>
                            <span class="{{ $storageLinkResult['link_exists'] ? 'text-green-700' : 'text-red-700' }}">
                                {{ $storageLinkResult['link_exists'] ? 'Exists ✓' : 'Missing ✗' }}
                            </span>
                        </div>
                        @if($storageLinkResult['link_exists'])
                        <code class="text-xs text-green-600 mt-1 block">
                            {{ public_path('storage') }} → {{ storage_path('app/public') }}
                        </code>
                        @endif
                    </div>
                    
                    <div class="p-3 rounded-lg {{ $storageLinkResult['target_exists'] ? 'bg-green-100 border border-green-200' : 'bg-red-100 border border-red-200' }}">
                        <div class="flex items-center">
                            <span class="font-semibold mr-2">Storage Path:</span>
                            <span class="{{ $storageLinkResult['target_exists'] ? 'text-green-700' : 'text-red-700' }}">
                                {{ $storageLinkResult['target_exists'] ? 'Exists ✓' : 'Missing ✗' }}
                            </span>
                        </div>
                        <code class="text-xs text-green-600 mt-1 block">
                            {{ storage_path('app/public') }}
                        </code>
                    </div>
                </div>
                
                <div class="p-3 bg-blue-50 rounded-lg border border-blue-200">
                    <span class="font-semibold text-blue-800">Message:</span>
                    <span class="text-blue-700 ml-2">{{ $storageLinkResult['message'] }}</span>
                </div>
            </div>

            <!-- Images Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="imagesGrid">
                @forelse($images as $image)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Image -->
                    <div class="h-64 bg-gray-200 flex items-center justify-center overflow-hidden">
                        @if($image->image_url && $storageLinkResult['link_exists'])
                            <img src="{{ $image->image_url }}" 
                                 alt="{{ $image->caption ?? 'Property Image' }}"
                                 class="w-full h-full object-cover hover:scale-105 transition duration-300 cursor-pointer"
                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                 onclick="showImageModal('{{ $image->image_url }}')">
                            <div class="hidden flex-col items-center justify-center text-center p-4 text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                Image failed to load
                            </div>
                        @else
                            <div class="text-gray-500 text-center p-4">
                                <svg class="w-12 h-12 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                No Image Available
                            </div>
                        @endif
                    </div>
                    
                    <!-- Image Info -->
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2">Image ID: {{ $image->idrec }}</h3>
                        
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex justify-between">
                                <span>Property ID:</span>
                                <span class="font-medium">{{ $image->property_id }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span>Storage Exists:</span>
                                <span class="font-medium {{ Storage::exists($image->image) ? 'text-green-600' : 'text-red-600' }}">
                                    {{ Storage::exists($image->image) ? 'Yes ✓' : 'No ✗' }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Image URLs -->
                        <div class="mt-4 space-y-2">
                            <div class="bg-gray-50 p-2 rounded text-xs">
                                <strong>Storage Path:</strong> 
                                <code class="break-all">{{ $image->image }}</code>
                            </div>
                            
                            <div class="bg-blue-50 p-2 rounded text-xs">
                                <strong>Access URL:</strong> 
                                <a href="{{ $image->image_url }}" target="_blank" 
                                   class="text-blue-600 hover:underline break-all {{ !$storageLinkResult['link_exists'] ? 'opacity-50' : '' }}">
                                    {{ $image->image_url }}
                                </a>
                            </div>
                        </div>
                        
                        <!-- Test Buttons -->
                        <div class="mt-4 flex space-x-2">
                            <button onclick="testImage({{ $image->idrec }})" 
                                    class="flex-1 bg-blue-600 text-white py-2 px-3 rounded hover:bg-blue-700 transition text-sm">
                                Test API
                            </button>
                            <a href="{{ $image->image_url }}" target="_blank" 
                               class="flex-1 bg-green-600 text-white py-2 px-3 rounded hover:bg-green-700 transition text-sm text-center {{ !$storageLinkResult['link_exists'] ? 'pointer-events-none opacity-50' : '' }}">
                                Open Image
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-600">No Images Found</h3>
                    <p class="text-gray-500 mt-2">No property images available for testing.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden">
        <div class="max-w-4xl max-h-full p-4">
            <div class="bg-white rounded-lg overflow-hidden">
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-lg font-semibold">Image Preview</h3>
                    <button onclick="closeImageModal()" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="p-4">
                    <img id="modalImage" src="" alt="Preview" class="max-w-full max-h-96 object-contain" onerror="this.src='//via.placeholder.com/400x300?text=Image+Not+Found'">
                </div>
            </div>
        </div>
    </div>

    <script>
        function showImageModal(imageUrl) {
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        async function testImage(imageId) {
            try {
                const response = await fetch(`/test/image/${imageId}`);
                const data = await response.json();
                
                if (data.success) {
                    alert('✅ Image test successful!\n\nURL: ' + data.image_url + 
                          '\nThumbnail URL: ' + data.thumbnail_url +
                          '\nStorage Exists: ' + (data.storage_exists ? 'Yes' : 'No'));
                } else {
                    alert('❌ Image test failed!');
                }
            } catch (error) {
                alert('❌ Error testing image: ' + error.message);
            }
        }

        async function forceStorageLink() {
            if (!confirm('This will recreate the storage link. Continue?')) return;
            
            try {
                const response = await fetch('/test/storage/link', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('✅ ' + data.message);
                    location.reload();
                } else {
                    alert('❌ ' + data.message);
                }
            } catch (error) {
                alert('❌ Error: ' + error.message);
            }
        }

        async function checkStorage() {
            try {
                const response = await fetch('/test/storage/check');
                const data = await response.json();
                
                let message = 'Storage Check Results:\n\n';
                message += `📁 Storage Link: ${data.storage_link_exists ? 'Exists ✓' : 'Missing ✗'}\n`;
                message += `📂 Storage Path: ${data.storage_path_exists ? 'Exists ✓' : 'Missing ✗'}\n`;
                message += `✏️ Public Writable: ${data.public_storage_writable ? 'Yes ✓' : 'No ✗'}\n`;
                message += `✏️ Storage Writable: ${data.storage_app_public_writable ? 'Yes ✓' : 'No ✗'}\n`;
                
                if (data.storage_link_exists) {
                    message += `\n🔗 Link Target: ${data.link_target}\n`;
                    message += `🎯 Expected: ${data.expected_target}\n`;
                    message += `✅ Match: ${data.link_target === data.expected_target ? 'Yes ✓' : 'No ✗'}`;
                }
                
                alert(message);
            } catch (error) {
                alert('❌ Error checking storage: ' + error.message);
            }
        }

        // Close modal on ESC key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeImageModal();
            }
        });

        // Close modal on background click
        document.getElementById('imageModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeImageModal();
            }
        });
    </script>
</x-app-layout>