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
    <title>About Us</title>
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
            z-index: 4;
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

        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* About Us Section */
        .about-us-section {
            display: flex;
            align-items: center;
            justify-content: center; /* Untuk menempatkan konten di tengah */
            gap: 20px;
            padding: 60px 0;
            background-color: #ffffff;
            background-image: url('../assets/gambar/makanan2.jpg'); /* Gambar background */
            background-size: cover;
            background-position: center;
            position: relative;
            animation: fadeIn 1.2s ease-out both;
            z-index: 2; /* Pastikan bagian ini tetap di atas overlay */
        }

        /* Overlay yang lebih rendah di bawah konten */
        .about-us-section::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white overlay */
            z-index: 1; /* Pastikan overlay berada di bawah konten */
        }

        /* Styling untuk About Us Title */
        .about-us-section h3 {
            font-size: 4rem; /* Ukuran font lebih besar */
            color: var(--dark-green); /* Warna dark green */
            text-align: center;
            margin-bottom: 40px;
            font-weight: 700;
            text-transform: uppercase;
            text-shadow: 4px 4px 10px rgba(0, 0, 0, 0.3); /* Memberikan efek bayangan gelap */
            animation: fadeIn 1.5s ease-out both;
            letter-spacing: 3px;
            z-index: 3; /* Pastikan teks berada di atas overlay */
            position: relative;
            margin-top: 30px;
        }


        /* About Us content */
        .about-us-content {
            max-width: 900px;
            margin: 0 auto;
            font-size: 1.1rem;
            line-height: 1.8;
            text-align: justify;
            color: #333;
            z-index: 3; /* Ensure it's above the overlay */
            position: relative; /* Make sure it's positioned above the overlay */
            margin-top: 3px;
        }

        /* Icon Box Styling */
        .icon-box {
            display: flex;
            justify-content: space-around;
            margin-top: 40px;
            z-index: 3; /* Make sure the icon box is above the overlay */
        }

        .icon-box div {
            text-align: center;
            padding: 20px;
            background-color: var(--dark-green);
            color: white;
            border-radius: 8px;
            transition: transform 0.3s ease;
            margin: 10px;
            position: relative; /* Ensure it’s above any possible overlays */
            z-index: 4; /* Make sure icon boxes are above the overlay */
        }

        .icon-box div:hover {
            transform: scale(1.05);
        }

        .icon-box i {
            gap: 30px;
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        /* Accordion Button Styling */
        .accordion-button {
            background-color: var(--medium-green);
            color: white;
            font-weight: 600;
        }

        .accordion-button:not(.collapsed) {
            background-color: var(--highlight-color);
        }

        /* Hover effects for buttons */
        .btn-outline-light:hover {
            background-color: var(--highlight-color);
            color: white;
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
                        <a class="nav-link" href="tentang-gerd.php">ABOUT GERD</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="about-us.php">ABOUT US</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="registrasi.php" class="btn btn-outline-light me-2">Sign Up</a>
                    <a href="login.php" class="btn btn-outline-light">Sign In</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- About Us Section -->
    <section class="about-us-section" id="about-us">
        <div class="container">
            <h3>About Us</h3>

            <div class="about-us-content">
            <p>L-Healthy adalah platform inovatif yang dirancang untuk membantu penderita GERD dalam memilih menu makanan yang ideal dan sesuai dengan kebutuhan kesehatan mereka. Kami berkomitmen untuk memberikan rekomendasi makanan yang telah divalidasi oleh ahli gizi berlisensi di RSUD Banyumas.</p> <p>Kami memahami bahwa penderita GERD membutuhkan perhatian khusus dalam memilih makanan sehari-hari. Oleh karena itu, kami menyediakan rekomendasi menu yang diprioritaskan untuk mendukung kesehatan pencernaan Anda, membantu mencegah gejala, dan memastikan keseimbangan gizi yang optimal.</p>
                <!-- Accordion for more details -->
                <div class="accordion" id="aboutAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                VISI
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#aboutAccordion">
                            <div class="accordion-body">
                            <p>VISI L-Healthy:</p> 
                            Menjadi platform utama yang membantu penderita GERD dalam mengelola pola makan dan meningkatkan kualitas hidup 
                            mereka.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                MISI
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#aboutAccordion">
                            <div class="accordion-body">
                            <p>MISI L-Healthy:</p> <ul> <li>Menyediakan rekomendasi menu makanan yang berdasarkan pada analisis medis dan gizi yang akurat.</li> <li>Meningkatkan kesadaran tentang pentingnya pola makan sehat bagi penderita GERD.</li> <li>Membantu pengguna membuat pilihan makanan yang tepat melalui sistem yang mudah digunakan dan dapat diandalkan.</li> </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                TUJUAN
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#aboutAccordion">
                            <div class="accordion-body">
                            L-Healthy bertujuan untuk memberikan solusi bagi penderita GERD dalam memilih makanan yang tidak hanya sehat, tetapi juga aman dan sesuai dengan kebutuhan medis mereka. Kami menyusun daftar menu yang diprioritaskan berdasarkan kecocokan dengan kondisi kesehatan dan gizi Anda.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Icon boxes for additional sections -->
            <div class="icon-box">
                <div>
                    <i class="fa-solid fa-bullseye"></i>
                    <h5>MANFAAT</h5>
                    <p>Manfaat L-Healthy adalah penderita GERD dapat dengan mudah menemukan menu makanan yang sesuai dengan kondisi medis mereka, memilih makanan yang membantu meringankan gejala GERD, dan mendapatkan rekomendasi yang didasarkan pada analisis medis dan gizi yang akurat. </p>
                </div>
                <div>
                    <i class="fa-solid fa-check"></i>
                    <h5>VALIDASI DATA</h5>
                    <p>Semua menu makanan di website ini mengacu pada Tabel Komposisi Pangan Indonesia (2017), yang dikembangkan dari versi tahun 2009. Data berasal dari penelitian tentang komposisi gizi pangan oleh Pusat Penelitian dan Pengembangan Gizi dan Pangan, Departemen Kesehatan RI.</p> 
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section" id="contact">
        <div class="container">
            <div class="text-center mb-4">
                <h3 class="fw-bold">Hubungi Kami</h3>
                <p>Informasi Kontak RSUD Banyumas</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-4">
                    <div class="contact-card">
                        <i class="fa-solid fa-location-dot contact-icon"></i>
                        <div class="contact-info">
                            <h6>Alamat :</h6>
                            <p>Jl. Rumah Sakit No.1 Banyumas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-card">
                        <i class="fa-solid fa-phone contact-icon"></i>
                        <div class="contact-info">
                            <h6>Telepon :</h6>
                            <p>(0281) 796031<br>(0281) 796511</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="contact-card">
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

    <section class="credits-section" style="padding: 5px; text-align: center; background-color: #f8f9fa;">
    <p style="font-size: 10px; color: #555;">
        Gambar yang digunakan pada website ini berasal dari 
        <a href="https://www.freepik.com/" target="_blank" rel="noopener noreferrer" style="color: #388E3C; font-weight: bold; text-decoration: none;">Freepik</a>.
    </p>
    </section>



    <!-- Optional JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>
