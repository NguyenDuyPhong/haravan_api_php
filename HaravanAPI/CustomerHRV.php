<?php
/*
 * class for doing GET, POST, PUT, DELETE Customer on Haravan /admin/customers... 
 * 
 * @author: phong.nguyen 
 */  
class CustomerHRV extends HaravanClient {   
	
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
	 * get all customers 
	 * 
	 * @author: phong.nguyen 20151031   
	 * @param array $arrFilter 
	 * 		'page' => 2
	 * 		...
	 */  
	public function get_all($arrFilter = array()){   
		return $this->call('GET', '/admin/customers.json', $arrFilter); 
	}
	 
	/*
	 * get one customer 
	 * 
	 * @author: phong.nguyen 20151021  
	 * @param: string $strID - Customer ID 
	 */  
	public function get_one($strID){  
		return $this->call('GET', '/admin/customers/'. $strID . '.json', array()); 
	}
	
	/*
	 * post one customer  
	 * 
	 * @author: phong.nguyen 20151021  
	 * @param: array $arrData - Customer data under array  
	 */  
	public function post_one($arrData){  
		$arrData = array( 'customer' => $arrData);   
		return $this->call('POST', '/admin/customers.json', $arrData);  
	}
	
	/*
	 * delete one customer  
	 * 
	 * @author: phong.nguyen 20151021  
	 * @param: string $strID - ID need to be deleted 
	 */  
	public function delete_one($strID){  
		return $this->call('DELETE', '/admin/customers/'. $strID . '.json', array() );  
	}
	
	/*
	 * update one customer  
	 * 
	 * @author: phong.nguyen 20151021  
	 * @param: string $strID - ID need to be updated 
	 * @param: array $arrData - Customer data under array  
	 */  
	public function update_one($strID, $arrData){  
		$arrData = array( 'customer' => $arrData);  
		return $this->call('PUT', '/admin/customers/'. $strID . '.json', $arrData);  
	} 
	
	
	/*
	 * check existing customer by Haravan handle 
	 * 
	 * @author: phong.nguyen 20151021  
	 * @param: string $strCustomerHandle - Handle need to be checked   
	 * @param: array $arrAllCustomers - Customer data under array  
	 */  
	public function check_existing_customer($strCustomerNumber, $arrAllCustomers){   
		$boFound = false; 
		$strCustomerID = null; 
		foreach($arrAllCustomers as $arrCustomer){
			if ($strCustomerHandle == $arrCustomer['customer_number']){
				$boFound = true; 
				$strCustomerID = $arrCustomer['id']; 
				break;  
			}
		}
		
		return $strCustomerID; 
	} 
 
	/* 
	 * get_all_loop_page... 
	 * 
	 * @author: phong.nguyen 20151109  
	 */  
	public function get_all_loop_page(){    
		$arrAllCustomers = array();     
		$arrFilter = array(
			'page' => 1, 
		); 
		$boContinue = true; 
		while($boContinue == true)
		{   
			$arrTemp = $this->get_all($arrFilter);   
			$arrFilter['page']++; 
			if(count($arrTemp) > 0){ 
				foreach($arrTemp as $item){
					$arrAllCustomers[] = $item; 
				} 
			}
			else{
				$boContinue = false; 
			}
		} 
		
		return $arrAllCustomers; 
	
	}
	 
		
}


