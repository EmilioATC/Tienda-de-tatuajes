<?php
session_start();
if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit();
}
?>
<?php
include "templates/header.php";
$user_id = $_SESSION['user_id'];
if (!isset($_SESSION['user_id'])) {
  header("Location: ../login.php");
  exit();
}
require '../config.php';

if (!empty($_POST['date'])) {

  $date = $_POST['date'];
  $time = $_POST['time'];
  $tatuador = $_POST['tatuador'];

  // Verificar si el email ya existe en la base de datos
  $existingEmail = $mysqli->query("SELECT date, time, tattooed_by FROM appt WHERE date = '$date' && time = '$time' && tattooed_by = '$tatuador'");
  if ($existingEmail->num_rows > 0) {
    echo '<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Esa fecha ya esta ocupada, puedes poner otra fecha.",
        confirmButtonColor: "#000"
      });
    });
    </script>';
  } else {
    if (isset($_POST['agendar_cita'])) {
      if (!empty($_POST['date'])) {
        $sql2 = "INSERT INTO work (num_session, status) VALUES (?, ?)";
        $stmt2 = $mysqli->prepare($sql2);
        $num_session = 0;
        $status = 0;
        $stmt2->bind_param('ss', $num_session, $status);
        $stmt2->execute();

        $sql = "SELECT MAX(work_by) AS max_work_by FROM appt";
        $result = $mysqli->query($sql);
        if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          $design = $row['max_work_by'] + 1;
          $work_by = $row['max_work_by'] + 1;
        } else {
          $design = 1;
          $work_by = 1;
        }

        $date = $_POST['date'];
        $time = $_POST['time'];
        $tatuador = $_POST['tatuador'];
        $create_d = date('Y-m-d H:i:s');
        $deleted = 0;

        $size = $_POST['size'];
        $color = $_POST['color'];
        $model = $_POST['model'];

        $sql = "INSERT INTO appt (create_by, tattooed_by, design, work_by, deleted, create_d, date, time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);

        $stmt->bind_param('ssssssss', $user_id, $tatuador, $design, $work_by, $deleted, $create_d, $date, $time);
        $sql2 = "INSERT INTO design ( color, model, size) VALUES (?, ?, ?)";
        $stmt2 = $mysqli->prepare($sql2);
        $stmt2->bind_param('sss', $color, $model, $size);
        $stmt2->execute();

        if ($stmt->execute()) {
          echo '<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script>
      document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
          icon: "success",
          title: "Felicidades",
          text: "Agregaste una cita! Para más información de tus citas puedes ver tu historial.",
          footer: `<a href="historial.php">Ver historial</a>`,
          confirmButtonColor: "#000"
        });
      });
      </script>';
        } else {
          echo '<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script>
      document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
          icon: "error",
          title: "Error",
          text: ":(",
          confirmButtonColor: "#000"
        });
      });
      </script>';
        }
      }
    }
  }
}
$sql = "SELECT * FROM users";

$resultado = mysqli_query($mysqli, $sql);

// Verificar si la consulta tuvo éxito
if (!$resultado) {
  die("Error al ejecutar la consulta: " . mysqli_error($mysqli));
}

?>

<main class="align-items-center" style="background-color: black;">
  <!-- ... (otras partes del contenido) ... -->
