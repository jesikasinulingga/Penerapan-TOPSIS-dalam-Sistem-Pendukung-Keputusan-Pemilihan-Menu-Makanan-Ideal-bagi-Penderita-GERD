<?php
session_start();
require_once(__DIR__ . '/../includes/init.php');

// Pastikan user login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../public/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data menu dari tabel dashboard-user.php
$menuQuery = "SELECT alternatif FROM alternatif";
$menuResult = mysqli_query($koneksi, $menuQuery);

$menuList = [];
while ($menuRow = mysqli_fetch_assoc($menuResult)) {
    $menuList[] = strtolower($menuRow['alternatif']); // Simpan menu dalam array dan ubah menjadi huruf kecil untuk mempermudah pengecekan
}

// Proses POST untuk mengirim permintaan menu baru
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['requested_menu'])) {
    $requestedMenu = strtolower(trim($_POST['requested_menu'])); // Ubah input ke huruf kecil untuk perbandingan
    if (!empty($requestedMenu)) {
        // Periksa apakah menu sudah ada di daftar yang tampil di dashboard-user.php
        if (in_array($requestedMenu, $menuList)) {
            $_SESSION['alert'] = "<div class='alert alert-warning'>Menu yang Anda masukkan sudah tersedia dalam daftar menu kami.</div>";
        } else {
            // Periksa apakah menu sudah pernah direquest oleh user ini
            $checkQuery = "SELECT * FROM permintaan_menu WHERE menu = '$requestedMenu' AND user_id = '$user_id'";
            $checkResult = mysqli_query($koneksi, $checkQuery);

            if (mysqli_num_rows($checkResult) > 0) {
                // Jika menu sudah pernah direquest
                $_SESSION['alert'] = "<div class='alert alert-warning'>Menu ini sudah pernah Anda request.</div>";
            } else {
                // Jika belum, tambahkan ke database
                $query = "INSERT INTO permintaan_menu (menu, tanggal, status, user_id) VALUES ('$requestedMenu', NOW(), 'pending', '$user_id')";
                if (mysqli_query($koneksi, $query)) {
                    $_SESSION['alert'] = "<div class='alert alert-success'>Permintaan menu berhasil dikirim.</div>";
                } else {
                    $_SESSION['alert'] = "<div class='alert alert-danger'>Gagal mengirim permintaan. Silakan coba lagi.</div>";
                }
            }
        }
    } else {
        $_SESSION['alert'] = "<div class='alert alert-danger'>Nama menu tidak boleh kosong.</div>";
    }
    // Redirect untuk menghindari pengulangan form
    header("Location: permintaan-user.php");
    exit;
}

// Ambil data permintaan berdasarkan user_id (DIPINDAHKAN KE SINI)
$query = "SELECT menu, status FROM permintaan_menu WHERE user_id = $user_id ORDER BY tanggal DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Menu Anda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FAFAFA;
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
            text-transform: uppercase;
            margin-right: 20px;
        }

        .navbar-custom .navbar-nav .nav-link:hover {
            color: #FF7043;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }

        .background-section {
            background: url('../assets/gambar/makanan.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }

        .background-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8); /* Transparansi */
        }

        .content-container {
            position: relative;
            z-index: 2;
            max-width: 800px;
            margin: auto;
            padding: 40px 20px;
            animation: fadeIn 2s ease-in-out;
        }

        .form-container {
            background: #ffe0b2;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .form-container h1 {
            font-size: 1.5rem;
            font-weight: 700;
            color: #004d40;
        }

        .form-container input {
            border-radius: 8px;
            padding: 10px;
            border: 1px solid #00695c;
        }

        .form-container button {
            background-color: #00695c;
            color: #ffffff;
            padding: 10px 15px;
            border-radius: 8px;
            border: none;
            font-weight: 600;
            transition: background 0.3s;
        }

        .form-container button:hover {
            background-color: #004d40;
        }

        .table-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: #00695c;
            color: white;
            text-align: center;
        }

        .table tbody td {
            text-align: center;
            color: #004d40;
        }

        .table tbody tr:hover {
            background-color: #ffe0b2;
            transition: background 0.3s;
        }

        .status-approved {
            color: #00695c;
            font-weight: 600;
        }

        .status-rejected {
            color: #d84315;
            font-weight: 600;
        }

        .status-pending {
            color: #8d6e63;
            font-weight: 600;
        }
    </style>
</head>
<body>

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
                    <a class="nav-link fw-bold" href="dashboard-user.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link fw-bold" href="permintaan-user.php">Request Menu</a>
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

<div class="background-section">
    <div class="background-overlay"></div>
    <div class="content-container">
        <div class="form-container">
            <h1>Permintaan Menu Baru</h1>
            <?php
            if (isset($_SESSION['alert'])) {
                echo $_SESSION['alert'];
                unset($_SESSION['alert']);
            }
            ?>
            <form method="POST">
                <div class="mb-3">
                    <label for="requested-menu" class="form-label">Nama Menu Makanan</label>
                    <input type="text" id="requested-menu" name="requested_menu" class="form-control" placeholder="Masukkan nama menu makanan" required>
                </div>
                <button type="submit" class="btn w-100">Kirim Permintaan</button>
            </form>
        </div>

        <div class="table-container">
            <h2 class="text-center mb-4">Status Permintaan Menu Anda</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Menu</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $statusClass = 'status-pending';
                            if ($row['status'] === 'approved') {
                                $statusClass = 'status-approved';
                            } elseif ($row['status'] === 'rejected') {
                                $statusClass = 'status-rejected';
                            }
                            echo "<tr>
                                <td>{$no}</td>
                                <td>{$row['menu']}</td>
                                <td class='{$statusClass}'>" . ucfirst($row['status']) . "</td>
                            </tr>";
                            $no++;
                        }
                    } else {
                        echo "<tr><td colspan='3' class='text-center'>Belum ada permintaan menu</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
