<?php
/*
 * class for doing GET, POST, PUT, DELETE Order on Haravan /admin/orders... 
 * 
 * @author: phong.nguyen 
 */  
class OrderHRV extends HaravanClient {   
	
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
	 * get all orders 
	 * 
	 * @author: phong.nguyen 20151031   
	 * @param array $arrFilter 
	 * 		'page' => 2
	 * 		...
	 */  
	public function get_all($arrFilter = array()){   
		return $this->call('GET', '/admin/orders.json', $arrFilter); 
	}
	 
	/*
	 * get one order 
	 * 
	 * @author: phong.nguyen 20151021  
	 * @param: string $strID - Order ID 
	 */  
	public function get_one($strID){  
		return $this->call('GET', '/admin/orders/' . $strID . '.json', array()); 
	}
	
	/*
	 * post one order  
	 * 
	 * @author: phong.nguyen 20151021  
	 * @param: array $arrData - Order data under array  
	 */  
	public function post_one($arrData){  
		$arrData = array( 'order' => $arrData);   
		return $this->call('POST', '/admin/orders.json', $arrData);  
	}
	
	/*
	 * delete one order  
	 * 
	 * @author: phong.nguyen 20151021  
	 * @param: string $strID - ID need to be deleted 
	 */  
	public function delete_one($strID){  
		return $this->call('DELETE', '/admin/orders/' . $strID . '.json', array() );  
	}
	
	/*
	 * update one order  
	 * 
	 * @author: phong.nguyen 20151021  
	 * @param: string $strID - ID need to be updated 
	 * @param: array $arrData - Order data under array  
	 */  
	public function update_one($strID, $arrData){  
		$arrData = array( 'order' => $arrData);  
		return $this->call('PUT', '/admin/orders/' . $strID . '.json', $arrData);  
	} 
	
	
	/*
	 * check existing order by Haravan handle 
	 * 
	 * @author: phong.nguyen 20151021  
	 * @param: string $strOrderHandle - Handle need to be checked   
	 * @param: array $arrAllOrders - Order data under array  
	 */  
	public function check_existing_order($strOrderNumber, $arrAllOrders){   
		$boFound = false; 
		$strOrderID = null; 
		foreach($arrAllOrders as $arrOrder){
			if ($strOrderHandle == $arrOrder['order_number']){
				$boFound = true; 
				$strOrderID = $arrOrder['id']; 
				break;  
			}
		}
		
		return $strOrderID; 
	} 
	 
	/* 
	 * get_all_loop_page... 
	 * 
	 * @author: phong.nguyen 20151109  
	 */  
	public function get_all_loop_page(){    
		$arrAllOrders = array();     
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
					$arrAllOrders[] = $item; 
				} 
			}
			else{
				$boContinue = false; 
			}
		} 
		
		return $arrAllOrders; 
	
	}
	
	
	/* 
	 * update_shipment_and_payment... 
	 *    shipment field "fulfillment_status" 
	 *    payment field "financial_status" 
	 *  
	 * @author: phong.nguyen 20151109  
	 */  
	private $arrPayCode = array(
		'pending' => 'Chờ xử lý', 
		'paid' => 'Đã thanh toán', 
	); 
	public function update_shipment_and_payment($arrOrder){ 
		
		// update shipment 
		$arrFulfill = $arrOrder['fulfillments']; 
		if( count($arrFulfill) > 0 ){
			$arrFulfill = $arrFulfill[0]; 
			$arrOrder['fulfillment_status'] = $arrFulfill['carrier_status_name'];   
		}
		else{
			$arrOrder['fulfillment_status'] = 'Chưa giao hàng'; 
		}  
		
		// update payment 
		$keyPayCode = 'financial_status'; 
		if(array_key_exists($arrOrder[$keyPayCode], $this->arrPayCode)){
			$arrOrder[$keyPayCode] = $this->arrPayCode[$arrOrder[$keyPayCode]];  
		} 
		
		return $arrOrder; 
	}
	 
		
} 

