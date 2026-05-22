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
                    <input type="text" class="form-control harga-display rupiah-display" disabled>
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
    row.find('.harga-display').val(harga.toLocaleString('id-ID'));
}

function getSelectedProdukIds() {
    return $('#detailTable select[name="produk_id[]"]').map(function(){
        return $(this).val();
    }).get().filter(function(value){
        return value !== "" && value !== null;
    });
}

function updateProdukOptions() {
    const selectedIds = getSelectedProdukIds();

    $('#detailTable select[name="produk_id[]"] option').each(function(){
        const optionValue = $(this).val();
        if(optionValue === "") {
            $(this).prop('disabled', false);
            return;
        }

        const currentSelect = $(this).closest('select');
        const currentValue = currentSelect.val();
        const shouldDisable = optionValue !== currentValue && selectedIds.includes(optionValue);

        $(this).prop('disabled', shouldDisable);
    });
}

function hasDuplicateProduk(produkId, currentRow) {
    let duplicate = false;

    $('#detailTable select[name="produk_id[]"]').not(currentRow.find('select[name="produk_id[]"]')).each(function(){
        if($(this).val() === produkId) {
            duplicate = true;
            return false;
        }
    });

    return duplicate;
}

$(document).ready(function(){
    $('#addRow').click(function(){
        const newRow = $('#detailTable tbody tr:first').clone();
        newRow.find('input, select').val('');
        newRow.find('select[name="produk_id[]"] option').prop('disabled', false);
        newRow.find('.harga-display').val('');
        $('#detailTable tbody').append(newRow);
        updateProdukOptions();
    });

    $('#detailTable').on('click', '.removeRow', function(){
        if($('#detailTable tbody tr').length > 1){
            $(this).closest('tr').remove();
            updateProdukOptions();
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

    // Validasi stok dan duplikasi saat pilihan produk diubah
    $('#detailTable').on('change', 'select[name="produk_id[]"]', function(){
        let tr = $(this).closest('tr');
        let selectedValue = $(this).val();

        if(selectedValue !== "" && hasDuplicateProduk(selectedValue, tr)) {
            Swal.fire({
                icon: 'warning',
                title: 'Produk Sudah Dipilih!',
                text: 'Produk ini sudah ada di baris lain. Pilih produk lain.',
                confirmButtonText: 'Mengerti'
            });
            $(this).val('');
            tr.find('.harga').val('');
            tr.find('.harga-display').val('');
            tr.find('input[name="jumlah[]"]').val('');
            updateProdukOptions();
            return;
        }

        let option = $(this).find('option:selected');
        let jumlahInput = tr.find('input[name="jumlah[]"]');
        let stok = parseFloat(option.data('stok')) || 0;
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
        updateProdukOptions();
    });

    $('#detailTable tbody tr').each(function(){
        syncHarga($(this));
    });

    updateProdukOptions();
});
</script>
@endpush
