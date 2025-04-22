<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Sistem Pendukung Keputusan Metode TOPSIS</title>

  <!-- Custom fonts for this template-->
  <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../assets/css/sb-admin-2.css" rel="stylesheet">
  <link href="../assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <script src="../assets/vendor/jquery/jquery.min.js"></script>
  <link rel="shortcut icon" href="../assets/img/favicon.ico" type="image/x-icon">
  <link rel="icon" href="../assets/img/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="../fontawesome/css/all.css">

</head>
<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <!-- Redirect to dashboard.php on logo click -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../admin/dashboard.php">
        <div class="sidebar-brand-icon rotate-n-15">
          <i class="fa-solid fa-apple-whole fa-rotate-by" style="--fa-rotate-angle: 40deg;"></i>
        </div>
        <div class="sidebar-brand-text mx-3">L-Healthy</div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item <?php if($page == "Dashboard"){echo "active";} ?>">
        <!-- Redirect to dashboard.php on Dashboard click -->
        <a class="nav-link" href="../admin/dashboard.php">
          <i class="fa-solid fa-house-chimney-user"></i>
          <span>Dashboard</span>
        </a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Master Data
      </div>

      <li class="nav-item <?php if($page == "Kriteria"){echo "active";} ?>">
        <a class="nav-link" href="../admin/list-kriteria.php">
          <i class="fa-solid fa-stroopwafel"></i>
          <span>Data Kriteria</span>
        </a>
      </li>

      <li class="nav-item <?php if($page == "Sub Kriteria"){echo "active";} ?>">
        <a class="nav-link" href="../admin/list-sub-kriteria.php">
          <i class="fa-regular fa-lemon"></i>
          <span>Data Sub Kriteria</span>
        </a>
      </li>

      <li class="nav-item <?php if($page == "Alternatif"){echo "active";} ?>">
        <a class="nav-link" href="../admin/list-alternatif.php">
          <i class="fa-solid fa-bowl-food"></i>
          <span>Data Alternatif</span>
        </a>
      </li>

      <li class="nav-item <?php if($page == "Penilaian"){echo "active";} ?>">
        <a class="nav-link" href="../admin/list-penilaian.php">
          <i class="fas fa-fw fa-edit"></i>
          <span>Data Penilaian</span>
        </a>
      </li>

      <li class="nav-item <?php if($page == "Permintaan"){echo "active";} ?>">
        <a class="nav-link" href="../admin/list-permintaan.php">
        <i class="fa-solid fa-inbox"></i>
          <span>Data Permintaan</span>
        </a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Master User
      </div>

      <li class="nav-item <?php if($page == "User"){echo "active";} ?>">
        <a class="nav-link" href="../admin/list-user.php">
          <i class="fas fa-fw fa-users-cog"></i>
          <span>Data User</span>
        </a>
      </li> 

      <li class="nav-item <?php if($page == "Profile"){echo "active";} ?>">
        <a class="nav-link" href="../admin/list-profile.php">
          <i class="fa-solid fa-user-gear"></i>
          <span>Data Profile</span>
        </a>
      </li> 

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn text-danger d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="text-uppercase mr-2 d-none d-lg-inline text-gray-600 small">
                  <?php echo $_SESSION['username']; ?>
                </span>
                <span style="color: #A9A9A9;">
                  <i class="fa-solid fa-user-large fa-lg"></i>
                </span>
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="../admin/list-profile.php">
                  <i class="fa-solid fa-user-gear fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <div class="container-fluid">
