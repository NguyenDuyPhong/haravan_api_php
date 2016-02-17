<?php
/*
 * class for doing GET, POST, PUT, DELETE Product on Haravan /admin/products... 
 * 
 * @author: phong.nguyen 
 */  
class ProductHRV extends HaravanClient {   
	
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
	 * get all products 
	 * 
	 * @author: phong.nguyen 20151031   
	 * @param array $arrFilter 
	 * 		'page' => 2
	 * 		...
	 */  
	public function get_all($arrFilter = array()){   
		return $this->call('GET', '/admin/products.json', $arrFilter); 
	}
	 
	/*
	 * get one product 
	 * 
	 * @author: phong.nguyen 20151021  
	 * @param: string $strID - Product ID 
	 */  
	public function get_one($strID){  
		return $this->call('GET', '/admin/products/'. $strID . '.json', array()); 
	}
	
	/*
	 * post one product  
	 * 
	 * @author: phong.nguyen 20151021  
	 * @param: array $arrData - Product data under array  
	 */  
	public function post_one($arrData){  
		$arrData = array( 'product' => $arrData);   
		return $this->call('POST', '/admin/products.json', $arrData);  
	}
	
	/*
	 * delete one product  
	 * 
	 * @author: phong.nguyen 20151021  
	 * @param: string $strID - ID need to be deleted 
	 */  
	public function delete_one($strID){  
		return $this->call('DELETE', '/admin/products/'. $strID . '.json', array() );  
	}
	
	/*
	 * update one product  
	 * 
	 * @author: phong.nguyen 20151021  
	 * @param: string $strID - ID need to be updated 
	 * @param: array $arrData - Product data under array  
	 */  
	public function update_one($strID, $arrData){  
		$arrData = array( 'product' => $arrData);  
		return $this->call('PUT', '/admin/products/'. $strID . '.json', $arrData);  
	} 
	
	
	/*
	 * check existing product by Haravan handle 
	 * 
	 * @author: phong.nguyen 20151021  
	 * @param: string $strProHandle - Handle need to be checked   
	 * @param: array $arrAllProducts - Product data under array  
	 */  
	public function check_existing_product($strProHandle, $arrAllProducts){   
		$boFound = false; 
		// $strProID = null; 
		$arrProExisting = null; 
		foreach($arrAllProducts as $arrPro){
			if ($strProHandle == $arrPro['handle']){
				$boFound = true; 
				$arrProExisting = $arrPro; //$strProID = $arrPro['id']; 
				break;  
			}
		}
		
		return $arrProExisting; 
	} 
	
	/* 
	 * Add 1 collection for a product.   
	 * 
	 * @author: phong.nguyen 20151028  
	 * @param: string $strProID - Product ID  
	 * @param: string $strCollectionTitle - Collection Title  
	 * @param: array $arrAllCurrentCC - MODIFIED, all Custom Collection   
	 * @param: array $objCCHaravan - object HaravanClient "Custom Collection"  
	 * @param: array $objCollectHRV - object HaravanClient "Collect"   
	 */  
	public function add_one_collection($strProID, $strCollectionTitle, &$arrAllCurrentCC, $objCCHaravan, $objCollectHRV){  
		// get id for Custom Collection "$strCollectionTitle"  
		$strCCRelevantID = $objCCHaravan->add_one_cc($arrAllCurrentCC, $strCollectionTitle);  
		
		// add current Custom Collection for a product  
		$arrCollectData = array(
			// 'collect' => array( // no need! 
				'product_id' => $strProID, 
				'collection_id' => $strCCRelevantID, 
			// ) 
		); 
		$arrCollects = $objCollectHRV->post_one($arrCollectData);   
		
		return $strCCRelevantID; 
	} 
	
