var nextIndex = 0;
//var colores = ['#ddabae', '#e5c0c2', '#eed5d6', '#f6eaea', '#ffffff'];
//var colores = ['#9ba1d6', '#b4b8e0', '#cdd0ea', '#e6e7f4', '#ffffff'];
var colores = ['#c9d8d8', '#d7e1e2', '#e4ebeb', '#f1f5f5', '#ffffff'];
$(document).ready(function () {
	// Configuracion del datepicker
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
	// Generar el resumen de ventas
	$('#formReporte').submit(function (e) {
		e.preventDefault();
		renderResumen();
	});
	// Mostrar / Ocultar el arbol
	$('#resumen tbody').on('click', 'span', function () {
		$this = $(this);
		var tr = $(this).closest('tr');
		if (tr.attr('data-open') == 'false') {
			tr.attr('data-open', true);
			$this.removeClass('ti-angle-right').addClass('ti-angle-down');
			obtenerData('division', false);
			$('#reporte').removeClass('hidden');
		} else {
			tr.attr('data-open', false);
			$this.addClass('ti-angle-right').removeClass('ti-angle-down');
			$('#bodyReporte').empty();
			$('#reporte').addClass('hidden');
		}
	});

	// Abrir/Cerrar el arbol
	$('#reporte tbody').on('click', 'span', function () {
		$this = $(this);
		tr = $this.closest('tr');
		//console.log(tr);
		if (tr.attr('data-open') == 'false') {
			tr.attr('data-open', true);
			$this.removeClass('ti-angle-right').addClass('ti-angle-down');
			switch (tr.attr('data-tipo')) {
				case 'division':
					obtenerData('zona', tr);
					break;
				case 'zona':
					obtenerData('tienda', tr);
					break;
				case 'tienda':
					obtenerData('producto', tr);
					break;
				default:
					break;
			}
		} else {
			tr.attr('data-open', false);
			$this.addClass('ti-angle-right').removeClass('ti-angle-down');
			removeItem(tr);
		}
	});

	// Comprobamos si existen tiendas sin zona asignada o tiendas que tengan más de una zona asignada
	comprobarZonas();
	$("#reporte").freezeHeader();
});

// Funcion para comprobar la relación zona-tienda
function comprobarZonas() {
	$.ajax({
		url: 'ComprobarZonas',
		type: 'POST',
		async: true,
		cache: false,
		dataType: 'json',
		beforeSend: function () {
			swal({
				html: '<h3>Cargando datos, espera...</h3>',
				showConfirmButton: false
			});
		},
		success: function (data) {
			if (data.bandera == false) {
				swal({
					title: "Atiende!",
					html: data.msj,
					buttonsStyling: true,
					confirmButtonClass: "btn btn-warning btn-fill"
				});
				return false
			}
			if (data.tdz.length > 0 || data.tsz.length > 0 ) {
				$('#ttz').text(data.tdz.length);
				$.each(data.tdz, function (index, tienda) {
					$('#tz').append(tienda.tienda + ' ' + tienda.duplicados + ', ');
				});
				$('#ttn').text(data.tsz.length);
				$.each(data.tsz, function (index, tienda) {
					$('#tn').append(tienda.tienda + ' ' + tienda.nombre + ', ');
				});
				$('#zonas').removeClass('hidden');
				swal.close();
			}
		}
	});
}

// Funcion para renderizar el resumen de ventas
function renderResumen() {
	$('#bodyResumen').empty();
	$('#reporte').addClass('hidden');
	$.ajax({
		url: 'ObtenerResumen',
		data: { fecha: $('#fecha').val() },
		type: 'POST',
		async: true,
		cache: false,
		dataType: 'json',
		beforeSend: function () {
			swal({
				html: '<h3>Cargando datos, espera...</h3>',
				showConfirmButton: false
			});
		},
		success: function (data) {
			$('#bodyReporte').empty();
			if (data.bandera == false) {
				swal({
					title: "Atiende!",
					html: data.msj,
					buttonsStyling: true,
					confirmButtonClass: "btn btn-warning btn-fill"
				});
			} else {
				$('#bodyResumen').html(
					"<tr data-open='false' style='background-color: " + colores[0] + "'>" +
					"<td><span class='ti-angle-right' style='cursor: pointer'></span></td>" +
					"<td class='text-right'>$ " + formato_numero(data.data.valorInventarioPesos, 2, '.', ',') + "</td>" +
					"<td class='text-right'>$ " + formato_numero(data.data.ventaPromedio, 2, '.', ',') + "</td>" +
					"<td class='text-right'>" + formato_numero(data.data.diasDeInventario, 2, '.', ',') + "</td>" +
					"</tr>"
				);
				//$('.table-fixed-header').fixedHeader();
				swal.close();
			}
		}
	});
}

