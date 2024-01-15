<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function index()
    {
        $data['title'] = 'OBOR RAHAYAT 07 - Dashboard';
        $data['useradmin'] = $this->db->get_where('useradmin', ['username' =>
        $this->session->userdata('username')])->row_array();

        // Cek role_id pengguna
        if ($data['useradmin']['role_id'] == 1) {
            // Jika role_id adalah 1 (useradmin), tampilkan view useradmin
            $this->load->view('templates/header', $data);
            $this->load->view('topbar', $data);
            $this->load->view('page/dashboard/index', $data);
            $this->load->view('templates/footer');
        } elseif ($data['useradmin']['role_id'] == 2) {
            // Jika role_id adalah 2 (user), tampilkan view user
            $this->load->view('templates/header', $data);
            $this->load->view('topbar', $data);
            $this->load->view('page/dashboard/index', $data);
            $this->load->view('templates/footer');
        } else {
            // Jika role_id tidak valid, tampilkan pesan error
            $this->load->view('templates/header', $data);
            $this->load->view('templates/topbar', $data);
            $this->load->view('admin/error', $data);
            $this->load->view('templates/footer');
        }
    }
}
