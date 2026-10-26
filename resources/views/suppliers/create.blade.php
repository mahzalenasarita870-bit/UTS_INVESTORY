@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="card shadow-sm col-md-6 mx-auto">
        <div class="card-header bg-white font-weight-bold">Tambah Supplier Baru</div>
        <div class="card-body">
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Supplier</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">Nomor Telepon</label>
                    <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Alamat</label>
                    <textarea name="address" id="address" class="form-control" rows="3">{{ old('address') }}</textarea>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection