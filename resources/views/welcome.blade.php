<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .bg-cover-custom {
            background-image: url('{{ asset('img/banner-bg.png') }}');
            background-size: cover;
            background-position: center;
        }
        @keyframes updown {
            0% {
                transform: translateY(-20px);
            }
            50% {
                transform: translateY(20px);
            }
            100% {
                transform: translateY(-20px);
            }
        }
        .animation-updown {
            animation: updown 3s linear infinite;
        }
    </style>
</head>
<body class="bg-cover-custom flex items-center justify-center min-h-screen">
    <div class="container mx-auto p-8">
        <div class="flex flex-col md:flex-row items-center">
            <div class="flex-1 md:w-7/12 text-center md:text-left">
                <h1 class="text-4xl font-bold mb-6 text-white">Welcome to Stockify</h1>
                <a href="{{ route('products.index') }}" class="inline-block bg-blue-500 text-white py-2 px-4 rounded-full shadow-neumorphism focus:outline-none focus:ring-4 focus:ring-blue-300">
                    Ayo Kelola Stok Barangmu
                </a>
                <p class="text-slate-300 text-lg mt-4">
                    Stockify adalah aplikasi web yang dirancang untuk membantu bisnis, khususnya yang memiliki gudang, dalam mengelola stok barang secara efisien dan akurat.
                </p>
            </div>
            <div class="flex-1 mt-8 md:mt-0 md:w-5/12 flex justify-center md:justify-end">
                <img src="{{ asset('img/Ordering Groceries Online.png') }}" alt="Header illustration of a developer" class="animation-updown w-full h-auto max-w-lg" />
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/flowbite@1.4.0/dist/flowbite.js"></script>
</body>
</html>
