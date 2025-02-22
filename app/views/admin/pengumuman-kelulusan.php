<?php include '../app/views/layouts/admin_header.php'; ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Pengumuman Kelulusan</h1>
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

        <form action="/admin/pengumuman-kelulusan" method="POST">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Siswa</h3>
                </div>
                <div class="card-body table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No Pendaftaran</th>
                                <th>Nama Siswa</th>
                                <th>Nilai Ujian</th>
                                <th>Nilai Wawancara</th>
                                <th>Nilai Akhir</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($daftar_siswa as $siswa): ?>
                            <tr>
                                <td><?= $siswa['no_pendaftaran'] ?></td>
                                <td><?= $siswa['nama_lengkap'] ?></td>
                                <td><?= number_format($siswa['nilai_ujian_tulis'], 2) ?></td>
                                <td><?= number_format($siswa['nilai_wawancara'], 2) ?></td>
                                <td><?= number_format($siswa['nilai_akhir'], 2) ?></td>
                                <td>
                                    <select name="kelulusan[<?= $siswa['id'] ?>]" class="form-select" required>
                                        <option value="">Pilih Status</option>
                                        <option value="lulus">Lulus</option>
                                        <option value="tidak_lulus">Tidak Lulus</option>
                                    </select>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pengumuman</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label>Tanggal Publish</label>
                        <input type="datetime-local" name="tanggal_publish" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Isi Pengumuman</label>
                        <textarea name="isi_pengumuman" class="form-control" rows="5" required></textarea>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Publikasi Pengumuman</button>
                </div>
            </div>
        </form>
    </div>
</section>

<?php include '../app/views/layouts/admin_footer.php'; ?>