<?php include '../app/views/layouts/siswa_header.php'; ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Pendaftaran Ulang</h1>
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

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Informasi Pendaftaran Ulang</h3>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <h5><i class="icon fas fa-info"></i> Petunjuk Pembayaran</h5>
                    <p>Silahkan transfer biaya pendaftaran ulang sebesar Rp 2.000.000 ke rekening:</p>
                    <p>Bank BRI<br>
                    No. Rekening: 1234-5678-9012-3456<br>
                    A.n. PPDB SD</p>
                </div>

                <?php if ($data_daftar_ulang): ?>
                    <div class="alert alert-<?= $data_daftar_ulang['status'] === 'selesai' ? 'success' : 'warning' ?>">
                        Status: <?= ucfirst($data_daftar_ulang['status']) ?>
                        <?php if (!empty($data_daftar_ulang['catatan'])): ?>
                            <br>Catatan: <?= $data_daftar_ulang['catatan'] ?>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <form action="/siswa/daftar-ulang" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Upload Bukti Pembayaran</label>
                            <input type="file" name="bukti_daftar_ulang" class="form-control" required 
                                   accept="image/*,.pdf">
                            <small class="text-muted">Format: JPG, PNG, atau PDF</small>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3">Submit Pendaftaran Ulang</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include '../app/views/layouts/siswa_footer.php'; ?>