<?php
/*
 * class for doing GET, POST, PUT, DELETE page on Haravan /admin/admin/pages... 
 * 
 * @author: phong.nguyen 
 */  
class PageHRV extends HaravanClient {   
	
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
	 * get all pages 
	 * 
	 * @author: phong.nguyen 20151207    
	 */  
	public function get_all($arrFilter = array()){  
		return $this->call('GET', '/admin/pages.json', $arrFilter); 
	}
	 
	/*
	 * get one page 
	 * 
	 * @author: phong.nguyen 20151207  
	 * @param: string $strBlogID - blog ID 
	 * @param: string $strID - page ID 
	 */  
	public function get_one($strID){  
		return $this->call('GET', '/admin/pages/'. $strID . '.json', array()); 
	}
	
	/*
	 * post one page  
	 * 
	 * @author: phong.nguyen 20151207  
	 * @param: array $strBlogID - blog ID 
	 * @param: array $arrData - page data under array  
	 */  
	public function post_one($arrData){  
		$arrData = array('page' => $arrData);   
		return $this->call('POST', '/admin/pages.json', $arrData);  
	}
	
	/*
	 * delete one page  
	 * 
	 * @author: phong.nguyen 20151207  
	 * @param: string $strBlogID - blog ID 
	 * @param: string $strID - ID need to be deleted 
	 */  
	public function delete_one($strID){  
		return $this->call('DELETE', '/admin/pages/'. $strID . '.json', array() );  
	}
	
	/*
	 * update one page  
	 * 
	 * @author: phong.nguyen 20151207  
	 * @param: string $strBlogID - blog ID 
	 * @param: string $strID - page ID need to be updated 
	 * @param: array $arrData - page data under array  
	 */  
	public function update_one($strID, $arrData){  
		$arrData = array('page' => $arrData);  
		return $this->call('PUT', '/admin/pages/'. $strID . '.json', $arrData);  
	} 
	
		
		
} 


