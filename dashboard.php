<?php
session_start();
require 'koneksi.php';

$sql = "SELECT * FROM kendaraan";
$result = $conn->query($sql);

if ($result === false) {
    die('Error executing query: ' . $conn->error);
}

$shownModels = array();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="src/output.css">
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
	<title>K2 RentCar - Dashboard</title>
    <link rel="icon" href="src/assets/img/k2rentcar.png">
</head>
<body class="bg-slate-100">

	<!-- SIDEBAR -->
<div class="fixed mt-32 pt-3 w-64 flex h-full" id="sidebar">
    <div class="bg-merah overflow-y-auto flex flex-col flex-grow pt-3">
        <nav class="space-y-3 px-4 mt-5">
            <a href="#" class="text-gray-200 flex items-center space-x-3 p-2 rounded-lg bg-dongker">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M13 9V3h8v6zM3 13V3h8v10zm10 8V11h8v10zM3 21v-6h8v6z"/></svg>
                </div>
                <span>Dashboard</span>
            </a>
            </a>
            <a href="pemesanan.html" class="text-gray-200 flex items-center space-x-3 p-2 rounded-lg transition duration-300 transform hover:bg-[#142445] hover:bg-opacity-20 hover:scale-105 hover:shadow-lg">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10s10-4.486 10-10S17.514 2 12 2zm1 14.915V18h-2v-1.08c-2.339-.367-3-2.002-3-2.92h2c.011.143.159 1 2 1c1.38 0 2-.585 2-1c0-.324 0-1-2-1c-3.48 0-4-1.88-4-3c0-1.288 1.029-2.584 3-2.915V6.012h2v1.109c1.734.41 2.4 1.853 2.4 2.879h-1l-1 .018C13.386 9.638 13.185 9 12 9c-1.299 0-2 .516-2 1c0 .374 0 1 2 1c3.48 0 4 1.88 4 3c0 1.288-1.029 2.584-3 2.915z" fill="currentColor"/></svg>
                </div>
                <span>Pemesanan</span>
            </a>
        </nav>
    </div>
