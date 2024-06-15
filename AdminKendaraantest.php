<?php
require 'koneksi.php';
session_start();

$sql = "SELECT * FROM kendaraan";
$result = $conn->query($sql);

if ($result === false) {
    die('Error executing query: ' . $conn->error);
}


if (isset($_SESSION['username'])) {
    $username = htmlspecialchars($_SESSION['username']);
} else {
    $username = "username";
}
?>

<style>
.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  padding: 12px 16px;
  z-index: 1;
}

.dropdown:hover .dropdown-content {
  display: block;
}
</style>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="src/output.css">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<title>K2 RentCar - Kelola Kendaraan</title>
    <link rel="icon" href="src/assets/img/k2rentcar.png">
</head>
<body class="bg-slate-100">

	<!-- SIDEBAR -->
    <div class="fixed mt-12 w-64 flex h-full" id="sidebar">
        <div class="bg-dongker overflow-y-full flex flex-grow">
            <nav class="space-y-3 px-2 mt-8">
                <a href="AdminPesanan.html"
                    class="text-[#142445] bg-white flex items-center px-10 py-2 rounded-3xl transition duration-300 transform hover:text-white hover:bg-[#EB002B] hover:bg-opacity-100 hover:scale-105 hover:shadow-lg">
                    <span>Kelola Pesanan</span>
                </a>
                </a>
                <a href="AdminKendaraan.html"
                    class="text-white bg-merah flex items-center pl-10 px-10 py-2 rounded-3xl transition duration-300 transform hover:text-white hover:bg-[#EB002B] hover:bg-opacity-100 hover:scale-105 hover:shadow-lg">
                    <span>Kelola Kendaraan</span>
                </a>
                <a href="admin-CustomerService.html"
                    class="text-[#142445] bg-white flex items-center px-10 py-2 rounded-3xl transition duration-300 transform hover:text-white hover:bg-[#EB002B] hover:bg-opacity-100 hover:scale-105 hover:shadow-lg">
                    <span>Customer Service</span>
                </a>
            </nav>
        </div>
    </div>
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">
        <!-- navbar section start -->
        <nav class="bg-white shadow-md">
            <div class="">
                <div class="fixed flex items-center justify-between w-full bg-white">
                    <!-- Logo and Title -->
                    <div class="flex-shrink-0 flex items-center">
                        <img class="h-8 w-8 flex item-left" src="src/assets/img/k2rentcar.png" alt="LogoK2rentcar">
                        <span class="ml-10 text-red-600 font-semibold text-lg">
                            <a href="dashboard.html">K2RentCar</a>
                        </span>
                        <span class="ml-1 text-gray-700">Admin Menu</span>
                    </div>
                    <!-- User Info -->
                    <div class="ml-auto flex items-center">
                        <span class="mr-2 text-gray-700">
                            <div class="dropdown">
                                <span><?php echo $username; ?></span>
                                <div class="dropdown-content">
                                    <a href="logout.php" style="color: red;">Logout</a>
                                </div></span>
                        <button class="flex-shrink-0 rounded-full p-2 focus:outline-none focus:bg-gray-300">
                            <img class="h-10 w-10 rounded-full" src="src/assets/img/IconAcc.svg" alt="User Icon">
                        </button>
                    </div>
                </div>
            </div>
        </nav>
        <!-- navbar section end -->

        <!-- MAIN -->
