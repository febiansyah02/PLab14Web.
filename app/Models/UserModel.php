<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'user'; // <-- Pastikan nama tabel di MySQL kamu 'user'
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    
    // Kolom-kolom ini wajib ada di tabel MySQL kamu, Feb!
    protected $allowedFields    = ['username', 'useremail', 'userpassword']; 
}