	/*
	 * get all vendors  
	 * 
	 * @author: phong.nguyen 20151113     
	 * @return: array(
	 *  	'Minh long' => true, 
	 *  	...,  
	 * )
	 */  
	public function get_all_vendors(){   
		$arrFilter = array(
			'fields' => 'vendor', 
		);
		$arrVendors = array(); 
		$arrAllItems =  $this->get_all($arrFilter);  
		
		//loop for getting all vendors 
		foreach($arrAllItems as $arrTMP){
			$arrVendors[trim($arrTMP['vendor'])] = true; 
		}
		
		return $arrVendors; 
	} 
	
	
	/* 
	 * Put qhome product in raw-data to Haravan admin.   
	 * 
	 * @author: phong.nguyen 20151023   
	 * @param: array $arrData - Product raw-data under array    
	 * @param: array $objConfigs - CodeIgniter config 
	 * @return:
	 *    array { 'countBlogERROR' => 123 } 
	 */  
	public function put_qhome_products_raw($arrProductImported, $objConfigs){   
		
		$int_max_put = 10000; // support 10k products  
		$int_count = 0;   
		
		// get all Custom Collection  
		$objCCHaravan = new CustomCollectionHRV($this->shop_domain, $this->token, $this->api_key, $this->secret);   
		$objCollectHRV = new CollectHRV($this->shop_domain, $this->token, $this->api_key, $this->secret); 
		$objBlogHRV = new BlogHRV($this->shop_domain, $this->token, $this->api_key, $this->secret); 
		
		$arrAllCC = $objCCHaravan->get_all();  
		$strProID = '1001024105';  
		// $strCollectionTitle = 'cat05'; 
		
		$strFakeBlogTitle = 'My Custom Blog'; 
		// // remove all fake Custom Blog  
		$this->removeFakeBlogs($objBlogHRV, $strFakeBlogTitle);  
		$arrErrors = array();  
		
		// //create Fake CustomCollection; not, maybe raise error  
		// $arrData = array( 
			// 'handle' => $strFakeBlogTitle,  // $strTitle, //  
			// 'title' => $strFakeBlogTitle, 
		// );  
		// $arrBlogFake = $objBlogHRV->post_one($arrData);   
		// $strBlogFake_ID = $arrBlogFake['id'];  
		
		//get all current products  
		$arrAllProducts = $this->get_all_loop_page();  
		
		foreach($arrProductImported as $proOK) { 
			$int_count++; 
			if($int_count <= $int_max_put){   
				
				//fix img: moved to ...\application\libraries\Excel.php
				
				//get Haravan handle, by updating Fake CustomCollection    
				$arrData = array( 
					'handle' => $proOK['handle'], // $strFakeBlogTitle 
					'title' => $strFakeBlogTitle, //$proOK['handle'], // 
					// 'body_html' => $proOK['handle'], // not at blog; only at  CustomCollection 
				); 		
				// $arrBlogFake = $objBlogHRV->update_one($strBlogFake_ID, $arrData);   
				$arrBlogFake = $objBlogHRV->post_one($arrData);   
				$strHaravanHandle = $arrBlogFake['handle']; 
				$proOK['handle'] = $strHaravanHandle;  
				
				//check existing product by handle: existing - update, NOT - create a new one.  
				usleep(500000); // (micro sec.) === 0.5s  
				$arrProExisting = $this->check_existing_product($strHaravanHandle, $arrAllProducts); 
				$strProID = null; 
				if( $arrProExisting != null )  
				{	 
					// var_dump($proOK); 
					unset($proOK['handle']); // important: NOT update handle 
					
					// keep Custom-Tags by user  
					$strTagsPrefixDefault = $objConfigs->item("tags_prefix_default");
					$arrTagsPrefixDefault = explode(',', $strTagsPrefixDefault); 
					
					$strTagsOld = $arrProExisting['tags'];  
					$arrTagsOld = explode(',', $strTagsOld);  
					$strCustomTagsOld = ''; 
					foreach($arrTagsOld as $strTagOld){
						$strTagOld_Prefix = explode('_', $strTagOld)[0]; 
						$strTagOld_Prefix .= '_'; 
						if(in_array($strTagOld, $arrTagsPrefixDefault) 
						|| in_array($strTagOld_Prefix, $arrTagsPrefixDefault)  ){
							// do nothing 
						} 
						else {
							// keep old tags 
							$strCustomTagsOld .= ',' . $strTagOld; 
						}
					}  
					
					// update product 
					$proOK['tags'] .= $strCustomTagsOld; 
					$strProID = $arrProExisting['id']; // update current ProductID 
					$product = $this->update_one($strProID, $proOK);   
				}
				else{ 
					// var_dump($proOK);
					// var_dump($strHaravanHandle);  
					// echo 'create 1 product';
					// var_dump($proOK); exit; // check bug 20151208 
					$product = $this->post_one($proOK); 
					$strProID = $product['id']; // update current ProductID 
				} 
				// $product = $this->get_one($strProID);   
				
				// add 1 collection  
				$strCollectionTitle = $proOK['collection_title']; 
				$strCCRelevantID = $this->add_one_collection($strProID, $strCollectionTitle, $arrAllCC, $objCCHaravan, $objCollectHRV);  
				
				
			} // end if: int_max_put  
		} // end for: arrProductImported  
		
		//delete Fake CustomCollection   
		// $objBlogHRV->delete_one($strBlogFake_ID);  
		
		// remove all fake Blog; phong.nguyen 20151111 loop for removing...  
		$strFakeBlogTitle = 'My Custom Blog'; 
		$arrErrors['countBlogERROR'] = $this->removeFakeBlogs($objBlogHRV, $strFakeBlogTitle);  
		return $arrErrors; 
		
	} 
	
	
	/* 
	 * removeFakeBlogs
	 * Sleep 3s foreach going through 50 items
	 * 
	 * @author: phong.nguyen 20151125    
	 * @param: object $objBlogHRV - Haravan client for Blog  
	 * @param: string $strFakeBlogTitle 
	 */  
	function removeFakeBlogs($objBlogHRV, $strFakeBlogTitle){
		$boStillHaveFakeBlog = true;  
		$countBlogERROR = 0; 
		while($boStillHaveFakeBlog == true){ 	
			try{ // phong.nguyen 20151215 not use 
				$arrAllBlogs = $objBlogHRV->get_all();   
				$countDeleted = $objBlogHRV->remove_all_titles($arrAllBlogs, $strFakeBlogTitle);    
				if($countDeleted > 0 ){ 
					sleep(3); // must-have sleep 3 second for the next-actions 
				}
				else {
					$boStillHaveFakeBlog = false; 
				}
			}
			catch(Exception $ex){
				sleep(10); 
				$countBlogERROR++; 
				if($countBlogERROR > 10){ // if count error over 10 times, break the while 
					// var_dump('countBlogERROR:'); 
					// var_dump($countBlogERROR); 
					$boStillHaveFakeBlog = false; 
				}
			}
		} 
		return $countBlogERROR; 
	}
		 
	
	
