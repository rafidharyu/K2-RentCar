<?php
require 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    // Lakukan sanitasi pada data yang diterima
    $id = mysqli_real_escape_string($conn, $id);
    $status = mysqli_real_escape_string($conn, $status);

    $updateSql = "UPDATE kendaraan SET status = '$status' WHERE id = $id";

    if ($conn->query($updateSql) === TRUE) {
        $_SESSION['kendaraan_error'] = "Status kendaraan berhasil diperbarui.";
    } else {
        $_SESSION['kendaraan_error'] = "Error updating record: " . $conn->error;
    }
} else {
    $_SESSION['kendaraan_error'] = "Invalid request method.";
}

$conn->close();

header("Location: AdminKendaraan.php");
exit();
?>
