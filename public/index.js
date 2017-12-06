var nextIndex = 0;
$(document).ready(function () {
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
	$('#formReporte').submit(function (e) {
		e.preventDefault();
		$('#reporte').empty();
		obtenerData('division', false);
	});
	// Abrir/Cerrar el arbol
	$('#tabla_reporte tbody').on('click', 'span', function () {
		var tr = $(this).closest('tr');
		switch (tr.attr('data-tipo')) {
			case 'division':
				obtenerData('zona', tr);
				break;
		
			default:
				break;
		}
		$(this).hasClass('ti-angle-right') ? $(this).removeClass('ti-angle-right').addClass('ti-angle-down') : $(this).addClass('ti-angle-right').removeClass('ti-angle-down');
	});
});

// Funcion para obtener las divisiones
function obtenerData(tipo, parent) {
	var fecha = $('#fecha').val();
	switch (tipo) {
		case 'division':
			url = 'ObtenerDivisiones'
			break;
		case 'zona':
			url = 'ObtenerZona'
			break;
		default:
			break;
	}
	
	$.ajax({
		url: url,
		data: { fecha: fecha },
		type: 'POST',
		async: true,
		cache: false,
		dataType: 'json',
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
			$.each(data.data, function (key, row) {
				renderRow(parent, row);
			});
		}
	});
}

// Funcion para renderizar una fila en el reporte
function renderRow(parent, row) {
	if (parent == false) {
		nextIndex = 0;
	} else {
		$parent = document.getElementById(parent);
		nextIndex = $parent.rowIndex;
	}
	$body = document.getElementById('reporte');
	var newRow = $body.insertRow(nextIndex);

	newRow.setAttribute('id', row['id'])
	newRow.setAttribute('data-tipo', row['tipo'])
	newRow.setAttribute('data-open', row['open'])
	newRow.setAttribute('data-division', row['division'])
	newRow.setAttribute('data-zona', row['zona'])
	newRow.setAttribute('data-tienda', row['tienda'])

	var cellDivision = newRow.insertCell(0);
	var cellZona = newRow.insertCell(1);
	var cellTienda = newRow.insertCell(2);
	var cellProducto = newRow.insertCell(3);
	var cellDescripcion = newRow.insertCell(4);
	var cellExistencia = newRow.insertCell(5);
	var cellValor = newRow.insertCell(6);
	var cellVentas = newRow.insertCell(7);
	var cellDiasDeInv = newRow.insertCell(8);

	strSpan = "<span class='ti-angle-right' style='cursor: pointer'></span> ";
	cellDivision.innerHTML = row.tipo == 'division' ? strSpan + row.division : row.division;
	cellZona.innerHTML = row.tipo == 'zona' ? strSpan + row.zona : row.zona;
	cellTienda.innerHTML = row.tipo == 'tienda' ? strSpan + row.tienda : row.tienda;
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