<!-- Table -->
<div class="container bg-gray-300 py-6 px-4 h-full rounded-2xl w-full">
    <!-- Search bar -->
    <span>
        <div class="flex justify-left space-x-4 mt-20 ml-60">
            <div class="border-2 border-merah rounded-full flex items-center px-3 py-1 bg-white">
            <form action="" method="get">
                <input type="text" name="search" placeholder="Cari" class="outline-none text-gray-500 text-lg w-32" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                <button type="submit" class="ml-2">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </form>
        </div>
            <div>
                <button type="submit" class="ml-2 bg-merah text-white rounded-full px-4 py-2 hover:text-dongker hover:bg-white hover:border-2 border-dongker transition duration-300">
                    Tambah+
                </button>
            </div>
        </div>
    </span>

  <div class="overflow-x">
  <table class="ml-60 mt-20 max-w-full bg-white border border-gray-300">
    <thead>
        <tr class="bg-gray-200">
            <th class="text-xs border p-2">No</th>
            <th class="text-xs border p-2">Gambar</th>
            <th class="text-xs border p-2">Nomor Polisi</th>
            <th class="text-xs border p-2">Model Kendaraan</th>
            <th class="text-xs border p-2">Jenis Mesin</th>
            <th class="text-xs border p-2">Kecepatan</th>
            <th class="text-xs border p-2">Jenis BBM</th>
            <th class="text-xs border p-2">Jumlah Kursi</th>
            <th class="text-xs border p-2">Harga/Hari</th>
            <th class="text-xs border p-2">Status</th>
            <th class="text-xs border p-2">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Ambil nilai pencarian jika ada
        $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
        // Tentukan apakah akan ada kondisi pencarian atau tidak
        if (!empty($searchTerm)) {
            // Query SQL dengan kondisi pencarian
            $sql = "SELECT * FROM kendaraan 
            WHERE nomor_polisi LIKE ? OR 
                  model_kendaraan LIKE ? OR 
                  jenis_mesin LIKE ? OR 
                  kecepatan LIKE ? OR 
                  jenis_bbm LIKE ? OR 
                  jumlah_kursi LIKE ? OR
                  harga_per_hari LIKE ?";
            // Preparing statement
            $stmt = $conn->prepare($sql);
            
            // Bind parameter
            $searchTerm = "%" . $searchTerm . "%"; // tambahkan wildcard (%) untuk pencarian
            $stmt->bind_param("sssssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
        } else {
            // Query SQL untuk mengambil semua data
            $sql = "SELECT * FROM kendaraan";
            // Prepare statement
            $stmt = $conn->prepare($sql);
        }
        
        // Execute statement
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $no = 1;
            while ($row = $result->fetch_assoc()) {
                $imgData = base64_encode($row['gambar']);
                $imgSrc = 'data:image/jpeg;base64,' . $imgData;
                
                ?>
                <tr>
                    <td class="text-xs border p-2"><?php echo $no++; ?>.</td>
                    <td class="text-xs border p-2 w-20 h-20">
                        <img src="<?php echo $imgSrc; ?>" alt="" width="200">
                    </td>
                    <td class="text-xs border p-2"><?php echo $row['nomor_polisi']; ?></td>
                    <td class="text-xs border p-2"><?php echo $row['model_kendaraan']; ?></td>
                    <td class="text-xs border p-2"><?php echo $row['jenis_mesin']; ?></td>
                    <td class="text-xs border p-2"><?php echo $row['kecepatan']; ?></td>
                    <td class="text-xs border p-2"><?php echo $row['jenis_bbm']; ?></td>
                    <td class="text-xs border p-2"><?php echo $row['jumlah_kursi']; ?> Seats</td>
                    <td class="text-xs border p-2">Rp.<?php echo number_format($row['harga_per_hari'], 0, ',', '.'); ?></td>
                    <td class="text-xs border p-2">
                        <form method="POST" action="update_status_kendaraan.php" onChange="this.submit()">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <select name="status" class="bg-dongker text-white py-1 px-2 rounded">
                                <option value="tersedia" <?php echo ($row['status'] == 'tersedia') ? 'selected' : ''; ?>>Tersedia</option>
                                <option value="tidak_tersedia" <?php echo ($row['status'] == 'tidak_tersedia') ? 'selected' : ''; ?>>Tidak Tersedia</option>
                            </select>
                        </form>
                    </td>
                    <td class="border p-4 flex">
                        <span>
                            <button class="py-1 px-2 rounded mr-0.5">
                                <img src="src/VectorEdit.png" alt="Edit">
                            </button>
                        </span>
                        <span>
                        <form method="POST" action="delete_kendaraan.php" onsubmit="return confirm('Anda yakin ingin menghapus kendaraan ini?');">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="py-1 px-2 rounded" name="delete">
                                <img src="src/VectorDelete.png" alt="Delete">
                            </button>
                        </form>
                        </span>
                    </td>
                </tr>
        <?php
            }
        } else {
            echo "<tr><td colspan='11' class='text-center'>No records found</td></tr>";
        }
        ?>
    </tbody>
</table>

	<script src="src/script.js"></script>
</body>
</html>
