<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ImportModelDudi extends CI_Model {

	public function insert($data){
		$insert = $this->db->insert_batch('dudi', $data);
		if($insert){
			return true;
		}
	}
	public function getData(){
		$this->db->select('*');
		return $this->db->get('dudi')->result_array();
	}

}