<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Peta extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Peta_model');
    }

    public function index() {
        $data['desa_list'] = $this->Peta_model->get_all_desa();
        $data['google_maps_api_key'] = MAP_API;
        $this->load->view('template/header');
        $this->load->view('peta/index', $data);
        $this->load->view('template/footer');
    }

//     public function detail($desa_id, $kategori_id=0) {
//         $desa = $this->Peta_model->get_desa_by_id($desa_id);
        
//         if (!$desa) {
//             show_404();
//         }
//         $data['desa'] = $desa;

//         $data['lokasi'] = $this->Peta_model->get_lokasi_by_desa($desa_id);
// // print_r($data);die();        
//         $data['kategori'] = $this->Peta_model->get_all_kategori_at_desa($desa_id);
//         $data['google_maps_api_key'] = MAP_API;

//         $this->load->view('template/header');
//         $this->load->view('peta/detail_leaflet', $data);
//         $this->load->view('template/footer');
//     }
    public function detail($desa_id, $kategori_id = 0) {
        $desa = $this->Peta_model->get_desa_by_id($desa_id);
        
        if (!$desa) {
            show_404();
        }
        $data['desa'] = $desa;

        // Gunakan fungsi yang sudah dimodifikasi
        $data['lokasi'] = $this->Peta_model->get_lokasi_by_desa_and_kategori($desa_id, $kategori_id);
        
        $data['kategori'] = $this->Peta_model->get_all_kategori_at_desa($desa_id);
        $data['selected_kategori'] = $kategori_id;
        $data['google_maps_api_key'] = MAP_API;

        $this->load->view('template/header');
        $this->load->view('peta/detail_leaflet', $data);
        $this->load->view('template/footer');
    }

}