</div>
<div class="fixed bottom-0 w-64 h-40 flex" id="sidebar">
    <div class="bg-white overflow-y-auto flex flex-col flex-grow">
        <nav class="py-4 pr-24 pl-4">
            <a href="setting.php" class="flex items-center space-x-3 py-1 px-4 rounded-lg border-2 border-abu transition duration-300 ease-in-out hover:bg-abu hover:text-black hover:shadow-lg">
                <div>
                    <img src="src/assets/img/setting.svg" alt="">
                </div>
                <span>Setting</span>
            </a>   
            <div class="flex items-center justify-start ms-2 mt-10">
                <!-- Youtube -->
                <a href="#" target="_blank" class="h-9 mr-3 flex justify-center items-center text-gray-500 hover:border-primary hover:bg-primary hover:text-merah">
                    <svg width="17" class="fill-current" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>YouTube</title><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                </a>
                <!-- Instagram
                <a href="#" target="_blank" class="h-9 mr-3 flex justify-center items-center text-gray-500 hover:border-primary hover:bg-primary hover:text-merah">
                    <svg width="15" class="fill-current" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>Instagram</title><path d="M7.0301.084c-1.2768.0602-2.1487.264-2.911.5634-.7888.3075-1.4575.72-2.1228 1.3877-.6652.6677-1.075 1.3368-1.3802 2.127-.2954.7638-.4956 1.6365-.552 2.914-.0564 1.2775-.0689 1.6882-.0626 4.947.0062 3.2586.0206 3.6671.0825 4.9473.061 1.2765.264 2.1482.5635 2.9107.308.7889.72 1.4573 1.388 2.1228.6679.6655 1.3365 1.0743 2.1285 1.38.7632.295 1.6361.4961 2.9134.552 1.2773.056 1.6884.069 4.9462.0627 3.2578-.0062 3.668-.0207 4.9478-.0814 1.28-.0607 2.147-.2652 2.9098-.5633.7889-.3086 1.4578-.72 2.1228-1.3881.665-.6682 1.0745-1.3378 1.3795-2.1284.2957-.7632.4966-1.636.552-2.9124.056-1.2809.0692-1.6898.063-4.948-.0063-3.2583-.021-3.6668-.0817-4.9465-.0607-1.2797-.264-2.1487-.5633-2.9117-.3084-.7889-.72-1.4568-1.3876-2.1228C21.2982 1.33 20.628.9208 19.8378.6165 19.074.321 18.2017.1197 16.9244.0645 15.6471.0093 15.236-.005 11.977.0014 8.718.0076 8.31.0215 7.0301.0839m.1402 21.6932c-1.17-.0509-1.8053-.2453-2.2287-.408-.5606-.216-.96-.4771-1.3819-.895-.422-.4178-.6811-.8186-.9-1.378-.1644-.4234-.3624-1.058-.4171-2.228-.0595-1.2645-.072-1.6442-.079-4.848-.007-3.2037.0053-3.583.0607-4.848.05-1.169.2456-1.805.408-2.2282.216-.5613.4762-.96.895-1.3816.4188-.4217.8184-.6814 1.3783-.9003.423-.1651 1.0575-.3614 2.227-.4171 1.2655-.06 1.6447-.072 4.848-.079 3.2033-.007 3.5835.005 4.8495.0608 1.169.0508 1.8053.2445 2.228.408.5608.216.96.4754 1.3816.895.4217.4194.6816.8176.9005 1.3787.1653.4217.3617 1.056.4169 2.2263.0602 1.2655.0739 1.645.0796 4.848.0058 3.203-.0055 3.5834-.061 4.848-.051 1.17-.245 1.8055-.408 2.2294-.216.5604-.4763.96-.8954 1.3814-.419.4215-.8181.6811-1.3783.9-.4224.1649-1.0577.3617-2.2262.4174-1.2656.0595-1.6448.072-4.8493.079-3.2045.007-3.5825-.006-4.848-.0608M16.953 5.5864A1.44 1.44 0 1 0 18.39 4.144a1.44 1.44 0 0 0-1.437 1.4424M5.8385 12.012c.0067 3.4032 2.7706 6.1557 6.173 6.1493 3.4026-.0065 6.157-2.7701 6.1506-6.1733-.0065-3.4032-2.771-6.1565-6.174-6.1498-3.403.0067-6.156 2.771-6.1496 6.1738M8 12.0077a4 4 0 1 1 4.008 3.9921A3.9996 3.9996 0 0 1 8 12.0077"/></svg>
                </a> -->
                <!-- Tiktok -->
                <a href="#" target="_blank" class="h-9 mr-3 flex justify-center items-center text-gray-500 hover:border-primary hover:bg-primary hover:text-merah">
                    <svg width="17" class="fill-current" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>TikTok</title><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                </a>
                <!-- Github -->
                <a href="#" target="_blank" class="h-9 mr-3 flex justify-center items-center text-gray-500 hover:border-primary hover:bg-primary hover:text-merah">
                    <svg width="17" class="fill-current" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>GitHub</title><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg>
                </a>
                <!-- Linkedin -->
                <a href="#" target="_blank" class="h-9 mr-3 flex justify-center items-center text-gray-500 hover:border-primary hover:bg-primary hover:text-merah">
                    <svg width="17" class="fill-current" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><title>LinkedIn</title><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                </a>
            </div>
            <div class="absolute items-center justify-start px-2 py-4 bottom-0">
                <p class="font-normal text-black text-start text-xs">© 2023 Kelompok2.</p>
            </div>
        </nav>
    </div>
