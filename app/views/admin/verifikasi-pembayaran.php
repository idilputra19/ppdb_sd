<?php include '../app/views/layouts/admin_header.php'; ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Verifikasi Pembayaran</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible">
                <?= $_SESSION['success']; unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No Pendaftaran</th>
                            <th>Nama Siswa</th>
                            <th>Tanggal Upload</th>
                            <th>Bukti Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pending_payments as $payment): ?>
                        <tr>
                            <td><?= $payment['no_pendaftaran'] ?></td>
                            <td><?= $payment['nama_lengkap'] ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($payment['created_at'])) ?></td>
                            <td>
                                <a href="/assets/uploads/bukti_pembayaran/<?= $payment['bukti_pembayaran_path'] ?>" 
                                   target="_blank" class="btn btn-sm btn-info">
                                    Lihat Bukti
                                </a>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-success" 
                                        onclick="verifikasiPembayaran(<?= $payment['id'] ?>, 'verified')">
                                    Verifikasi
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" 
                                        onclick="verifikasiPembayaran(<?= $payment['id'] ?>, 'rejected')">
                                    Tolak
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Modal Verifikasi -->
<div class="modal fade" id="modalVerifikasi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/admin/verifikasi-pembayaran" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Verifikasi Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="payment_id">
                    <input type="hidden" name="status" id="payment_status">
                    
                    <div class="form-group">
                        <label>Catatan (opsional)</label>
                        <textarea name="catatan" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function verifikasiPembayaran(id, status) {
    document.getElementById('payment_id').value = id;
    document.getElementById('payment_status').value = status;
    
    var modal = new bootstrap.Modal(document.getElementById('modalVerifikasi'));
    modal.show();
}
</script>

<?php include '../app/views/layouts/admin_footer.php'; ?>