<?php

/**
 * NilaiSeleksi handles all operations related to student selection scores
 * including written tests and interviews
 */
class NilaiSeleksi {
    /** @var PDO */
    private $conn;
    private const TABLE = 'nilai_seleksi';
    
    // Score constraints
    private const MIN_SCORE = 0;
    private const MAX_SCORE = 100;
    private const MAX_NOTES_LENGTH = 500;

    /**
     * Initialize NilaiSeleksi with database connection
     * 
     * @param PDO $db Database connection
     */
    public function __construct(PDO $db) {
        $this->conn = $db;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Create a new selection score record
     * 
     * @param array $data Score data including student ID, written test score, interview score
     * @return bool Success status
     * @throws ValidationException If data validation fails
     * @throws DatabaseException If database operation fails
     */
    public function create(array $data): bool {
        $this->validateScoreData($data);

        $query = "INSERT INTO " . self::TABLE . " 
                (siswa_id, nilai_ujian_tulis, nilai_wawancara, catatan_pewawancara,
                tanggal_ujian_tulis, tanggal_wawancara, penguji_id, pewawancara_id,
                created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        
        try {
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([
                $data['siswa_id'],
                $data['nilai_ujian_tulis'],
                $data['nilai_wawancara'],
                $data['catatan_pewawancara'],
                $data['tanggal_ujian_tulis'],
                $data['tanggal_wawancara'],
                $data['penguji_id'],
                $data['pewawancara_id']
            ]);

            if ($result) {
                $this->logScoreCreation($this->conn->lastInsertId(), $data);
            }

            return $result;
        } catch (PDOException $e) {
            $this->logError('Score creation failed', $e);
            throw new DatabaseException('Failed to create score record');
        }
    }

    /**
     * Update written test score
     * 
     * @param int $id Score record ID
     * @param float $nilai Test score
     * @param int $penguji_id Examiner ID
     * @return bool Success status
     * @throws ValidationException If score validation fails
     * @throws DatabaseException If database operation fails
     */
    public function updateNilaiUjian(int $id, float $nilai, int $penguji_id): bool {
        $this->validateScore($nilai);
        $this->validateId($id);
        $this->validateId($penguji_id, 'Penguji ID');

        $query = "UPDATE " . self::TABLE . " 
                SET nilai_ujian_tulis = ?, 
                    penguji_id = ?, 
                    tanggal_ujian_tulis = NOW(),
                    updated_at = NOW() 
                WHERE id = ?";
        
        try {
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([$nilai, $penguji_id, $id]);

            if ($result) {
                $this->logScoreUpdate($id, 'written_test', $nilai, $penguji_id);
            }

            return $result;
        } catch (PDOException $e) {
            $this->logError('Written test score update failed', $e);
            throw new DatabaseException('Failed to update written test score');
        }
    }

    /**
     * Update interview score
     * 
     * @param int $id Score record ID
     * @param float $nilai Interview score
     * @param string $catatan Interviewer notes
     * @param int $pewawancara_id Interviewer ID
     * @return bool Success status
     * @throws ValidationException If validation fails
     * @throws DatabaseException If database operation fails
     */
    public function updateNilaiWawancara(int $id, float $nilai, string $catatan, int $pewawancara_id): bool {
        $this->validateScore($nilai);
        $this->validateNotes($catatan);
        $this->validateId($id);
        $this->validateId($pewawancara_id, 'Pewawancara ID');

        $query = "UPDATE " . self::TABLE . " 
                SET nilai_wawancara = ?, 
                    catatan_pewawancara = ?, 
                    pewawancara_id = ?, 
                    tanggal_wawancara = NOW(),
                    updated_at = NOW() 
                WHERE id = ?";
        
        try {
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([$nilai, $catatan, $pewawancara_id, $id]);

            if ($result) {
                $this->logScoreUpdate($id, 'interview', $nilai, $pewawancara_id);
            }

            return $result;
        } catch (PDOException $e) {
            $this->logError('Interview score update failed', $e);
            throw new DatabaseException('Failed to update interview score');
        }
    }

    /**
     * Get students who haven't taken the written test
     * 
     * @return array List of students
     * @throws DatabaseException If query fails
     */
    public function getSiswaBelumUjian(): array {
        $query = "SELECT s.*, ns.id as nilai_id, ns.nilai_ujian_tulis 
                FROM siswa s 
                LEFT JOIN " . self::TABLE . " ns ON s.id = ns.siswa_id 
                WHERE (ns.nilai_ujian_tulis IS NULL OR ns.id IS NULL) 
                AND s.status_verifikasi = 'verified'
                ORDER BY s.created_at ASC";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError('Failed to get students without written test', $e);
            throw new DatabaseException('Failed to retrieve student list');
        }
    }

    /**
     * Get students who haven't had their interview
     * 
     * @return array List of students
     * @throws DatabaseException If query fails
     */
    public function getSiswaBelumWawancara(): array {
        $query = "SELECT s.*, ns.id as nilai_id, ns.nilai_wawancara 
                FROM siswa s 
                LEFT JOIN " . self::TABLE . " ns ON s.id = ns.siswa_id 
                WHERE ns.nilai_ujian_tulis IS NOT NULL 
                AND (ns.nilai_wawancara IS NULL OR ns.id IS NULL) 
                AND s.status_verifikasi = 'verified'
                ORDER BY s.created_at ASC";
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError('Failed to get students without interview', $e);
            throw new DatabaseException('Failed to retrieve student list');
        }
    }

