<?php

use Kreait\Firebase\Database\Query;

defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{

    function register()
    {
        $data = json_decode(file_get_contents('php://input'), true);
    
        $username = $data['username'];
        $password = md5($data['password']);
    
        $sql = "INSERT INTO pengguna (username, password, approve) VALUES ('$username', '$password', 0)";
    
        $cek = $this->db->query("SELECT * FROM pengguna WHERE username = '$username'")->num_rows();
    
        if ($cek > 0) {
            echo json_encode(array("status" => 404, "message" => "Email is already exist!"));
        } else {
            $this->db->query($sql);
            echo json_encode(array("status" => 200, "message" => "Congratulation, You have been registered!"));
        }
    }
    

    function approveuser()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $username = $data['username'];
    
        $updateSql = "UPDATE pengguna SET approve = 1 WHERE username = '$username'";
        $this->db->query($updateSql);
    
        echo json_encode(array("status" => 200, "message" => "User approved successfully!"));
    }
    

    

    function loginkonseling()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $nik = $data['nik'];
        $nama_sekolah = $data['nama_sekolah'];
        $nama_desa = $data['nama_desa'];

        $sql = "SELECT * FROM konselingremaja WHERE nik='$nik' AND nama_sekolah='$nama_sekolah' AND nama_desa='$nama_desa'";

        // cek dulu
        $jumlah = $this->db->query($sql)->num_rows();

        if ($jumlah > 0) {
            $user = $this->db->query($sql)->row_array();
            $buttonPressCount = $user['konseling'];

            // Increment buttonPressCount
            $newButtonPressCount = $buttonPressCount + 1;

            // Update buttonPressCount in database
            $updateSql = "UPDATE konselingremaja SET konseling='$newButtonPressCount' WHERE nik='$nik'";
            $this->db->query($updateSql);

            // Retrieve updated user data
            $updatedUser = $this->db->query($sql)->row_array();
            echo json_encode($updatedUser);
        } else {
            echo 200;
        }
    }


    

    function konselingremaja()
    {
        $data = json_decode(file_get_contents('php://input'), true);



    $nik = $data['nik'];
    $nama_sekolah = $data['nama_sekolah'];
    $nama_desa =    $data['nama_desa'];





        $SQL = "SELECT * FROM konselingremaja WHERE nik='$nik'";
        $jumlah = $this->db->query($SQL)->num_rows();

        if ($jumlah > 0) {
            $user = $this->db->query($SQL)->row_array();
            echo json_encode(($user));
            echo "NIK SUDAH TERDAFTAR'";
        } else {
            $sql = [
                'nik' =>  $data['nik'],
                'nama_sekolah' => $data['nama_sekolah'],
                'nama_desa' => $data['nama_desa'],
                'tanggal' => date('d/'),
                'bulan' => date('m/'),
                'tahun' => date('Y/'),
            ];
            $this->db->insert('konselingremaja',$sql);
            // $this->db->insert('konselingremaja', $data1);
            if ($sql) {
               
                echo 200;
            } else {
                echo 505;
            }
        }
    }


    function slider()
    {
        include(APPPATH . 'config/database.php');
        $host = $db['default']['hostname'];
        $dbname   = $db['default']['database'];
        $username = $db['default']['username'];
        $password = $db['default']['password'];

        $koneksi = mysqli_connect($host, $username, $password, $dbname);



        // Mendapatkan data gambar slider dari database
        $query = "SELECT * FROM slider";
        $result = mysqli_query($koneksi, $query);

        // Membuat array kosong untuk menampung data gambar slider
        $sliderImages = array();

        // Mengambil data gambar slider dari hasil query dan menambahkannya ke array
        while ($row = mysqli_fetch_assoc($result)) {
            $sliderImages[] = array(
                'url' => 'http://10.255.5.229/besti/assets/img/slider/' . $row['nama'],// Ganti dengan URL gambar sesuai dengan direktori di server Anda
                'keterangan' => $row['keterangan']
            );
        }

        // Mengirimkan data gambar slider dalam format JSON
        header('Content-Type: application/json');
        echo json_encode($sliderImages);
    }

    function namaaplikasi()
    {

        $koneksi = mysqli_connect("localhost", "root", "", "bestiedatabase");

        // Query untuk mengambil data dari tabel nama_aplikasi
        $sql = "SELECT id, app_name FROM nama_aplikasi";
        $result = $koneksi->query($sql);

        // Memeriksa hasil query
        if ($result->num_rows > 0) {
            // Mengambil setiap baris data
            while ($row = $result->fetch_assoc()) {
                $id = $row["id"];
                $appName = $row["app_name"];
                // Gunakan nilai yang diambil sesuai kebutuhan Anda
                echo "ID: " . $id . ", App Name: " . $appName . "<br>";
            }
        } else {
            echo "Tidak ada data yang ditemukan";
        }

        // Menutup koneksi ke database
        $koneksi->close();

    }

}
