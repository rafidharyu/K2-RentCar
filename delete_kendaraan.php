<?php
require 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = $_POST['id'];

    // Lakukan sanitasi pada data yang diterima
    $id = mysqli_real_escape_string($conn, $id);

    $deleteSql = "DELETE FROM kendaraan WHERE id = '$id'";

    if ($conn->query($deleteSql) === TRUE) {
        $_SESSION['kendaraan_success'] = "Kendaraan berhasil dihapus.";
    } else {
        $_SESSION['kendaraan_error'] = "Error deleting record: " . $conn->error;
    }
} else {
    $_SESSION['kendaraan_error'] = "Invalid request method.";
}

$conn->close();

header("Location: AdminKendaraan.php");
exit();
?>
