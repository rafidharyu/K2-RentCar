<?php
require 'koneksi.php';

$sql = "SELECT * FROM kendaraan";
$result = $conn->query($sql);

if ($result === false) {
    die('Error executing query: ' . $conn->error);
}

$shownModels = array();
?>

<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
  <script src="tailwind.config.js"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="src/input.css">
  <title>K2 RentCar - SeeAll</title>
  <link rel="icon" href="src/assets/img/k2rentcar.png">
</head>

<body class="bg-gray-100">

  <main class="container mx-auto mt-10">
    <h3 class="text-center">POPULAR CAR</h3>
    <h1 class="text-3xl font-bold text-center mb-2 text-red-500">Choose Your Suitable Car</h1>
    <p class="text-center text-gray-500">We present popular cars that are rented by customers to maximize your comfort on long trips.</p>

    <div class="grid grid-cols-3 gap-y-0.5 mt-10">
    <?php
          
            $counter = 0;
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                  $model_kendaraan = $row['model_kendaraan'];
                  if (in_array($model_kendaraan, $shownModels)) {
                      continue; // Skip if the model has already been shown
                  }
                  $shownModels[] = $model_kendaraan;

                  // Hitung jumlah unit yang tersedia untuk model ini
                  $countSql = "SELECT COUNT(*) as count FROM kendaraan WHERE model_kendaraan = ? AND status = 'tersedia'";
                  $stmtCount = $conn->prepare($countSql);
                  $stmtCount->bind_param("s", $model_kendaraan);
                  $stmtCount->execute();
                  $countResult = $stmtCount->get_result();
                  $countRow = $countResult->fetch_assoc();
                  $unitCount = $countRow['count'];

                  if ($counter % 3 == 0 && $counter != 0) {
                      //echo '</div><div class="flex flex-wrap justify-center">'; // Close row and start a new one every three items
                  }
                  $counter++;

                  // Fetch one example of this model for display
                  $exampleSql = "SELECT * FROM kendaraan WHERE model_kendaraan = ? LIMIT 1";
                  $stmtExample = $conn->prepare($exampleSql);
                  $stmtExample->bind_param("s", $model_kendaraan);
                  $stmtExample->execute();
                  $exampleResult = $stmtExample->get_result();
                  $exampleRow = $exampleResult->fetch_assoc();

                    $counter++;
                    echo '<div class="bg-white shadow-lg rounded-lg max-w-sm mx-10">';
                    echo '  <div class="bg-gray-200 rounded-t-lg p-4 flex justify-center items-center gap-y-4">';
                    echo '    <img src="data:image/jpeg;base64,' . base64_encode($row['gambar']) . '" alt="' . htmlspecialchars($row['model_kendaraan']) . '" class="w-full h-48">';
                    echo '  </div>';
                    echo '  <div class="p-6">';
                    echo '    <div class="flex items-center justify-between mb-2">';
                    echo '      <h2 class="text-xl font-semibold text-gray-700">' . htmlspecialchars($row['model_kendaraan']) . '</h2>';
                    echo '      <div class="flex items-center space-x-1 text-gray-600">';
                    echo '        <span class="inline-block w-5 h-5 justify-center items-center">';
                    echo '          <img src="src/assets/img/mobil/unit.png" alt="units">';
                    echo '        </span>';
                    echo '        <span>' . htmlspecialchars($unitCount) . '</span>';
                    echo '      </div>';
                    echo '    </div>';
                    echo '    <div class="flex justify-between items-center mb-6">';
                    echo '      <div class="text-dongker text-2xl font-bold">Rp. ' . number_format($row["harga_per_hari"], 0, ",", ".") .'<span';
                    echo '        class="font-normal text-[#AEAEB0] text-sm">/Day</span></div>';
                    echo '      <div class="flex items-center space-x-0.5 text-gray-600">';
                    echo '        <span class="inline-block w-5 h-5 justify-center items-center">';
                    echo '          <img src="src/assets/img/mobil/seat.png" alt="seats">';
                    echo '        </span>';
                    echo '        <span>' . htmlspecialchars($row['jumlah_kursi']) . ' Seats</span>';
                    echo '      </div>';
                    echo '    </div>';
                    echo '    <a href="details.php?id=' . urlencode($exampleRow['id']) . '">';
                    echo '      <button class="w-full py-4 mt-2 border-2 border-current text-current hover:bg-gray-300 hover:text-dongker rounded-lg transition duration-300">See Details</button>';
                    echo '    </a>';
                    echo '  </div>';
                    echo '</div>';
                  }
                } else {
                    echo '<p>No vehicles found.</p>';
                }
                ?>
            </div>
        </div>
    </body>
    </html>
    
    <?php
    $conn->close();
    ?>
  </main>

  <div class="flex justify-center items-center mt-10 mb-1">
    <button class="bg-gray-300 hover:bg-gray-400 text-current text-lg font-bold py-4 px-8 rounded-lg transition duration-300"><a href="dashboard.php">Go Back</a></button>
  </div>

  <div>
    <img src="src/assets/img/mobil/Frame 670.svg" alt="Frame" class=" w-full h-auto mt-0.5">
  </div>
</body>

</html>
