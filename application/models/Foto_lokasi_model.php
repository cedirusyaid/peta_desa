<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Foto_lokasi_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Get all photos by location_id
    public function get_by_lokasi($lokasi_id) {
        return $this->db->get_where('foto_lokasi', array('lokasi_id' => $lokasi_id))->result();
    }

    // Get single photo
    public function get_foto($foto_id) {
        return $this->db->get_where('foto_lokasi', array('foto_lokasi_id' => $foto_id))->row();
    }

    // Add new photo
    public function insert_foto($data) {
        $this->db->insert('foto_lokasi', $data);
        return $this->db->insert_id();
    }

    // Delete photo
    public function delete_foto($foto_id) {
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
}