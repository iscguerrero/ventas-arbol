<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reporte extends Base_Controller {
	public function Index() {
		$this->load->view('Index');
	}
	# Método para obtener las divisiones de productos de la tienda
	public function ObtenerDivisiones() {
		exit(
			json_encode(
				array(
					array('tipo'=>'division', 'division' => 'abacom', 'zona'=>'', 'tienda'=>'', 'producto'=>'', 'descripcion'=>'', 'existencia'=>0, 'valor'=>0, 'ventas'=>0, 'dias_de_inv'=>0),
					array('tipo'=>'division', 'division' => 'abanocom', 'zona'=>'', 'tienda'=>'', 'producto'=>'', 'descripcion'=>'', 'existencia'=>0, 'valor'=>0, 'ventas'=>0, 'dias_de_inv'=>0),
					array('tipo'=>'division', 'division' => 'vinylic', 'zona'=>'', 'tienda'=>'', 'producto'=>'', 'descripcion'=>'', 'existencia'=>0, 'valor'=>0, 'ventas'=>0, 'dias_de_inv'=>0),
					array('tipo'=>'division', 'division' => 'cerveza', 'zona'=>'', 'tienda'=>'', 'producto'=>'', 'descripcion'=>'', 'existencia'=>0, 'valor'=>0, 'ventas'=>0, 'dias_de_inv'=>0)
				)
			)
				);
	}
}
