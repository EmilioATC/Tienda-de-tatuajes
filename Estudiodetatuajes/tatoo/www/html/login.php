<?php
session_start();
require 'config.php';

if (isset($_POST['mostrarMensaje'])) {
    echo '<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            title: "¿Olvidó su contraseña?",
            html: "Comunícate con tu administrador para recuperar tu contraseña",
            confirmButtonColor: "#000",
            didClose: () => {
                window.location.href = "login.php";
            
            }
        });
    });
    </script>';
}

if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Verificar si la cuenta está bloqueada
        if (isset($_SESSION['blocked_until']) && $_SESSION['blocked_until'] > time()) {
            $remainingTime = $_SESSION['blocked_until'] - time();
            $remainingTime = $remainingTime * 1000;
            echo '<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Su cuenta ha sido bloqueada!",
                    html: "Se desbloqueara en <b></b> segundos.",
                    timer: ' . $remainingTime . ',
                    timerProgressBar: true,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                        const b = Swal.getHtmlContainer().querySelector("b");
                        timerInterval = setInterval(() => {
                            b.textContent = Swal.getTimerLeft();
                        }, 100);
                    },
                    willClose: () => {
                        clearInterval(timerInterval);
                    }
                }).then((result) => {
                    /* Read more about handling dismissals below */
                    if (result.dismiss === Swal.DismissReason.timer) {
                        console.log("Se desbloqueo la cuenta, puedes intentarlo otra vez");
                    }
                });
            });
            </script>';
        } else {
            $stmt = $mysqli->prepare('SELECT user_id, email,type_user, password FROM users WHERE email = ?');
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();

            if ($result->num_rows > 0 && password_verify($password, $row['password'])) {
                if (($row['type_user'] === 1)) {
                    setcookie('userCookie', $row['user_id'], time() + 3600, '/');
                    $_SESSION['user_id'] = $row['user_id'];
                    header('Location: admin/index.php');
                    exit();
                } else {
                    setcookie('userCookie', $row['user_id'], time() + 3600, '/');
                    $_SESSION['user_id'] = $row['user_id'];
                    header('Location: usuario/index.php');
                    exit();
                }
            } else {
                // Incrementar el contador de intentos fallidos
                $_SESSION['login_attempts']++;

                // Verificar si se ha alcanzado el número máximo de intentos fallidos
                if ($_SESSION['login_attempts'] >= 3) {
                    // Bloquear la cuenta por 3 minutos
                    $_SESSION['blocked_until'] = time() + 180;
                    echo '<div class="container">
                <div class="col col-lg-6 mt-3 alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Su cuenta ha sido bloqueada!</strong> Espere 3 minutos para volver a intentarlo.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
              </div>';
                } else {
                    echo '<div class="container">
                <div class="col col-lg-6 mt-3 alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error!</strong> El correo o contraseña es incorrecto.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
              <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
              </div>';
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<title> Supernova - Inicio de sesión</title>
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
            background-image: url(assets/img/wallpapers/f13.jpg);
            background-position: center center;
        }
    </style>
</head>

<body>
    <div class="container w-75 bg-primary mt-5 mb-5 rounded shadow">
        <div class="row align-items-stretch">
            <div class="col bg d-none d-lg-block "></div>

            <div class="col bg-white p-5 rounded-end">
                <h2 class="fw-bold text-center mt-1 py-4">Inicio de sesión</h2>
                <form class="needs-validation mt-3" novalidate method="POST">
                    <label for="exampleInputEmail1" class="form-label"><b>Correo electrónico:</b></label>
                    <div class="input-group mb-4">
                        <div class="input-group-text"><i class="bi bi-envelope-at"></i></div>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        <div class="invalid-feedback">¡Por favor, escriba su correo!</div>
                    </div>
                    <label for="inputPassword" class="form-label"><b>Contraseña:</b></label>
                    <div class="input-group mb-4">
                        <div class="input-group-text"><i class="bi bi-key"></i></div>
                        <input class="form-control" id="inputPassword" name="password" type="password" required value="<?php echo isset($_POST['password']) ? htmlspecialchars($_POST['password']) : ''; ?>" />
                        <div class="invalid-feedback">¡Por favor, escriba su contraseña!</div>
                    </div>
                    <div class="mb-4">
                        <input type="checkbox" name="connected" class="form-check-input">
                        <label for="connected" class="form-check-label">Recordar</label>
                    </div>
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-dark btn-lg shadow-sm">Iniciar sesión</button>
                </form>
            </div>
            <div class="mt-3">
                <form id="formRecuperar" method="post" style="display: none;">
                    <input type="hidden" name="mostrarMensaje">
                </form>
                <span>¿Olvidaste tu contraseña? <a href="#" onclick="enviarFormulario()">Recuperar
                        contraseña</a></span><br>
            </div>
            <div class="mt-2">
                <span>¿No tienes cuenta? <a href="registro.php">Regístrate</a></span><br>
            </div>
            <!--div class="container w-100 my-5">
                <div class="row text-center">
                    <div class="col-12">Iniciar Sesión</div>
                </div>
                <div class="row">
                    <div class="col mt-3">
                        <button class="btn btn-outline-primary w-100 my-1">
                            <div class="row align-items-center">
                                <div class="col-2 d-none d-md-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                                        <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951z" />
                                    </svg>
                                </div>
                                <div class="col-12 col-md-10 text-center">
                                    Facebook
                                </div>
                            </div>
                        </button>
                    </div>
                    <div class="col mt-3">
                        <button class="btn btn-outline-danger w-100 my-1">
                            <div class="row align-items-center">
                                <div class="col-2 d-none d-md-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-google" viewBox="0 0 16 16">
                                        <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z" />
                                    </svg>
                                </div>
                                <div class="col-12 col-md-10 text-center text-center">
                                    Google
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </div-->
        </div>

    </div>
    <script>
        function enviarFormulario() {
            document.getElementById("formRecuperar").submit();
        }

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