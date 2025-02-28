@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-cover bg-center px-4">
    <div class="bg-white bg-opacity-70 p-6 rounded-2xl shadow-xl w-full max-w-md animate-fadeIn">
        <h1 class="text-3xl font-bold text-center mb-6 text-gray-800">Register</h1>
        
        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded-lg mb-4 shadow-md">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-800">Name</label>
                <input type="text" name="name" id="name" 
                    class="w-full mt-1 p-3 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 transition-all" 
                    required>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-800">Email</label>
                <input type="email" name="email" id="email" 
                    class="w-full mt-1 p-3 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 transition-all" 
                    required>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-800">Password</label>
                <input type="password" name="password" id="password" 
                    class="w-full mt-1 p-3 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 transition-all" 
                    required>
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-800">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" 
                    class="w-full mt-1 p-3 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 transition-all" 
                    required>
            </div>
            <button type="submit" 
                class="w-full bg-green-500 text-white py-2 rounded-lg hover:bg-green-600 transition-all transform hover:scale-105">Register</button>
            <div class="text-center mt-4">
                <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Back to Login</a>
            </div>
        </form>

        @if(session('status'))
            <script>
                window.location.href = "{{ route('login') }}";
            </script>
        @endif
    </div>
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
@endsection
