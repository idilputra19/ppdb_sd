<?php
class NotificationHelper {
    private $notification;

    public function __construct($db) {
        $this->notification = new Notification($db);
    }

    public function sendVerificationNotification($user_id, $status) {
        $title = 'Verifikasi Dokumen';
        $type = $status === 'verified' ? 'success' : 'danger';
        $message = $status === 'verified' ? 
            'Dokumen Anda telah diverifikasi dan dinyatakan lengkap.' : 
            'Dokumen Anda ditolak. Silahkan periksa kembali kelengkapan dokumen.';

        return $this->notification->create([
            'user_id' => $user_id,
            'title' => $title,
            'message' => $message,
            'type' => $type
        ]);
    }

    public function sendPaymentNotification($user_id, $status) {
        $title = 'Verifikasi Pembayaran';
        $type = $status === 'verified' ? 'success' : 'warning';
        $message = $status === 'verified' ? 
            'Pembayaran Anda telah diverifikasi.' : 
            'Pembayaran Anda ditolak. Silahkan upload ulang bukti pembayaran yang valid.';

        return $this->notification->create([
            'user_id' => $user_id,
            'title' => $title,
            'message' => $message,
            'type' => $type
        ]);
    }

    public function sendSelectionResultNotification($user_id, $status) {
        $title = 'Hasil Seleksi';
        $type = $status === 'lulus' ? 'success' : 'info';
        $message = $status === 'lulus' ? 
            'Selamat! Anda dinyatakan LULUS seleksi.' : 
            'Mohon maaf, Anda dinyatakan TIDAK LULUS seleksi.';

        return $this->notification->create([
            'user_id' => $user_id,
            'title' => $title,
            'message' => $message,
            'type' => $type
        ]);
    }
}