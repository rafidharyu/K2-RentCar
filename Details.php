<?php
require 'koneksi.php';

if (!isset($_GET['id'])) {
    die('Error: ID not provided.');
}

$id = $_GET['id'];

// Query to get the details of the selected vehicle
$sql = "SELECT * FROM kendaraan WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    die('Error: Vehicle not found.');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="tailwind.config.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <title>K2 RentCar - Details</title>
    <link rel="icon" href="src/assets/img/k2rentcar.png">
</head>

<body class="bg-gray-100 h-screen p-4">
    <main class="h-full flex flex-col">
        <div>
            <button
                class="w-24 py-2 mt-8 ml-16 text-current font-semibold bg-gray-300 hover:bg-gray-400 hover:text-current rounded-lg transition duration-300"><a href="SeeAll.php">Go Back</a></button>
        </div>
        
        <div class="flex-1 flex items-center justify-center mt-2">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full max-w-7xl p-4">
                <div class="bg-gray-200 flex justify-center items-center p-4">
                <?php
                    // Memeriksa apakah ada gambar
                    if (!empty($row['gambar'])) {
                        $imgData = base64_encode($row['gambar']);
                        $imgSrc = 'data:image/jpeg;base64,' . $imgData;
                        echo '<img src="' . $imgSrc . '" class="w-full h-auto max-h-96">';
                    } else {
                        echo '<p class="text-red-500">Gambar tidak tersedia</p>';
                    }
                    ?>
                </div>
                <div class="bg-gray-100 p-8 flex flex-col justify-between">
                    <div>
                        <h1 class="font-semibold text-4xl"><?php echo htmlspecialchars($row['model_kendaraan']); ?></h1>
                        <div class="flex items-baseline mt-16">
                            <h1 class="font-bold text-5xl">Rp.<?php echo number_format($row['harga_per_hari'], 0, ',', '.'); ?></h1>
                            <span class="ml-32 text-lg text-gray-500">/Day</span>
                        </div>
                        <div class="flex items-center space-x-1 text-gray-600 mt-4">
                            <img src="src/assets/img/mobil/seat.png" alt="Seats" class="w-7 h-7">
                            <span class="text-lg"><?php echo htmlspecialchars($row['jumlah_kursi']); ?> Seats</span>
                        </div>
                    </div>
                    <div>
                        <button
                            class="w-96 py-2 mt-4 border border-1 border-current text-current text-2xl font-medium hover:bg-gray-400 hover:text-current transition duration-300 flex items-center justify-center">
                            <img src="src/assets/img/mobil/u_phone.png" alt="Phone Icon" class="w-7 h-7 mr-8 ">
                            <a href="sewa.php?id=<?php echo $row['id']; ?>">Rent Now</a>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center space-x-1 text-current mt-4 pl-32">
            <img src="src/assets/img/mobil/mesin.png" alt="Seats" class="w-16 h-16 bg-gray-300 rounded-full p-4">
            <span class="text-xl flex justify-center items-center w-max h-10 bg-gray-300 rounded-2xl">
                <span class="ml-10">Machine Type :</span>
                <span class="ml-80 mr-80"><?php echo htmlspecialchars($row['jenis_mesin']); ?></span>
            </span>
        </div>

        <div class="flex items-center space-x-1 text-current mt-4 pl-32">
            <img src="src/assets/img/mobil/kecepatan.png" alt="Seats" class="w-16 h-16 bg-gray-300 rounded-full p-4">
            <span class="text-xl flex justify-center items-center w-max h-10 bg-gray-300 rounded-2xl">
                <span class="ml-10">Maximum Speed:</span>
                <span class="ml-96 mr-48"><?php echo htmlspecialchars($row['kecepatan']); ?> km/h</span>
            </span>
        </div>

        <div class="flex items-center space-x-1 text-current mt-4 pl-32">
            <img src="src/assets/img/mobil/mesin.png" alt="Seats" class="w-16 h-16 bg-gray-300 rounded-full p-4">
            <span class="text-xl flex justify-center items-center w-max h-10 bg-gray-300 rounded-2xl">
                <span class="ml-10">Fuel Type :</span>
                <span class="ml-96 mr-52"><?php echo htmlspecialchars($row['jenis_bbm']); ?></span>
            </span>
        </div>
    </main>
    <div>
        <img src="src/assets/img/mobil/Frame 670.svg" alt="Frame" class=" w-full h-auto mt-20">
    </div>
</body>

</html>