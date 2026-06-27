# Laporan Praktikum Pemrograman Web 2
## Pertemuan 14: Keamanan API, Autentikasi Token, dan Axios Interceptors

---

## 📝 1. Deskripsi Tugas & Analisis
[cite_start]Praktikum ini berfokus pada implementasi **Server-Side Security** (Keamanan di sisi Server) untuk melengkapi sistem keamanan browser yang telah dibangun pada praktikum sebelumnya[cite: 16, 18]. 

### Kesimpulan Analisis Perbedaan Keamanan:
* [cite_start]**Vue Router Navigation Guards (Client-Side Security):** Hanya berfungsi menjaga pintu gerbang *User Interface* (UI) pada browser agar halaman internal tidak bisa diklik secara visual[cite: 16]. [cite_start]Namun, database masih rawan dibobol jika endpoint REST API ditembak langsung menggunakan tools luar[cite: 17].
* [cite_start]**CodeIgniter Filters (Server-Side Security):** Merupakan benteng pertahanan utama database[cite: 18]. [cite_start]Filter bekerja di latar belakang server untuk mencegat setiap *request* manipulasi data dan memvalidasi keberadaan token spesifik pada HTTP Header sebelum memberikan izin akses data[cite: 20, 28].

---

## 🛠️ 2. Komponen & Lingkungan Jaringan
Aplikasi berjalan pada arsitektur terpisah (*Decoupled Architecture*) menggunakan pemetaan port local berikut:
* [cite_start]**Frontend SPA Server (VueJS 3):** `http://localhost/lab8_vuejs/` (Dijalankan via Apache XAMPP Port `80`) [cite: 11, 13]
* **Backend REST API Server (CodeIgniter 4):** `http://localhost:8080` (Dijalankan via CLI Spark Serve)
* [cite_start]**Database Management:** MySQL / MariaDB dengan nama database `lab_ci4`[cite: 11].

---

## 📂 3. Struktur Berkas Proyek Aktif
Berikut adalah letak komponen-komponen utama yang terintegrasi pada Praktikum 14:

```text
proyek-spa/
├── lab8_vuejs/                  # DIREKTORI FRONTEND (VUEJS 3)
│   ├── assets/
│   │   ├── css/
│   │   │   └── style.css        # Layouting & Form Box Login
│   │   └── js/
│   │       ├── components/
│   │       │   ├── About.js     # Komponen Halaman Profil
│   │       │   ├── Artikel.js   # Komponen CRUD Artikel
│   │       │   ├── Home.js      # Komponen Beranda
│   │       │   └── Login.js     # Komponen Form Login Admin
│   │       └── app.js           # Core Router & Axios Interceptors Global
│   └── index.html               # Main Single Page HTML
│
└── lab11_ci/                    # DIREKTORI BACKEND (CODEIGNITER 4)
    └── ci4/
        ├── app/
        │   ├── Config/
        │   │   ├── Filters.php  # Registrasi Core & Custom Filter Aliases
        │   │   └── Routes.php   # Pemetaan Jalur Proteksi REST API Resource
        │   ├── Controllers/
        │   │   ├── Api/
        │   │   │   └── Auth.php # Controller Autentikasi & Injeksi CORS
        │   │   └── Post.php     # Resource Controller Data Artikel
        │   ├── Filters/
        │   │   └── ApiAuthFilter.php # Script Mencegat & Validasi HTTP Token
        │   └── Models/
        │       └── UserModel.php # Model Koneksi Data Akun Tabel User
        └── .env                 # Konfigurasi Environment & Database `lab_ci4`
```
💻 4. Implementasi Kode Sumber KunciA. Filter Validasi Token (app/Filters/ApiAuthFilter.php)Bertugas memeriksa kiriman HTTP Header Authorization dari klien .  PHP<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface; // cite: 33
use CodeIgniter\Http\RequestInterface;  // cite: 34
use CodeIgniter\Http\ResponseInterface; // cite: 35
use Config\Services;                    // cite: 36

