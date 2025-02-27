@extends('layouts.app')

@section('content')
@php
    $setting = App\Models\Setting::first();
@endphp

<div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">General Settings</h2>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Application Name -->
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Application Name</label>
            <input type="text" name="app_name" value="{{ $setting->app_name ?? config('app.name') }}" 
                   class="w-full border rounded-lg px-3 py-2">
        </div>

        <!-- Application Logo -->
        <div class="mb-4">
            <label class="block text-gray-700 font-bold mb-2">Application Logo</label>
            <input type="file" name="app_logo" class="w-full border rounded-lg px-3 py-2">
            
            @if ($setting && $setting->logo)
                <img src="{{ asset('storage/' . $setting->logo) }}" class="mt-2 w-24 h-24 rounded">
            @else
                <img src="{{ asset('img/default-logo.png') }}" class="mt-2 w-24 h-24 rounded">
            @endif
        </div>

        <button type="submit" class="bg-purple-700 text-white px-4 py-2 rounded">Save Changes</button>
    </form>
</div>
@endsection
