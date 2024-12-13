<?php
session_start();
$user_id = $_SESSION['user_id'];
// Verificar si la sesión de usuario no está activa
if (!isset($_SESSION['user_id'])) {
  // Redirigir al usuario a la página de inicio de sesión
  header("Location: ../login.php");
  exit();
}
?>
<?php
include "templates/header.php";
require '../config_c.php';

if (isset($_POST['filter'])) {
  $start_date = $_POST['start_date'];
  $end_date = $_POST['end_date'];

  $sql = "SELECT * from appt_historial where ID_user = ? AND FECHA BETWEEN ? AND ? ; ";

  $statement = $mysqli->prepare($sql);
  $statement->bind_param("iss", $user_id, $start_date, $end_date);
  $statement->execute();
  $result = $statement->get_result();

  if ($result) {
    $rows = array();
    while ($row = $result->fetch_assoc()) {
      $rows[] = $row;
    }
    $result->free();
  } else {
    echo "Error al ejecutar la consulta: " . $mysqli->error;
  }
} else {

  $sql = "SELECT * from appt_historial where ID_user = ?; ";

  $statement = $mysqli->prepare($sql);
  $statement->bind_param("i", $user_id);
  $statement->execute();
  $result = $statement->get_result();

  if ($result) {
    $rows = array();
    while ($row = $result->fetch_assoc()) {
      $rows[] = $row;
    }
    $result->free();
  } else {
    echo "Error al ejecutar la consulta: " . $mysqli->error;
  }
}
?>

<!-- ======= Hero Section ======= -->
<main id="hero" class="d-flex align-items-center" style="background-image: url(../assets/img/wallpapers/f2.jpg); background-size: cover; background-position: center center;">

  <div class="container text-center">
    <h1>Historial</h1>
    <div class="d-flex justify-content-center mt-4">
      <a href="historial.php#main" class="btn-get-started scrollto" style="background-color: red; margin-bottom: 40px;">¡Ver cita!</a>
    </div>
  </div>

  <!-- -->
</main><!-- End Hero -->

