<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Queries extends CI_Model {

	public function query_users($n_PhoneNumber=false){

		$user = $this->db->query("SELECT pu.* 
								  FROM pro_users pu
								  WHERE 1 > 0".
								  ($n_PhoneNumber!=false ? " AND pu.n_PhoneNumber = ".$n_PhoneNumber : ""));

		if ($user->num_rows() != 0) {
			return $user->result_array();
		}else{
			return false;
		}
	}	

	public function query_est($n_IdEstablishment=false){
		$establishment = $this->db->query("SELECT pe.* 
										   FROM pro_establishments pe
										   WHERE 1 > 0".
										   ($n_IdEstablishment!=false ? " AND pe.n_IdEstablishment = ".$n_IdEstablishment : ""));
		if ($establishment->num_rows() != 0) {
			return $establishment->result_array();
		}else{
			return false;
		}									   
	}

	public function query_service($dt_CheckIn=false){
		$service = $this->db->query("SELECT rs.*
									 FROM rel_services rs
									 WHERE 1 > 0".
									 ($dt_CheckIn!=false ? " AND dt_CheckIn = '".$dt_CheckIn."'" : ""));
		if ($service->num_rows() != 0) {
			return $service->result_array();
		}else{
			return false;
		}
	}
}