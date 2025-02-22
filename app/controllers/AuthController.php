<?php

/**
 * AuthController handles user authentication, registration, and session management
 */
class AuthController {
    /** @var PDO */
    private $db;
    
    /** @var User */
    private $user;
    
    // Configuration constants
    private const PASSWORD_MIN_LENGTH = 8;
    private const MAX_LOGIN_ATTEMPTS = 5;
    private const LOGIN_TIMEOUT = 900; // 15 minutes in seconds
    private const SESSION_LIFETIME = 3600; // 1 hour in seconds
    
    /**
     * Initialize AuthController
     * 
     * @throws SystemException
     */
    public function __construct() {
        try {
            $database = new Database();
            $this->db = $database->getConnection();
            $this->user = new User($this->db);
            $this->initializeSession();
        } catch (Exception $e) {
            $this->logError('Authentication initialization failed', $e);
            throw new SystemException('System initialization failed');
        }
    }

    /**
     * Initialize secure session settings
     */
    private function initializeSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.cookie_httponly', '1');
            ini_set('session.cookie_secure', '1');
            ini_set('session.cookie_samesite', 'Strict');
            ini_set('session.gc_maxlifetime', self::SESSION_LIFETIME);
            session_start();
        }
    }

    /**
     * Default route - redirects to login
     */
    public function index(): void {
        $this->redirect('/auth/login');
    }

    /**
     * Handle user login
     */
    public function login(): void {
        try {
            if ($this->isAuthenticated()) {
                $this->redirectBasedOnRole();
                return;
            }

            if ($this->isPostRequest()) {
                $this->processLogin();
                return;
            }

            $this->renderView('login', ['title' => 'Login']);
        } catch (AuthenticationException $e) {
            $this->setFlashMessage('error', $e->getMessage());
            $this->redirect('/auth/login');
        } catch (Exception $e) {
            $this->handleError('Login error', $e);
        }
    }

    /**
     * Handle user registration
     */
    public function register(): void {
        try {
            if ($this->isAuthenticated()) {
                $this->redirectBasedOnRole();
                return;
            }

            if ($this->isPostRequest()) {
                $this->processRegistration();
                return;
            }

            $this->renderView('register', ['title' => 'Register']);
        } catch (ValidationException $e) {
            $this->setFlashMessage('error', $e->getMessage());
            $this->redirect('/auth/register');
        } catch (Exception $e) {
            $this->handleError('Registration error', $e);
        }
    }

    /**
     * Handle user logout
     */
    public function logout(): void {
        try {
            $this->validateCSRFToken();
            $this->terminateSession();
            $this->redirect('/auth/login');
        } catch (Exception $e) {
            $this->handleError('Logout error', $e);
        }
    }

    /**
     * Process login attempt
     * @throws AuthenticationException|SecurityException
     */
    private function processLogin(): void {
        $this->validateCSRFToken();
        $this->checkLoginAttempts();

        $credentials = $this->validateLoginInput($_POST);
        $user = $this->authenticateUser($credentials);

        if (!$user) {
            $this->incrementLoginAttempts();
            throw new AuthenticationException('Email atau password salah');
        }

        $this->createSecureSession($user);
        $this->resetLoginAttempts();
        $this->user->updateLastLogin($user['id']);
        
        ActivityLogger::log('User logged in', $user['id']);
        $this->redirectBasedOnRole($user['role']);
    }

    /**
     * Process registration request
     * @throws ValidationException|SystemException
     */
    private function processRegistration(): void {
        $this->validateCSRFToken();
        $data = $this->validateRegistrationInput($_POST);
        
        try {
            $this->db->beginTransaction();

            $userId = $this->user->register($data);
            if (!$userId) {
                throw new SystemException('Failed to create user account');
            }

            $siswa = new Siswa($this->db);
            $registrationNumber = $this->generateRegistrationNumber($userId);
            
            $studentData = [
                'user_id' => $userId,
                'no_pendaftaran' => $registrationNumber,
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s')
            ];

            if (!$siswa->create($studentData)) {
                throw new SystemException('Failed to create student record');
            }

            $this->db->commit();
            ActivityLogger::log('New user registered', $userId);
            
            $this->setFlashMessage('success', 'Registrasi berhasil. Silahkan login.');
            $this->redirect('/auth/login');
        } catch (Exception $e) {
            $this->db->rollBack();
            throw new SystemException('Registration failed: ' . $e->getMessage());
        }
    }

    /**
     * Validate login input
     * @throws ValidationException
     */
    private function validateLoginInput(array $input): array {
        $validator = new Validator();
        $rules = [
            'email' => 'required|email|max:100',
            'password' => 'required|min:' . self::PASSWORD_MIN_LENGTH
        ];

        if (!$validator->validate($input, $rules)) {
            throw new ValidationException(implode(', ', $validator->getErrors()));
        }

        return [
            'email' => filter_var($input['email'], FILTER_SANITIZE_EMAIL),
            'password' => $input['password']
        ];
    }

    /**
     * Validate registration input
     * @throws ValidationException
     */
    private function validateRegistrationInput(array $input): array {
        $validator = new Validator();
        $rules = [
            'email' => 'required|email|max:100|unique:users',
            'password' => 'required|min:' . self::PASSWORD_MIN_LENGTH,
            'confirm_password' => 'required|same:password'
        ];

        if (!$validator->validate($input, $rules)) {
            throw new ValidationException(implode(', ', $validator->getErrors()));
        }

        return [
            'email' => filter_var($input['email'], FILTER_SANITIZE_EMAIL),
            'password' => password_hash($input['password'], PASSWORD_ARGON2ID)
        ];
    }

    /**
     * Authenticate user credentials
     * @throws AuthenticationException
     */
    private function authenticateUser(array $credentials): ?array {
        $user = $this->user->findByEmail($credentials['email']);
        
        if (!$user || !password_verify($credentials['password'], $user['password'])) {
            return null;
        }

        if (!$user['is_active']) {
            throw new AuthenticationException('Account is inactive');
        }

        return $user;
    }

    /**
     * Create secure session for authenticated user
     */
    private function createSecureSession(array $user): void {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['created_at'] = time();
        $_SESSION['last_activity'] = time();
        
        session_regenerate_id(true);
    }

    /**
     * Generate unique registration number
     */
    private function generateRegistrationNumber(int $userId): string {
        $year = date('y');
        $sequence = str_pad($userId, 4, '0', STR_PAD_LEFT);
        $random = substr(uniqid(), -3);
        return "PPDB{$year}{$sequence}{$random}";
    }

    /**
     * Check login attempts to prevent brute force
     * @throws AuthenticationException
     */
    private function checkLoginAttempts(): void {
        $attempts = $_SESSION['login_attempts'] ?? 0;
        $lastAttempt = $_SESSION['last_login_attempt'] ?? 0;

        if ($attempts >= self::MAX_LOGIN_ATTEMPTS) {
            $timeLeft = self::LOGIN_TIMEOUT - (time() - $lastAttempt);
            if ($timeLeft > 0) {
                throw new AuthenticationException(
                    "Too many failed attempts. Please try again in " . 
                    ceil($timeLeft / 60) . " minutes."
                );
            }
            $this->resetLoginAttempts();
        }
    }

    // Additional helper methods...
    private function incrementLoginAttempts(): void {
        $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
        $_SESSION['last_login_attempt'] = time();
    }

    private function resetLoginAttempts(): void {
        unset($_SESSION['login_attempts'], $_SESSION['last_login_attempt']);
    }

    private function terminateSession(): void {
        $_SESSION = [];
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        session_destroy();
    }

    private function isAuthenticated(): bool {
        return isset($_SESSION['user_id']) && 
               isset($_SESSION['role']) && 
               isset($_SESSION['created_at']);
    }

    private function isPostRequest(): bool {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    private function validateCSRFToken(): void {
        if (!Security::validateCSRFToken($_POST['csrf_token'] ?? '')) {
            throw new SecurityException('Invalid CSRF token');
        }
    }

    private function redirectBasedOnRole(?string $role = null): void {
        $role = $role ?? $_SESSION['role'];
        $this->redirect($role === 'admin' ? '/admin/dashboard' : '/siswa/dashboard');
    }

    private function renderView(string $view, array $data = []): void {
        extract($data);
        require_once "../app/views/auth/$view.php";
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
        $this->setFlashMessage('error', 'System error occurred');
        $this->redirect('/error');
    }
}

// Custom Exceptions
class ValidationException extends Exception {}
class AuthenticationException extends Exception {}
class SecurityException extends Exception {}
class SystemException extends Exception {}


