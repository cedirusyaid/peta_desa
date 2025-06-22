<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    public function index() {
        $data['users'] = $this->User_model->get_all();
        $this->load->view('template/header');
        $this->load->view('user/index', $data);
        $this->load->view('template/footer');
    }

    public function create() {
        $data['desa_list'] = $this->User_model->get_all_desa();
        $data['form_action'] = 'user/create';
        $data['is_edit'] = false;

        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
        $this->form_validation->set_rules('password_confirmation', 'Password Confirmation', 'required|matches[password]');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('template/header');
            $this->load->view('user/form', $data);
            $this->load->view('template/footer');
        } else {
            $data = [
                'username' => $this->input->post('username'),
                'password' => $this->input->post('password'),
                'desa_id' => $this->input->post('desa_id'),
                'is_admin' => $this->input->post('is_admin') ? 1 : 0
            ];

            $this->User_model->create($data);
            $this->session->set_flashdata('success', 'User created successfully');
            redirect('user');
        }
    }

    public function edit($id) {
        $data['user'] = $this->User_model->get_by_id($id);
        $data['desa_list'] = $this->User_model->get_all_desa();
        $data['form_action'] = 'user/edit/'.$id;
        $data['is_edit'] = true;

        $this->form_validation->set_rules('username', 'Username', 'required');
        if ($this->input->post('password')) {
            $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
            $this->form_validation->set_rules('password_confirmation', 'Password Confirmation', 'matches[password]');
        }

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('template/header');
            $this->load->view('user/form', $data);
            $this->load->view('template/footer');
        } else {
            $update_data = [
                'username' => $this->input->post('username'),
                'desa_id' => $this->input->post('desa_id'),
                'is_admin' => $this->input->post('is_admin') ? 1 : 0
            ];

            if ($this->input->post('password')) {
                $update_data['password'] = $this->input->post('password');
            }

            $this->User_model->update($id, $update_data);
            $this->session->set_flashdata('success', 'User updated successfully');
            redirect('user');
        }
    }

    public function delete($id) {
        $this->User_model->delete($id);
        $this->session->set_flashdata('success', 'User deleted successfully');
        redirect('user');
    }
}
?>