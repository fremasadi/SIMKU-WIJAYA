<div class="mb-3">
    <label class="form-label">Tanggal Pembelian</label>
    <input type="date" name="tanggal_pembelian" class="form-control"
        value="{{ old('tanggal_pembelian', date('Y-m-d')) }}" required>
</div>

<hr>

<div class="mb-3">
    <label class="form-label">Detail Pembelian</label>
    <table class="table table-bordered" id="detailTable">
        <thead>
            <tr>
                <th>Bahan Baku</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Subtotal</th>
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
                        @foreach($bahanBakus as $b)
                        <option value="{{ $b->id }}">{{ $b->nama_bahan }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="jumlah[]" class="form-control jumlah" step="0.01" required></td>
                <td><input type="number" name="harga[]" class="form-control harga" step="0.01" required></td>
                <td><input type="text" class="form-control subtotal" readonly></td>
                <td><button type="button" class="btn btn-danger btn-sm removeRow"><i class="bx bx-trash"></i></button></td>
            </tr>
        </tbody>
    </table>
</div>

<button class="btn btn-success">{{ $button }}</button>
<a href="{{ route('pembelian.index') }}" class="btn btn-secondary">Kembali</a>

@push('scripts')
<script>
    function updateSubtotal(row){
        const jumlah = parseFloat(row.find('.jumlah').val()) || 0;
        const harga = parseFloat(row.find('.harga').val()) || 0;
        row.find('.subtotal').val((jumlah * harga).toFixed(2));
    }

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

        $('#detailTable').on('input', '.jumlah, .harga', function(){
            const row = $(this).closest('tr');
            updateSubtotal(row);
        });
    });
</script>
@endpush