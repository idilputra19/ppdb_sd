

<?php
session_start();
require_once '../app/config/Database.php';

// Di bagian routing, tambahkan case untuk notification
$routes = [
    'notification/mark-read' => ['NotificationController', 'markRead'],
    'notification/mark-all-read' => ['NotificationController', 'markAllRead'], 
    'notification/unread-count' => ['NotificationController', 'getUnreadCount']
];

$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';

if (isset($routes[$url])) {
    list($controller, $action) = $routes[$url];
    $controllerName = $controller;
    $actionName = $action;
} else {
    // Default routing logic
    $controller = isset($url[0]) ? ucfirst($url[0]) : 'Auth';
    $action = isset($url[1]) ? $url[1] : 'index';
}

// Autoload classes
spl_autoload_register(function ($class) {
    $paths = [
        '../app/controllers/',
        '../app/models/',
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Parse URL
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$url = filter_var($url, FILTER_SANITIZE_URL);
$url = explode('/', $url);

// Set default controller and action
$controller = isset($url[0]) && $url[0] != '' ? ucfirst($url[0]) : 'Auth';
$action = isset($url[1]) ? $url[1] : 'index';

// Add Controller suffix
$controllerName = $controller . 'Controller';

// Create controller instance
if (class_exists($controllerName)) {
    $controller = new $controllerName();
    if (method_exists($controller, $action)) {
        unset($url[0], $url[1]);
        call_user_func_array([$controller, $action], array_values($url));
    } else {
        // 404 handler
        include '../app/views/errors/404.php';
    }
} else {
    // 404 handler
    include '../app/views/errors/404.php';
}