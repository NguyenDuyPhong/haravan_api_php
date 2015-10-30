<?php
/*
 * class for doing GET, POST, PUT, DELETE CustomCollection on Haravan /admin/custom_collections... 
 * 
 * @author: phong.nguyen  
 */  
class CustomCollectionHRV extends HaravanClient {  
	public function __construct($shop_domain, $token, $api_key, $secret) { 
        parent::__construct($shop_domain, $token, $api_key, $secret); 
    } 
	 
	/*
	 * Get one CustomCollection 
	 * 
	 * @author: phong.nguyen 20151028  
	 * @param: string $strID - CustomCollection ID 
	 */  
	public function get_one($strID){  
		return $this->call('GET', '/admin/custom_collections/' . $strID . '.json', array()); 
	}
	
	/*
	 * Get all CustomCollection 
	 * 
	 * @author: phong.nguyen 20151028  
	 * @param: string $strID - CustomCollection ID 
	 */  
	public function get_all(){  
		return $this->call('GET', '/admin/custom_collections.json', array()); 
	}
	
	/*
	 * post one CustomCollection  
	 * 
	 * @author: phong.nguyen 20151028  
	 * @param: array $arrData - CustomCollection data under array  
	 */  
	public function post_one($arrData){  
		return $this->call('POST', '/admin/custom_collections.json', $arrData);  
	}
	
	/*
	 * Delete one CustomCollection  
	 * 
	 * @author: phong.nguyen 20151028  
	 * @param: string $strID - ID need to be deleted 
	 */  
	public function delete_one($strID){  
		return $this->call('DELETE', '/admin/custom_collections/' . $strID . '.json', array() );  
	}
	
	/*
	 * Update one CustomCollection  
	 * 
	 * @author: phong.nguyen 20151028  
	 * @param: string $strID - ID need to be updated 
	 * @param: array $arrData - CustomCollection data under array  
	 */  
	public function update_one($strID, $arrData){  
		return $this->call('PUT', '/admin/custom_collections/' . $strID . '.json', $arrData);  
	} 
	 
	/*
	 * Check 1 Collection Name inside array current Custom Collection.  
	 * Return relevant id for $strCollectionTitle. 
	 * 
	 * @author: phong.nguyen 20151028  
	 * @param: array $arrCustomCollections - array 
	 * @param: string $strCollectionTitle 
	 * @return: string $id 
	 */  
	public function check_collection($arrCurrentCC, $strCollectionTitle){   
		
		$strCCTitleChecking = trim($strCollectionTitle); 
		// $boFound = false; 
		$arrFoundCC = null; 
		foreach($arrCurrentCC as $arr1CC){
			$strCCTitle = trim($arr1CC['title']); 
			if($strCCTitle == $strCCTitleChecking){
				$arrFoundCC = $arr1CC; 
				break; 
			} 
		} 
		
		//check found == true/false 
		if($arrFoundCC != null){ 
			return $arrFoundCC['id'];  
		} 
		else{  
			// create new CC   
			$arrData = array(
				'custom_collection' => array(  
					'handle' => $strCCTitleChecking, 
					'title' => $strCCTitleChecking, 
					// 'body_html' => '', // no need!!! 
				)
			); 
			$arrNewCC = $this->post_one($arrData); 
			return $arrNewCC['id'];  
		} 
		
	} 
	 
		
} 


