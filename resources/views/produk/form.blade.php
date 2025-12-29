<div class="mb-3">
    <label class="form-label">Nama Produk</label>
    <input type="text" name="nama_produk" class="form-control"
        value="{{ old('nama_produk', $produk->nama_produk ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Satuan</label>
    <input type="text" name="satuan" class="form-control"
        value="{{ old('satuan', $produk->satuan ?? '') }}" required>
</div>

<button class="btn btn-success">{{ $button }}</button>
<a href="{{ route('produk.index') }}" class="btn btn-secondary">Kembali</a>