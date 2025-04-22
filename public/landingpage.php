<?php
// Start the session on landing page only
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/../includes/init.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK Pemilihan Menu Makanan dengan TOPSIS</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <style>
        /* Updated Color Variables */
        :root {
            --dark-green: #004D40;
            --medium-green: #388E3C;
            --dark-brown: #8D6E63;
            --light-brown: #A1887F;
            --dark-peach: #FFCC80;
            --background-color: #FFF8E1;
            --primary-green: #004D40;
            --secondary-green: #388E3C;
            --light-green: #C8E6C9;
            --bg-contact: #f1f3f4;
        }

        /* Navbar Styling */
        .navbar-custom {
            background-color: var(--dark-green);
            padding: 10px 20px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 3;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .navbar-custom .navbar-brand {
            color: white;
            font-weight: 900;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
        }

        .navbar-custom .navbar-brand i {
            font-size: 1.8rem;
            margin-right: 8px;
            transition: transform 0.3s ease;
        }

        .navbar-custom .navbar-brand:hover i {
            transform: scale(1.1);
        }

        .navbar-custom .navbar-nav .nav-link {
            color: white;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            transition: color 0.3s ease;
            margin-right: 15px;
        }

        .navbar-custom .navbar-nav .nav-link:hover {
            color: var(--dark-peach);
        }

        .navbar-custom .btn-outline-light {
            border-color: white;
            color: white;
            border-radius: 20px;
            padding: 5px 15px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .navbar-custom .btn-outline-light:hover {
            background-color: white;
            color: var(--dark-green);
        }

        /* Keyframes for Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Animation Delays */
        .fade-in-up {
            animation: fadeInUp 1s ease-out forwards;
        }
        .fade-in {
            animation: fadeIn 1.5s ease-out forwards;
        }
        .slide-in-left {
            animation: slideInLeft 1s ease-out forwards;
        }
        .slide-in-right {
            animation: slideInRight 1s ease-out forwards;
        }

        /* Welcome Section */
        .welcome-section {
            background: url('../assets/gambar/makanan.jpg') no-repeat center center fixed;
            background-size: cover;
            padding: 50px 0;
            position: relative;
            animation: fadeIn 1s ease-in-out forwards;
        }

        .welcome-overlay {
            background-color: rgba(255, 255, 255, 0.7);
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1;
            animation: fadeIn 2s ease-out forwards;
        }

        .welcome-content {
            position: relative;
            z-index: 2;
        }

        .welcome-text h1 {
            color: #333;
            font-weight: bold;
            font-size: 2.5rem;
            margin-bottom: 20px;
            margin-top: 30px;
            animation: fadeInUp 1s ease-in-out forwards;
        }

        .welcome-text p {
            color: #555;
            margin-bottom: 20px;
            font-size: 1.2rem;
            text-align: justify;
            animation: fadeInUp 1.2s ease-in-out forwards;
        }

        .welcome-image {
            margin-top: 50px; /* Menggeser ke bawah */
        }

        .btn-primary-custom {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            transition: background-color 0.3s ease, color 0.3s ease;
            animation: fadeInUp 1.4s ease-in-out forwards;
        }

        .btn-primary-custom:hover {
            background-color: var(--secondary-green);
            border-color: var(--secondary-green);
        }

        /* Styling untuk bagian "Kenali Kami Lebih Dekat" */
        .kenali-section {
            background-color: var(--light-green);
            padding: 0;
            margin: 0;
            animation: fadeIn 1.5s ease-in-out forwards;
        }

        .kenali-section img {
            width: 100vw;
            height: 100%;
            object-fit: cover;
            animation: slideInLeft 1s ease-in-out forwards;
        }

        .kenali-text {
            padding: 40px;
            animation: slideInRight 1.2s ease-in-out forwards;
        }

        .kenali-text h3 {
            color: var(--dark-green);
            font-weight: bold;
            font-size: 1.75rem;
            margin-bottom: 20px;
        }

        .kenali-text p {
            color: #555;
            line-height: 1.8;
            text-align: justify;
        }

        /* Styling untuk bagian "Hubungi Kami" */
        .contact-section {
            background-color: var(--bg-contact);
            padding: 40px 0;
            color: #333;
            animation: fadeIn 1.5s ease-in-out forwards;
        }

        .contact-card {
            display: flex;
            align-items: center;
            padding: 15px;
            transition: transform 0.3s ease;
            animation: fadeInUp 1.3s ease-in-out forwards;
        }

        .contact-card:hover {
            transform: scale(1.02);
        }

        .contact-icon {
            font-size: 2rem;
            color: var(--primary-green);
            margin-right: 15px;
            transition: color 0.3s ease;
        }

        .contact-info h6 {
            font-weight: 700;
            margin: 0;
            color: #333;
            transition: color 0.3s ease;
        }

        .contact-info p {
            margin: 0;
            color: #555;
            transition: color 0.3s ease;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="landingpage.php">
                <i class="fa-solid fa-apple-whole"></i> L-Healthy
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="landingpage.php">HOME</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tentang-gerd.php">ABOUT GERD</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about-us.php">ABOUT US</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="registrasi.php" class="btn btn-outline-light me-2">Sign Up</a>
                    <a href="login.php" class="btn btn-outline-light">Sign In</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Welcome Section -->
    <section class="welcome-section">
        <div class="welcome-overlay fade-in"></div>
        <div class="container welcome-content">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="welcome-text">
                        <h1>Selamat Datang Di Sistem Pendukung Keputusan Pemilihan Menu Makanan Ideal Untuk Penderita GERD</h1>
                        <p>Mendapatkan Saran Makanan Terbaik Berdasarkan Kebutuhan Tubuh Anda.</p>
                        <a href="login.php" class="btn btn-primary-custom">Mulai Sekarang dan Temukan Menu Idealmu</a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="../assets/gambar/gbr2.png" alt="Healthy Food" class="welcome-image fade-in">
                </div>
            </div>
        </div>
    </section>

    <!-- Kenali Kami Lebih Dekat Section -->
    <section class="kenali-section" id="kenali-section">
        <div class="container-fluid p-0 m-0">
            <div class="row g-0">
                <div class="col-lg-6">
                    <img src="../assets/gambar/makanan2.jpg" alt="Makanan" class="img-fluid slide-in-left">
                </div>
                <div class="col-lg-6 d-flex align-items-center p-0">
                    <div class="kenali-text">
                        <h3>Kenali Kami Lebih Dekat</h3>
                        <p>Kami Hadir Untuk Membantu Penderita GERD Menemukan Menu Makanan Ideal Yang Aman Dan Mendukung Kesehatan Pencernaan. Sistem Ini Didukung Oleh Ahli Gizi Dan Profesional Medis, Memastikan Setiap Rekomendasi Berdasarkan Penilaian Yang Akurat. Dengan Teknologi Sistem Pendukung Keputusan, Kami Memberikan Solusi Cepat Dan Tepat, Memandu Anda Dalam Memilih Makanan Yang Aman. Mari Jaga Kesehatan Lambung Anda Dengan Pilihan Menu Yang Lebih Cerdas Dan Menyehatkan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section" id="contact">
        <div class="container">
            <div class="text-center mb-4">
                <h3 class="fw-bold fade-in">Hubungi Kami</h3>
                <p class="fade-in">Informasi Kontak RSUD Banyumas</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="contact-card fade-in-up">
                        <i class="fa-solid fa-location-dot contact-icon"></i>
                        <div class="contact-info">
                            <h6>Alamat :</h6>
                            <p>Jl. Rumah Sakit No.1 Banyumas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-card fade-in-up">
                        <i class="fa-solid fa-phone contact-icon"></i>
                        <div class="contact-info">
                            <h6>Telepon :</h6>
                            <p>(0281) 796031<br>(0281) 796511</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-card fade-in-up">
                        <i class="fa-solid fa-envelope contact-icon"></i>
                        <div class="contact-info">
                            <h6>E-mail :</h6>
                            <p>rsudbanyumas@banyumaskab.go.id</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Optional JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
