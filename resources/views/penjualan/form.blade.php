<div class="mb-3">
    <label class="form-label">Tanggal Penjualan</label>
    <input type="date" name="tanggal_penjualan" class="form-control"
        value="{{ old('tanggal_penjualan', date('Y-m-d')) }}" required>
</div>

<hr>

<div class="mb-3">
    <label class="form-label">Produk</label>
    <table class="table table-bordered" id="detailTable">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga</th>
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
                    <select name="produk_id[]" class="form-control" required>
                        <option value="">-- Pilih Produk --</option>
                        @foreach($produks as $produk)
                        <option value="{{ $produk->id }}">{{ $produk->nama_produk }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="jumlah[]" class="form-control" step="0.01" required></td>
                <td><input type="number" name="harga[]" class="form-control" step="0.01" required></td>
                <td><button type="button" class="btn btn-danger btn-sm removeRow"><i class="bx bx-trash"></i></button></td>
            </tr>
        </tbody>
    </table>
</div>

<button class="btn btn-success">{{ $button }}</button>
<a href="{{ route('penjualan.index') }}" class="btn btn-secondary">Kembali</a>

@push('scripts')
<script>
$(document).ready(function(){
    $('#addRow').click(function(){
        const newRow = $('#detailTable tbody tr:first').clone();
        newRow.find('input, select').val('');
        $('#detailTable tbody').append(newRow);
    });

    $('#detailTable').on('click', '.removeRow', function(){
        if($('#detailTable tbody tr').length > 1){
            $(this).closest('tr').remove();
        }
    });
});
</script>
@endpush