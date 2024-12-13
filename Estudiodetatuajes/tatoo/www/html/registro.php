<?php
require 'config.php';

if (!empty($_POST['email']) && !empty($_POST['user']) && !empty($_POST['password']) && !empty($_POST['confirm_password']) && !empty($_POST['phone'])) {
    $email = $_POST['email'];
    $user = $_POST['user'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = $_POST['phone'];
    $type_user = $_POST['type_user'];

    // Verificar si el email y/o el número de teléfono ya existen en la base de datos
    $existingData = $mysqli->query("SELECT email, phone FROM users WHERE email = '$email' OR phone = '$phone'");
    if ($existingData && $existingData->num_rows > 0) {
        echo '<div class="container mt-3">
        <div class="col col-lg-6">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div>
                        <strong>Error!</strong> Este correo o teléfono ya está registrado. Por favor, utilice otro.
                    </div>
                </div>
            </div>
        </div>
    </div>';
    } else {
        $sql = "INSERT INTO users (email, user, password,  phone, type_user) VALUES (?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('sssss', $email, $user, $password, $phone, $type_user);

        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        if ($_POST['password'] == $_POST['confirm_password']) {
            if ($stmt->execute()) {
            echo '<div class="container mt-3">
            <div class="col col-lg-5">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <div>
                            <strong>¡El registro fue exitoso!</strong> Bienvenido '.$user.' ?>.
                        </div>
                    </div>
                </div>
            </div>
        </div>';
            } else {
                $message = 'Hemos tenido problemas al crear su usuario';
            }
        } else {
            echo '<div class="container mt-3">
                <div class="col col-lg-6">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            <div>
                                <strong>¡Lo siento!</strong> Ha ocurrido un error al confirmar la contraseña. Por favor, vuelva a intentarlo.
                            </div>
                        </div>
                    </div>
                </div>
            </div>';
        }
    }
}
?>


<?php if (!empty($message)) : ?>
    <p> <?= $message ?></p>
<?php endif; ?>

<!DOCTYPE html>
<html lang="es">
<title> Supernova - Registro</title>
<meta content="" name="description">
<meta content="" name="keywords">

<!-- Favicons -->
<link href="assets/img/logo/logo6.png" rel="icon">
<link href="assets/img/logo/logo6.png" rel="apple-touch-icon">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

<!-- Vendor CSS Files -->
<link href="assets/vendor/aos/aos.css" rel="stylesheet">
<link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
<link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
<link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
<link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
<link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

<!-- Template Main CSS File -->
<link href="assets/css/style.css" rel="stylesheet">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: #000;
            background-image: linear-gradient(to right, #000 0%, #660000 31%, #800000 70%, #000000);
        }

        .bg {
            background-image: url(assets/img/wallpapers/f4.jpg);
            background-position: center center;
        }
    </style>
</head>

<body>
    <div class="container w-75 bg-primary mt-3 rounded shadow">
        <div class="row align-items-stretch">
            <div class="col bg d-none d-lg-block col-md-4 col-lg-5 col-xl-5 rounded">

            </div>

            <div class="col bg-white p-4 rounded-end">
                <!--div class="text-end">
                        <img src="img/logo2.png" width="48" alt="">
                    </div-->
                <h2 class="fw-bold text-center py-2"><b>Registro</b></h2>
                <form class="needs-validation mt-3" novalidate method="POST">
                    <div class="row m-3">
                        <label for="email" class="form-label"><b>Correo electrónico:</b></label>
                        <div class="input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-envelope-at"></i></div>
                            <input type="email" class="form-control" id="email" name="email" required oninput="this.value = this.value.toLowerCase().replace(/\s/g, '')" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            <div class="invalid-feedback">¡Por favor, escriba su correo!</div>
                        </div>
                        <label for="user" class="form-label"><b>Usuario:</b></label>
                        <div class=" input-group mb-2">
                            <div class="input-group-text"><i class="bi bi-person"></i></div>
                            <input class="form-control" id="user" name="user" type="text" required oninput="this.value = this.value.toLowerCase().replace(/\s/g, '')" value="<?php echo isset($_POST['user']) ? htmlspecialchars($_POST['user']) : ''; ?>"/>
                            <div class="invalid-feedback">¡Por favor, escriba su usuario!</div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <label for="password" class="form-label"><b>Contraseña:</b></label>
                            <div class="input-group mb-2">
                                <div class="input-group-text"><i class="bi bi-key"></i></div>
                                <input class="form-control" id="password" name="password" type="password" required value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>"/>
                                <div class="invalid-feedback">¡Por favor, escriba su contraseña!</div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">
                            <label for="confirm_password" class="form-label"><b>Confirmar contraseña:</b></label>
                            <div class="input-group mb-2">
                                <div class="input-group-text"><i class="bi bi bi-key"></i></div>
                                <input class="form-control" id="confirm_password" name="confirm_password" type="password" required value="<?php echo isset($_POST['confirm_password']) ? htmlspecialchars($_POST['confirm_password']) : ''; ?>"/>
                                <div class="invalid-feedback">¡Por favor, confirme su contraseña!</div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-2">
                            <label for="phone" class="form-label "><b>Telefono:</b></label>
                            <div class="input-group ">
                                <div class="input-group-text "><i class="bi bi-phone"></i></div>
                                <input type="tel" class="form-control" id="phone" pattern="[0-9]{10}" name="phone" required value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                                <div class="invalid-feedback">¡Por favor, escriba su telefono!</div>
                            </div>
                        </div>
                        <div class="col-md-6 mt-2">

                            <div class="form-group mb-4"> <!-- State Button -->
                                <label for="state_id m-4" class="control-label"><b>Puesto:</b></label>
                                <select class="form-control" name="type_user">
                                    <option value="0">Cliente</option>
                                    <option value="1">Empleado</option>
                                </select>
                            </div>

                        </div>
                        <div class="mt-3 mb-3">
                            <div class="form-check">
                                <input class="form-check-input" name="terms" type="checkbox" value="" id="acceptTerms" required >
                                <label class="form-check-label" for="acceptTerms">Estoy de acuerdo y acepto los términos y condiciones</label>
                                <div class="invalid-feedback">Debe estar de acuerdo antes de enviar.</div>
                            </div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark btn-lg shadow-sm">Regístrate</button>
                        </div>
                    </div>



                </form>
                <div class="m-4">
                    <span>¿Tienes cuenta? <a href="login.php">Inicia sesión</a></span><br>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Example starter JavaScript for disabling form submissions if there are invalid fields
        (function() {
            'use strict'

            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.querySelectorAll('.needs-validation')

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms)
                .forEach(function(form) {
                    form.addEventListener('submit', function(event) {
                        if (!form.checkValidity()) {
                            event.preventDefault()
                            event.stopPropagation()
                        }

                        form.classList.add('was-validated')
                    }, false)
                })
        })()

        // Seleccionar el elemento del mensaje de error
        const alertElement = document.querySelector('.alert');

        // Eliminar el mensaje de error después de 5 segundos
        setTimeout(() => {
            alertElement.remove();
        }, 2500);
    </script>

</body>

</html>