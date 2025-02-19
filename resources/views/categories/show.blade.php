@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Detail Kategori</h1>
    <div class="bg-white shadow-md rounded p-4">
        <h2 class="text-xl font-semibold">{{ $category->name }}</h2>
        <a href="{{ route('categories.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md mt-4">Kembali</a>
    </div>
</div>
@endsection
