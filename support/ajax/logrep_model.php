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
				
		mysql_select_db($database_conn, $conn);
		
		if ($action=='LOAD_USERS')
		{
			$sql = "SELECT DISTINCT `name`,username FROM loginfo ORDER BY name";
			
			if (trim($crit)!='') $sql .= $crit;
				
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
		}
	}
	
	echo json_encode($rows);
?>