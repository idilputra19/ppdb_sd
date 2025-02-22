<?php include '../app/views/layouts/admin_header.php'; ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Input Nilai Wawancara</h1>
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
            <div class="card-body">
                <?php foreach ($siswa_belum_wawancara as $siswa): ?>
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">
                            <?= $siswa['nama_lengkap'] ?> (<?= $siswa['no_pendaftaran'] ?>)
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="/admin/input-nilai-wawancara" method="POST"
                              class="form-wawancara" id="form_<?= $siswa['nilai_id'] ?>">
                            <input type="hidden" name="nilai_id" value="<?= $siswa['nilai_id'] ?>">
                            
                            <div class="form-group">
                                <label>Nilai Wawancara</label>
                                <input type="number" name="nilai_wawancara" class="form-control"
                                       min="0" max="100" step="0.01" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Catatan Pewawancara</label>
                                <textarea name="catatan_pewawancara" class="form-control" rows="3" required></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </form>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<?php include '../app/views/layouts/admin_footer.php'; ?>