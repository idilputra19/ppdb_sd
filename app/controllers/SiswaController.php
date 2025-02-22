<?php

/**
 * SiswaController handles all student-related operations
 * Including profile management, document uploads, payments, and announcements
 */
class SiswaController {
    private $db;
    private $siswa;
    private $biaya;
    
    // Configuration constants
    private const MAX_FILE_SIZE = 5242880; // 5MB in bytes
    private const MAX_PAYMENT_FILE_SIZE = 2097152; // 2MB in bytes
    private const ALLOWED_DOC_TYPES = ['pdf', 'jpg', 'jpeg', 'png'];
    private const UPLOAD_PATH = '../public/uploads/';
    
    public function __construct() {
        try {
            $database = Database::getInstance();
            $this->db = $database->getConnection();
            $this->initializeModels();
            $this->setupErrorHandling();
        } catch (Exception $e) {
            $this->logError('Database initialization failed', $e);
            throw new SystemException('Failed to initialize system');
        }
    }

    /**
     * Initialize required models
     */
    private function initializeModels(): void {
        $this->siswa = new Siswa($this->db);
        $this->biaya = new BiayaPendaftaran($this->db);
    }

    /**
     * Setup custom error handling
     */
    private function setupErrorHandling(): void {
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
    }

    /**
     * Student dashboard
     */
    public function dashboard() {
        try {
            $this->validateStudentAccess();
            
            $dashboardData = [
                'studentInfo' => $this->siswa->getDetailedInfo($_SESSION['user_id']),
                'registrationStatus' => $this->getRegistrationStatus(),
                'announcements' => $this->getRecentAnnouncements(),
                'paymentStatus' => $this->getPaymentStatus(),
                'title' => 'Dashboard Siswa'
            ];
            
            $this->renderView('dashboard', $dashboardData);
        } catch (Exception $e) {
            $this->handleError('Dashboard Error', $e);
        }
    }

    /**
     * Personal data management
     */
    public function dataPribadi() {
        try {
            $this->validateStudentAccess();
            
            if ($this->isPostRequest()) {
                $this->processPersonalDataUpdate();
                return;
            }
            
            $viewData = [
                'studentData' => $this->siswa->getByUserId($_SESSION['user_id']),
                'title' => 'Data Pribadi'
            ];
            
            $this->renderView('data-pribadi', $viewData);
        } catch (ValidationException $e) {
            $this->setFlashMessage('error', $e->getMessage());
            $this->redirect('/siswa/data-pribadi');
        } catch (Exception $e) {
            $this->handleError('Personal Data Error', $e);
        }
    }

    /**
     * Document upload handling
     */
    public function uploadDokumen() {
        try {
            $this->validateStudentAccess();
            
            if ($this->isPostRequest()) {
                $this->processDocumentUpload();
                return;
            }
            
            $viewData = [
                'studentDocs' => $this->siswa->getDocuments($_SESSION['user_id']),
                'allowedTypes' => self::ALLOWED_DOC_TYPES,
                'maxSize' => self::MAX_FILE_SIZE,
                'title' => 'Upload Dokumen'
            ];
            
            $this->renderView('upload-dokumen', $viewData);
        } catch (ValidationException $e) {
            $this->setFlashMessage('error', $e->getMessage());
            $this->redirect('/siswa/upload-dokumen');
        } catch (Exception $e) {
            $this->handleError('Document Upload Error', $e);
        }
    }

    /**
     * Payment processing
     */
    public function pembayaran() {
        try {
            $this->validateStudentAccess();
            
            if ($this->isPostRequest()) {
                $this->processPayment();
                return;
            }
            
            $studentData = $this->siswa->getByUserId($_SESSION['user_id']);
            $viewData = [
                'paymentInfo' => $this->biaya->getBySiswaId($studentData['id']),
                'paymentAmount' => $this->getPaymentAmount(),
                'paymentStatus' => $this->getPaymentStatus(),
                'title' => 'Pembayaran'
            ];
            
            $this->renderView('pembayaran', $viewData);
        } catch (ValidationException $e) {
            $this->setFlashMessage('error', $e->getMessage());
            $this->redirect('/siswa/pembayaran');
        } catch (Exception $e) {
            $this->handleError('Payment Processing Error', $e);
        }
    }

