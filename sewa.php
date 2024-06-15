<?php
require 'koneksi.php';

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


// Proses penyimpanan transaksi jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $tanggal_kembali = $_POST['tanggal_kembali'];
    $harga_per_hari = $row['harga_per_hari']; // Ambil harga per hari dari data kendaraan

    // Hitung harga total berdasarkan tanggal pinjam, tanggal kembali, dan harga per hari
    $datetime1 = new DateTime($tanggal_pinjam);
    $datetime2 = new DateTime($tanggal_kembali);
    $selisih = $datetime1->diff($datetime2);
    $jumlah_hari = $selisih->days;
    $harga_total = $jumlah_hari * $harga_per_hari;

    // Insert data transaksi ke tabel transaksi
    $sql_insert = "INSERT INTO transaksi (fullname, username, nomor_polisi, model_kendaraan, tanggal_pinjam, tanggal_kembali, harga_total, denda, status_transaksi)
                   VALUES (?, ?, ?, ?, ?, ?, ?, 0, 'pending')";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ssssssd", $fullname, $username, $row['nomor_polisi'], $row['model_kendaraan'], $tanggal_pinjam, $tanggal_kembali, $harga_total);

    if ($stmt_insert->execute()) {
        echo '<div class="bg-green-200 px-4 py-2 text-green-900 mb-4">Data transaksi berhasil disimpan.</div>';
    } else {
        echo '<div class="bg-red-200 px-4 py-2 text-red-900 mb-4">Error: ' . $stmt_insert->error . '</div>';
    }

    $stmt_insert->close();
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
        <title>K2 RentCar - Sewa</title>
        <link rel="icon" href="src/assets/img/k2rentcar.png">
    </head>

    <body class="bg-gray-100 h-screen p-4">
    <main class="h-full flex flex-col">
        <div>
            <button class="w-24 py-2 mt-8 ml-16 text-current font-semibold bg-gray-300 hover:bg-gray-400 hover:text-current rounded-lg transition duration-300"><a href="Details.html">Go Back</a></button>
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
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 max-w-7xl ml-80 mt-10">
            <div class="px-4 py-6 bg-gray-400 rounded-3xl w-full mb-20">
                <h2 class="text-3xl font-bold mb-2 text-[#142445]">Lama Sewa</h2>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id; ?>">
                    <div class="flex space-x-2 mt-6 mb-4">
                        <input type="date" name="tanggal_pinjam" class="px-4 py-2 border border-gray-300 rounded" value="<?php echo date('Y-m-d'); ?>">
                        <span class="self-center">→</span>
                        <input type="date" name="tanggal_kembali" class="px-4 py-2 border border-gray-300 rounded" value="<?php echo date('Y-m-d', strtotime('+1 day')); ?>">
                    </div>
                    <div class="text-right">
                        <!-- Trigger/Open The Modal -->
    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        <a href="bisasewa.html">Sewa</a>
    </button>

    <!-- The Modal -->
    <div id="modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div
            class="relative top-10 bottom-10 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-2xl bg-white mb-10">
            <div class="text-left p-5">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Persyaratan dan Ketentuan Penyewaan</h3>
                <span class="absolute top-0 right-0 p-4 text-gray-400 cursor-pointer text-3xl font-bold"
                    id="closeModal">&times;</span>
                <div class="mt-4">
                    <p class="text-sm text-gray-700">
                        <strong>Persyaratan dan Ketentuan Penyewaan:</strong><br>
                        • Pelanggan harus memiliki usia minimal 20 tahun dan memiliki SIM yang valid untuk melakukan
                        penyewaan mobil.<br>
                        • Penggunaan mobil hanya diperbolehkan di dalam wilayah yang ditentukan. Penggunaan di luar
                        wilayah tersebut akan dikenakan biaya tambahan.<br><br>

                        <strong>Ketentuan Umum:</strong><br>
                        • Penyedia layanan berhak untuk membatalkan atau menunda penyewaan mobil jika terjadi keadaan
                        darurat atau kejadian tak terduga lainnya.<br>
                        • Ketentuan dan harga dapat berubah tanpa pemberitahuan sebelumnya, namun perubahan tersebut
                        tidak berlaku untuk penyewaan yang sudah dikonfirmasi sebelumnya.<br><br>

                        <strong>Kebijakan Pembatalan:</strong><br>
                        • Pembatalan yang dilakukan dalam waktu 24 jam sebelum waktu penjemputan akan dikenakan biaya
                        pembatalan sebesar 25% dari total biaya penyewaan.<br>
                        • Pembatalan yang dilakukan kurang dari 24 jam sebelum waktu penjemputan akan dikenakan biaya
                        pembatalan sebesar 50% dari total biaya penyewaan.<br><br>

                        <strong>Biaya Tambahan:</strong><br>
                        • Biaya penggunaan bahan bakar tidak termasuk dalam harga penyewaan. Pelanggan diharapkan
                        mengembalikan mobil dengan tangki bahan bakar penuh. Jika tidak, akan dikenakan biaya tambahan
                        untuk pengisian bahan bakar.<br>
                        • Biaya keterlambatan pengembalian mobil akan dikenakan jika mobil tidak dikembalikan sesuai
                        dengan waktu yang disepakati. Biaya ini akan dihitung per jam keterlambatan.<br>
                    </p>
                </div>
                <div class="flex items-center justify-between mt-4">
                    <label class="inline-flex items-center">
                        <input type="checkbox" class="form-checkbox text-indigo-600" id="agreeCheckbox">
                        <span class="ml-2 text-gray-700">Saya Setuju</span>
                    </label>
                    <button id="sewaBtn" class="bg-gray-400 text-white font-bold py-2 px-4 rounded cursor-not-allowed"
                        disabled>
                        Sewa
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Modal -->
<div id="notificationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
  <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white mb-10">
    <div class="text-center p-5 flex flex-col items-center">
      <img src="src/assets/img/popup/pesanan.png" alt="pesanan berhasil" class="w-32 h-32 mb-4">
      <h3 class="text-lg leading-6 font-medium text-dongker">Pesanan Anda Telah Diatur</h3>
      <p class="mt-4 text-dongker">Silahkan datang ke tempat pengambilan mobil.</p>
      <button id="closeNotificationBtn" class="bg-[#142445] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg mt-8">
        Oke
      </button>
    </div>
  </div>
