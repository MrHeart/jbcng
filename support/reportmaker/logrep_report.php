<?php
	session_start();//Continue the session

	//Make sure that the input come from a posted form. Otherwise quit immediately
	if ($_SERVER["REQUEST_METHOD"] <> "POST") die("<h1 style='text-decoration:blink; color:#c00;'>Unauthorized Access!</h1>");
	
	error_reporting(E_ERROR);
	ini_set('memory_limit', '512M');
	
	include('../ajax/loginfo.inc');
	require_once('../ajax/Connections/conn.php');
	require_once('config/lang/eng.php');
	require_once('tcpdf.php');
	require_once("pChart/pData.class");
	require_once("pChart/pChart.class");

	if  (isset($_POST["action"]))
	{
		$User=''; $fromdate=''; $todate='';
				
		$action = strtoupper(trim($_POST["action"]));
		
		if (isset($_POST["User"])) $User = trim($_POST["User"]);
		if (isset($_POST["fromdate"])) $fromdate = trim($_POST["fromdate"]);
		if (isset($_POST["todate"])) $todate = trim($_POST["todate"]);
		
		mysql_select_db($database_conn, $conn);
		
		//cntry,company,brand,state,projectid,batch
		
		if ($action=='DISPLAY_REPORT')
		{
			$crit=''; $u='';
					
			$sql = "SELECT LogDate,LoginDate,LoginTime,Name,Activity,ActionDate,ActionTime,Username,LogOutDate,LogOutTime,Operation FROM loginfo WHERE (TRIM(LogDate) BETWEEN '".$fromdate."' AND '".$todate."')";
							
			if ($User)
			{
				if (strtoupper($User)!= "ALL")
				{
					$arr=explode(',',$User);
					
					if (count($arr)>0)
					{						
						for($i=0; $i<count($arr); $i++):
							if ($arr[$i])
							{
								if ($u == '') $u="(TRIM(Username)='". trim($arr[$i]) . "')"; else $u .= " OR (TRIM(Username)='". trim($arr[$i]) . "')";
							}
						endfor;
					}
				}					
				
				if ($u !='') $crit .= " AND (". $u . ")";
			}
			
			if (trim($crit)!='') $sql .= $crit;
			
			$sql .= " ORDER BY LogDate,Username,Operation";
					
			$result = mysql_query($sql, $conn) or die('GET AUDIT TRAIL RECORDS ERROR: '.mysql_error());

			$TotalExpected = mysql_num_rows($result);
			
			if ($TotalExpected==0)
			{
				$rows[]="There is no audit trail for the selected query criteria";
				
				echo json_encode($rows);
			}else
			{
				$logo='africanstock.png';
				
				//Get Company Info
				$qry="SELECT * FROM parameters";
				$rsLogo = mysql_query($qry, $conn) or die('COMPANY INFORMATION RETRIEVAL ERROR: '.mysql_error());
				$recNo=mysql_num_rows($rsLogo);
				
				if ($recNo>0)
				{
					$row_rsLogo = mysql_fetch_assoc($rsLogo);
					$address=''; $company='';
					if ($row_rsLogo['company']) $company=$row_rsLogo['company'];
					if ($row_rsLogo['address']) $address=$row_rsLogo['address'];
				}			
				
/////////////////////////////////////REPORT BELOW/////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////
				//Table Headers
				$tableheader='
<table nobr="false" border="1" cellpadding="2" cellspacing="0" width="100%">	
<thead>
  <tr bgcolor="#EEEEEE">
    <th valign="middle" align="center"  width="7%"><b><font size="12pt" face="Arial">S/NO</font></b></th>
	<th align="center" valign="middle" width="10%"><b><font size="12pt" face="Arial">LOG DATE</font></b></th>
    <th align="center" valign="middle" width="12%"><b><font size="12pt" face="Arial">USERNAME</font></b></th>
    <th align="center" valign="middle" width="15%"><b><font size="12pt" face="Arial">FULL NAME</font></b></th>
    <th align="center" valign="middle" width="20%"><b><font size="12pt" face="Arial">OPERATION</font></b></th>
    <th align="center" valign="middle" width="36%"><b><font size="12pt" face="Arial">ACTIVITY</font></b></th>
  </tr>
</thead>';
  				
				$sn=0;
				#LogDate,Username,NAME,Operation,Activity
				while ($row = mysql_fetch_array($result)):
					$sn += 1;
					
					$un=''; $nm='&nbsp;'; $op='&nbsp;'; $cdt='&nbsp;'; $act='&nbsp;'; $dt='';
					
					if ($row['LogDate']) $dt=trim($row['LogDate']);
					if ($row['Username']) $un=trim($row['Username']);
					if ($row['Name']) $nm=trim($row['Name']);										
					if ($row['Operation']) $op=ucwords(trim($row['Operation']));					
					if ($row['Activity']) $act=trim($row['Activity']);				
					if ($dt) $cdt=date('j M Y',strtotime($dt));#Get Date in dd MMM yyyy
		
					$tbrow .= '
<tr align="center">
	<td valign="middle" align="center"  width="7%"><font size="12pt" face="Georgia, Times New Roman, Times, serif">'.number_format($sn,0).'</font></td>
	<td valign="middle" align="center" width="10%"><font size="12pt" face="Georgia, Times New Roman, Times, serif">'.$cdt.'</font></td>										
	<td valign="middle" align="center" width="12%"><font size="12pt" face="Georgia, Times New Roman, Times, serif">'.ucwords($un).'</font></td>										
	<td valign="middle" align="center" width="15%"><font size="12pt" face="Georgia, Times New Roman, Times, serif">'.ucwords($nm).'</font></td>									
	<td valign="middle" align="center" width="20%"><font size="12pt" face="Georgia, Times New Roman, Times, serif">'.ucwords($op).'</font></td>									
	<td valign="middle" align="center" width="36%"><font size="12pt" face="Georgia, Times New Roman, Times, serif">'.$act.'</font></td>
</tr>';
				endwhile;			
				
				//Build Report Html
				$content=$tableheader.$tbrow.'</table>';					

				$reportheader = '
					<div>
						<table border="0" cellpadding="2" cellspacing="0" width="100%">
						<tr bgcolor="#000000"> <td align="left"><font face="Arial,Helvetica, sans-serif" color="#FFFFFF"><b>AUDIT TRAIL REPORT</b></font></td><td align="right"><font face="Arial,Helvetica, sans-serif" color="#FFFFFF"><b>'.$today=date("jS F Y").'</b></font></td> </tr></table></div>';
				
				//////PDF OUT STARTS//////////////
				//////////////////////////////////
				// create new PDF document
				// page orientation (P=portrait, L=landscape)
				$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				
				// set document information
				$pdf->SetCreator('Idongesit Akpan');
				$pdf->SetAuthor('African Stock');
				$pdf->SetTitle('African Stock');
				$pdf->SetSubject('African Stock');
				$pdf->SetKeywords('African Stock, Data Capture, African Stock, Audit Trail');
				
				$pdf->SetFont('helvetica', 'B', 20, '', true);
				
				// set default header data
				//$pdf->SetHeaderData('images/'.$logo, PDF_HEADER_LOGO_WIDTH, $company, '');
				
				// set header and footer fonts
				$pdf->setFooterFont(Array('timesI', 'I', 10, '', true));
				$pdf->setHeaderFont(Array('timesI', 'I', 10, '', true));
				//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
				//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
				
				if ($logo)
				{
					if (file_exists('../images/'.$logo))
					{
						if (file_exists("../images/".$logo))
						{
							$ext=strtolower(trim(getExtension("../images/".$logo)));
									
							if (($ext=='jpg') || ($ext=='png') || ($ext=='gif'))
							{
								$pdf->SetHeaderData('', 0, '', 'African Stock - Audit Trail Report');
							}else
							{
								$pdf->SetHeaderData('', 0, '', 'African Stock - Audit Trail Report');	
							}
						}else
						{
							$pdf->SetHeaderData('', 0, '', 'African Stock - Audit Trail Report');	
						}
					}else
						{
							$pdf->SetHeaderData('', 0, '', 'African Stock - Audit Trail Report');	
						}
				}else
				{
					$pdf->SetHeaderData('', 0, '', 'African Stock - Audit Trail Report');	
				}
				
				$pdf->setHeaderFont(Array('timesI', 'I', 10, '', true));
				
				// set default monospaced font
				$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
				
				//set margins
				$pdf->SetMargins("0.4", 0.4, "0.4");
				//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
				
				$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
				$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
				//$pdf->setHeaderData('',0,'','',array(0,0,0), array(255,255,255) );  
				$pdf->SetPrintHeader(false);
				
				//set auto page breaks
				$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
				
				//set image scale factor
				$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
				
				//set some language-dependent strings
				$pdf->setLanguageArray($l);
				
				// ---------------------------------------------------------
				$pdf->SetFont('times', '', 10);				
				$pdf->setFontSubsetting(true);// set default font subsetting mode
				
				// Add a page
				$pdf->AddPage();
								
				//Print Logo				
				$header='<table border="0" cellpadding="4" cellspacing="0">'; 
					if ($logo)
					{
						if (file_exists("../images/".$logo))
						{
							$ext=strtolower(trim(getExtension("../images/".$logo)));
									
							if (($ext=='jpg') || ($ext=='png') || ($ext=='gif'))
							{
								$header.='<tr>	
									<td valign="top"><img width="170px" src="../images/'.$logo.'"></td>			
									<td valign="top" align="right"></td>
								</tr>';
							}else
							{
								$header.='<tr>	
									<td colspan="2" valign="top" align="left"><font size="+6" face="Trebuchet MS, Arial, Helvetica, sans-serif" color="#EA6724">'.$company.'</font><br><font size="+1" face="Georgia, Times New Roman, Times, serif">'.$address.'</font>
								</td>
							</tr>';
							}							
						}else
						{
							$header.='<tr>	
								<td colspan="2" valign="top" align="left"><font size="+6" face="Trebuchet MS, Arial, Helvetica, sans-serif" color="#EA6724">'.$company.'</font><br><font size="+1" face="Georgia, Times New Roman, Times, serif">'.$address.'</font>
								</td>
							</tr>';
						}	
					}else
					{
						$header.='<tr>	
								<td colspan="2" valign="top" align="left"><font size="+6" face="Trebuchet MS, Arial, Helvetica, sans-serif" color="#EA6724">'.$company.'</font><br><font size="+1" face="Georgia, Times New Roman, Times, serif">'.$address.'</font>
								</td>
							</tr>';
					}
					
					$header.='</table>
				';
			
				//*******BUILD CONTENTS HERE***********************								
				$content = $reportheader.$content;			
				
				$pdf->writeHTMLCell($w=0, $h=$pdf->GetY(), $x='', $y='0.30', $header, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
			
				$pdf->SetFont('times', '', 12);
				$pdf->writeHTMLCell($w=0, $h=$pdf->GetY(), $x='', $y='1', $content, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);// Close and output PDF document
				// This method has several options, check the source code documentation for more information.
				$rows=array();
				
				$outputfile="../reports/AuditTrailReport.pdf";
				
								
				$pdf->Output($outputfile, 'F');
					
				$rows[]="AuditTrailReport.pdf";
				
				/*echo "<script language='javascript' type='text/javascript' >window.open('".$outputfile."',null,'left=0, top=0, height=700, width= 1000, status=no, resizable= yes, scrollbars= yes, toolbar= no,location= no, menubar= no');</script>";*/
				
				echo json_encode($rows);
				
				//unlink($chartfilename);
				//============================================================+
				// END OF REPORT SECTION
				//============================================================+
			}//End Build Report
		}
	}
	
function getExtension($str) 
{
	 $i = strrpos($str,".");
	 if (!$i) { return ""; }
	 $l = strlen($str) - $i;
	 $ext = substr($str,$i+1,$l);
	 return $ext;
 }
 
 /////////////////////////////////////////////////
//$file = fopen('idong.txt',"w");	fwrite($file,$t); fclose($file); exit();
?>