</main>
<main id="main">
  <section class="contact mt-5">
    <div class="container col-lg-8" data-aos="fade-up">

      <div class="section-title">
        <h2>Registro para solicitar cita</h2>
      </div>
      <div class="row">
        <div class="d-flex align-items-stretch justify-content-center">
          <div class="info">
            <form action="n_cita.php" method="post">
              <div class="row m-3">
                <div class="form-group col-md-6">
                  <label for="disabledSelect" class="form-label">Modelo:</label>
                  <div class="input-group mb-4">
                    <div class="input-group-text"><i class="bi bi-person-bounding-box"></i></div>
                    <select id="disabledSelect" class="form-select" name="model">
                      <option value="animales" <?php if (isset($_POST['model']) && $_POST['model'] == 'animales')
                                                  echo 'selected'; ?>>Animales</option>
                      <option value="flores" <?php if (isset($_POST['model']) && $_POST['model'] == 'flores')
                                                echo 'selected'; ?>>Flores</option>
                      <option value="letras" <?php if (isset($_POST['model']) && $_POST['model'] == 'letras')
                                                echo 'selected'; ?>>Letras</option>
                      <option value="simbolos" <?php if (isset($_POST['model']) && $_POST['model'] == 'simbolos')
                                                  echo 'selected'; ?>>Símbolos</option>
                      <option value="retratos" <?php if (isset($_POST['model']) && $_POST['model'] == 'retratos')
                                                  echo 'selected'; ?>>Retratos</option>
                    </select>
                  </div>
                </div>
                <div class="form-group col-md-6">
                  <label for="disabledSelect" class="form-label">Color:</label>
                  <div class="input-group mb-4">
                    <div class="input-group-text"><i class="bi bi-brush"></i></div>
                    <select id="disabledSelect" class="form-select" name="color">
                      <option value="tradicional" <?php if (isset($_POST['color']) && $_POST['color'] == 'tradicional')
                                                    echo 'selected'; ?>>Tradicional</option>
                      <option value="minimalista" <?php if (isset($_POST['color']) && $_POST['color'] == 'minimalista')
                                                    echo 'selected'; ?>>Minimalista</option>
                      <option value="dotwork" <?php if (isset($_POST['color']) && $_POST['color'] == 'dotwork')
                                                echo 'selected'; ?>>Dotwork</option>
                      <option value="blackwork" <?php if (isset($_POST['color']) && $_POST['color'] == 'blackwork')
                                                  echo 'selected'; ?>>Blackwork</option>
                      <option value="surrealista" <?php if (isset($_POST['color']) && $_POST['color'] == 'surrealista')
                                                    echo 'selected'; ?>>Surrealista</option>
                      <option value="realista" <?php if (isset($_POST['color']) && $_POST['color'] == 'realista')
                                                  echo 'selected'; ?>>Realista</option>
                    </select>
                  </div>
                </div>

                <div class="form-group col-md-6">
                  <label for="disabledSelect" class="form-label">Tamaño:</label>
                  <div class="input-group mb-4">
                    <div class="input-group-text"><i class="bi bi-aspect-ratio"></i></div>
                    <select id="disabledSelect" class="form-select" name="size">
                      <option value="big" <?php if (isset($_POST['size']) && $_POST['size'] == 'big')
                                            echo 'selected'; ?>>
                        Grande</option>
                      <option value="medium" <?php if (isset($_POST['size']) && $_POST['size'] == 'medium')
                                                echo 'selected'; ?>>Mediano</option>
                      <option value="small" <?php if (isset($_POST['size']) && $_POST['size'] == 'small')
                                              echo 'selected'; ?>>Pequeño</option>
                    </select>
                  </div>
                </div>

                <div class="form-group col-md-6">
                  <label for="disabledSelect" class="form-label">Tatuador:</label>
                  <div class="input-group mb-4">
                    <div class="input-group-text"><i class="bi bi-people"></i></div>
                    <select id="disabledSelect" class="form-select" name="tatuador">
                      <option value="1" <?php if (isset($_POST['tatuador']) && $_POST['tatuador'] == '1') echo 'selected'; ?>>Amanda Jepson</option>
                      <option value="2" <?php if (isset($_POST['tatuador']) && $_POST['tatuador'] == '2') echo 'selected'; ?>>William Anderson</option>
                      <option value="3" <?php if (isset($_POST['tatuador']) && $_POST['tatuador'] == '3') echo 'selected'; ?>>Sarah Jhonson</option>
                      <option value="4" <?php if (isset($_POST['tatuador']) && $_POST['tatuador'] == '4') echo 'selected'; ?>>Walter White</option>
                    </select>
                  </div>
                </div>
                <div class="d-grid">
                  <button type="submit" name="calcular_costo" class="btn btn-dark btn-lg shadow-sm m-4">Costos del tatuaje</button>
                </div>
                <div class="form-group col-md-4"></div>
                <div class="form-group col-md-6">
                  <label for="disabledSelect" class="form-label">Costo:</label>
                  <div class="input-group mb-4">
                    <div class="input-group-text"><i class="bi bi-cash"></i></div>
                    <input type="text" class="form-control" id="costo" value="" readonly>
                  </div>
                </div>
                <div class="form-group col-md-6">
                  <label for="disabledSelect" class="form-label">Selecciona una hora:</label>
                  <div class="input-group mb-4">
                    <div class="input-group-text"><i class="bi bi-alarm"></i></div>
                    <select class="form-control" name="time" id="hour">
                    <?php
                      for ($i = 10; $i <= 20; $i++) {
                        printf('<option value="%02d:00">%02d:00</option>', $i, $i);
                      }
                      ?>
                    </select>
                  </div>
                </div>
                <div class="form-group col-md-6">
                  <label for="date" class="form-label">Fecha:</label>
                  <div class="input-group mb-4">
                    <input type="date" id="date" class="form-control" name="date" required>
                  </div>
                </div>
                <div class="d-grid">
                  <button type="submit" name="agendar_cita" class="btn btn-dark btn-lg shadow-sm m-3">Agendar cita</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!--div id="modal" class="modal" >
    <div class="modal-dialog  modal-fullscreen-sm">
      <div id="modal-content" class="modal-content">
      
      </div>
    </div>
  </div-->

  <div id="modal" class="modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Horas disponibles:</h5>
        </div>
        <div class="modal-body">
          <table id="modal-content" class="table table-bordered">

          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
  <?php include "templates/footer.php"; ?>

  <!-- Agregar este script al final del archivo -->
  <script>
        
    var inputDate = document.getElementById("date");
    var tatuadorInput = document.querySelector('[name="tatuador"]');

    var currentDate = new Date();
    currentDate.setDate(currentDate.getDate());
    var maxDate = new Date(currentDate);
    var minDate = currentDate.toISOString().slice(0, 10);
    inputDate.min = minDate;
    maxDate.setDate(currentDate.getDate() + 7);
    var maxDateISO = maxDate.toISOString().slice(0, 10);
    inputDate.max = maxDateISO;

    const appointmentDateInput = document.getElementById('date');
    const modal = document.getElementById('modal');
    const modalContent = document.getElementById('modal-content');

    appointmentDateInput.addEventListener('change', function () {
      const selectedDate = appointmentDateInput.value;
      const selectedTatuador = tatuadorInput.value;
      const tatuador = selectedTatuador || tatuadorInput.options[tatuadorInput.selectedIndex].value;


      // Realizar la solicitud AJAX
      const xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
          modal.style.display = 'block';
          modalContent.innerHTML = xhr.responseText;
        }
      };

      xhr.open('GET', `consultar.php?fecha=${selectedDate}&&tatuador=${tatuador}`, true);
      xhr.send();
    });

    // Cerrar la ventana emergente al hacer clic fuera de ella
    modal.addEventListener('click', function (event) {
      if (event.target === modal) {
        modal.style.display = 'none';
        closeModal();
      }
    });

    // Función para cerrar el modal y restablecer su contenido
    function closeModal() {
      modal.style.display = 'none';
      isModalOpen = false;
      modalContent.innerHTML = '';
    }

    // ... Tu código existente ...

    // Agregar un evento para cerrar el modal cuando se hace clic en el botón "Cerrar"
    document.querySelector('.btn-secondary').addEventListener('click', function () {
      closeModal();
    });

    document.addEventListener("DOMContentLoaded", function() {
      const calculateCostButton = document.querySelector('[name="calcular_costo"]');
      const costoInput = document.getElementById("costo");

      // Función para calcular el costo
      function calcularCosto() {
        const color = document.querySelector('[name="color"]').value;
        const size = document.querySelector('[name="size"]').value;
        const model = document.querySelector('[name="model"]').value;

        // Realizar una petición AJAX al servidor para calcular el costo.
        // Puedes enviar los valores de color, size y model al servidor y obtener el resultado.
        // Por simplicidad, vamos a realizar el cálculo del costo directamente en el cliente.

        let d = 0;
        switch (color) {
          case "tradicional":
            d = 2;
            break;
          case "minimalista":
            d = 3;
            break;
          case "dotwork":
            d = 4;
            break;
          case "blackwork":
            d = 2;
            break;
          case "surrealista":
            d = 5;
            break;
          case "realista":
            d = 5;
            break;
          default:
            d = 0;
        }

        switch (size) {
          case "small":
            d += 2;
            break;
          case "medium":
            d += 3;
            break;
          case "big":
            d += 5;
            break;
        }

        switch (model) {
          case "animales":
            d += 1;
            break;
          case "flores":
            d += 2;
            break;
          case "letras":
            d += 3;
            break;
          case "simbolos":
            d += 4;
            break;
          case "retratos":
            d += 5;
            break;
        }

        let duration, cost, session_amount;
        if (d <= 5) {
          duration = "2 horas";
          cost = 500.0;
          session_amount = 1;
        } else if (d <= 7) {
          duration = "3 horas";
          cost = 1000.0;
          session_amount = 1;
        } else if (d <= 10) {
          duration = "4 horas";
          cost = 1500.0;
          session_amount = 2;
        } else if (d <= 12) {
          duration = "5 horas";
          cost = 4000.0;
          session_amount = 3;
        } else if (d <= 15) {
          duration = "5 horas";
          cost = 5000.0;
          session_amount = 5;
        } else {
          duration = "N/A";
          cost = 0.0;
          session_amount = 0;
        }

        // Mostrar el resultado en el campo de costo
        costoInput.value = cost;
      }

      // Agregar el evento click al botón para calcular el costo
      calculateCostButton.addEventListener("click", function(event) {
        event.preventDefault();
        calcularCosto();
      });

      // Llamar a la función para calcular el costo al cargar la página (por si hay valores seleccionados previamente)
      //calcularCosto();
    });
  </script>
</main>