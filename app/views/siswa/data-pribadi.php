<?php include '../app/views/layouts/siswa_header.php'; ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Pribadi</h1>
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

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Form Data Pribadi</h3>
            </div>
            <form action="/siswa/data-pribadi" method="POST" class="card-body">
                <div class="row">
                    <!-- Data Pribadi -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nisn">NISN</label>
                            <input type="text" class="form-control" id="nisn" name="nisn" 
                                   value="<?= $siswa_data['nisn'] ?? '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="nik">NIK</label>
                            <input type="text" class="form-control" id="nik" name="nik" 
                                   value="<?= $siswa_data['nik'] ?? '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" 
                                   value="<?= $siswa_data['nama_lengkap'] ?? '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_panggilan">Nama Panggilan</label>
                            <input type="text" class="form-control" id="nama_panggilan" name="nama_panggilan" 
                                   value="<?= $siswa_data['nama_panggilan'] ?? '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="tempat_lahir">Tempat Lahir</label>
                            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" 
                                   value="<?= $siswa_data['tempat_lahir'] ?? '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" 
                                   value="<?= $siswa_data['tanggal_lahir'] ?? '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="L" <?= ($siswa_data['jenis_kelamin'] ?? '') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                <option value="P" <?= ($siswa_data['jenis_kelamin'] ?? '') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Data Tambahan -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="agama">Agama</label>
                            <select class="form-control" id="agama" name="agama" required>
                                <option value="">Pilih Agama</option>
                                <?php
                                $agama_list = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];
                                foreach ($agama_list as $agama) {
                                    $selected = ($siswa_data['agama'] ?? '') === $agama ? 'selected' : '';
                                    echo "<option value=\"$agama\" $selected>$agama</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="anak_ke">Anak ke</label>
                            <input type="number" class="form-control" id="anak_ke" name="anak_ke" 
                                   value="<?= $siswa_data['anak_ke'] ?? '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="jumlah_saudara">Jumlah Saudara</label>
                            <input type="number" class="form-control" id="jumlah_saudara" name="jumlah_saudara" 
                                   value="<?= $siswa_data['jumlah_saudara'] ?? '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="alamat">Alamat</label>
                            <textarea class="form-control" id="alamat" name="alamat" rows="3" required><?= $siswa_data['alamat'] ?? '' ?></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rt">RT</label>
                                    <input type="text" class="form-control" id="rt" name="rt" 
                                           value="<?= $siswa_data['rt'] ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rw">RW</label>
                                    <input type="text" class="form-control" id="rw" name="rw" 
                                           value="<?= $siswa_data['rw'] ?? '' ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="kelurahan">Kelurahan</label>
                            <input type="text" class="form-control" id="kelurahan" name="kelurahan" 
                                   value="<?= $siswa_data['kelurahan'] ?? '' ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="kecamatan">Kecamatan</label>
                            <input type="text" class="form-control" id="kecamatan" name="kecamatan" 
                                   value="<?= $siswa_data['kecamatan'] ?? '' ?>" required>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include '../app/views/layouts/siswa_footer.php'; ?>