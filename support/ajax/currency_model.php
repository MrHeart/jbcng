<?php
	session_start();//Continue the session

	//Make sure that the input come from a posted form. Otherwise quit immediately
	if ($_SERVER["REQUEST_METHOD"] <> "POST") die("<h1 style='text-decoration:blink; color:#c00;'>Unauthorized Access!</h1>");
	
	error_reporting(E_ERROR);
	
	include('loginfo.inc');
	require_once('Connections/conn.php');

	if  (isset($_POST["action"]))
	{
		$action = trim($_POST["action"]);
		
		if (isset($_POST["currency"])) $currency = ucwords(trim($_POST["currency"]));
		if (isset($_POST["currencycode"])) $currencycode = strtoupper(trim($_POST["currencycode"]));
		if (isset($_POST["id"])) $id = trim($_POST["id"]);
		
		mysql_select_db($database_conn, $conn);
		
		if (trim(strtoupper($action))=='LOAD_CURRENCIES')
		{
			$sql = "SELECT currencycode,currency,id FROM currencies ORDER BY currency";
			
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
		}elseif (trim(strtoupper($action))=='ADD_CURRENCY')
		{
			//Check if record exists
			$sql = "SELECT * FROM currencies WHERE (TRIM(currency)='".$currency . "')";
			
			$rs = mysql_query($sql, $conn) or die(mysql_error());
			$totalRows_rs = mysql_num_rows($rs);
			
			if ($totalRows_rs>0){
				$rows[]='Currency "'.strtoupper($currency).'" exists in the database.';
			}else
			{
				if (trim($currencycode) != '')
				{
					#check currencycode
					$sql = "SELECT * FROM currencies WHERE (TRIM(currencycode)='".$currencycode . "')";
				
					$rst = mysql_query($sql, $conn) or die(mysql_error());
					$rw = mysql_fetch_assoc($rst);
					$nn = mysql_num_rows($rst);
					
					if ($nn>0)
					{
						$rows[]='Currency code "'.strtoupper($currencycode).'" has been used for the currency '.strtoupper($rw['currency']).' in the database.';
					}else
					{
						$sql_insert = "INSERT INTO `currencies`(currencycode,currency) VALUES('".$currencycode."','".$currency."')";
												
						if (mysql_query($sql_insert,$conn))
						{
							WriteLog("Currency ".strtoupper($currency)." with code ".strtoupper($currencycode)." was added successfully.");
							$rows[] = "Currency Record Has Been Added Successfully.";
						}else
						{
							WriteLog("Attempted adding currency ".strtoupper($currency)." with code ".strtoupper($currencycode)." but failed: ".mysql_error());
							$rows[] = "Currency Record Could Not Be Added: ".mysql_error();
						}
					}	
				}else
				{
					$sql_insert = "INSERT INTO `currencies`(currencycode,currency) VALUES('".$currencycode."','".$currency."')";
												
					if (mysql_query($sql_insert,$conn))
					{
						WriteLog("Currency ".strtoupper($currency)." with code ".strtoupper($currencycode)." was added successfully.");
						$rows[] = "Currency Record Has Been Added Successfully.";
					}else
					{
						WriteLog("Attempted adding currency ".strtoupper($currency)." with code ".strtoupper($currencycode)." but failed: ".mysql_error());
						$rows[] = "Currency Record Could Not Be Added: ".mysql_error();
					}
				}
							
			}
		}elseif ($action=='EDIT_CURRENCY')//EDIT Currency Record
		{
			mysql_select_db($database_conn, $conn);
			$sql = "SELECT * FROM currencies WHERE id=".$id;
			
			$rsTT = mysql_query($sql, $conn) or die(mysql_error());
			$row_rsdevice = mysql_fetch_assoc($rsTT);
			$totalRows_rsdevice = mysql_num_rows($rsTT);
			
			if ($totalRows_rsdevice>0)//Record Exists - Edit
			{
				if (trim($currencycode) != '')
				{
					#check currencycode
					$sql = "SELECT * FROM currencies WHERE (TRIM(currencycode)='".$currencycode . "')";
				
					$rst = mysql_query($sql, $conn) or die(mysql_error());
					$rw = mysql_fetch_assoc($rst);
					$nn = mysql_num_rows($rst);
					
					if ($nn>0)
					{
						if (strtolower(trim($rw['currency'])) != strtolower(trim($currency)))
						{
							$rows[]='Currency code "'.strtoupper($currencycode).'" has been used for the currency '.strtoupper($rw['currency']).' in the database.';
						}else
						{
							$sql = "SELECT * FROM currencies WHERE id=".$id;
				
							$rst = mysql_query($sql, $conn) or die(mysql_error());
							$row_rsdevice = mysql_fetch_assoc($rst);
														
							$cd=$row_rsdevice['currencycode'];
							$st=$row_rsdevice['currency'];
							
							$sql_edit = "UPDATE currencies SET currencycode='".$currencycode."',currency='".$currency."' WHERE id=".$id;
												
							if (mysql_query($sql_edit,$conn))
							{
								WriteLog("Currency has been edited successfully. Old Values: Currency Code => ".$cd.", Currency => ".$st.". Updated values: Currency Code => ".$currencycode.", Currency => ".$currency);			
								$rows[]= strtoupper($st)."'s Record Was Edited Successfully.";					
							}else
							{
								WriteLog("Attempted editing currency record but failed: ".mysql_error());
								$rows[]= "Currency Record Could Not Be Edited: ".mysql_error();
							}		
						}
					}else
					{
						$sql = "SELECT * FROM currencies WHERE id=".$id;
				
						$rst = mysql_query($sql, $conn) or die(mysql_error());
						$row_rsdevice = mysql_fetch_assoc($rst);
						$cd=$row_rsdevice['currencycode'];
						$st=$row_rsdevice['currency'];
						
						$sql_edit = "UPDATE currencies SET currencycode='".$currencycode."',currency='".$currency."' WHERE id=".$id;
											
						if (mysql_query($sql_edit,$conn))
						{
							WriteLog("Currency has been edited successfully. Old Values: Currency Code => ".$cd.", Currency => ".$st.". Updated values: Currency Code => ".$currencycode.", Currency => ".$currency);			
							$rows[]= strtoupper($st)."'s Record Was Edited Successfully.";					
						}else
						{
							WriteLog("Attempted editing currency record but failed: ".mysql_error());
							$rows[]= "Currency Record Could Not Be Edited: ".mysql_error();
						}	
					}	
				}else
				{
					$sql = "SELECT * FROM currencies WHERE id=".$id;
				
					$rst = mysql_query($sql, $conn) or die(mysql_error());
					$row_rsdevice = mysql_fetch_assoc($rst);
							
					$cd=$row_rsdevice['currencycode'];
					$st=$row_rsdevice['currency'];
					
					$sql_edit = "UPDATE currencies SET currencycode='',currency='".$currency."' WHERE id=".$id;
										
					if (mysql_query($sql_edit,$conn))
					{
						WriteLog("Currency has been edited successfully. Old Values: Currency Code => ".$cd.", Currency => ".$st.". Updated values: Currency Code => ".$currencycode.", Currency => ".$currency);			
						$rows[]= strtoupper($st)."'s Record Was Edited Successfully.";					
					}else
					{
						WriteLog("Attempted editing currency record but failed: ".mysql_error());
						$rows[]= "Currency Record Could Not Be Edited: ".mysql_error();
					}	
				}
			}else
			{
				$rows[]= "Could Not Edit Currency Record. Record Does Not Exist: ".mysql_error();
			}
		}elseif ($action=='DELETE_CURRENCY')//DELETE Currency Record
		{
			mysql_select_db($database_conn, $conn);
			$sql = "SELECT * FROM currencies WHERE id=".$id;
			
			$rsTT = mysql_query($sql, $conn) or die(mysql_error());
			$row_rsdevice = mysql_fetch_assoc($rsTT);
			$totalRows_rsdevice = mysql_num_rows($rsTT);
			
			if ($totalRows_rsdevice>0)//Record Exists - delete
			{
				$cd=$row_rsdevice['currencycode'];
				$st=$row_rsdevice['currency'];
				
				$sql_delete = "DELETE FROM currencies WHERE id=".$id;
									
				if (mysql_query($sql_delete,$conn))
				{
					WriteLog("Currency ".strtoupper($st)." has been deleted successfully from the database.");			
					$rows[]= "Currency Record Was Deleted Successfully From The Database.";					
				}else
				{
					WriteLog("Attempted deleting currency from the database but failed: ".mysql_error());
					$rows[]= "Currency Record Could Not Be Deleted: ".mysql_error();
				}
			}else
			{
				$rows[]= "Could Not Delete Currency Record. Record Does Not Exist: ".mysql_error();
			}
		}
	}
	
	echo json_encode($rows);
?>