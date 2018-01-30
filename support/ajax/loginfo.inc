<?php
session_start();//Continue the session

error_reporting(E_ERROR);

function WriteLog($log)
{
	$hostname_conn = "localhost";
	$database_conn = "afrikcom_as_suppdb";
	$username_conn = "afrikcom_infouse";
	$password_conn = "alerta123";
	$conn = mysql_pconnect($hostname_conn, $username_conn, $password_conn) or trigger_error(mysql_error,E_USER_ERROR); 

	$today=date("Y-m-d");$tm=date("H:i:s");
	mysql_select_db($database_conn);
	LogDetails($database_conn,$log,$_SESSION['username'],$tm,$tm);	
}

function LogDetails($database_conn,$activity,$username,$logintime,$logtime)
{
	$hostname_conn = "localhost";
	$database_conn = "afrikcom_as_suppdb";
	$username_conn = "afrikcom_infouse";
	$password_conn = "alerta123";
	$conn = mysql_pconnect($hostname_conn, $username_conn, $password_conn) or trigger_error(mysql_error,E_USER_ERROR); 

	$activity = str_replace("'", "`",$activity);
    $name="";
	$company= "";
	$today=date("Y-m-d");$tm=date("H:i:s");
	
	$sql="SELECT * FROM userinfo WHERE username='".$username."'"; 
	mysql_select_db($database_conn,$conn);			
	$rs=mysql_query($sql) or die('Database Error: '.mysql_error());
	$row_rs = mysql_fetch_assoc($rs);
	$totalRows_rs = mysql_num_rows($rs);
								
	if ($totalRows_rs>0)
	{
		$name = trim($row_rs['othernames']).' '.trim($row_rs['surname']);
		
		$len=strlen("LOGGED OUT SUCCESSFULLY");
		
		if (substr(strtoupper(trim($activity)),0,$len)=="LOGGED OUT") //Logging Out
		{
			$sql="UPDATE loginfo SET logouttime='".$today." ".$tm."' WHERE id=".$_Session['id'];
			$rslogout=mysql_query($sql) or die(mysql_error());
		}elseif (!$_Session['id']) //Insert Login
		{//LogDate,LoginDate,LoginTime,Name,Activity,ActionDate,ActionTime,Username,LogOutDate,LogOutTime,Operation,id 
		#country,company,username,name,logdate,logtime,activity,logintime
			$sql="INSERT INTO loginfo(LogDate,LoginDate,LoginTime,Name,Activity,ActionDate,ActionTime,Username,Operation)
    				VALUES('".$today."','".$today."','".$logtime."','".$name."','".$activity."','".$today."','".$logtime."','".$username."','LOGIN')";
			if (mysql_query($sql) or die(mysql_error()))
			{
				$sql="SELECT id FROM loginfo WHERE (username='".$username."') AND (logdate='".$today."') AND (logintime='".$logintime."')";
				$rslogin=mysql_query($sql) or die(mysql_error());
				$row_rslogin = mysql_fetch_assoc($rslogin);	
				$_SESSION['id']=$row_rslogin['id'];				
			}
		}else  //Update
		{
			If (substr(strtoupper(trim($activity)),0,$len)=="LOGGED OUT SUCCESSFULLY") //Logging Out
			{
				$sql="UPDATE loginfo SET logouttime='".$today." ".$tm."' WHERE id=".$_Session['id'];
				$rslogout=mysql_query($sql) or die(mysql_error());
			}else //Other activity log
			{
				$sql="INSERT INTO loginfo(LogDate,LoginDate,LoginTime,Name,Activity,ActionDate,ActionTime,Username,Operation)
    				VALUES('".$today."','".$today."','".$logintime."','".$name."','".$activity."','".$logtime."','".$username."','')";
				$r=mysql_query($sql) or die(mysql_error());
			}
		}
	}          
}
?>