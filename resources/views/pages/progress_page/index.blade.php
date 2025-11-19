<x-app-layout>
    <div class="min-h-screen bg-gray-50 py-8 px-4">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-4">Test Property Images</h1>
                <p class="text-gray-600 mb-6">Testing image display from storage</p>
                
                <!-- Button untuk kembali -->
                <a href="{{ route('dashboard') }}" class="inline-block mb-6">
                    <button class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Kembali ke Dashboard
                    </button>
                </a>
            </div>

            <!-- Info Storage -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <h2 class="text-xl font-semibold mb-4">Storage Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="p-3 bg-blue-50 rounded">
                        <strong>Storage Path:</strong> 
                        <code class="text-xs">{{ storage_path('app/public') }}</code>
                    </div>
                    <div class="p-3 bg-green-50 rounded">
                        <strong>Public Path:</strong> 
                        <code class="text-xs">{{ public_path() }}</code>
                    </div>
                    <div class="p-3 bg-yellow-50 rounded">
                        <strong>APP_URL:</strong> 
                        <code class="text-xs">{{ config('app.url') }}</code>
                    </div>
                </div>
            </div>

            <!-- Images Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($images as $image)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <!-- Image -->
                    <div class="h-64 bg-gray-200 flex items-center justify-center overflow-hidden">
                        @if($image->image_url)
                            <img src="{{ $image->image_url }}" 
                                 alt="{{ $image->caption ?? 'Property Image' }}"
                                 class="w-full h-full object-cover hover:scale-105 transition duration-300 cursor-pointer"
                                 onclick="showImageModal('{{ $image->image_url }}')">
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
                                <span>Thumbnail:</span>
                                <span class="font-medium {{ $image->thumbnail ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $image->thumbnail ? 'Yes' : 'No' }}
                                </span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span>Caption:</span>
                                <span class="font-medium">{{ $image->caption ?? 'N/A' }}</span>
                            </div>
                        </div>
                        
                        <!-- Image URLs -->
                        <div class="mt-4 space-y-2">
                            <div class="bg-gray-50 p-2 rounded text-xs">
                                <strong>Image Path:</strong> 
                                <code class="break-all">{{ $image->image }}</code>
                            </div>
                            
                            <div class="bg-blue-50 p-2 rounded text-xs">
                                <strong>Full URL:</strong> 
                                <a href="{{ $image->image_url }}" target="_blank" class="text-blue-600 hover:underline break-all">
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
                               class="flex-1 bg-green-600 text-white py-2 px-3 rounded hover:bg-green-700 transition text-sm text-center">
                                Open Image
                            </a>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200">
    <p class="text-sm text-gray-500 mb-4">Testing Image Storage</p>
    <a href="{{ route('test.images') }}" class="inline-block">
        <button class="px-6 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition duration-300 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 shadow">
            Test Images
        </button>
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
                    <img id="modalImage" src="" alt="Preview" class="max-w-full max-h-96 object-contain">
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
                    alert('Image test successful!\n\nURL: ' + data.image_url + 
                          '\nThumbnail URL: ' + data.thumbnail_url);
                } else {
                    alert('Image test failed!');
                }
            } catch (error) {
                alert('Error testing image: ' + error.message);
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

    <style>
        .break-all {
            word-break: break-all;
        }
    </style>
</x-app-layout>