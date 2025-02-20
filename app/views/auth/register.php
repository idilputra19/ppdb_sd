<?php
session_start();
// require_once '../config/Database.php';
// require_once '../controllers/AuthController.php';

echo realpath(__DIR__ . '/../../config/Database.php');
echo realpath(__DIR__ . '/controllers/AuthController.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and validate inputs
    $full_name = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $terms_agreed = isset($_POST['terms_agreed']) ? true : false;

    // Validate inputs
    $errors = [];
    if (empty($full_name)) {
        $errors[] = 'Nama lengkap harus diisi';
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email tidak valid';
    }
    if (empty($password)) {
        $errors[] = 'Password harus diisi';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password minimal 8 karakter';
    }
    if ($password !== $confirm_password) {
        $errors[] = 'Konfirmasi password tidak cocok';
    }
    if (!$terms_agreed) {
        $errors[] = 'Anda harus menyetujui syarat dan ketentuan';
    }

    // If no errors, attempt registration
    if (empty($errors)) {
        $authController = new AuthController();
        
        // Attempt to register
        $registrationResult = $authController->register($full_name, $email, $password);
        
        if ($registrationResult) {
            // Registration successful
            $_SESSION['registration_success'] = 'Registrasi berhasil! Silakan login.';
            header('Location: login.php');
            exit();
        } else {
            // Registration failed (likely email already exists)
            $_SESSION['registration_error'] = 'Registrasi gagal. Email mungkin sudah terdaftar.';
            header('Location: register.php');
            exit();
        }
    } else {
        // Store errors in session
        $_SESSION['registration_errors'] = $errors;
        header('Location: register.php');
        exit();
    }
}

?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - PPDB SD</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md">
        <form class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8 mb-4" action="" method="POST">
            <div class="mb-6 text-center">
                <h2 class="text-2xl font-bold text-gray-800">Registrasi PPDB SD</h2>
                <p class="text-gray-600 mt-2">Buat akun baru untuk mendaftar</p>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nama_lengkap">
                    Nama Lengkap
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="bi bi-person text-gray-400"></i>
                    </span>
                    <input 
                        class="shadow appearance-none border rounded w-full py-2 px-3 pl-10 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        id="nama_lengkap" 
                        type="text" 
                        name="nama_lengkap" 
                        placeholder="Masukkan nama lengkap" 
                        required
                    >
                </div>
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
            
            <div class="mb-4">
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
                        placeholder="Buat password" 
                        required
                    >
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="konfirmasi_password">
                    Konfirmasi Password
                </label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="bi bi-lock-fill text-gray-400"></i>
                    </span>
                    <input 
                        class="shadow appearance-none border rounded w-full py-2 px-3 pl-10 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        id="konfirmasi_password" 
                        type="password" 
                        name="konfirmasi_password" 
                        placeholder="Konfirmasi password" 
                        required
                    >
                </div>
            </div>
            
            <div class="flex items-center justify-between">
                <button 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full" 
                    type="submit"
                >
                    Daftar
                </button>
            </div>
            
            <div class="text-center mt-4">
                <p class="text-gray-600 text-sm">
                    Sudah punya akun? 
                    <a href="login.php" class="text-blue-500 hover:text-blue-700">Login disini</a>
                </p>
            </div>
        </form>
        
        <p class="text-center text-gray-500 text-xs">
            &copy; 2025 PPDB SD. Hak Cipta Dilindungi.
        </p>
    </div>
</body>
</html>