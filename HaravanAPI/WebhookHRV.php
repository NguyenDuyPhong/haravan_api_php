<?php
/*
 * class for doing GET, POST, PUT, DELETE Product on 
 * 
 * @author: phong.nguyen 
 */  
class WebhookHRV extends HaravanClient {  
	public function __construct($shop_domain, $token, $api_key, $secret) { 
        parent::__construct($shop_domain, $token, $api_key, $secret); 
    } 
	 
	public function register_uninstall_hook($hook_url){  	
		$arrData = array("webhook"=> array(	
						'topic'=> 'app/uninstalled',
						'address'=> $hook_url,
						'format'=> 'json')
		); 
		return $this->call('POST', '/admin/webhooks.json', $arrData); 
	}

	public function remove_uninstall_hook($id){  	
		return $this->call('DELETE', '/admin/webhooks/'.$id.'.json'); 
	}

	public function getshopinfo(){
		return $this->call('GET', '/admin/shop.json'); 
	}
} 


 