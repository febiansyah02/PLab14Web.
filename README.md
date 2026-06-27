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
