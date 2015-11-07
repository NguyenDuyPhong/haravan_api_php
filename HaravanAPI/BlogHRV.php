<?php
/*
 * class for doing GET, POST, PUT, DELETE blog on Haravan /admin/blogs... 
 * 
 * @author: phong.nguyen 
 */  
class BlogHRV extends HaravanClient {   
	
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
	 * get all blogs 
	 * 
	 * @author: phong.nguyen 20151031   
	 */  
	public function get_all(){  
		return $this->call('GET', '/admin/blogs.json', array()); 
	}
	 
	/*
	 * get one blog 
	 * 
	 * @author: phong.nguyen 20151021  
	 * @param: string $strID - blog ID 
	 */  
	public function get_one($strID){  
		return $this->call('GET', '/admin/blogs/' . $strID . '.json', array()); 
	}
	
	/*
	 * post one blog  
	 * 
	 * @author: phong.nguyen 20151021  
	 * @param: array $arrData - blog data under array  
	 */  
	public function post_one($arrData){  
		$arrData = array( 'blog' => $arrData);   
		return $this->call('POST', '/admin/blogs.json', $arrData);  
	}
	
	/*
	 * delete one blog  
	 * 
	 * @author: phong.nguyen 20151021  
	 * @param: string $strID - ID need to be deleted 
	 */  
	public function delete_one($strID){  
		return $this->call('DELETE', '/admin/blogs/' . $strID . '.json', array() );  
	}
	
	/*
	 * update one blog  
	 * 
	 * @author: phong.nguyen 20151021  
	 * @param: string $strID - ID need to be updated 
	 * @param: array $arrData - blog data under array  
	 */  
	public function update_one($strID, $arrData){  
		$arrData = array( 'blog' => $arrData);  
		return $this->call('PUT', '/admin/blogs/' . $strID . '.json', $arrData);  
	} 
	
	/*
	 * Remove all CC with relevant title $strBlogTitle  
	 *  
	 * @author: phong.nguyen 20151028  
	 * @param: array $arrAllCurrentBlog - MODIFIED, all current CustomCollections 
	 * @param: string $strBlogTitle for removing  
	 */  
	public function remove_all_titles(&$arrAllCurrentBlog, $strBlogTitle){  
		$strBlogTitleChecking = trim($strBlogTitle); 
		
		// loop to find out removing section. 
		foreach($arrAllCurrentBlog as $arr1Blog){
			$strBlogTitle = trim($arr1Blog['title']); 
			
			if( strpos($strBlogTitle, $strBlogTitleChecking) !== false ){ // 2 'equal' chars ==  
				// remove current Blog 
				$this->delete_one($arr1Blog['id']);   
				
				// $arrAllCurrentBlog 
				
			} 
		} 
	} 
	
	
		
		
} 


