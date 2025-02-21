@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Verifikasi Email Anda</h2>

    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <p>Silakan cek email Anda untuk tautan verifikasi.</p>
    <p>Jika Anda belum menerima email, klik tombol di bawah untuk mengirim ulang.</p>

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn btn-primary">Kirim Ulang Verifikasi</button>
    </form>

    <br>
    <p><a href="{{ route('logout') }}" 
          onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          Logout
      </a></p>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</div>
@endsection
