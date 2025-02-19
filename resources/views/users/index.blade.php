@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Daftar Pengguna</h1>
    <a href="{{ route('users.create') }}" class="bg-blue-500 text-white py-2 px-4 rounded mb-4 inline-block">Tambah Pengguna</a>
    <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
        <thead class="bg-gray-800 text-white">
            <tr>
                <th class="py-3 px-4 text-left">Nama</th>
                <th class="py-3 px-4 text-left">Email</th>
                <th class="py-3 px-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr class="border-b">
                    <td class="py-3 px-4">{{ $user->name }}</td>
                    <td class="py-3 px-4">{{ $user->email }}</td>
                    <td class="py-3 px-4 text-center">
                        <div class="inline-flex gap-2">
                            <a href="{{ route('users.edit', $user->id) }}" class="bg-yellow-500 text-white py-1 px-4 rounded hover:bg-yellow-600">Edit</a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white py-1 px-4 rounded hover:bg-red-600">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
