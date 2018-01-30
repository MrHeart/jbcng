<?php
try
{
	$excelFile = realpath('D:/Javedi Network Service/Uniuyo/jambresult.xlsx');
	$excelDir = dirname($excelFile);
	$Sheet='Sheet1'.'$';
	$con = odbc_connect("Driver={Microsoft Excel Driver (*.xls, *.xlsx, *.xlsm, *.xlsb)};DriverId=790;Dbq=".$excelFile.";DefaultDir=".$excelDir , '', '')  or die("Connection Failed: ".odbc_error());
	
	$result = odbc_exec ($con, "SELECT * FROM [".$Sheet."]");
	
	while( $r = odbc_fetch_array($result)):
		echo 'Reg.No: '.$r['RegNumb'].' => Candidate Name: '.$r['CandName'].'<br>';
	   
	 
	 
	   // each key and value can be examined individually as well
	   #foreach($r as $key => $value)
	   #{
		#  print "<br>Key: " . $key . " Value: " . $value;
	  # }
	endwhile;
}
catch (PDOException $e)
{
   	echo $e->getMessage();
}
?>