// Funcion para obtener el data para expandir el arbol
function obtenerData(tipo, tr) {
	var fecha = $('#fecha').val();
	division = tr == false ? false : tr.attr('data-division');
	zona = tr == false ? false : tr.attr('data-zona');
	tienda = tr == false ? false : tr.attr('data-tienda');
	switch (tipo) {
		case 'division':
			url = 'ObtenerDivisiones';
			break;
		case 'zona':
			url = 'ObtenerZonas';
			break;
		case 'tienda':
			url = 'ObtenerTiendas';
			break;
		case 'producto':
			url = 'ObtenerProductos';
			break;
		default:
			break;
	}
	$.ajax({
		url: url,
		data: { fecha: fecha, division: division, zona: zona, tienda: tienda },
		type: 'POST',
		async: true,
		cache: false,
		dataType: 'json',
		beforeSend: function () {
			swal({
				html: '<h3>Cargando datos, espera...</h3>',
				showConfirmButton: false
			});
		},
		success: function (data) {
			if (data.bandera == false) {
				swal({
					title: "Atiende!",
					html: data.msj,
					buttonsStyling: true,
					confirmButtonClass: "btn btn-warning btn-fill"
				});
				return false
			}
			renderRow(tr, data.data);
			swal.close();
			if (tipo == 'division') renderFooter(data.data);
		}
	});
}

// Funcion para renderizar el footer del reporte
function renderFooter(data) {
	$('#footReporte').empty();
	tExistenciaPiezas = tExistenciaPesos = tventaPromedioDiaPiezas = tventaPromedioDiaPesos = tdiasDeInventario = 0;
	$.each(data, function (index, row) {
		tExistenciaPiezas = tExistenciaPiezas + parseFloat(row.existenciaPiezas);
		tExistenciaPesos = tExistenciaPesos + parseFloat(row.existenciaPesos);
		tventaPromedioDiaPiezas = tventaPromedioDiaPiezas + parseFloat(row.ventaPromedioDiaPiezas);
		tventaPromedioDiaPesos = tventaPromedioDiaPesos + parseFloat(row.ventaPromedioDiaPesos);
	});
	tdiasDeInventario = tExistenciaPesos / tventaPromedioDiaPesos;
	$('#footReporte').html("<tr>" +
		"<th></th>" +
		"<th></th>" +
		"<th></th>" +
		"<th></th>" +
		"<th></th>" +
		"<th></th>" +
		"<th class='text-right'>" + formato_numero(tExistenciaPiezas, 2, '.', ',') + "</th>" +
		"<th class='text-right'>$ " + formato_numero(tExistenciaPesos, 2, '.', ',') + "</th>" +
		"<th class='text-right'>" + formato_numero(tventaPromedioDiaPiezas, 2, '.', ',') + "</th>" +
		"<th class='text-right'>$ " + formato_numero(tventaPromedioDiaPesos, 2, '.', ',') + "</th>" +
		"<th class='text-right'>" + formato_numero(tdiasDeInventario, 2, '.', ',') + "</th>" +
		"</tr>");
}

