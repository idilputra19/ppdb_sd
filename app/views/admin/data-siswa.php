<?php include '../app/views/layouts/admin_header.php'; ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Siswa</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Filter Form -->
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" name="keyword" class="form-control" 
                                   placeholder="Cari nama/no pendaftaran..."
                                   value="<?= $_GET['keyword'] ?? '' ?>">
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <select name="status_verifikasi" class="form-select">
                            <option value="">- Status Verifikasi -</option>
                            <option value="pending" <?= ($_GET['status_verifikasi'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="verified" <?= ($_GET['status_verifikasi'] ?? '') === 'verified' ? 'selected' : '' ?>>Verified</option>
                            <option value="rejected" <?= ($_GET['status_verifikasi'] ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <select name="status_kelulusan" class="form-select">
                            <option value="">- Status Kelulusan -</option>
                            <option value="pending" <?= ($_GET['status_kelulusan'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="lulus" <?= ($_GET['status_kelulusan'] ?? '') === 'lulus' ? 'selected' : '' ?>>Lulus</option>
                            <option value="tidak_lulus" <?= ($_GET['status_kelulusan'] ?? '') === 'tidak_lulus' ? 'selected' : '' ?>>Tidak Lulus</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="?<?= http_build_query(array_merge($_GET, ['export' => 'excel'])) ?>" 
                           class="btn btn-success">Export Excel</a>
                        <a href="?<?= http_build_query(array_merge($_GET, ['export' => 'pdf'])) ?>" 
                           class="btn btn-danger">Export PDF</a>
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
                            <th>No Pendaftaran</th>
                            <th>Nama Lengkap</th>
                            <th>Status Verifikasi</th>
                            <th>Nilai Ujian</th>
                            <th>Nilai Wawancara</th>
                            <th>Nilai Akhir</th>
                            <th>Status Kelulusan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($result['data'] as $siswa): ?>
                        <tr>
                            <td><?= $siswa['no_pendaftaran'] ?></td>
                            <td><?= $siswa['nama_lengkap'] ?></td>
                            <td><?= ucfirst($siswa['status_verifikasi']) ?></td>
                            <td><?= $siswa['nilai_ujian_tulis'] ?? '-' ?></td>
                            <td><?= $siswa['nilai_wawancara'] ?? '-' ?></td>
                            <td><?= $siswa['nilai_akhir'] ?? '-' ?></td>
                            <td><?= ucfirst(str_replace('_', ' ', $siswa['status_kelulusan'])) ?></td>
                            <td>
                                <a href="/admin/detail-siswa/<?= $siswa['id'] ?>" 
                                   class="btn btn-sm btn-info">Detail</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <?php if ($result['total_pages'] > 1): ?>
                <div class="mt-3">
                    <nav>
                        <ul class="pagination">
                            <?php for ($i = 1; $i <= $result['total_pages']; $i++): ?>
                                <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                    <a class="page-link" 
                                       href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include '../app/views/layouts/admin_footer.php'; ?>