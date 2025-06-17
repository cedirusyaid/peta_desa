<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lokasi_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Get all locations
    public function get_all_lokasi() {
    	$this->db->join('gis_kategori', 'gis_kategori.kategori_id = gis_lokasi.lokasi_kategori', 'left');
    	$this->db->order_by('lokasi_id', 'desc');
        return $this->db->get('gis_lokasi')->result();
    }

    // Get location by id
    public function get_lokasi($id) {
        return $this->db->get_where('gis_lokasi', array('lokasi_id' => $id))->row();
    }

    // Add new location
    public function insert_lokasi($data) {
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
}
?>