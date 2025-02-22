<?php
class BiayaPendaftaran {
    private $conn;
    private $table = 'biaya_pendaftaran';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                (siswa_id, jumlah_biaya, bukti_pembayaran_path, status_pembayaran) 
                VALUES (?, ?, ?, 'pending')";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                $data['siswa_id'],
                $data['jumlah_biaya'],
                $data['bukti_pembayaran_path']
            ]);
            return true;
        } catch(PDOException $e) {
            return false;
        }
    }

    public function uploadBuktiPembayaran($siswa_id, $file) {
        $upload_dir = '../public/assets/uploads/bukti_pembayaran/';
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'pdf'];

        if (!in_array($file_ext, $allowed_ext)) {
            return false;
        }

        $filename = $siswa_id . '_bukti_' . time() . '.' . $file_ext;
        $filepath = $upload_dir . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return $filename;
        }

        return false;
    }

    public function getBySiswaId($siswa_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE siswa_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$siswa_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function verifikasiPembayaran($id, $status, $verifikator_id, $catatan = '') {
        $query = "UPDATE " . $this->table . " 
                SET status_pembayaran = ?, verifikator_id = ?, catatan = ?, tanggal_verifikasi = NOW() 
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
        $query = "SELECT bp.*, s.nama_lengkap, s.no_pendaftaran 
                FROM " . $this->table . " bp 
                JOIN siswa s ON bp.siswa_id = s.id 
                WHERE bp.status_pembayaran = 'pending'";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}