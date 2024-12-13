<?php
session_start();
$user_id = $_SESSION['user_id'];
if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit();
}

if (isset($_COOKIE['userCookie'])) {
  require '../config.php';
  $userId = $_COOKIE['userCookie'];

  $userResult = $mysqli->query("SELECT user FROM users where user_id = '$user_id'");
  $userRow = $userResult->fetch_assoc();
  $user = $userRow['user'];
  $user = ucfirst($user);
} else {
  header('Location: ../login.php');
  exit();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Supernova</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="../assets/img/logo/logo6.png" rel="icon">
  <link href="../assets/img/logo/logo6.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
  <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
  <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
  <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="assets/css/style.css" rel="stylesheet">

  <style>
    /* Estilo para quitar el subrayado de los enlaces */
    .nav-item a,
    .nav-item a:hover,
    .nav-item a:focus {
      text-decoration: none !important;
    }

    .logo {
      text-decoration: none !important;
    }
  </style>
</head>

<body>

  <!-- Header -->
  <header id="header" class="header fixed-top d-flex align-items-center">

    <div class="d-flex align-items-center justify-content-between">
      <a href="index.php" class="logo d-flex align-items-center">
        <img src="../assets/img/logo/logo6.png" alt="">
        <span class="d-none d-lg-block">Supernova</span>
      </a>
      <i class="bi bi-list toggle-sidebar-btn"></i>
    </div><!-- End Logo -->

    <nav class="header-nav ms-auto">
      <ul class="d-flex align-items-center">

        <li class="nav-item dropdown pe-3">

          <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
            <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
            <span class="d-none d-md-block dropdown-toggle ps-2 m-3"><?php echo $user; ?></span>
          </a><!-- EndProfile Image Icon -->


          <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
            <li class="dropdown-header">
              <h6><?php echo $user; ?></h6>
              <span>Usuario</span>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="perfil.php">
                <i class="bi bi-person"></i>
                <span>Perfil</span>
              </a>
            </li>
            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item d-flex align-items-center" href="/usuario/cerrar.php">
                <i class="bi bi-box-arrow-right"></i>
                <span>Cerrar sesión</span>
              </a>
            </li>

          </ul><!-- End Profile Dropdown Items -->

        </li><!-- End Profile Nav -->


      </ul>
    </nav><!-- End Icons Navigation -->
    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar">

      <ul class="sidebar-nav" id="sidebar-nav">

        <li class="nav-item">
          <a class="nav-link collapsed" href="index.php">
            <i class="bi bi-grid"></i>
            <span>Inicio</span>
          </a>
        </li><!-- End Dashboard Nav -->

        <li class="nav-item">
          <a class="nav-link" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="tatuadores.php">
            <i class="bi bi-journal-text"></i><span>Tatuadores</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="forms-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            <li>
              <a href="tatuadores.php" class="active">
                <i class="bi bi-circle"></i><span>Tatuadores</span>
              </a>
            </li>
            <li>
              <a href="a_tatuador.php" class="active">
                <i class="bi bi-circle"></i><span>Agregar tatuador</span>
              </a>
            </li>
          </ul>
        </li><!-- End Forms Nav -->

        <li class="nav-item">
          <a class="nav-link" data-bs-target="#forms-cita" data-bs-toggle="collapse" href="citas.php">
            <i class="bi bi-journal-text"></i><span>Citas</span><i class="bi bi-chevron-down ms-auto"></i>
          </a>
          <ul id="forms-cita" class="nav-content collapse" data-bs-parent="#sidebar-nav">
            <li>
              <a href="citas.php" class="active">
                <i class="bi bi-circle"></i><span>Citas</span>
              </a>
            </li>
          </ul>
        </li><!-- End Forms Nav -->


      </ul>

    </aside><!-- End Sidebar -->

  </header><!-- End Header -->