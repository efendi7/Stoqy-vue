<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        /* Custom input styles to prevent background color change */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px rgba(255, 255, 255, 0.9) inset !important;
            -webkit-text-fill-color: #000 !important;
        }

        input,
        select {
            background-color: rgba(255, 255, 255, 0.5) !important; /* Set transparency */
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-cover bg-center">
    <div class="bg-white bg-opacity-70 p-6 rounded-2xl shadow-xl w-full max-w-md animate-fadeIn">
        <h1 class="text-xl font-bold text-gray-800 text-center mb-6">Tambah Produk Baru</h1>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-800">Nama Produk</label>
                <input type="text" name="name" id="name" 
                    class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 transition-all" 
                    value="{{ old('name') }}" required>
            </div>

            <div>
                <label for="sku" class="block text-sm font-medium text-gray-800">SKU</label>
                <input type="text" name="sku" id="sku" 
                    class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 transition-all" 
                    value="{{ old('sku') }}" required>
            </div>

            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-800">Kategori</label>
                <select name="category_id" id="category_id" 
                    class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all" 
                    required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="supplier_id" class="block text-sm font-medium text-gray-800">Supplier</label>
                <select name="supplier_id" id="supplier_id" 
                    class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all">
                    <option value="">Pilih Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="purchase_price" class="block text-sm font-medium text-gray-800">Harga Beli</label>
                <input type="number" name="purchase_price" id="purchase_price" 
                    class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all" 
                    value="{{ old('purchase_price') }}" step="100" min="0" required>
            </div>

            <div>
                <label for="sale_price" class="block text-sm font-medium text-gray-800">Harga Jual</label>
                <input type="number" name="sale_price" id="sale_price" 
                    class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all" 
                    value="{{ old('sale_price') }}" step="100" min="0" required>
            </div>

            <div>
    <label for="initial_stock" class="block text-sm font-medium text-gray-800">Inisial Stok</label>
    <input type="number" name="initial_stock" id="initial_stock" 
        class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all" 
        value="{{ old('initial_stock', 0) }}" min="0" readonly required>
</div>

<script>
    document.getElementById('stock').addEventListener('input', function() {
        let initialStockInput = document.getElementById('initial_stock');
        initialStockInput.value = this.value;
    });
</script>



            <div>
                <label for="stock" class="block text-sm font-medium text-gray-800">Stok realtime</label>
                <input type="number" name="stock" id="stock" 
                    class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all" 
                    value="{{ old('stock') }}" min="0" required>
            </div>

            <div>
                <label for="minimum_stock" class="block text-sm font-medium text-gray-800">Minimum Stok</label>
                <input type="number" name="minimum_stock" id="minimum_stock" 
                    class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all" 
                    value="{{ old('minimum_stock', 5) }}" min="0" required>
            </div>

            <div>
        <label for="image" class="block text-sm font-medium text-gray-800">Gambar Produk</label>
        <input type="file" name="image" id="image" class="w-full mt-1 p-2 rounded-lg border border-gray-300">
        <p id="image-error" class="text-red-500 text-sm mt-1 hidden"></p>
    </div>

            <button type="submit" 
                class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition-all transform hover:scale-105">Simpan</button>
        </form>
    </div>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.8s ease-out;
        }
    </style>

<script>
    document.getElementById('stock').addEventListener('input', function() {
        document.getElementById('initial_stock').value = this.value;
    });
</script>


<script>
    document.getElementById('image').addEventListener('change', function(event) {
        const file = event.target.files[0];
        const errorMessage = document.getElementById('image-error');
        errorMessage.classList.add('hidden'); // Sembunyikan error saat awal

        if (file) {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            const maxSize = 2 * 1024 * 1024; // 2MB

            if (!allowedTypes.includes(file.type)) {
                errorMessage.textContent = "Format file tidak didukung! (Hanya JPG, PNG, GIF)";
                errorMessage.classList.remove('hidden');
                event.target.value = ''; // Reset input file
            } else if (file.size > maxSize) {
                errorMessage.textContent = "Ukuran file terlalu besar! Maksimal 2MB.";
                errorMessage.classList.remove('hidden');
                event.target.value = ''; // Reset input file
            }
        }
    });
</script>
</body>
</html>
