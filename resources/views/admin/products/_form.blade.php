@php $p = $product ?? null; @endphp

<div class="grid md:grid-cols-3 gap-6">
    <div class="md:col-span-2 space-y-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="font-heading font-semibold text-navy mb-4">Informasi Produk</h3>
            <div class="space-y-4">
                <div>
                    <label class="form-label">Nama Produk <span class="required">*</span></label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', $p?->name) }}" required>
                    @error('name')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="form-label">Kategori <span class="required">*</span></label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $p?->category_id)==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label">Deskripsi Singkat</label>
                    <textarea name="short_description" class="form-textarea" rows="2">{{ old('short_description', $p?->short_description) }}</textarea>
                </div>
                <div>
                    <label class="form-label">Deskripsi Lengkap</label>
                    <textarea name="description" class="form-textarea" rows="5">{{ old('description', $p?->description) }}</textarea>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="font-heading font-semibold text-navy mb-4">Harga & Stok</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Harga (Rp) <span class="required">*</span></label>
                    <input type="number" name="price" class="form-input" value="{{ old('price', $p?->price) }}" required min="0">
                </div>
                <div>
                    <label class="form-label">Harga Asli (coret)</label>
                    <input type="number" name="original_price" class="form-input" value="{{ old('original_price', $p?->original_price) }}" min="0">
                </div>
                <div>
                    <label class="form-label">Stok <span class="required">*</span></label>
                    <input type="number" name="stock" class="form-input" value="{{ old('stock', $p?->stock ?? 0) }}" required min="0">
                </div>
                <div>
                    <label class="form-label">Berat (gram) <span class="required">*</span></label>
                    <input type="number" name="weight" class="form-input" value="{{ old('weight', $p?->weight ?? 0) }}" required min="0">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="font-heading font-semibold text-navy mb-4">Gambar Produk</h3>
            <input type="file" name="images[]" multiple accept="image/*" class="form-input">
            <p class="text-xs text-gray-400 mt-2">Maksimal 2MB per gambar. Format: JPG, PNG, WebP.</p>
            @if($p && $p->images)
                <div class="flex gap-3 mt-4">
                    @foreach($p->images as $img)
                        <img src="{{ $img }}" class="w-20 h-20 rounded-lg object-cover border">
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <h3 class="font-heading font-semibold text-navy mb-4">Status</h3>
            <div class="space-y-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" class="w-4 h-4 rounded text-amber" {{ old('is_active', $p?->is_active ?? true)?'checked':'' }}>
                    <span class="text-sm">Aktif (tampil di toko)</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_featured" value="1" class="w-4 h-4 rounded text-amber" {{ old('is_featured', $p?->is_featured)?'checked':'' }}>
                    <span class="text-sm">Produk Unggulan</span>
                </label>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_sale" value="1" class="w-4 h-4 rounded text-amber" {{ old('is_sale', $p?->is_sale)?'checked':'' }}>
                    <span class="text-sm">Sedang Sale</span>
                </label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg btn-block">
            {{ $p ? 'Simpan Perubahan' : 'Tambah Produk' }}
        </button>
    </div>
</div>
