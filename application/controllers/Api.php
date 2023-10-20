<?php

use Kreait\Firebase\Database\Query;

defined('BASEPATH') or exit('No direct script access allowed');

class Api extends CI_Controller
{

    function register()
    {
        $data = json_decode(file_get_contents('php://input'), true);

 $sql = [

    'nama' => $data['nama'],
    'tempat' => $data['tempat'],
    'tanggallahir' => $data['tanggallahir'],
    'tanggal' => date('d/'),
    'bulan' => date('/'),
    'tahun' => date('Y/'),

 ];
            $this->db->insert('pengguna',$sql);
            if ($sql) {
                echo 212;
            } else {
                echo 505;
            }
        
    }





    function login()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $nama = $data['nama'];
        $tempat = $data['tempat'];
        $tanggallahir = $data['tanggallahir'];

        $sql = "SELECT * FROM pengguna WHERE nama='$nama' AND tempat='$tempat' AND tanggallahir='$tanggallahir'";

        // cek dulu
        $jumlah = $this->db->query($sql)->num_rows();

        if ($jumlah > 0) {
            $user = $this->db->query($sql)->row_array();
            echo json_encode(($user));
        } else {
            echo 212;
        }
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
