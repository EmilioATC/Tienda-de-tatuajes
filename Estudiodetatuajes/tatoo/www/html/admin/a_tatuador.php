<?php include "assets/templates/header.php";
require '../config.php';




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = $_POST['name'];
  $control_n = $_POST['control_n'];
  $phone = $_POST['phone'];
  $email = $_POST['email'];
  $last_name = $_POST['last_name'];

  $sql = "INSERT INTO tattooist (name, control_n, phone, email, last_name) VALUES (?, ?, ?, ?, ?)";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param('sssss', $name, $control_n, $phone, $email, $last_name);


  if ($stmt->execute()) {
    echo '<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
      Swal.fire({
        icon: "success",
        title: "Felicidades",
        text: "Agregaste un nuevo tatuador!",
        footer: `<a href="tatuadores.php">Tatuadores</a>`,
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
        text: "Verifica la información",
        confirmButtonColor: "#000"
      });
    });
    </script>';
  }
}

?>


<main id="main" class="main">

  <div class="pagetitle">
    <h1>Agregar tatuador</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Inicio</a></li>
        <li class="breadcrumb-item">Tatuador</li>
        <li class="breadcrumb-item active">Agregar tatuador</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <main>
    <div class="container">

      <section class="section register min-vh-100 d-flex flex-column py-4">
        <div class="container">
          <div class="row ">
            <div class="col-lg-6 col-md-6 d-flex flex-column ">

              <div class="card mb-3">

                <div class="card-body">

                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Registro</h5>
                    <p class="text-center small">Ingrese sus datos personales para crear una cuenta</p>
                  </div>

                  <form class="row g-3 needs-validation mt-3" novalidate method="POST">
                    <div class="row mb-3">
                      <div class="col-md-6">
                        <label for="yourName" class="form-label">Nombre(s):</label>
                        <input type="text" name="name" class="form-control" id="yourName" required>
                        <div class="invalid-feedback">¡Por favor, escriba su nombre!</div>
                      </div>
                      <div class="col-md-6">
                        <label for="yourName" class="form-label">Apellidos:</label>
                        <input type="text" name="last_name" class="form-control" id="yourName" required>
                        <div class="invalid-feedback">¡Por favor, escriba su apellido!</div>
                      </div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-md-6">
                        <label for="yourName" class="form-label">No. Cuenta:</label>
                        <input type="text" name="control_n" class="form-control" id="yourName" required>
                        <div class="invalid-feedback">¡Por favor, escriba su número de cuenta!</div>
                      </div>

                      <div class="col-md-6">
                        <label for="yourEmail" class="form-label">Telefono:</label>
                        <input type="phone" name="phone" class="form-control" id="yourEmail" required>
                        <div class="invalid-feedback">¡Por favor, escriba su número correctamente!</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <label for="yourEmail" class="form-label">Correo:</label>
                      <div class="input-group has-validation">
                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                        <input type="email" name="email" class="form-control" id="yourEmail" required>
                        <div class="invalid-feedback">¡Por favor, introduce una dirección de correo electrónico válida!</div>
                      </div>
                    </div>

                    <div class="col-12">
                      <div class="form-check">
                        <input class="form-check-input" name="terms" type="checkbox" value="" id="acceptTerms" required>
                        <label class="form-check-label" for="acceptTerms">Estoy de acuerdo y acepto los términos y condiciones</label>
                        <div class="invalid-feedback">Debe estar de acuerdo antes de enviar.</div>
                      </div>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-dark w-100" type="submit">Nuevo tatuador</button>
                    </div>
                  </form>

                </div>
              </div>

            </div>
          </div>
        </div>

      </section>

    </div>
  </main><!-- End #main -->

</main><!-- End #main -->

<?php include "assets/templates/footer.php"; ?>