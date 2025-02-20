

<?php
session_start();

echo realpath(__DIR__ . '/../../config/Database.php');
echo realpath(__DIR__ . '/controllers/AuthController.php');

// require_once '../config/Database.php';
// require_once '../controllers/AuthController.php';
// require_once 'C:/xampp2/htdocs/ppdb_sd/config/Database.php';

// require_once(__DIR__ . '/../../../config/Database.php');
// require_once __DIR__ . '/../../controllers/AuthController.php';

// if (file_exists(__DIR__ . '/../../controllers/AuthController.php')) {
//     echo "AuthController.php ditemukan!";
// } else {
//     echo "AuthController.php tidak ditemukan!";
// }


// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate input
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    // Validate inputs
    $errors = [];
    if (empty($email)) {
        $errors[] = 'Email harus diisi';
    }
    if (empty($password)) {
        $errors[] = 'Password harus diisi';
    }

    // If no errors, attempt login
    if (empty($errors)) {
        $authController = new AuthController();
        
        if ($authController->login($email, $password)) {
            // Redirect based on user role
            $user = $authController->getCurrentUser();
            
            if ($user['role'] == 'admin') {
                header('Location: ../views/admin/dashboard.php');
            } else {
                header('Location: ../views/siswa/dashboard.php');
            }
            exit();
        } else {
            // Login failed
            $_SESSION['login_error'] = 'Email atau password salah';
            header('Location: login.php');
            exit();
        }
    } else {
        // Store errors in session
        $_SESSION['login_errors'] = $errors;
        header('Location: login.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PPDB SD</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md">
        <form class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4" action="" method="POST">
            <div class="mb-6 text-center">
                <h2 class="text-2xl font-bold text-gray-800">Login PPDB SD</h2>
                <p class="text-gray-600 mt-2">Silakan masuk ke akun Anda</p>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Email
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="bi bi-envelope text-gray-400"></i>
                    </span>
                    <input 
                        class="shadow appearance-none border rounded w-full py-2 px-3 pl-10 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        id="email" 
                        type="email" 
                        name="email" 
                        placeholder="Masukkan email" 
                        required
                    >
                </div>
            </div>
            
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Password
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="bi bi-lock text-gray-400"></i>
                    </span>
                    <input 
                        class="shadow appearance-none border rounded w-full py-2 px-3 pl-10 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        id="password" 
                        type="password" 
                        name="password" 
                        placeholder="Masukkan password" 
                        required
                    >
                </div>
                <a href="#" class="text-sm text-blue-500 hover:text-blue-700 text-right block">Lupa password?</a>
            </div>
            
            <div class="flex items-center justify-between">
                <button 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full" 
                    type="submit"
                >
                    Login
                </button>
            </div>
            
            <div class="text-center mt-4">
                <p class="text-gray-600 text-sm">
                    Belum punya akun? 
                    <a href="register.php" class="text-blue-500 hover:text-blue-700">Daftar disini</a>
                </p>
            </div>
        </form>
        
        <p class="text-center text-gray-500 text-xs">
            &copy; 2025 PPDB SD. Hak Cipta Dilindungi.
        </p>
    </div>
</body>
</html>