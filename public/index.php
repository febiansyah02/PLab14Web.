<?php

use CodeIgniter\Boot;
use Config\Paths;

/**
 * ---------------------------------------------------------------
 * CHECK PHP VERSION
 * ---------------------------------------------------------------
 */
$minPhpVersion = '8.2'; 
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    $message = sprintf(
        'Versi PHP anda harus %s atau lebih tinggi. Versi saat ini: %s',
        $minPhpVersion,
        PHP_VERSION,
    );

    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo $message;
    exit(1);
}

/**
 * ---------------------------------------------------------------
 * SET THE CURRENT DIRECTORY
 * ---------------------------------------------------------------
 */
// Menentukan Path Konstan untuk folder public (FCPATH)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Memastikan directory aktif mengarah ke folder ini
if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

/**
 * ---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 * ---------------------------------------------------------------
 */

// 1. Memuat konfigurasi Paths 
$pathsConfig = FCPATH . '../app/Config/Paths.php';

if (! file_exists($pathsConfig)) {
    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo 'Folder aplikasi (app) tidak ditemukan. Periksa konfigurasi path anda.';
    exit(3); 
}

require $pathsConfig;
$paths = new Paths();

// 2. Memuat file Bootstrap dari sistem CI4
require $paths->systemDirectory . '/Boot.php';

// 3. Menjalankan Aplikasi Web
exit(Boot::bootWeb($paths));