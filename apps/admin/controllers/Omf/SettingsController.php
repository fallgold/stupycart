<?php

namespace Stupycart\Admin\Controllers\O;

class MFSettingsController extends \Stupycart\Admin\Controllers\ControllerBase {
	private $error = array();
	
	public function indexAction() {		
		$this->language->load('omf/settings');		
    	
		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->data['heading_title'] 								= $this->language->get('heading_title');		
		
		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->get('token'), 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('omf/settings', 'token=' . $this->session->get('token'), 'SSL'),       		
      		'separator' => ' :: '
   		);
		
		if ($this->session->has('error')) {
			$this->data['error_warning'] = $this->session->get('error');
			$this->session->remove('error');
			
		} else {
			$this->data['error_warning'] = '';
		}
		
		if ($this->session->has('success')) {
			$this->data['success'] = $this->session->get('success');			
			$this->session->remove('success');
			
		} else {
			$this->data['success'] = '';
		}
		
		$this->data['tab_sync'] = $this->language->get('tab_sync');
		$this->data['tab_settings'] = $this->language->get('tab_settings');
		$this->data['tab_help'] = $this->language->get('tab_help');
		
		$this->data['text_help'] = $this->language->get('text_help');
		$this->data['text_blog'] = $this->language->get('text_blog');
		$this->data['text_support'] = $this->language->get('text_support');
		$this->data['text_synchronization'] = $this->language->get('text_synchronization');
		$this->data['text_initial_import'] = $this->language->get('text_initial_import');	
		
		$this->data['entry_template'] = $this->language->get('entry_template');
		
		
		$this->data['button_send'] = $this->language->get('button_send');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_test'] = $this->language->get('button_test');
		$this->data['button_sync'] = $this->language->get('button_sync');
		$this->data['button_synchronize_now'] = $this->language->get('button_synchronize_now');
		$this->data['button_initial_import'] = $this->language->get('button_initial_import');
				
		$this->data['action_connection'] = $this->url->link('omf/settings/save', 'token=' . $this->session->get('token'), 'SSL');		
		$this->data['action_sync'] = $this->url->link('omf/settings/sync', 'token=' . $this->session->get('token'), 'SSL');		
		$this->data['action_save'] = $this->url->link('omf/settings/save', 'token=' . $this->session->get('token'), 'SSL');		
		$this->data['action_test'] = $this->url->link('omf/settings/test', 'token=' . $this->session->get('token'), 'SSL');		
		$this->data['action_flush_products'] = html_entity_decode($this->url->link('omf/settings/flushMobicartProducts', 'token=' . $this->session->get('token'), 'SSL'));		
		$this->data['action_flush_depts'] = html_entity_decode($this->url->link('omf/settings/flushMobicartDepartments', 'token=' . $this->session->get('token'), 'SSL'));		
		$this->data['action_add_depts_and_products'] = html_entity_decode($this->url->link('omf/settings/addDepartmentsAndProducts', 'token=' . $this->session->get('token'), 'SSL'));		
		
		$this->data['error']	= $this->error;

		$this->model_setting_setting = new \Stupycart\Common\Models\Admin\Setting\Setting();
				
		$this->data['mobicart_settings'] = $this->getMobiCartSettings(); 
		
		if ($this->data['mobicart_settings']['store_id']) {
			$this->data['oc_categories'] = $this->getOpenCartCatList();
			$this->data['mc_categories'] = $this->getMobiCartDeptList();		
		}
		
		$this->language->load('catalog/category');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['column_name'] = $this->language->get('column_name');		
		$this->data['column_action'] = $this->language->get('column_action'); 				
		$this->data['column_status'] = $this->language->get('column_status'); 				
				
		$this->view->pick('omf/settings');
		$this->_commonAction();
				
		$this->view->setVars($this->data);
	}	
