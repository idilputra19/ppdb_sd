<?php

/**
 * Siswa (Student) Model Class
 * Handles all student-related database operations including:
 * - Personal data management
 * - Document uploads
 * - Status updates
 * - Reporting and statistics
 */
class Siswa {
    /** @var PDO */
    private $conn;
    private const TABLE = 'siswa';
    
    // Configuration constants
    private const UPLOAD_PATH = '../public/assets/uploads/';
    private const ALLOWED_DOC_TYPES = ['foto', 'kk', 'kia'];
    private const MAX_FILE_SIZE = 5242880; // 5MB
    private const VALID_STATUSES = [
        'verifikasi' => ['pending', 'verified', 'rejected'],
        'kelulusan' => ['pending', 'lulus', 'tidak_lulus'],
        'daftar_ulang' => ['belum', 'selesai', 'batal']
    ];

    /**
     * Initialize Siswa with database connection
     * 
     * @param PDO $db Database connection
     */
    public function __construct(PDO $db) {
        $this->conn = $db;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Update student personal data
     * 
     * @param int $userId User ID
     * @param array $data Personal data
     * @return bool Success status
     * @throws ValidationException If data validation fails 
     * @throws DatabaseException If database operation fails
     */
    public function updateData(int $userId, array $data): bool {
        try {
            $this->validatePersonalData($data);
            
            $query = "UPDATE " . self::TABLE . " SET 
                nama_lengkap = :nama_lengkap,
                nisn = :nisn,
                nik = :nik,
                nama_panggilan = :nama_panggilan,
                tempat_lahir = :tempat_lahir,
                tanggal_lahir = :tanggal_lahir,
                jenis_kelamin = :jenis_kelamin,
                agama = :agama,
                anak_ke = :anak_ke,
                jumlah_saudara = :jumlah_saudara,
                alamat = :alamat,
                rt = :rt,
                rw = :rw,
                kelurahan = :kelurahan,
                kecamatan = :kecamatan,
                kota = :kota,
                provinsi = :provinsi,
                kode_pos = :kode_pos,
                no_hp = :no_hp,
                sekolah_asal = :sekolah_asal,
                updated_at = NOW()
                WHERE user_id = :user_id";

            $stmt = $this->conn->prepare($query);
            $params = array_merge($data, ['user_id' => $userId]);
            
            $result = $stmt->execute($params);
            
            if ($result) {
                $this->logActivity('update_data', $userId, 'Personal data updated');
            }
            
            return $result;
        } catch (PDOException $e) {
            $this->logError('Data update failed', $e);
            throw new DatabaseException('Failed to update student data');
        }
    }

    /**
     * Upload student documents
     * 
     * @param int $userId User ID
     * @param string $type Document type
     * @param array $file Uploaded file data
     * @return bool Success status
     * @throws ValidationException If validation fails
     * @throws SystemException If file operation fails
     * @throws DatabaseException If database operation fails
     */
    public function uploadDokumen(int $userId, string $type, array $file): bool {
        try {
            if (!in_array($type, self::ALLOWED_DOC_TYPES)) {
                throw new ValidationException('Invalid document type');
            }

            $this->validateFileUpload($file);
            
            $filename = $this->generateSecureFilename($file['name']);
            $uploadPath = self::UPLOAD_PATH . $type . '/';
            $filepath = $uploadPath . $filename;

            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            if (!move_uploaded_file($file['tmp_name'], $filepath)) {
                throw new SystemException('Failed to upload file');
            }

            $column = $type . '_path';
            $query = "UPDATE " . self::TABLE . " 
                    SET $column = :filepath, updated_at = NOW() 
                    WHERE user_id = :user_id";
            
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([
                'filepath' => $filename,
                'user_id' => $userId
            ]);

            if ($result) {
                $this->logActivity('upload_document', $userId, "Document uploaded: $type");
            }

            return $result;
        } catch (PDOException $e) {
            $this->logError('Document upload failed', $e);
            throw new DatabaseException('Failed to save document information');
        }
    }

    // ... [Previous validation and helper methods remain the same]

    /**
     * Validation methods
     */
    private function validatePersonalData(array $data): void {
        $validator = new Validator();
        $rules = [
            'nama_lengkap' => 'required|max:100',
            'nisn' => 'required|numeric|length:10',
            'nik' => 'required|numeric|length:16',
            'jenis_kelamin' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date',
            'no_hp' => 'required|phone',
            'email' => 'required|email'
        ];

        if (!$validator->validate($data, $rules)) {
            throw new ValidationException(implode(', ', $validator->getErrors()));
        }
    }

    private function validateFileUpload(array $file): void {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new ValidationException('File upload failed');
        }

        if ($file['size'] > self::MAX_FILE_SIZE) {
            throw new ValidationException('File size exceeds limit');
        }

        $allowedMimes = ['image/jpeg', 'image/png', 'application/pdf'];
        if (!in_array($file['type'], $allowedMimes)) {
            throw new ValidationException('Invalid file type');
        }
    }

    private function generateSecureFilename(string $originalName): string {
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        return uniqid('doc_', true) . '.' . $extension;
    }

    private function logActivity(string $action, int $userId, string $description): void {
        ActivityLogger::log($action, $userId, $description);
    }

    private function logError(string $message, Exception $e): void {
        error_log("Siswa Error [$message]: " . $e->getMessage());
    }
}

// Custom Exceptions
class ValidationException extends Exception {}
class DatabaseException extends Exception {}
class SystemException extends Exception {}