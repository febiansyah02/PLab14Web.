<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Artikel;
use App\Controllers\User;
use App\Controllers\AjaxController;
use App\Controllers\Api\Auth;
use App\Controllers\Post; // Kita import class Post agar tidak ada warning kuning

/**
 * @var RouteCollection $routes
 */

// --- Route Halaman Utama & Artikel (Public) ---
$routes->get('/', [Artikel::class, 'index']); 
$routes->get('/artikel', [Artikel::class, 'index']); 
$routes->get('/artikel/(:any)', [Artikel::class, "view/$1"]);

// --- Route Login & Logout Web Standard (Praktikum Lama) ---
$routes->add('user/login', [User::class, 'login']); 
$routes->get('user/logout', [User::class, 'logout']);

// --- Route Praktikum 13: REST API Autentikasi Frontend (PERBAIKAN TOTAL) ---
$routes->post('api/login', [Auth::class, 'login']);

// --- Route Praktikum 8 & 9 (AJAX) ---
$routes->get('ajax', [AjaxController::class, 'index']);
$routes->get('ajax/getData', [AjaxController::class, 'getData']);
$routes->delete('ajax/delete/(:num)', [AjaxController::class, "delete/$1"]);

// --- Route Group Admin (Protected MVC Lama) ---
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('artikel', [Artikel::class, 'admin_index']);
    $routes->add('artikel/add', [Artikel::class, 'add']);
    $routes->add('artikel/edit/(:any)', [Artikel::class, 'edit/$1']);
    $routes->get('artikel/delete/(:any)', [Artikel::class, 'delete/$1']);
});

// --- Route Praktikum 10, 11 & 14 (REST API Resource + Proteksi Filter) ---
// Perbaikan: Tanda kutip luar yang merusak array sudah dibuang total
$routes->get('post', [Post::class, 'index']);
$routes->get('post/(:segment)', [Post::class, 'show/$1']);

// Mengamankan method POST, PUT, dan DELETE untuk resource/post menggunakan filter apiauth [cite: 97-100]
$routes->post('post', [Post::class, 'create'], ['filter' => 'apiauth']); // cite: 98
$routes->put('post/(:segment)', [Post::class, 'update/$1'], ['filter' => 'apiauth']); // cite: 99
$routes->delete('post/(:segment)', [Post::class, 'delete/$1'], ['filter' => 'apiauth']); // cite: 100