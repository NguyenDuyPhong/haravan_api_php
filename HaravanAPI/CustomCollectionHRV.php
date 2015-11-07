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
		$arrData = array( 'custom_collection' => $arrData);   
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
		$arrData = array( 'custom_collection' => $arrData);   
		return $this->call('PUT', '/admin/custom_collections/' . $strID . '.json', $arrData);  
	} 
	 
	/*
	 * Remove all CC with relevant title $strCollectionTitle  
	 *  
	 * @author: phong.nguyen 20151028  
	 * @param: array $arrAllCurrentCC - MODIFIED, all current CustomCollections 
	 * @param: string $strCollectionTitle for removing  
	 */  
	public function remove_all_titles(&$arrAllCurrentCC, $strCollectionTitle){  
		$strCCTitleChecking = trim($strCollectionTitle); 
		
		// loop to find out removing section. 
		foreach($arrAllCurrentCC as $arr1CC){
			$strCCTitle = trim($arr1CC['title']); 
			
			if( strpos($strCCTitle, $strCCTitleChecking) !== false ){ // 2 'equal' chars ==  
				// remove current CC 
				$this->delete_one($arr1CC['id']);   
				
				// $arrAllCurrentCC 
				
			} 
		} 
	} 
	
	
	/*
	 * Add 1 Collection "$strCollectionTitle" inside array current Custom Collection.  
	 * Return relevant id for $strCollectionTitle. 
	 * 
	 * @author: phong.nguyen 20151028  
	 * @param: array $arrAllCurrentCC - MODIFIED, all current CustomCollections 
	 * @param: string $strCollectionTitle 
	 * @return: string $id 
	 */  
	public function add_one_cc(&$arrAllCurrentCC, $strCollectionTitle){   
		
		$strCCTitleChecking = trim($strCollectionTitle); 
		// $boFound = false; 
		$arrFoundCC = null; 
		foreach($arrAllCurrentCC as $arr1CC){
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
				// 'custom_collection' => array(   // no need! 
					'handle' => $strCCTitleChecking, 
					'title' => $strCCTitleChecking, 
					// 'body_html' => '', // no need!!! 
				// )
			); 
			$arrNewCC = $this->post_one($arrData); 
			
			//update current CustomCollection 
			$arrAllCurrentCC[] = $arrNewCC; 
			
			//return New ID 
			return $arrNewCC['id'];  
		} 
		
	} 
	
	
	/*
	 * get all CustomCollection that product belong to 
	 * ERROR, check this: ha-limits14-api errorAAA collects + product.png 
	 * 
	 * @author: phong.nguyen 20151103   
	 * @param: string $strProID - Product ID 
	 */  
	public function get_all_custom_collection_by_product($strProID){  
		return $this->call('GET', 'admin/custom_collections.json?product_id='. $strProID, array()); 
	} 
	 
		
} 


