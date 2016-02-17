<?php
/*
 * class for doing GET, POST, PUT, DELETE article on Haravan /admin/articles... 
 * 
 * @author: phong.nguyen 
 */  
class ArticleHRV extends HaravanClient {   
	
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
	 * get all articles 
	 * 
	 * @author: phong.nguyen 20151031   
	 * @param: string strBlogID 
	 */  
	public function get_all($strBlogID){  
		return $this->call('GET', '/admin/blogs/'. $strBlogID .'/articles.json', array()); 
	}
	 
	/*
	 * get one article 
	 * 
	 * @author: phong.nguyen 20151021  
	 * @param: string $strBlogID - blog ID 
	 * @param: string $strID - article ID 
	 */  
	public function get_one($strBlogID, $strID){  
		return $this->call('GET', '/admin/blogs/'. $strBlogID .'/articles'. $strID .'.json', array()); 
	}
	
	/*
	 * post one article  
	 * 
	 * @author: phong.nguyen 20151021  
	 * @param: array $strBlogID - blog ID 
	 * @param: array $arrData - article data under array  
	 */  
	public function post_one($strBlogID, $arrData){  
		$arrData = array( 'article' => $arrData);   
		return $this->call('POST', '/admin/blogs/'. $strBlogID .'/articles.json', $arrData);  
	}
	
	/*
	 * delete one article  
	 * 
	 * @author: phong.nguyen 20151021  
	 * @param: string $strBlogID - blog ID 
	 * @param: string $strID - ID need to be deleted 
	 */  
	public function delete_one($strBlogID, $strID){  
		return $this->call('DELETE', '/admin/blogs/'. $strBlogID .'/articles/'. $strID . '.json', array() );  
	}
	
	/*
	 * update one article  
	 * 
	 * @author: phong.nguyen 20151021  
	 * @param: string $strBlogID - blog ID 
	 * @param: string $strID - article ID need to be updated 
	 * @param: array $arrData - article data under array  
	 */  
	public function update_one($strBlogID, $strID, $arrData){  
		$arrData = array( 'article' => $arrData);  
		return $this->call('PUT', '/admin/blogs/'. $strBlogID .'/articles/'. $strID . '.json', $arrData);  
	} 
	
		
		
} 


