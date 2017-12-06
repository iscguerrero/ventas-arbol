<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reporte extends Base_Controller {
	public function Index() {
		$this->load->view('Index');
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
}
