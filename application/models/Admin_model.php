<?php
// defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();

    }
// public function get_all_desa() {
//     // Get all desa
//     $desa_list = $this->db->get('kode_desa')->result();
    
//     // Add an option for unassigned locations
//     $unassigned = new stdClass();
//     $unassigned->desa_id = 0;
//     $unassigned->desa_nama = 'Lokasi Belum Terdaftar Desa';
//     array_unshift($desa_list, $unassigned);
    
//     return $desa_list;
// }



    public function get_all_desa() {
        $this->db->select('kd.desa_id, kd.desa_nama, kc.kecamatan_id, kc.kecamatan_nama');
        $this->db->from('kode_desa kd');
        $this->db->join('kode_kecamatan kc', 'kc.kecamatan_id = kd.kecamatan_id', 'left');
        $this->db->order_by('kc.kecamatan_nama', 'ASC');
        $this->db->order_by('kd.desa_nama', 'ASC');
        $desa_list = $this->db->get()->result();
        // Add an option for unassigned locations
        $unassigned = new stdClass();
        $unassigned->desa_id = 0;
        $unassigned->desa_nama = 'Lokasi Belum Terdaftar Desa';
        array_unshift($desa_list, $unassigned);
        
        return $desa_list;
    }

public function get_locations_by_desa($desa_id) {
    $this->db->select('gis_lokasi.*, gis_kategori.kategori_nama, gis_kategori.kategori_icon');
    $this->db->from('gis_lokasi');
    $this->db->join('gis_kategori', 'gis_lokasi.lokasi_kategori = gis_kategori.kategori_id', 'left');
    
    if ($desa_id == 0) {
        // Filter for locations with no desa assigned
        $this->db->where('gis_lokasi.desa_id IS NULL OR gis_lokasi.desa_id = 0');
    } else {
        $this->db->where('gis_lokasi.desa_id', $desa_id);
    }
    
    $query = $this->db->get();
    return $query->result();
}

    // Get location by id
    public function get_lokasi($id) {


        $this->db->select('gis_lokasi.*, gis_kategori.kategori_nama, gis_kategori.kategori_icon, kode_desa.desa_nama');
        $this->db->from('gis_lokasi');
        $this->db->join('gis_kategori', 'gis_lokasi.lokasi_kategori = gis_kategori.kategori_id', 'left');
        $this->db->join('kode_desa', 'gis_lokasi.desa_id = kode_desa.desa_id', 'left');
        $this->db->where('gis_lokasi.lokasi_id', $id);
        $query = $this->db->get();
        return $query->row();



    }

    // Add new location
    public function insert_lokasi($data) {
        // print_r($data);die();
        $this->db->insert('gis_lokasi', $data);
        return $this->db->insert_id();
    }

    // Update location
    public function update_lokasi($id, $data) {
        $this->db->where('lokasi_id', $id);
        return $this->db->update('gis_lokasi', $data);
    }

    // Delete location
    public function delete_lokasi($id) {
        $this->db->where('lokasi_id', $id);
        return $this->db->delete('gis_lokasi');
    }

    // Get all categories for dropdown
    public function get_kategori_options() {
        $this->db->select('kategori_id, kategori_nama');
        $this->db->where('kategori_aktif', 1);
        $query = $this->db->get('gis_kategori');
        return $query->result();
    }

    public function get_foto_by_lokasi($lokasi_id) {
        return $this->db->get_where('foto_lokasi', array('lokasi_id' => $lokasi_id))->result();
    }

    // Upload new photo
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

    // Get single photo
    public function get_foto($foto_id) {
        return $this->db->get_where('foto_lokasi', array('foto_lokasi_id' => $foto_id))->row();
    }

    // Delete photo
    public function foto_delete($foto_id) {
        $foto = $this->get_foto($foto_id);
        if ($foto) {
            // Delete file from server
            $file_path = FCPATH . 'uploads/foto_lokasi/' . $foto->foto_file;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            // Delete record from database
            return $this->db->delete('foto_lokasi', array('foto_lokasi_id' => $foto_id));
        }
        return false;
    }
    // // Delete photo
    // public function foto_delete($foto_id, $lokasi_id) {
    //     if ($this->admin_model->delete_foto($foto_id)) {
    //         $this->session->set_flashdata('message', 'Foto berhasil dihapus');
    //     } else {
    //         $this->session->set_flashdata('error', 'Gagal menghapus foto');
    //     }
    //     redirect('admin/detail/' . $lokasi_id);
    // }
    public function insert_foto($data) {
        $this->db->insert('foto_lokasi', $data);
        return $this->db->insert_id();
    }

}
?>