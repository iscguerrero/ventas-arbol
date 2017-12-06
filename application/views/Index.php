<!doctype html>
<html lang="es">
<head>
	<meta charset="utf-8" />
	<link rel="icon" type="image/png" sizes="96x96" href="<?php echo base_url('assets/img/logo.png') ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>Asturiano</title>
	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
	<meta name="viewport" content="width=device-width" />
	<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css') ?>"/>
	<link rel="stylesheet" href="<?php echo base_url('assets/css/paper-dashboard.css') ?>"/>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
	<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Muli:400,300' type='text/css'>
	<link rel="stylesheet" href="<?php echo base_url('assets/css/themify-icons.css') ?>">
</head>
<body>
	<div class="container-fluid" style="max-width:1200px">
		<div class="row">
			<div class="col-xs-12">
				<div class="card">
					<div class="card-header">
						<div class="row">
							<div class="col-xs-7">
								<h4 class="card-title">Reporte Días de Inventario</h4>
								<p class="category">aqui va otra cosa</p>
							</div>
							<form id="formReporte">
								<div class="col-xs-3">
									<input type="text" class="form-control datepicker text-center" name="fecha" id="fecha" placeholder="Al día"/>
								</div>
								<div class="col-xs-2">
									<button type="submit" class="btn btn-primary btn-block btn-fill btn-wd">Generar</button>
								</div>
							</form>
						</div>
					</div>
					<div class="card-content table-responsive table-full-width">
						<table class="table table-hover" id="tabla_reporte">
							<thead>
								<th>División</th>
								<th>Zona</th>
								<th>Tienda</th>
								<th>Producto</th>
								<th>Descripción</th>
								<th class="text-right">Existencia</th>
								<th class="text-right">Valor</th>
								<th class="text-right">Ventas</th>
								<th class="text-right">Días de Inv.</th>
							</thead>
							<tbody id="reporte"></tbody>
						</table>
					</div>
			</div>
		</div>
	</div>
</body>
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-3.1.1.min.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap.min.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/moment.min.js') ?>"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/locale/es.js"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap-datetimepicker.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/sweetalert2.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('public/index.js') ?>"></script>
</html>
