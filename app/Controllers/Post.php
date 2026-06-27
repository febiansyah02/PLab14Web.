<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ArtikelModel;

class Post extends ResourceController
{
    use ResponseTrait;

    public function index()
    {
        $model = new ArtikelModel();
        $data['artikel'] = $model->orderBy('id', 'DESC')->findAll();
        return $this->respond($data);
    }

    public function create()
    {
        $model = new ArtikelModel();
        $judul = $this->request->getJsonVar('judul');
        $isi   = $this->request->getJsonVar('isi');
        $slug  = url_title($judul, '-', true);

        $data = [
            'judul'       => $judul,
            'isi'         => $isi,
            'slug'        => $slug,
            'status'      => '0',
            'gambar'      => null,
            'id_kategori' => 1
        ];
        
        if ($model->insert($data)) {
            return $this->respondCreated([
                'status'   => 201,
                'error'    => null,
                'messages' => ['success' => 'Data artikel berhasil ditambahkan.']
            ]);
        }
        return $this->fail('Gagal menyimpan data.');
    }

    public function show($id = null)
    {
        $model = new ArtikelModel();
        $data = $model->where('id', $id)->first();
        return $data ? $this->respond($data) : $this->failNotFound('Data tidak ditemukan.');
    }

    public function update($id = null)
    {
        $model = new ArtikelModel();
        $judul = $this->request->getJsonVar('judul');
        $isi   = $this->request->getJsonVar('isi');
        $slug  = url_title($judul, '-', true);

        $data = [
            'judul' => $judul,
            'isi'   => $isi,
            'slug'  => $slug
        ];
        
        $model->update($id, $data);
        return $this->respond([
            'status'   => 200,
            'error'    => null,
            'messages' => ['success' => 'Data artikel berhasil diubah.']
        ]);
    }

    public function delete($id = null)
    {
        $model = new ArtikelModel();
        $cekData = $model->where('id', $id)->first();
        
        if ($cekData) {
            $model->delete($id);
            return $this->respondDeleted([
                'status'   => 200,
                'error'    => null,
                'messages' => ['success' => 'Data artikel berhasil dihapus.']
            ]);
        }
        return $this->failNotFound('Data tidak ditemukan.');
    }
}