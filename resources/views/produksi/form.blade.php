<div class="mb-3">
    <label class="form-label">Produk</label>
    <select name="produk_id" class="form-control" required>
        <option value="">-- Pilih Produk --</option>
        @foreach ($produks as $produk)
            <option value="{{ $produk->id }}" {{ old('produk_id') == $produk->id ? 'selected' : '' }}>
                {{ $produk->nama_produk }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Tanggal Produksi</label>
    <input type="date" name="tanggal_produksi" class="form-control"
        value="{{ old('tanggal_produksi', date('Y-m-d')) }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Jumlah Produksi/Pcs</label>
    <input type="number" name="jumlah_produksi" class="form-control" step="0.01" required>
</div>

<hr>

<div class="mb-3">
    <label class="form-label">Bahan Baku yang Digunakan</label>
    <table class="table table-bordered" id="detailTable">
        <thead>
            <tr>
                <th>Bahan Baku</th>
                <th>Jumlah</th>
                <th>
                    <button type="button" class="btn btn-success btn-sm" id="addRow">
                        <i class="bx bx-plus"></i>
                    </button>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <select name="bahan_baku_id[]" class="form-control" required>
                        <option value="">-- Pilih Bahan --</option>
                        @foreach ($bahanBakus as $b)
                            <option value="{{ $b->id }}">
                                {{ $b->nama_bahan }} (stok: {{ $b->stok }} {{ $b->satuan }})
                            </option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="jumlah_bahan[]" class="form-control" step="0.01" required></td>
                <td><button type="button" class="btn btn-danger btn-sm removeRow"><i class="bx bx-trash"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<button class="btn btn-success">{{ $button }}</button>
<a href="{{ route('produksi.index') }}" class="btn btn-secondary">Kembali</a>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#addRow').click(function() {
                const newRow = $('#detailTable tbody tr:first').clone();
                newRow.find('input, select').val('');
                $('#detailTable tbody').append(newRow);
            });

            $('#detailTable').on('click', '.removeRow', function() {
                if ($('#detailTable tbody tr').length > 1) {
                    $(this).closest('tr').remove();
                }
            });
        });
    </script>
@endpush
