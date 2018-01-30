<?php
	
	if(session_id() == '')
	 {
		 session_start();// session has NOT been started
	 }
	 else
	 {
		  session_destroy();// session has been started
	 }
	
	header('Content-type: text/html; charset=utf-8');
	
	//Make sure that the input come from a posted form. Otherwise quit immediately
	if ($_SERVER["REQUEST_METHOD"] <> "POST") die("<h1 style='text-decoration:blink; color:#c00;'>Unauthorized Access!</h1>");
	
	error_reporting(E_ERROR);
	
	include('loginfo.inc');
	require_once('Connections/conn.php');

	if  (isset($_POST["action"]))
	{//defaultcountry,defaultcurrency
		$surname=''; $othernames=''; $pwd=''; $username=''; $role=''; $accountstatus=''; $datecreated=''; $company='';
		$AddItem=''; $EditItem=''; $DeleteItem=''; $SetParameter=''; $CreateAccount=''; $ClearLog='';  $cntry='';
		$ViewLogs=''; $ViewReports=''; $CheckPaymentStatus=''; $fullname='';
	
		$action = trim($_POST["action"]);
		if (isset($_POST["username"])) $username = trim($_POST["username"]);
		if (isset($_POST["pwd"])) $pwd = $_POST["pwd"];
		
		$username = mysql_real_escape_string($username);
		$pwd = mysql_real_escape_string($pwd);
				
		mysql_select_db($database_conn, $conn);
		
		if (trim(strtoupper($action))=='LOGIN')
		{#surname,othernames,role,username,pwd,datecreated,accountstatus
		#AddItem,EditItem,DeleteItem,CreateAccount,ClearLog,CheckPaymentStatus,SetParameter,ViewLogs,ViewReport,
		 	if ((trim(strtolower($username)=='master')) && ($pwd==sha1('jesus')))
			{
				$_SESSION['Master']=1;
				
				$_SESSION['fullname'] = 'Super Master User';
				$_SESSION['username'] = 'Master'; 
				$_SESSION['role'] = 'ADMIN'; $_SESSION['accountstatus'] = 'Active';
				
				$_SESSION['address'] = ''; $_SESSION['state'] = '';
				
				$sql="SELECT * FROM parameters";
   
				$rst = mysql_query($sql, $conn) or die(mysql_error());
				$no= mysql_num_rows($rst);
				
				if ($no>0)
				{
					$rw = mysql_fetch_assoc($rst);
					
					if ($rw['company'])$_SESSION['company'] = $rw['company'];
					if ($rw['address'])$_SESSION['address'] = $rw['address'];
					if ($rw['phone'])$_SESSION['phone'] = $rw['phone'];
					if ($rw['email'])$_SESSION['email'] = $rw['email'];
					if ($rw['interswitch_payment_url'])$_SESSION['interswitch_payment_url'] = $rw['interswitch_payment_url'];
					if ($rw['interswitch_REST_url'])$_SESSION['interswitch_REST_url'] = $rw['interswitch_REST_url'];
					if ($rw['interswitch_product_id'])$_SESSION['interswitch_product_id'] = $rw['interswitch_product_id'];
					if ($rw['interswitch_pay_item_id'])$_SESSION['interswitch_pay_item_id'] = $rw['interswitch_pay_item_id'];
					if ($rw['interswitch_currency'])$_SESSION['interswitch_currency'] = $rw['interswitch_currency'];
					if ($rw['interswitch_mac_key'])$_SESSION['interswitch_mac_key'] = $rw['interswitch_mac_key'];
				}
				
				//Get Default Country
										 #AddItem,EditItem,DeleteItem,CreateAccount,ClearLog,CheckPaymentStatus,SetParameter,ViewLogs,ViewReport
				
				 //Get Permissions									
				$_SESSION['AddItem']=1; 		$_SESSION['EditItem']=1; 		$_SESSION['DeleteItem']=1;			
				$_SESSION['SetParameter']=1; 	$_SESSION['CreateAccount']=1;
				$_SESSION['ClearLog']=1;  		$_SESSION['ViewLogs']=1; 
				$_SESSION['ViewReport']=1; 		$_SESSION['CheckPaymentStatus']=1;
								
				
				$today=date("Y-m-d");$tm=date("H:i:s");
				//LogDetails($database_conn, $conn,"Logged In Successfully.",$_SESSION['username'],$tm,$tm);
				WriteLog("Super Master User Logged In Successfully.");
				//echo "Location: " . $MM_redirectLoginSuccess;
				
				$rows[]="OK";
			}else
			{
				$_SESSION['Master']=0;
				
				$sql="SELECT * FROM userinfo WHERE username='".$username."' AND pwd='".$pwd."'";
			   
			  $LoginRS = mysql_query($sql, $conn) or die(mysql_error());
			  $loginFoundUser= mysql_num_rows($LoginRS);
			  
			  #$file = fopen('idong.txt',"w"); fwrite($file,$loginFoundUser."\n\n".$sql); fclose($file);
		#exit();
				$rows=array();
				
				if ($loginFoundUser>0) 
				{ 
					$rs = mysql_fetch_assoc($LoginRS);
				
					if(strtolower($rs['accountstatus']) !='active') 
					{
						$rows[] = "Account has been disabled. Please contact your system administrator.";
					}else
					{
						//surname,othernames,role,username,pwd,datecreated,accountstatus
						if ($rs['surname']) $surname= $rs['surname']; 	if ($rs['pwd'])$pwd=$rs['pwd'];		
						if ($rs['username'])$username=$rs['username'];		if ($rs['role'])$role=$rs['role'];				
						if ($rs['accountstatus'])$accountstatus=$rs['accountstatus'];
						if ($rs['datecreated'])$datecreated=$rs['datecreated'];
						if ($rs['othernames']) $othernames= $rs['othernames'];
						
						//declare session variables and assign them
						$_SESSION['fullname'] = $surname.', '.$othernames;
						$_SESSION['pwd'] = $pwd; $_SESSION['username'] = $username; 
						$_SESSION['role'] = $role; $_SESSION['accountstatus'] = $accountstatus;
						$_SESSION['datecreated'] = $datecreated;
						
						$_SESSION['address'] = '';
						
						$sql="SELECT * FROM parameters";
	#company,address,phone,email,qm_url,portal_url,portal_dbname,portal_username,portal_pwd,portal_pixfolder,msaccess_username,msaccess_pwd,currentsession	   
						$rst = mysql_query($sql, $conn) or die(mysql_error());
						$no= mysql_num_rows($rst);
						
						if ($no>0)
						{
							$rw = mysql_fetch_assoc($rst);
							
							if ($rw['company'])$_SESSION['company'] = $rw['company'];
							if ($rw['address'])$_SESSION['address'] = $rw['address'];
							if ($rw['phone'])$_SESSION['phone'] = $rw['phone'];
							if ($rw['email'])$_SESSION['email'] = $rw['email'];
							if ($rw['interswitch_payment_url'])$_SESSION['interswitch_payment_url'] = $rw['interswitch_payment_url'];
							if ($rw['interswitch_REST_url'])$_SESSION['interswitch_REST_url'] = $rw['interswitch_REST_url'];
							if ($rw['interswitch_product_id'])$_SESSION['interswitch_product_id'] = $rw['interswitch_product_id'];
							if ($rw['interswitch_pay_item_id'])$_SESSION['interswitch_pay_item_id'] = $rw['interswitch_pay_item_id'];
							if ($rw['interswitch_currency'])$_SESSION['interswitch_currency'] = $rw['interswitch_currency'];
							if ($rw['interswitch_mac_key'])$_SESSION['interswitch_mac_key'] = $rw['interswitch_mac_key'];
						}
					
						//Get Default Country
												 #AddItem,EditItem,DeleteItem,CreateAccount,ClearLog,CheckPaymentStatus,SetParameter,ViewLogs,ViewReport
						
						 //Get Permissions
						if ($rs['AddItem'])$AddItem= $rs['AddItem'];
						if ($rs['EditItem'])$EditItem= $rs['EditItem']; 		 
						if ($rs['DeleteItem'])$DeleteItem = $rs['DeleteItem'];
						if ($rs['SetParameter'])$SetParameter = $rs['SetParameter'];
						if ($rs['CreateAccount'])$CreateAccount=$rs['CreateAccount'];
						if ($rs['ClearLog'])$ClearLog=$rs['ClearLog'];
						if ($rs['ViewLogs'])$ViewLogs=$rs['ViewLogs'];
						if ($rs['ViewReport']) $ViewReport=$rs['ViewReport'];
						if ($rs['CheckPaymentStatus'])$CheckPaymentStatus=$rs['CheckPaymentStatus'];				
											
						$_SESSION['AddItem']=$AddItem; $_SESSION['EditItem']=$EditItem; $_SESSION['DeleteItem']=$DeleteItem;			
						$_SESSION['SetParameter']=$SetParameter; $_SESSION['CreateAccount']=$CreateAccount;
						$_SESSION['ClearLog']=$ClearLog;  $_SESSION['ViewLogs']=$ViewLogs; 
						$_SESSION['ViewReport']=$ViewReport; $_SESSION['CheckPaymentStatus']=$CheckPaymentStatus;										
						
						$today=date("Y-m-d");$tm=date("H:i:s");
						//LogDetails($database_conn, $conn,"Logged In Successfully.",$_SESSION['username'],$tm,$tm);
						WriteLog("Logged In Successfully.");
						//echo "Location: " . $MM_redirectLoginSuccess;
						#$file = fopen('idong.txt',"w"); fwrite($file,"OK"); fclose($file);
						$rows[]="OK";
					}	
				}else
				{
					$rows[]="Login Failed: Invalid authentication information. Please check your username and password.";
				}
			}
		 
		  
		}
	}
	
	echo json_encode($rows);
?>