# *******Save settings through merchant/parner api
	public function saveAction() {
		$result = "";
		if ($this->validate()) {
			if(is_numeric($result = $this->test())) {				
				$this->model_setting_setting = new \Stupycart\Common\Models\Admin\Setting\Setting();
				$this->request->getPostE('mobicart_store_id') = $result;
				$this->model_setting_setting->editSetting('omf', $this->request->getPostE());				
				# *******Save settings through merchant/parner api
				$this->session->set('success', "Connection established. Settings saved.");
				
			} else {				
				$this->session->set('error', $result);
			}				
		} else {
			$this->session->set('error', "Invalid data");			
		}
		
		$this->index();
		
		/* Should return a json message showing success or error of the procedure. */
	}
	
	public function syncAction() {
		/* Synchronize categories by id. */
		/* Should return a json message showing success or error of the procedure. */
	}

	/* public function initialImport() {		
		$mobi = $this->getMobicartConnection();		
		
		$result = "";
		$result .= $this->flushMobicartProducts();	
		$result .= $this->flushMobicartDepartments();			
		$result .= $this->addDepartmentsAndProducts();
		
		$this->session->set('success', $result);
		
		$this->index();
	}  */
	
	public function flushMobicartDepartments() {
		$json = array();
		$mobi = $this->getMobicartConnection();		

		$departments = $mobi->dept->get()->execute();

		$deptsSucceeded = 0;
		$deptsFailed = 0;
		$subDeptsSucceeded = 0;
		$subDeptsFailed = 0;
		
		if($departments) {
			foreach ($departments as $department) {			
				$department_id = $department->departmentId;			
				
				$deleteStats = $this->deleteDepartment($department);
				$deptsSucceeded += $deleteStats['department']['succeeded'];
				$deptsFailed += $deleteStats['department']['failed'];
				$subDeptsSucceeded += $deleteStats['subDepartments']['succeeded'];
				$subDeptsFailed += $deleteStats['subDepartments']['failed'];
				
			}	 
		}
		
		$result = $deptsSucceeded . " departments were deleted successfully." . "<br/>";
		
		if ($deptsFailed > 0) {
			$result .= $deptsFailed . " departments couldn't be deleted." . "<br/>";
		}
		
		$result .= $subDeptsSucceeded . " sub-departments were deleted successfully." . "<br/>";
			
		if ($subDeptsFailed) {
			$result .= $subDeptsFailed . " sub-departments couldn't be deleted." . "<br/>";
		}
		
		$json['output'] = $result;		
		
		$this->response->setContent(json_encode($json));
		return $this->response;				
	}
	
	private function deleteDepartment($department) {
		$mobi = $this->getMobicartConnection();
		
		$deptsSucceeded = 0;
		$deptsFailed = 0;
		$subDeptsSucceeded = 0;
		$subDeptsFailed = 0;
		
		foreach ($department->subDepartments as $subDepartment) {
			$subDeptStats = $this->deleteSubDepartment($subDepartment);
			$subDeptsSucceeded += $subDeptStats['succeeded'];
			$subDeptsFailed += $subDeptStats['failed'];
		}
		
		$delete_result = $mobi->dept->delete(false, $department->departmentId)->execute();
		
		if(!$mobi->last_request->error) {			
			#$this->log->write($delete_result." ID: " . $department->departmentId);
			$deptsSucceeded++;
		} else {
			$this->log->write($delete_result->message . " ID: " . $department->departmentId);
			$this->log->write($mobi->last_request->error);
			$deptsFailed++;
			
		}
		
		return array(
			'department' => array(
				'succeeded' => $deptsSucceeded,
				'failed' => $deptsFailed),
			'subDepartments' => array(
				'succeeded' => $subDeptsSucceeded,
				'failed' => $subDeptsFailed)
			);
	}
	
	private function deleteSubDepartment($department) {
		$mobi = $this->getMobicartConnection();
		
		$deleteDeptsSucceeded = "";
		$deleteDeptsFailed = "";
		
		foreach ($department->subDepartments as $subDepartment) {
			$deptStats = $this->deleteSubDepartment($subDepartment);
			$deleteDeptsSucceeded += $deptStats['succeeded'];
			$deleteDeptsFailed += $deptStats['failed'];
		}
		
		$delete_result = $mobi->dept->delete_sub(false, $department->departmentId)->execute();
		
		if(!$mobi->last_request->error) {
			#$result .= $delete_result;
			#$this->log->write($delete_result . "ID: " . $department->	departmentId);
			#$this->log->write($mobi->last_request->error);
			$deleteDeptsSucceeded++;
			
		} else {
			$this->log->write($mobi->last_request->error);
			$deleteDeptsFailed++;
		}
		
		return array('succeeded' => $deleteDeptsSucceeded, 'failed' => $deleteDeptsFailed);
	}	

	public function flushMobicartProducts() {
		$json = array();
		
		$mobi = $this->getMobicartConnection();		

		$result = $mobi->product->get_all()->execute();		
		
 		if($result) {
			$products = $result->products;				
			$productsSucceeded = 0;
			$productsFailed = 0;
			
			foreach ($products as $product) {			
				$product_id = $product->productId;			
				
				$delete_result = $mobi->product->delete(false, $product_id)->execute();
								
				if ($mobi->last_request->error) {
					$json['error'] = $mobi->last_request->error;
					$productsFailed++;
				} else {
					$productsSucceeded++;
				}
			}

			$result = $productsSucceeded . " products were deleted successfully from MobiCart." . "<br/>";
			
			if ($productsFailed > 0) {
				$result .= $productsFailed + " products failed to be deleted from MobiCart." . "<br/>";
			}		
			
			$json['output'] = $result;
			
		} else {			
			$json['output'] = "No products";
		}		
		
		$this->response->setContent(json_encode($json));
		return $this->response;		
	}
	
	public function addDepartmentsAndProducts() {
		$json = array();
		
		$mobi = $this->getMobicartConnection();		
		
		$result = "";
		
		$oc_categories = $this->getOpenCartCatList();
		
		$oc_parent_id = 0;
		
		$departmentCount = 0;
		$subDeptsSucceeded = 0;
		$subDeptsFailed = 0;
		$productsSucceeded = 0;
		$productsFailed = 0;
		
		foreach ($oc_categories as $category) {
	 		if($category['parent_id'] == 0) { 
				$add_result = $mobi->dept->add(false, false, $category['real_name'], true)->execute();
				$departmentCount++;
				
				$parent_id = $category['category_id'];
				$mc_department_id = $add_result->id;
								
				#$this->log->write("Department added. ID: " . $category['category_id']);
				$productStats = $this->addProducts($category['category_id'], $mc_department_id);
				$productsSucceeded += $productStats['succeeded'];
				$productsFailed += $productStats['failed'];
				
				//This isn't working in the current implementation of the api. You'll get an error if you have products uploaded;
				//if($productStats['succeeded'] == 0) {
					$subDeptStats = $this->addSubDepartments($category['category_id'], $mc_department_id, $oc_categories);
						$subDeptsSucceeded += $subDeptStats['departments']['succeeded'];
						$subDeptsFailed += $subDeptStats['departments']['failed'];
						$productsSucceeded += $subDeptStats['products']['succeeded'];
						$productsFailed += $subDeptStats['products']['failed'];
						
						#$this->log->write(print_r($subDeptStats, 1));
				//} else {
				//	$json['error'][]= "You can't add sub-departments when you have products in the parent department";
				//}
			}
		} 	
		
		$result = $departmentCount . " departments uploaded." . "</br>";
		$result .= $subDeptsSucceeded . " sub-departments uploaded successfully.". "</br>";
		
		if ($subDeptsFailed > 0) {
			$result .= $subDeptsFailed . " sub-departments were not uploaded.". "</br>";
		}
		
		$result .= $productsSucceeded . " products uploaded successfully.". "</br>";
		
		if ($productsFailed > 0) {
			$result .= $productsFailed . " products were not uploaded.". "</br>";
		}
		
		$json['output'] = $result;		
		
		$this->response->setContent(json_encode($json));
		return $this->response;		
	}
	
	private function addSubDepartments($oc_parent_id, $mc_parent_id, $oc_categories) {
		$mobi = $this->getMobicartConnection();		
				
		$productStats = array('succeeded' => 0, 'failed' => 0);
		$deptStats = array('succeeded' => 0, 'failed' => 0);
		
		foreach($oc_categories as $category) {
			if($category['parent_id'] == $oc_parent_id) { 
				$add_result = $mobi->dept->add_sub(false, $mc_parent_id, $mc_parent_id, $category['real_name'], true)->execute();
								
				if(!$mobi->last_request->error) {
					$deptStats['succeeded']++;
					$subProductStats = $this->addProducts($category['category_id'], $add_result->id);
						$productStats['succeeded'] += $subProductStats['succeeded'];
						$productStats['failed'] += $subProductStats['failed'];
					
					$subDeptStats = $this->addSubDepartments($category['category_id'], $add_result->id, $oc_categories);
						$deptStats['succeeded'] += $subDeptStats['departments']['succeeded'];
						$deptStats['failed'] += $subDeptStats['departments']['failed'];
						
						$productStats['succeeded'] += $subDeptStats['products']['succeeded'];
						$productStats['failed'] += $subDeptStats['products']['failed'];
				} else {
					$this->log->write($mobi->last_request->raw);
					$deptStats['failed']++;
				}				
			}
		}
		
		
		return array(
			'departments' => $deptStats,
			'products' => $productStats);
	}
	
	private function addProducts($category_id, $department_id) {
		$mobi = $this->getMobicartConnection();		
		
		$this->model_catalog_product = new \Stupycart\Common\Models\Admin\Catalog\Product();
		$results = $this->model_catalog_product->getProductsByCategoryId($category_id);
		
		$succeeded = 0;
		$failed = 0;
		foreach ($results as $result) {
			$status = "hidden";
			if($result['status']) $status = "active";
			
			$add_result = $mobi->product->add(
				false, 
				$department_id,
				false, //What is this category_id when we have department_id??
				html_entity_decode($result['name']),
				strip_tags(html_entity_decode($result['description'])), /* TODO: More fixing of the description strings */
				$status,
				$result['price'],
				false,
				HTTP_IMAGE . $result['image']
			)->execute();
			
			 if(!$mobi->last_request->error) {
				#$this->log->write($add_result . " ID:". $result['product_id']);
				$succeeded++;
				
			} else {
				#$this->log->write($add_result->message . " ID:". $result['product_id'] . "Mobicart ID:". $add_result->id);
				#$this->log->write("Last request: " . $mobi->last_request->error);
				$failed++;
				$this->log->write($mobi->last_request->raw);
			}  			
		}
		
		return array('succeeded' => $succeeded, 'failed' =>$failed);
	}
	
	/* Test connection settings without saving them. */
	/* Returns store_id if connection was successful. */
	
	public function testAction() {		
		$key = $this->request->getPostE('mobicart_api_key');
		$mobi = new Mobicart($key);
		$mobi->add_param("user_name", $this->request->getPostE('mobicart_email'), true);		
		
		if($result = $mobi->store->get()->execute()) {			
			return $result->storeId;
		} else {
			return $mobi->last_request->error;
		}		
	}
	
	private function getOpenCartCatList() {				
		$this->model_catalog_category = new \Stupycart\Common\Models\Admin\Catalog\Category();
		
		$oc_categories = array();
		
		$results = $this->model_catalog_category->getCategories(0);

		foreach ($results as $result) {
			$action = array();
			
			$action[] = array(
				'text' => $this->language->get('text_sync'),
				'href' => $this->url->link('omf/settings/sync', 'token=' . $this->session->get('token') . '&category_id=' . $result['category_id'], 'SSL')
			);
					
			$oc_categories[] = array(
				'category_id' => $result['category_id'],
				'parent_id' => $result['parent_id'],
				'real_name' => $result['real_name'],
				'name'        => $result['name'],
				'sort_order'  => $result['sort_order'],
				'selected'    => $this->request->hasPost('selected') && in_array($result['category_id'], $this->request->getPostE('selected')),
				'action'      => $action
			);
		}

		return $oc_categories;
	}

	/* Load MobiCart connection settings from DB. */
	private function getMobiCartSettings() {
		$settings = array();	
		
		$settings = array(
			'email' => $this->config->get("mobicart_email"),
			'api_key' => $this->config->get("mobicart_api_key"), 
			'store_id' => $this->config->get("mobicart_store_id")
		); 			
		
		return $settings;
	}

	/* Return a MobiCart object ready for action. */
	private function getMobicartConnection() {
		$settings = $this->getMobiCartSettings();		
		
		$key = $settings['api_key'];
		$mobi = new Mobicart($key);
		$mobi->add_param("user_name", $settings['email'], true);
		$mobi->add_param("store_id", $settings['store_id'], true); 	
		
		return $mobi;
	}
	
