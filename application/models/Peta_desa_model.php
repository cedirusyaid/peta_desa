<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Peta_desa_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_desa() {
        $query = $this->db->get('kode_desa');
        return $query->result();
    }

    public function get_desa_by_id($desa_id) {
        $this->db->where('desa_id', $desa_id);
        $query = $this->db->get('kode_desa');
        return $query->row();
    }

    public function get_dusun_by_desa($desa_id) {
        $this->db->where('desa_id', $desa_id);
        $query = $this->db->get('kode_dusun');
        return $query->result();
    }

    // public function get_lokasi_by_desa($desa_id) {
    //     $this->db->where('desa_id', $desa_id);
    //     $query = $this->db->get('gis_lokasi');
    //     return $query->result();
    // }

    public function get_lokasi_by_desa($desa_id) {
        $this->db->select('l.lokasi_id, l.lokasi_nama, l.lokasi_kategori, l.lokasi_alamat, 
                          l.lokasi_lat, l.lokasi_long, l.lokasi_ket, 
                          k.kategori_id, k.kategori_nama, k.kategori_icon');
        $this->db->from('gis_lokasi l');
        $this->db->join('gis_kategori k', 'l.lokasi_kategori = k.kategori_id', 'left');
        $this->db->where('l.desa_id', $desa_id);
        $this->db->where('k.kategori_aktif', 1);
        
        return $this->db->get()->result();
    }

public function get_all_kategori_at_desa($desa_id) {
    $this->db->select('k.kategori_id, k.kategori_nama, k.kategori_icon, COUNT(l.lokasi_id) as jml');
    $this->db->from('gis_kategori k');
    $this->db->join('gis_lokasi l', 'k.kategori_id = l.lokasi_kategori AND l.desa_id = '.$desa_id, 'left');
    $this->db->where('k.kategori_aktif', 1);
    $this->db->group_by('k.kategori_id');
    $this->db->having('jml >', 0);  // Add this line to filter for count > 0
    $this->db->order_by('k.kategori_nama', 'ASC');
    return $this->db->get()->result();
}
    // public function get_all_kategori_at_desa($desa_id) {
    //     $this->db->select('k.kategori_id, k.kategori_nama, k.kategori_icon, COUNT(l.lokasi_id) as jml');
    //     $this->db->from('gis_kategori k');
    //     $this->db->join('gis_lokasi l', 'k.kategori_id = l.lokasi_kategori AND l.desa_id = '.$desa_id, 'left');
    //     $this->db->where('k.kategori_aktif', 1);
    //     $this->db->group_by('k.kategori_id');
    //     $this->db->order_by('k.kategori_nama', 'ASC');
    //     return $this->db->get()->result();
    // }
    public function get_all_kategori() {
        $query = $this->db->get('gis_kategori');
        return $query->result();
    }

    public function get_foto_lokasi($lokasi_id) {
        $this->db->where('lokasi_id', $lokasi_id);
        $query = $this->db->get('foto_lokasi');
        return $query->result();
    }
}