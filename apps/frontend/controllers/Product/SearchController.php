<?php 

namespace Stupycart\Frontend\Controllers\Product;

class SearchController extends \Stupycart\Frontend\Controllers\ControllerBase { 	
	public function indexAction() { 
    	$this->language->load('product/search');
		
		$this->model_catalog_category = new \Stupycart\Common\Models\Catalog\Category();
		
		$this->model_catalog_product = new \Stupycart\Common\Models\Catalog\Product();
		
		$this->model_tool_image = new \Stupycart\Common\Models\Tool\Image(); 
		
		if ($this->request->hasQuery('search')) {
			$search = $this->request->getQueryE('search');
		} else {
			$search = '';
		} 
		
		if ($this->request->hasQuery('tag')) {
			$tag = $this->request->getQueryE('tag');
		} elseif ($this->request->hasQuery('search')) {
			$tag = $this->request->getQueryE('search');
		} else {
			$tag = '';
		} 
				
		if ($this->request->hasQuery('description')) {
			$description = $this->request->getQueryE('description');
		} else {
			$description = '';
		} 
				
		if ($this->request->hasQuery('category_id')) {
			$category_id = $this->request->getQueryE('category_id');
		} else {
			$category_id = 0;
		} 
		
		if ($this->request->hasQuery('sub_category')) {
			$sub_category = $this->request->getQueryE('sub_category');
		} else {
			$sub_category = '';
		} 
								
		if ($this->request->hasQuery('sort')) {
			$sort = $this->request->getQueryE('sort');
		} else {
			$sort = 'p.sort_order';
		} 

		if ($this->request->hasQuery('order')) {
			$order = $this->request->getQueryE('order');
		} else {
			$order = 'ASC';
		}
  		
		if ($this->request->hasQuery('page')) {
			$page = $this->request->getQueryE('page');
		} else {
			$page = 1;
		}
				
		if ($this->request->hasQuery('limit')) {
			$limit = $this->request->getQueryE('limit');
		} else {
			$limit = $this->config->get('config_catalog_limit');
		}
		
		if ($this->request->hasQuery('search')) {
			$this->document->setTitle($this->language->get('heading_title') .  ' - ' . $this->request->getQueryE('search'));
		} else {
			$this->document->setTitle($this->language->get('heading_title'));
		}
		
		$this->document->addScript('js/jquery/jquery.total-storage.min.js');

		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array( 
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
      		'separator' => false
   		);
		
		$url = '';
		
		if ($this->request->hasQuery('search')) {
			$url .= '&search=' . urlencode(html_entity_decode($this->request->getQueryE('search'), ENT_QUOTES, 'UTF-8'));
		}
		
		if ($this->request->hasQuery('tag')) {
			$url .= '&tag=' . urlencode(html_entity_decode($this->request->getQueryE('tag'), ENT_QUOTES, 'UTF-8'));
		}
				
		if ($this->request->hasQuery('description')) {
			$url .= '&description=' . $this->request->getQueryE('description');
		}
				
		if ($this->request->hasQuery('category_id')) {
			$url .= '&category_id=' . $this->request->getQueryE('category_id');
		}
		
		if ($this->request->hasQuery('sub_category')) {
			$url .= '&sub_category=' . $this->request->getQueryE('sub_category');
		}
		
		if ($this->request->hasQuery('sort')) {
			$url .= '&sort=' . $this->request->getQueryE('sort');
		}	

		if ($this->request->hasQuery('order')) {
			$url .= '&order=' . $this->request->getQueryE('order');
		}
				
		if ($this->request->hasQuery('page')) {
			$url .= '&page=' . $this->request->getQueryE('page');
		}	
		
		if ($this->request->hasQuery('limit')) {
			$url .= '&limit=' . $this->request->getQueryE('limit');
		}
						
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('product/search', $url),
      		'separator' => $this->language->get('text_separator')
   		);
		
		if ($this->request->hasQuery('search')) {
    		$this->data['heading_title'] = $this->language->get('heading_title') .  ' - ' . $this->request->getQueryE('search');
		} else {
			$this->data['heading_title'] = $this->language->get('heading_title');
		}
		
