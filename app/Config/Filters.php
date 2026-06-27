<?php

namespace Config;

use CodeIgniter\Config\Filters as BaseFilters;
use CodeIgniter\Filters\Cors;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\ForceHTTPS;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\PageCache;
use CodeIgniter\Filters\PerformanceMetrics;
use CodeIgniter\Filters\SecureHeaders;
use App\Filters\Auth;

class Filters extends BaseFilters
{
   public array $aliases = [
        'csrf'          => \CodeIgniter\Filters\CSRF::class,
        'toolbar'       => \CodeIgniter\Filters\DebugToolbar::class,
        'honeypot'      => \CodeIgniter\Filters\Honeypot::class,
        'invalidchars'  => \CodeIgniter\Filters\InvalidChars::class,
        'secureheaders' => \CodeIgniter\Filters\SecureHeaders::class,
        'cors'          => \CodeIgniter\Filters\Cors::class,
        'forcehttps'    => \CodeIgniter\Filters\ForceHTTPS::class, // <-- Kunci perbaikan eror forcehttps
        'pagecache'     => \CodeIgniter\Filters\PageCache::class,   // <-- Ditambahkan agar pagecache tidak eror
        'performance'   => \CodeIgniter\Filters\PerformanceMetrics::class, // <-- Ditambahkan agar performance tidak eror
        'apiauth'       => \App\Filters\ApiAuthFilter::class, // Filter Praktikum 14 kamu 
    ];

    public array $required = [
        'before' => [
            'forcehttps', 
            'pagecache',  
        ],
        'after' => [
            'pagecache',   
            'performance', 
            'toolbar',     
        ],
    ];

    public array $globals = [
        'before' => [],
        'after'  => [],
    ];

    public array $methods = [];

    // === PERBAIKAN DI SINI ===
    // Kita aktifkan filter 'apiauth' khusus untuk memproteksi endpoint resource 'post'
    public array $filters = [
        'apiauth' => [
            'before' => [
                'post/*', // Mengunci semua submenu post (update, delete)
                'post'    // Mengunci endpoint post utama (create)
            ]
        ]
    ];

    /**
     * Jalankan manipulasi header CORS di dalam Constructor agar aman dari error PHP syntax.
     */
    public function __construct()
    {
        parent::__construct();

        // Menyuntikkan permission CORS secara global saat aplikasi diakses
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Authorization");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");

        // Langsung selesaikan request jika browser melakukan Preflight (OPTIONS)
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === "OPTIONS") {
            die();
        }
    }
}