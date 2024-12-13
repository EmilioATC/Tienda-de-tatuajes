<?php include "assets/templates/header.php";
$user_id = $_SESSION['user_id'];
if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit();
}
require '../config_c.php';


$sql = " select * from appt_user;";

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
    <h1>Citas</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Inicio</a></li>
        <li class="breadcrumb-item active">Citas</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    <div class="row">
      <div class="col-lg-10">

        <div class="card">
          <div class="card-body">
            <table class="table datatable">
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
                      <div class="row">
                        <div class="col-lg-6">
                          <button type="button" class="btn btn-dark " data-bs-toggle="modal" data-bs-target="#modalcitas<?php echo $row['ID']; ?> ">
                            Ver más
                          </button>
                        </div>
                        <div class="col-lg-3">
                          <button type="submit" onclick="Delete(<?php echo $row['ID']; ?>)" class="btn btn-danger">
                            <i class="bi bi-x-square"></i>
                          </button>
                        </div>
                        <div class="col-lg-3">
                          <button type="submit" onclick="Confirm(<?php echo $row['ID']; ?>)" class="btn btn-success">
                            <i class="bi bi-check-square"></i>
                          </button>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <!-- Modal -->
                  <div class="modal fade" id="modalcitas<?php echo $row['ID']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable">
                      <div class="modal-content">
                        <?php
                        $clienteId = $row['ID'];
                        $clienteResult = $mysqli->query("select * from appt_user where ID = '$clienteId'");
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
  </section>
</main><!-- End #main -->

<script type="text/javascript">
  $(document).ready(function() {
    $('.datatable').DataTable();
  });
</script>

<!-- JavaScript for deletion confirmation -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">

<script>
  function Delete(id) {
    Swal.fire({
      title: "¿Estás seguro?",
      text: "¡No podrás revertir esto!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si, bórralo!"
    }).then((result) => {
      if (result.isConfirmed) {
        // Send an AJAX request to the PHP script
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "delete_appt.php?id=" + id, true);
        xhr.onreadystatechange = function() {
          if (xhr.readyState === 4 && xhr.status === 200) {
            // Process the response if needed
            const response = xhr.responseText;
            console.log(response);
          }
        };
        xhr.send();
      }
    });
  }

  function Confirm(id) {
    Swal.fire({
      title: "¿Deseas agregar está cita?",
      text: "¡No podrás revertir esto!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Si, agregala!"
    }).then((result) => {
      if (result.isConfirmed) {
        // Send an AJAX request to the PHP script
        const xhr = new XMLHttpRequest();
        xhr.open("GET", "confirm_appt.php?id=" + id, true);
        xhr.onreadystatechange = function() {
          if (xhr.readyState === 4 && xhr.status === 200) {
            const response = xhr.responseText;
            console.log(response);
          }
        };
        xhr.send();
      }
    });
  }
</script>

<?php include "assets/templates/footer.php"; ?>