<?php

// ...

use App\Controllers\AuthController;

// ...

switch ($path) {
    // ...
    case '/login':
        $authController = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->login();
        } else {
            $authController->showLoginForm();
        }
        break;
    case '/register':
        $authController = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $authController->register();
        } else {
            $authController->showRegistrationForm();
        }
        break;
    case '/logout':
        $authController = new AuthController();
        $authController->logout();
        break;
    // ...
    
}