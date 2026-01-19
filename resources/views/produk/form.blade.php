<div class="mb-3">
    <label class="form-label">Nama Produk</label>
    <input type="text" name="nama_produk" class="form-control"
        value="{{ old('nama_produk', $produk->nama_produk ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Satuan</label>
    <select name="satuan" class="form-select" required>
        <option value="">-- Pilih Satuan --</option>

        @php
            $satuan = ['Kg', 'Gram', 'Pack', 'Bal', 'Dus', 'Pcs'];
        @endphp

        @foreach($satuan as $s)
        <option value="{{ $s }}"
            {{ old('satuan', $produk->satuan ?? '') == $s ? 'selected' : '' }}>
            {{ $s }}
        </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Stok</label>
    <input type="number" name="stok" class="form-control"
        value="{{ old('stok', $produk->stok ?? 0) }}" min="0" required>
</div>

<button class="btn btn-success">{{ $button }}</button>
<a href="{{ route('produk.index') }}" class="btn btn-secondary">Kembali</a>