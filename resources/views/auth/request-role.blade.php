@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Ajukan Role</h2>
    <p>Akun Anda belum memiliki akses. Silakan ajukan role agar admin dapat memverifikasinya.</p>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('request.role') }}" method="POST">
        @csrf
        <label for="role">Pilih Role:</label>
        <select name="requested_role" required class="form-control">
            <option value="warehouse_manager">Manajer Gudang</option>
            <option value="warehouse_staff">Staff Gudang</option>
        </select>
        <button type="submit" class="btn btn-primary mt-3">Ajukan</button>
    </form>
</div>
@endsection
