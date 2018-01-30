<?php
// Put this code in first line of web page.

WriteLog("Logged Out Successfully");

session_destroy();

//header('location:'. site_url("login"));

/*
echo '<script type="text/javascript">
    window.open("http://www.ultexa.com/");
    self.close();
</script>';*/

redirect(site_url("login"), false);

function WriteLog($log)
{
	$hostname_conn = "localhost";
	$database_conn = "as_suppdb";
	$username_conn = "root";
	$password_conn = "";
	$conn = mysql_pconnect($hostname_conn, $username_conn, $password_conn) or trigger_error(mysql_error,E_USER_ERROR); 

	$today=date("Y-m-d");$tm=date("H:i:s");
	mysql_select_db($database_conn);
	LogDetails($database_conn,$log,$_SESSION['username'],$tm,$tm);	
}

function LogDetails($database_conn,$activity,$username,$logintime,$logtime)
{
	$hostname_conn = "localhost";
	$database_conn = "as_suppdb";
	$username_conn = "root";
	$password_conn = "";
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
		$name = trim($row_rs['surname'].', '.$row_rs['othernames']);
		$len=strlen("LOGGED OUT SUCCESSFULLY");
		
		if (substr(strtoupper(trim($activity)),0,$len)=="LOGGED OUT") //Logging Out
		{
			$sql="UPDATE loginfo SET logouttime='".$tm."',logoutdate='".$today."' WHERE id=".$_Session['id'];
			$rslogout=mysql_query($sql) or die(mysql_error());
		}elseif (!$_Session['id']) //Insert Login
		{//LogDate,LoginDate,LoginTime,Name,Activity,ActionDate,ActionTime,Username,LogOutDate,LogOutTime,Operation
			$sql="INSERT INTO loginfo(LogDate,LoginDate,LoginTime,Name,Activity,ActionDate,ActionTime,Username,LogOutDate,LogOutTime,Operation)
    				VALUES('".$today."','".$today."','".$logintime."','".$name."','".$activity."','".$today."','".$logtime."','".$username."','".$today."','".$tm."','"."LOGOUT"."')";
			if (mysql_query($sql) or die(mysql_error()))
			{
				$sql="SELECT id FROM loginfo WHERE (username='".$username."') AND (logdate='".$today."') AND (logintime='".$logintime."')";
				$rslogin=mysql_query($sql) or die(mysql_error());
				$row_rslogin = mysql_fetch_assoc($rslogin);	
				$_SESSION['id']=$row_rslogin['id'];
				//$GLOBALS['logID']=$row_rslogin['logID'];
			}
		}else  //Update
		{
			If (substr(strtoupper(trim($activity)),0,$len)=="LOGGED OUT SUCCESSFULLY") //Logging Out
			{
				$sql="UPDATE loginfo SET logouttime='".$tm."',logoutdate='".$today."' WHERE id=".$_Session['id'];
				$rslogout=mysql_query($sql) or die(mysql_error());
			}else //Other activity log
			{
				$sql="INSERT INTO loginfo(LogDate,LoginDate,LoginTime,Name,Activity,ActionDate,ActionTime,Username,LogOutDate,LogOutTime,Operation)
    				VALUES('".$today."','".$today."','".$logintime."','".$name."','".$activity."','".$today."','".$logtime."','".$username."','".$today."','".$tm."','"."LOGOUT"."')";
				$r=mysql_query($sql) or die(mysql_error());
			}
		}
	}          
}

?>