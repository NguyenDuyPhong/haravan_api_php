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
	 */  
	public function get_all(){  
		return $this->call('GET', '/admin/products.json', array()); 
	}
	 
	/*
	 * get one product 
	 * 
	 * @author: phong.nguyen 20151021  
	 * @param: string $strID - Product ID 
	 */  
	public function get_one($strID){  
		return $this->call('GET', '/admin/products/' . $strID . '.json', array()); 
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
		return $this->call('DELETE', '/admin/products/' . $strID . '.json', array() );  
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
		return $this->call('PUT', '/admin/products/' . $strID . '.json', $arrData);  
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
		$strProID = null; 
		foreach($arrAllProducts as $arrPro){
			if ($strProHandle == $arrPro['handle']){
				$boFound = true; 
				$strProID = $arrPro['id']; 
				break;  
			}
		}
		
		return $strProID; 
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
	 * Put qhome product in raw-data to Haravan admin.   
	 * 
	 * @author: phong.nguyen 20151023   
	 * @param: array $arrData - Product raw-data under array   
	 * @param: string $strUrlImagesFolder 
	 */  
	public function put_qhome_products_raw($arrProductImported, $strUrlImagesFolder){   
		
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
		$arrAllBlogs = $objBlogHRV->get_all();  
		$objBlogHRV->remove_all_titles($arrAllBlogs, $strFakeBlogTitle);   
		
		// //create Fake CustomCollection; not, maybe raise error  
		// $arrData = array( 
			// 'handle' => $strFakeBlogTitle,  // $strTitle, //  
			// 'title' => $strFakeBlogTitle, 
		// );  
		// $arrBlogFake = $objBlogHRV->post_one($arrData);   
		// $strBlogFake_ID = $arrBlogFake['id'];  
		
		//get all current products 
		$arrAllProducts = $this->get_all();   
		
		foreach($arrProductImported as $proOK) { 
			$int_count++; 
			if($int_count <= $int_max_put){  
				
				
				//fix img: get images url on hostring when host-url(strUrlImagesFolder) NOT empty 
				if(trim($strUrlImagesFolder) != ''){
					$proOK['images'][0]['src'] = $strUrlImagesFolder . '/' . $proOK['variants'][0]['sku'] . '.png';  
				} 
				
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
				$strProID = $this->check_existing_product($strHaravanHandle, $arrAllProducts); 
				if( $strProID != null )  
				{	
					// var_dump('PUT'); 
					// var_dump($proOK); 
					$product = $this->update_one($strProID, $proOK);  
				}
				else{
					// var_dump('POST');
					// var_dump($proOK);
					// var_dump($strHaravanHandle); 
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
		
		// remove all fake Custom Blog  
		// $strFakeBlogTitle = 'My Custom Blog'; 
		$arrAllBlogs = $objBlogHRV->get_all();  
		$objBlogHRV->remove_all_titles($arrAllBlogs, $strFakeBlogTitle);   
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
		
 