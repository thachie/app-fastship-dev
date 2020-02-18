<?php

function getCustomer($customerId){
	//require("connect.php");
	try
	{
		if(!empty($customerId)){
			/*$sql = "SELECT CUST_ID,CUST_FIRSTNAME,CUST_LASTNAME,CUST_EMAIL FROM customer where CUST_ID = '$cusID' ORDER BY CUST_ID DESC LIMIT 1";
			$query = mysqli_query($conn,$sql);
			$num_rows = mysqli_num_rows($query);
			if($num_rows > 0){
				$row = mysqli_fetch_assoc($query);
				return $row;
			}else{
				$row = null;
				return $row;
			}*/
			//$customerId=9999;
			//$customerObj = DB::table('customer')->where("CUST_ID",$customerId)->where("IS_ACTIVE",1)->first();
			$customerObj = DB::table('customer')
                ->select("CUST_ID","CUST_FIRSTNAME","CUST_LASTNAME","CUST_EMAIL")
                ->where('CUST_ID', $customerId)
                ->where("IS_ACTIVE",1)
                ->first();
            return $customerObj;
		}else{
			$customerObj = null;
			return $customerObj;
		} 
	}catch(Exception $e){
		echo 'Error -- $e';
	}
	
	//mysqli_close($conn);
}

function createCustomerPayment($data=array()){
	//require("connect.php");
	date_default_timezone_set("Asia/Bangkok");
	$CREATE_DATETIME = date("Y-m-d H:i:s");
	try
	{
		if (!empty($data)){
			$CUST_ID = $data['CUST_ID'];
			$OMISE_ID = $data['OMISE_ID'];
			$OMISE_CARD = $data['OMISE_CARD'];
			$OMISE_CARDNAME = $data['OMISE_CARDNAME'];
			$OMISE_CARDTYPE = $data['OMISE_CARDTYPE'];
			$OMISE_LASTDIGITS = $data['OMISE_LASTDIGITS'];
			$OMISE_EXPIRE = $data['OMISE_EXPIRE'];
			$OMISE_BANK = $data['OMISE_BANK'];
			$OMISE_COUNTRY = $data['OMISE_COUNTRY'];
			$OMISE_DESC = $data['OMISE_DESC'];
			$IS_ACTIVE = $data['IS_ACTIVE'];
			$NUMBER = $data['NUMBER'];
			$CVV = $data['CVV'];
			
			$sql_add = "INSERT INTO omise_customer ";
			$sql_add .= "(";
			$sql_add .= "CUST_ID, OMISE_ID ,OMISE_CARD,OMISE_CARDNAME ";
			$sql_add .= ",OMISE_CARDTYPE,NUMBER,OMISE_LASTDIGITS,CVV,OMISE_EXPIRE,OMISE_BANK ";
			$sql_add .= ",OMISE_COUNTRY,OMISE_DESC,IS_ACTIVE,CREATE_DATETIME ";
			$sql_add .= ")";
			$sql_add .= "VALUES ( ";
			$sql_add .= "'$CUST_ID','$OMISE_ID','$OMISE_CARD','$OMISE_CARDNAME' ";
			$sql_add .= ",'$OMISE_CARDTYPE','$NUMBER','$OMISE_LASTDIGITS','$CVV','$OMISE_EXPIRE','$OMISE_BANK' ";
			$sql_add .= ",'$OMISE_COUNTRY','$OMISE_DESC','$IS_ACTIVE','$CREATE_DATETIME' ";
			$sql_add .= ")";

			//alert($sql_add); 
			$obj_add = mysqli_query($conn,$sql_add);
			if( mysqli_affected_rows($conn) == 1 ){
				return 1;
				//echo 'Insert Success';
				//header("Location: ".base_url()."index.php?cus_id=$cus_id&status=success");
				//exit(0);
			}else{
				return 0;
				//echo("Error description: " . mysqli_error($conn));
				//header("Location: ".base_url()."index.php?cus_id=$cus_id&status=fail");
				//exit(0);
			}
		}else{
			$row = null;
			return $row;
		} 
	}catch(Exception $e){
		echo 'Error -- $e';
	}
	
	//mysqli_close($conn);
}

