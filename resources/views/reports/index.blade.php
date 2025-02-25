@extends('layouts.app')

@section('content')
<form method="GET" action="{{ route('reports.index') }}">
    <div class="form-group">
        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" id="start_date" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" id="end_date" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Generate Report</button>
</form>

<div class="container mt-4">

    <h1>Laporan Stok Barang</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            @if(isset($stockReports))
                @foreach($stockReports as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>{{ $product->stock }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>

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
            @if(isset($incomingTransactions))
                @foreach($incomingTransactions as $transaction)
                    <tr>
                        <td>{{ $transaction->product->name }}</td>
                        <td>{{ $transaction->quantity }}</td>
                        <td>{{ $transaction->transaction_date }}</td>
                    </tr>
                @endforeach
            @endif
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
            @if(isset($outgoingTransactions))
                @foreach($outgoingTransactions as $transaction)
                    <tr>
                        <td>{{ $transaction->product->name }}</td>
                        <td>{{ $transaction->quantity }}</td>
                        <td>{{ $transaction->transaction_date }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
@endsection
