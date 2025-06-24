<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Git_pull extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        // Batasi hanya bisa diakses via CLI atau IP tertentu
        // if (!$this->input->is_cli_request()) {
        //     $allowed_ips = ['127.0.0.1', '192.168.1.100']; // Ganti dengan IP yang diizinkan
        //     if (!in_array($_SERVER['REMOTE_ADDR'], $allowed_ips)) {
        //         show_error('Access denied', 403);
        //     }
        // }
    }

    public function pull() {
        // Path ke direktori project Anda
        $path = FCPATH; // FCPATH adalah konstanta CI untuk path root
        
        // Perintah git pull
        $command = "cd {$path} && git pull origin main 2>&1";
        
        // Eksekusi perintah
        exec($command, $output, $return_var);
        
        // Format output
        $result = [
            'status' => ($return_var === 0) ? 'success' : 'error',
            'return_var' => $return_var,
            'output' => implode("\n", $output)
        ];
        
        // Tampilkan hasil
        header('Content-Type: application/json');
        echo json_encode($result, JSON_PRETTY_PRINT);
    }
}