    /**
     * Announcement viewing
     */
    public function pengumuman() {
        try {
            $this->validateStudentAccess();
            
            $pengumuman = new Pengumuman($this->db);
            $viewData = [
                'announcements' => $pengumuman->getPengumumanAktif(),
                'studentStatus' => $this->siswa->getStatus($_SESSION['user_id']),
                'title' => 'Pengumuman'
            ];
            
            $this->renderView('pengumuman', $viewData);
        } catch (Exception $e) {
            $this->handleError('Announcement Error', $e);
        }
    }

    /**
     * Re-registration process
     */
    public function daftarUlang() {
        try {
            $this->validateStudentAccess();
            $this->validateGraduationStatus();
            
            if ($this->isPostRequest()) {
                $this->processReregistration();
                return;
            }
            
            $daftarUlang = new PendaftaranUlang($this->db);
            $viewData = [
                'reregistrationData' => $daftarUlang->getByUserId($_SESSION['user_id']),
                'requirements' => $this->getReregistrationRequirements(),
                'title' => 'Daftar Ulang'
            ];
            
            $this->renderView('daftar-ulang', $viewData);
        } catch (ValidationException $e) {
            $this->setFlashMessage('error', $e->getMessage());
            $this->redirect('/siswa/daftar-ulang');
        } catch (Exception $e) {
            $this->handleError('Re-registration Error', $e);
        }
    }

    /**
     * Process personal data update
     */
    private function processPersonalDataUpdate(): void {
        $this->validateCSRFToken();
        $data = $this->validatePersonalData($_POST);
        
        if ($this->siswa->updateData($_SESSION['user_id'], $data)) {
            ActivityLogger::log('Personal data updated', $_SESSION['user_id']);
            $this->setFlashMessage('success', 'Data berhasil disimpan');
        } else {
            throw new SystemException('Failed to update personal data');
        }
        
        $this->redirect('/siswa/data-pribadi');
    }

    /**
     * Process document upload
     */
    private function processDocumentUpload(): void {
        $this->validateCSRFToken();
        
        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
        $file = $this->validateFileUpload($_FILES['file'], self::MAX_FILE_SIZE);
        
        $uploadResult = $this->siswa->uploadDocument(
            $_SESSION['user_id'],
            $type,
            $file,
            self::UPLOAD_PATH
        );
        
        if ($uploadResult) {
            ActivityLogger::log("Document uploaded: $type", $_SESSION['user_id']);
            $this->setFlashMessage('success', 'Dokumen berhasil diupload');
        } else {
            throw new SystemException('Failed to upload document');
        }
        
        $this->redirect('/siswa/upload-dokumen');
    }

    /**
     * Process payment submission
     */
    private function processPayment(): void {
        $this->validateCSRFToken();
        
        $file = $this->validateFileUpload(
            $_FILES['bukti_pembayaran'],
            self::MAX_PAYMENT_FILE_SIZE
        );
        
        $studentData = $this->siswa->getByUserId($_SESSION['user_id']);
        $filename = $this->biaya->uploadBuktiPembayaran($studentData['id'], $file);
        
        if (!$filename) {
            throw new SystemException('Failed to upload payment proof');
        }
        
        $paymentData = [
            'siswa_id' => $studentData['id'],
            'jumlah_biaya' => $this->getPaymentAmount(),
            'bukti_pembayaran_path' => $filename,
            'tanggal_pembayaran' => date('Y-m-d H:i:s')
        ];
        
        if ($this->biaya->create($paymentData)) {
            ActivityLogger::log('Payment submitted', $_SESSION['user_id']);
            $this->setFlashMessage('success', 'Bukti pembayaran berhasil diupload');
        } else {
            throw new SystemException('Failed to process payment');
        }
        
        $this->redirect('/siswa/pembayaran');
    }

