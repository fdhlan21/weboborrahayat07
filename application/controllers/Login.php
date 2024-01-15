<?php
defined('BASEPATH') or exit('No direct script access allowed');

class login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
    }


    public function index()
    {
        $this->form_validation->set_rules('username', 'Username', 'trim|required|callback_check_username', [
            'check_username' => 'This username is not registered.'
        ]);
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        if ($this->form_validation->run() == false) {

            $data['title'] = 'Login Page';
            $this->load->view('templates/login_header', $data);
            $this->load->view('page/logindanregister/login');
            $this->load->view('templates/login_footer');
        } else {

            $this->_login();
        }
    }

    public function check_username($username)
    {
        $this->db->where('username', $username);
        $query = $this->db->get('useradmin');

        if ($query->num_rows() > 0) {
            return true; // Username terdaftar
        } else {
            return false; // Username tidak terdaftar
        }
    }
    private function _login()

    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $admin = $this->db->get_where('useradmin', ['username' => $username])->row_array();

        if ($admin) {
            // Jika username terdaftar, cek password
            if (password_verify($password, $admin['password'])) {
                $data = [
                    'username' => $admin['username'],
                    'role_id' => $admin['role_id']
                ];
                $this->session->set_userdata($data);
                if ($admin['role_id'] == 1) {
                    redirect('dashboard');
                } else if ($admin['role_id'] == 2) {
                    redirect('dashboard');
                }
            } else {
                // Jika password tidak cocok, tampilkan pesan kesalahan
                $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
                Wrong password!
            </div>');
                redirect('login');
            }
        } else {
            // Jika username tidak terdaftar, tampilkan pesan kesalahan
            $this->session->set_flashdata('message', '<div class="alert alert-danger" role="alert">
            This username is not registered.
        </div>');
            redirect('login');
        }
    }



    public function register()
    {


        $this->form_validation->set_rules('username', 'username', 'required|trim|is_unique[useradmin.username]', [
            'is_unique' => 'This email has already been taken!'
        ]);
        $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[3]|matches[password]', [
            'min_length' => 'Password too short!',
        ]);

        if ($this->form_validation->run() == false) {

            $data['title'] = 'OBOR RAHAYAT 07 - Register';
            $this->load->view('templates/login_header', $data);
            $this->load->view('page/logindanregister/register');
            $this->load->view('templates/login_footer');
        } else {

            $data = [
                'username' => htmlspecialchars($this->input->post('username', true)),
                'password' => password_hash(
                    $this->input->post('password'),
                    PASSWORD_DEFAULT
                ),
                'role_id' => 2,
            ];

            $this->db->insert('useradmin',  $data);
            $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
Selamat!, Anda telah berhasil membuat akun.
</div>');
            redirect('login');
        }
    }



    public function logout()

    {
        $this->session->unset_userdata('username');
        $this->session->unset_userdata('role_id');

        $this->session->set_flashdata('message', '<div class="alert alert-success" role="alert">
 Kamu telah keluar dari situs!
</div>');
        redirect('login');
    }
}
