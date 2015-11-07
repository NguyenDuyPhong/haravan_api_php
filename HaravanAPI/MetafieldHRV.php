<?php
/*
 * class for doing GET, POST, PUT, DELETE Metafield on Haravan /admin/metafields... 
 * 
 * @author: phong.nguyen 
 */  
class MetafieldHRV extends HaravanClient {   
	
	private $token;
	private $api_key;
	private $secret;
	public function __construct($shop_domain, $token, $api_key, $secret) { 
		$this->shop_domain = $shop_domain;  
		$this->token = $token; 
		$this->api_key = $api_key; 
		$this->secret = $secret; 
        parent::__construct($shop_domain, $token, $api_key, $secret); 
    } 
	 
	/*
	 * get all metafields 
	 * 
	 * @author: phong.nguyen 20151031   
	 */  
	public function get_all(){  
		return $this->call('GET', '/admin/metafields.json', array()); 
	}
	 
	/*
	 * get one metafield 
	 * 
	 * @author: phong.nguyen 20151104  
	 * @param: string $strID - Metafield ID 
	 */  
	public function get_one($strID){  
		return $this->call('GET', '/admin/metafields/' . $strID . '.json', array()); 
	}
	
	/*
	 * post one metafield  for product 
	 * 
	 * @author: phong.nguyen 20151104  
	 * @param: string $strOwnerID - Product ID contain Metafield 
	 * @param: array $arrData - Metafield data under array  
	 */  
	public function post_one_for_product($strOwnerID, $arrData){  
		$arrData = array_merge( 
				$arrData, 
				array(	
					'key' => 'is_active', 
					// 'owner_id' => $strOwnerID, 
					// 'owner_resource' => 'product',  
				) 
			); 
		$arrData = array( 'metafield' => $arrData);   
		return $this->call('POST', '/admin/products/'. $strOwnerID .'/metafields.json', $arrData);  
	} 
	
	/*
	 * delete one metafield  
	 * 
	 * @author: phong.nguyen 20151104  
	 * @param: string $strID - ID need to be deleted 
	 */  
	public function delete_one($strID){  
		return $this->call('DELETE', '/admin/metafields/' . $strID . '.json', array() );  
	}
	
	/*
	 * update one metafield  
	 * 
	 * @author: phong.nguyen 20151104  
	 * @param: string $strID - ID need to be updated 
	 * @param: array $arrData - Metafield data under array  
	 */  
	public function update_one($strID, $arrData){  
		
		$arrData = array( 'metafield' => $arrData);  
		return $this->call('PUT', '/admin/metafields/' . $strID . '.json', $arrData);  
	} 
	
	// /*
	 // * check existing metafield by Haravan handle 
	 // * 
	 // * @author: phong.nguyen 20151104  
	 // * @param: string $strProHandle - Handle need to be checked   
	 // * @param: array $arrAllMetafields - Metafield data under array  
	 // */  
	// public function check_existing_metafield($strProHandle, $arrAllMetafields){   
		// $boFound = false; 
		// $strProID = null; 
		// foreach($arrAllMetafields as $arrPro){
			// if ($strProHandle == $arrPro['handle']){
				// $boFound = true; 
				// $strProID = $arrPro['id']; 
				// break;  
			// }
		// }
		
		// return $strProID; 
	// } 
	
	
	/*
	 * post one metafield  for Order 
	 * 
	 * @author: phong.nguyen 20151104  
	 * @param: string $strOwnerID - Order ID contain Metafield 
	 * @param: array $arrData - Metafield data under array  
	 */  
	public function post_one_for_order($strOwnerID, $arrData){  
		$arrData = array_merge( 
				$arrData, 
				array(	
					'key' => 'is_active', 
					// 'owner_id' => $strOwnerID, 
					// 'owner_resource' => 'order',  
				) 
			); 
		$arrData = array( 'metafield' => $arrData);   
		return $this->call('POST', '/admin/orders/'. $strOwnerID .'/metafields.json', $arrData);  
	} 
	
	/*
	 * post one metafield  for Customer  
	 * 
	 * @author: phong.nguyen 20151104  
	 * @param: string $strOwnerID - Customer ID contain Metafield 
	 * @param: array $arrData - Metafield data under array  
	 */  
	public function post_one_for_customer($strOwnerID, $arrData){  
		$arrData = array_merge( 
				$arrData, 
				array(	
					'key' => 'is_active', 
					// 'owner_id' => $strOwnerID, 
					// 'owner_resource' => 'customer',  
				) 
			); 
		$arrData = array( 'metafield' => $arrData);   
		return $this->call('POST', '/admin/customers/'. $strOwnerID .'/metafields.json', $arrData); 
	} 
	
	/*
	 * post one metafield  for custom_collection  
	 * 
	 * @author: phong.nguyen 20151104  
	 * @param: string $strOwnerID - custom_collection ID contain Metafield 
	 * @param: array $arrData - Metafield data under array  
	 */  
	public function post_one_for_custom_collection($strOwnerID, $arrData){  
		$arrData = array_merge( 
				$arrData, 
				array(	
					'key' => 'is_active', 
					// 'owner_id' => $strOwnerID, 
					// 'owner_resource' => 'custom_collection',  
				) 
			); 
		$arrData = array( 'metafield' => $arrData);   
		return $this->call('POST', '/admin/custom_collections/'. $strOwnerID .'/metafields.json', $arrData); 
	} 
	 
} 


				//get one 
				// // // $metafield = $proHRV->call('GET', '/admin/metafields/1000459014.json', array());
				// $metafield = $proHRV->get_one('1000459014');  
				// var_dump($metafield);  
				
				// //post one  
				// $arrData = array(
					// 'metafield' => array(
						// 'handle' => 'pro00', 
						// 'title' => 'pro 00', 
						// 'name' => 'orders/create',
						// 'body_html' => 'pro00...', 
						// 'metafield_type' => 'Khác',
					// ) 
				// );  
				// $metafield = $proHRV->post_one($arrData);  
				// var_dump($metafield);   
				
				// // delete one;  
				// $metafield = $proHRV->delete_one('1001015071');    
				// var_dump($metafield);  
				
		
		
		// $proSH = new MetafieldSH( 'nguyenduyphong.myshopify.com', $this->config->item("SHOPIFY_TOKEN"), $this->config->item("SHOPIFY_API_KEY"), $this->config->item("SHOPIFY_API_SECRET"));  
		// // // $metafield = $proSH->call('GET', '/admin/metafields/1000459014.json', array());
		// $metafield = $proSH->get_one('434285837');  
		// var_dump($metafield);  
		
 