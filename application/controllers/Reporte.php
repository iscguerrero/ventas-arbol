<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reporte extends Base_Controller {
	public function Index() {
		$this->load->view('Index');
	}
}
