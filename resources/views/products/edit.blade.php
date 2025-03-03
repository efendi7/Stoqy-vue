<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
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

        /* Fade-in animation */
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
</head>
<body class="min-h-screen flex items-center justify-center bg-cover bg-center">
    <div class="bg-white bg-opacity-70 p-6 rounded-2xl shadow-xl w-full max-w-md animate-fadeIn">
        <h1 class="text-xl font-bold text-gray-800 text-center mb-6">Edit Produk</h1>

        <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT') <!-- Method override for PUT request -->

            <!-- Errors Display -->
            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Product Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-800">Nama Produk</label>
                <input type="text" name="name" id="name" 
                    class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 transition-all" 
                    value="{{ old('name', $product->name) }}" required>
            </div>

            <!-- SKU -->
            <div>
                <label for="sku" class="block text-sm font-medium text-gray-800">SKU</label>
                <input type="text" name="sku" id="sku" 
                    class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 transition-all" 
                    value="{{ old('sku', $product->sku) }}" required>
            </div>

            <!-- Category -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-800">Kategori</label>
                <select name="category_id" id="category_id" 
                    class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all" 
                    required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Supplier -->
            <div>
                <label for="supplier_id" class="block text-sm font-medium text-gray-800">Supplier</label>
                <select name="supplier_id" id="supplier_id" 
                    class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all">
                    <option value="">Pilih Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ $product->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Purchase Price -->
            <div>
                <label for="purchase_price" class="block text-sm font-medium text-gray-800">Harga Beli</label>
                <input type="number" name="purchase_price" id="purchase_price" 
                    class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all" 
                    value="{{ old('purchase_price', $product->purchase_price) }}" step="100" min="0" required>
            </div>

            <!-- Sale Price -->
            <div>
                <label for="sale_price" class="block text-sm font-medium text-gray-800">Harga Jual</label>
                <input type="number" name="sale_price" id="sale_price" 
                    class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all" 
                    value="{{ old('sale_price', $product->sale_price) }}" step="100" min="0" required>
            </div>

            <!-- Stock -->
            <div>
                <label for="stock" class="block text-sm font-medium text-gray-800">Stok</label>
                <input type="number" name="stock" id="stock" 
                    class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all" 
                    value="{{ old('stock', $product->stock) }}" min="0" required>
            </div>

            <!-- Minimum Stock -->
            <div>
                <label for="minimum_stock" class="block text-sm font-medium text-gray-800">Minimum Stok</label>
                <input type="number" name="minimum_stock" id="minimum_stock" 
                    class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all" 
                    value="{{ old('minimum_stock', $product->minimum_stock) }}" min="0" required>
            </div>

            <!-- Product Image -->
            <div>
                <label for="image" class="block text-sm font-medium text-gray-800">Gambar Produk</label>
                <input type="file" name="image" id="image" class="w-full mt-1 p-2 rounded-lg border border-gray-300">
                @if ($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" class="mt-2 w-32 h-32 object-cover">
                @endif
                <p id="image-error" class="text-red-500 text-sm mt-1 hidden"></p>
            </div>

            <!-- Submit Button -->
            <button type="submit" 
                class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition-all transform hover:scale-105">Update</button>
        </form>
    </div>

    <script>
        // Image Validation
        document.getElementById('image').addEventListener('change', function(event) {
            const file = event.target.files[0];
            const errorMessage = document.getElementById('image-error');
            errorMessage.classList.add('hidden'); // Hide error initially

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
