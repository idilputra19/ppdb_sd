<!-- Content Header -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Pendaftar</span>
                        <span class="info-box-number"><?= $stats['total_pendaftar'] ?></span>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-clock"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Belum Verifikasi</span>
                        <span class="info-box-number"><?= $stats['belum_verifikasi'] ?></span>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Sudah Verifikasi</span>
                        <span class="info-box-number"><?= $stats['sudah_verifikasi'] ?></span>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-graduation-cap"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Lulus Seleksi</span>
                        <span class="info-box-number"><?= $stats['lulus'] ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Registrations -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Pendaftar Terbaru</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>No Pendaftaran</th>
                                    <th>Nama</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Add table rows here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../app/views/layouts/admin_header.php'; ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-12 col-sm-6 col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Pendaftar</span>
                        <span class="info-box-number"><?= $stats['total_pendaftar'] ?></span>
                    </div>
                </div>
            </div>
            <!-- Add more info boxes -->
        </div>

        <!-- Chart Component -->
        <div id="dashboard-charts"></div>
    </div>
</section>

<script type="text/javascript">
    // Data untuk charts
    const chartData = {
        pendaftaranHarian: <?= json_encode($pendaftaran_harian) ?>,
        statusVerifikasi: <?= json_encode($status_verifikasi) ?>,
        rataRataNilai: <?= json_encode($rata_rata_nilai) ?>
    };
</script>

<script type="module">
    import DashboardCharts from '/views/admin/components/DashboardCharts.js';
    
    // Render dashboard charts
    const container = document.getElementById('dashboard-charts');
    ReactDOM.render(
        React.createElement(DashboardCharts, chartData),
        container
    );
</script>

<?php include '../app/views/layouts/admin_footer.php'; ?>