<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    public function index() {
        $data['user'] = [
            'username' => $this->session->userdata('username'),
            'role' => $this->session->userdata('role'),
            'desa_id' => $this->session->userdata('desa_id')
        ];

        $this->load->view('template/header');
        $this->load->view('dashboard/index', $data);
        $this->load->view('template/footer');
    }
}
?>