    /**
     * Process re-registration
     */
    private function processReregistration(): void {
        $this->validateCSRFToken();
        
        $file = $this->validateFileUpload(
            $_FILES['bukti_daftar_ulang'],
            self::MAX_PAYMENT_FILE_SIZE
        );
        
        $studentData = $this->siswa->getByUserId($_SESSION['user_id']);
        $daftarUlang = new PendaftaranUlang($this->db);
        
        $filename = $daftarUlang->uploadBukti($studentData['id'], $file);
        
        if (!$filename) {
            throw new SystemException('Failed to upload re-registration proof');
        }
        
        $reregistrationData = [
            'siswa_id' => $studentData['id'],
            'bukti_daftar_ulang_path' => $filename,
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if ($daftarUlang->create($reregistrationData)) {
            ActivityLogger::log('Re-registration submitted', $_SESSION['user_id']);
            $this->setFlashMessage('success', 'Pendaftaran ulang berhasil disubmit');
        } else {
            throw new SystemException('Failed to process re-registration');
        }
        
        $this->redirect('/siswa/daftar-ulang');
    }

    /**
     * Validation methods
     */
    private function validatePersonalData(array $input): array {
        $validator = new Validator();
        $rules = [
            'nama_lengkap' => 'required|min:3|max:100',
            'nama_panggilan' => 'required|min:2|max:50',
            'tempat_lahir' => 'required|min:3|max:50',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P'
        ];
        
        if (!$validator->validate($input, $rules)) {
            throw new ValidationException(implode(', ', $validator->getErrors()));
        }
        
        return Security::sanitize($input);
    }

    private function validateFileUpload(array $file, int $maxSize): array {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new ValidationException('File upload failed');
        }
        
        if ($file['size'] > $maxSize) {
            throw new ValidationException(
                'File too large. Maximum size: ' . ($maxSize / 1024 / 1024) . 'MB'
            );
        }
        
        $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExt, self::ALLOWED_DOC_TYPES)) {
            throw new ValidationException(
                'Invalid file type. Allowed types: ' . implode(', ', self::ALLOWED_DOC_TYPES)
            );
        }
        
        return $file;
    }

    private function validateGraduationStatus(): void {
        $studentData = $this->siswa->getByUserId($_SESSION['user_id']);
        if ($studentData['status_kelulusan'] !== 'lulus') {
            throw new ValidationException('Maaf, Anda belum dinyatakan lulus');
        }
    }

    /**
     * Helper methods
     */
    private function validateStudentAccess(): void {
        AuthMiddleware::isSiswa();
        if (!isset($_SESSION['user_id'])) {
            throw new AuthenticationException('Unauthorized access');
        }
    }

    private function validateCSRFToken(): void {
        if (!Security::validateCSRFToken($_POST['csrf_token'] ?? '')) {
            throw new SecurityException('Invalid CSRF token');
        }
    }

    private function isPostRequest(): bool {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    private function renderView(string $view, array $data = []): void {
        extract($data);
        require_once '../app/views/layouts/siswa_header.php';
        require_once "../app/views/siswa/$view.php";
        require_once '../app/views/layouts/siswa_footer.php';
    }

    private function setFlashMessage(string $type, string $message): void {
        $_SESSION[$type] = $message;
    }

    private function redirect(string $path): void {
        header("Location: $path");
        exit;
    }

    private function logError(string $message, Exception $e): void {
        error_log("[$message] " . $e->getMessage());
    }

    private function handleError(string $context, Exception $e): void {
        $this->logError($context, $e);
        $this->setFlashMessage('error', 'Terjadi kesalahan sistem');
        $this->redirect('/siswa/error');
    }
}

// Custom Exceptions
class ValidationException extends Exception {}
class SecurityException extends Exception {}
class AuthenticationException extends Exception {}
class SystemException extends Exception {}