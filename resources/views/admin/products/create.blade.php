@extends('layouts.admin')
@section('page-title', 'Tambah Produk')
@section('content')
<a href="{{ route('admin.products.index') }}" class="text-amber text-sm hover:underline mb-4 inline-block">← Kembali</a>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @include('admin.products._form')
</form>
@endsection
