<?php

	require_once('../config.php');

	if(!isLogin()) {
      header("Location: ".$base_url);
      exit();
  }

  $date_from = null;
  $date_to = null;

  if(isset($_GET['date-from']) && isset($_GET['date-to'])) {
    $date_from = $_GET['date-from'];
    $date_to = $_GET['date-to'];
  }

?><!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Talentos | Nación Media</title>
    <link rel="icon" href="<?php echo $base_url ?>/assets/img/icon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="<?php echo $base_url ?>/assets/css/style.css?v=1.0.5" type="text/css" media="all" />
    <style type="text/css">
      .menu-footer {
          text-align: center;
          margin-top: 60px;
      }

      .menu-footer .separate {
          display: inline-block;
          padding: 0 4px;
      }

      .list-medios {
          overflow: hidden;
          margin-top: 24px;
      }

      .list-medios .item {
          float: left;
          width: 30px;
      }

      .list-medios .item img {
          width: 100%;
          border-radius: 50%;
          border: 1px solid #EEE;
      }

      .list-medios .item.isp {
          margin-right: 16px;
          position: relative;
      }

      .list-medios .item.isp:after {
          content: "";
          position: absolute;
          right: -8px;
          top: 0;
          width: 1px;
          height: 100%;
          background: #CCC;
      }

      .btn-reporte {
        position: relative;
      }

      .btn-reporte .spinner-border {
          position: absolute;
          right: 8px;
          top: 7px;
      }
    </style>
  </head>
  <body>

  	<table class="form-signin-t">
        <td>
            <main class="form-signin">
              <?php if($date_from == NULL || $date_to == NULL) { ?>
                <form id="form-excel" action="" method="get">
                  <h3 class="text-center mb-4">Talentos | Nación Media</h3>
                  <div class="form-floating mb-2">
                    <input type="text" class="form-control" id="datefilter" placeholder="Rango de fechas" autocomplete="off" required>
                    <input type="hidden" id="date-from" name="date-from" required>
                    <input type="hidden" id="date-to" name="date-to" required>
                    <label for="datefilter">Rango de fechas</label>
                  </div>
                  <button class="w-100 btn btn-lg btn-primary btn-reporte" type="submit">
                    Generar Reporte
                    <span class="spinner-border d-none" role="status" aria-hidden="true"></span>
                  </button>
                  <div class="alert alert-danger mt-3 error-reporte d-none" role="alert">Ha ocurrido un error, inténtelo nuevamente.</div>
                  <div id="form-info" class="form-text d-none">
                    Puede tardar unos segundos para generar el reporte.
                  </div>
                </form>
              <?php } else { ?>
                <div class="dow-report">
                  <h3 class="text-center mb-4">Talentos | Reporte</h3>
                  <p><b>Rango de fechas:</b> <?php echo date("d-m-Y", strtotime($date_from)) ?> al <?php echo date("d-m-Y", strtotime($date_to)) ?></p>

                  <div class="row">
                    <div class="col-sm">
                      <a class="w-100 btn btn-lg btn-primary" href="<?php echo $base_url ?>/get-excel/generate-excel.php?date-from=<?php echo $date_from ?>&date-to=<?php echo $date_to ?>">
                        Descargar Excel
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-download" viewBox="0 0 16 16">
                          <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5"/>
                          <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708z"/>
                        </svg> 
                      </a>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-sm">
                      <a class="w-100 btn btn-lg btn-primary mt-2" href="<?php echo $base_url ?>/get-excel/reporte-grafica?rrss=instagram&date-from=<?php echo $date_from ?>&date-to=<?php echo $date_to ?>" target="_blank">
                        Ver gráfica Instagram
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-up-right" viewBox="0 0 16 16">
                          <path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5"/>
                          <path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0z"/>
                        </svg>
                      </a>

                      <a class="w-100 btn btn-lg btn-primary mt-2" href="<?php echo $base_url ?>/get-excel/reporte-grafica?rrss=facebook&date-from=<?php echo $date_from ?>&date-to=<?php echo $date_to ?>" target="_blank">
                        Ver gráfica Facebook
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-up-right" viewBox="0 0 16 16">
                          <path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5"/>
                          <path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0z"/>
                        </svg>
                      </a>

                      <a class="w-100 btn btn-lg btn-primary mt-2" href="<?php echo $base_url ?>/get-excel/reporte-grafica?rrss=tiktok&date-from=<?php echo $date_from ?>&date-to=<?php echo $date_to ?>" target="_blank">
                        Ver gráfica Tiktok
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-up-right" viewBox="0 0 16 16">
                          <path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5"/>
                          <path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0z"/>
                        </svg>
                      </a>
                    </div>
                  </div>

                </div>
              <?php } ?>
              <div class="list-medios">
                <div class="item">
                  <img src="images/instagram.jpg" alt="Instagram" title="Instagram" />
                </div>
                <div class="item">
                  <img src="images/facebook.jpg" alt="Facebook" title="Facebook" />
                </div>
                <div class="item isp">
                  <img src="images/tiktok.jpg" alt="Tiktok" title="Tiktok" />
                </div>
                <?php 
                $medios = getMedios();
                foreach ($medios as $medioKey => $medio) { ?>
                  <div class="item">
                    <img src="images/<?php echo $medioKey ?>.jpg" alt="<?php echo $medio['name'] ?>" title="<?php echo $medio['name'] ?>" />
                  </div>
                <?php } ?>
              </div>
            </main>

            <?php if($date_from == NULL || $date_to == NULL) { ?>
            <div class="menu-footer">
              <?php if($_SESSION['tnm_user_id'] == 1) { ?>
              <a href="<?php echo $base_url ?>/get-excel/settings.php" class="btn btn-light">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                  <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/>
                  <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/>
                </svg>
                Configurar Talentos
              </a>
              <span class="separate"></span>
              <?php } ?>
              <a href="<?php echo $base_url ?>/logout.php" class="btn btn-light">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-bar-right" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M6 8a.5.5 0 0 0 .5.5h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L12.293 7.5H6.5A.5.5 0 0 0 6 8m-2.5 7a.5.5 0 0 1-.5-.5v-13a.5.5 0 0 1 1 0v13a.5.5 0 0 1-.5.5"/>
                </svg>
                Salir
              </a>
            </div>
            <?php } else { ?>
              <div class="menu-footer">
              <a href="<?php echo $base_url ?>/get-excel/" class="btn btn-light">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                  <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                </svg>
                Generar otro reporte
              </a>
            </div>
            <?php } ?>
        </td>
    </table>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
		<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
		<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

		<script type="text/javascript">
			$(function() {

        moment.locale('es');

			  $('input#datefilter').daterangepicker({
			      autoUpdateInput: false,
			      locale: {
			          applyLabel: 'Aplicar',
                cancelLabel: 'Limpiar',
                daysOfWeek: ["dom", "lun", "mar", "mie", "jue", "vie", "sab"],
                monthNames: [
                  "Enero",
                  "Febrero",
                  "Marzo",
                  "Abril",
                  "Mayo",
                  "Junio",
                  "Julio",
                  "Agosto",
                  "Septiembre",
                  "Octubre",
                  "Noviembre",
                  "Diciembre"
                ],
			      }
			  });

			  $('input#datefilter').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
			      $('input#date-from').val(picker.startDate.format('YYYY-MM-DD'));
			      $('input#date-to').val(picker.endDate.format('YYYY-MM-DD'));
			  });

			  $('input#datefilter').on('cancel.daterangepicker', function(ev, picker) {
			      $(this).val('');
			  });

        var report_complete = false;

        $('#form-excel').submit(function(event) {
          $('#form-info').removeClass('d-none');
          if(!report_complete) {
            event.preventDefault();

            var date_from = $('#date-from').val();
            var date_to = $('#date-to').val();
            
            $('.btn-reporte').prop('disabled', true);;
            $('.btn-reporte .spinner-border').removeClass('d-none');
            $('.error-reporte').addClass('d-none');
            
            $.post("<?php echo $base_url ?>/get-excel/generate-report.php", {date_from: date_from, date_to: date_to}, function(result){
              $('.btn-reporte').prop('disabled', false);
              $('.btn-reporte .spinner-border').addClass('d-none');
              $('#form-info').addClass('d-none');

              if(result.status == 'success') {
                report_complete = true;
                $( "#form-excel" ).trigger( "submit" );
              } else {
                $('.error-reporte').removeClass('d-none');
              }

            });
          }
        });
			});
		</script>

  </body>
</html><?php 
$mysqli->close(); ?>