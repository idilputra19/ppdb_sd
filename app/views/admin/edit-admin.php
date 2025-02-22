<?php include '../app/views/layouts/admin_header.php'; ?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Edit Admin</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <form action="/admin/edit-admin" method="POST">
                <input type="hidden" name="id" value="<?= $admin_data['id'] ?>">
                
                <div class="card-body">
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" required
                               value="<?= $admin_data['username'] ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required
                               value="<?= $admin_data['email'] ?>">
                    </div>
                    
                    <div class="form-group">
                        <label>Password Baru (kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    
                    <div class="form-group">
                        <label>Status</label>
                        <select name="is_active" class="form-select" required>
                            <option value="1" <?= $admin_data['is_active'] ? 'selected' : '' ?>>Aktif</option>
                            <option value="0" <?= !$admin_data['is_active'] ? 'selected' : '' ?>>Nonaktif</option>
                        </select>
                    </div>
                </div>
                
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="/admin/manage-users" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include '../app/views/layouts/admin_footer.php'; ?>