@extends('layouts.app')
@section('title', 'Checkout — OlehLampung')
@section('content')
{{-- Steps --}}
<div class="container-main mt-8 mb-10">
    <div class="bg-white rounded-xl border border-gray-200 px-6 py-5 shadow-sm">
        <div class="checkout-steps">
            <span class="checkout-step completed"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> Keranjang</span>
            <span class="checkout-step-divider completed"></span>
            <span class="checkout-step active"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><circle cx="10" cy="10" r="6"/></svg> Checkout</span>
            <span class="checkout-step-divider"></span>
            <span class="checkout-step">Konfirmasi</span>
        </div>
    </div>
</div>

<div class="container-main pb-12">
    <form action="{{ route('checkout.store') }}" method="POST" id="checkout-form">
        @csrf
        <div class="grid md:grid-cols-3 gap-8">
            {{-- Left --}}
            <div class="md:col-span-2 space-y-6">
                {{-- Shipping Info --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="font-heading font-semibold text-navy text-lg mb-5 flex items-center gap-2">
                        <span class="w-6 h-6 bg-amber text-navy rounded-full flex items-center justify-center text-xs font-bold">1</span>
                        Informasi Pengiriman
                    </h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Nama Depan <span class="required">*</span></label>
                            <input type="text" name="first_name" class="form-input" value="{{ old('first_name') }}" required placeholder="Budi">
                            @error('first_name')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="form-label">Nama Belakang <span class="required">*</span></label>
                            <input type="text" name="last_name" class="form-input" value="{{ old('last_name') }}" required placeholder="Santoso">
                            @error('last_name')<p class="text-danger text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="form-label">No. WhatsApp <span class="required">*</span></label>
                        <input type="tel" name="whatsapp" class="form-input" value="{{ old('whatsapp') }}" required placeholder="0812-xxxx-xxxx">
                    </div>
                    <div class="mt-4">
                        <label class="form-label">Email <span class="required">*</span></label>
                        <input type="email" name="email" class="form-input" value="{{ old('email') }}" required placeholder="budi@email.com">
                    </div>
                    <div class="mt-4">
                        <label class="form-label">Alamat Lengkap <span class="required">*</span></label>
                        <textarea name="address" class="form-textarea" rows="2" required placeholder="Jl. Contoh No. 10, RT 01/RW 02, Kelurahan...">{{ old('address') }}</textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="form-label">Kota/Kabupaten <span class="required">*</span></label>
                            <input type="text" name="city" class="form-input" value="{{ old('city') }}" required placeholder="Jakarta Selatan">
                        </div>
                        <div>
                            <label class="form-label">Provinsi <span class="required">*</span></label>
                            <select name="province" class="form-select" required>
                                <option value="">Pilih Provinsi</option>
                                @foreach(['Aceh','Bali','Banten','Bengkulu','DI Yogyakarta','DKI Jakarta','Gorontalo','Jambi','Jawa Barat','Jawa Tengah','Jawa Timur','Kalimantan Barat','Kalimantan Selatan','Kalimantan Tengah','Kalimantan Timur','Kalimantan Utara','Kepulauan Bangka Belitung','Kepulauan Riau','Lampung','Maluku','Maluku Utara','Nusa Tenggara Barat','Nusa Tenggara Timur','Papua','Papua Barat','Riau','Sulawesi Barat','Sulawesi Selatan','Sulawesi Tengah','Sulawesi Tenggara','Sulawesi Utara','Sumatera Barat','Sumatera Selatan','Sumatera Utara'] as $prov)
                                    <option value="{{ $prov }}" {{ old('province')==$prov?'selected':'' }}>{{ $prov }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="form-label">Kode Pos <span class="required">*</span></label>
                            <input type="text" name="postal_code" class="form-input" value="{{ old('postal_code') }}" required placeholder="12345">
                        </div>
                        <div>
                            <label class="form-label">Negara</label>
                            <input type="text" class="form-input bg-gray-50" value="Indonesia" readonly>
                        </div>
                    </div>

                    {{-- Map Pin --}}
                    <div class="mt-6">
                        <label class="form-label">Titik Lokasi Pengiriman <span class="required">*</span></label>
                        <p class="text-xs text-gray-400 mb-2">Klik pada peta atau geser pin untuk menentukan lokasi pengiriman Anda.</p>
                        <div id="shipping-map"></div>
                        <input type="hidden" name="latitude" id="lat-input" value="{{ old('latitude', '-5.4500') }}" required>
                        <input type="hidden" name="longitude" id="lng-input" value="{{ old('longitude', '105.2667') }}" required>
                        <p class="text-xs text-gray-400 mt-1">Koordinat: <span id="coord-display">-5.4500, 105.2667</span></p>
                    </div>

                    <div class="mt-4">
                        <button type="button" class="text-amber text-sm font-medium hover:underline" onclick="document.getElementById('notes-field').classList.toggle('hidden')">+ Tambah catatan untuk penjual</button>
                        <textarea name="notes" id="notes-field" class="form-textarea mt-2 hidden" rows="2" placeholder="Catatan tambahan...">{{ old('notes') }}</textarea>
                    </div>
                </div>

                {{-- Shipping Method --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="font-heading font-semibold text-navy text-lg mb-5 flex items-center gap-2">
                        <span class="w-6 h-6 bg-amber text-navy rounded-full flex items-center justify-center text-xs font-bold">2</span>
                        Metode Pengiriman
                    </h2>
                    <div class="space-y-3">
                        @foreach([['jne_reguler','JNE Reguler','Estimasi 2–3 hari kerja',18000],['jne_yes','JNE YES – Yakin Esok Sampai','Estimasi 1 hari kerja',35000],['jnt_express','J&T Express','Estimasi 2–4 hari kerja',15000],['kurir_lokal','Kurir Lokal (Bandar Lampung)','Same day – khusus area Bandar Lampung',1]] as $s)
                            <label class="flex items-center gap-4 p-4 rounded-lg border {{ old('shipping_method')==$s[0]?'border-amber bg-amber-50':'border-gray-200' }} cursor-pointer hover:border-amber transition-colors shipping-option">
                                <input type="radio" name="shipping_method" value="{{ $s[0] }}" class="w-4 h-4 text-amber" {{ old('shipping_method')==$s[0]?'checked':'' }} {{ $loop->first && !old('shipping_method')?'checked':'' }} onchange="updateShipping({{ $s[3] }})">
                                <div class="flex-1">
                                    <p class="font-semibold text-navy text-sm">{{ $s[1] }}</p>
                                    <p class="text-xs text-gray-400">{{ $s[2] }}</p>
                                </div>
                                <span class="font-semibold text-navy text-sm">Rp {{ number_format($s[3],0,',','.') }}</span>
                                @if($s[0]==='kurir_lokal')<span class="badge badge-new text-xs ml-2">Same Day</span>@endif
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- Payment Method --}}
                <div class="bg-white rounded-xl border border-gray-200 p-6">
                    <h2 class="font-heading font-semibold text-navy text-lg mb-5 flex items-center gap-2">
                        <span class="w-6 h-6 bg-amber text-navy rounded-full flex items-center justify-center text-xs font-bold">3</span>
                        Metode Pembayaran
                    </h2>
                    <div class="grid grid-cols-3 gap-3">
                        @foreach(['Transfer Bank','QRIS','GoPay','OVO','DANA','COD'] as $pm)
                            <label class="flex items-center justify-center p-3 rounded-lg border {{ old('payment_method')==strtolower(str_replace(' ','_',$pm))?'border-amber bg-amber-50':'border-gray-200' }} cursor-pointer hover:border-amber transition-colors text-sm font-medium text-navy">
                                <input type="radio" name="payment_method" value="{{ strtolower(str_replace(' ','_',$pm)) }}" class="sr-only" {{ old('payment_method')==strtolower(str_replace(' ','_',$pm))?'checked':'' }} {{ $loop->first && !old('payment_method')?'checked':'' }}>
                                <span>{{ $pm }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Right: Order Summary --}}
            <div>
                <div class="bg-white rounded-xl border border-gray-200 p-6 sticky top-20">
                    <h3 class="font-heading font-semibold text-navy mb-4">Ringkasan Pesanan</h3>
                    <div class="space-y-3 mb-4 max-h-60 overflow-y-auto">
                        @foreach($cartItems as $item)
                            <div class="flex gap-3">
                                <img src="{{ $item['product']->primary_image }}" class="w-14 h-14 rounded-lg object-cover">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-navy truncate">{{ $item['product']->name }}</p>
                                    <p class="text-xs text-gray-400">x {{ $item['quantity'] }}</p>
                                </div>
                                <span class="text-sm font-semibold text-navy whitespace-nowrap">Rp {{ number_format($item['subtotal'],0,',','.') }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="border-t border-gray-200 pt-4 space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-gray-500">Subtotal ({{ count($cartItems) }} produk)</span><span>Rp {{ number_format($subtotal,0,',','.') }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Ongkos Kirim</span><span id="shipping-display">Rp 18.000</span></div>
                        <div class="flex justify-between"><span class="text-success text-xs">Diskon Promo</span><span class="text-success">- Rp 0</span></div>
                    </div>
                    <div class="border-t border-gray-200 mt-4 pt-4">
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total Pembayaran</span>
                            <span class="text-amber" id="total-display">Rp {{ number_format($subtotal+18000,0,',','.') }}</span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg btn-block mt-6">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Bayar Sekarang
                    </button>
                    <div class="flex items-center justify-center gap-4 mt-4 text-xs text-gray-400">
                        <span>🔒 SSL Secure</span><span>✅ Verified</span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
// Map
const map = L.map('shipping-map').setView([-5.45, 105.2667], 13);
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution:'© OpenStreetMap'}).addTo(map);
const marker = L.marker([-5.45, 105.2667], {draggable: true}).addTo(map);
function updateCoords(lat, lng) {
    document.getElementById('lat-input').value = lat.toFixed(8);
    document.getElementById('lng-input').value = lng.toFixed(8);
    document.getElementById('coord-display').textContent = lat.toFixed(4) + ', ' + lng.toFixed(4);
}
marker.on('dragend', function(e) { const p = e.target.getLatLng(); updateCoords(p.lat, p.lng); });
map.on('click', function(e) { marker.setLatLng(e.latlng); updateCoords(e.latlng.lat, e.latlng.lng); });

// Shipping cost
const subtotal = {{ $subtotal }};
function updateShipping(cost) {
    document.getElementById('shipping-display').textContent = 'Rp ' + cost.toLocaleString('id-ID');
    document.getElementById('total-display').textContent = 'Rp ' + (subtotal + cost).toLocaleString('id-ID');
    document.querySelectorAll('.shipping-option').forEach(el => { el.classList.remove('border-amber','bg-amber-50'); el.classList.add('border-gray-200'); });
    event.target.closest('.shipping-option').classList.add('border-amber','bg-amber-50');
    event.target.closest('.shipping-option').classList.remove('border-gray-200');
}

// Payment selection
document.querySelectorAll('input[name="payment_method"]').forEach(el => {
    el.addEventListener('change', function() {
        document.querySelectorAll('input[name="payment_method"]').forEach(r => { r.closest('label').classList.remove('border-amber','bg-amber-50'); r.closest('label').classList.add('border-gray-200'); });
        this.closest('label').classList.add('border-amber','bg-amber-50');
        this.closest('label').classList.remove('border-gray-200');
    });
});
</script>
@endpush
