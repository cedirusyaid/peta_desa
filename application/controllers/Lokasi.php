<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lokasi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('lokasi_model');
        $this->load->library('form_validation');
    }

    // Index - List all locations
    public function index() {
        $data['lokasi'] = $this->lokasi_model->get_all_lokasi();
        $this->load->view('lokasi/lokasi_list', $data);
    }

    // Create - Show add form
    public function create() {
        $data['kategori_options'] = $this->lokasi_model->get_kategori_options();
        $this->load->view('lokasi/lokasi_form', $data);
    }

    // Store - Save new location
    public function store() {
        $this->form_validation->set_rules('lokasi_nama', 'Nama Lokasi', 'required');
        $this->form_validation->set_rules('lokasi_kategori', 'Kategori', 'required');
        $this->form_validation->set_rules('lokasi_alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('lokasi_lat', 'Latitude', 'required');
        $this->form_validation->set_rules('lokasi_long', 'Longitude', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['kategori_options'] = $this->lokasi_model->get_kategori_options();
            $this->load->view('lokasi/lokasi_form', $data);
        } else {
            $data = array(
                'lokasi_nama' => $this->input->post('lokasi_nama'),
                'lokasi_kategori' => $this->input->post('lokasi_kategori'),
                'lokasi_alamat' => $this->input->post('lokasi_alamat'),
                'lokasi_lat' => $this->input->post('lokasi_lat'),
                'lokasi_long' => $this->input->post('lokasi_long'),
                'lokasi_desa' => 'Desa Saotengnga',
                'kecamatan_id' => '730704',
                'lokasi_zindex' => 200,
                'lokasi_ket' => '',
                'unit_id' => '',
                'lokasi_panoramio' => NULL,
                'lokasi_slug' => NULL,
                'lokasi_13' => NULL,
                'lokasi_14' => NULL,
                'lokasi_15' => NULL
            );

            $this->lokasi_model->insert_lokasi($data);
            $this->session->set_flashdata('message', 'Lokasi berhasil ditambahkan');
            redirect('lokasi');
        }
    }

    // Edit - Show edit form
    public function edit($id) {
        $data['lokasi'] = $this->lokasi_model->get_lokasi($id);
        $data['kategori_options'] = $this->lokasi_model->get_kategori_options();
        $this->load->view('lokasi/lokasi_form', $data);
    }

    // Update - Update existing location
    public function update($id) {
        $this->form_validation->set_rules('lokasi_nama', 'Nama Lokasi', 'required');
        $this->form_validation->set_rules('lokasi_kategori', 'Kategori', 'required');
        $this->form_validation->set_rules('lokasi_alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('lokasi_lat', 'Latitude', 'required');
        $this->form_validation->set_rules('lokasi_long', 'Longitude', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['lokasi'] = $this->lokasi_model->get_lokasi($id);
            $data['kategori_options'] = $this->lokasi_model->get_kategori_options();
            $this->load->view('lokasi/lokasi_form', $data);
        } else {
            $data = array(
                'lokasi_nama' => $this->input->post('lokasi_nama'),
                'lokasi_kategori' => $this->input->post('lokasi_kategori'),
                'lokasi_alamat' => $this->input->post('lokasi_alamat'),
                'lokasi_lat' => $this->input->post('lokasi_lat'),
                'lokasi_long' => $this->input->post('lokasi_long')
            );

            $this->lokasi_model->update_lokasi($id, $data);
            $this->session->set_flashdata('message', 'Lokasi berhasil diperbarui');
            redirect('lokasi');
        }
    }

    // Delete - Delete location
    public function delete($id) {
        $this->lokasi_model->delete_lokasi($id);
        $this->session->set_flashdata('message', 'Lokasi berhasil dihapus');
        redirect('lokasi');
    }
}
?>