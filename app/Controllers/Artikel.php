<?php

namespace App\Controllers;

use App\Models\ArtikelModel;
use App\Models\KategoriModel; 
use CodeIgniter\Exceptions\PageNotFoundException;

class Artikel extends BaseController
{
    public function index()
    {
        $title = 'Daftar Artikel';
        $model = new ArtikelModel();
        $artikel = $model->getArtikelDenganKategori(); 

        return view('artikel/index', compact('artikel', 'title'));
    }

    public function admin_index()
{
    $model = new ArtikelModel();
    $q = $this->request->getVar('q') ?? '';
    $kategori_id = $this->request->getVar('kategori_id') ?? '';
    $sort = $this->request->getVar('sort') ?? 'id'; // Default urut berdasarkan ID
    $page = $this->request->getVar('page') ?? 1;

    $builder = $model->table('artikel')
                     ->select('artikel.*, kategori.nama_kategori')
                     ->join('kategori', 'kategori.id_kategori = artikel.id_kategori');

    if ($q != '') {
        $builder->like('artikel.judul', $q);
    }

    if ($kategori_id != '') {
        $builder->where('artikel.id_kategori', $kategori_id);
    }

    // Logika Sorting (Tugas No. 4)
    if ($sort == 'judul') {
        $builder->orderBy('artikel.judul', 'ASC');
    } else {
        $builder->orderBy('artikel.id', 'DESC');
    }

    $artikel = $builder->paginate(10, 'default', $page);
    $pager = $model->pager;

    $data = [
        'q' => $q,
        'kategori_id' => $kategori_id,
        'sort' => $sort,
        'artikel' => $artikel,
        'pager' => $pager->links()
    ];

    if ($this->request->isAJAX()) {
        return $this->response->setJSON($data);
    } else {
        $kategoriModel = new KategoriModel();
        $data['kategori'] = $kategoriModel->findAll();
        $data['title'] = 'Daftar Artikel (Admin)';
        return view('artikel/admin_index', $data);
    }
}

    public function add()
    {
        $kategoriModel = new KategoriModel();
        $model = new ArtikelModel();

        if ($this->request->getPost()) {
            $rules = [
                'judul'       => 'required',
                'isi'         => 'required',
                'id_kategori' => 'required|integer' 
            ];

            if ($this->validate($rules)) {
                // Proses Upload Gambar [cite: 22-23]
                $file = $this->request->getFile('gambar');
                $file->move(ROOTPATH . 'public/gambar');

                $model->insert([
                    'judul'       => $this->request->getPost('judul'),
                    'isi'         => $this->request->getPost('isi'),
                    'slug'        => url_title($this->request->getPost('judul'), '-', true),
                    'id_kategori' => $this->request->getPost('id_kategori'),
                    'gambar'      => $file->getName(), // Simpan nama file ke database [cite: 33]
                ]);
                return redirect()->to(base_url('admin/artikel'));
            }
        }

        $data = [
            'title'    => 'Tambah Artikel',
            'kategori' => $kategoriModel->findAll()
        ];
        return view('artikel/form_add', $data);
    }

    public function edit($id)
    {
        $model = new ArtikelModel();
        $kategoriModel = new KategoriModel();
        $artikel = $model->find($id);

        if (!$artikel) {
            throw PageNotFoundException::forPageNotFound();
        }

        if ($this->request->getPost()) {
            if ($this->validate(['judul' => 'required'])) {
                $model->update($id, [
                    'judul'       => $this->request->getPost('judul'),
                    'isi'         => $this->request->getPost('isi'),
                    'id_kategori' => $this->request->getPost('id_kategori'),
                ]);
                return redirect()->to(base_url('admin/artikel'));
            }
        }

        $data = [
            'title'    => 'Edit Artikel',
            'artikel'  => $artikel,
            'kategori' => $kategoriModel->findAll(),
        ];
        return view('artikel/form_edit', $data);
    }

    public function delete($id)
    {
        $model = new ArtikelModel();
        $model->delete($id);
        return redirect()->to(base_url('admin/artikel'));
    }

    public function view($slug)
    {
        $model = new ArtikelModel();
        $data['artikel'] = $model->where(['slug' => $slug])->first();

        if (empty($data['artikel'])) {
            throw PageNotFoundException::forPageNotFound();
        }

        $data['title'] = $data['artikel']['judul'];
        return view('artikel/detail', $data);
    }
}