<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reporte extends Base_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('item');
	}

	public function Index($tienda = null) {
		$data = $tienda == null ? array('blocked' => false) : array('blocked' => true);
		$this->load->view('Index', $data);
	}

	# Método para obtener el resumen de ventas al dia
	public function ObtenerResumen() {
		if(!$this->input->is_ajax_request()) show_404();
		$fecha = $this->str_to_date($this->input->post('fecha'));
		$divisiones = $this->input->post('divisiones[]');
		$regiones = $this->input->post('regiones[]');
		$zonas = $this->input->post('zonas[]');
		$tiendas = $this->input->post('tiendas[]');
		$productos = $this->input->post('productos[]');
		$proveedores = $this->input->post('proveedores[]');
		exit(json_encode(array('bandera'=>true, 'data'=>$this->item->venta($fecha, $divisiones, $regiones, $zonas, $tiendas, $productos, $proveedores))));
	}

	# Método para obtener las divisiones de productos de la tienda
	public function ObtenerDivisiones() {
		if(!$this->input->is_ajax_request()) show_404();
		$str = $this->input->post('str');
		$fecha = ''; $divisiones = $regiones = $zonas = $tiendas = $productos = $proveedores = array();
		foreach ($str as $key => $item) {
			if($item['name'] == 'fecha') $fecha = $this->str_to_date($item['value']);
			if($item['name'] == 'divisiones[]') array_push($divisiones, $item['value']);
			if($item['name'] == 'regiones[]') array_push($regiones, $item['value']);
			if($item['name'] == 'zonas[]') array_push($zonas, $item['value']);
			if($item['name'] == 'tiendas[]') array_push($tiendas, $item['value']);
			if($item['name'] == 'productos[]') array_push($productos, $item['value']);
			if($item['name'] == 'proveedores[]') array_push($proveedores, $item['value']);
		}
		$items = $this->item->divisiones($fecha, $divisiones, $regiones, $zonas, $tiendas, $productos, $proveedores);

		exit(json_encode(array('bandera'=>true, 'data'=>$items)));
	}

	# Método para obtener las regiones de productos de la tienda
	public function ObtenerRegiones() {
		if(!$this->input->is_ajax_request()) show_404();
		$str = $this->input->post('str');
		$division = $this->input->post('division');
		$fecha = ''; $divisiones = $regiones = $zonas = $tiendas = $productos = $proveedores = array();
		foreach ($str as $key => $item) {
			if($item['name'] == 'fecha') $fecha = $this->str_to_date($item['value']);
			if($item['name'] == 'regiones[]') array_push($regiones, $item['value']);
			if($item['name'] == 'zonas[]') array_push($zonas, $item['value']);
			if($item['name'] == 'tiendas[]') array_push($tiendas, $item['value']);
			if($item['name'] == 'productos[]') array_push($productos, $item['value']);
			if($item['name'] == 'proveedores[]') array_push($proveedores, $item['value']);
		}
		$items = $this->item->regiones($fecha, $division, $regiones, $zonas, $tiendas, $productos, $proveedores);

		exit(json_encode(array('bandera'=>true, 'data'=>$items)));
	}

	# Método para obtener las zonas de una division
	public function ObtenerZonas() {
		if(!$this->input->is_ajax_request()) show_404();
		$str = $this->input->post('str');
		$division = $this->input->post('division');
		$region = $this->input->post('region');
		$fecha = ''; $regiones = $zonas = $tiendas = $productos = $proveedores = array();
		foreach ($str as $key => $item) {
			if($item['name'] == 'fecha') $fecha = $this->str_to_date($item['value']);
			if($item['name'] == 'zonas[]') array_push($zonas, $item['value']);
			if($item['name'] == 'tiendas[]') array_push($tiendas, $item['value']);
			if($item['name'] == 'productos[]') array_push($productos, $item['value']);
			if($item['name'] == 'proveedores[]') array_push($proveedores, $item['value']);
		}
		$items = $this->item->zonas($fecha, $division, $region, $zonas, $tiendas, $productos, $proveedores);
		exit(json_encode(array('bandera'=>true, 'data'=>$items)));
	}

	# Método para obtener las tiendas de una zona
	public function ObtenerTiendas() {
		if(!$this->input->is_ajax_request()) show_404();
		$str = $this->input->post('str');
		$division = $this->input->post('division');
		$region = $this->input->post('region');
		$zona = $this->input->post('zona');
		$fecha = ''; $divisiones = $regiones = $zonas = $tiendas = $productos = $proveedores = array();
		foreach ($str as $key => $item) {
			if($item['name'] == 'fecha') $fecha = $this->str_to_date($item['value']);
			if($item['name'] == 'tiendas[]') array_push($tiendas, $item['value']);
			if($item['name'] == 'productos[]') array_push($productos, $item['value']);
			if($item['name'] == 'proveedores[]') array_push($proveedores, $item['value']);
		}
		$items = $this->item->tiendas($fecha, $division, $region, $zona, $tiendas, $productos, $proveedores);

		foreach ($items as $tienda) {
			$descripcion = $this->item->tienda($tienda->tienda);
			$name = isset($descripcion->Name) ? ' - ' . $descripcion->Name : '';
			$tienda->tiendaDes = $tienda->tienda . $name;
		}
		exit(json_encode(array('bandera'=>true, 'data'=>$items)));
	}

	# Método para obtener los productos de una tienda
	public function ObtenerProductos() {
		if(!$this->input->is_ajax_request()) show_404();
		$str = $this->input->post('str');
		$division = $this->input->post('division');
		$region = $this->input->post('region');
		$zona = $this->input->post('zona');
		$tienda = $this->input->post('tienda');
		$fecha = ''; $productos = $proveedores = array();
		foreach ($str as $key => $item) {
			if($item['name'] == 'fecha') $fecha = $this->str_to_date($item['value']);
			if($item['name'] == 'productos[]') array_push($productos, $item['value']);
			if($item['name'] == 'proveedores[]') array_push($proveedores, $item['value']);
		}
		$items = $this->item->productos($fecha, $division, $region, $zona, $tienda, $productos, $proveedores);

		foreach ($items as $producto) {
			$descripcion = $this->item->producto($producto->producto);
			$producto->descripcion = $descripcion->Description;
		}
		exit(json_encode(array('bandera'=>true, 'data'=>$items)));
	}

	# Método para obtener los productos por division
	public function ObtenerSoloProductos() {
		if(!$this->input->is_ajax_request()) show_404();
		$str = $this->input->post('str');
		$division = $this->input->post('division');
		$fecha = ''; $divisiones = $regiones = $zonas = $tiendas = $productos = $proveedores = array();
		foreach ($str as $key => $item) {
			if($item['name'] == 'fecha') $fecha = $this->str_to_date($item['value']);
			if($item['name'] == 'divisiones[]') array_push($divisiones, $item['value']);
			if($item['name'] == 'regiones[]') array_push($regiones, $item['value']);
			if($item['name'] == 'zonas[]') array_push($zonas, $item['value']);
			if($item['name'] == 'tiendas[]') array_push($tiendas, $item['value']);
			if($item['name'] == 'productos[]') array_push($productos, $item['value']);
			if($item['name'] == 'proveedores[]') array_push($proveedores, $item['value']);
		}
		$items = $this->item->soloProductos($fecha, $division, $regiones, $zonas, $tiendas, $productos, $proveedores);

		foreach ($items as $producto) {
			$descripcion = $this->item->producto($producto->producto);
			$producto->descripcion = $descripcion->Description;
		}
		exit(json_encode(array('bandera'=>true, 'data'=>$items)));
	}

	# Metodo para comprobar la relacion tienda - zona
	public function ComprobarZonas() {
		if(!$this->input->is_ajax_request()) show_404();
		$tdz = $this->item->tiendasDobleZona();
		$tsz = $this->item->tiendasSinZona();
		$zsr = $this->item->zonasSinRegion();
		exit(json_encode(array('bandera'=>true, 'tdz'=>$tdz, 'tsz'=>$tsz, 'zsr'=>$zsr)));
	}

	# Metodo para obtener una lista de todas las divisiones
	public function CatDivisiones() {
		if(!$this->input->is_ajax_request()) show_404();
		exit(json_encode($this->item->catDivisiones()));
	}

	# Metodo para obtener el catalogo de proveedores
	public function CatProveedores() {
		if(!$this->input->is_ajax_request()) show_404();
		exit(json_encode($this->item->catProveedores()));
	}

	# Metodo para obtener una lista de todas las regiones
	public function CatRegiones() {
		if(!$this->input->is_ajax_request()) show_404();
		exit(json_encode($this->item->catRegiones()));
	}

	# Metodo para obtener una lista de todas las zonas
	public function CatZonas() {
		if(!$this->input->is_ajax_request()) show_404();
		$region = $this->input->post('region');
		exit(json_encode($this->item->catZonas($region)));
	}

	# Metodo para obtener una lista de todas las tiendas
	public function CatTiendas() {
		if(!$this->input->is_ajax_request()) show_404();
		$zonas = $this->input->post('zonas');
		exit(json_encode($this->item->catTiendas($zonas)));
	}

	# Metodo para obtener una lista de todos los productos en venta
	public function CatProductos() {
		if(!$this->input->is_ajax_request()) show_404();
		$term = $this->input->get('term');
		$divisiones = $this->input->get('divisiones');

		exit(json_encode($this->item->catProductos($term, $divisiones)));
	}

}
