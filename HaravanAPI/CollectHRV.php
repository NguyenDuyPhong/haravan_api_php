<?php
/*
 * class for doing GET, POST, PUT, DELETE Collect on Haravan /admin/collects... 
 * 
 * @author: phong.nguyen  
 */  
class CollectHRV extends HaravanClient {  
	public function __construct($shop_domain, $token, $api_key, $secret) { 
        parent::__construct($shop_domain, $token, $api_key, $secret); 
    } 
	 
	/*
	 * get one Collect 
	 * 
	 * @author: phong.nguyen 20151028  
	 * @param: string $strID - Collect ID 
	 */  
	public function get_one($strID){  
		return $this->call('GET', '/admin/collects/' . $strID . '.json', array()); 
	}
	
	/*
	 * get all CustomCollection 
	 * 
	 * @author: phong.nguyen 20151028  
	 * @param: string $strID - CustomCollection ID 
	 */  
	public function get_all(){  
		return $this->call('GET', '/admin/collects.json', array()); 
	}
	
	/*
	 * post one Collect  
	 * 
	 * @author: phong.nguyen 20151028  
	 * @param: array $arrData - Collect data under array  
	 */  
	public function post_one($arrData){  
		return $this->call('POST', '/admin/collects.json', $arrData);  
	}
	
	/*
	 * delete one Collect  
	 * 
	 * @author: phong.nguyen 20151028  
	 * @param: string $strID - ID need to be deleted 
	 */  
	public function delete_one($strID){  
		return $this->call('DELETE', '/admin/collects/' . $strID . '.json', array() );  
	}
	
	/*
	 * update one Collect  
	 * 
	 * @author: phong.nguyen 20151028  
	 * @param: string $strID - ID need to be updated 
	 * @param: array $arrData - Collect data under array  
	 */  
	public function update_one($strID, $arrData){  
		return $this->call('PUT', '/admin/collects/' . $strID . '.json', $arrData);  
	} 
	 
		
} 


