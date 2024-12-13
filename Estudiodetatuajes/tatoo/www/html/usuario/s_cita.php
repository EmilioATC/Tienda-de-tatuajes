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

$sql = "SELECT * FROM users";

$resultado = mysqli_query($mysqli, $sql);

// Verificar si la consulta tuvo éxito
if (!$resultado) {
  die("Error al ejecutar la consulta: " . mysqli_error($mysqli));
}

if (!empty($_POST['date'])) {
  $userResult = $mysqli->query("SELECT user FROM users where user_id = '$user_id'");
  $userRow = $userResult->fetch_assoc();
  $user = $userRow['user'];

  $sql = "SELECT 
    `a`.`create_by` AS `User_id`,
      `a`.`tattooed_by` AS `Tattooed_id`,
      `a`.`work_by` AS `Work_id`,
      `a`.`design` AS `Design_id`,
      `w`.`status` AS `Estado`
    FROM
      `supernova`.`appt` `a`
      LEFT JOIN `supernova`.`work` `w` ON `a`.`work_by` = `w`.`work_id`
    WHERE
    `a`.`create_by`= '$user_id' AND `w`.`status` = 0
    GROUP BY `a`.`create_by`, `a`.`tattooed_by`, `a`.`work_by`, `a`.`design`, `w`.`status`;";

  $result = $mysqli->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $tatuador = $row['Tattooed_id'];
    $design = $row['Design_id'];
    $work_by = $row['Work_id'];
    echo $tatuador;
    echo $design;
    echo $work_by;

    $date = $_POST['date'];
    $time = $_POST['time'];
    $create_d = date('Y-m-d H:i:s');
    $deleted = 0;

    $sql = "INSERT INTO appt (create_by, tattooed_by, design, work_by, deleted, create_d, date, time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('ssssssss', $user_id, $tatuador, $design, $work_by, $deleted, $create_d, $date, $time);

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
    }
  } else {
    echo '<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "No tienes un tatuaje en seguimiento!",
        footer: `<a href="historial.php">Ver historial</a>`,
        confirmButtonColor: "#000"
      });
    });
    </script>';
  }
}

?>

<main class=" align-items-center" style="background-color: black;">

</main>
<main id="main">
  <section id="contact" class="contact mt-5">
    <div class="container col-lg-8" data-aos="fade-up">
      <div class="section-title">
        <h2>Seguimiento de tatuaje</h2>
        <p><strong>Nota:</strong> Para más información de tus citas puedes ver tu historial.</p>
      </div>
      <div class="row ">
        <div class="d-flex align-items-stretch justify-content-center">
          <div class="info">
            <form method="POST">
              <div class="row m-3">
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
                  <button type="submit" class="btn btn-dark btn-lg shadow-sm mb-4">Agendar cita</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

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
  <script>
        
        var inputDate = document.getElementById("date");
    
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
    
    
          // Realizar la solicitud AJAX
          const xhr = new XMLHttpRequest();
          xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
              modal.style.display = 'block';
              modalContent.innerHTML = xhr.responseText;
            }
          };
    
          xhr.open('GET', `consultar_c.php?fecha=${selectedDate}`, true);
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
</script>