<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reporte extends Base_Controller {
	public function Index() {
		$this->load->view('Index');
	}
	# Método para obtener el resumen de ventas al dia
	public function ObtenerResumen() {
		if(!$this->input->is_ajax_request()) show_404();
		# Se valida el contenido de la petición
		$this->form_validation->set_rules('fecha', 'Fecha', 'required', array('required'=>'Es necesario proporcionar la fecha de reporte'));
		if ($this->form_validation->run() == false) exit(json_encode(array('bandera'=>false, 'msj'=>validation_errors())));
		# Se obtienen las divisiones
		$fecha = $this->str_to_date($this->input->post('fecha'));
		$this->load->model('item');
		exit(json_encode(array('bandera'=>true, 'data'=>$this->item->venta($fecha))));
	}
	# Método para obtener las divisiones de productos de la tienda
	public function ObtenerDivisiones() {
		if(!$this->input->is_ajax_request()) show_404();
		# Se valida el contenido de la petición
		$this->form_validation->set_rules('fecha', 'Fecha', 'required', array('required'=>'Es necesario proporcionar la fecha de reporte'));
		if ($this->form_validation->run() == false) exit(json_encode(array('bandera'=>false, 'msj'=>validation_errors())));
		# Se obtienen las divisiones
		$fecha = $this->str_to_date($this->input->post('fecha'));
		$this->load->model('item');
		exit(json_encode(array('bandera'=>true, 'data'=>$this->item->divisiones($fecha))));
	}
	# Método para obtener las zonas de una division
	public function ObtenerZonas() {
		if(!$this->input->is_ajax_request()) show_404();
		# Se valida el contenido de la petición
		$this->form_validation->set_rules('fecha', 'Fecha', 'required', array('required'=>'Es necesario proporcionar la fecha de reporte'));
		if ($this->form_validation->run() == false) exit(json_encode(array('bandera'=>false, 'msj'=>validation_errors())));
		# Se obtienen las divisiones
		$fecha = $this->str_to_date($this->input->post('fecha'));
		$division = $this->input->post('division');
		$this->load->model('item');
		exit(json_encode(array('bandera'=>true, 'data'=>$this->item->zonas($fecha, $division))));
	}
	# Método para obtener las tiendas de una zona
	public function ObtenerTiendas() {
		if(!$this->input->is_ajax_request()) show_404();
		# Se valida el contenido de la petición
		$this->form_validation->set_rules('fecha', 'Fecha', 'required', array('required'=>'Es necesario proporcionar la fecha de reporte'));
		if ($this->form_validation->run() == false) exit(json_encode(array('bandera'=>false, 'msj'=>validation_errors())));
		# Se obtienen las divisiones
		$fecha = $this->str_to_date($this->input->post('fecha'));
		$division = $this->input->post('division');
		$zona = $this->input->post('zona');
		$this->load->model('item');
		$tiendas = $this->item->tiendas($fecha, $division, $zona);
		foreach ($tiendas as $tienda) {
			$descripcion = $this->item->tienda($tienda->tienda);
			$name = isset($descripcion->Name) ? ' - ' . $descripcion->Name : '';
			$tienda->tiendaDes = $tienda->tienda . $name;
		}
		exit(json_encode(array('bandera'=>true, 'data'=>$tiendas)));
	}
	# Método para obtener los productos de una tienda
	public function ObtenerProductos() {
		if(!$this->input->is_ajax_request()) show_404();
		# Se valida el contenido de la petición
		$this->form_validation->set_rules('fecha', 'Fecha', 'required', array('required'=>'Es necesario proporcionar la fecha de reporte'));
		if ($this->form_validation->run() == false) exit(json_encode(array('bandera'=>false, 'msj'=>validation_errors())));
		# Se obtienen las divisiones
		$fecha = $this->str_to_date($this->input->post('fecha'));
		$division = $this->input->post('division');
		$zona = $this->input->post('zona');
		$tienda = $this->input->post('tienda');
		$this->load->model('item');
		$productos = $this->item->productos($fecha, $division, $zona, $tienda);
		foreach ($productos as $producto) {
			$descripcion = $this->item->producto($producto->producto);
			$producto->descripcion = $descripcion->Description;
		}
		exit(json_encode(array('bandera'=>true, 'data'=>$productos)));
	}
}
