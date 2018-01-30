<?php
	session_start();//Continue the session

	//Make sure that the input come from a posted form. Otherwise quit immediately
	if ($_SERVER["REQUEST_METHOD"] <> "POST") die("<h1 style='text-decoration:blink; color:#c00;'>Unauthorized Access!</h1>");
	
	error_reporting(E_ERROR);
	
	include('loginfo.inc');
	require_once('Connections/conn.php');

	if  (isset($_POST["action"]))
	{
		$responsecode=''; $responsedescription=''; $trans_date=''; $cardnumber=''; $paymentreference='';
				
		$action = trim($_POST["action"]);
		
		if (isset($_POST["trans_ref"])) $trans_ref = trim($_POST["trans_ref"]);
		if (isset($_POST["amount"])) $amount = trim($_POST["amount"]);
		if (isset($_POST["interswitch_product_id"])) $interswitch_product_id = trim($_POST["interswitch_product_id"]);
		if (isset($_POST["interswitch_mac_key"])) $interswitch_mac_key = trim($_POST["interswitch_mac_key"]);
		if (isset($_POST["FromDate"])) $FromDate = trim($_POST["FromDate"]);
		if (isset($_POST["ToDate"])) $ToDate = trim($_POST["ToDate"]);
		
		$support_date=date('Y-m-d H:i:s');
		$support_name=$_SESSION['fullname'];
		$support_username=$_SESSION['username'];
								
		mysql_select_db($database_conn, $conn);
		
		#$file = fopen('idong.txt',"w"); fwrite($file, "\n\nT => ".$accountstatus); fclose($file);
		
		if (trim(strtoupper($action))=='LOAD_INTERSWITCH_VALUES')
		{
			$sql="SELECT interswitch_product_id,interswitch_mac_key FROM parameters";
			
			$result = mysql_query($sql, $conn); 
			$num = mysql_num_rows($result);
			
			try
			{
				if ( $num > 0 )  //Build Array of results
				{	
					$rows = array();
					while ($row = mysql_fetch_array($result)):
						if ($row) $rows[] = $row;
					endwhile;
				}	
			}catch (Exception $e)
			{
				$rows[]=$e;
			}
		}elseif (trim(strtoupper($action))=='DISPLAY_RECORDS')
		{
			$sql = "SELECT * FROM complains WHERE (DATE_FORMAT(support_date,'%Y-%m-%d') BETWEEN '".$FromDate."' AND '".$ToDate."') ORDER BY support_date DESC,trans_ref";
			
			$result = mysql_query($sql, $conn); 
			$num = mysql_num_rows($result);
			#$file = fopen('idong.txt',"w"); fwrite($file, $sql); fclose($file);
			try
			{
				if ( $num > 0 )  //Build Array of results
				{	
					$rows = array();
					while ($row = mysql_fetch_array($result)):
						if ($row) $rows[] = $row;
					endwhile;
				}	
			}catch (Exception $e)
			{
				$rows[]=$e;
			}
		}elseif (trim(strtoupper($action))=='ADD_TRANSACTION')
		{
			$qry="SELECT * FROM parameters";
   
			$rst = mysql_query($qry, $conn) or die(mysql_error());
			$rw = mysql_fetch_assoc($rst);

			if ($rw['interswitch_payment_url'])$interswitch_payment_url = $rw['interswitch_payment_url'];
			if ($rw['interswitch_REST_url'])$interswitch_REST_url = $rw['interswitch_REST_url'];
			if ($rw['interswitch_product_id'])$interswitch_product_id = $rw['interswitch_product_id'];
			if ($rw['interswitch_pay_item_id'])$interswitch_pay_item_id = $rw['interswitch_pay_item_id'];
			if ($rw['interswitch_currency'])$interswitch_currency = $rw['interswitch_currency'];
			if ($rw['interswitch_mac_key'])$interswitch_mac_key = $rw['interswitch_mac_key'];
						
#{"Amount":300000,"CardNumber":"6055","MerchantReference":"8421941122","PaymentReference":"ZIB|WEB|VNA|15-10-2012|015933", "RetrievalReferenceNumber":"000000538268", "LeadBankCbnCode":null, "LeadBankName":null, "SplitAccounts":[],"TransactionDate":"2012-10-15T11:07:54.547","ResponseCode":"00","ResponseDescription":"Approved Successful"}
			
			#Call CheckPaymentStatus Function		
			$json=CheckPaymentStatus($interswitch_mac_key,$interswitch_product_id,$trans_ref,$interswitch_REST_url,$amount);
			
			if ($json !='BAD')
			{
				if ($json['CardNumber']) $cardnumber=$json['CardNumber'];
				if ($json['ResponseCode']) $responsecode=$json['ResponseCode'];
				if ($json['ResponseDescription']) $responsedescription=$json['ResponseDescription'];
				if ($json['TransactionDate']) $trans_date=$json['TransactionDate'];
				if ($json['PaymentReference']) $paymentreference=$json['PaymentReference'];
						#$file = fopen('idong.txt',"w"); fwrite($file, $json['MerchantReference']); fclose($file);		
				$sql_insert = "UPDATE complains SET amount='$amount', responsecode='$responsecode', responsedescription='$responsedescription', trans_date='$trans_date', cardnumber='$cardnumber', paymentreference='$paymentreference', support_date=NOW() WHERE trans_ref='$trans_ref'"; 
											
				if (mysql_query($sql_insert,$conn))
				{
					WriteLog("Added resolved complain record for transaction with reference number ".$trans_ref." and  amount ".$amount." successfully. Transaction status: CODE => ".$responsecode."; CODE DESCRIPTION => ".$responsedescription."; PAYMENT REREFENCE => ".$paymentreference);
					$rows[] = "User Record Has Been Updated Successfully.";
				}else
				{
					WriteLog("Attempted resolving complain record for transaction with reference number ".$trans_ref." and  amount ".$amount." but failed: ".mysql_error());
					$rows[] = "Complain: Record Could Not Be Added - ".mysql_error();
				}
			}else
			{
				#$file = fopen('idong.txt',"w"); fwrite($file, $json); fclose($file);
				WriteLog("Attempted resolving complain record for transaction with reference number ".$trans_ref." and  amount ".$amount." but failed: ".mysql_error());
				$rows[] = "Complain Record Could Not Be Added: ".mysql_error();
			}
		}
	}
	
	echo json_encode($rows);
	
	function CheckPaymentStatus($interswitch_mac_key,$interswitch_product_id,$trans_ref,$resturl,$amount)
	{
		$hash_string = $interswitch_product_id.$trans_ref.$interswitch_mac_key;
		$hash = hash('sha512', $hash_string);
		
		$amount=$amount*100;
		
		//Using the json Api
		//Add two zeros to amount because it must be in kobo
		$url = $resturl."?productid=".$interswitch_product_id."&transactionreference=".$trans_ref."&amount=".$amount;
	
		$ch = curl_init($url);
		curl_setopt($ch,CURLOPT_HTTPHEADER,array("Hash: ".$hash, "UserAgent: Mozilla/4.0 compatible; MSIE 6.0; MS Web Services Client Protocol 4.0.30319.239"));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);			
		curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__)."/cacert.pem");
		
		$response = curl_exec($ch);
		
		#$err='';			
		#$a = curl_getinfo($ch); $t=''; foreach($a as $v): $t .= $v."\n"; endforeach;				
		#$err .= $t."\n\n".curl_errno($ch)."\n\n"; $err .= curl_error($ch)."\n\n";
		#$file = fopen('curl_error.txt',"w"); fwrite($file,$err); fclose($file);
		
		curl_close($ch);
	
		if($response)
		{    
			$response_array = json_decode($response,true);
						
			return $response_array;
			
			#{"Amount":300000,"CardNumber":"6055","MerchantReference":"8421941122","PaymentReference":"ZIB|WEB|VNA|15-10-2012|015933", "RetrievalReferenceNumber":"000000538268", "LeadBankCbnCode":null, "LeadBankName":null, "SplitAccounts":[],"TransactionDate":"2012-10-15T11:07:54.547","ResponseCode":"00","ResponseDescription":"Approved Successful"}
		}else
		{
			return "BAD";
		}
	}
?>
