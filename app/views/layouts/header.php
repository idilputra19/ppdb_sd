<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PPDB SD - <?= $title ?? 'Welcome' ?></title>
    
    <head>
        <!-- Scripts lainnya -->
        <script src="/assets/js/custom.js"></script>
        <meta name="csrf-token" content="<?= Security::generateCSRFToken() ?>">
    </head>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- AdminLTE -->
    <link rel="stylesheet" href="/assets/css/adminlte.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/assets/css/custom.css">
</head>
<body class="hold-transition sidebar-mini">
