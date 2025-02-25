@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Stock Opname</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Kuantitas Tersedia</th>
                <th>Transaksi Masuk</th>
                <th>Transaksi Keluar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->stock_quantity }}</td>
                <td>{{ $transactions->where('product_id', $product->id)->where('type', 'Masuk')->sum('quantity') }}</td>
                <td>{{ $transactions->where('product_id', $product->id)->where('type', 'Keluar')->sum('quantity') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
