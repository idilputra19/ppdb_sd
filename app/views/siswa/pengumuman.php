<?php include '../app/views/layouts/siswa_header.php'; ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Pengumuman</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <?php if ($siswa_data['status_kelulusan'] !== 'pending'): ?>
            <div class="alert alert-<?= $siswa_data['status_kelulusan'] === 'lulus' ? 'success' : 'danger' ?>">
                <h4 class="alert-heading">
                    Status Kelulusan: <?= ucfirst(str_replace('_', ' ', $siswa_data['status_kelulusan'])) ?>
                </h4>
                <?php if ($siswa_data['status_kelulusan'] === 'lulus'): ?>
                    <p>Selamat! Anda dinyatakan lulus seleksi. Silahkan melanjutkan ke proses daftar ulang.</p>
                    <a href="/siswa/daftar-ulang" class="btn btn-success">
                        Daftar Ulang
                    </a>
                <?php else: ?>
                    <p>Mohon maaf, Anda belum dinyatakan lulus seleksi. Tetap semangat!</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php foreach ($daftar_pengumuman as $pengumuman): ?>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?= $pengumuman['judul'] ?></h3>
                    <div class="card-tools">
                        <small class="text-muted">
                            <?= date('d/m/Y H:i', strtotime($pengumuman['tanggal_publish'])) ?>
                        </small>
                    </div>
                </div>
                <div class="card-body">
                    <?= nl2br($pengumuman['isi']) ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php include '../app/views/layouts/siswa_footer.php'; ?>