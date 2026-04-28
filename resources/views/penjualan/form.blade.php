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
                        <option value="{{ $produk->id }}" data-stok="{{ $produk->stok }}" data-harga="{{ $produk->harga ?? 0 }}">{{ $produk->nama_produk }} (Stok: {{ $produk->stok }})</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="jumlah[]" class="form-control jumlah" step="0.01" required></td>
                <td>
                    <input type="hidden" name="harga[]" class="harga" required>
                    <input type="number" class="form-control harga-display" step="0.01" disabled>
                </td>
                <td><button type="button" class="btn btn-danger btn-sm removeRow"><i class="bx bx-trash"></i></button></td>
            </tr>
        </tbody>
    </table>
</div>

<button class="btn btn-success">{{ $button }}</button>
<a href="{{ route('penjualan.index') }}" class="btn btn-secondary">Kembali</a>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function syncHarga(row) {
    const selectedOption = row.find('select[name="produk_id[]"] option:selected');
    const harga = parseFloat(selectedOption.data('harga')) || 0;

    row.find('.harga').val(harga.toFixed(2));
    row.find('.harga-display').val(harga.toFixed(2));
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

    // Validasi stok saat input jumlah diubah
    $('#detailTable').on('input', 'input[name="jumlah[]"]', function(){
        let tr = $(this).closest('tr');
        let select = tr.find('select[name="produk_id[]"]');
        let option = select.find('option:selected');
        
        if(option.val() === "") return; // Abaikan jika belum pilih produk

        let stok = parseFloat(option.data('stok'));
        let jumlah = parseFloat($(this).val());

        if (jumlah > stok) {
            Swal.fire({
                icon: 'warning',
                title: 'Stok Tidak Cukup!',
                text: 'Jumlah melebihi stok yang tersedia (' + stok + ')!',
                confirmButtonText: 'Mengerti'
            });
            $(this).val(stok); // Set ke maksimum stok
        }
    });

    // Validasi stok saat pilihan produk diubah (jika jumlah sudah terisi)
    $('#detailTable').on('change', 'select[name="produk_id[]"]', function(){
        let tr = $(this).closest('tr');
        let option = $(this).find('option:selected');
        let jumlahInput = tr.find('input[name="jumlah[]"]');
        
        let stok = parseFloat(option.data('stok'));
        jumlahInput.attr('max', stok);

        let jumlah = parseFloat(jumlahInput.val());

        if (!isNaN(jumlah) && jumlah > stok) {
            Swal.fire({
                icon: 'warning',
                title: 'Stok Tidak Cukup!',
                text: 'Jumlah melebihi stok yang tersedia (' + stok + ')!',
                confirmButtonText: 'Mengerti'
            });
            jumlahInput.val(stok);
        }

        syncHarga(tr);
    });

    $('#detailTable tbody tr').each(function(){
        syncHarga($(this));
    });
});
</script>
@endpush