	/* 
	 * build_property_data_for_update
	 * build array arrData base on list of available-properties & relevant array Data 
	 * 
	 * @author: phong.nguyen 20151127   
	 * @param: array $arrProperties  ('title' => 'Tiêu đề'...)
	 * @param: array $arrData ('title' => 'Google Nexus 7',... )  (Based on Haravan API. )  
	 * @return array for updating product  
	 * 		array property KEY + data  
	 * 		nothing: array length =  0 
	 */  
	public function build_property_data_for_update($arrProperties, $arrData){   
		$arrRe = array(
			'product_type' => 'Khác', 
		); 
		
		foreach ($arrProperties as $key => $prop){
			if( array_key_exists($key, $arrData) ){
				// single property like as: title, handle 
				switch($key){
					case 'variants': 
						$arrVars = array(); 
						foreach($arrData[$key] as $var) {
							unset($var['id']); 
							unset($var['product_id']); 
							unset($var['image_id']); 
							$arrVars[] = $var; 
						} 
						$arrRe[$key] = $arrVars; 
						break; 
					default:  
						$arrRe[$key] = $arrData[$key];   
				}
			}
			else{
				// multiple property (combo, conflex prop ); like as: variants, images 
				
			} 
		} 
		return $arrRe;  
	}
	
	
	/*
	 * get all Products belong to 1 CustomCollection 
	 * 
	 * @author: phong.nguyen 20160107     
	 * @param: string $strCCid  
	 */  
	public function get_all_products_by_custom_collection($strCCid){  
		return $this->call('GET', 'admin/products.json?collection_id='. $strCCid, array()); 
	} 

} 


				//get one 
				// // // $product = $proHRV->call('GET', '/admin/products/1000459014.json', array());
				// $product = $proHRV->get_one('1000459014');  
				// var_dump($product);  
				
				// //post one  
				// $arrData = array(
					// 'product' => array(
						// 'handle' => 'pro00', 
						// 'title' => 'pro 00', 
						// 'name' => 'orders/create',
						// 'body_html' => 'pro00...', 
						// 'product_type' => 'Khác',
					// ) 
				// );  
				// $product = $proHRV->post_one($arrData);  
				// var_dump($product);   
				
				// // delete one;  
				// $product = $proHRV->delete_one('1001015071');    
				// var_dump($product);  
				
		
		
		// $proSH = new ProductSH( 'nguyenduyphong.myshopify.com', $this->config->item("SHOPIFY_TOKEN"), $this->config->item("SHOPIFY_API_KEY"), $this->config->item("SHOPIFY_API_SECRET"));  
		// // // $product = $proSH->call('GET', '/admin/products/1000459014.json', array());
		// $product = $proSH->get_one('434285837');  
		// var_dump($product);  
		
 