<?php	
try
{
	$dbname='D:\Javedi Network Service\Uniuyo\Jamb DB\jamb2007.accdb';
	$uid='Admin';
	$pwd='';
	
	$conn = new PDO("odbc:Driver={Microsoft Access Driver (*.mdb, *.accdb)};Dbq=".$dbname.";Uid=".$uid."Pwd=".$dbname);
	 
	$sql = "SELECT * FROM jambresult";
	$q = $conn->query($sql) or die("failed!");
		
	while($r = $q->fetch(PDO::FETCH_ASSOC)):
		echo 'Reg.No: '.$r['RegNumb'].' => Candidate Name: '.$r['CandName'].'<br>';
	endwhile;
}
catch (PDOException $e)
{
   	echo $e->getMessage();
}
	
	
?>