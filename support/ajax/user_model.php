<?php
	session_start();//Continue the session

	//Make sure that the input come from a posted form. Otherwise quit immediately
	if ($_SERVER["REQUEST_METHOD"] <> "POST") die("<h1 style='text-decoration:blink; color:#c00;'>Unauthorized Access!</h1>");
	
	error_reporting(E_ERROR);
	
	include('loginfo.inc');
	require_once('Connections/conn.php');
//surname,othernames,role,username,pwd,datecreated,accountstatus,AddItem,EditItem,DeleteItem,CreateAccount,ClearLog,CheckPaymentStatus,SetParameter,ViewLogs,ViewReport

	if  (isset($_POST["action"]))
	{
		$action = trim($_POST["action"]);
		
		if (isset($_POST["othernames"])) $othernames = ucwords(trim($_POST["othernames"]));
		if (isset($_POST["surname"])) $surname = ucwords(trim($_POST["surname"]));
		if (isset($_POST["pwd"])) $pwd = trim($_POST["pwd"]);
		if (isset($_POST["username"])) $username = trim($_POST["username"]);
		if (isset($_POST["role"])) $role = ucwords(trim($_POST["role"]));
		if (isset($_POST["accountstatus"])) $accountstatus = ucwords(trim($_POST["accountstatus"]));
		$datecreated=date('Y-m-d');

#AddItem,EditItem,DeleteItem,CreateAccount,ClearLog,CheckPaymentStatus,SetParameter,ViewLogs,ViewReport
		if (isset($_POST["AddItem"])) $AddItem = trim($_POST["AddItem"]);
		if (isset($_POST["EditItem"])) $EditItem = trim($_POST["EditItem"]);
		if (isset($_POST["DeleteItem"])) $DeleteItem = trim($_POST["DeleteItem"]);
		if (isset($_POST["SetParameter"])) $SetParameter = trim($_POST["SetParameter"]);
		if (isset($_POST["CreateAccount"])) $CreateAccount = trim($_POST["CreateAccount"]);
		if (isset($_POST["ClearLog"])) $ClearLog = trim($_POST["ClearLog"]);
		if (isset($_POST["ViewLogs"])) $ViewLogs = trim($_POST["ViewLogs"]);
		if (isset($_POST["ViewReport"])) $ViewReport = trim($_POST["ViewReport"]);
		if (isset($_POST["CheckPaymentStatus"])) $CheckPaymentStatus = trim($_POST["CheckPaymentStatus"]);
								
		if (isset($_POST["id"])) $id = trim($_POST["id"]);
		
		if (!isset($AddItem)) $AddItem = "0";
		if (!isset($EditItem)) $EditItem = "0";
		if (!isset($DeleteItem)) $DeleteItem = "0";
		if (!isset($SetParameter)) $SetParameter = "0";
		if (!isset($CreateAccount)) $CreateAccount = "0";
		if (!isset($ClearLog)) $ClearLog = "0";
		if (!isset($ViewLogs)) $ViewLogs = "0";
		if (!isset($ViewReport)) $ViewReport = "0";
		if (!isset($CheckPaymentStatus)) $CheckPaymentStatus = "0";
						
		mysql_select_db($database_conn, $conn);
		
		#$file = fopen('idong.txt',"w"); fwrite($file, "\n\nT => ".$accountstatus); fclose($file);
		
		if (trim(strtoupper($action))=='LOAD_USERS')
		{
			$sql = "SELECT * FROM userinfo ORDER BY surname,othernames";
			
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
		}elseif (trim(strtoupper($action))=='ADD_USER')
		{
			//Check if record exists
			mysql_select_db($database_conn, $conn);
			$sql = "SELECT * FROM userinfo WHERE (TRIM(username)='". $username . "')";
			
			$rs = mysql_query($sql, $conn) or die(mysql_error());
			$row_rs = mysql_fetch_assoc($rs);
			$totalRows_rs = mysql_num_rows($rs);
			
			if ($totalRows_rs>0){
				$rows[]='Username "'.strtoupper($username).'" exists in the database.';
			}else
			{//othernames,surname,pwd,username,role,accountstatus,datecreated,AddItem,EditItem,DeleteItem,SetParameter,CreateAccount,ClearLog,ViewLogs,ViewReport,CheckPaymentStatus,id
				$sql_insert = "INSERT INTO `userinfo`(othernames,surname,pwd,username,role,accountstatus,datecreated,AddItem,EditItem,DeleteItem,SetParameter,CreateAccount,ClearLog,ViewLogs,ViewReport,CheckPaymentStatus) VALUES('".$othernames."','".$surname."','".$pwd."','".$username."','".$role."','".$accountstatus."','".$datecreated."',".$AddItem.",".$EditItem.",".$DeleteItem.",".$SetParameter.",".$CreateAccount.",".$ClearLog.",".$ViewLogs.",".$ViewReport.",".$CheckPaymentStatus.")";
										
				if (mysql_query($sql_insert,$conn))
				{
					WriteLog("User record with username ".strtoupper($username)."(".strtoupper($othernames.' '.$surname).")"." was added successfully.");
					$rows[] = "User Record Has Been Added Successfully.";
				}else
				{
					WriteLog("Attempted adding user record with username ".strtoupper($username)."(".strtoupper($othernames.' '.$surname).")"." but failed: ".mysql_error());
					$rows[] = "User Record Could Not Be Added: ".mysql_error();
				}				
			}
		}elseif ($action=='EDIT_USER')//EDIT Record
		{
			#$file = fopen('idong.txt',"w"); fwrite($file, "\n\nT1 => ".$accountstatus); fclose($file);
			
			$sql = "SELECT * FROM userinfo WHERE id=".$id;
			
			$rsTT = mysql_query($sql, $conn) or die(mysql_error());
			$row_rsdevice = mysql_fetch_assoc($rsTT);
			$totalRows_rsdevice = mysql_num_rows($rsTT);
			
			if ($totalRows_rsdevice>0)//Record Exists - Edit
			{//othernames,surname,pwd,username,role,accountstatus,datecreated,AddItem,EditItem,DeleteItem,SetParameter,CreateAccount,ClearLog,ViewLogs,ViewReport,CheckPaymentStatus
				$st=$row_rsdevice['othernames']; $fn=$row_rsdevice['surname'];
				$pw=$row_rsdevice['pwd']; 	$un=$row_rsdevice['username'];  	$cn=$row_rsdevice['country'];
				$rl=$row_rsdevice['role'];  $us=$row_rsdevice['accountstatus']; $rd=$row_rsdevice['datecreated'];
					
				$ai=$row_rsdevice['AddItem']; 		$ei=$row_rsdevice['EditItem']; 		$di=$row_rsdevice['DeleteItem'];
				$sp=$row_rsdevice['SetParameter'];	$ca=$row_rsdevice['CreateAccount'];	$cl=$row_rsdevice['ClearLog']; 
				$vl=$row_rsdevice['ViewLogs'];  	$vr=$row_rsdevice['ViewReport']; 	$um=$row_rsdevice['CheckPaymentStatus'];	
							
				$sql_edit = "UPDATE userinfo SET othernames='".$othernames."',surname='".$surname."',username='".$username."',role='".$role."',accountstatus='".$accountstatus."',datecreated='".$datecreated."',AddItem=".$AddItem.",EditItem=".$EditItem.",DeleteItem=".$DeleteItem.",SetParameter=".$SetParameter.",CreateAccount=".$CreateAccount.",ClearLog=".$ClearLog.",ViewLogs=".$ViewLogs.",ViewReport=".$ViewReport.",CheckPaymentStatus=".$CheckPaymentStatus." WHERE id=".$id;
				
				
				
				if (mysql_query($sql_edit,$conn))
				{
					WriteLog("User record has been edited successfully. OLD VALUES: Othernames => ".$st.", Surname => ".$fn.", Username => ".$un.", User Role => ".$rl.", Account Status => ".$us.", Creation Date => ".$rd.", Add Item => ".$ai.", Edit Item => ".$ei.", Delete Item => ".$di.", Set Parameters => ".$sp.", Create User Account => ".$ca.", Delete Log Files => ".$cl.", View Log Reports => ".$vl.", View Reports => ".$vr.", Check Payment Status => ".$um.".  UPDATED VALUES values: Othernames => ".$othernames.", Surname => ".$surname.", Username => ".$username.", User Role => ".$role.", Account Status => ".$accountstatus.", Creation Date => ".$datecreated.", Add Item => ".$AddItem.", Edit Item => ".$EditItem.", Delete Item => ".$DeleteItem.", Set Parameters => ".$SetParameter.", Create User Account => ".$CreateAccount.", Delete Log Files => ".$ClearLog.", View Log Reports => ".$ViewLogs.", View Reports => ".$ViewReport.", Check Payment Status => ".$CheckPaymentStatus);
					
					$rows[]= "User Record Was Edited Successfully.";					
				}else
				{
					WriteLog("Attempted editing user record but failed: ".mysql_error());
					$rows[]= "User Record Could Not Be Edited: ".mysql_error();
				}
			}else
			{
				$rows[]= "Could Not Edit User Record. Record Does Not Exist: ".mysql_error();
			}
		}elseif ($action=='DELETE_USER')//DELETE State Record
		{
			mysql_select_db($database_conn, $conn);
			$sql = "SELECT * FROM userinfo WHERE id=".$id;
			
			$rsTT = mysql_query($sql, $conn) or die(mysql_error());
			$row_rsdevice = mysql_fetch_assoc($rsTT);
			$totalRows_rsdevice = mysql_num_rows($rsTT);
			
			if ($totalRows_rsdevice>0)//Record Exists - delete
			{
				$cn=$row_rsdevice['surname'];  	$st=$row_rsdevice['othernames'];
				$un=$row_rsdevice['username'];	
				
				$sql_delete = "DELETE FROM userinfo WHERE id=".$id;
									
				if (mysql_query($sql_delete,$conn))
				{
					WriteLog("User record with username ".strtoupper($un)."(".strtoupper($othernames.' '.$surname).") has been deleted successfully from the database.");			
					$rows[]= "User Record Was Deleted Successfully From The Database.";					
				}else
				{
					WriteLog("Attempted deleting user from the database but failed: ".mysql_error());
					$rows[]= "User Record Could Not Be Deleted: ".mysql_error();
				}
			}else
			{
				$rows[]= "Could Not Delete User Record. Record Does Not Exist: ".mysql_error();
			}
		}
	}
	
	//if (!isset($rows)) $rows[]='';
	
	echo json_encode($rows);
?>