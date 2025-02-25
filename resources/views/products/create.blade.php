<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk Baru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom input styles to prevent background color change */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px #2d3748 inset !important;
            -webkit-text-fill-color: #fff !important;
        }

        input,
        select {
            background-color: #2d3748 !important;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('/img/box.jpg');">
    <div class="bg-gray-800 bg-opacity-60 p-6 rounded-2xl shadow-lg w-full max-w-md animate-fadeIn">
        <h1 class="text-xl font-bold text-white text-center mb-6">Tambah Produk Baru</h1>

        <form action="{{ route('products.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-300">Nama Produk</label>
                <input type="text" name="name" id="name" 
                    class="w-full mt-1 p-2 rounded-lg bg-opacity-80 border border-gray-600 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 transition-all" 
                    value="{{ old('name') }}" required>
            </div>

            <div>
                <label for="sku" class="block text-sm font-medium text-gray-300">SKU</label>
                <input type="text" name="sku" id="sku" 
                    class="w-full mt-1 p-2 rounded-lg bg-opacity-80 border border-gray-600 text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 transition-all" 
                    value="{{ old('sku') }}" required>
            </div>

            <div>
                <label for="category_id" class="block text-sm font-medium text-white">Kategori</label>
                <select name="category_id" id="category_id" 
                    class="w-full mt-1 p-2 rounded-lg bg-opacity-80 border border-gray-500 text-white focus:ring-2 focus:ring-blue-500 transition-all" 
                    required>
                    <option value="">Pilih Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="supplier_id" class="block text-sm font-medium text-white">Supplier</label>
                <select name="supplier_id" id="supplier_id" 
                    class="w-full mt-1 p-2 rounded-lg bg-opacity-80 border border-gray-500 text-white focus:ring-2 focus:ring-blue-500 transition-all">
                    <option value="">Pilih Supplier</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="purchase_price" class="block text-sm font-medium text-white">Harga Beli</label>
                <input type="number" name="purchase_price" id="purchase_price" 
                    class="w-full mt-1 p-2 rounded-lg bg-opacity-80 border border-gray-500 text-white focus:ring-2 focus:ring-blue-500 transition-all" 
                    value="{{ old('purchase_price') }}" step="100" min="0" required>
            </div>

            <div>
                <label for="sale_price" class="block text-sm font-medium text-white">Harga Jual</label>
                <input type="number" name="sale_price" id="sale_price" 
                    class="w-full mt-1 p-2 rounded-lg bg-opacity-80 border border-gray-500 text-white focus:ring-2 focus:ring-blue-500 transition-all" 
                    value="{{ old('sale_price') }}" step="100" min="0" required>
            </div>

            <div>
                <label for="stock" class="block text-sm font-medium text-white">Stok</label>
                <input type="number" name="stock" id="stock" 
                    class="w-full mt-1 p-2 rounded-lg bg-opacity-80 border border-gray-500 text-white focus:ring-2 focus:ring-blue-500 transition-all" 
                    value="{{ old('stock') }}" min="0" required>
            </div>

            <div>
    <label for="minimum_stock" class="block text-sm font-medium text-white">Minimum Stok</label>
    <input type="number" name="minimum_stock" id="minimum_stock" 
        class="w-full mt-1 p-2 rounded-lg bg-opacity-80 border border-gray-500 text-white focus:ring-2 focus:ring-blue-500 transition-all" 
        value="{{ old('minimum_stock', 5) }}" min="0" required>
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
</body>
</html>