// Funcion para renderizar una fila en el reporte
function renderRow(parent, data) {
	nextIndex = parent == false ?  0 :nextIndex = parent.index() + 1;
	$body = document.getElementById('bodyReporte');
	$.each(data, function (index, row) {
		var newRow = $body.insertRow(nextIndex);

		newRow.setAttribute('data-tipo', row['tipo']);
		newRow.setAttribute('data-open', row['_open']);
		newRow.setAttribute('data-division', row['division']);
		newRow.setAttribute('data-zona', row['zona']);
		newRow.setAttribute('data-tienda', row['tienda']);

		var cellDivision = newRow.insertCell(0);
		var cellZona = newRow.insertCell(1);
		var cellTienda = newRow.insertCell(2);
		var cellProducto = newRow.insertCell(3);
		var cellDescripcion = newRow.insertCell(4);
		var cellPrecioVenta = newRow.insertCell(5);
		var cellExistenciaPiezas = newRow.insertCell(6);
		var cellExistenciaPesos = newRow.insertCell(7);
		var cellVentaPromedioDiaPiezas = newRow.insertCell(8);
		var cellVentaPromedioDiaPesos = newRow.insertCell(9);
		var cellDiasDeInv = newRow.insertCell(10);

		strSpan = "<span class='ti-angle-right' style='cursor: pointer'></span> ";
		if (row.tipo == 'division') newRow.style.backgroundColor = colores[1];
		if (row.tipo == 'zona') newRow.style.backgroundColor = colores[2];
		if (row.tipo == 'tienda') newRow.style.backgroundColor = colores[3];
		if (row.tipo == 'producto') newRow.style.backgroundColor = colores[4];

		cellDivision.innerHTML = row.tipo == 'division' ? strSpan + row.division : '';
		cellZona.innerHTML = row.tipo == 'zona' ? strSpan + row.zona : '';
		cellTienda.innerHTML = row.tipo == 'tienda' ? strSpan + row.tiendaDes : '';
		cellProducto.innerHTML = row.producto;
		cellDescripcion.innerHTML = row.descripcion;
		cellPrecioVenta.innerHTML = row.tipo == 'producto' ? formato_numero(row['PrecioVenta'], 2, '.', ',') : '';
		cellExistenciaPiezas.innerHTML = formato_numero(row['existenciaPiezas'], 2, '.', ',');
		cellExistenciaPesos.innerHTML = '$ ' + formato_numero(row['existenciaPesos'], 2, '.', ',');
		cellVentaPromedioDiaPiezas.innerHTML = formato_numero(row['ventaPromedioDiaPiezas'], 2, '.', ',');
		cellVentaPromedioDiaPesos.innerHTML = '$ ' + formato_numero(row['ventaPromedioDiaPesos'], 2, '.', ',');
		cellDiasDeInv.innerHTML = formato_numero(row['diasDeInventario'], 2, '.', ',');
		cellPrecioVenta.className = 'text-right';
		cellExistenciaPiezas.className = 'text-right';
		cellExistenciaPesos.className = 'text-right';
		cellVentaPromedioDiaPiezas.className = 'text-right';
		cellVentaPromedioDiaPesos.className = 'text-right';
		cellDiasDeInv.className = 'text-right';

		nextIndex = nextIndex + 1;
	});
}

// Funcion para remover los child rows de un tr
function removeItem(parent) {
	var parentIndex = parent.index();
	var division = parent.attr('data-division');
	var zona = parent.attr('data-zona');
	var tienda = parent.attr('data-tienda');
	if (parent.attr('data-tipo') == 'division') {
		$('#reporte tbody tr').each(function (index) {
			if ($(this).index() != parentIndex && $(this).attr('data-division') == division) {
				$(this).remove();
			}
		});
	} else if (parent.attr('data-tipo') == 'zona') {
		$('#reporte tbody tr').each(function (index) {
			if ($(this).index() != parentIndex && $(this).attr('data-division') == division && $(this).attr('data-zona') == zona) {
				$(this).remove();
			}
		});
	} else if (parent.attr('data-tipo') == 'tienda') {
		$('#reporte tbody tr').each(function (index) {
			if ($(this).index() != parentIndex && $(this).attr('data-division') == division && $(this).attr('data-zona') == zona && $(this).attr('data-tienda') == tienda) {
				$(this).remove();
			}
		});
	}
	//$('.table-fixed-header').fixedHeader();
}

// Funcion para formatear cifras numéricas
function formato_numero(numero, decimales, separador_decimal, separador_miles) {
	numero = parseFloat(numero);
	if (isNaN(numero)) return '';
	if (decimales !== undefined) numero = numero.toFixed(decimales);
	numero = numero.toString().replace('.', separador_decimal !== undefined ? separador_decimal : ',');
	if (separador_miles) {
		var miles = new RegExp("(-?[0-9]+)([0-9]{3})");
		while (miles.test(numero)) {
			numero = numero.replace(miles, '$1' + separador_miles + '$2');
		}
	}
	return numero;
}