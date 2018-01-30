<?php
	session_start();//Continue the session

	//Make sure that the input come from a posted form. Otherwise quit immediately
	if ($_SERVER["REQUEST_METHOD"] <> "POST") die("<h1 style='text-decoration:blink; color:#c00;'>Unauthorized Access!</h1>");
	
	error_reporting(E_ERROR);
	
	include('loginfo.inc');
	require_once('Connections/conn.php');

	if  (isset($_POST["action"]))
	{
		$company=''; $address=''; $phone=''; $email=''; $interswitch_payment_url=''; $interswitch_REST_url=''; $interswitch_product_id=''; $interswitch_pay_item_id=''; $interswitch_currency=''; $interswitch_mac_key=''; 
		$action ='';
		
		$action = trim($_POST["action"]);

		if (isset($_POST["company"])) $company = ucwords(trim($_POST["company"]));
		if (isset($_POST["address"])) $address = trim($_POST["address"]);
		if (isset($_POST["phone"])) $phone = trim($_POST["phone"]);
		if (isset($_POST["email"])) $email = trim($_POST["email"]);
		if (isset($_POST["interswitch_payment_url"])) $interswitch_payment_url = trim($_POST["interswitch_payment_url"]);
		if (isset($_POST["interswitch_REST_url"])) $interswitch_REST_url = trim($_POST["interswitch_REST_url"]);
		if (isset($_POST["interswitch_product_id"])) $interswitch_product_id = trim($_POST["interswitch_product_id"]);
		if (isset($_POST["interswitch_pay_item_id"])) $interswitch_pay_item_id = trim($_POST["interswitch_pay_item_id"]);
		if (isset($_POST["interswitch_currency"])) $interswitch_currency = trim($_POST["interswitch_currency"]);
		if (isset($_POST["interswitch_mac_key"])) $interswitch_mac_key = ucwords(trim($_POST["interswitch_mac_key"]));
	;
				
		mysql_select_db($database_conn, $conn);
		
		if (trim(strtoupper($action))=='LOAD_CURRENCIES')
		{
			$sql = "SELECT currencycode,currency FROM currencies ORDER BY currency";
			
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
		}elseif (trim(strtoupper($action))=='LOAD_GENERAL')
		{
			$sql = "SELECT * FROM parameters";
			
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
		}elseif (trim(strtoupper($action))=='UPDATE_INFO')
		{
			//Check if record exists
			mysql_select_db($database_conn, $conn);
			$sql = "SELECT * FROM parameters";
			
			$rs = mysql_query($sql, $conn) or die(mysql_error());
			$row_rs = mysql_fetch_assoc($rs);
			$totalRows_rs = mysql_num_rows($rs);
			
			if ($totalRows_rs>0){//Update
				$cm=$row_rsdevice['company']; 			$ad=$row_rsdevice['address'];
				$ph=$row_rsdevice['phone'];				$em=$row_rsdevice['email'];
				$qm=$row_rsdevice['interswitch_payment_url'];			$purl=$row_rsdevice['interswitch_REST_url'];
				$pdb=$row_rsdevice['interswitch_product_id'];	$pun=$row_rsdevice['interswitch_pay_item_id'];
				$ppwd=$row_rsdevice['interswitch_currency'];		$mk=$row_rsdevice['interswitch_mac_key'];
								
				$sql_edit = "UPDATE parameters SET company='".$company."',address='".$address."',phone='".$phone."',email='".$email."',interswitch_payment_url='".$interswitch_payment_url."',interswitch_REST_url='".$interswitch_REST_url."',interswitch_product_id='".$interswitch_product_id."',interswitch_pay_item_id='".$interswitch_pay_item_id."',interswitch_currency='".$interswitch_currency."',interswitch_mac_key='".$interswitch_mac_key."'";
	
				if (mysql_query($sql_edit,$conn))
				{
					if ($company)$_SESSION['company'] = $company;
					if ($address)$_SESSION['address'] = $address;
					if ($phone)$_SESSION['phone'] = $phone;
					if ($email)$_SESSION['email'] = $email;
					if ($interswitch_payment_url)$_SESSION['interswitch_payment_url'] = $interswitch_payment_url;
					if ($interswitch_REST_url)$_SESSION['interswitch_REST_url'] = $interswitch_REST_url;
					if ($interswitch_product_id)$_SESSION['interswitch_product_id'] = $interswitch_product_id;
					if ($interswitch_pay_item_id)$_SESSION['interswitch_pay_item_id'] = $interswitch_pay_item_id;
					if ($interswitch_currency)$_SESSION['interswitch_currency'] = $interswitch_currency;
					if ($interswitch_mac_key)$_SESSION['interswitch_mac_key'] = $interswitch_mac_key;
				
					WriteLog("System general information has been edited successfully. Old Values: Company => ".$cm.", Address => ".$ad.", Phone => ".$ph.", Email => ".$em.", Interswitch Payment URL => ".$qm.", Interswitch REST URL => ".$purl.", Interswitch Product ID => ".$pdb.", Interswitch Payment Item ID => ".$pun.", Interswitch Default Currency => ".$ppwd.", Interswitch MAC Key => ".$mk.". Updated values: Company => ".$company.", Address => ".$address.", Phone => ".$phone.", Email => ".$email.", Interswitch Payment URL => ".$interswitch_payment_url.", Interswitch REST URL => ".$interswitch_REST_url.", Interswitch Product ID => ".$interswitch_product_id.", Interswitch Payment Item ID => ".$interswitch_pay_item_id.", Interswitch Default Currency => ".$interswitch_currency.", Interswitch MAC Key => ".$interswitch_mac_key);	
							
					$rows[]= "General Information Record Was Edited Successfully.";					
				}else
				{
					WriteLog("Attempted editing general information record but failed: ".mysql_error());
					$rows[]= "General Information Record Could Not Be Edited: ".mysql_error();
				}
			}else//INSERT
			{#company,address,phone,email,interswitch_payment_url,interswitch_REST_url,interswitch_product_id,interswitch_pay_item_id,interswitch_currency,interswitch_mac_key
				$sql_insert = "INSERT INTO `parameters`(company,address,phone,email,interswitch_payment_url,interswitch_REST_url,interswitch_product_id,interswitch_pay_item_id,interswitch_currency,interswitch_mac_key) VALUES('".$company."','".$address."','".$phone."','".$email."','".$interswitch_payment_url."','".$interswitch_REST_url."','".$interswitch_product_id."','".$interswitch_pay_item_id."','".$interswitch_currency."','".$interswitch_mac_key."')";

#app_path = $_SERVER['DOCUMENT_ROOT']   =>  serverport => $_SERVER['SERVER_PORT']
				if (mysql_query($sql_insert,$conn))
				{
					if ($company)$_SESSION['company'] = $company;
					if ($address)$_SESSION['address'] = $address;
					if ($phone)$_SESSION['phone'] = $phone;
					if ($email)$_SESSION['email'] = $email;
					if ($interswitch_payment_url)$_SESSION['interswitch_payment_url'] = $interswitch_payment_url;
					if ($interswitch_REST_url)$_SESSION['interswitch_REST_url'] = $interswitch_REST_url;
					if ($interswitch_product_id)$_SESSION['interswitch_product_id'] = $interswitch_product_id;
					if ($interswitch_pay_item_id)$_SESSION['interswitch_pay_item_id'] = $interswitch_pay_item_id;
					if ($interswitch_currency)$_SESSION['interswitch_currency'] = $interswitch_currency;
					if ($interswitch_mac_key)$_SESSION['interswitch_mac_key'] = $interswitch_mac_key;
										
					WriteLog("General information record was added successfully.");
					$rows[] = "General Information Record Has Been Added Successfully.";
				}else
				{
					WriteLog("Attempted adding general information record but failed: ".mysql_error());
					$rows[] = "General Information Record Could Not Be Added: ".mysql_error();
				}				
			}
		}elseif ($action=='DELETE_INFO')//DELETE State Record
		{
			mysql_select_db($database_conn, $conn);
			$sql = "SELECT * FROM parameters";
			
			$rsTT = mysql_query($sql, $conn) or die(mysql_error());
			$row_rsdevice = mysql_fetch_assoc($rsTT);
			$totalRows_rsdevice = mysql_num_rows($rsTT);
			
			if ($totalRows_rsdevice>0)//Record Exists - delete
			{
				$ad=$row_rsdevice['address']; 	$nm=$row_rsdevice['company'];				
				
				$sql_delete = "DELETE FROM parameters";
									
				if (mysql_query($sql_delete,$conn))
				{
					WriteLog("General information has been deleted successfully from the database.");			
					$rows[]= "General Information Record Was Deleted Successfully From The Database.";					
				}else
				{
					WriteLog("Attempted deleting general information from the database but failed: ".mysql_error());
					$rows[]= "General Information Record Could Not Be Deleted: ".mysql_error();
				}
			}else
			{
				$rows[]= "Could Not Delete General Information Record. Record Does Not Exist: ".mysql_error();
			}
		}
	}
	
	echo json_encode($rows);
?>