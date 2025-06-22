<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Peta_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_desa() {
        $this->db->select('desa_id, desa_nama');
        $this->db->from('kode_desa');
        $this->db->order_by('desa_nama', 'ASC');
        return $this->db->get()->result();
    }

    public function get_desa_by_id($desa_id) {
        return $this->db->get_where('kode_desa', ['desa_id' => $desa_id])->row();
    }

    public function get_lokasi_by_desa_and_kategori($desa_id, $kategori_id) {
        $this->db->select('l.lokasi_id, l.lokasi_nama, l.lokasi_kategori, l.lokasi_alamat, 
                          l.lokasi_lat, l.lokasi_long, l.lokasi_ket, 
                          k.kategori_nama, k.kategori_icon');
        $this->db->from('gis_lokasi l');
        $this->db->join('gis_kategori k', 'l.lokasi_kategori = k.kategori_id', 'left');
        $this->db->where('l.desa_id', $desa_id);
        $this->db->where('k.kategori_aktif', 1);
        
        if ($kategori_id != 0) {
            $this->db->where('l.lokasi_kategori', $kategori_id);
        }
        
        return $this->db->get()->result();
    }

    public function get_all_kategori_at_desa($desa_id) {
        $this->db->select('k.kategori_id, k.kategori_nama, k.kategori_icon, COUNT(l.lokasi_id) as jml');
        $this->db->from('gis_kategori k');
        $this->db->join('gis_lokasi l', 'k.kategori_id = l.lokasi_kategori AND l.desa_id = '.$desa_id, 'left');
        $this->db->where('k.kategori_aktif', 1);
        $this->db->group_by('k.kategori_id');
        $this->db->order_by('k.kategori_nama', 'ASC');
        return $this->db->get()->result();
    }

    public function get_foto_lokasi($lokasi_id) {
        return $this->db->get_where('foto_lokasi', ['lokasi_id' => $lokasi_id])->result();
    }
}