@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    <h4 class="fw-bold mb-3">Tambah Penyesuaian Stok</h4>

    <div class="card p-4">
        <form action="{{ route('penyesuaian-stok-bahan-baku.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                    value="{{ old('tanggal', date('Y-m-d')) }}" required>
                @error('tanggal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Bahan Baku</label>
                <select name="bahan_baku_id" id="bahanBakuSelect" class="form-control @error('bahan_baku_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Bahan --</option>
                    @foreach($bahanBakus as $b)
                        <option value="{{ $b->id }}" data-stok="{{ $b->stok }}" data-satuan="{{ $b->satuan }}"
                            {{ old('bahan_baku_id') == $b->id ? 'selected' : '' }}>
                            {{ $b->nama_bahan }} - stok {{ number_format($b->stok, 2, ',', '.') }} {{ $b->satuan }}
                        </option>
                    @endforeach
                </select>
                @error('bahan_baku_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted" id="stokTersedia"></small>
            </div>

            <div class="mb-3">
                <label class="form-label">Jenis Penyesuaian</label>
                <select name="jenis" class="form-control @error('jenis') is-invalid @enderror" required>
                    <option value="">-- Pilih Jenis --</option>
                    @foreach(['busuk' => 'Busuk', 'rusak' => 'Rusak', 'hilang' => 'Hilang', 'kadaluarsa' => 'Kadaluarsa', 'koreksi' => 'Koreksi Stok', 'lainnya' => 'Lainnya'] as $value => $label)
                        <option value="{{ $value }}" {{ old('jenis') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('jenis')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Jumlah Dikurangi</label>
                <input type="number" step="0.01" min="0.01" name="jumlah" class="form-control @error('jumlah') is-invalid @enderror"
                    value="{{ old('jumlah') }}" required>
                @error('jumlah')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" rows="4" class="form-control @error('catatan') is-invalid @enderror"
                    placeholder="Contoh: bahan busuk karena terkena air di gudang" required>{{ old('catatan') }}</textarea>
                @error('catatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button class="btn btn-success">Simpan</button>
            <a href="{{ route('penyesuaian-stok-bahan-baku.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function updateStokTersedia() {
        const option = document.querySelector('#bahanBakuSelect option:checked');
        const info = document.getElementById('stokTersedia');

        if (!option || !option.value) {
            info.textContent = '';
            return;
        }

        const stok = parseFloat(option.dataset.stok || 0).toLocaleString('id-ID', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        info.textContent = `Stok tersedia: ${stok} ${option.dataset.satuan || ''}`;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const select = document.getElementById('bahanBakuSelect');
        select.addEventListener('change', updateStokTersedia);
        updateStokTersedia();
    });
</script>
@endpush
