<?php
/*
 * class for doing GET, POST, PUT, DELETE SmartCollection on Haravan /admin/smart_collections... 
 * 
 * @author: phong.nguyen  
 */  
class SmartCollectionHRV extends HaravanClient {  
	public function __construct($shop_domain, $token, $api_key, $secret) { 
        parent::__construct($shop_domain, $token, $api_key, $secret); 
    } 
	 
	/*
	 * Get one SmartCollection 
	 * 
	 * @author: phong.nguyen 20151028  
	 * @param: string $strID - SmartCollection ID 
	 */  
	public function get_one($strID){  
		return $this->call('GET', '/admin/smart_collections/' . $strID . '.json', array()); 
	}
	
	/*
	 * Get all SmartCollection 
	 * 
	 * @author: phong.nguyen 20151028  
	 * @param: string $strID - SmartCollection ID 
	 */  
	public function get_all($arrFilter = array()){  
		return $this->call('GET', '/admin/smart_collections.json', $arrFilter);  
	}
	
	/*
	 * post one SmartCollection  
	 * 
	 * @author: phong.nguyen 20151028  
	 * @param: array $arrData - SmartCollection data under array  
	 */  
	public function post_one($arrData){  
		$arrData = array( 'smart_collection' => $arrData);   
		return $this->call('POST', '/admin/smart_collections.json', $arrData);  
	}
	
	/*
	 * Delete one SmartCollection  
	 * 
	 * @author: phong.nguyen 20151028  
	 * @param: string $strID - ID need to be deleted 
	 */  
	public function delete_one($strID){  
		return $this->call('DELETE', '/admin/smart_collections/' . $strID . '.json', array() );  
	}
	
	/*
	 * Update one SmartCollection  
	 * 
	 * @author: phong.nguyen 20151028  
	 * @param: string $strID - ID need to be updated 
	 * @param: array $arrData - SmartCollection data under array  
	 */  
	public function update_one($strID, $arrData){  
		$arrData = array( 'smart_collection' => $arrData);   
		return $this->call('PUT', '/admin/smart_collections/' . $strID . '.json', $arrData);  
	} 
	 
	
	
	/*
	 * Add 1 Collection "$strCollectionTitle" inside array current Custom Collection.  
	 * Return relevant id for $strCollectionTitle. 
	 * 
	 * @author: phong.nguyen 20151028  
	 * @param: array $arrAllCurrentCC - MODIFIED, all current SmartCollections 
	 * @param: string $strCollectionTitle 
	 * @return: string $id 
	 */  
	public function add_one_cc(&$arrAllCurrentCC, $strCollectionTitle){   
		
		$strCCTitleChecking = trim($strCollectionTitle); 
		// $boFound = false; 
		$arrFoundCC = null; 
		foreach($arrAllCurrentCC as $arr1CC){
			$strCCTitle = trim($arr1CC['name']); 
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
				// 'smart_collection' => array(   // no need! 
					'alias' => $strCCTitleChecking, 
					'name' => $strCCTitleChecking, 
					// 'body_html' => '', // no need!!! 
				// )
			); 
			$arrNewCC = $this->post_one($arrData); 
			
			//update current SmartCollection 
			$arrAllCurrentCC[] = $arrNewCC; 
			
			//return New ID 
			return $arrNewCC['id'];  
		} 
		
	}  

	/*
	 * get all SmartCollection that belong to a product 
	 * 
	 * @author: phong.nguyen 20151209    
	 * @param: string $strProID - Product ID 
	 */  
	public function get_all_smart_collection_by_product($strProID){  
		return $this->call('GET', 'admin/smart_collections.json?product_id='. $strProID, array()); 
	} 
		
} 


