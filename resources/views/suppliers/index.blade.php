@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Daftar Supplier</h1>
    <a href="{{ route('suppliers.create') }}" class="bg-blue-500 text-white py-2 px-4 rounded">Tambah Supplier</a>
    <table class="min-w-full leading-normal mt-4">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kontak</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Alamat</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($suppliers as $supplier)
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $supplier->name }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $supplier->contact }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $supplier->address }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="bg-yellow-500 text-white py-1 px-2 rounded">Edit</a>
                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white py-1 px-2 rounded">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
