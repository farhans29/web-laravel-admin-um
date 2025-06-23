<div>
    <div>
        <label class="text-sm">Tanggal Mulai</label>
        <input type="text" x-ref="startDate" class="border px-4 py-2 rounded w-full" placeholder="Tanggal Mulai" readonly>
    </div>
    <div>
        <label class="text-sm">Tanggal Akhir</label>
        <input type="text" x-ref="endDate" class="border px-4 py-2 rounded w-full" placeholder="Tanggal Akhir" readonly>
    </div>
    <div>
        <label class="text-sm">Harga</label>
        <input type="number" x-model="setPrice" class="border px-4 py-2 rounded w-full" placeholder="Masukkan Harga">
    </div>
    <div class="flex justify-between items-center pt-2">
        <button @click="updatePrice" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Update</button>
        <button @click="$el.remove()" class="text-sm text-gray-500 hover:underline">Close</button>
    </div>
</div>
