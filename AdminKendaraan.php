<?php
require 'koneksi.php';
session_start();

$sql = "SELECT * FROM kendaraan";
$result = $conn->query($sql);

if ($result === false) {
    die('Error executing query: ' . $conn->error);
}

?>

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
                        <span class="mr-2 text-gray-700">Admin A</span>
                        <button class="flex-shrink-0 rounded-full p-2 focus:outline-none focus:bg-gray-300">
                            <img class="h-10 w-10 rounded-full" src="src/assets/img/IconAcc.svg" alt="User Icon">
                        </button>
                    </div>
                </div>
            </div>
        </nav>
        <!-- navbar section end -->


<!-- MAIN -->
 <main col-span-10 p-4>
<div class="container bg-gray-300 py-6 px-4 h-full rounded-2xl w-full">
    <!-- Search bar -->
    <span>
        <div class="flex justify-left space-x-4 mt-20 ml-60">
                <div class="border-2 border-merah rounded-full flex items-center px-3 py-1 bg-white">
                    <input type="text" placeholder="Cari" class="outline-none text-gray-500 text-lg w-32">
                    <button type="submit" class="ml-2">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                </div>
            <div>

        <!-- Button Tambah -->
            <button id="myBtn" class="ml-2 bg-merah text-white rounded-full px-4 py-2 hover:text-dongker hover:bg-white hover:border-2 border-dongker transition duration-300">
                + Tambah
            </button>

            <div id="myModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
                <div class="relative top-20 mx-auto p-5 border max-w-3xl w-full shadow-lg rounded-2xl bg-dongker">
                    <div class="mt-3 text-left">
                        <h3 class="text-lg leading-6 font-medium text-white">Tambah Kendaraan</h3>
                        <span class="absolute top-0 right-2 p-2 text-gray-200 cursor-pointer text-3xl font-bold" id="closeModal">&times;</span>
                        <div class="mt-2 px-7 py-3">
                            <form action="proses_tambah_kendaraan.php" method="post" enctype="multipart/form-data">
                                <div class="mb-4 flex space-x-10">
                                    <div class="w-1/2">
                                        <label for="gambar" class="block text-white text-sm font-semibold mb-2">Gambar:</label>
                                        <input type="file" name="gambar" id="gambar" class="shadow appearance-none border rounded w-full py-2 px-3 bg-white text-md font-medium leading-tight focus:outline-none focus:shadow-outline">
                                    </div>
                                    <div class="w-1/2">
                                        <label for="nomor_polisi" class="block text-white text-sm font-semibold mb-2">Nomor Polisi:</label>
                                        <input type="text" name="nomor_polisi" id="nomor_polisi" required class="shadow appearance-none border rounded w-full py-2 px-3 text-md font-medium leading-tight focus:outline-none focus:shadow-outline">
                                    </div>
                                </div>
                                <div class="mb-4 flex space-x-10">
                                    <div class="w-1/2">
                                        <label for="model_kendaraan" class="block text-white text-sm font-semibold mb-2">Model Kendaraan:</label>
                                        <input type="text" name="model_kendaraan" id="model_kendaraan" required class="shadow appearance-none border rounded w-full py-2 px-3 text-md font-medium leading-tight focus:outline-none focus:shadow-outline">
                                    </div>
                                    <div class="w-1/2">
                                        <label for="jenis_mesin" class="block text-white text-sm font-semibold mb-2">Jenis Mesin:</label>
                                        <input type="text" name="jenis_mesin" id="jenis_mesin" class="shadow appearance-none border rounded w-full py-2 px-3 text-md font-medium leading-tight focus:outline-none focus:shadow-outline">
                                    </div>
                                </div>
                                <div class="mb-4 flex space-x-10">
                                    <div class="w-1/2">
                                        <label for="kecepatan" class="block text-white text-sm font-semibold mb-2">Kecepatan:</label>
                                        <input type="text" name="kecepatan" id="kecepatan" class="shadow appearance-none border rounded w-full py-2 px-3 text-md font-medium leading-tight focus:outline-none focus:shadow-outline">
                                    </div>
                                    <div class="w-1/2">
                                        <label for="jenis_bbm" class="block text-white text-sm font-semibold mb-2">Jenis BBM:</label>
                                        <input type="text" name="jenis_bbm" id="jenis_bbm" class="shadow appearance-none border rounded w-full py-2 px-3 text-md font-medium leading-tight focus:outline-none focus:shadow-outline">
                                    </div>
                                </div>
                                <div class="mb-4 flex space-x-10">
                                    <div class="w-1/2">
                                        <label for="jumlah_kursi" class="block text-white text-sm font-semibold mb-2">Jumlah Kursi:</label>
                                        <input type="number" name="jumlah_kursi" id="jumlah_kursi" class="shadow appearance-none border rounded w-full py-2 px-3 text-md font-medium leading-tight focus:outline-none focus:shadow-outline">
                                    </div>
                                    <div class="w-1/2">
                                        <label for="harga_per_hari" class="block text-white text-sm font-semibold mb-2">Harga per Hari:</label>
                                        <input type="text" name="harga_per_hari" id="harga_per_hari" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 text-md font-medium leading-tight focus:outline-none focus:shadow-outline">
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label for="status" class="block text-white text-sm font-semibold mb-2">Status:</label>
                                    <select name="status" id="status" required class="shadow appearance-none border rounded w-full py-2 px-3 text-md font-medium leading-tight focus:outline-none focus:shadow-outline">
                                        <option value="tersedia">Tersedia</option>
                                        <option value="tidak_tersedia">Tidak Tersedia</option>
                                    </select>
                                </div>
                                <div class="flex items-center justify-between mt-8">
                                    <button type="submit" class="bg-merah hover:bg-blue-800 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline">+ Tambah Kendaraan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Tailwind JS for Modal functionality -->
                <script>
                    var modal = document.getElementById("myModal");
                    var btn = document.getElementById("myBtn");
                    var span = document.getElementById("closeModal");

                    btn.onclick = function() {
                        modal.classList.remove("hidden");
                    }

                    span.onclick = function() {
                        modal.classList.add("hidden");
                    }

                    window.onclick = function(event) {
                        if (event.target == modal) {
                            modal.classList.add("hidden");
                        }
                    }
                </script>
            </div>
        </div>
    </span>

<!-- Table -->
 
  <div class="overflow-x rounded-2xl ms-48">
  <table class="ml-56 mt-10 mr-10 max-w-full bg-white border border-gray-300 rounded-2xl">
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
                  jumlah_kursi LIKE ?";
            // Preparing statement
            $stmt = $conn->prepare($sql);
            
            // Bind parameter
            $searchTerm = "%" . $searchTerm . "%"; // tambahkan wildcard (%) untuk pencarian
            $stmt->bind_param("ssssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
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
                    <td class="text-xs border p-2">Rp. <?php echo $row['harga_per_hari']; ?></td>
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
