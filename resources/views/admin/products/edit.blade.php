@extends('layouts.admin')
@section('page-title', 'Edit Produk')
@section('content')
<a href="{{ route('admin.products.index') }}" class="text-amber text-sm hover:underline mb-4 inline-block">← Kembali</a>

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.products._form', ['product' => $product])
</form>
@endsection