</div>
	<!-- SIDEBAR -->

	<!-- CONTENT -->
	<section id="content">
		<!-- navbar section start -->
        <header class="fixed bg-white w-full flex z-10 items-center shadow-lg border-b border-black">
            <div class="ps-10">
                <a href="landing-page.html"><img src="src/assets/img/k2rentcar.png" alt="k2rentcar" class="w-20"></a>
            </div>
            <div class="flex-row w-full ps-14">
                <div class="relative bg-dongker rounded-bl-full flex">
                    <div class="absolute ps-16 top-[13px] block max-w-full w-1/4">
                      <img src="src/assets/img/navbar/mail.svg" alt="">
                    </div>
                    <div class="py-4 top-full block static max-w-full w-1/4">
                        <p class="font-normal text-slate-100 ps-28">k2rentcar@support.com</p>
                    </div>
                    <div class="absolute ps-72 ms-16 top-[13px] block max-w-full w-1/4">
                      <img src="src/assets/img/navbar/jam.svg" alt="">
                    </div>
                    <div class="py-4 w-full top-full block static max-w-full">
                        <p class="font-normal text-slate-100 ps-28">Mon to Fri: 9:00am to 6:00pm</p>
                    </div>
                    <div class="w-1/4 me-10 flex">
                        <div class="absolute ms-4 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"><path fill="white" fill-opacity="0.25" d="M3 11c0-3.771 0-5.657 1.172-6.828C5.343 3 7.229 3 11 3h2c3.771 0 5.657 0 6.828 1.172C21 5.343 21 7.229 21 11v2c0 3.771 0 5.657-1.172 6.828C18.657 21 16.771 21 13 21h-2c-3.771 0-5.657 0-6.828-1.172C3 18.657 3 16.771 3 13z"/><circle cx="12" cy="10" r="4" fill="white"/><path fill="white" fill-rule="evenodd" d="M18.946 20.253a.232.232 0 0 1-.14.25C17.605 21 15.836 21 13 21h-2c-2.835 0-4.605 0-5.806-.498a.232.232 0 0 1-.14-.249C5.483 17.292 8.429 15 12 15c3.571 0 6.517 2.292 6.946 5.253" clip-rule="evenodd"/></svg>
                          </div>
                        <a href="login.php" target="_blank" class="text-sm font-medium text-white bg-merah px-10 py-5"><span class="text-merah">____</span> 
                        <?php
                          if (isset($_SESSION['username'])) {
                            echo htmlspecialchars($_SESSION['username']);
                          } else {
                            echo "username";
                          } 
                        ?></a>
                    </div>
            </div>
            <div class="items-center ps-14 justify-between relative bg-white">
                <div class="flex items-center">
                    <h3 class="font-normal text-2xl text-dongker uppercase w-1/3 text-center">k2 rent car</h3>
                    <nav id="nav-menu" class="py-5 rounded-none max-w-full w-full right-4 top-full block static bg-transparent">
                        <ul class="block lg:flex">
                            <li class="group">
                                <a href="#" class="text-base text-transparent py-2 mx-5 flex">.</a>
                            </li>
                            
                        </ul>
                    </nav>
                    <div class="w-1/3 top-full block static max-w-full">
                        <div class="absolute ps-4 top-[26px]">
                            <img src="src/assets/img/navbar/telpon.svg" alt="">
                          </div>
                        <a href="tel:+628123456789" target="_blank" class="text-sm text-center font-medium text-dongker border-2 border-dongker px-6 py-5 w-1/4 rounded-full"><span class="text-white">____</span>+62 815-5892-1481</a>
                    </div>
                    <div class="w-1/3 me-10 top-full block static max-w-full">
                        <div class="absolute ps-6 top-[26px]">
                            <img src="src/assets/img/navbar/chat.svg" alt="">
                          </div>
                        <a href="https://wa.me/6281558921481" target="_blank" class="text-sm text-center font-medium text-white bg-green-600 px-7 py-5 w-1/4 rounded-full"><span class="text-green-600">_____</span>Chat WhatsApp</a>
                    </div>
                </div>
            </div>
        </div>
        </header>
    <!-- navbar section end -->

        <!-- MAIN -->
		<main class="absolute start-80 top-44 pr-20">
			<div class="head-title flex justify-between items-center">
				<div class="flex flex-row">
                    <div class="pt-1 me-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="#142445" d="M13 9V3h8v6zM3 13V3h8v10zm10 8V11h8v10zM3 21v-6h8v6z"/></svg>
                    </div>
					<h1 class="text-2xl font-bold mb-5 text-dongker">Dashboard</h1>
				</div>
			</div>

            <div class="bg-dongker rounded-r-full rounded-l-3xl w-full py-[110px] px-[550px] border-merah border-8">
                <div class="absolute top-20 left-10">
                    <h3 class="font-bold text-xl text-white">Hello <?php
                          if (isset($_SESSION['username'])) {
                            echo htmlspecialchars($_SESSION['username']);
                          } else {
                            echo "username";
                          } 
                        ?>!</h3>
                    <p class="font-normal text-base text-white pb-4">Welcome to K2 RentCar Dashboard!</p>
                    <p class="font-normal text-base text-white">We have many types of cars that are ready for you
                        to travel <br> anywhere and anytime.
                    </p>
                </div>
                <div class="absolute top-14 pt-2 ms-24 me-10">
                    <img src="src/assets/img/mobilkecil.svg" alt="">
                </div>
            </div>          
            
            <div class="mt-7">
                <h3 class="text-2xl font-bold mb-5 text-dongker">Choose Your Car</h3>
                
                <div class="flex mb-16">
                <?php
                $counter = 0;
                $maxModels = 3;
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        if ($counter >= $maxModels) {
                            break; // Limit the number of displayed models
                        }

                        $model_kendaraan = $row['model_kendaraan'];
                        if (in_array($model_kendaraan, $shownModels)) {
                            continue; // Skip if the model has already been shown
                        }
                        $shownModels[] = $model_kendaraan;

                        // Count available units for this model
                        $countSql = "SELECT COUNT(*) as count FROM kendaraan WHERE model_kendaraan = ? AND status = 'tersedia'";
                        $stmtCount = $conn->prepare($countSql);
                        $stmtCount->bind_param("s", $model_kendaraan);
                        $stmtCount->execute();
                        $countResult = $stmtCount->get_result();
                        $countRow = $countResult->fetch_assoc();
                        $unitCount = $countRow['count'];

                        // Fetch one example of this model for display
                        $exampleSql = "SELECT * FROM kendaraan WHERE model_kendaraan = ? LIMIT 1";
                        $stmtExample = $conn->prepare($exampleSql);
                        $stmtExample->bind_param("s", $model_kendaraan);
                        $stmtExample->execute();
                        $exampleResult = $stmtExample->get_result();
                        $exampleRow = $exampleResult->fetch_assoc();

                        echo '<div class="bg-white shadow-lg rounded-lg max-w-sm mx-5">';
                        echo '  <div class="bg-gray-200 rounded-t-lg px-7 py-10 flex justify-center items-center">';
                        echo '    <img src="data:image/jpeg;base64,' . base64_encode($exampleRow['gambar']) . '" alt="' . htmlspecialchars($exampleRow['model_kendaraan']) . '" class="w-96 h-48">';
                        echo '  </div>';
                        echo '  <div class="p-6">';
                        echo '    <div class="flex items-center justify-between mb-2">';
                        echo '      <h2 class="text-xl font-semibold text-gray-700">' . htmlspecialchars($exampleRow['model_kendaraan']) . '</h2>';
                        echo '      <div class="flex items-center space-x-1 text-gray-600">';
                        echo '        <span class="inline-block w-5 h-5 justify-center items-center">';
                        echo '          <img src="src/assets/img/mobil/unit.png" alt="units">';
                        echo '        </span>';
                        echo '        <span>' . htmlspecialchars($unitCount) . '</span>';
                        echo '      </div>';
                        echo '    </div>';
                        echo '    <div class="flex justify-between items-center mb-6">';
                        echo '      <div class="text-dongker text-2xl font-bold">Rp ' . number_format($exampleRow['harga_per_hari'], 0, ",", ".") . '<span class="font-normal text-[#AEAEB0] text-sm">/Day</span></div>';
                        echo '      <div class="flex items-center space-x-0.5 text-gray-600">';
                        echo '        <span class="inline-block w-5 h-5 justify-center items-center">';
                        echo '          <img src="src/assets/img/mobil/seat.png" alt="seats">';
                        echo '        </span>';
                        echo '        <span>' . htmlspecialchars($exampleRow['jumlah_kursi']) . ' Seats</span>';
                        echo '      </div>';
                        echo '    </div>';
                        echo '    <a href="details.php?id=' . urlencode($exampleRow['id']) . '">';
                        echo '      <button class="w-full py-4 mt-2 border-2 border-current text-current hover:bg-gray-300 hover:text-dongker rounded-lg transition duration-300">See Details</button>';
                        echo '    </a>';
                        echo '  </div>';
                        echo '</div>';

                        $counter++;
                    }
                } else {
                    echo '<p>No vehicles found.</p>';
                }
                ?>    
                </div>
                <div class="text-center pb-16">
                    <a href="SeeAll.php" class="text-base font-semibold text-white bg-merah py-5 px-7 hover:shadow-lg hover:opacity-80 duration-300 ease-in-out">See All</a>
                </div>
            </div>
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

	<script src="src/script.js"></script>
</body>
</html>
