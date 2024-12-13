<?php include "assets/templates/header.php";
require '../config.php';

$sql = "select CONCAT(UPPER(SUBSTRING(name, 1, 1)), SUBSTRING(name, 2)) AS `name`, control_n, phone, email, last_name from tattooist;";

$result = $mysqli->query($sql);

if ($result) {
  $rows = array();
  while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
  }
  $result->free();
} else {
  echo "Error al ejecutar la consulta: " . $mysqli->error;
}

?>


<main id="main" class="main">

  <div class="pagetitle">
    <h1>Tatuadores</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item active">Tatuadores</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column py-4">
        <div class="container text-center">
          <div class="justify-content-center align-items-center">
          <div class="row">
          <?php
          foreach ($rows as $row) {
          ?>
            <div class="col-lg-4 col-md-6 mb-4">
              <div class="card">
                <div class="card-header"><i class="bi bi-handbag mr-1"></i> Tatuador</div>
                <div class="card-body">
                  <h5 class="card-title">Tatuador: <?php echo $row['name']  ?></h5>
                  <p class="card-text"><strong>Apellido: </strong><?php echo $row['last_name']; ?></p>
                  <p class="card-text"><strong>No. Cuenta: </strong><?php echo $row['control_n']; ?></p>
                  <p class="card-text"><strong>Telefono: </strong><?php echo $row['phone']; ?></p>
                  <p class="card-text"><strong>Correo: </strong><?php echo $row['email']; ?></p>
                </div>
              </div>
            </div>
          <?php
          }
          ?>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

</main><!-- End #main -->

<?php include "assets/templates/footer.php"; ?>