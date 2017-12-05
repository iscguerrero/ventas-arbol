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
							<div class="col-xs-8">
								<h4 class="card-title">Reporte Días de Inventario</h4>
								<p class="category">aqui va otra cosa</p>
							</div>
							<form id="formReporte">
								<div class="col-xs-2">
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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/locale/es.js"></script>
	<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap-datetimepicker.js') ?>"></script>
	<script src="<?php echo base_url('assets/js/sweetalert2.js') ?>"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			// Configuracion del cuadro de fecha
			$('.datepicker').datetimepicker({
				locale: 'es',
				format: 'DD-MMMM-YYYY',
				icons: {
						time: "fa fa-clock-o",
						date: "fa fa-calendar",
						up: "fa fa-chevron-up",
						down: "fa fa-chevron-down",
						previous: 'fa fa-chevron-left',
						next: 'fa fa-chevron-right',
						today: 'fa fa-screenshot',
						clear: 'fa fa-trash',
						close: 'fa fa-remove'
				}
			});
			// Generar el reporte de ventas inicial
			$('#formReporte').submit(function(e) {
				e.preventDefault();
				obtenerData('division');
			});
			// Abrir/Cerrar el arbol
			$('#tabla_reporte tbody').on('click', 'span', function() {
				var tr = $(this).closest('tr');
				$(this).hasClass('ti-angle-right') ? $(this).removeClass('ti-angle-right').addClass('ti-angle-down') : $(this).addClass('ti-angle-right').removeClass('ti-angle-down');
			});
		});

		// Funcion para obtener las divisiones
		function obtenerData(tipo) {
			switch (tipo) {
				case 'division':
					url = 'ObtenerDivisiones'
					break;
				default:
					break;
			}
			$.ajax({
				url: url,
				data: {fecha: fecha},
				type: 'POST',
				async: true,
				cache: false,
				dataType: 'json',
				success: function (data) {
					$.each(data, function(key, item) {
						renderRow(parent, item);
					});
				}
			});
		}

		// Funcion para renderizar una fila en el reporte
		function renderRow(parent, row) {
			$parent = document.getElementById(parent);
			$body = document.getElementById('reporte');
			var nextIndex = $parent.rowIndex;
			var newRow = $body.insertRow(nextIndex);

			newRow.setAttribute('id', row['id'])
			.setAttribute('data-tipo', row['tipo'])
			.setAttribute('data-open', rowData['open'])
			.setAttribute('data-division', row['division'])
			.setAttribute('data-zona', row['zona'])
			.setAttribute('data-tienda', row['tienda'])

			var cellDivision = newRow.insertCell(0);
			var cellZona = newRow.insertCell(1);
			var cellTienda = newRow.insertCell(2);
			var cellProducto = newRow.insertCell(3);
			var cellDescripcion = newRow.insertCell(4);
			var cellExistencia = newRow.insertCell(5);
			var cellValor = newRow.insertCell(6);
			var cellVentas = newRow.insertCell(7);
			var cellDiasDeInv = newRow.insertCell(8);

			cellDivision.innerHTML = row.division;
			cellZona.innerHTML = row.zona;
			cellTienda.innerHTML = row.tienda;
			cellProducto.innerHTML = row.producto;
			cellDescripcion.innerHTML = row.descripcion;
			cellExistencia.innerHTML = formato_numero(row['existencia'], 0, '.', ',');
			cellValor.innerHTML = formato_numero(row['valor'], 0, '.', ',');
			cellVentas.innerHTML = formato_numero(row['ventas'], 0, '.', ',');
			cellDiasDeInv.innerHTML = formato_numero(row['dias_de_inv'], 0, '.', ',');
			cellExistencia.className = 'text-right';
			cellValor.className = 'text-right';
			cellVentas.className = 'text-right';
			cellDiasDeInv.className = 'text-right';

			nextIndex = nextIndex + 1;

			//if(row.tipo == 'division')

		}

		function formato_numero(numero, decimales, separador_decimal, separador_miles){
			numero = parseFloat(numero);
			if(isNaN(numero)) return '';
			if(decimales!==undefined) numero=numero.toFixed(decimales);
			numero = numero.toString().replace('.', separador_decimal!==undefined ? separador_decimal : ',');
			if(separador_miles) {
				var miles=new RegExp("(-?[0-9]+)([0-9]{3})");
				while(miles.test(numero)) {
					numero=numero.replace(miles, '$1' + separador_miles + '$2');
				}
			}
			return numero;
		}
	</script>
</html>