		$this->data['text_empty'] = $this->language->get('text_empty');
    	$this->data['text_critea'] = $this->language->get('text_critea');
    	$this->data['text_search'] = $this->language->get('text_search');
		$this->data['text_keyword'] = $this->language->get('text_keyword');
		$this->data['text_category'] = $this->language->get('text_category');
		$this->data['text_sub_category'] = $this->language->get('text_sub_category');
		$this->data['text_quantity'] = $this->language->get('text_quantity');
		$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$this->data['text_model'] = $this->language->get('text_model');
		$this->data['text_price'] = $this->language->get('text_price');
		$this->data['text_tax'] = $this->language->get('text_tax');
		$this->data['text_points'] = $this->language->get('text_points');
		$this->data['text_compare'] = sprintf($this->language->get('text_compare'), ($this->session->has('compare') ? count($this->session->get('compare')) : 0));
		$this->data['text_display'] = $this->language->get('text_display');
		$this->data['text_list'] = $this->language->get('text_list');
		$this->data['text_grid'] = $this->language->get('text_grid');		
		$this->data['text_sort'] = $this->language->get('text_sort');
		$this->data['text_limit'] = $this->language->get('text_limit');
		
		$this->data['entry_search'] = $this->language->get('entry_search');
    	$this->data['entry_description'] = $this->language->get('entry_description');
		  
    	$this->data['button_search'] = $this->language->get('button_search');
		$this->data['button_cart'] = $this->language->get('button_cart');
		$this->data['button_wishlist'] = $this->language->get('button_wishlist');
		$this->data['button_compare'] = $this->language->get('button_compare');

		$this->data['compare'] = $this->url->link('product/compare');
		
		$this->model_catalog_category = new \Stupycart\Common\Models\Catalog\Category();
		
		// 3 Level Category Search
		$this->data['categories'] = array();
					
		$categories_1 = $this->model_catalog_category->getCategories(0);
		
		foreach ($categories_1 as $category_1) {
			$level_2_data = array();
			
			$categories_2 = $this->model_catalog_category->getCategories($category_1['category_id']);
			
			foreach ($categories_2 as $category_2) {
				$level_3_data = array();
				
				$categories_3 = $this->model_catalog_category->getCategories($category_2['category_id']);
				
				foreach ($categories_3 as $category_3) {
					$level_3_data[] = array(
						'category_id' => $category_3['category_id'],
						'name'        => $category_3['name'],
					);
				}
				
				$level_2_data[] = array(
					'category_id' => $category_2['category_id'],	
					'name'        => $category_2['name'],
					'children'    => $level_3_data
				);					
			}
			
			$this->data['categories'][] = array(
				'category_id' => $category_1['category_id'],
				'name'        => $category_1['name'],
				'children'    => $level_2_data
			);
		}
		
		$this->data['products'] = array();
		