class ApiAuthFilter implements FilterInterface // cite: 37
{
    public function before(RequestInterface $request, $arguments = null) // cite: 40
    {
        $authHeader = $request->getServer('HTTP_AUTHORIZATION'); // cite: 42

        if (!$authHeader) { // cite: 43
            $response = Services::response(); // cite: 45
            $response->setStatusCode(401); // cite: 46
            return $response->setJSON([ // cite: 47
                'status'   => 401, // cite: 48
                'error'    => 401, // cite: 49
                'messages' => 'Akses Ditolak. Token tidak ditemukan pada request!' // cite: 50
            ]); // cite: 51
        }

        $token = null; // cite: 54
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) { // cite: 55
            $token = $matches[1]; // cite: 56
        }

        if (!$token || empty($token)) { // cite: 61
            $response = Services::response(); // cite: 62
            $response->setStatusCode(401); // cite: 66
            return $response->setJSON([ // cite: 67
                'status'   => 401, // cite: 71
                'error'    => 401, // cite: 72
                'messages' => 'Sesi Token tidak valid atau kedaluwarsa!' // cite: 73
            ]); // cite: 70
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null) {} // cite: 74
}
B. Otomatisasi Frontend (assets/js/app.js - Axios Interceptors)Bertindak sebagai kurir otomatis yang menyisipkan token dari localStorage ke dalam HTTP Header secara global tanpa perlu mengetik kodenya manual di setiap fungsi komponen .  JavaScript// Interceptor Request: Suntik Token ke Header sebelum Request Keluar [cite: 106, 115]
axios.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('userToken'); // cite: 118
        if (token) { // cite: 119
            config.headers['Authorization'] = 'Bearer ' + token; // cite: 120
        }
        return config; // cite: 122
    },
    (error) => Promise.reject(error) // cite: 125
);

// Interceptor Response: Tangkap Eror 401 dan tendang paksa jika token kedaluwarsa [cite: 128-129, 134]
axios.interceptors.response.use(
    (response) => response, // cite: 131
    (error) => {
        if (error.response && error.response.status === 401) { // cite: 134
            alert('Sesi Anda telah berakhir atau Token tidak sah. Silakan login kembali.'); // cite: 135
            localStorage.clear(); // cite: 136
            window.location.href = '#/login'; // cite: 137
            window.location.reload(); // cite: 143
        }
        return Promise.reject(error); // cite: 144
    }
);
⚙️ 5. Langkah Menjalankan & Pengujian End-to-End1. Aktivasi Server & DatabasePastikan database lab_ci4 aktif di phpMyAdmin dengan data user bertipe hash bcrypt pada kolom userpassword.Jalankan server backend via command line terminal: php spark serve.2. Pengujian Keamanan Pintu Belakang (Simulasi Pembobolan via Postman)Lakukan request menggunakan Postman ke URL endpoint manipulasi data backend: http://localhost:8080/post dengan metode POST tanpa menyertakan token di bagian header.  Hasil Diharapkan: Server CodeIgniter 4 menolak secara mutlak dan mengembalikan kode status 401 Unauthorized beserta pesan JSON proteksi.
(Lampiaskan Screenshot Bukti Respon Error 401 dari Postman di Sini)   3. Pengujian Transmisi Data Sukses (Aplikasi Browser)Buka browser, lakukan pembersihan cache via Ctrl + F5, lalu lakukan login menggunakan akun administrator yang valid.Lakukan operasi Tambah/Ubah/Hapus data artikel. Berkat Axios Interceptors, data akan terkirim mulus ke database server secara rahasia di latar belakang.  Buka menu F12 (Developer Tools) -> tab Network -> klik pada nama request API post untuk memverifikasi bahwa parameter Authorization: Bearer <token> telah berhasil disuntikkan secara otomatis.
(Lampiaskan Screenshot Bukti Parameter Authorization Bearer pada Tab Network Browser di Sini)
