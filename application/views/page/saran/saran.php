<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saran Website</title>

    <style>
        /* Gaya untuk pesan kesuksesan */
        #successModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            position: relative;
            animation: fadeIn 0.5s ease-out;
            max-width: 50%; /* Lebar modal setengah dari lebar layar */
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }

        .checkmark {
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            border: 2px solid #5cb85c;
            width: 40px;
            height: 40px;
            margin: 0 auto 20px;
            background-color: #5cb85c;
            color: #fff;
            font-size: 24px;
        }
    </style>

    <!-- Tambahkan script JavaScript untuk menampilkan alert -->
    <script>
        function showSuccessModal() {
            document.getElementById('successModal').style.display = 'flex';
        }

        function closeSuccessModal() {
            document.getElementById('successModal').style.display = 'none';
        }
    </script>
</head>
<body>

<div style="max-width: 600px; margin: 50px auto; padding: 20px; background-color: #fff; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
    <h2 style="text-align: center; font-family: 'Poppins', sans-serif; font-weight: 600; color: black;">Silakan Berikan Saran Anda</h2>

    <!-- Pesan Kesuksesan Modal -->
    <div id="successModal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeSuccessModal()">&#10006;</span>
            <div class="checkmark">&#10003;</div>
            <p style="font-size: 18px; margin-top: 10px;">Berhasil! Terima kasih telah memberikan saran.</p>
        </div>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Mengambil data dari formulir
        $namaLengkap = $_POST["nama_lengkap"];
        $masukan = $_POST["masukan"];

        // Mengatur zona waktu ke Asia/Jakarta
        date_default_timezone_set("Asia/Jakarta");

        // Mendapatkan tanggal dan waktu saat ini
        $tanggal = date("Y-m-d");

        // Mengambil konfigurasi koneksi ke database dari file database.php di folder config CodeIgniter
        include(APPPATH . 'config/database.php');

        $host = $db['default']['hostname'];
        $dbname = $db['default']['database'];
        $username = $db['default']['username'];
        $password = $db['default']['password'];

        try {
            $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $pdo = new PDO($dsn, $username, $password, $options);

            // Query SQL untuk menyimpan data ke tabel masukan
            $queryInsert = "INSERT INTO masukan (nama_lengkap, masukan, tanggal) VALUES (?, ?, ?)";
            $stmtInsert = $pdo->prepare($queryInsert);
            $stmtInsert->execute([$namaLengkap, $masukan, $tanggal]);

            // Menampilkan pesan kesuksesan
            echo '<script>showSuccessModal();</script>';

        } catch (PDOException $e) {
            die("Koneksi ke database gagal: " . $e->getMessage());
        }

        // Menutup koneksi ke database
        $pdo = null;
    }
    ?>

    <form action="" method="post" style="display: flex; flex-direction: column;">

        <!-- Nama Lengkap -->
        <label for="nama_lengkap" style="margin-bottom: 8px;">Nama Lengkap:</label>
        <p style="font-size: 12px; margin-bottom: 8px;">Tolong isi nama lengkap Anda dengan benar.</p>
        <input type="text" id="nama_lengkap" name="nama_lengkap" required style="padding: 10px; margin-bottom: 16px;">

        <!-- Saran -->
        <label for="masukan" style="margin-bottom: 8px;">Saran:</label>
        <p style="font-size: 12px; margin-bottom: 8px;">Tolong berikan saran Anda kepada kami yang menunjukkan untuk organisasi Karang Taruna.</p>
        <textarea id="masukan" name="masukan" rows="4" required style="padding: 10px; margin-bottom: 16px;"></textarea>

        <!-- Tombol Submit -->
        <button type="submit" style="padding: 12px; background-color: #3498db; color: #fff; border: none; cursor: pointer;">Kirim Saran</button>
    
    </form>
</div>

</body>
</html>
