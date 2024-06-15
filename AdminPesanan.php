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
    <title>K2 RentCar - KelolaPesanan</title>
    <link rel="icon" href="src/assets/img/k2rentcar.png">
</head>

<body class="bg-slate-100">

    <!-- SIDEBAR -->
    <div class="fixed mt-12 pt-3 w-64 flex h-full" id="sidebar">
        <div class="bg-dongker overflow-y-full flex flex-grow">
            <nav class="space-y-3 px-4 mt-8">
                <a href="AdminPesanan.html"
                    class="text-white bg-merah flex items-center px-10 py-2 rounded-3xl transition duration-300 transform hover:text-white hover:bg-[#EB002B] hover:bg-opacity-100 hover:scale-105 hover:shadow-lg">
                    <span>Kelola Pesanan</span>
                </a>
                </a>
                <a href="AdminKendaraan.html"
                    class="text-[#142445] bg-white flex items-center pl-10 px-10 py-2 rounded-3xl transition duration-300 transform hover:text-white hover:bg-[#EB002B] hover:bg-opacity-100 hover:scale-105 hover:shadow-lg">
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
        <nav class="bg-white shadow-md ">
            <div class="max-w-7xl">
                <div class="fixed flex items-center justify-between h-16 w-full bg-white">
                    <!-- Logo and Title -->
                    <div class="flex-shrink-0 flex items-center">
                        <img class="h-8 w-8 flex item-left" src="src/assets/img/k2rentcar.png" alt="LogoK2rentcar">
                        <span class="ml-10 text-red-600 font-semibold text-lg">K2RentCar</span>
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

        <!-- Main content -->
        <main class="col-span-10 p-4">

            <!-- Search bar -->
            

            <!-- Table -->
            <div class="mt-14 container bg-gray-300 py-6 px-4 h-full rounded-2xl w-full">
                <div class="flex w-48 ml-72 border-2 border-red-500 rounded-full px-3 py-1 bg-white mt-24">
                    <form action="" method="get">
                        <input type="text" name="search" placeholder="Cari" class="outline-none text-gray-500 text-lg w-32" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button type="submit" class="ml-2">
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>
                </div>
                <div class="overflow-x">
                    <table class="ml-60 max-w-full bg-white border border-gray-300 mt-8">
                        <thead>
                            <tr class="bg-gray-200">
                                <th class="text-xs border p-2">No</th>
                                <th class="text-xs border p-2">ID Transaksi</th>
                                <th class="text-xs border p-2">Nama Penyewa</th>
                                <th class="text-xs border p-2">Nomor Polisi</th>
                                <th class="text-xs border p-2">Model Kendaraan</th>
                                <th class="text-xs border p-2">Tanggal Pinjam</th>
                                <th class="text-xs border p-2">Tanggal Kembali</th>
                                <th class="text-xs border p-2">Harga Total</th>
                                <th class="text-xs border p-2">Denda</th>
                                <th class="text-xs border p-2">Status Transaksi</th>
                                <th class="text-xs border p-2">Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        // Ambil nilai pencarian jika ada
                        $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
                        // Tentukan apakah akan ada kondisi pencarian atau tidak
                        if (!empty($searchTerm)) {
                            // Query SQL dengan kondisi pencarian
                            $sql = "SELECT * FROM transaksi 
                                    WHERE fullname LIKE ? OR 
                                        username LIKE ? OR
                                        nomor_polisi LIKE ? OR 
                                        model_kendaraan LIKE ? OR 
                                        tanggal_pinjam LIKE ? OR 
                                        tanggal_kembali LIKE ? OR 
                                        status_transaksi LIKE ?";
                            // Preparing statement
                            $stmt = $conn->prepare($sql);
                            // Bind parameter
                            $searchTerm = "%" . $searchTerm . "%"; // tambahkan wildcard (%) untuk pencarian
                            $stmt->bind_param("sssssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
                        } else {
                            // Query SQL untuk mengambil semua data
                            $sql = "SELECT * FROM transaksi";
                            // Prepare statement
                            $stmt = $conn->prepare($sql);
                        }

                        // Execute statement
                        $stmt->execute();
                        $result = $stmt->get_result();
                        if ($result === false) {
                            die('Error executing query: ' . $conn->error);
                        }
                        $no = 1;
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                ?>
                                <tr>
                                    <td class="text-xs border p-2"><?php echo $no++; ?>.</td>
                                    <td class="text-xs border p-2"><?php echo $row['id']; ?></td>
                                    <td class="text-xs border p-2"><?php echo $row['fullname']; ?></td>
                                    <td class="text-xs border p-2"><?php echo $row['nomor_polisi']; ?></td>
                                    <td class="text-xs border p-2"><?php echo $row['model_kendaraan']; ?></td>
                                    <td class="text-xs border p-2"><?php echo $row['tanggal_pinjam']; ?></td>
                                    <td class="text-xs border p-2"><?php echo $row['tanggal_kembali']; ?></td>
                                    <td class="text-xs border p-2">Rp.<?php echo number_format($row['harga_total'], 2, ',', '.'); ?></td>
                                    <td class="text-xs border p-2">Rp.<?php echo number_format($row['denda'], 2, ',', '.'); ?></td>
                                    <td class="text-xs border p-2">
                                        <select class="bg-dongker text-white py-1 px-2 rounded">
                                            <option value="disewa" <?php echo ($row['status_transaksi'] == 'disewa') ? 'selected' : ''; ?>>Disewa</option>
                                            <option value="dibatalkan" <?php echo ($row['status_transaksi'] == 'dibatalkan') ? 'selected' : ''; ?>>Dibatalkan</option>
                                            <option value="dikembalikan" <?php echo ($row['status_transaksi'] == 'dikembalikan') ? 'selected' : ''; ?>>Dikembalikan</option>
                                        </select>
                                    </td>
                                    <td class="border p-2 flex">
                                        <span>
                                            <button class="py-1 px-2 rounded mr-0.5">
                                                <img src="src/VectorEdit.png" alt="Edit">
                                            </button>
                                        </span>
                                        <span>
                                            <button class="py-1 px-2 rounded">
                                                <img src="src/VectorDelete.png" alt="Delete">
                                            </button>
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
                </div>
            </div>
        </main>
        </div>

        <script src="src/script.js"></script>
</body>

</html>
