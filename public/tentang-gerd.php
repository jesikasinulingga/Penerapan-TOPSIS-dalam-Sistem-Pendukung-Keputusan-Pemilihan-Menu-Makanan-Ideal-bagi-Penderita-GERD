<?php
// Start the session if not started
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
    <title>Tentang GERD</title>
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
            --highlight-color: #FF7043;
            --content-bg: #f7f7f7;
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

        /* Apa Itu GERD Section */
        .about-gerd-section {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 60px 0;
            background-color: #ffffff;
            background-image: url('../assets/gambar/makanan2.jpg'); /* Transparent background image */
            background-size: cover;
            background-position: center;
            position: relative;
            animation: fadeIn 1.2s ease-out both;
        }

        .about-gerd-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white overlay */
            z-index: 1;
        }

        .about-gerd-section img {
            max-width: 200px;
            height: auto;
            border-radius: 10px;
            animation: bounce 2s infinite alternate;
            order: 2; /* Place image on the left */
            position: relative;
            z-index: 2;
            margin-top: 30px; 
            margin-left: 140px; 
        }

        .about-gerd-text {
            flex: 1;
            font-family: 'Poppins', sans-serif;
            color: #333;
            order: 1;
            position: relative;
            z-index: 2;
            max-width: 60%; /* Limit width to control text wrapping */
        }

        .about-gerd-text h2 {
            color: var(--highlight-color);
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .about-gerd-text p {
            font-size: 1.1rem;
            line-height: 1.8; /* Adjust line height for more spacing between lines */
            color: #1F1F1F;
            text-align: justify;
        }

        /* Information Section Styling */
        .info-section {
            padding: 60px 0;
            background-color: var(--background-color);
            color: #333;
            font-family: 'Poppins', sans-serif;
        }

        .content-section {
            margin-bottom: 30px;
            background-color: var(--content-bg);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 20px;
            animation: fadeInUp 1s ease-out both;
            min-height: 200px;
        }

        .content-section img {
            width: 150px;
            height: 170px;
            animation: bounce 2s infinite alternate;
            border-radius: 10px;
        }

        .content-section h5 {
            color: var(--dark-green);
            font-weight: bold;
        }

        .content-section p,
        .content-section ul {
            color: #555;
            line-height: 1.8;
            font-size: 1rem;
        }

        .icon {
            color: var(--highlight-color);
            margin-right: 10px;
        }

        /* Animations */
        @keyframes bounce {
            0% {
                transform: translateY(0);
            }
            100% {
                transform: translateY(-10px);
            }
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Styling */
        @media (max-width: 768px) {
            .about-gerd-section {
                flex-direction: column;
                text-align: center;
            }

            .about-gerd-section img {
                order: 0;
                margin-bottom: 15px;
            }

            .content-section {
                flex-direction: column;
                text-align: center;
                min-height: auto;
            }

            .content-section img {
                margin-bottom: 15px;
            }
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
    <!-- Navbar (Same as landingpage.php) -->
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
                        <a class="nav-link active" href="tentang-gerd.php">ABOUT GERD</a>
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

    <!-- Apa Itu GERD Section -->
    <section class="about-gerd-section">
        <div class="container d-flex align-items-center">
            <div class="about-gerd-text">
                <h2>Apa Itu GERD?</h2>
                <p>
                    GERD atau Gastroesophageal Reflux Disease adalah kondisi medis kronis di mana asam lambung atau isi lambung lainnya mengalir kembali ke kerongkongan, menyebabkan iritasi pada lapisan kerongkongan. 
                    GERD dapat menyebabkan gejala yang mengganggu seperti nyeri dada dan rasa terbakar di dada, yang dikenal sebagai heartburn. Penyakit ini juga dapat memengaruhi kualitas hidup dan membutuhkan perhatian medis yang tepat.
                </p>
            </div>
            <img src="../assets/gambar/dokterku.png" alt="Dokter Menjelaskan GERD">
        </div>
    </section>

    <!-- Information Section -->
    <section class="info-section">
        <div class="container">
            <!-- Gejala Section -->
            <div class="content-section">
                <img src="../assets/gambar/gerd.png" alt="Ilustrasi Gejala GERD">
                <div>
                    <h5><i class="fas fa-exclamation-triangle icon"></i>Gejala GERD</h5>
                    <p>Gejala umum GERD meliputi:</p>
                    <ul>
                        <li>Heartburn (nyeri dada seperti terbakar, biasanya setelah makan atau saat berbaring)</li>
                        <li>Regurgitasi (kembalinya makanan atau asam ke dalam mulut)</li>
                        <li>Sulit menelan</li>
                        <li>Rasa asam atau pahit di mulut</li>
                    </ul>
                </div>
            </div>

            <!-- Penyebab Section -->
            <div class="content-section">
                <img src="../assets/gambar/gerd1.png" alt="Ilustrasi Penyebab GERD">
                <div>
                    <h5><i class="fas fa-flask icon"></i>Penyebab GERD</h5>
                    <p>GERD dapat disebabkan oleh beberapa faktor, termasuk:</p>
                    <ul>
                        <li>Melemahnya otot LES (Lower Esophageal Sphincter) yang menghubungkan lambung dan kerongkongan</li>
                        <li>Makan terlalu banyak atau terlalu cepat</li>
                        <li>Makanan atau minuman tertentu seperti alkohol, kopi, makanan berlemak, dan coklat</li>
                        <li>Obesitas atau kelebihan berat badan</li>
                    </ul>
                </div>
            </div>

            <!-- Pencegahan Section -->
            <div class="content-section">
                <img src="../assets/gambar/gerd2.png" alt="Ilustrasi Pencegahan GERD">
                <div>
                    <h5><i class="fas fa-shield-alt icon"></i>Pencegahan GERD</h5>
                    <p>Beberapa cara untuk mencegah atau mengurangi risiko GERD antara lain:</p>
                    <ul>
                        <li>Makan dalam porsi kecil dan sering</li>
                        <li>Menghindari makanan pemicu seperti makanan pedas atau berlemak</li>
                        <li>Tidak berbaring setelah makan</li>
                        <li>Menurunkan berat badan jika diperlukan</li>
                    </ul>
                </div>
            </div>

            <!-- Pengobatan Section -->
            <div class="content-section">
                <img src="../assets/gambar/gerd3.png" alt="Ilustrasi Pengobatan GERD">
                <div>
                    <h5><i class="fas fa-stethoscope icon"></i>Pengobatan GERD</h5>
                    <p>Pilihan pengobatan GERD meliputi:</p>
                    <ul>
                        <li>Obat-obatan antasida untuk menetralkan asam lambung</li>
                        <li>Obat untuk mengurangi produksi asam lambung</li>
                        <li>Perubahan gaya hidup, seperti diet dan pola makan</li>
                        <li>Operasi, dalam kasus yang sangat parah</li>
                    </ul>
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
