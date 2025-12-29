<div class="mb-3">
    <label class="form-label">Nama Bahan</label>
    <input type="text" name="nama_bahan" class="form-control"
        value="{{ old('nama_bahan', $bahanBaku->nama_bahan ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Satuan</label>
    <input type="text" name="satuan" class="form-control"
        value="{{ old('satuan', $bahanBaku->satuan ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Stok</label>
    <input type="number" step="0.01" name="stok" class="form-control"
        value="{{ old('stok', $bahanBaku->stok ?? 0) }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Harga Satuan</label>
    <input type="number" step="0.01" name="harga_satuan" class="form-control"
        value="{{ old('harga_satuan', $bahanBaku->harga_satuan ?? 0) }}" required>
</div>

<button class="btn btn-success">{{ $button }}</button>
<a href="{{ route('bahan-baku.index') }}" class="btn btn-secondary">Kembali</a>