/*
 *	Load categories from MobiCart
 *
 */
	private function getMobiCartDeptList() {						
		$this->data['mc_categories'] = array();
		
		$mobi = $this->getMobicartConnection();
		
		$departments = $mobi->dept->get()->execute();
		
		
		if($departments) {
			
			$departments = array_reverse($departments);
			/* Still not right. Fix this. */
			foreach ($departments as $department) {										
				$this->data['mc_departments'][] = array(				
					'name'        => $department->departmentName,
					'status'        => $department->departmentStatus
				); 			
				
				if($department->subDepartments) {
					$this->getSubDepartments($department, $department->departmentName);
				}	
			}

			
		} else {
			$this->log->write("No departments");
		} 
	}
	
	private function getSubDepartments($department, $parent_name) {
		$subDepartments = $department->subDepartments;
		
		foreach ($subDepartments as $subDepartment) {		
			$name = $parent_name . ' > ' . $subDepartment->departmentName;
			$this->data['mc_departments'][] = array(				
				'name'        => $name,
				'status'        => $subDepartment->departmentStatus
			); 
			
			if ($subDepartment->subDepartments) {
				$this->getSubDepartments($subDepartment, $name);
			}
		}
	}
	
	private function validate() {
		if (!$this->user->hasPermission('modify', 'setting/setting')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
    	if ((utf8_strlen($this->request->getPostE('mobicart_email')) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->getPostE('mobicart_email'))) {
      		$this->error['email'] = $this->language->get('error_email');
    	}

    	if (utf8_strlen($this->request->getPostE('mobicart_api_key')) != 32) {
      		$this->error['api_key'] = $this->language->get('error_api_key');
    	}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
			
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>