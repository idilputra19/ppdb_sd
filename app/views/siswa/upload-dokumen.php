<?php include '../app/views/layouts/siswa_header.php'; ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Upload Dokumen</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible">
                <?= $_SESSION['success']; ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible">
                <?= $_SESSION['error']; ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Foto -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Foto</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($siswa_data['foto_path'])): ?>
                            <img src="/assets/uploads/foto/<?= $siswa_data['foto_path'] ?>" 
                                 class="img-fluid mb-3" alt="Foto Siswa">
                        <?php endif; ?>
                        <form action="/siswa/upload-dokumen" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="jenis" value="foto">
                            <div class="form-group">
                                <label>Upload Foto</label>
                                <input type="file" class="form-control" name="file" required 
                                       accept="image/jpeg,image/png">
                            </div>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Kartu Keluarga -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Kartu Keluarga</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($siswa_data['kk_path'])): ?>
                            <div class="alert alert-success">
                                KK sudah diupload
                            </div>
                        <?php endif; ?>
                        <form action="/siswa/upload-dokumen" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="jenis" value="kk">
                            <div class="form-group">
                                <label>Upload KK</label>
                                <input type="file" class="form-control" name="file" required 
                                       accept="image/jpeg,image/png,application/pdf">
                            </div>
                            <button type="submit" class="btn btn-primary">Upload</button