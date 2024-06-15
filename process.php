<?php
$servername = "localhost";
$username = "root"; // sesuaikan dengan user MySQL Anda
$password = ""; // sesuaikan dengan password MySQL Anda
$dbname = "k2rentcar";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    $sql = "INSERT INTO users (fullname, phone_number, username, email) VALUES ('$fullname', '$phone', '$username', '$email')";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();
?>
