<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Base_Model extends CI_Model {

	/* table, la tabla del modelo
	* este valor se puede cambiar en el constructor del modelo
	* @var string
	* @access protected
	*/
	protected $table = '';

	/* primary_key, la primary key de la tabla del modelo
	* este valor se puede cambiar en el constructor del modelo de la tabla
	* @var string
	* @access protected
	*/
	protected $primary_key = 'id';

	/* return_type, tipo de datos que retornara el método, 'array' (Default) / 'object'
	* este valor se puede cambiar en el metodo en específico en el constructor del modelo
	* @var mixed
	* @access protected
	*/
	protected $return_type = 'array';

	# Constructor de la clase
	public function  __construct() {
		parent::__construct();
	}

	/* get, Metodo para obtener la informacion de un registro en particular, si se desea un listado de registros hay que usar el metodo 'filter'
	* $where: 'id = 1' o array('id' => 1, 'other_id' => 2)
	* @param array $where, Puede ser un arreglo o una cadena
	* @param array $fields, Puede ser un arreglo o una cadena
	* @access public
	* @return void
	*/
	public function get($where = '', $fields = '') {
		$results = $this->filter($where, $fields);
		if (count($results) == 1) {
			return $results[0];
		}
		return $results;
	}

	/* save, Método para guardar información en la base de datos
	* Para actualizar hay que proporcionar el elemento {$this->primary_key} en el arreglo que se envía al método
	* El método retorna el id insertado o actualizado
	* @param array $data
	* @access public
	* @return mixed
	*/
	public function save($data) {
		if (isset($data[$this->primary_key]) AND $data[$this->primary_key] != 0) {
			$this->db->where($this->primary_key, $data[$this->primary_key]);
			$this->db->update($this->table, $data);
		} else {
			$this->db->insert($this->table, $data);
			$data[$this->primary_key] = $this->db->insert_id();
		}
		return ($this->db->affected_rows() > 0) ? $data[$this->primary_key] : FALSE;
	}

	/* filter, Método para retornar información de la base de datos, aunque el método solo retorno un registro
	* @param array $where, puede ser un arreglo o una cadena
	* @param array $fields, puede ser un arreglo o una cadena
	* @access public
	* @return array
	*/
	public function filter($where = '', $fields = '') {
		$this->db->from($this->table);
		if (is_array($where)) {
			foreach ($where as $f => $w){
				$this->db->where($f, $w);
			}
		}
		elseif (strlen($where) > 0) {
			$this->db->where($where);
		}
		if (is_array($fields)) {
			foreach ($fields as $field) {
				$this->db->select($field);
			}
		}
		elseif (strlen($fields) > 0) {
			$this->db->select($fields);
		}
		$query = $this->db->get();
		if ($this->return_type == 'array') {
			$results = $query->result_array();
		}
		else {
			$results = $query->result();
		}
		return $results;
	}

	/* delete, Método para borrar un registro de la base de datos
	* @param integer $id
	* @access public
	* @return booelan
	*/
	public function delete($id) {
		if (!is_numeric($id)) {
			return FALSE;
		}
		else {
			$this->db->where($this->primary_key, $id);
			$this->db->delete($this->table);
			return TRUE;
		}
	}

	/* count_results, Método para contar el numero de registros de una tabla
	* @param array $where
	* @access public
	* @return void
	*/
	public function count_results($where = '') {
		if (is_array($where)) {
			foreach ($where as $f => $w){
				$this->db->where($f, $w);
			}
		}
		elseif (strlen($where) > 0) {
			$this->db->where($where);
		}
		return $this->db->count_all_results($this->table);
	}

	/* count, Retorna el total de registros de la tabla del modelo
	* @access public
	* @return integer
	*/
	public function count() {
		return $this->db->count_all_results($this->table);
	}

}