<?php

	require_once('../config.php');

	if(!isLogin() || $_SESSION['tnm_user_id'] != 1) {
      header("Location: ".$base_url);
      exit();
  }

  $new_program_msg = null;

  if(isset($_POST['new_title'])) {

  	$new_program_msg = 'warning';

  	$new_title = isset($_POST['new_title']) ? trim($_POST['new_title']) : null;
  	$new_type = isset($_POST['new_type']) ? trim($_POST['new_type']) : null;
  	$new_hashtag = isset($_POST['new_hashtag']) ? trim($_POST['new_hashtag']) : null;
  	$new_color = isset($_POST['new_color']) ? $_POST['new_color'] : '#FFFFFF';
  	$new_enabled = isset($_POST['new_enabled']) && $_POST['new_enabled'] ? 1 : 0;

  	if(!empty($new_title) && !empty($new_type) && !empty($new_hashtag)) {
  		$new_program_added = addProgram($new_title, $new_type, $new_hashtag, $new_color, $new_enabled);
  		if($new_program_added) {
  			$new_program_msg = 'success';
  		}
  	}

  }

	$programs = getPrograms(true);

?><!doctype html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="refresh" content="300">
    <title>Configuraciones | Nación Media</title>
    <link rel="icon" href="<?php echo $base_url ?>/assets/img/icon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo $base_url ?>/assets/css/style.css?v=1.0.5" type="text/css" media="all" />
    <style type="text/css">
    	.btn.btn-save {
			    min-width: 86px;
			}
			.form-switch {
				  width: 40px;
				  margin: 0 auto;
			}
			.modal .form-switch {
			    margin-left: 0;
			}
    </style>
  </head>
  <body>

  	<div class="container mt-4 mb-4">

  		<?php if($new_program_msg !== null) { ?>
  		<div class="alert alert-<?php echo $new_program_msg ?>" role="alert">
			  <?php echo $new_program_msg == 'success' ? 'Talento agregado correctamente.' : 'Ha ocurrido un error, el Talento no a sido agregado.'; ?>
			</div>
			<?php } ?>

			<button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#modalAddProgram">
			  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
				  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
				</svg>
			  Agregar Talento
			</button>

			<div class="card">
				<div class="card-body">
					<table class="table table-hover">
						<thead>
							<tr>
								<th scope="col">Talentos</th>
								<th scope="col">Tipo</th>
								<th scope="col">Buscar por</th>
								<th class="text-center" scope="col" width="78">Mostrar</th>
								<th class="text-center" scope="col" width="105">Acción</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($programs as $program) { ?>
							<tr>
								<td><?php echo $program['title'] ?></td>
								<td><?php echo $program['type'] ?></td>
								<td>
									<input type="text" class="form-control" id="hashtag-<?php echo $program['id'] ?>" value="<?php echo $program['hashtag'] ?>" />
								</td>
								<td class="text-center">
									<div class="form-check form-switch">
									  <input class="form-check-input" type="checkbox" role="switch" id="enabled-<?php echo $program['id'] ?>" <?php echo $program['enabled'] == 1 ? 'checked' : '' ?> />
									</div>
								</td>
								<td class="text-center"><button type="button" class="btn btn-outline-primary btn-save" data-id="<?php echo $program['id'] ?>">Guardar</button></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
			<a href="<?php echo $base_url ?>/get-excel" class="btn btn-light mt-3 mb-4">
	  		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
				  <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
				</svg>
				Volver
			</a>
  	</div>

  	<!-- Modal -->
  	<div class="modal fade" id="modalAddProgram" tabindex="-1" aria-labelledby="modalAddProgramLabel" aria-hidden="true">
		  <div class="modal-dialog">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h1 class="modal-title fs-5" id="modalAddProgramLabel">Agregar Talento</h1>
		        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
		      </div>
		      <form action="" method="post">
			      <div class="modal-body">
			      	<div class="mb-3">
							  <label for="new_title" class="form-label">Talento</label>
							  <input type="text" class="form-control" id="new_title" name="new_title" placeholder="Nombre del talento" required autocomplete="off" />
							</div>
							<div class="mb-3">
							  <label for="new_type" class="form-label">Tipo</label>
							  <input type="text" class="form-control" id="new_type" name="new_type" value="Talento" placeholder="" required autocomplete="off" />
							</div>
							<div class="mb-3">
							  <label for="new_hashtag" class="form-label">Buscar por</label>
							  <input type="text" class="form-control" id="new_hashtag" name="new_hashtag" placeholder="Username del Talento (@talento) o texto de búsqueda" required autocomplete="off" />
							</div>
							<div class="mb-3">
							  <label for="new_color" class="form-label">Color de fondo de la fila en el Excel</label>
							  <input type="color" class="form-control form-control-color" id="new_color" name="new_color" value="#FFFFFF" style="width: 75px;" />
							</div>
							<div class="mb-3">
							  <label for="new_enabled" class="form-label">Mostrar</label>
							  <div class="form-check form-switch">
								  <input class="form-check-input" type="checkbox" role="switch" id="new_enabled" name="new_enabled" checked />
								</div>
							</div>
			      </div>
			      <div class="modal-footer">
			        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
			        <button type="submit" class="btn btn-primary">Guardar</button>
			      </div>
			    </form>
		    </div>
		  </div>
		</div>

  	<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

		<script type="text/javascript">
			$(function() {

        $('.btn-save').click(function() {
          var id = $(this).data('id');
          var hashtag = $('#hashtag-'+id).val();
          var enabled = $("#enabled-" + id).is(":checked") ? 1 : 0;
          var btn = $(this);
          btn.prop('disabled', true);
          btn.html('...');
          $.post("<?php echo $base_url ?>/get-excel/settings_save.php", {id: id, hashtag: hashtag, enabled: enabled}, function(result){
				    btn.prop('disabled', false);
				    btn.removeClass('btn-outline-primary');
				    if(result.status == 'success') {
				    	btn.addClass('btn-success');
				    	btn.html('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16"><path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/></svg>');
				    } else {
				    	btn.addClass('btn-danger');
				    	btn.html('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16"><path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708"/></svg>');
				    }
				    setTimeout(() => {
						  btn.removeClass('btn-danger').removeClass('btn-success').addClass('btn-outline-primary');
				    	btn.html('Guardar');
						}, 1500);
				  });
        });

			});
		</script>
  </body>
</html><?php 
$mysqli->close(); ?>