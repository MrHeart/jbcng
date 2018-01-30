<?php
	
	function GetStateFromCode($conn,$code)
	{
		if (!$code) return '';
		if (!$conn) return $code;
				
		$sql = "SELECT state FROM states WHERE (TRIM(code)='".$code."') LIMIT 0,1";	
		$result = mysql_query($sql, $conn); 
		$row = mysql_fetch_array($result);
		
		if ($row['state']) return ucwords(strtolower(trim($row['state']))); else return trim($code);
	}
	
	
?>