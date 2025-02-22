<?php include '../app/views/layouts/admin_header.php'; ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Input Nilai Ujian</h1>
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
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No Pendaftaran</th>
                            <th>Nama Siswa</th>
                            <th>Nilai Ujian</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($siswa_belum_ujian as $siswa): ?>
                        <tr>
                            <td><?= $siswa['no_pendaftaran'] ?></td>
                            <td><?= $siswa['nama_lengkap'] ?></td>
                            <td>
                                <input type="number" class="form-control nilai-ujian" 
                                       id="nilai_<?= $siswa['nilai_id'] ?>" 
                                       min="0" max="100" step="0.01">
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary"
                                        onclick="simpanNilai(<?= $siswa['nilai_id'] ?>)">
                                    Simpan Nilai
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script>
function simpanNilai(id) {
    const nilai = document.getElementById('nilai_' + id).value;
    
    if (nilai === '' || nilai < 0 || nilai > 100) {
        alert('Nilai harus diisi antara 0-100');
        return;
    }
    
    // Submit form
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/admin/input-nilai-ujian';
    
    const inputId = document.createElement('input');
    inputId.type = 'hidden';
    inputId.name = 'nilai_id';
    inputId.value = id;
    form.appendChild(inputId);
    
    const inputNilai = document.createElement('input');
    inputNilai.type = 'hidden';
    inputNilai.name = 'nilai_ujian_tulis';
    inputNilai.value = nilai;
    form.appendChild(inputNilai);
    
    document.body.appendChild(form);
    form.submit();
}
</script>

<?php include '../app/views/layouts/admin_footer.php'; ?>