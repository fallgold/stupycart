<?php

namespace Libs\Opencart;

class Affiliate extends \Phalcon\DI\Injectable {
	private $affiliate_id;
	private $firstname;
	private $lastname;
	private $email;
	private $telephone;
	private $fax;
	private $code;
	
  	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');
				
		if ($this->session->has('affiliate_id')) { 
			$affiliate_query = $this->ocdb->db_query("SELECT * FROM " . DB_PREFIX . "affiliate WHERE affiliate_id = '" . (int)$this->session->get('affiliate_id') . "' AND status = '1'");
			
			if ($affiliate_query->num_rows) {
				$this->affiliate_id = $affiliate_query->row['affiliate_id'];
				$this->firstname = $affiliate_query->row['firstname'];
				$this->lastname = $affiliate_query->row['lastname'];
				$this->email = $affiliate_query->row['email'];
				$this->telephone = $affiliate_query->row['telephone'];
				$this->fax = $affiliate_query->row['fax'];
				$this->code = $affiliate_query->row['code'];
							
      			$this->ocdb->db_query("UPDATE " . DB_PREFIX . "affiliate SET ip = '" . $this->ocdb->db_escape($this->request->getServer('REMOTE_ADDR')) . "' WHERE affiliate_id = '" . (int)$this->session->get('affiliate_id') . "'");
			} else {
				$this->logout();
			}
  		}
	}
		
  	public function login($email, $password) {
		$affiliate_query = $this->ocdb->db_query("SELECT * FROM " . DB_PREFIX . "affiliate WHERE LOWER(email) = '" . $this->ocdb->db_escape(utf8_strtolower($email)) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->ocdb->db_escape($password) . "'))))) OR password = '" . $this->ocdb->db_escape(md5($password)) . "') AND status = '1' AND approved = '1'");
		
		if ($affiliate_query->num_rows) {
			$this->session->set('affiliate_id', $affiliate_query->row['affiliate_id']);	
		    
			$this->affiliate_id = $affiliate_query->row['affiliate_id'];
			$this->firstname = $affiliate_query->row['firstname'];
			$this->lastname = $affiliate_query->row['lastname'];
			$this->email = $affiliate_query->row['email'];
			$this->telephone = $affiliate_query->row['telephone'];
			$this->fax = $affiliate_query->row['fax'];
      		$this->code = $affiliate_query->row['code'];
	  
	  		return true;
    	} else {
      		return false;
    	}
  	}
  
  	public function logout() {
		$this->session->remove('affiliate_id');

		$this->affiliate_id = '';
		$this->firstname = '';
		$this->lastname = '';
		$this->email = '';
		$this->telephone = '';
		$this->fax = '';
  	}
  
  	public function isLogged() {
    	return $this->affiliate_id;
  	}

  	public function getId() {
    	return $this->affiliate_id;
  	}
      
  	public function getFirstName() {
		return $this->firstname;
  	}
  
  	public function getLastName() {
		return $this->lastname;
  	}
  
  	public function getEmail() {
		return $this->email;
  	}
  
  	public function getTelephone() {
		return $this->telephone;
  	}
  
  	public function getFax() {
		return $this->fax;
  	}
	
  	public function getCode() {
		return $this->code;
  	}	
}
?>