<?php include '../app/views/layouts/admin_header.php'; ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Cari Siswa</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Search Form -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Filter Pencarian</h3>
            </div>
            <div class="card-body">
                <form id="searchForm" method="GET" class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Kata Kunci</label>
                            <input type="text" name="keyword" class="form-control" 
                                   value="<?= $_GET['keyword'] ?? '' ?>"
                                   placeholder="Nama atau No Pendaftaran">
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Status Verifikasi</label>
                            <select name="status_verifikasi" class="form-select">
                                <option value="">Semua</option>
                                <option value="pending">Pending</option>
                                <option value="verified">Verified</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                    
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
                    
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn btn-primary">Cari</button>
                        <a href="?format=excel<?= $_SERVER['QUERY_STRING'] ? '&' . $_SERVER['QUERY_STRING'] : '' ?>" 
                           class="btn btn-success">Export Excel</a>
                        <a href="?format=pdf<?= $_SERVER['QUERY_STRING'] ? '&' . $_SERVER['QUERY_STRING'] : '' ?>" 
                           class="btn btn-danger">Export PDF</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Table -->
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
                    <tbody id="searchResults">
                        <?php foreach ($data as $siswa): ?>
                        <tr>
                            <td><?= $siswa['no_pendaftaran'] ?></td>
                            <td><?= $siswa['nama_lengkap'] ?></td>
                            <td><?= ucfirst($siswa['status_verifikasi']) ?></td>
                            <td><?= $siswa['nilai_ujian_tulis'] ?? '-' ?></td>
                            <td><?= $siswa['nilai_wawancara'] ?? '-' ?></td>
                            <td><?= $siswa['nilai_akhir'] ?? '-' ?></td>
                            <td><?= ucfirst(str_replace('_', ' ', $siswa['status_kelulusan'])) ?></td>
                            <td>
                                <a href="/admin/detail-siswa?id=<?= $siswa['id'] ?>" 
                                   class="btn btn-sm btn-info">Detail</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <div class="mt-3" id="pagination">
                    <?php
                    $total_pages = ceil($total / $params['limit']);
                    $current_page = $_GET['page'] ?? 1;
                    ?>
                    
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?><?= $_SERVER['QUERY_STRING'] ? '&' . $_SERVER['QUERY_STRING'] : '' ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    loadResults(1);
});

function loadResults(page) {
    const form = document.getElementById('searchForm');
    const formData = new FormData(form);
    formData.append('page', page);
    
    const params = new URLSearchParams(formData);
    
    fetch('/admin/search-siswa?' + params.toString(), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        updateTable(data.data);
        updatePagination(data.total, data.page);
    });
}

function updateTable(data) {
    const tbody = document.getElementById('searchResults');
    tbody.innerHTML = '';
    
    data.forEach(siswa => {
        tbody.innerHTML += `
            <tr>
                <td>${siswa.no_pendaftaran}</td>
                <td>${siswa.nama_lengkap}</td>
                <td>${siswa.status_verifikasi}</td>
                <td>${siswa.nilai_ujian_tulis || '-'}</td>
                <td>${siswa.nilai_wawancara || '-'}</td>
                <td>${siswa.nilai_akhir || '-'}</td>
                <td>${siswa.status_kelulusan.replace('_', ' ')}</td>
                <td>
                    <a href="/admin/detail-siswa?id=${siswa.id}" 
                       class="btn btn-sm btn-info">Detail</a>
                </td>
            </tr>
        `;