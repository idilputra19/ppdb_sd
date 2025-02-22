<?php
class Pengumuman {
    private $conn;
    private $table = 'pengumuman';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                (judul, isi, tipe, status, tanggal_publish, created_by) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                $data['judul'],
                $data['isi'],
                $data['tipe'],
                $data['status'],
                $data['tanggal_publish'],
                $data['created_by']
            ]);
            return true;
        } catch(PDOException $e) {
            return false;
        }
    }

    public function getPengumumanAktif() {
        $query = "SELECT * FROM " . $this->table . " 
                WHERE status = 'published' 
                AND tanggal_publish <= NOW() 
                ORDER BY tanggal_publish DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}