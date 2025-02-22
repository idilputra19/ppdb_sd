<?php include '../app/views/layouts/siswa_header.php'; ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Pembayaran Pendaftaran</h1>
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

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Pembayaran</h3>
                    </div>
                    <div class="card-body">
                        <p>Biaya Pendaftaran: Rp 500.000</p>
                        <p>Silahkan transfer ke rekening berikut:</p>
                        <div class="alert alert-info">
                            <p>Bank BRI<br>
                            No. Rekening: 1234-5678-9012-3456<br>
                            A.n. PPDB SD</p>
                        </div>
                        
                        <?php if (!empty($pembayaran)): ?>
                            <div class="alert alert-<?= $pembayaran['status_pembayaran'] === 'verified' ? 'success' : 'warning' ?>">
                                Status: <?= ucfirst($pembayaran['status_pembayaran']) ?>
                                <?php if (!empty($pembayaran['catatan'])): ?>
                                    <br>Catatan: <?= $pembayaran['catatan'] ?>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if (empty($pembayaran) || $pembayaran['status_pembayaran'] === 'rejected'): ?>
                            <form action="/siswa/pembayaran" method="POST" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label>Upload Bukti Pembayaran</label>
                                    <input type="file" name="bukti_pembayaran" class="form-control" required 
                                           accept="image/*,.pdf">
                                    <small class="text-muted">Format: JPG, PNG, atau PDF</small>
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">Upload Bukti Pembayaran</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../app/views/layouts/siswa_footer.php'; ?>