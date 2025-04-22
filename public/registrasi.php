<?php
// Memulai sesi
session_start();
require_once(__DIR__ . '/../includes/init.php');

$errors = []; // Array untuk menyimpan pesan error

// Menangkap input dari form
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$nama = isset($_POST['nama']) ? trim($_POST['nama']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';
$confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

// Proses ketika form disubmit
if (isset($_POST['submit'])) {
    // Validasi input form
    if (!$password) {
        $errors[] = 'Password tidak boleh kosong';
    } elseif ($password !== $confirm_password) {
        $errors[] = 'Password dan Konfirmasi Password tidak sama';
    } elseif (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9\W]/', $password)) {
        $errors[] = 'Password harus mengandung huruf besar, huruf kecil, dan angka atau simbol';
    }

    // Jika tidak ada error, lanjutkan proses registrasi
    if (empty($errors)) {
        // Cek apakah username atau email sudah terdaftar
        $stmt = $koneksi->prepare("SELECT * FROM user WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $errors[] = 'Username atau Email sudah terdaftar';
        } else {
            // Hash password sebelum disimpan ke database
            $hashed_password = sha1($password);

            // Simpan data pengguna ke database
            $stmt = $koneksi->prepare("INSERT INTO user (username, nama, email, password, role) VALUES (?, ?, ?, ?, ?)");
            $role = 2; // Set default role sebagai 'user'
            $stmt->bind_param("ssssi", $username, $nama, $email, $hashed_password, $role);

            if ($stmt->execute()) {
                // Jika berhasil, redirect ke halaman login atau dashboard
                header("Location: login.php");
                exit();
            } else {
                $errors[] = 'Terjadi kesalahan saat menyimpan data';
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <!-- Custom styles -->
    <style>
        :root {
            --dark-green: #004D40;
            --medium-green: #388E3C;
            --dark-brown: #8D6E63;
            --light-brown: #A1887F;
            --dark-peach: #FFCC80;
            --background-color: #FFF8E1;
        }

        body {
            background: url('../assets/gambar/makanan2.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Nunito', sans-serif;
            position: relative;
        }

        /* Fade-in Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Navbar */
        .navbar {
            background-color: var(--dark-green);
            padding: 10px 20px;
            position: relative;
            width: 100%;
            top: 0;
            z-index: 3;
        }
        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: 900;
            color: white;
        }
        .navbar-brand i {
            font-size: 1.5rem;
            margin-right: 8px;
        }
        .navbar .btn-outline-light {
            border-color: white;
            color: white;
            border-radius: 20px;
            padding: 5px 15px;
            margin-left: 15px;
            transition: all 0.3s ease;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
        }
        .navbar .btn-outline-light:hover {
            background-color: white;
            color: var(--dark-green);
        }

        /* Overlay */
        .bg-overlay {
            background-color: rgba(255, 255, 255, 0.8);
            position: absolute;
            top: 70px;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
        }

        /* Register Container with Animation */
        .register-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding-top: 40px;
            padding-bottom: 40px; 
            position: relative;
            z-index: 2;
            animation: fadeIn 1s ease-out; /* Subtle fade-in effect */
        }

        .card {
            background-color: white;
            border-radius: 20px;
            box-shadow: 0px 8px 30px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 100%;
            padding: 20px;
            animation: fadeIn 1s ease-out;
        }

        /* Enhanced Header */
        .card-header {
            background-color: transparent;
            color: var(--dark-green);
            font-weight: bold;
            text-align: center;
            padding: 10px;
            font-size: 1.5rem;
            border: none;
            font-family: 'Poppins', sans-serif;
            border-bottom: 2px solid var(--dark-green);
            margin-bottom: 20px;
        }

        /* Input Styling */
        .form-control, .input-group-text {
            border-radius: 10px;
        }
        .input-group-text {
            background-color: var(--medium-green);
            color: white;
            border: none;
        }

        /* Button */
        .btn-custom {
            background-color: var(--dark-green);
            border-color: var(--dark-green);
            color: white;
            border-radius: 10px;
            font-weight: bold;
        }
        .btn-custom:hover {
            background-color: var(--medium-green);
            border-color: var(--medium-green);
        }

        /* Link */
        .text-center a {
            color: var(--dark-green);
            font-weight: bold;
        }
        .text-center a:hover {
            color: var(--medium-green);
        }

        /* Error Messages */
        .alert {
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark shadow-lg py-3">
        <div class="container">
            <a class="navbar-brand" href="../index.php">
                <i class="fa-solid fa-apple-whole"></i> L-Healthy
            </a>
            <div class="d-flex ms-auto">
                <a href="login.php" class="btn btn-outline-light">Login</a>
            </div>
        </div>
    </nav>

    <!-- Overlay after navbar -->
    <div class="bg-overlay"></div>

    <!-- Register Section -->
    <div class="register-container">
        <div class="card">
            <div class="card-header">Register</div>
            <div class="card-body">
                <?php if (!empty($errors)) : ?>
                    <?php foreach ($errors as $error) : ?>
                        <div class="alert alert-danger text-center"><?php echo $error; ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <form action="registrasi.php" method="post">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                        <input required autocomplete="off" type="text" value="<?php echo isset($username) ? htmlentities($username) : ''; ?>" class="form-control" placeholder="Username" name="username">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                        <input required autocomplete="off" type="text" value="<?php echo isset($nama) ? htmlentities($nama) : ''; ?>" class="form-control" placeholder="Nama" name="nama">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-envelope"></i></span>
                        <input required autocomplete="off" type="email" value="<?php echo isset($email) ? htmlentities($email) : ''; ?>" class="form-control" placeholder="Email" name="email">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        <input required autocomplete="off" type="password" class="form-control" name="password" placeholder="Password">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        <input required autocomplete="off" type="password" class="form-control" placeholder="Konfirmasi Password" name="confirm_password">
                    </div>
                    <button type="submit" class="btn btn-custom w-100 mt-3" name="submit">Daftar</button>
                </form>
                <div class="text-center mt-4">
                    <span>Sudah Punya Akun? <a href="login.php">Login Sekarang</a></span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