		if ($this->request->hasQuery('search') || $this->request->hasQuery('filter_tag')) {
			$data = array(
				'filter_name'         => $search, 
				'filter_tag'          => $tag, 
				'filter_description'  => $description,
				'filter_category_id'  => $category_id, 
				'filter_sub_category' => $sub_category, 
				'sort'                => $sort,
				'order'               => $order,
				'start'               => ($page - 1) * $limit,
				'limit'               => $limit
			);
					
			$product_total = $this->model_catalog_product->getTotalProducts($data);
								
			$results = $this->model_catalog_product->getProducts($data);
					
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				} else {
					$image = false;
				}
				
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}
				
				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}	
				
				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}				
				
				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}
			
				$this->data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 100) . '..',
					'price'       => $price,
					'special'     => $special,
					'tax'         => $tax,
					'rating'      => $result['rating'],
					'reviews'     => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'] . $url)
				);
			}
					
			$url = '';
			
			if ($this->request->hasQuery('search')) {
				$url .= '&search=' . urlencode(html_entity_decode($this->request->getQueryE('search'), ENT_QUOTES, 'UTF-8'));
			}
			
			if ($this->request->hasQuery('tag')) {
				$url .= '&tag=' . urlencode(html_entity_decode($this->request->getQueryE('tag'), ENT_QUOTES, 'UTF-8'));
			}
					
			if ($this->request->hasQuery('description')) {
				$url .= '&description=' . $this->request->getQueryE('description');
			}
			
			if ($this->request->hasQuery('category_id')) {
				$url .= '&category_id=' . $this->request->getQueryE('category_id');
			}
			
			if ($this->request->hasQuery('sub_category')) {
				$url .= '&sub_category=' . $this->request->getQueryE('sub_category');
			}
					
			if ($this->request->hasQuery('limit')) {
				$url .= '&limit=' . $this->request->getQueryE('limit');
			}
						
			$this->data['sorts'] = array();
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_default'),
				'value' => 'p.sort_order-ASC',
				'href'  => $this->url->link('product/search', 'sort=p.sort_order&order=ASC' . $url)
			);
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_asc'),
				'value' => 'pd.name-ASC',
				'href'  => $this->url->link('product/search', 'sort=pd.name&order=ASC' . $url)
			); 
	
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_name_desc'),
				'value' => 'pd.name-DESC',
				'href'  => $this->url->link('product/search', 'sort=pd.name&order=DESC' . $url)
			);
	
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_price_asc'),
				'value' => 'p.price-ASC',
				'href'  => $this->url->link('product/search', 'sort=p.price&order=ASC' . $url)
			); 
	
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_price_desc'),
				'value' => 'p.price-DESC',
				'href'  => $this->url->link('product/search', 'sort=p.price&order=DESC' . $url)
			); 
			
			if ($this->config->get('config_review_status')) {
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_desc'),
					'value' => 'rating-DESC',
					'href'  => $this->url->link('product/search', 'sort=rating&order=DESC' . $url)
				); 
				
				$this->data['sorts'][] = array(
					'text'  => $this->language->get('text_rating_asc'),
					'value' => 'rating-ASC',
					'href'  => $this->url->link('product/search', 'sort=rating&order=ASC' . $url)
				);
			}
			
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_model_asc'),
				'value' => 'p.model-ASC',
				'href'  => $this->url->link('product/search', 'sort=p.model&order=ASC' . $url)
			); 
	
			$this->data['sorts'][] = array(
				'text'  => $this->language->get('text_model_desc'),
				'value' => 'p.model-DESC',
				'href'  => $this->url->link('product/search', 'sort=p.model&order=DESC' . $url)
			);
	
			$url = '';
			
			if ($this->request->hasQuery('search')) {
				$url .= '&search=' . urlencode(html_entity_decode($this->request->getQueryE('search'), ENT_QUOTES, 'UTF-8'));
			}
			
			if ($this->request->hasQuery('tag')) {
				$url .= '&tag=' . urlencode(html_entity_decode($this->request->getQueryE('tag'), ENT_QUOTES, 'UTF-8'));
			}
					
			if ($this->request->hasQuery('description')) {
				$url .= '&description=' . $this->request->getQueryE('description');
			}
			
			if ($this->request->hasQuery('category_id')) {
				$url .= '&category_id=' . $this->request->getQueryE('category_id');
			}
			
			if ($this->request->hasQuery('sub_category')) {
				$url .= '&sub_category=' . $this->request->getQueryE('sub_category');
			}
						
			if ($this->request->hasQuery('sort')) {
				$url .= '&sort=' . $this->request->getQueryE('sort');
			}	
	
			if ($this->request->hasQuery('order')) {
				$url .= '&order=' . $this->request->getQueryE('order');
			}
			
			$this->data['limits'] = array();
	
			$limits = array_unique(array($this->config->get('config_catalog_limit'), 25, 50, 75, 100));
			
			sort($limits);
	
			foreach($limits as $limits){
				$this->data['limits'][] = array(
					'text'  => $limits,
					'value' => $limits,
					'href'  => $this->url->link('product/search', $url . '&limit=' . $limits)
				);
			}
					
			$url = '';
	
			if ($this->request->hasQuery('search')) {
				$url .= '&search=' . urlencode(html_entity_decode($this->request->getQueryE('search'), ENT_QUOTES, 'UTF-8'));
			}
			
			if ($this->request->hasQuery('tag')) {
				$url .= '&tag=' . urlencode(html_entity_decode($this->request->getQueryE('filter_tag'), ENT_QUOTES, 'UTF-8'));
			}
					
			if ($this->request->hasQuery('description')) {
				$url .= '&description=' . $this->request->getQueryE('description');
			}
			
			if ($this->request->hasQuery('category_id')) {
				$url .= '&category_id=' . $this->request->getQueryE('category_id');
			}
			
			if ($this->request->hasQuery('sub_category')) {
				$url .= '&sub_category=' . $this->request->getQueryE('sub_category');
			}
										
			if ($this->request->hasQuery('sort')) {
				$url .= '&sort=' . $this->request->getQueryE('sort');
			}	
	
			if ($this->request->hasQuery('order')) {
				$url .= '&order=' . $this->request->getQueryE('order');
			}
			
			if ($this->request->hasQuery('limit')) {
				$url .= '&limit=' . $this->request->getQueryE('limit');
			}
					
			$pagination = new \Libs\Opencart\Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->text = $this->language->get('text_pagination');
			$pagination->url = $this->url->link('product/search', $url . '&page={page}');
			
			$this->data['pagination'] = $pagination->render();
		}	
		
		$this->data['search'] = $search;
		$this->data['description'] = $description;
		$this->data['category_id'] = $category_id;
		$this->data['sub_category'] = $sub_category;
				
		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
		$this->data['limit'] = $limit;
		
		$this->view->pick('product/search');
		
		$this->_commonAction();
				
		$this->view->setVars($this->data);
  	}
}
?>