function insertToCreditBalance($ccID, $customerId){
	//require("connect.php");
	$date_Time = date("Y-m-d H:i:s");
	try
	{
		if (!empty($ccID) && !empty($customerId)){
			
			//Get Amount
			/* $sql_amount = "SELECT amount FROM credit_balance where cus_id = $cus_id ORDER BY c_id DESC LIMIT 1";
			$query_amount = mysqli_query($conn,$sql_amount);
			$row_amount = mysqli_num_rows($query_amount);
			if($row_amount > 0){
				$rs_amount = mysqli_fetch_assoc($query_amount);
				$amount_old = $rs_amount['amount'];
			}else{
				$amount_old = 0;
			} */
			$amount_old = getBalance($customerId);
			//alert($amount_old);
			//create_credit
			$sql_credit = "SELECT * FROM create_credit WHERE cc_id = $ccID and cus_id = $customerId and status = 0 ";
			$query_credit = mysqli_query($conn,$sql_credit);
			$row_credit = mysqli_num_rows($query_credit);
			if($row_credit > 0){
				while($rs_credit = mysqli_fetch_assoc($query_credit)) {
					$rs = $rs_credit;
					$tran_id = $rs_credit['tran_id'];
					$amount_sum = $rs_credit['balance_in']+$amount_old;
					$balance_in = $rs_credit['balance_in'];
					$tran_ref = $rs_credit['tran_ref'];
					$transfer_no = $rs_credit['transfer_no'];
					$tran_type = $rs_credit['tran_type'];
					$payment_method = $rs_credit['payment_method'];
					$payment_transfer = $rs_credit['payment_transfer'];
					$payment_order = $rs_credit['payment_order'];
					$file_upload = $rs_credit['file_upload'];
					$memo = $rs_credit['memo'];
					$balance_in_date = $rs_credit['balance_in_date'];
					

				}
				
				$verified = 'Complete';
				$verified_by = 'Omise';
				$status = 1;
				$create_date = $date_Time;
				
				$sql_add = "INSERT INTO credit_balance ";
				$sql_add .= "(";
				$sql_add .= "tran_id, cus_id ,cc_id,amount,balance_in ";
				$sql_add .= ",tran_ref,transfer_no,tran_type,payment_method,payment_transfer ";
				$sql_add .= ",verified,verified_by,file_upload,memo ";
				$sql_add .= ",balance_in_date,create_date ";
				$sql_add .= ")";
				$sql_add .= "VALUES ( ";
				$sql_add .= "'$tran_id','$customerId','$ccID','$amount_sum','$balance_in' ";
				$sql_add .= ",'$tran_ref','$transfer_no','$tran_type','$payment_method','$payment_transfer' ";
				$sql_add .= ",'$verified','$verified_by','$file_upload','$memo' ";
				$sql_add .= ",'$balance_in_date','$create_date' ";
				$sql_add .= ")";
				
				$obj_add = mysqli_query($conn,$sql_add);
				if( mysqli_affected_rows($conn) == 1 ){
					//echo 'Insert Success <br>';
					$sql_update = "UPDATE create_credit SET 
								verified = 'Complete',
								status = 1 ,
								approve_by = '".$verified_by."' ,
								create_date = '".$date_Time."'
								WHERE cc_id = '".$ccID."' ";

					$update = mysqli_query($conn,$sql_update);
					if ($update) {
						return 1; //INSERT to table credit_balance and UPDATE status table create_credit success
					} else {
						return 3; //INSERT to table credit_balance success and UPDATE status table create_credit fail
					}

				}else{
					return 2; //INSERT to table credit_balance fali
				}

			}else{
				return 9; //ไม่มี Reccord ใน table create_credit
			}
			
		}else{
			$row = null;
			return 8; // $ccID or $customerId is null
		} 
	}catch(Exception $e){
		echo 'Error -- $e';
	}
	
	//mysqli_close($conn);
}


function getBalance($cusID){
	//require("connect.php");
	try
	{
		if(!empty($cusID)){
			$sql_amount = "SELECT amount FROM credit_balance where cus_id = '$cusID' ORDER BY c_id DESC LIMIT 1";
			$query_amount = mysqli_query($conn,$sql_amount);
			$row_amount = mysqli_num_rows($query_amount);
			if($row_amount > 0){
				$rs_amount = mysqli_fetch_assoc($query_amount);
				$total_balance = $rs_amount['amount'];
				return $total_balance;
			}else{
				$total_balance = 0;
				return $total_balance;
			}
		}else{
			$total_balance = null;
			return $total_balance;
		} 
	}catch(Exception $e){
		echo 'Error -- $e';
	}
	
	//mysqli_close($conn);
}


function getBalance2($cusID){
	try
	{
		if($_SERVER['SERVER_NAME'] == 'localhost'){
			$serverName = "localhost";
			$userName = "root";
			$userPassword = "";
			$dbName = "fastship_dev";
		}else{
			$serverName = "52.77.249.67";
			$userName = "tae";
			$userPassword = "Fastship123";
			$dbName = "fastship_dev";

		}
		// Create connection
		$conn = new mysqli($serverName, $userName, $userPassword, $dbName);
		// Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		} else {
			if(!empty($cusID)){
				$sql = "SELECT amount FROM credit_balance where cus_id = '$cusID' ORDER BY c_id DESC LIMIT 1";
				$result = $conn->query($sql);
				if ($result->num_rows > 0) {
					while($row = $result->fetch_assoc()) {
						$amount = $row["amount"];
						return  $amount;
					}
				} else {
					return  null;
				}
			}else {
				return  null;
			}
		}
		$conn->close();
	}
	catch(Exception $e)
	{
		echo 'Error -- $e';
	}
}
?>