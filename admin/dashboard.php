<?php require_once(__DIR__ . '/../includes/init.php'); ?>
<?php cek_login($role = array(1)); ?>

<?php
$page = "Alternatif";
require_once('../template/header.php');
?>

<div class="container main-dashboard mt-5">
    <!-- Page Heading and Welcome Message -->
    <div class="header-section text-center mb-4">
        <h1 class="dashboard-title"><i class="fa-solid fa-house-chimney-user me-2"></i>Dashboard Admin</h1>
        <div class="alert alert-welcome shadow-sm mt-3">
            <strong>Selamat datang, ADMIN!</strong> Anda dapat mengoperasikan semua fitur sistem.
        </div>
    </div>

    <!-- Admin Content: Cards for different sections -->
    <div class="row g-4 mt-4 justify-content-center">
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card-modern shadow-sm">
                <div class="card-body text-center">
                    <i class="fa-solid fa-stroopwafel icon-modern mb-3"></i>
                    <h5 class="card-title"><a href="list-kriteria.php" class="link-modern">Data Kriteria</a></h5>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card-modern shadow-sm">
                <div class="card-body text-center">
                    <i class="fa-regular fa-lemon icon-modern mb-3"></i>
                    <h5 class="card-title"><a href="list-sub-kriteria.php" class="link-modern">Data Sub Kriteria</a></h5>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card-modern shadow-sm">
                <div class="card-body text-center">
                    <i class="fa-solid fa-bowl-food icon-modern mb-3"></i>
                    <h5 class="card-title"><a href="list-alternatif.php" class="link-modern">Data Alternatif</a></h5>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once('../template/footer.php');
?>

<!-- Custom CSS for Modern Design -->
<style>
    /* Color Variables */
    :root {
        --dark-green: #004D40;
        --medium-green: #388E3C;
        --dark-peach: #FFCC80;
        --background-color: rgba(255, 255, 255, 0.85);
    }

    /* Body Background */
    html, body {
        height: 100%;
        background: url('../assets/gambar/bg.jpg') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Nunito', sans-serif;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }

    /* Main Container */
    .container.main-dashboard {
        max-width: 1100px;
        padding: 30px;
        background: var(--background-color);
        border-radius: 15px;
        box-shadow: 0px 10px 30px rgba(0, 0, 0, 0.1);
        margin-top: 60px;
    }

    /* Title Styling */
    .dashboard-title {
        font-family: 'Nunito', sans-serif;
        font-weight: 700;
        font-size: 2rem;
        color: var(--dark-green);
    }

    /* Card Styling */
    .card-modern {
        background: var(--background-color);
        border-radius: 20px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
        padding: 20px;
        height: 180px;
    }

    .card-modern:hover {
        transform: translateY(-5px);
        box-shadow: 0px 15px 25px rgba(0, 0, 0, 0.15);
        background: linear-gradient(135deg, #e0f7fa, #b2dfdb);
    }

    /* Card Content Styling */
    .card-title {
        color: var(--dark-green);
        font-weight: 600;
        font-size: 1.15rem;
        margin-top: 15px;
    }

    /* Link Styling */
    .link-modern {
        text-decoration: none;
        color: inherit;
        transition: color 0.2s ease;
    }

    .link-modern:hover {
        color: var(--medium-green);
    }

    /* Icon Styling */
    .icon-modern {
        font-size: 2.5rem;
        color: var(--medium-green);
        opacity: 0.85;
        transition: color 0.2s ease;
    }

    .card-modern:hover .icon-modern {
        color: var(--dark-green);
    }

    /* Welcome Alert */
    .alert-welcome {
        font-size: 1rem;
        font-weight: 500;
        background-color: var(--medium-green);
        color: white;
        border-radius: 20px;
        padding: 10px 20px;
        box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.1);
    }

    /* Responsiveness */
    @media (max-width: 768px) {
        .icon-modern {
            font-size: 2rem;
        }
        .card-title {
            font-size: 1rem;
        }
    }
</style>
