<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;

class Auth extends ResourceController
{
    protected $format = 'json';

    public function login()
    {
        // SUNTIKKAN CORS HEADER SECARA PAKSA
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === "OPTIONS") {
            return $this->respond(null, 200);
        }

        // Membaca input payload JSON mentah dari Axios
        $json = $this->request->getJSON();
        $username = $json->username ?? $this->request->getVar('username');
        $password = $json->password ?? $this->request->getVar('password');

        if (empty($username) || empty($password)) {
            return $this->fail('Username dan Password tidak boleh kosong.', 400);
        }

        $model = new UserModel();

        try {
            // Mencari user berdasarkan username atau email di database
            $user = $model->where('username', $username)
                          ->orWhere('useremail', $username)
                          ->first();

            if ($user) {
                // MENGGUNAKAN PASSWORD_VERIFY UNTUK MENCOCOKKAN HASH BCRYPT DI DATABASENMU
                if (password_verify($password, $user['userpassword']) || $password === $user['userpassword']) {
                    
                    return $this->respond([
                        'status'   => 200,
                        'error'    => null,
                        'messages' => 'Login Berhasil',
                        'data'     => [
                            'id'       => $user['id'],
                            'username' => $user['username'],
                            'token'    => base64_encode("TOKEN-SECRET-" . $user['username'])
                        ]
                    ], 200);
                }
            }
        } catch (\Exception $e) {
            return $this->respond([
                'status'   => 500,
                'error'   => true,
                'messages' => 'Gagal enkripsi/koneksi: ' . $e->getMessage()
            ], 500);
        }

        return $this->failUnauthorized('Username atau Password yang Anda masukkan salah.');
    }
}