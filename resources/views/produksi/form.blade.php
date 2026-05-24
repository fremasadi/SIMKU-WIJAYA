@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mb-3">
    <label class="form-label">Produk</label>
    <div class="input-group">
        <select name="produk_id" id="produkSelect" class="form-control" required>
            <option value="">-- Pilih Produk --</option>
            @foreach ($produks as $produk)
                <option value="{{ $produk->id }}" {{ old('produk_id') == $produk->id ? 'selected' : '' }}>
                    {{ $produk->nama_produk }}
                </option>
            @endforeach
        </select>
        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createProdukModal">
            <i class="bx bx-plus"></i>
        </button>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Tanggal Produksi</label>
    <input type="date" name="tanggal_produksi" class="form-control"
        value="{{ old('tanggal_produksi', date('Y-m-d')) }}" required>
</div>

<div class="mb-3">
    <label class="form-label">Jumlah Produksi/Pcs</label>
    <input type="number" name="jumlah_produksi" class="form-control"
        value="{{ old('jumlah_produksi') }}" step="0.01" required>
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
                            <option value="{{ $b->id }}" data-stok="{{ $b->stok }}" data-satuan="{{ $b->satuan }}">
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

<div class="modal fade" id="createProdukModal" tabindex="-1" aria-labelledby="createProdukModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createProdukModalLabel">Tambah Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger d-none" id="createProdukError"></div>

                <div class="mb-3">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" id="modalNamaProduk" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Harga</label>
                    <input type="text" inputmode="numeric" id="modalHargaProduk" class="form-control rupiah-input">
                </div>

                <div class="mb-3">
                    <label class="form-label">Satuan</label>
                    <select id="modalSatuanProduk" class="form-select">
                        <option value="">-- Pilih Satuan --</option>
                        @foreach (['Kg', 'Gram', 'Pack', 'Bal', 'Dus', 'Pcs'] as $satuan)
                            <option value="{{ $satuan }}">{{ $satuan }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="saveProdukButton">Simpan Produk</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            const tableBody = $('#detailTable tbody');
            const produkModal = new bootstrap.Modal(document.getElementById('createProdukModal'));
            const produkError = $('#createProdukError');

            function syncRowAttributes(row) {
                const selectedOption = row.find('select[name="bahan_baku_id[]"] option:selected');
                const stok = parseFloat(selectedOption.data('stok'));
                const jumlahInput = row.find('input[name="jumlah_bahan[]"]');

                if (!selectedOption.val() || Number.isNaN(stok)) {
                    jumlahInput.removeAttr('max');
                    return;
                }

                jumlahInput.attr('max', stok);
            }

            function validateStock(activeInput = null, showAlert = false) {
                const totals = {};
                let hasError = false;

                tableBody.find('tr').each(function() {
                    const row = $(this);
                    const bahanId = row.find('select[name="bahan_baku_id[]"]').val();
                    const jumlah = parseFloat(row.find('input[name="jumlah_bahan[]"]').val()) || 0;

                    row.find('select[name="bahan_baku_id[]"], input[name="jumlah_bahan[]"]').removeClass('is-invalid');

                    if (!bahanId) {
                        return;
                    }

                    totals[bahanId] = (totals[bahanId] || 0) + jumlah;
                });

                if (activeInput) {
                    const activeRow = activeInput.closest('tr');
                    const activeSelect = activeRow.find('select[name="bahan_baku_id[]"]');
                    const bahanId = activeSelect.val();
                    const selectedOption = activeSelect.find('option:selected');
                    const stok = parseFloat(selectedOption.data('stok'));
                    const jumlahSaatIni = parseFloat(activeInput.val()) || 0;

                    if (bahanId && !Number.isNaN(stok) && (totals[bahanId] || 0) > stok) {
                        const sisaYangBolehDipakai = Math.max(0, stok - ((totals[bahanId] || 0) - jumlahSaatIni));
                        activeInput.val(sisaYangBolehDipakai > 0 ? sisaYangBolehDipakai : '');
                        totals[bahanId] = stok;

                        if (showAlert) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Stok Bahan Baku Tidak Cukup',
                                text: 'Jumlah bahan baku melebihi stok yang tersedia.',
                                confirmButtonText: 'Mengerti'
                            });
                        }
                    }
                }

                tableBody.find('tr').each(function() {
                    const row = $(this);
                    const select = row.find('select[name="bahan_baku_id[]"]');
                    const jumlahInput = row.find('input[name="jumlah_bahan[]"]');
                    const bahanId = select.val();
                    const selectedOption = select.find('option:selected');
                    const stok = parseFloat(selectedOption.data('stok'));

                    if (!bahanId || Number.isNaN(stok)) {
                        jumlahInput[0].setCustomValidity('');
                        return;
                    }

                    if ((totals[bahanId] || 0) > stok) {
                        const satuan = selectedOption.data('satuan') || '';
                        const message = `Total pemakaian bahan baku melebihi stok tersedia (${stok} ${satuan}).`;

                        hasError = true;
                        select.addClass('is-invalid');
                        jumlahInput.addClass('is-invalid');
                        jumlahInput[0].setCustomValidity(message);
                    } else {
                        jumlahInput[0].setCustomValidity('');
                    }
                });

                return !hasError;
            }

            $('#addRow').click(function() {
                const newRow = $('#detailTable tbody tr:first').clone();
                newRow.find('input, select').val('');
                newRow.find('select[name="bahan_baku_id[]"], input[name="jumlah_bahan[]"]').removeClass('is-invalid');
                tableBody.append(newRow);
            });

            $('#detailTable').on('click', '.removeRow', function() {
                if ($('#detailTable tbody tr').length > 1) {
                    $(this).closest('tr').remove();
                    validateStock();
                }
            });

            $('#detailTable').on('change', 'select[name="bahan_baku_id[]"]', function() {
                const row = $(this).closest('tr');
                syncRowAttributes(row);
                validateStock(row.find('input[name="jumlah_bahan[]"]'), true);
            });

            $('#detailTable').on('input', 'input[name="jumlah_bahan[]"]', function() {
                validateStock($(this), true);
            });

            $('form').on('submit', function(e) {
                if (!validateStock()) {
                    e.preventDefault();

                    Swal.fire({
                        icon: 'warning',
                        title: 'Stok Bahan Baku Tidak Cukup',
                        text: 'Periksa kembali jumlah bahan baku yang digunakan.',
                        confirmButtonText: 'Mengerti'
                    });
                }
            });

            tableBody.find('tr').each(function() {
                syncRowAttributes($(this));
            });

            $('#createProdukModal input, #createProdukModal select').on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    $('#saveProdukButton').trigger('click');
                }
            });

            $('#saveProdukButton').click(function() {
                const button = $(this);

                produkError.addClass('d-none').empty();
                button.prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: "{{ route('produk.store') }}",
                    method: 'POST',
                    dataType: 'json',
                    headers: {
                        Accept: 'application/json',
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    data: {
                        nama_produk: $('#modalNamaProduk').val(),
                        harga: $('#modalHargaProduk').val().replace(/\./g, ''),
                        satuan: $('#modalSatuanProduk').val(),
                        stok: 0
                    },
                    success: function(response) {
                        const produk = response.produk;
                        const option = new Option(produk.nama_produk, produk.id, true, true);

                        $('#produkSelect').append(option).trigger('change');
                        $('#modalNamaProduk, #modalHargaProduk').val('');
                        $('#modalSatuanProduk').val('');
                        produkModal.hide();
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON?.errors;
                        const message = errors
                            ? Object.values(errors).flat().join('<br>')
                            : 'Produk gagal ditambahkan. Coba lagi.';

                        produkError.removeClass('d-none').html(message);
                    },
                    complete: function() {
                        button.prop('disabled', false).text('Simpan Produk');
                    }
                });
            });
        });
    </script>
@endpush
