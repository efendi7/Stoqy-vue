@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Laporan Barang Masuk dan Keluar</h1>

    <h2>Barang Masuk</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Jumlah</th>
                <th>Tanggal Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($incomingTransactions as $transaction)
                <tr>
                    <td>{{ $transaction->product->name }}</td>
                    <td>{{ $transaction->quantity }}</td>
                    <td>{{ $transaction->transaction_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Barang Keluar</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Jumlah</th>
                <th>Tanggal Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($outgoingTransactions as $transaction)
                <tr>
                    <td>{{ $transaction->product->name }}</td>
                    <td>{{ $transaction->quantity }}</td>
                    <td>{{ $transaction->transaction_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
