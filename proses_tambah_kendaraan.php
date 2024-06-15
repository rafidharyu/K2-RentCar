<?php
require 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nomor_polisi = $_POST['nomor_polisi'];
    $status = $_POST['status'];
    $model_kendaraan = $_POST['model_kendaraan'];

    // Periksa apakah nomor polisi sudah ada dalam database
    $checkPolisiSql = "SELECT * FROM kendaraan WHERE nomor_polisi = ?";
    $stmtPolisi = $conn->prepare($checkPolisiSql);
    $stmtPolisi->bind_param("s", $nomor_polisi);
    $stmtPolisi->execute();
    $resultPolisi = $stmtPolisi->get_result();

    if ($resultPolisi->num_rows > 0) {
        $message = "Nomor polisi sudah ada.";
        header("Location: AdminKendaraan.php?message=" . urlencode($message));
        exit();
    }

    // Periksa apakah model kendaraan sudah ada dalam database
    $checkSql = "SELECT * FROM kendaraan WHERE model_kendaraan = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("s", $model_kendaraan);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Jika model kendaraan sudah ada, ambil deskripsi dari kendaraan yang ada
        $row = $result->fetch_assoc();
        $jenis_mesin = $row['jenis_mesin'];
        $kecepatan = $row['kecepatan'];
        $jenis_bbm = $row['jenis_bbm'];
        $jumlah_kursi = $row['jumlah_kursi'];
        $harga_per_hari = $row['harga_per_hari'];
        $gambar = $row['gambar']; // Gambar dari kendaraan yang ada

        $message = "Deskripsi kendaraan dengan model yang sama ditemukan, menggunakan data yang ada.";

    } else {
        // Jika model kendaraan belum ada, gunakan data dari form
        $jenis_mesin = $_POST['jenis_mesin'];
        $kecepatan = $_POST['kecepatan'];
        $jenis_bbm = $_POST['jenis_bbm'];
        $jumlah_kursi = $_POST['jumlah_kursi'];
        $harga_per_hari = $_POST['harga_per_hari'];
        $gambar = addslashes(file_get_contents($_FILES['gambar']['tmp_name']));

        $message = "Model kendaraan baru, menggunakan data dari form.";
        
    }

    // Query untuk insert kendaraan
    $sql = "INSERT INTO kendaraan (gambar, nomor_polisi, model_kendaraan, jenis_mesin, kecepatan, jenis_bbm, jumlah_kursi, harga_per_hari, status) 
            VALUES ('$gambar', '$nomor_polisi', '$model_kendaraan', '$jenis_mesin', '$kecepatan', '$jenis_bbm', $jumlah_kursi, '$harga_per_hari', '$status')";

    if ($conn->query($sql) === TRUE) {
        $message = "New record created successfully";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    header("Location: AdminKendaraan.php?message=" . urlencode($message));
}
?>