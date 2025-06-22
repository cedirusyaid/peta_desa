<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Peta_desa extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Peta_desa_model');
        $this->load->model('admin_model'); // tambahkan ini

        $this->load->helper('url');
    }

    public function index() {
        $data['desa_list'] = $this->Peta_desa_model->get_all_desa();
        $this->load->view('template/header');
        $this->load->view('peta_desa/index', $data);
        $this->load->view('template/footer');
    }

    public function peta($desa_id) {
        // Validasi desa_id
        if(!is_numeric($desa_id)) {
            show_404();
        }

        // Ambil data desa
        $desa = $this->Peta_desa_model->get_desa_by_id($desa_id);
        if(!$desa) {
            show_404();
        }

        // Ambil data untuk peta
        $data = [
            'desa' => $desa,
            'dusun_list' => $this->Peta_desa_model->get_dusun_by_desa($desa_id),
            'lokasi_list' => $this->Peta_desa_model->get_lokasi_by_desa($desa_id),
            'kategori_list' => $this->Peta_desa_model->get_all_kategori_at_desa($desa_id)
        ];
// print_r($data['kategori_list']); die();
        // $this->load->view('template/header');
        $this->load->view('peta_desa/peta', $data);
        // $this->load->view('template/footer');
    }

    public function detail($id) {
        $data['lokasi'] = $this->admin_model->get_lokasi($id);
        $data['all_desa'] = $this->admin_model->get_all_desa();
        $data['kategori_options'] = $this->admin_model->get_kategori_options();
        $data['fotos'] = $this->admin_model->get_foto_by_lokasi($id);

        $this->load->view('template/header', $data);
        $this->load->view('peta_desa/detail', $data);
        $this->load->view('template/footer', $data);
    }

}