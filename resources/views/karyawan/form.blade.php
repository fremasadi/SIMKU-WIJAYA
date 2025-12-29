<div class="mb-3">
    <label class="form-label">Nama Karyawan</label>
    <input type="text" name="nama" class="form-control"
        value="{{ old('nama', $karyawan->nama ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Jabatan</label>
    <select name="jabatan" class="form-control" required>
        <option value="">-- Pilih Jabatan --</option>
        <option value="Karyawan Jemur"
            {{ old('jabatan', $karyawan->jabatan ?? '') == 'Karyawan Jemur' ? 'selected' : '' }}>
            Karyawan Jemur
        </option>
        <option value="Karyawan Produksi"
            {{ old('jabatan', $karyawan->jabatan ?? '') == 'Karyawan Produksi' ? 'selected' : '' }}>
            Karyawan Produksi
        </option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label">No HP</label>
    <input type="text" name="no_hp" class="form-control"
        value="{{ old('no_hp', $karyawan->no_hp ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Alamat</label>
    <textarea name="alamat" class="form-control" rows="3" required>{{ old('alamat', $karyawan->alamat ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">Tanggal Masuk</label>
    <input type="date" name="tanggal_masuk" class="form-control"
        value="{{ old('tanggal_masuk', isset($karyawan) ? $karyawan->tanggal_masuk->format('Y-m-d') : '') }}"
        required>
</div>

<button class="btn btn-success">{{ $button }}</button>
<a href="{{ route('karyawan.index') }}" class="btn btn-secondary">Kembali</a>