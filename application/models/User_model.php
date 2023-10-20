<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function getUserById($id)
    {
        return $this->db->get_where('users', ['id' => $id])->row_array();
    }

    public function editUser($id)
    {
        $data = [
            'name' => $this->input->post('name'),
            'username' => $this->input->post('username'),
            'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT)
        ];

        $this->db->where('id', $id);
        $this->db->update('users', $data);
    }

    public function deleteUser($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('users');
    }
}
