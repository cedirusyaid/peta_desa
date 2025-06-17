<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Foto_lokasi extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('foto_lokasi_model');
        $this->load->library('upload');
    }

    // Index - Show photos by location_id
    public function index($lokasi_id) {
        $data['fotos'] = $this->foto_lokasi_model->get_by_lokasi($lokasi_id);
        $data['lokasi_id'] = $lokasi_id;
        $this->load->view('lokasi/foto_lokasi_list', $data);
    }

    // Upload new photo
    public function upload($lokasi_id) {
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
            $this->foto_lokasi_model->insert_foto($data);
            $this->session->set_flashdata('message', 'Foto berhasil diupload');
        }

        redirect('foto_lokasi/index/' . $lokasi_id);
    }

    // Delete photo
    public function delete($foto_id, $lokasi_id) {
        if ($this->foto_lokasi_model->delete_foto($foto_id)) {
            $this->session->set_flashdata('message', 'Foto berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus foto');
        }
        redirect('foto_lokasi/index/' . $lokasi_id);
    }
}
