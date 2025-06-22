<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Auth_model');
    }

    public function login() {
        // Jika sudah login, redirect ke dashboard
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('template/header');
            $this->load->view('auth/login');
            $this->load->view('template/footer');
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');

            $user = $this->Auth_model->login($username, $password);

            if ($user) {
                // Set session data
                $session_data = [
                    'user_id'    => $user->id,
                    'username'   => $user->username,
                    'desa_id'    => $user->desa_id,
                    'is_admin'   => $user->is_admin,
                    'role'       => $this->Auth_model->get_user_role($user),
                    'logged_in'  => TRUE
                ];

                $this->session->set_userdata($session_data);
                $this->session->set_flashdata('success', 'Login successful');
                redirect('admin');
            } else {
                $this->session->set_flashdata('error', 'Invalid username or password');
                redirect('auth/login');
            }
        }
    }

    public function logout() {
        // Unset session data
        $this->session->unset_userdata(['user_id', 'username', 'desa_id', 'is_admin', 'role', 'logged_in']);
        $this->session->set_flashdata('success', 'You have been logged out');
        redirect('auth/login');
    }
}
?>