<main id="main">
  <section id="contact" class="contact mt-4">
    <div class="row justify-content-evenly">
      <div class="container col-5 ms-5" data-aos="fade-up">
        <div class="section-title">
          <h2>Citas</h2>
          <p><strong>Nota:</strong> Si deseas ver una cita en particular, por favor selecciona el rango de fechas en el cual se encuentra la cita.</p>
        </div>
        <div class="info">
          <form method="POST" action="">
            <div class="row d-flex align-items-stretch justify-content-center mb-5">
              <div class="form-group col-md-6">
                <label for="start_date" class="form-label">Fecha de inicio:</label>
                <input type="date" name="start_date" id="start_date" class="form-control" required>
              </div>
              <div class="form-group col-md-6">
                <label for="end_date" class="form-label">Fecha de fin:</label>
                <input type="date" name="end_date" id="end_date" class="form-control" required>
              </div>
            </div>
            <div class="d-grid">
              <button type="submit" name="filter" class="btn btn-dark btn-lg shadow-sm mb-3">¡Ver cita!</button>
            </div>
            
          </form>
        </div>
      </div>
      <div class="container col-6" data-aos="fade-up">
        <div class="container text-center">
          <div class="card mb-4">
            <div class="card-header"><i class="fas fa-table mr-1"></i> Citas</div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Cliente</th>
                      <th>Tatuador</th>
                      <th>Fecha</th>
                      <th>Hora</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($rows as $row) { ?>
                      <tr>
                        <td><?php echo $row['Cliente']; ?></td>
                        <td><?php echo $row['Tatuador']; ?></td>
                        <td><?php echo $row['Fecha']; ?></td>
                        <td><?php echo $row['Hora']; ?></td>
                        <td>
                          <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalcitas<?php echo $row['ID']; ?> ">
                            Ver más
                          </button>
                        </td>
                      </tr>
                      <!-- Modal -->
                      <div class="modal fade" id="modalcitas<?php echo $row['ID']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-scrollable">
                          <div class="modal-content">
                            <?php
                            $clienteId = $row['ID'];

                            $clienteResult = $mysqli->query("select * from appt_historial where ID = '$clienteId'");
                            $clienteRow = $clienteResult->fetch_assoc();
                            ?>
                            <div class="modal-header">
                              <div class="container text-center">
                                <h3 class="modal-title text-center ">Cliente: <?php echo $clienteRow['Cliente']; ?></h3>
                              </div>
                              <button type="button" class="btn-close close-modal " data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                              <div class="container">
                                <div class="input-group m-2">
                                  <label for="disabledSelect" class="form-label m-2">Tatuador:</label>
                                  <div class="input-group-text"><i class="bi bi-people"></i></div>
                                  <input type="text" class="form-control" value="<?php echo $clienteRow['Tatuador']; ?>" readonly>
                                </div>
                                <div class="input-group m-2">
                                  <label for="disabledSelect" class="form-label m-2">Fecha:</label>
                                  <div class="input-group-text"><i class="bi bi-calendar-event"></i></div>
                                  <input type="text" class="form-control" value="<?php echo $clienteRow['Fecha']; ?>" readonly>
                                </div>
                                <div class="input-group m-2">
                                  <label for="disabledSelect" class="form-label m-2">Hora:</label>
                                  <div class="input-group-text"><i class="bi bi-alarm"></i></div>
                                  <input type="text" class="form-control" value="<?php echo $clienteRow['Hora']; ?>" readonly>
                                </div>
                                <div class="input-group m-2">
                                  <label for="disabledSelect" class="form-label m-2">Costo:</label>
                                  <span class="input-group-text">$</span>
                                  <input type="text" class="form-control" value="<?php echo $clienteRow['Costo']; ?>" readonly>
                                  <span class="input-group-text">.00</span>
                                </div>
                                <div class="input-group m-2">
                                  <label for="disabledSelect" class="form-label m-2">Duración:</label>
                                  <div class="input-group-text"><i class="bi bi-hourglass-split"></i></div>
                                  <input type="text" class="form-control" value="<?php echo $clienteRow['Duracion']; ?>" readonly>
                                </div>
                                <div class="input-group m-2">
                                  <label for="disabledSelect" class="form-label m-2">Modelo:</label>
                                  <div class="input-group-text"><i class="bi bi-person-bounding-box"></i></div>
                                  <input type="text" class="form-control" value="<?php echo $clienteRow['Modelo']; ?>" readonly>
                                </div>
                                <div class="input-group m-2">
                                  <label for="disabledSelect" class="form-label m-2">Color:</label>
                                  <div class="input-group-text"><i class="bi bi-brush"></i></div>
                                  <input type="text" class="form-control" value="<?php echo $clienteRow['Color']; ?>" readonly>
                                </div>
                                <div class="input-group m-2">
                                  <label for="disabledSelect" class="form-label m-2">Tamaño:</label>
                                  <div class="input-group-text"><i class="bi bi-aspect-ratio"></i></div>
                                  <input type="text" class="form-control" value="<?php echo $clienteRow['Tamano']; ?>" readonly>
                                </div>
                                <div class="input-group m-2">
                                  <label for="disabledSelect" class="form-label m-2">N_sesiones:</label>
                                  <div class="input-group-text"><i class="bi bi-list-ol"></i></div>
                                  <input type="text" class="form-control" value="<?php echo $clienteRow['N_sesiones']; ?>" readonly>
                                </div>
                                <div class="input-group m-2">
                                  <label for="disabledSelect" class="form-label m-2">C_sesiones:</label>
                                  <div class="input-group-text"><i class="bi bi-list-check"></i></div>
                                  <input type="text" class="form-control" value="<?php echo $clienteRow['C_sesiones']; ?>" readonly>
                                </div>
                                <div class="input-group m-2">
                                  <label for="disabledSelect" class="form-label m-2">Estado:</label>
                                  <div class="input-group-text"><i class="bi bi-check-circle"></i></div>
                                  <input type="text" class="form-control" value="<?php echo $clienteRow['Estado']; ?>" readonly>
                                </div>

                              </div>
                            </div>
                            <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

  </section>
</main>




<?php include "templates/footer.php"; ?>