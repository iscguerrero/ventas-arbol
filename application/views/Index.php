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
	<style>
		table .header-fixed {
			position: fixed;
			top: 40px;
			z-index: 1020;
		}
	</style>
</head>
<body>
	<div class="container-fluid" style="max-width:1700px">
		<div class="row">
			<div class="col-xs-12">
				<div class="card">
					<div class="card-header">
						<div class="row">
							<div class="col-xs-7">
								<h4 class="card-title">Valor Total del Inventario a Precio de Venta</h4>
								<!--p class="category">aqui va otra cosa</p-->
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
						<div class="row hidden" id="zonas">
							<div class="col-xs-4">
								<div class="card">
									<div class="card-content">
										<div class="row">
											<div class="col-xs-4">
												<div class="icon-big icon-danger text-center">
													<i class="ti-direction-alt"></i>
												</div>
											</div>
											<div class="col-xs-8">
												<div class="numbers">
													<p>Tiendas asignadas a más de una zona</p>
													<font id="ttz"></font>
												</div>
											</div>
										</div>
									</div>
									<div class="card-footer">
										<hr />
										<div class="stats" id="tz">
										</div>
									</div>
								</div>
							</div>
							<div class="col-xs-8">
								<div class="card">
									<div class="card-content">
										<div class="row">
											<div class="col-xs-4">
												<div class="icon-big icon-danger text-center">
													<i class="ti-pulse"></i>
												</div>
											</div>
											<div class="col-xs-8">
												<div class="numbers">
													<p>Tiendas sin zona asignada</p>
													<font id="ttn"></font>
												</div>
											</div>
										</div>
									</div>
									<div class="card-footer">
										<hr />
										<div class="stats" id="tn">
										</div>
									</div>
								</div>
							</div>
						</div>
						<table class="table" id="resumen">
							<thead>
								<th></th>
								<th class="text-right">Valor Inventario Pesos</th>
								<th class="text-right">Venta Promedio Por Día</th>
								<th class="text-right">Dias De Inventario</th>
							</thead>
							<tbody id="bodyResumen"></tbody>
						</table>
						<table class="table table-hover hidden" id="reporte">
							<thead style="background-color:#fff">
								<tr>
									<th>División</th>
									<th>Zona</th>
									<th>Tienda</th>
									<th>Producto</th>
									<th>Descripción</th>
									<th class="text-right">Precio de Venta</th>
									<th class="text-right">Existencia Piezas</th>
									<th class="text-right">Existencia Pesos</th>
									<th class="text-right">Venta Promedio Día Piezas</th>
									<th class="text-right">Ventas Promedio Día Pesos</th>
									<th class="text-right">Días de Inv.</th>
								</tr>
							</thead>
							<tbody id="bodyReporte"></tbody>
							<tfoot id="footReporte"></tfoot>
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
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.freezeheader.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('public/index.js') ?>"></script>
</html>
