<?php include '../app/views/layouts/admin_header.php'; ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Verifikasi Pendaftaran Ulang</h1>
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
                            <th>Tanggal Daftar Ulang</th>
                            <th>Bukti Pembayaran</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pending_daftar_ulang as $daftar_ulang): ?>
                        <tr>
                            <td><?= $daftar_ulang['no_pendaftaran'] ?></td>
                            <td><?= $daftar_ulang['nama_lengkap'] ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($daftar_ulang['tanggal_daftar_ulang'])) ?></td>
                            <td>
                                <a href="/assets/uploads/daftar_ulang/<?= $daftar_ulang['bukti_daftar_ulang_path'] ?>" 
                                   target="_blank" class="btn btn-sm btn-info">
                                    Lihat Bukti
                                </a>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-success" 
                                        onclick="verifikasiDaftarUlang(<?= $daftar_ulang['id'] ?>, 'selesai')">
                                    Verifikasi
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" 
                                        onclick="verifikasiDaftarUlang(<?= $daftar_ulang['id'] ?>, 'batal')">
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
            <form action="/admin/verifikasi-daftar-ulang" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Verifikasi Pendaftaran Ulang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="daftar_ulang_id">
                    <input type="hidden" name="status" id="daftar_ulang_status">
                    
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
function verifikasiDaftarUlang(id, status) {
    document.getElementById('daftar_ulang_id').value = id;
    document.getElementById('daftar_ulang_status').value = status;
    
    var modal = new bootstrap.Modal(document.getElementById('modalVerifikasi'));
    modal.show();
}
</script>

<?php include '../app/views/layouts/admin_footer.php'; ?>