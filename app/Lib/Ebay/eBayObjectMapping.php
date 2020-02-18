<?php
namespace App\Lib\Ebay;
class eBayObjectMapping{
	
	public static function toOrder($order){

		$orderArr = array();
		//$orderArr['title'] = (string)$order->Title;
		$orderArr['reference'] = (string)$order->OrderID;
		$orderArr['order_date'] = date_format($order->CreatedTime,"Y/m/d H:i:s");
		$orderArr['total'] = (string)$order->Total;
		$orderArr['subtotal'] = (string)$order->Subtotal;
 		$totalAttrs = $order->Total->attributes();
 		$orderArr['currency'] = (string)$totalAttrs[0];
		$orderArr['payment'] = (string)$order->PaymentMethods;
		$orderArr['agent'] = (string)$order->ShippingServiceSelected->ShippingService;
		if(isset($orderArr['note']) && $orderArr['note'] != ""){
		  $orderArr['note'] .= ";" . (string)$order->ShippingServiceSelected->ShippingService;
		}
		$orderArr['shipping'] = (string)$order->ShippingServiceSelected->ShippingServiceCost;
		$orderArr['shipped_time'] = (string)$order->ShippedTime;
		
		$count = 0;
		if(is_array($order->TransactionArray->Transaction)){
			
		    $count += $order->TransactionArray->Transaction[0]->QuantityPurchased;
			$orderArr['firstname'] = (string)$order->TransactionArray->Transaction[0]->Buyer->UserFirstName;
			$orderArr['lastname'] = (string)$order->TransactionArray->Transaction[0]->Buyer->UserLastName;
			if((string)$order->TransactionArray->Transaction[0]->Buyer->Email != "Invalid Request"){
				$orderArr['email'] = (string)$order->TransactionArray->Transaction[0]->Buyer->Email;
			}else{
				$orderArr['email'] = (string)$order->TransactionArray->Transaction[0]->Buyer->StaticAlias;
			}
			
			$orderArr['billing_firstname'] = (string)$order->TransactionArray->Transaction[0]->Buyer->UserFirstName;
			$orderArr['billing_lastname'] = (string)$order->TransactionArray->Transaction[0]->Buyer->UserLastName;
		
		}else{
		    $count += $order->TransactionArray->Transaction->Transaction->QuantityPurchased;
			$orderArr['firstname'] = (string)$order->TransactionArray->Transaction->Buyer->UserFirstName;
			$orderArr['lastname'] = (string)$order->TransactionArray->Transaction->Buyer->UserLastName;
			if((string)$order->TransactionArray->Transaction[0]->Buyer->Email != "Invalid Request"){
				$orderArr['email'] = (string)$order->TransactionArray->Transaction->Buyer->Email;
			}else{
				$orderArr['email'] = (string)$order->TransactionArray->Transaction->Buyer->StaticAlias;
			}
			
			$orderArr['billing_firstname'] = (string)$order->TransactionArray->Transaction->Buyer->UserFirstName;
			$orderArr['billing_lastname'] = (string)$order->TransactionArray->Transaction->Buyer->UserLastName;
		}
		$orderArr['qty'] = (string)$count;
		$orderArr['shipping_firstname'] = (string)$order->ShippingAddress->Name;
		$orderArr['shipping_address'] = (string)$order->ShippingAddress->Street1;
		$orderArr['shipping_address2'] = (string)$order->ShippingAddress->Street2;
		$orderArr['shipping_city'] = (string)$order->ShippingAddress->CityName;
		$orderArr['shipping_state'] = (string)$order->ShippingAddress->StateOrProvince;
		$orderArr['shipping_country'] = (string)$order->ShippingAddress->Country;
		$orderArr['shipping_telephone'] = (string)$order->ShippingAddress->Phone;
		$orderArr['shipping_postcode'] = (string)$order->ShippingAddress->PostalCode;

		$orderArr['status'] = (string)$order->OrderStatus;
		
		return $orderArr;
	}
	
