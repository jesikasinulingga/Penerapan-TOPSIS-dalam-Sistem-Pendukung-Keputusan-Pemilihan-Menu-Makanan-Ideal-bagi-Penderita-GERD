<?php
require_once(__DIR__ . '/../includes/init.php');

// Start the session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
        /* Full height background image with transparency */
        body {
            background-image: url('../assets/gambar/makanan2.jpg'); /* Background image */
            background-size: cover; /* Cover the entire viewport */
            background-position: center; /* Center the background */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            overflow: hidden;
        }

        /* Overlay to make background transparent */
        .background-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6); /* Dark transparent overlay */
            z-index: 0; /* Place behind the card */
            backdrop-filter: blur(6px); /* Adds blur effect */
        }

        /* Centered logout card */
        .logout-card {
            background-color: #ffffff; /* Solid white background */
            border-radius: 12px;
            padding: 25px;
            max-width: 400px;
            width: 100%;
            text-align: center;
            box-shadow: 0px 6px 16px rgba(0, 0, 0, 0.3); /* Card shadow */
            z-index: 1; /* Ensure it's above the background overlay */
            font-family: Arial, sans-serif;
        }

        /* Text styling */
        .logout-card h5 {
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }
        .logout-card p {
            color: #666;
            margin-bottom: 20px;
        }

        /* Button styles */
        .btn-secondary {
            background-color: #6c757d;
            border: none;
            color: #fff;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
        }
        .btn-danger {
            background-color: #dc3545;
            border: none;
            color: #fff;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="background-overlay"></div> <!-- Transparent overlay for background -->

<div class="logout-card">
    <h5>Konfirmasi Keluar</h5>
    <p>Anda yakin ingin keluar?</p>
    <div class="mt-3">
        <a class="btn btn-secondary" href="dashboard-user.php" role="button">Batal</a>
        <a class="btn btn-danger" id="confirmLogout"><i class="fas fa-sign-out-alt mr-1"></i> Keluar</a>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // Cek apakah tombol sudah ada
        $('#confirmLogout').on('click', function(e) {
            e.preventDefault(); // Mencegah perilaku default anchor (klik link)
            
            // Mengirim permintaan logout melalui AJAX
            $.ajax({
                type: 'POST',
                url: '', // Tidak perlu URL, proses logout langsung di sini
                data: {
                    action: 'logout' // Menandakan bahwa ini adalah permintaan logout
                },
                success: function(response) {
                    // Redirect ke halaman landingpage.php setelah logout berhasil
                    window.location.href = "../public/landingpage.php";
                },
                error: function(xhr, status, error) {
                    console.error("Terjadi kesalahan saat logout:", error);
                }
            });
        });
    });
</script>

<?php
// Proses logout di sini
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'logout') {
    // Menghancurkan sesi
    session_start();
    session_unset(); // Menghapus semua session variables
    session_destroy(); // Menghancurkan sesi
    exit(); // Akhiri proses PHP setelah logout
}
?>

</body>
</html>
