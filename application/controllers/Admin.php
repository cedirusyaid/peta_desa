<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('admin_model');
        $this->load->library('upload','form_validation');
    }
    public function index() {

        if ($this->input->post()) {
            redirect('admin?desa_id='.$this->input->post('desa_id'));
        }
        
        
        // Load data for dropdown
        $data['desa_list'] = $this->admin_model->get_all_desa();
        
        // Get selected desa_id from GET or default to first desa
        $desa_id = $this->input->get('desa_id') ?? ($data['desa_list'][0]->desa_id ?? 0);
        $data['selected_desa'] = $desa_id;
        
        // Get locations for selected desa
        $data['locations'] = $this->admin_model->get_locations_by_desa($desa_id);
        
        // Load view
        $this->load->view('template/header', $data);
        $this->load->view('admin/list', $data);
        $this->load->view('template/footer', $data);
    }



    // Create - Show add form
    public function create() {
        $data['kategori_options'] = $this->admin_model->get_kategori_options();
        $data['all_desa'] = $this->admin_model->get_all_desa();
        $this->load->view('template/header', $data);
        $this->load->view('admin/form', $data);
        $this->load->view('template/footer', $data);
    }

    // Create - Show add form
    public function create_manual() {
        $data['kategori_options'] = $this->admin_model->get_kategori_options();
        $this->load->view('admin/admin_form_manual', $data);
    }

    // Store - Save new location
    public function store() {
        $this->form_validation->set_rules('lokasi_nama', 'Nama Lokasi', 'required');
        $this->form_validation->set_rules('lokasi_kategori', 'Kategori', 'required');
        $this->form_validation->set_rules('lokasi_alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('lokasi_lat', 'Latitude', 'required');
        $this->form_validation->set_rules('lokasi_long', 'Longitude', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['kategori_options'] = $this->admin_model->get_kategori_options();
            $data['all_desa'] = $this->admin_model->get_all_desa();
            $this->load->view('admin/form', $data);
        } else {
            $data = array(
                'lokasi_nama' => $this->input->post('lokasi_nama'),
                'lokasi_kategori' => $this->input->post('lokasi_kategori'),
                'lokasi_alamat' => $this->input->post('lokasi_alamat'),
                'lokasi_lat' => $this->input->post('lokasi_lat'),
                'lokasi_long' => $this->input->post('lokasi_long'),
                'desa_id' => $this->input->post('desa_id'),
                'lokasi_zindex' => 200,
                'lokasi_ket' => ''
            );

            $insert_data = $this->admin_model->insert_lokasi($data);
            $this->session->set_flashdata('message', 'Lokasi berhasil ditambahkan');
            redirect('admin/detail/'.$insert_data);
        }
    }

    // Edit - Show edit form
    public function edit($id) {
        $data['lokasi'] = $this->admin_model->get_lokasi($id);
        $data['all_desa'] = $this->admin_model->get_all_desa();
        $data['kategori_options'] = $this->admin_model->get_kategori_options();
        $this->load->view('admin/form', $data);
    }

    // Edit - Show detail
    public function detail($id) {
        $data['lokasi'] = $this->admin_model->get_lokasi($id);
        $data['all_desa'] = $this->admin_model->get_all_desa();
        $data['kategori_options'] = $this->admin_model->get_kategori_options();
        $data['fotos'] = $this->admin_model->get_foto_by_lokasi($id);

        $this->load->view('template/header', $data);
        $this->load->view('admin/detail', $data);
        $this->load->view('template/footer', $data);
    }

    // Update - Update existing location
    public function update($id) {
        $this->form_validation->set_rules('lokasi_nama', 'Nama Lokasi', 'required');
        $this->form_validation->set_rules('lokasi_kategori', 'Kategori', 'required');
        $this->form_validation->set_rules('lokasi_alamat', 'Alamat', 'required');
        $this->form_validation->set_rules('lokasi_lat', 'Latitude', 'required');
        $this->form_validation->set_rules('lokasi_long', 'Longitude', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['lokasi'] = $this->admin_model->get_lokasi($id);
            $data['all_desa'] = $this->admin_model->get_all_desa();
            $data['kategori_options'] = $this->admin_model->get_kategori_options();
            $this->load->view('admin/form', $data);
        } else {
            $data = array(
                'lokasi_nama' => $this->input->post('lokasi_nama'),
                'lokasi_kategori' => $this->input->post('lokasi_kategori'),
                'lokasi_alamat' => $this->input->post('lokasi_alamat'),
                'lokasi_lat' => $this->input->post('lokasi_lat'),
                'desa_id' => $this->input->post('desa_id'),
                'lokasi_long' => $this->input->post('lokasi_long')
            );

            $this->admin_model->update_lokasi($id, $data);
            $this->session->set_flashdata('message', 'Lokasi berhasil diperbarui');
            redirect('admin/detail/'.$id);
        }
    }

    // Delete - Delete location
    public function delete($id) {
        $this->admin_model->delete_lokasi($id);
        $this->session->set_flashdata('message', 'Lokasi berhasil dihapus');
        redirect('lokasi');
    }

    public function foto_upload($lokasi_id) {
        $config['upload_path'] = './uploads/foto_lokasi/';
        $config['allowed_types'] = 'jpg|jpeg|png|gif';
        $config['max_size'] = 20048; // 2MB
        $config['encrypt_name'] = TRUE;

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('foto_file')) {
            $error = $this->upload->display_errors();
            $this->session->set_flashdata('error', $error);
        } else {
            $upload_data = $this->upload->data();
            $data = array(
                'lokasi_id' => $lokasi_id,
                'foto_file' => $upload_data['file_name']
            );
            $this->admin_model->insert_foto($data);
            $this->session->set_flashdata('message', 'Foto berhasil diupload');
        }

        redirect('admin/detail/' . $lokasi_id);
    }

    // Delete photo
    public function foto_delete($foto_id, $lokasi_id) {
        if ($this->admin_model->foto_delete($foto_id, $lokasi_id)) {
            $this->session->set_flashdata('message', 'Foto berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus foto');
        }
        redirect('admin/detail/' . $lokasi_id);
    }    

}
?>