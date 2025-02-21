@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold my-8 text-center">Login</h1>
    
    <!-- Error Messages -->
    @if ($errors->any())
        <div class="alert alert-danger bg-red-100 text-red-700 p-4 rounded-lg mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('login') }}" method="POST" class="bg-white shadow-md rounded-lg p-8">
        @csrf
        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
            <input type="email" name="email" id="email" class="block w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring focus:border-blue-500" required>
        </div>
        <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <input type="password" name="password" id="password" class="block w-full border border-gray-300 rounded-md p-3 focus:outline-none focus:ring focus:border-blue-500" required>
        </div>
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition duration-300">Login</button>
            <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Register</a>
        </div>
    </form>
</div>
@endsection
