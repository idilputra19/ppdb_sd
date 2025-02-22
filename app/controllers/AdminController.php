<?php

/**
 * AdminController handles all administrative functions for the PPDB system
 * Including student verification, payment processing, exam scoring, and reporting
 */
class AdminController {
    private $db;
    private const ITEMS_PER_PAGE = 10;
    private const ALLOWED_EXPORT_FORMATS = ['excel', 'pdf'];
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->initializeErrorHandling();
    }

    /**
     * Initialize error handling settings
     */
    private function initializeErrorHandling() {
        set_error_handler([$this, 'errorHandler']);
        set_exception_handler([$this, 'exceptionHandler']);
    }

    /**
     * Dashboard page showing key statistics and charts
     */
    public function dashboard() {
        try {
            AuthMiddleware::validateAdmin();
            $this->validateCSRFToken();
            
            $siswa = new Siswa($this->db);
            $nilaiSeleksi = new NilaiSeleksi($this->db);
            
            $viewData = [
                'stats' => $this->getDashboardStats($siswa),
                'charts' => $this->getDashboardCharts($siswa, $nilaiSeleksi),
                'recentActivity' => $this->getRecentActivity(),
                'title' => 'Dashboard Admin'
            ];
            
            $this->renderAdminView('dashboard', $viewData);
        } catch (Exception $e) {
            $this->handleError($e, 'Dashboard error');
        }
    }

    /**
     * Student verification page and processing
     */
    public function verifikasiSiswa() {
        try {
            AuthMiddleware::validateAdmin();
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->processStudentVerification();
                return;
            }
            
            $siswa = new Siswa($this->db);
            $viewData = [
                'pendingStudents' => $siswa->getPendingVerifikasi(),
                'title' => 'Verifikasi Siswa'
            ];
            
            $this->renderAdminView('verifikasi-siswa', $viewData);
        } catch (ValidationException $e) {
            $this->setFlashMessage('error', $e->getMessage());
            $this->redirect('/admin/verifikasi-siswa');
        } catch (Exception $e) {
            $this->handleError($e, 'Student verification error');
        }
    }

    /**
     * Payment verification processing
     */
    public function verifikasiPembayaran() {
        try {
            AuthMiddleware::validateAdmin();
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->processPaymentVerification();
                return;
            }
            
            $biaya = new BiayaPendaftaran($this->db);
            $viewData = [
                'pendingPayments' => $biaya->getPendingVerifikasi(),
                'title' => 'Verifikasi Pembayaran'
            ];
            
            $this->renderAdminView('verifikasi-pembayaran', $viewData);
        } catch (ValidationException $e) {
            $this->setFlashMessage('error', $e->getMessage());
            $this->redirect('/admin/verifikasi-pembayaran');
        } catch (Exception $e) {
            $this->handleError($e, 'Payment verification error');
        }
    }

    /**
     * Written exam score input
     */
    public function inputNilaiUjian() {
        try {
            AuthMiddleware::validateAdmin();
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->processExamScoreInput();
                return;
            }
            
            $nilaiSeleksi = new NilaiSeleksi($this->db);
            $viewData = [
                'unprocessedStudents' => $nilaiSeleksi->getSiswaBelumUjian(),
                'title' => 'Input Nilai Ujian'
            ];
            
            $this->renderAdminView('input-nilai-ujian', $viewData);
        } catch (ValidationException $e) {
            $this->setFlashMessage('error', $e->getMessage());
            $this->redirect('/admin/input-nilai-ujian');
        } catch (Exception $e) {
            $this->handleError($e, 'Exam score input error');
        }
    }

    /**
     * Interview score input
     */
    public function inputNilaiWawancara() {
        try {
            AuthMiddleware::validateAdmin();
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->processInterviewScoreInput();
                return;
            }
            
            $nilaiSeleksi = new NilaiSeleksi($this->db);
            $viewData = [
                'unprocessedStudents' => $nilaiSeleksi->getSiswaBelumWawancara(),
                'title' => 'Input Nilai Wawancara'
            ];
            
            $this->renderAdminView('input-nilai-wawancara', $viewData);
        } catch (ValidationException $e) {
            $this->setFlashMessage('error', $e->getMessage());
            $this->redirect('/admin/input-nilai-wawancara');
        } catch (Exception $e) {
            $this->handleError($e, 'Interview score input error');
        }
    }

    /**
     * Admission announcement management
     */
    public function pengumumanKelulusan() {
        try {
            AuthMiddleware::validateAdmin();
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->processAdmissionAnnouncement();
                return;
            }
            
            $siswa = new Siswa($this->db);
            $viewData = [
                'eligibleStudents' => $siswa->getSiswaForKelulusan(),
                'title' => 'Pengumuman Kelulusan'
            ];
            
            $this->renderAdminView('pengumuman-kelulusan', $viewData);
        } catch (Exception $e) {
            $this->handleError($e, 'Admission announcement error');
        }
    }

    /**
     * Re-registration verification
     */
    public function verifikasiDaftarUlang() {
        try {
            AuthMiddleware::validateAdmin();
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->processReregistrationVerification();
                return;
            }
            
            $daftarUlang = new PendaftaranUlang($this->db);
            $viewData = [
                'pendingReregistrations' => $daftarUlang->getPendingVerifikasi(),
                'title' => 'Verifikasi Pendaftaran Ulang'
            ];
            
            $this->renderAdminView('verifikasi-daftar-ulang', $viewData);
        } catch (Exception $e) {
            $this->handleError($e, 'Re-registration verification error');
        }
    }

    /**
     * Registration report generation
     */
    public function laporanPendaftaran() {
        try {
            AuthMiddleware::validateAdmin();
            
            $filter = $this->getReportFilters();
            $siswa = new Siswa($this->db);
            
            $data = [
                'registrations' => $siswa->getLaporanPendaftaran($filter),
                'summary' => $siswa->getRekapitulasi(),
                'filter' => $filter,
                'title' => 'Laporan Pendaftaran'
            ];
            
            if (isset($_GET['export']) && $_GET['export'] === 'excel') {
                $this->exportToExcel($data['registrations']);
                return;
            }
            
            $this->renderAdminView('laporan-pendaftaran', $data);
        } catch (Exception $e) {
            $this->handleError($e, 'Registration report error');
        }
    }

    /**
     * Admin user management
     */
    public function manageUsers() {
        try {
            AuthMiddleware::validateSuperAdmin();
            
            $user = new User($this->db);
            $viewData = [
                'adminUsers' => $user->getAllAdmin(),
                'title' => 'Manajemen Admin'
            ];
            
            $this->renderAdminView('manage-users', $viewData);
        } catch (Exception $e) {
            $this->handleError($e, 'User management error');
        }
    }

    /**
     * Student search functionality
     */
    public function searchSiswa() {
        try {
            AuthMiddleware::validateAdmin();
            
            $params = $this->getSearchParameters();
            $siswa = new Siswa($this->db);
            
            $results = [
                'data' => $siswa->search($params),
                'total' => $siswa->getTotalFiltered($params),
                'page' => $params['page']
            ];
            
            if ($this->isAjaxRequest()) {
                $this->sendJsonResponse($results);
                return;
            }
            
            $viewData = array_merge($results, ['title' => 'Cari Siswa']);
            $this->renderAdminView('search-siswa', $viewData);
        } catch (Exception $e) {
            $this->handleError($e, 'Student search error');
        }
    }

    /**
     * Data export functionality
     */
    public function exportSiswa() {
        try {
            AuthMiddleware::validateAdmin();
            
            $format = $this->validateExportFormat($_GET['format'] ?? 'excel');
            $siswa = new Siswa($this->db);
            $data = $siswa->search($_GET);
            
            $exporter = new DataExporter();
            $exporter->export($data, $format, 'data_siswa_ppdb');
        } catch (Exception $e) {
            $this->handleError($e, 'Data export error');
        }
    }

    /**
     * Process admin user creation
     */
    public function addAdmin() {
        try {
            AuthMiddleware::validateSuperAdmin();
            $this->validateCSRFToken();
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = $this->validateAdminData($_POST);
                
                $user = new User($this->db);
                if ($user->createAdmin($data)) {
                    $this->setFlashMessage('success', 'Admin berhasil ditambahkan');
                    ActivityLogger::log('Admin created: ' . $data['username'], $_SESSION['user_id']);
                } else {
                    throw new Exception('Failed to create admin user');
                }
                
                $this->redirect('/admin/manage-users');
            }
        } catch (ValidationException $e) {
            $this->setFlashMessage('error', $e->getMessage());
            $this->redirect('/admin/manage-users');
        } catch (Exception $e) {
            $this->handleError($e, 'Admin creation error');
        }
    }

    // Private helper methods...
    private function validateCSRFToken() {
        if (!Security::validateCSRFToken($_POST['csrf_token'] ?? '')) {
            throw new SecurityException('Invalid CSRF token');
        }
    }

    private function validateAdminData($data) {
        $validator = new Validator();
        $rules = [
            'username' => 'required|min:4|max:50|alpha_num',
            'email' => 'required|email|max:100',
            'password' => 'required|min:8|max:100'
        ];
        
        if (!$validator->validate($data, $rules)) {
            throw new ValidationException(implode(', ', $validator->getErrors()));
        }
        
        return Security::sanitize($data);
    }

    private function handleError(Exception $e, string $context) {
        error_log("$context: " . $e->getMessage());
        $this->setFlashMessage('error', 'Terjadi kesalahan sistem');
        $this->redirect('/admin/error');
    }

    private function setFlashMessage(string $type, string $message) {
        $_SESSION[$type] = $message;
    }

    private function redirect(string $path) {
        header("Location: $path");
        exit;
    }

    private function renderAdminView(string $view, array $data = []) {
        extract($data);
        require_once '../app/views/layouts/admin_header.php';
        require_once "../app/views/admin/$view.php";
        require_once '../app/views/layouts/admin_footer.php';
    }

    private function isAjaxRequest(): bool {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    private function sendJsonResponse($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    private function validateExportFormat(string $format): string {
        if (!in_array($format, self::ALLOWED_EXPORT_FORMATS)) {
            throw new ValidationException('Invalid export format');
        }
        return $format;
    }
}

// Custom Exceptions
class ValidationException extends Exception {}
class SecurityException extends Exception {}
class DatabaseException extends Exception {}