@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Edit Supplier</h1>
    <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Supplier</label>
            <input type="text" name="name" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="name" value="{{ old('name', $supplier->name) }}" required>
        </div>
        <div class="mb-4">
            <label for="contact" class