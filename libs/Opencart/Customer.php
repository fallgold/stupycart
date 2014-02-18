<?php

namespace Libs\Opencart;

class Customer extends \Phalcon\DI\Injectable {
	private $customer_id;
	private $firstname;
	private $lastname;
	private $email;
	private $telephone;
	private $fax;
	private $newsletter;
	private $customer_group_id;
	private $address_id;
	
  	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');
				
		if ($this->session->has('customer_id')) { 
			$customer_query = $this->ocdb->db_query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$this->session->get('customer_id') . "' AND status = '1'");
			
			if ($customer_query->num_rows) {
				$this->customer_id = $customer_query->row['customer_id'];
				$this->firstname = $customer_query->row['firstname'];
				$this->lastname = $customer_query->row['lastname'];
				$this->email = $customer_query->row['email'];
				$this->telephone = $customer_query->row['telephone'];
				$this->fax = $customer_query->row['fax'];
				$this->newsletter = $customer_query->row['newsletter'];
				$this->customer_group_id = $customer_query->row['customer_group_id'];
				$this->address_id = $customer_query->row['address_id'];
							
      			$this->ocdb->db_query("UPDATE " . DB_PREFIX . "customer SET cart = '" . $this->ocdb->db_escape($this->session->has('cart') ? serialize($this->session->get('cart')) : '') . "', wishlist = '" . $this->ocdb->db_escape($this->session->has('wishlist') ? serialize($this->session->get('wishlist')) : '') . "', ip = '" . $this->ocdb->db_escape($this->request->getServer('REMOTE_ADDR')) . "' WHERE customer_id = '" . (int)$this->customer_id . "'");
			
				$query = $this->ocdb->db_query("SELECT * FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$this->session->get('customer_id') . "' AND ip = '" . $this->ocdb->db_escape($this->request->getServer('REMOTE_ADDR')) . "'");
				
				if (!$query->num_rows) {
					$this->ocdb->db_query("INSERT INTO " . DB_PREFIX . "customer_ip SET customer_id = '" . (int)$this->session->get('customer_id') . "', ip = '" . $this->ocdb->db_escape($this->request->getServer('REMOTE_ADDR')) . "', date_added = NOW()");
				}
			} else {
				$this->logout();
			}
  		}
	}
		
  	public function login($email, $password, $override = false) {
		if ($override) {
			$customer_query = $this->ocdb->db_query("SELECT * FROM " . DB_PREFIX . "customer where LOWER(email) = '" . $this->ocdb->db_escape(utf8_strtolower($email)) . "' AND status = '1'");
		} else {
			$customer_query = $this->ocdb->db_query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->ocdb->db_escape(utf8_strtolower($email)) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->ocdb->db_escape($password) . "'))))) OR password = '" . $this->ocdb->db_escape(md5($password)) . "') AND status = '1' AND approved = '1'");
		}
		
		if ($customer_query->num_rows) {
			$this->session->set('customer_id', $customer_query->row['customer_id']);	
		    
			if ($customer_query->row['cart'] && is_string($customer_query->row['cart'])) {
				$cart = unserialize($customer_query->row['cart']);
				
				foreach ($cart as $key => $value) {
					if (!array_key_exists($key, $this->session->get('cart'))) {
						{ $_tmp = $this->session->get('cart'); $_tmp[$key] = $value; $this->session->set('cart', $_tmp); }
					} else {
						{ $_tmp = $this->session->get('cart'); $_tmp[$key] += $value; $this->session->set('cart', $_tmp); }
					}
				}			
			}

			if ($customer_query->row['wishlist'] && is_string($customer_query->row['wishlist'])) {
				if (!$this->session->has('wishlist')) {
					$this->session->set('wishlist', array());
				}
								
				$wishlist = unserialize($customer_query->row['wishlist']);
			
				foreach ($wishlist as $product_id) {
					if (!in_array($product_id, $this->session->get('wishlist'))) {
						{ $_tmp = $this->session->get('wishlist'); $_tmp[] = $product_id; $this->session->set('wishlist', $_tmp); }
					}
				}			
			}
									
			$this->customer_id = $customer_query->row['customer_id'];
			$this->firstname = $customer_query->row['firstname'];
			$this->lastname = $customer_query->row['lastname'];
			$this->email = $customer_query->row['email'];
			$this->telephone = $customer_query->row['telephone'];
			$this->fax = $customer_query->row['fax'];
			$this->newsletter = $customer_query->row['newsletter'];
			$this->customer_group_id = $customer_query->row['customer_group_id'];
			$this->address_id = $customer_query->row['address_id'];
          	
			$this->ocdb->db_query("UPDATE " . DB_PREFIX . "customer SET ip = '" . $this->ocdb->db_escape($this->request->getServer('REMOTE_ADDR')) . "' WHERE customer_id = '" . (int)$this->customer_id . "'");
			
	  		return true;
    	} else {
      		return false;
    	}
  	}
  	
	public function logout() {
		$this->ocdb->db_query("UPDATE " . DB_PREFIX . "customer SET cart = '" . $this->ocdb->db_escape($this->session->has('cart') ? serialize($this->session->get('cart')) : '') . "', wishlist = '" . $this->ocdb->db_escape($this->session->has('wishlist') ? serialize($this->session->get('wishlist')) : '') . "' WHERE customer_id = '" . (int)$this->customer_id . "'");
		
		$this->session->remove('customer_id');

		$this->customer_id = '';
		$this->firstname = '';
		$this->lastname = '';
		$this->email = '';
		$this->telephone = '';
		$this->fax = '';
		$this->newsletter = '';
		$this->customer_group_id = '';
		$this->address_id = '';
  	}
  
  	public function isLogged() {
    	return $this->customer_id;
  	}

  	public function getId() {
    	return $this->customer_id;
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
	
  	public function getNewsletter() {
		return $this->newsletter;	
  	}

  	public function getCustomerGroupId() {
		return $this->customer_group_id;	
  	}
	
  	public function getAddressId() {
		return $this->address_id;	
  	}
	
  	public function getBalance() {
		$query = $this->ocdb->db_query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$this->customer_id . "'");
	
		return $query->row['total'];
  	}	
		
  	public function getRewardPoints() {
		$query = $this->ocdb->db_query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$this->customer_id . "'");
	
		return $query->row['total'];	
  	}	
}
?>