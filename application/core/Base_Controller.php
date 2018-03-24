<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base_Controller extends CI_Controller {
	public function __construct(){
		parent::__construct();
			$this->load->database();
			$this->load->helper(array('url','form', 'date'));
			$this->load->library(array('form_validation', 'session', 'encrypt'));
	}

	# Funcion para formatear la fecha a formato Y-m-d
	function str_to_date($string){
		$meses = array("Enero" => "01", "Febrero" => "02", "Marzo" => "03", "Abril" => "04", "Mayo" => "05", "Junio" => "06", "Julio" => "07", "Agosto" => "08", "Septiembre" => "09", "Octubre" => "10", "Noviembre" => "11", "Diciembre" => "12");
		if(!isset($string)) exit(json_encode(array('flag'=>false, 'msj'=>'UNA O VARIAS DE LAS FECHAS NO FUE PROPORCIONADA CORRECTAMENTE')));
		if($string == null) exit(json_encode(array('flag'=>false, 'msj'=>'UNA O VARIAS DE LAS FECHAS ES NULA')));
		if($string == '') exit(json_encode(array('flag'=>false, 'msj'=>'UNA O VARIAS DE LAS FECHAS ES NULA')));
		isset($string)?$fecha=explode("-", $string):exit(array('flag'=>false, 'msj'=>'UNA DE LAS FECHAS NO SE PROPORCIONO CORRECTAMENTE'));
		$date = $fecha[2] . '-' . $meses[$fecha[1]] . '-' . $fecha[0];
		return $date;
	}
}