</div>

    <!-- Thank You Modal -->
    <div id="thankYouModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-2xl shadow-lg rounded-2xl bg-white mb-10">
            <div class="text-center p-5">
                <h3 class="text-2xl font-bold text-merah">Terimakasih</h3>
                <p class="mt-4 text-dongker">Telah Menyewa Mobil di K2RentCar</p>
                <button id="closeThankYouBtn"
                    class="bg-[#142445] hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg mt-8">
                    <a href="pemesanan.html">Tutup</a>
                </button>
            </div>
        </div>
    </div>

    <!-- Tailwind JS for Modal functionality -->
    <script>
        var modal = document.getElementById("modal");
        var openModalBtn = document.getElementById("openModalBtn");
        var closeModal = document.getElementById("closeModal");
        var sewaBtn = document.getElementById("sewaBtn");
        var agreeCheckbox = document.getElementById("agreeCheckbox");

        var notificationModal = document.getElementById("notificationModal");
        var closeNotificationBtn = document.getElementById("closeNotificationBtn");

        var thankYouModal = document.getElementById("thankYouModal");
        var closeThankYouBtn = document.getElementById("closeThankYouBtn");

        openModalBtn.onclick = function () {
            modal.classList.remove("hidden");
        }

        closeModal.onclick = function () {
            modal.classList.add("hidden");
        }

        window.onclick = function (event) {
            if (event.target == modal) {
                modal.classList.add("hidden");
            }
        }

        agreeCheckbox.onclick = function () {
            if (this.checked) {
                sewaBtn.disabled = false;
                sewaBtn.classList.remove("bg-gray-400", "cursor-not-allowed");
                sewaBtn.classList.add("bg-blue-500", "hover:bg-dongker");
            } else {
                sewaBtn.disabled = true;
                sewaBtn.classList.remove("bg-blue-500", "hover:bg-dongker");
                sewaBtn.classList.add("bg-gray-400", "cursor-not-allowed");
            }
        }

        sewaBtn.onclick = function () {
            modal.classList.add("hidden");
            notificationModal.classList.remove("hidden");
        }

        closeNotificationBtn.onclick = function () {
            notificationModal.classList.add("hidden");
            thankYouModal.classList.remove("hidden");
        }

        closeThankYouBtn.onclick = function () {
            thankYouModal.classList.add("hidden");
        }
    </script>
                    </div>
                </form>
            </div>
        </div>

    </main>

    <div>
        <img src="src/assets/img/mobil/Frame 670.svg" alt="Frame" class=" w-full h-auto mt-64">
    </div>

</body>
</html>