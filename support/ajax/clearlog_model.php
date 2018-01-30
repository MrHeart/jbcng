<?php
	session_start();//Continue the session

	//Make sure that the input come from a posted form. Otherwise quit immediately
	if ($_SERVER["REQUEST_METHOD"] <> "POST") die("<h1 style='text-decoration:blink; color:#c00;'>Unauthorized Access!</h1>");
	
	error_reporting(E_ERROR);
	
	include('loginfo.inc');
	require_once('Connections/conn.php');

	if  (isset($_POST["action"]))
	{
		$action = strtoupper(trim($_POST["action"]));
		if (isset($_POST["username"])) $username = trim($_POST["username"]);
		if (isset($_POST["fromdate"])) $fromdate = trim($_POST["fromdate"]);
		if (isset($_POST["todate"])) $todate = trim($_POST["todate"]);
		
		mysql_select_db($database_conn, $conn);
		
		if ($action=='LOAD_USERS')
		{
			// country,company,surname,othernames,email,phone,pwd,username,role,userstatus,regdate
			$sql = "SELECT CONCAT(othernames,' ',surname,' [',username,']') AS fullname,username FROM userinfo ORDER BY othernames,surname";	
				
			$result = mysql_query($sql, $conn); 
			
			try
			{
				$rows = array(); #$s='';
				while ($row = mysql_fetch_array($result)):
					$f=''; $u='';
					
					$f=ucwords(strtolower($row['fullname']));
					$u=ucwords(strtolower($row['username']));
					
					$row['fullname']=$f;
					$row['username']=$u;
					
					if ($row) $rows[] = $row;
					
					#$s.="\n\n".$f." => ".$u;
				endwhile;
				
				#$file = fopen('idong.txt',"w"); fwrite($file, "\n".$s); fclose($file);
			}catch (Exception $e)
			{
				$rows[]=$e;
			}
		}elseif ($action=='DELETE_LOG')//DELETE Log Record
		{
			mysql_select_db($database_conn, $conn);
			$sql = "SELECT * FROM loginfo WHERE (logdate BETWEEN '".$fromdate."' AND '".$todate."')";
			
			$rsTT = mysql_query($sql, $conn) or die(mysql_error());
			$row_rsdevice = mysql_fetch_assoc($rsTT);
			$totalRows_rsdevice = mysql_num_rows($rsTT);
			
			if ($totalRows_rsdevice>0)//Record Exists - delete
			{	
				$crit='';			
				$sql_delete = "DELETE FROM loginfo WHERE (logdate BETWEEN '".$fromdate."' AND '".$todate."')";
								
				if ($username)
				{
					if (strtoupper($username)=="ALL")
					{
						$u=NULL;
					}else
					{
						$arr=explode(',',$username);
						
						if (count($arr)>0)
						{
							$u='';
							
							for($i=0; $i<count($arr); $i++):
								if ($arr[$i])
								{
									$arr[$i]=trim($arr[$i]);
									if ($u == '') $u="(TRIM(username)='". $arr[$i] . "')"; else $u .= " OR (TRIM(username)='". $arr[$i] . "')";
								}
							endfor;
						}
					}					
					
					if ($u) $crit .= " AND (". $u . ")";
				}
				
				if (trim($crit)!='') $sql_delete .= $crit;	

				if (mysql_query($sql_delete,$conn))
				{
					if ($todate!=$fromdate)
					{
						$m="Log entries record between ".$fromdate." and ".$todate." has been deleted successfully from the database.";
					}else
					{
						$m="Log entries record for ".$fromdate." has been deleted successfully from the database.";
					}
					
					WriteLog($m);			
					$rows[]= "Log Entry Records Deleted Successfully From The Database.";					
				}else
				{
					WriteLog("Attempted deleting log entry records from the database but failed: ".mysql_error());
					$rows[]= "Log Entry Records Could Not Be Deleted: ".mysql_error();
				}
			}else
			{
				$rows[]= "There Is No Log Record To Delete.";
			}
		}
	}
	
	echo json_encode($rows);
?>