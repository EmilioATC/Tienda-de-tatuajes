<?php
include "assets/templates/header.php";

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

  echo '
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <script>
      Swal.fire({
    title: "Bienvenido ' . $user . '!",
    text: "Espero que tengas un buen día :)",
    // html:
    timer: 2000,
    confirmButtonText:
    `<i class="bi bi-hand-thumbs-up"></i>`,
    confirmButtonColor: "#000",
    width: 500,
    padding: "3em",
    color: "#fff",
    background: "#000",
  });
  </script>
  ';

  $sql = "SELECT 
  CONCAT(UPPER(SUBSTRING(`u`.`user`, 1, 1)), SUBSTRING(`u`.`user`, 2))
       AS `Cliente`,
      `u`.`email` AS `Correo`,
      CONCAT(UPPER(SUBSTRING(`t`.`name`, 1, 1)), SUBSTRING(`t`.`name`, 2))
       AS `Tatuador`,
      `a`.`sales_id` AS `Appt`,
      `w`.`work_id` AS `Work`,
      `a`.`create_by` AS `N_cliente`,
      `ta`.`cost` AS `Costo`,
      `ta`.`duration` AS `Duración`,
      `d`.`model` AS `Modelo`,
      `d`.`color` AS `Color`,
      `d`.`size` AS `Tamaño`,
      `a`.`date` AS `Fecha`,
      `a`.`time` AS `Hora`,
      `w`.`num_session` AS `N_sesiones`,
      `ta`.`session_amount` AS `C_sesiones`,
      `w`.`status` AS `Estado`
  FROM
      `supernova`.`sales` `a`
     JOIN `supernova`.`users` `u` ON `a`.`create_by` = `u`.`user_id`
     JOIN `supernova`.`tattooist` `t` ON `a`.`tattooed_by` = `t`.`tattooist_id`
     JOIN `supernova`.`design` `d` ON `a`.`design` = `d`.`design_id`
     JOIN `supernova`.`tattoo_design` `fd` ON `d`.`design_id` = `fd`.`design_id`
     JOIN `supernova`.`tattoo` `ta` ON `fd`.`tattoo_id` = `ta`.`tattoo_id`
     JOIN `supernova`.`work` `w` ON `a`.`work_by` = `w`.`work_id`
  GROUP BY 
    `a`.`sales_id`,
    `w`.`work_id`,
      `u`.`user`,
      `u`.`email`,
      `t`.`name`,
      `ta`.`cost`,
      `ta`.`duration`,
      `d`.`model`,
      `d`.`color`,
      `d`.`size`,
      `a`.`date`,
      `a`.`time`,
      `w`.`num_session`,
      `ta`.`session_amount`,
      `w`.`status`;";



  $result = $mysqli->query($sql);

  if ($result) {
    $rows = array();
    while ($row = $result->fetch_assoc()) {
      $rows[] = $row;
      $costo[] = $row['Costo'];
      $work[] = $row['Work'];
      $n_cliente[] = $row['N_cliente'];
    }
    $result->free();
  } else {
    echo "Error al ejecutar la consulta: " . $mysqli->error;
  }
} else {
  header('Location: ../login.php');
  exit();
}
?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Inicio</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <section class="section dashboard">
    <div class="row">

      <!-- Left side columns -->
      <div class="col-lg-8">
        <div class="row">
          <!-- --------------------------------------------------------------------------------- -->
          <?php
          // Primero, asegúrate de tener una conexión a la base de datos.

          // Supongamos que ya tienes una conexión a la base de datos y la consulta SQL original se encuentra en $sql.
          // A continuación, definimos las consultas para cada filtro:

          // Consulta para el día actual
          $sqlDay = "SELECT sales.`date`, SUM(tattoo.`cost`) AS total_cost
           FROM `supernova`.`sales`
           JOIN `supernova`.`tattoo` ON sales.`work_by` = tattoo.`tattoo_id`
           WHERE sales.`deleted` = 0 AND sales.`date` = CURDATE()
           GROUP BY sales.`date`";

          // Consulta para la semana actual
          $sqlWeek = "SELECT YEARWEEK(sales.`date`) AS week_number, SUM(tattoo.`cost`) AS total_cost
            FROM `supernova`.`sales`
            JOIN `supernova`.`tattoo` ON sales.`work_by` = tattoo.`tattoo_id`
            WHERE sales.`deleted` = 0 AND YEARWEEK(sales.`date`) = YEARWEEK(CURDATE())
            GROUP BY YEARWEEK(sales.`date`)";

          // Consulta para el mes actual
          $sqlMonth = "SELECT YEAR(sales.`date`) AS year, MONTH(sales.`date`) AS month, SUM(tattoo.`cost`) AS total_cost
             FROM `supernova`.`sales`
             JOIN `supernova`.`tattoo` ON sales.`work_by` = tattoo.`tattoo_id`
             WHERE sales.`deleted` = 0 AND YEAR(sales.`date`) = YEAR(CURDATE()) AND MONTH(sales.`date`) = MONTH(CURDATE())
             GROUP BY YEAR(sales.`date`), MONTH(sales.`date`)";

          // Consulta para el año actual
          $sqlYear = "SELECT YEAR(sales.`date`) AS year, SUM(tattoo.`cost`) AS total_cost
            FROM `supernova`.`sales`
            JOIN `supernova`.`tattoo` ON sales.`work_by` = tattoo.`tattoo_id`
            WHERE sales.`deleted` = 0 AND YEAR(sales.`date`) = YEAR(CURDATE())
            GROUP BY YEAR(sales.`date`)";

          // A continuación, ejecutamos las consultas y obtenemos los resultados.

          // Supongamos que ya tenemos la conexión a la base de datos en la variable $conexion.
          $resultDay = mysqli_query($mysqli, $sqlDay);
          $resultWeek = mysqli_query($mysqli, $sqlWeek);
          $resultMonth = mysqli_query($mysqli, $sqlMonth);
          $resultYear = mysqli_query($mysqli, $sqlYear);

          // Luego, podemos obtener los valores de las ganancias para cada filtro.

          // Supongamos que ya hemos obtenido los resultados en las variables $resultDay, $resultWeek y $resultMonth.
          $totalCostDay = 0;
          $totalCostWeek = 0;
          $totalCostMonth = 0;
          $totalCostYear = 0;
          while ($row = mysqli_fetch_assoc($resultDay)) {
            $totalCostDay = $row['total_cost'];
          }
          while ($row = mysqli_fetch_assoc($resultWeek)) {
            $totalCostWeek = $row['total_cost'];
          }
          while ($row = mysqli_fetch_assoc($resultMonth)) {
            $totalCostMonth = $row['total_cost'];
          }
          while ($row = mysqli_fetch_assoc($resultYear)) {
            $totalCostYear = $row['total_cost'];
          }
          ?>

          <style>
            /* Custom CSS for card content */
            .info-card .card-body {
              overflow: hidden;
              /* Hide any content that overflows the card body */
            }

            .info-card h6 {
              white-space: nowrap;
              /* Prevent line breaks in the h6 element */
              overflow: hidden;
              /* Hide any content that overflows the h6 element */
              text-overflow: ellipsis;
              /* Add ellipsis (...) for overflowed text */
            }
          </style>

          <!-- Código HTML en tu página -->
          <div class="col-xxl-6 col-md-8">
            <div class="card info-card sales-card" style="background-color: #F1E3E3;">
              <div class="card-body">
                <h5 class="card-title">Ventas <span>| Hoy</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-currency-dollar"></i>
                  </div>
                  <div class="ps-3">
                    <h6>$<?php echo number_format($totalCostDay, 2); ?></h6>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xxl-6 col-md-8">
            <div class="card info-card sales-card" style="background-color: #d9edf7;">
              <div class="card-body">
                <h5 class="card-title">Ventas <span>| Esta semana</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-currency-dollar"></i>
                  </div>
                  <div class="ps-3">
                    <h6>$<?php echo number_format($totalCostWeek, 2); ?></h6>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xxl-6 col-md-8">
            <div class="card info-card sales-card" style="background-color: #dff0d8;">
              <div class="card-body">
                <h5 class="card-title">Ventas <span>| Este mes</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-currency-dollar"></i>
                  </div>
                  <div class="ps-3">
                    <h6>$<?php echo number_format($totalCostMonth, 2); ?></h6>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-xxl-6 col-md-8">
            <div class="card info-card sales-card" style="background-color: #D9E1F7;">
              <div class="card-body">
                <h5 class="card-title">Ventas <span>| Este año</span></h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i class="bi bi-currency-dollar"></i>
                  </div>
                  <div class="ps-3">
                    <h6>$<?php echo number_format($totalCostYear, 2); ?></h6>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- --------------------------------------------------------------------------------- -->


          <!-- Reports -->
          <div class="col-12">
            <div class="card">

              <div class="card-body">
                <h5 class="card-title">Reporte <span>/Horas</span></h5>

                <!-- Bar Chart -->
                <canvas id="barChartt" style="max-height: 400px;"></canvas>
                <script>
                  document.addEventListener("DOMContentLoaded", () => {
                    <?php
                    // Realizar la consulta a la base de datos
                    $result = $mysqli->query("SELECT HOUR(sales.`time`) AS hour, COUNT(sales.`time`) AS count_time
                FROM `supernova`.`sales`
                WHERE sales.`deleted` = 0 AND YEAR(sales.`date`) = YEAR(CURDATE())
                GROUP BY HOUR(sales.`time`)");

                    $labels = array();
                    $data = array();
                    while ($row = $result->fetch_assoc()) {
                      $hour = $row['hour'];
                      $labels[] = $hour . ':00'; // Usar solo la hora como etiqueta
                      $data[] =  $row['count_time'];
                      
                    }
                    ?>

                    var data = <?php echo json_encode($data); ?>;
                    var labels = <?php echo json_encode($labels); ?>;
                    new Chart(document.querySelector('#barChartt'), {
                      type: 'bar',
                      data: {
                        labels: labels,
                        datasets: [{
                          label: 'Bar Chart',
                          data: data,
                          backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(255, 205, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(201, 203, 207, 0.2)'
                          ],
                          borderColor: [
                            'rgb(255, 99, 132)',
                            'rgb(255, 159, 64)',
                            'rgb(255, 205, 86)',
                            'rgb(75, 192, 192)',
                            'rgb(54, 162, 235)',
                            'rgb(153, 102, 255)',
                            'rgb(201, 203, 207)'
                          ],
                          borderWidth: 1
                        }]
                      },
                      options: {
                        scales: {
                          y: {
                            beginAtZero: true
                          }
                        }
                      }
                    });
                  });
                </script>
                <!-- End Bar Chart -->

              </div>


            </div>
          </div><!-- End Reports -->

          <!-- Ventas -->
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Ventas</h5>
                <?php
                // Consulta SQL para obtener los ingresos totales por mes
                $sql = "SELECT DATE_FORMAT(date, '%Y-%m-%d') AS fecha, SUM(ta.cost) AS total_ingresos
                        FROM sales a
                        JOIN work w ON a.work_by = w.work_id
                        JOIN tattoo ta ON w.work_id = ta.tattoo_id
                        GROUP BY DATE_FORMAT(date, '%Y-%m-%d') ORDER BY fecha";

                // Ejecutar la consulta
                $result = $mysqli->query($sql);

                // Procesar los resultados y generar los datos para el gráfico
                $data = array();
                while ($row = $result->fetch_assoc()) {
                  $data[] = array(
                    'date' => $row['fecha'],
                    'total_earnings' => (float) $row['total_ingresos']
                  );
                }
                ?>

                <!-- Area Chart -->
                <div id="areaChart"></div>

                <script>
                  document.addEventListener("DOMContentLoaded", () => {
                    // Datos obtenidos desde PHP
                    const monthData = <?php echo json_encode($data); ?>;

                    // Extraer las fechas y ganancias de la consulta
                    const dates = monthData.map((data) => data.date);
                    const earnings = monthData.map((data) => data.total_earnings);

                    // Crear el gráfico de área
                    new ApexCharts(document.querySelector("#areaChart"), {
                      series: [{
                        name: "Ganancias Totales",
                        data: earnings
                      }],
                      chart: {
                        type: "area",
                        height: 350,
                        zoom: {
                          enabled: false,
                        },
                      },
                      dataLabels: {
                        enabled: false,
                      },
                      stroke: {
                        curve: "straight",
                      },
                      subtitle: {
                        text: "Movimientos de precios",
                        align: "left",
                      },
                      labels: dates,
                      xaxis: {
                        type: "datetime",
                      },
                      yaxis: {
                        opposite: true,
                      },
                      legend: {
                        horizontalAlign: "left",
                      },
                    }).render();
                  });
                </script>

              </div>
            </div>
          </div><!-- End Ventas -->

          <!-- Ventas -->
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Ventas</h5>
                <!-- ------------------------------------------------------------------------------------------------------------------------- -->
                <?php
                // Consulta SQL para obtener los ingresos totales por mes
                $sql = "SELECT DATE_FORMAT(date, '%Y-%m') AS mes, SUM(ta.cost) AS total_ingresos
                        FROM sales a
                        JOIN work w ON a.work_by = w.work_id
                        JOIN tattoo ta ON w.work_id = ta.tattoo_id
                        GROUP BY DATE_FORMAT(date, '%Y-%m')";

                // Ejecutar la consulta
                $result = $mysqli->query($sql);

                // Procesar los resultados y generar los datos para el gráfico
                $data = array();
                while ($row = $result->fetch_assoc()) {
                  $data[] = array(
                    'month' => $row['mes'],
                    'total_earnings' => (float) $row['total_ingresos']
                  );
                }
                ?>

                <!-- Bar Chart -->
                <div id="barChart"></div>

                <script>
                  document.addEventListener("DOMContentLoaded", () => {
                    // Datos obtenidos desde PHP
                    const monthData = <?php echo json_encode($data); ?>;

                    // Extraer las fechas y ganancias de la consulta
                    const dates = monthData.map((data) => data.month);
                    const earnings = monthData.map((data) => data.total_earnings);

                    // Colores personalizados para cada barra
                    const customColors = [
                      'rgb(75, 192, 192)',
                    ];

                    // Crear el gráfico de barras
                    new ApexCharts(document.querySelector("#barChart"), {
                      series: [{
                        name: "Ganancias Totales",
                        data: earnings
                      }],
                      chart: {
                        type: "bar", // Cambiar el tipo de gráfico a "bar"
                        height: 350,
                        zoom: {
                          enabled: false,
                        },
                      },
                      plotOptions: {
                        bar: {
                          horizontal: false, // Cambiar a "true" para barras horizontales
                        }
                      },
                      dataLabels: {
                        enabled: false,
                      },
                      subtitle: {
                        text: "Movimientos de precios",
                        align: "left",
                      },
                      labels: dates,
                      xaxis: {
                        type: "datetime",
                      },
                      yaxis: {
                        opposite: true,
                      },
                      legend: {
                        horizontalAlign: "left",
                      },
                      colors: customColors,
                    }).render();
                  });
                </script>
              </div>
            </div>
          </div>
          <!-- End Ventas -->

        </div>
      </div><!-- End Left side columns -->

      <!-- Right side columns -->
      <div class="col-lg-4">

        <!-- Tatuadores -->
        <div class="card">
          <div class="card-body pb-0">
            <h5 class="card-title text-center">Tatuadores</h5>

            <div id="trafficChart" style="min-height: 400px;" class="echart"></div>

            <script>
              document.addEventListener("DOMContentLoaded", () => {
                <?php
                // Realizar la consulta a la base de datos
                $result = $mysqli->query("SELECT COUNT(*) AS total, CONCAT(UPPER(SUBSTRING(`t`.`name`, 1, 1)), SUBSTRING(`t`.`name`, 2)) AS `Tatuador` FROM `supernova`.`sales` `a` JOIN `supernova`.`tattooist` `t` ON `a`.`tattooed_by` = `t`.`tattooist_id` GROUP BY `t`.`name`");

                // Procesar los resultados y generar los datos para la gráfica
                $data = array();
                while ($row = $result->fetch_assoc()) {
                  $data[] = array(
                    'value' => $row['total'],
                    'name' => $row['Tatuador']
                  );
                }
                ?>

                var data = <?php echo json_encode($data); ?>;

                echarts.init(document.querySelector("#trafficChart")).setOption({
                  tooltip: {
                    trigger: 'item'
                  },
                  legend: {
                    top: '5%',
                    left: 'center'
                  },
                  series: [{
                    name: 'Access From',
                    type: 'pie',
                    radius: ['40%', '70%'],
                    avoidLabelOverlap: false,
                    label: {
                      show: false,
                      position: 'center'
                    },
                    emphasis: {
                      label: {
                        show: true,
                        fontSize: '18',
                        fontWeight: 'bold'
                      }
                    },
                    labelLine: {
                      show: false
                    },
                    data: data
                  }]
                });
              });
            </script>

          </div>
        </div><!-- End Website Traffic -->
        <!-- End Tatuadores -->

        <!-- Polar Area Chart -->
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Modelos</h5>

            <!-- Polar Area Chart -->
            <canvas id="polarAreaChart" style="max-height: 400px;"></canvas>
            <script>
              document.addEventListener("DOMContentLoaded", () => {
                <?php
                // Realizar la consulta a la base de datos
                $result = $mysqli->query("SELECT 
                                              CONCAT(UPPER(SUBSTRING(model, 1, 1)), SUBSTRING(model, 2)) AS Modelo
                                          FROM
                                              `supernova`.`sales` `a`
                                          JOIN
                                              `supernova`.`design` `d` ON `a`.`design` = `d`.`design_id`
                                          GROUP BY `d`.`model`
                                          ORDER BY `d`.`model`;
                                          ");

                // Procesar los resultados y generar los datos para la gráfica
                $data = array();
                while ($row = $result->fetch_assoc()) {
                  $data[] = array(
                    $row['Modelo']
                  );
                }

                $result2 = $mysqli->query("SELECT 
                                              CONCAT(UPPER(SUBSTRING(model, 1, 1)), SUBSTRING(model, 2)) AS Modelo, COUNT(*) AS total
                                          FROM
                                              `supernova`.`sales` `a`
                                          JOIN
                                              `supernova`.`design` `d` ON `a`.`design` = `d`.`design_id`
                                          GROUP BY `d`.`model`
                                          ORDER BY `d`.`model`;");

                // Procesar los resultados y generar los datos para la gráfica
                $data2 = array();
                while ($row2 = $result2->fetch_assoc()) {
                  $data2[] =  $row2['total'];
                }
                ?>
                var data = <?php echo json_encode($data); ?>;
                var data2 = <?php echo json_encode($data2); ?>;

                new Chart(document.querySelector('#polarAreaChart'), {
                  type: 'polarArea',
                  data: {
                    labels: data,
                    datasets: [{
                      label: 'My First Dataset',
                      data: data2,
                      backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(75, 192, 192)',
                        'rgb(255, 205, 86)',
                        'rgb(159, 224, 128)',
                        'rgb(54, 162, 235)'
                      ]
                    }]
                  }
                });
              });
            </script>
          </div>
        </div><!-- End Polar Area Chart -->

      </div><!-- End Right side columns -->

    </div>
  </section>

</main><!-- End #main -->

<?php include "assets/templates/footer.php"; ?>