<?php
ob_start();
session_start();
require_once(__DIR__ . '/../includes/init.php');
$errors = array();
$username = isset($_POST['username']) ? trim($_POST['username']) : '';
$password = isset($_POST['password']) ? trim($_POST['password']) : '';

if (isset($_POST['submit'])) :
    if (!$username) {
        $errors[] = 'Username tidak boleh kosong';
    }
    if (!$password) {
        $errors[] = 'Password tidak boleh kosong';
    }

    if (empty($errors)) :
        $query = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username'");
        $cek = mysqli_num_rows($query);
        $data = mysqli_fetch_array($query);

        if ($cek > 0) {
            $hashed_password = sha1($password);
            if ($data['password'] === $hashed_password) {
                $_SESSION["user_id"] = $data["id_user"];
                $_SESSION["username"] = $data["username"];
                $_SESSION["role"] = $data["role"];

                // Ubah pengecekan role berdasarkan nilai angka
                if ($data['role'] == 1) { // Role 1 untuk admin
                    header("Location: ../admin/dashboard.php");
                } elseif ($data['role'] == 2) { // Role 2 untuk user
                    header("Location: ../user/dashboard-user.php"); // Redirect ke dashboard user
                }
                exit();
            } else {
                $errors[] = 'Password salah!';
            }
        } else {
            $errors[] = 'Username tidak ditemukan!';
        }
    endif;
endif;
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <!-- Custom styles -->
    <style>
        /* Color Palette */
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
            position: fixed;
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

        /* Login Container with Animation */
        .login-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding-top: 100px;
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

        /* Enhanced Login Header */
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

        /* Toggle Password */
        .password-input {
            position: relative;
        }
        .password-input .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--medium-green);
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
                <a href="registrasi.php" class="btn btn-outline-light">Sign Up</a>
            </div>
        </div>
    </nav>

    <!-- Overlay after navbar -->
    <div class="bg-overlay"></div>

    <!-- Login Section -->
    <div class="login-container">
        <div class="card">
            <div class="card-header">Login</div>
            <div class="card-body">
                <?php if (!empty($errors)) : ?>
                    <?php foreach ($errors as $error) : ?>
                        <div class="alert alert-danger text-center"><?php echo $error; ?></div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <form action="login.php" method="post">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-user"></i></span>
                        <input required autocomplete="off" type="text" value="<?php echo htmlentities($username); ?>" class="form-control" placeholder="Username" name="username">
                    </div>
                    <div class="input-group mb-3 password-input">
                        <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        <input required autocomplete="off" type="password" class="form-control" name="password" placeholder="Password" id="password">
                        <i class="fas fa-eye toggle-password" onclick="togglePassword()"></i>
                    </div>
                    <button name="submit" type="submit" class="btn btn-custom w-100 mt-3"><i class="fas fa-sign-in-alt"></i> Masuk</button>
                </form>
                <div class="text-center mt-4">
                    <span>Belum Punya Akun? <a href="registrasi.php">Daftar Sekarang</a></span>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var toggleIcon = document.querySelector(".toggle-password");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }
    </script>
</body>

</html>
