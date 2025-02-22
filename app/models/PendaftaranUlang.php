<?php
class PendaftaranUlang {
    private $conn;
    private $table = 'pendaftaran_ulang';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                (siswa_id, tanggal_daftar_ulang, status, bukti_daftar_ulang_path) 
                VALUES (?, NOW(), 'pending', ?)";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                $data['siswa_id'],
                $data['bukti_daftar_ulang_path']
            ]);
            return true;
        } catch(PDOException $e) {
            return false;
        }
    }

    public function uploadBukti($siswa_id, $file) {
        $upload_dir = '../public/assets/uploads/daftar_ulang/';
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'pdf'];

        if (!in_array($file_ext, $allowed_ext)) {
            return false;
        }

        $filename = $siswa_id . '_daftar_ulang_' . time() . '.' . $file_ext;
        $filepath = $upload_dir . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $filename;
        }

        return false;
    }

    public function verifikasi($id, $status, $verifikator_id, $catatan = '') {
        $query = "UPDATE " . $this->table . " 
                SET status = ?, verifikator_id = ?, catatan = ? 
                WHERE id = ?";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$status, $verifikator_id, $catatan, $id]);
            return true;
        } catch(PDOException $e) {
            return false;
        }
    }

    public function getPendingVerifikasi() {
        $query = "SELECT du.*, s.nama_lengkap, s.no_pendaftaran 
                FROM " . $this->table . " du 
                JOIN siswa s ON du.siswa_id = s.id 
                WHERE du.status = 'pending'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByUserId($user_id) {
        $query = "SELECT du.* FROM " . $this->table . " du 
                JOIN siswa s ON du.siswa_id = s.id 
                WHERE s.user_id = ?";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}