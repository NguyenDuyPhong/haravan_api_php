<?php
/*
 * class for doing GET, POST, PUT, DELETE Product on Haravan /admin/products... 
 * 
 * @author: phong.nguyen 
 */  
class ProductHRV extends HaravanClient {  
	public function __construct($shop_domain, $token, $api_key, $secret) { 
        parent::__construct($shop_domain, $token, $api_key, $secret); 
    } 
	 
	/*
	 * get one product 
	 * 
	 * @author: phong.nguyen 20151021  
	 * @paran: string $strID - Product ID 
	 */  
	public function get_one($strID){  
		return $this->call('GET', '/admin/products/' . $strID . '.json', array()); 
	}
 
} 
?>
