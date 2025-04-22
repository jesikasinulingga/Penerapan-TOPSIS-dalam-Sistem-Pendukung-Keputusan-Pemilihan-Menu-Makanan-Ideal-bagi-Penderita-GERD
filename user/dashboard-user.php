<?php
session_start();
require_once(__DIR__ . '/../includes/init.php');


// Inisialisasi array sesi untuk menyimpan pilihan makanan jika belum ada
if (!isset($_SESSION['selected_menus'])) {
    $_SESSION['selected_menus'] = [];
}

// Proses POST untuk menyimpan pilihan menu
if (isset($_POST['menu_id'])) {
    $menuId = (int)$_POST['menu_id'];
    $isSelected = in_array($menuId, $_SESSION['selected_menus']);
    
    if ($isSelected) {
        // Hapus menu dari pilihan
        $_SESSION['selected_menus'] = array_diff($_SESSION['selected_menus'], [$menuId]);
        $status = 'removed';
    } else {
        // Tambahkan menu ke pilihan
        $_SESSION['selected_menus'][] = $menuId;
        $status = 'added';
    }

    // Kirimkan respons JSON
    echo json_encode(['status' => $status, 'selected_menus' => $_SESSION['selected_menus']]);
    exit;
}


// Mengambil data alternatif dan kandungan dari database
$query = "
    SELECT alternatif.id_alternatif, alternatif.alternatif, 
           GROUP_CONCAT(CONCAT(kriteria.kriteria, ': ', IFNULL(sub_kriteria.sub_kriteria, '-')) SEPARATOR ', ') AS kandungan
    FROM alternatif
    LEFT JOIN penilaian ON alternatif.id_alternatif = penilaian.id_alternatif
    LEFT JOIN kriteria ON penilaian.id_kriteria = kriteria.id_kriteria
    LEFT JOIN sub_kriteria ON penilaian.nilai = sub_kriteria.id_sub_kriteria
    WHERE kriteria.kriteria != 'Kafein'  -- Menghapus kriteria kafein
    GROUP BY alternatif.id_alternatif
