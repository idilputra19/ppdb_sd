<?php
session_start();

// Define base paths
define('ROOT_PATH', dirname(dirname(__FILE__)));
define('APP_PATH', ROOT_PATH . '/app');
define('VIEW_PATH', APP_PATH . '/views');
define('CONFIG_PATH', APP_PATH . '/config');
define('BASE_URL', 'http://localhost/ppdb_sd');

require_once CONFIG_PATH . '/Database.php';

// Autoload classes
spl_autoload_register(function ($class) {
    $paths = [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
        APP_PATH . '/helpers/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Define routes
$routes = [
    'notification/mark-read' => ['NotificationController', 'markRead'],
    'notification/mark-all-read' => ['NotificationController', 'markAllRead'], 
    'notification/unread-count' => ['NotificationController', 'getUnreadCount'],
    'auth/login' => ['AuthController', 'login']
];

// Parse URL
$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$url = filter_var($url, FILTER_SANITIZE_URL);

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check for predefined routes first
if (isset($routes[$url])) {
    list($controllerName, $action) = $routes[$url];
} else {
    // Parse URL segments
    $segments = explode('/', $url);
    $controllerName = isset($segments[0]) && $segments[0] != '' ? ucfirst($segments[0]) . 'Controller' : 'AuthController';
    $action = isset($segments[1]) ? $segments[1] : 'index';
}

// Try to create controller instance
try {
    if (class_exists($controllerName)) {
        $controller = new $controllerName();
        if (method_exists($controller, $action)) {
            call_user_func_array([$controller, $action], array_slice($segments, 2));
        } else {
            throw new Exception("Method $action not found in $controllerName");
        }
    } else {
        throw new Exception("Controller $controllerName not found");
    }
} catch (Exception $e) {
    // Log error
    error_log($e->getMessage());
    // Show 404 page
    header("HTTP/1.0 404 Not Found");
    if (file_exists(VIEW_PATH . '/errors/404.php')) {
        include VIEW_PATH . '/errors/404.php';
    } else {
        echo "Page not found";
    }
}