    /**
     * Get average scores for written test and interview
     * 
     * @return array Average scores
     * @throws DatabaseException If query fails
     */
    public function getRataRataNilai(): array {
        $query = "SELECT 
                ROUND(AVG(nilai_ujian_tulis), 2) as rata_ujian,
                ROUND(AVG(nilai_wawancara), 2) as rata_wawancara,
                COUNT(*) as total_peserta,
                COUNT(CASE WHEN nilai_ujian_tulis >= 70 THEN 1 END) as lulus_ujian,
                COUNT(CASE WHEN nilai_wawancara >= 70 THEN 1 END) as lulus_wawancara
                FROM " . self::TABLE;
        
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $this->logError('Failed to get average scores', $e);
            throw new DatabaseException('Failed to retrieve average scores');
        }
    }

    /**
     * Validation methods
     */
    private function validateScoreData(array $data): void {
        $required = ['siswa_id', 'nilai_ujian_tulis', 'nilai_wawancara', 
                    'penguji_id', 'pewawancara_id'];
        
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                throw new ValidationException("Missing required field: $field");
            }
        }

        $this->validateScore($data['nilai_ujian_tulis'], 'Written test score');
        $this->validateScore($data['nilai_wawancara'], 'Interview score');
        $this->validateId($data['siswa_id'], 'Student ID');
        $this->validateId($data['penguji_id'], 'Examiner ID');
        $this->validateId($data['pewawancara_id'], 'Interviewer ID');
        
        if (isset($data['catatan_pewawancara'])) {
            $this->validateNotes($data['catatan_pewawancara']);
        }
    }

    private function validateScore(float $score, string $field = 'Score'): void {
        if ($score < self::MIN_SCORE || $score > self::MAX_SCORE) {
            throw new ValidationException(
                "$field must be between " . self::MIN_SCORE . " and " . self::MAX_SCORE
            );
        }
    }

    private function validateId(int $id, string $field = 'ID'): void {
        if ($id <= 0) {
            throw new ValidationException("Invalid $field");
        }
    }

    private function validateNotes(string $notes): void {
        if (strlen($notes) > self::MAX_NOTES_LENGTH) {
            throw new ValidationException(
                "Notes too long. Maximum " . self::MAX_NOTES_LENGTH . " characters"
            );
        }
    }

    /**
     * Logging methods
     */
    private function logScoreCreation(string $id, array $data): void {
        $logData = [
            'action' => 'create_score',
            'score_id' => $id,
            'student_id' => $data['siswa_id'],
            'written_score' => $data['nilai_ujian_tulis'],
            'interview_score' => $data['nilai_wawancara']
        ];
        ActivityLogger::log(json_encode($logData));
    }

    private function logScoreUpdate(int $id, string $type, float $score, int $updater_id): void {
        $logData = [
            'action' => 'update_score',
            'score_id' => $id,
            'type' => $type,
            'new_score' => $score,
            'updater_id' => $updater_id
        ];
        ActivityLogger::log(json_encode($logData));
    }

    private function logError(string $message, Exception $e): void {
        error_log("NilaiSeleksi Error [$message]: " . $e->getMessage());
    }
}

// Custom Exceptions
class ValidationException extends Exception {}
class DatabaseException extends Exception {}