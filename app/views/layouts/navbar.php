<?php
$notification = new Notification($db);
$unread_count = $notification->getUnreadCount($_SESSION['user_id']);
$notifications = $notification->getUserNotifications($_SESSION['user_id'], 5);
?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <?php if ($unread_count > 0): ?>
                    <span class="badge badge-warning navbar-badge"><?= $unread_count ?></span>
                <?php endif; ?>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">
                    <?= $unread_count ?> Notifikasi Baru
                </span>
                <div class="dropdown-divider"></div>
                
                <?php foreach ($notifications as $notif): ?>
                    <a href="#" class="dropdown-item notification-item" 
                       data-id="<?= $notif['id'] ?>" 
                       onclick="markNotificationAsRead(<?= $notif['id'] ?>)">
                        <i class="fas fa-envelope mr-2"></i> <?= $notif['title'] ?>
                        <span class="float-right text-muted text-sm">
                            <?= date('d/m H:i', strtotime($notif['created_at'])) ?>
                        </span>
                        <p class="text-sm"><?= $notif['message'] ?></p>
                    </a>
                    <div class="dropdown-divider"></div>
                <?php endforeach; ?>
                
                <a href="#" class="dropdown-item dropdown-footer" 
                   onclick="markAllNotificationsAsRead()">
                    Tandai semua sudah dibaca
                </a>
            </div>
        </li>
    </ul>
</nav>