PPDB SD - Sistem Pendaftaran Peserta Didik Baru

Deskripsi

PPDB SD adalah sebuah aplikasi berbasis web yang digunakan untuk mengelola proses Pendaftaran Peserta Didik Baru (PPDB) secara online. Aplikasi ini dibangun menggunakan arsitektur MVC (Model-View-Controller) dengan PHP dan MySQL sebagai backend.

Fitur Utama

Autentikasi Pengguna (Login & Register untuk admin dan siswa)

Dashboard Admin untuk mengelola data siswa, verifikasi pendaftaran, dan laporan

Dashboard Siswa untuk mengisi data pribadi, mengunggah dokumen, dan melihat status pendaftaran

Manajemen Pembayaran termasuk verifikasi bukti pembayaran

Pengumuman Kelulusan

Laporan Pendaftaran dalam bentuk data yang dapat diekspor

Struktur Folder

ppdb_sd/
├── app/
│   ├── config/
│   │   └── Database.php
│   ├── controllers/
│   │   ├── AdminController.php
│   │   ├── AuthController.php
│   │   └── SiswaController.php
│   ├── models/
│   │   ├── Siswa.php
│   │   ├── OrangTua.php
│   │   ├── BiayaPendaftaran.php
│   │   └── PendaftaranUlang.php
│   └── views/
│       ├── admin/
│       │   ├── dashboard.php
│       │   ├── verifikasi-siswa.php
│       │   ├── verifikasi-pembayaran.php
│       │   ├── pengumuman-kelulusan.php
│       │   ├── laporan-pendaftaran.php
│       │   └── manage-users.php
│       ├── auth/
│       │   ├── login.php
│       │   ├── register.php
│       │   └── logout.php
│       ├── siswa/
│       │   ├── dashboard.php
│       │   ├── data-pribadi.php
│       │   ├── upload-dokumen.php
│       │   ├── data-orangtua.php
│       │   ├── pembayaran.php
│       │   ├── pengumuman.php
│       │   └── daftar-ulang.php
│       └── layouts/
│           ├── header.php
│           ├── footer.php
│           ├── sidebar.php
│           └── navbar.php
├── public/
│   ├── assets/
│   │   ├── css/
│   │   │   ├── adminlte.min.css
│   │   │   ├── tailwind.css
│   │   │   └── custom.css
│   │   ├── js/
│   │   │   ├── adminlte.min.js
│   │   │   └── custom.js
│   │   └── uploads/
│   │       ├── foto/
│   │       ├── kk/
│   │       ├── kia/
│   │       └── bukti_pembayaran/
│   └── index.php
├── .htaccess
└── index.php

Instalasi

Clone repository ini:

git clone https://github.com/username/ppdb_sd.git

Masuk ke direktori proyek:

cd ppdb_sd

Buat database dan impor file SQL yang tersedia.

Konfigurasi koneksi database di app/config/Database.php.

Pastikan server lokal Anda (XAMPP atau lainnya) berjalan dengan PHP dan MySQL.

Akses aplikasi melalui browser:

http://localhost/ppdb_sd/public/

Teknologi yang Digunakan

PHP

MySQL

HTML, CSS (Tailwind, AdminLTE)

JavaScript

Apache (untuk server lokal)

Kontribusi

Jika ingin berkontribusi dalam pengembangan aplikasi ini, silakan fork repository ini dan buat pull request.

Lisensi

Aplikasi ini dirilis dengan lisensi MIT.

Dibuat oleh Idil Putra