";
$result = mysqli_query($koneksi, $query);
?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css">
    <style>
        /* Styling untuk tampilan yang lebih menarik */
        body {
            background-color: #FFFFFF;
            font-family: 'Poppins', sans-serif;
            margin: 0;
        }
        
        /* Fade-in animation */
        .fade-in {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInEffect 1s ease-in forwards;
        }

        @keyframes fadeInEffect {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .navbar-custom {
            background-color: #004d40;
            padding: 10px 20px;
            position: relative;
            width: 100%;
            top: 0;
            z-index: 3;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }
        .navbar-custom .navbar-brand {
            color: white;
            font-weight: 900;
            font-size: 1.5rem;
        }
        .navbar-custom .navbar-nav .nav-link {
            color: white;
            font-weight: 600;
            font-size: 1rem;
            margin-right: 20px; 
        }
        .navbar-custom .navbar-nav .nav-link:hover {
            color: #FF7043;
        }
        .persuasive-section {
            display: block;
            padding: 60px 20px; /* Tambahkan ruang dalam untuk konten */
            background-color: #ffffff;
            background-image: url('../assets/gambar/bg2.jpg'); /* Ganti dengan URL gambar latar Anda */
            background-size: cover; /* Pastikan gambar latar mencakup seluruh elemen */
            background-position: center; /* Pastikan gambar terpusat */
            position: relative;
            width: 100%;
            height: 100vh; /* Pastikan tinggi elemen mencakup 100% tinggi layar */
            margin: 0; /* Hilangkan margin */
        }

        .persuasive-section .text-content {
            font-family: 'Poppins', sans-serif;
            color: #333;
            position: relative;
            z-index: 2; /* Tetap di atas */
            width: 100%; 
            max-width: 50%; /* Batasi lebar teks agar lebih nyaman dibaca */
            margin-left: 50px; /* Tambahkan margin di kiri agar teks tidak terlalu mentok */
            text-align: justify; /* Pastikan teks tetap rata kiri */
            padding: 0; /* Tidak ada padding tambahan */
            float: left; 
        }

        .persuasive-section h2 {
            color: #d84315;
            font-weight: 800;
            font-size: 2.2rem;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
            text-align: left; 
            margin: 0 0 10px 0; 
            padding: 0; 
        }

        .persuasive-section p {
            font-size: 1.2rem;
            line-height: 1.6;
            color: #333333;
            font-weight: 500;
            text-align:justify; 
            margin: 0; 
            padding: 0; 
        }

        .table-container,
        .content-section {
            background-color: transparent; /* Buat latar belakang transparan */
            box-shadow: none; /* Hapus efek bayangan */
            padding: 0; /* Sesuaikan padding untuk menghindari jarak kosong */
            border: none; /* Hapus border jika ada */
        }

        .table-responsive {
            margin-top: 20px;
        }
        .table thead th {
            background-color: #28a745;
            color: white;
            padding: 10px;
            vertical-align: middle;
            text-align: center;
        }
        .table td {
            vertical-align: middle; /* Rata atas bawah */
            text-align: center; /* Rata kiri kanan */
        }
        .btn-pilih {
            background-color: #FF7043;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            font-weight: bold;
        }
        .btn-pilih.selected {
            background-color: #6c757d; /* Warna tombol saat dipilih */
            color: #fff;
        }
        .btn-rekomendasi {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #004d40;
            color: white;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 8px;
            margin-top: 20px;
            text-decoration: none;
        }
        .btn-rekomendasi:hover {
            background-color: #003933;
        }
        .table-striped tbody tr:nth-child(odd) {
            background-color: #FFFFFF;
        }
    </style>
</head>
<body class="">
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
    <div class="container">
        <a class="navbar-brand" href="dashboard-user.php">
            <i class="fa-solid fa-apple-whole"></i> L-Healthy
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link text-uppercase fw-bold" href="dashboard-user.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-uppercase fw-bold" href="permintaan-user.php">Request Menu</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="editprofile-user.php">Edit Profile</a></li>
                        <li><a class="dropdown-item" href="logout-user.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>


<div class="container-fluid p-0">
    <div class="persuasive-section">
        <div class="text-content fade-in">
            <h2>Temukan Menu Makanan yang Cocok untuk Kamu dan Rasakan Manfaatnya!</h2>
            <p>Kesehatan pencernaan Anda adalah prioritas kami. Rekomendasi menu makanan ini telah disusun untuk mendukung kondisi 
                kesehatan Anda, membantu mengurangi rasa sakit dan mencegah gejala GERD. Mulai langkah pertama menuju pola makan yang 
                lebih sehat dan nikmati rasa nyaman di setiap sajian. Cobalah pilihan menu kami dan rasakan dampak positifnya!</p>
        </div>
    </div>
</div>

<div class="container content-section shadow-lg p-3 mb-5 bg-body rounded table-container fade-in" style="margin-top: 30px;">
    <div class="table-container">
        <h5 class="text-left mb-4">Pilih Beberapa Menu Makanan</h5>
        <div class="d-flex justify-content-between mb-4">
            <input type="text" id="search-input" class="form-control" placeholder="Cari menu makanan..." onkeyup="filterTable()">
            <button class="btn btn-secondary ms-2" onclick="resetTable()">Reset</button>
        </div>
        <div class="table-responsive">
            <table id="menu-table" class="table table-bordered text-center table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Menu Makanan</th>
                        <th>Protein</th>
                        <th>Karbohidrat</th>
                        <th>Lemak</th>
                        <th>Serat</th>
                        <th>Kalori</th>
                        <th>Pilih Menu Makanan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        $kandungan = explode(', ', $row['kandungan']);
                        $data = [];
                        foreach ($kandungan as $item) {
                            list($key, $value) = explode(': ', $item);
                            $data[trim($key)] = $value;
                        }
                        $isSelected = in_array($row['id_alternatif'], $_SESSION['selected_menus']);
                        ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td class="fw-bold"><?= $row['alternatif']; ?></td>
                            <td><?= $data['Protein'] ?? '-'; ?></td>
                            <td><?= $data['Karbohidrat'] ?? '-'; ?></td>
                            <td><?= $data['Lemak'] ?? '-'; ?></td>
                            <td><?= $data['Serat'] ?? '-'; ?></td>
                            <td><?= $data['Kalori'] ?? '-'; ?></td>
                            <td>
                                <button class="btn btn-pilih btn-sm <?= $isSelected ? 'selected' : '' ?>" 
                                        onclick="toggleSelect(this, <?= $row['id_alternatif']; ?>)">
                                    <?= $isSelected ? 'Batal' : 'Pilih' ?>
                                </button>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <a href="hasil_rekomendasi.php" class="btn-rekomendasi">LIHAT HASIL REKOMENDASI</a>
    </div>
</div>



<script>
    // Filter tabel berdasarkan input pencarian
    function filterTable() {
        let input = document.getElementById("search-input").value.toLowerCase();
        let table = document.getElementById("menu-table");
        let rows = table.getElementsByTagName("tr");

        for (let i = 1; i < rows.length; i++) {
            let cells = rows[i].getElementsByTagName("td");
            let menu = cells[1].textContent.toLowerCase();
            rows[i].style.display = menu.startsWith(input) ? "" : "none";
        }
    }

    // Reset tabel dan tampilkan semua baris
    function resetTable() {
        document.getElementById("search-input").value = "";
        filterTable();
    }

    // Fungsi toggle pilih menu makanan
    function toggleSelect(button, menuId) {
        fetch("", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: "menu_id=" + menuId
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'added') {
                // Tombol berubah menjadi 'Batal' saat menu dipilih
                button.classList.add("selected");
                button.textContent = "Batal";
            } else if (data.status === 'removed') {
                // Tombol berubah menjadi 'Pilih' saat menu dibatalkan
                button.classList.remove("selected");
                button.textContent = "Pilih";
            }
        })
        .catch(error => console.error('Error:', error));
    }

</script>  

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
