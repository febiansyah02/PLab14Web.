# Laporan Praktikum Pemrograman Web 2

## Pertemuan 14: Keamanan API, Autentikasi Token, dan Axios Interceptors

---

# 1. Deskripsi Tugas dan Analisis

Praktikum ini berfokus pada implementasi **Server-Side Security (Keamanan di Sisi Server)** untuk melengkapi sistem keamanan browser yang telah dibangun pada praktikum sebelumnya.

## Kesimpulan Analisis Perbedaan Keamanan

### Vue Router Navigation Guards (Client-Side Security)

Vue Router Navigation Guards hanya berfungsi menjaga pintu gerbang **User Interface (UI)** pada browser agar halaman internal tidak dapat diakses secara langsung oleh pengguna yang belum login.

Namun, keamanan ini belum cukup karena database masih dapat diserang apabila endpoint REST API diakses langsung menggunakan tools seperti **Postman** atau aplikasi HTTP Client lainnya.

### CodeIgniter Filters (Server-Side Security)

CodeIgniter Filters merupakan benteng pertahanan utama pada sisi server. Filter bekerja di belakang layar untuk mencegat setiap request yang masuk, kemudian memvalidasi keberadaan token autentikasi pada HTTP Header sebelum mengizinkan akses ke database.

---

# 2. Komponen dan Lingkungan Jaringan

Aplikasi menggunakan arsitektur **Decoupled Architecture** dengan pembagian server sebagai berikut.

* **Frontend SPA (VueJS 3)**
  `http://localhost/lab8_vuejs/`
  Dijalankan menggunakan Apache XAMPP pada Port **80**.

* **Backend REST API (CodeIgniter 4)**
  `http://localhost:8080`
  Dijalankan menggunakan perintah:

```bash
php spark serve
```

* **Database**

Menggunakan MySQL/MariaDB dengan nama database:

```text
lab_ci4
```

---

# 3. Struktur Berkas Proyek

```text
proyek-spa/
├── lab8_vuejs/
│   ├── assets/
│   │   ├── css/
│   │   │   └── style.css
│   │   └── js/
│   │       ├── components/
│   │       │   ├── About.js
│   │       │   ├── Artikel.js
│   │       │   ├── Home.js
│   │       │   └── Login.js
│   │       └── app.js
│   └── index.html
│
└── lab11_ci/
    └── ci4/
        ├── app/
        │   ├── Config/
        │   │   ├── Filters.php
        │   │   └── Routes.php
        │   ├── Controllers/
        │   │   ├── Api/
        │   │   │   └── Auth.php
        │   │   └── Post.php
        │   ├── Filters/
        │   │   └── ApiAuthFilter.php
        │   └── Models/
        │       └── UserModel.php
        └── .env
```

---

# 4. Implementasi Kode Sumber Kunci

## A. Filter Validasi Token

**Lokasi File**

```text
app/Filters/ApiAuthFilter.php
```

Filter ini bertugas memeriksa keberadaan **HTTP Header Authorization** pada setiap request yang masuk ke server.

```php
<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\Http\RequestInterface;
use CodeIgniter\Http\ResponseInterface;
use Config\Services;

class ApiAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getServer('HTTP_AUTHORIZATION');

        if (!$authHeader) {
            $response = Services::response();
            $response->setStatusCode(401);

            return $response->setJSON([
                'status'   => 401,
                'error'    => 401,
                'messages' => 'Akses Ditolak. Token tidak ditemukan pada request!'
            ]);
        }

        $token = null;

        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
        }

        if (!$token || empty($token)) {
            $response = Services::response();
            $response->setStatusCode(401);

            return $response->setJSON([
                'status'   => 401,
                'error'    => 401,
                'messages' => 'Sesi Token tidak valid atau kedaluwarsa!'
            ]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
```

---

## B. Otomatisasi Frontend Menggunakan Axios Interceptors

**Lokasi File**

```text
assets/js/app.js
```

Axios Interceptors bertugas sebagai mekanisme otomatis yang menyisipkan token autentikasi dari **localStorage** ke dalam HTTP Header sebelum request dikirim ke server.

Selain itu, interceptor juga akan menangani respon **401 Unauthorized** dengan menghapus token, mengarahkan pengguna kembali ke halaman login, serta memuat ulang aplikasi.

```javascript
axios.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('userToken');

        if (token) {
            config.headers['Authorization'] = 'Bearer ' + token;
        }

        return config;
    },
    (error) => Promise.reject(error)
);

axios.interceptors.response.use(
    (response) => response,
    (error) => {

        if (error.response && error.response.status === 401) {

            alert('Sesi Anda telah berakhir atau Token tidak sah. Silakan login kembali.');

            localStorage.clear();

            window.location.href = '#/login';

            window.location.reload();
        }

        return Promise.reject(error);
    }
);
```

---

# 5. Langkah Menjalankan dan Pengujian End-to-End

## 5.1 Aktivasi Server dan Database

Pastikan database **lab_ci4** telah aktif pada phpMyAdmin dan tabel **user** telah berisi password yang dienkripsi menggunakan **bcrypt**.

Jalankan backend CodeIgniter menggunakan perintah:

```bash
php spark serve
```

Pastikan frontend VueJS juga telah berjalan melalui Apache XAMPP.

---

## 5.2 Pengujian Keamanan Pintu Belakang (Postman)

Lakukan request menggunakan **Postman** menuju endpoint:

```text
POST http://localhost:8080/post
```

Tanpa menambahkan header:

```text
Authorization: Bearer <token>
```

### Hasil yang Diharapkan

Server akan menolak request dan mengembalikan respon:

* Status Code **401 Unauthorized**
* Pesan JSON bahwa token tidak ditemukan atau tidak valid.

**Lampirkan screenshot hasil respon 401 dari Postman pada bagian ini.**

---

## 5.3 Pengujian Transmisi Data Melalui Browser

1. Buka aplikasi pada browser.
2. Tekan **Ctrl + F5** untuk membersihkan cache.
3. Login menggunakan akun administrator.
4. Lakukan operasi Tambah, Ubah, atau Hapus artikel.
5. Buka **Developer Tools (F12)**.
6. Pilih tab **Network**.
7. Klik salah satu request menuju endpoint **post**.
8. Periksa bagian **Request Headers**.

### Hasil yang Diharapkan

Terlihat header berikut telah dikirim secara otomatis oleh Axios Interceptors.

```text
Authorization: Bearer <token>
```

Hal tersebut menunjukkan bahwa token autentikasi berhasil disisipkan secara otomatis ke setiap request menuju server.

**Lampirkan screenshot Request Header yang menampilkan Authorization Bearer pada bagian ini.**