	public static function toItemLines($items,$orderId=""){
	
		$itemLineArrs = array();
	
		if(sizeof($items->TransactionArray->Transaction) > 0){
			foreach($items->TransactionArray->Transaction as $item){
	
				if($orderId != "" && $orderId != $item->ContainingOrder->OrderID){
					continue;
				}
	
				$itemLineArr = array();
				$itemLineArr['sku_id'] = (string)$item->Variation->SKU;
				$itemLineArr['qty'] = (string)$item->QuantityPurchased;
				$itemLineArr['price'] = (string)$item->TransactionPrice;
				$itemLineArr['total'] = (string)$item->TransactionPrice;
				$itemLineArr['description'] = (string)$item->Variation->VariationTitle;
	
				$itemLineArrs[] = $itemLineArr;
			}
		}
	
		return $itemLineArrs;
	}
	public static function toItemLine($order){
		
		$itemLineArrs = array();

		$TransactionArray = get_object_vars($order->TransactionArray);

		if(is_array($TransactionArray)){

			foreach($TransactionArray as $tran){
				
				$itemLineArr = array();
				
				if(is_array($tran)){
					foreach ($tran as $Transaction){
						$itemLineArr['sku_id'] = (string)$Transaction->Variation->SKU;
						$itemLineArr['qty'] = (string)$Transaction->QuantityPurchased;
						$itemLineArr['price'] = (string)$Transaction->TransactionPrice;
						$itemLineArr['total'] = (string)$Transaction->TransactionPrice;
						$itemLineArr['description'] = (string)$Transaction->Item->Title;
						$itemLineArrs[] = $itemLineArr;
					}
				}else{
					
					$itemLineArr['sku_id'] = (string)$tran->Variation->SKU;
					$itemLineArr['qty'] = (string)$tran->QuantityPurchased;
					$itemLineArr['price'] = (string)$tran->TransactionPrice;
					$itemLineArr['total'] = (string)$tran->TransactionPrice;
					$itemLineArr['description'] = (string)$tran->Item->Title;
					
					$itemLineArrs[] = $itemLineArr;
					
				}

			}
		}
		
		return $itemLineArrs;
	}
	public static function toChannelListing($listing){
	
		$listArrs = array();

		if(is_array($listing->Variations->Variation)){

			foreach($listing->Variations->Variation as $list){
				
				$listArr = array();
				
				$listArr['sku_id'] = (string)$list->SKU;
				$listArr['reference'] = (string)$listing->ItemID;
				$listArr['quantity'] = (string)$list->Quantity;
				$listArr['sold'] = (string)$list->SellingStatus->QuantitySold;
				$listArr['price'] = (string)$list->SellingStatus->CurrentPrice;
				$listArr['url'] = (string)$listing->ListingDetails->ViewItemURL;
				$listArr['start_date'] = (string)date("Y-m-d H:i:s",strtotime($listing->ListingDetails->StartTime));
				$listArr['end_date'] = (string)date("Y-m-d H:i:s",strtotime($listing->ListingDetails->EndTime));
				$listArr['status'] = (string)$listing->SellingStatus->ListingStatus;

				$listArrs[] = $listArr;
			}
		}else{

			$listArr = array();
				
			$listArr['sku_id'] = (string)$listing->Variations->Variation->SKU;
			$listArr['reference'] = (string)$listing->ItemID;
			$listArr['quantity'] = (string)$listing->Variations->Variation->Quantity;
			$listArr['sold'] = (string)$listing->Variations->Variation->SellingStatus->QuantitySold;
			$listArr['price'] = (string)$listing->SellingStatus->CurrentPrice;
			$listArr['url'] = (string)$listing->ListingDetails->ViewItemURL;
			$listArr['start_date'] = (string)date("Y-m-d H:i:s",strtotime($listing->ListingDetails->StartTime));
			$listArr['end_date'] = (string)date("Y-m-d H:i:s",strtotime($listing->ListingDetails->EndTime));
			$listArr['status'] = (string)$listing->SellingStatus->ListingStatus;
			
			$listArrs[] = $listArr;

		}
		
		return $listArrs;
	}
	
	public static function toMessage($msg){
		
		$msgArr = array();
		
		$msgArr['message_date'] = (string)$msg->CreationDate;
		$msgArr['status'] = (string)$msg->MessageStatus;
		$msgArr['subject'] = (string)$msg->Question->Subject;
		$msgArr['body'] = (string)$msg->Question->Body;
		$msgArr['public'] = (string)$msg->Question->DisplayToPublic;
		$msgArr['sender'] = (string)$msg->Question->SenderID;
		$msgArr['recipient'] = (string)$msg->Question->RecipientID;
		$msgArr['item_id'] = (string)$msg->Item->ItemID;
		$msgArr['item_title'] = (string)$msg->Item->Title;
		$msgArr['item_start'] = (string)$msg->Item->ListingDetails->StartTime;
		$msgArr['item_end'] = (string)$msg->Item->ListingDetails->EndTime;
		$msgArr['item_url'] = (string)$msg->Item->ListingDetails->ViewItemURL;
		$msgArr['item_seller'] = (string)$msg->Item->Seller;
		$msgArr['item_price'] = (string)$msg->Item->SellingStatus->CurrentPrice;
		
		return $msgArr;
		
	}
}