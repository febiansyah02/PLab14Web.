<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\ArtikelModel;

class AjaxController extends Controller {
    public function index() {
        return view('ajax/index', ['title' => 'Data Artikel (AJAX)']);
    }

    public function getData() {
        $model = new ArtikelModel();
        return $this->response->setJSON($model->findAll());
    }

    public function delete($id) {
        $model = new ArtikelModel();
        $model->delete($id);
        return $this->response->setJSON(['status' => 'OK']);
    }
}