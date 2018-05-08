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
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css"/>
	<link rel="stylesheet" href="<?php echo base_url('assets/css/select2.min.css') ?>"/>
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
		.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
			padding: 5px 5px;
		}
.ui-autocomplete{
	z-index: 2147483647
}
	</style>
</head>
<body>

	<div class="wrapper wrapper-full-page">
		<div class="full-page" data-color="blue">
			<div class="content" style="padding-top: 15px">
				<div class="container-fluid">
					<div class="card">
						<div class="card-header">
							<h4 class="card-title pull-left">Valor Total del Inventario a Precio de Venta <font id="dia"></font></h4>
							<button class="btn btn-info pull-right" type="button" data-toggle="modal" data-target="#modalFiltros">Filtros</button>
						</div>
						<div class="card-content">
							<table class="table" id="resumen">
								<thead>
									<tr>
										<th></th>
										<th class="text-right">Valor Inventario Pesos</th>
										<th class="text-right">Venta Promedio Por Día</th>
										<th class="text-right">Dias De Inventario</th>
									</tr>
								</thead>
								<tbody id="bodyResumen"></tbody>
							</table>
							<table class="table hidden" id="reporte" style="font-size: 12px">
								<thead style="background-color:#fff">
									<tr>
										<td class="text-left">División</td>
										<td class="text-left">Region</td>
										<td class="text-left">Zona</td>
										<td class="text-left">Tienda</td>
										<td class="text-left">Producto</td>
										<td class="text-left">Descripción</td>
										<td class="text-right">Precio Venta</td>
										<td class="text-right">Exist Pz</td>
										<td class="text-right">Exist $</td>
										<td class="text-right">Venta Prom Día Pz</td>
										<td class="text-right">Venta Prom Día $</td>
										<td class="text-right">Días Inv.</td>
									</tr>
								</thead>
								<tbody id="bodyReporte"></tbody>
								<tfoot id="footReporte"></tfoot>
							</table>
						</div>
					</div>
						<div class="row">
							<div class="col-xs-4">
								<div class="card">
									<div class="card-header">
										<h5 class="card-title">Tiendas asignadas a más de una zona <font id="ntz"></font></h5>
									</div>
									<div class="card-content">
										<div class="stats" id="tz"></div>
									</div>
								</div>
							</div>
							<div class="col-xs-4">
								<div class="card">
									<div class="card-header">
										<h5 class="card-title">Tiendas sin zona asignada <font id="ntn"></font></h5>
									</div>
									<div class="card-content">
										<div class="stats" id="tn"></div>
									</div>
								</div>
							</div>
							<div class="col-xs-4">
								<div class="card">
									<div class="card-header">
										<h5 class="card-title">Zonas sin región asignada <font id="nzsr"></font></h5>
									</div>
									<div class="card-content">
										<div class="stats" id="zsr"></div>
									</div>
								</div>
							</div>
						</div>
				</div>
			</div>
		</div>
	</div>

<!-- Modal para mostrar los filtros del reporte -->
<div id="modalFiltros" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title"><i class="fa fa-filter"></i> filtros</h4>
			</div>
			<form id="formFiltros">
				<div class="modal-body">
					<div class="row">
						<div class="col-xs-6">
							<div class="form-group">
								<input type="text" class="form-control datepicker text-center" name="fecha" id="fecha" placeholder="Al día" required />
							</div>
						</div>
						<div class="col-xs-6">
							<div class="form-group">
								<select data-live-search="true" multiple title="Divisiones" class="selectpicker" data-style="btn-info btn-block" data-size="5" id="divisiones" name="divisiones[]"></select>
							</div>
						</div>
						<div class="col-xs-6">
							<div class="form-group">
								<select data-live-search="true" multiple title="Regiones" class="selectpicker" data-style="btn-info btn-block" data-size="5" id="regiones" name="regiones[]" <?php echo $blocked == true ? "disabled" : "" ?>></select>
							</div>
						</div>
						<div class="col-xs-6">
							<div class="form-group">
								<select data-live-search="true" multiple title="Zonas" class="selectpicker" data-style="btn-info btn-block" data-size="5" id="zonas" name="zonas[]" <?php echo $blocked == true ? "disabled" : "" ?>></select>
							</div>
						</div>
						<div class="col-xs-6">
							<div class="form-group">
								<select data-live-search="true" multiple title="Tiendas" class="selectpicker" data-style="btn-info btn-block" data-size="5" id="tiendas" name="tiendas[]" <?php echo $blocked == true ? "disabled" : "" ?>></select>
							</div>
						</div>
						<div class="col-xs-6">
							<div class="checkbox">
									<input name="solo_productos" id="solo_productos" type="checkbox">
									<label for="solo_productos">
									Solo productos
								</label>
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group">
								<select multiple class="form-control" id="proveedores" name="proveedores[]"></select>
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group">
								<input type="text" class="form-control" name="productos[]" id="productos" placeholder="Producto">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<div class="row">
						<div class="col-xs-12 pull-right">
							<button type="button" class="btn btn-default" data-dismiss="modal"><i class="ti ti-close"></i> Cancelar</button>
							<button type="submit" class="btn btn-info"><i class="ti ti-check"></i> Confirmar</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery-3.1.1.min.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap.min.js') ?>"></script>
	<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/core-js/2.4.1/core.js"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/moment.min.js') ?>"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/locale/es.js"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap-datetimepicker.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap-selectpicker.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/sweetalert2.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/select2.full.min.js') ?>"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.freezeheader.js') ?>"></script>
	<script src="<?php echo base_url('assets/js/jquery.cookie.js') ?>" type="text/javascript"></script>
	<script type="text/javascript" src="<?php echo base_url('public/index.js') ?>"></script>
</body>
</html>
