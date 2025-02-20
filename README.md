# PPDB SD - Sistem Pendaftaran Peserta Didik Baru

![PPDB SD Logo](public/assets/logo.png)

## Deskripsi
PPDB SD adalah sebuah aplikasi berbasis web yang digunakan untuk mengelola proses Pendaftaran Peserta Didik Baru (PPDB) secara online. Aplikasi ini dibangun menggunakan arsitektur MVC (Model-View-Controller) dengan PHP dan MySQL sebagai backend.

## Fitur Utama
- :lock: **Autentikasi Pengguna** (Login & Register untuk admin dan siswa)
- :bar_chart: **Dashboard Admin** untuk mengelola data siswa, verifikasi pendaftaran, dan laporan
- :busts_in_silhouette: **Dashboard Siswa** untuk mengisi data pribadi, mengunggah dokumen, dan melihat status pendaftaran
- :moneybag: **Manajemen Pembayaran** termasuk verifikasi bukti pembayaran
- :mega: **Pengumuman Kelulusan**
- :page_facing_up: **Laporan Pendaftaran** dalam bentuk data yang dapat diekspor

## Struktur Folder
```
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
```

## Instalasi
1. Clone repository ini:
   ```bash
   git clone https://github.com/username/ppdb_sd.git
   ```
2. Masuk ke direktori proyek:
   ```bash
   cd ppdb_sd
   ```
3. Buat database dan impor file SQL yang tersedia.
4. Konfigurasi koneksi database di `app/config/Database.php`.
5. Pastikan server lokal Anda (XAMPP atau lainnya) berjalan dengan PHP dan MySQL.
6. Akses aplikasi melalui browser:
   ```
   http://localhost/ppdb_sd/public/
   ```

## Teknologi yang Digunakan
- 🐘 PHP
- 🛢️ MySQL
- 🎨 HTML, CSS (Tailwind, AdminLTE)
- ⚡ JavaScript
- 🌐 Apache (untuk server lokal)

## Kontribusi
Jika ingin berkontribusi dalam pengembangan aplikasi ini, silakan fork repository ini dan buat pull request.

## Lisensi
Aplikasi ini dirilis dengan lisensi MIT.

## Alur Penggunaan Aplikasi ppdb sd
1. Siswa registrasi akun
2. Siswa login dan melengkapi data pribadi
3. Upload foto KK dan KIA
4. Mengisi data orang tua & wali (opsional)
5. Admin verifikasi data siswa
6. Setelah data diverifikasi, muncul biaya pendaftaran berbeda beda per siswa
7. Siswa melakukan pembayaran dan mengunggah bukti
8. Admin memverifikasi pembayaran
9. Admin mengumumkan kelulusan
10. Siswa yang lulus melakukan pendaftaran ulang
11. siswa mencetak bukti mendaftar ulang


---
Dibuat oleh [Idil Putra](https://github.com/idilputra)

