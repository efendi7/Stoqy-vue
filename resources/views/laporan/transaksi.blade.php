@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Laporan Transaksi Barang</h1>

    <div class="overflow-x-auto">
        <table class="w-full border border-gray-300">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="border p-2">Produk</th>
                    <th class="border p-2">Jumlah</th>
                    <th class="border p-2">Tipe Transaksi</th>
                    <th class="border p-2">Tanggal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaksi as $item)
                <tr class="border text-gray-800">
                    <td class="border p-2">{{ $item->product->name }}</td>
                    <td class="border p-2 text-center">{{ $item->quantity }}</td>
                    <td class="border p-2 text-center">
                        <span class="px-2 py-1 rounded text-white
                            {{ $item->transaction_type == 'masuk' ? 'bg-green-500' : 'bg-red-500' }}">
                            {{ ucfirst($item->transaction_type) }}
                        </span>
                    </td>
                    <td class="border p-2 text-center">{{ $item->created_at->format('d-m-Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
