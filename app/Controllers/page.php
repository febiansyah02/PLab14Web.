<?php

namespace App\Controllers;

// Import Model yang akan digunakan
use App\Models\ArtikelModel;

class Page extends BaseController
{
    public function about()
    {
        return view('about', [
            'title' => 'Halaman About',
            'content' => 'Ini adalah halaman about yang menjelaskan tentang isi halaman ini.'
        ]);
    }

    public function contact()
    {
        return view('about', [
            'title' => 'Halaman Kontak',
            'content' => 'Ini adalah halaman kontak. Anda bisa menghubungi kami melalui form ini.'
        ]);
    }

    public function faqs()
    {
        return view('about', [
            'title' => 'Halaman FAQ',
            'content' => 'Daftar pertanyaan yang sering diajukan.'
        ]);
    }

    public function artikel()
    {
        // Memanggil Model Artikel
        $model = new ArtikelModel();
        
        // Mengambil semua data dari tabel 'artikel' di database lab_ci4
        $data = [
            'title' => 'Halaman Artikel',
            'artikel' => $model->findAll() 
        ];

        // Mengarahkan ke view 'artikel' (bukan lagi view 'about')
        return view('artikel', $data);
    }

    public function tos()
    {
        return view('about', [
            'title' => 'Term of Services',
            'content' => 'Ini adalah halaman syarat dan ketentuan layanan (TOS).'
        ]);
    }
}