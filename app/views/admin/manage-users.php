<?php include '../app/views/layouts/admin_header.php'; ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Manajemen Admin</h1>
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

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible">
                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <!-- Add Admin Button -->
        <div class="mb-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAddAdmin">
                Tambah Admin
            </button>
        </div>

        <!-- Admin List -->
        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($admin_users as $admin): ?>
                        <tr>
                            <td><?= $admin['username'] ?></td>
                            <td><?= $admin['email'] ?></td>
                            <td>
                                <span class="badge bg-<?= $admin['is_active'] ? 'success' : 'danger' ?>">
                                    <?= $admin['is_active'] ? 'Aktif' : 'Nonaktif' ?>
                                </span>
                            </td>
                            <td><?= $admin['last_login'] ? date('d/m/Y H:i', strtotime($admin['last_login'])) : '-' ?></td>
                            <td>
                                <a href="/admin/edit-admin?id=<?= $admin['id'] ?>" class="btn btn-sm btn-info">
                                    Edit
                                </a>
                                <?php if ($admin['id'] != $_SESSION['user_id']): ?>
                                    <button type="button" class="btn btn-sm btn-danger" 
                                            onclick="deleteAdmin(<?= $admin['id'] ?>)">
                                        Hapus
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- Modal Add Admin -->
<div class="modal fade" id="modalAddAdmin" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/admin/add-admin" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Admin</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Admin Form -->
<form id="deleteAdminForm" action="/admin/delete-admin" method="POST" style="display: none;">
    <input type="hidden" name="id" id="delete_admin_id">
</form>

<script>
function deleteAdmin(id) {
    if (confirm('Apakah Anda yakin ingin menghapus admin ini?')) {
        document.getElementById('delete_admin_id').value = id;
        document.getElementById('deleteAdminForm').submit();
    }
}
</script>

<?php include '../app/views/layouts/admin_footer.php'; ?>

<form action="/admin/add-admin" method="POST">
    <input type="hidden" name="csrf_token" value="<?= Security::generateCSRFToken() ?>">
    <!-- Form fields -->
</form>