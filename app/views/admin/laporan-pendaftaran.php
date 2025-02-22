<?php include '../app/views/layouts/admin_header.php'; ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Laporan Pendaftaran</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Rekapitulasi -->
        <div class="row">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Pendaftar</span>
                        <span class="info-box-number"><?= $rekap['total_pendaftar'] ?></span>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-success"><i class="fas fa-check"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Terverifikasi</span>
                        <span class="info-box-number"><?= $rekap['verifikasi']['verified'] ?></span>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-warning"><i class="fas fa-graduation-cap"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Lulus Seleksi</span>
                        <span class="info-box-number"><?= $rekap['kelulusan']['lulus'] ?></span>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-primary"><i class="fas fa-clipboard-check"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Daftar Ulang</span>
                        <span class="info-box-number"><?= $rekap['daftar_ulang']['selesai'] ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter Laporan</h3>
            </div>
            <div class="card-body">
                <form method="GET" class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tanggal Awal</label>
                            <input type="date" name="tanggal_awal" class="form-control" 
                                   value="<?= $_GET['tanggal_awal'] ?? '' ?>">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tanggal Akhir</label>
                            <input type="date" name="tanggal_akhir" class="form-control"
                                   value="<?= $_GET['tanggal_akhir'] ?? '' ?>">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status Verifikasi</label>
                            <select name="status_verifikasi" class="form-select">
                                <option value="">Semua</option>
                                <option value="pending" <?= ($_GET['status_verifikasi'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="verified" <?= ($_GET['status_verifikasi'] ?? '') === 'verified' ? 'selected' : '' ?>>Verified</option>
                                <option value="rejected" <?= ($_GET['status_verifikasi'] ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status Kelulusan</label>
                            <select name="status_kelulusan" class="form-select">
                                <option value="">Semua</option>
                                <option value="pending" <?= ($_GET['status_kelulusan'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="lulus" <?= ($_GET['status_kelulusan'] ?? '') === 'lulus' ? 'selected' : '' ?>>Lulus</option>
                                <option value="tidak_lulus" <?= ($_GET['status_kelulusan'] ?? '') === 'tidak_lulus' ? 'selected' : '' ?>>Tidak Lulus</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="?export=excel<?= $_SERVER['QUERY_STRING'] ? '&' . $_SERVER['QUERY_STRING'] : '' ?>" 
                           class="btn btn-success">
                            Export Excel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Pendaftaran</th>
                            <th>Nama Lengkap</th>
                            <th>Status Verifikasi</th>
                            <th>Nilai Ujian</th>
                            <th>Nilai Wawancara</th>
                            <th>Nilai Akhir</th>
                            <th>Status Kelulusan</th>
                            <th>Status Daftar Ulang</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; foreach ($data_siswa as $siswa): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= $siswa['no_pendaftaran'] ?></td>
                            <td><?= $siswa['nama_lengkap'] ?></td>
                            <td><?= ucfirst($siswa['status_verifikasi']) ?></td>
                            <td><?= $siswa['nilai_ujian_tulis'] ?? '-' ?></td>
                            <td><?= $siswa['nilai_wawancara'] ?? '-' ?></td>
                            <td><?= $siswa['nilai_akhir'] ?? '-' ?></td>
                            <td><?= ucfirst(str_replace('_', ' ', $siswa['status_kelulusan'])) ?></td>
                            <td><?= $siswa['status_daftar_ulang'] ?? 'Belum' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php include '../app/views/layouts/admin_footer.php'; ?>