<?php

namespace App\Cells;
use CodeIgniter\View\Cell; 
use App\Models\ArtikelModel;

class ArtikelTerkini extends Cell
{
    /**
     * Render komponen artikel terkini untuk sidebar.
     * * @return string
     */
    public function render(): string
    {
        $model = new ArtikelModel();

        // Mengambil 5 artikel terbaru berdasarkan kolom created_at 
        $artikel = $model->orderBy('created_at', 'DESC')
                         ->limit(5)
                         ->findAll();

        // Mengirim data ke view komponen [cite: 100]
        return view('components/artikel_terkini', [
            'artikel' => $artikel
        ]);
    }
}