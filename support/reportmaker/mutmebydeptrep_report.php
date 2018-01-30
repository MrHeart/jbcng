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
		$session=''; $faculty=''; $course=''; $dept='';
				
		$action = strtoupper(trim($_POST["action"]));
		
		if (isset($_POST["session"])) $session = trim($_POST["session"]);
		if (isset($_POST["faculty"])) $faculty=trim($_POST["faculty"]);
		if (isset($_POST["dept"])) $dept=trim($_POST["dept"]);
		
		mysql_select_db($database_conn, $conn);
				
		if ($action=='DISPLAY_REPORT')
		{
			$crit=''; $u='';
			
			$sql = "SELECT * FROM candidates WHERE (TRIM(session)='".$session."') AND (TRIM(mode)='Merit') ";
		
			if (trim($faculty) != '') $sql .= " AND (TRIM(facabrev)='".$faculty."')";
			if (trim($dept) != '') $sql .= " AND (TRIM(dept)='".$dept."')";
			
			$sql .= " ORDER BY average_score DESC,faculty,dept,course,candidatename";
							
#$file = fopen('idong.txt',"w");	fwrite($file,"C => ".$c."\nU => ".$u."\n\n".$sql); fclose($file);	
			$result = mysql_query($sql, $conn) or die('GET POST UTME RESULT ERROR: '.mysql_error());

			$TotalExpected = mysql_num_rows($result);
			
			if ($TotalExpected==0)
			{
				$rows[]="There is no result record for the selected query criteria.";
				
				echo json_encode($rows);
			}else
			{
				$logo='uniuyo.png';
								
				//Get Company Info
				$qry="SELECT * FROM parameters";
				$rsLogo = mysql_query($qry, $conn) or die('SCHOOL INFORMATION RETRIEVAL ERROR: '.mysql_error());
				$recNo=mysql_num_rows($rsLogo);
				
				if ($recNo>0)
				{
					$row_rsLogo = mysql_fetch_assoc($rsLogo);
					$address=''; $school='';
					if ($row_rsLogo['school']) $school=$row_rsLogo['school'];
					if ($row_rsLogo['address']) $address=$row_rsLogo['address'];
				}			
				
/////////////////////////////////////REPORT BELOW/////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////
				//Table Headers
				$tableheader='
<table nobr="false" border="1" cellpadding="2" cellspacing="0" width="100%">	
<thead>
  <tr bgcolor="#EEEEEE">
    <th valign="middle" align="center"  width="4%"><b><font size="11pt" face="Arial">S/N</font></b></th>
    <th align="center" valign="middle" width="9%"><b><font size="11pt" face="Arial">Reg. No</font></b></th>
    <th align="center" valign="middle" width="17%"><b><font size="11pt" face="Arial">Candidate Name</font></b></th>
    <th align="center" valign="middle" width="7%"><b><font size="11pt" face="Arial">State</font></b></th>
    <th align="center" valign="middle" width="4%"><b><font size="11pt" face="Arial">Sex</font></b></th>
    <th align="center" valign="middle" width="8%"><b><font size="11pt" face="Arial">Fac. Abrev.</font></b></th>
    <th align="center" valign="middle" width="13%"><b><font size="11pt" face="Arial">Course Abrev.</font></b></th>
    <th align="center" valign="middle" width="6%"><b><font size="11pt" face="Arial">Course Code</font></b></th>
	<th align="center" valign="middle" width="6%"><b><font size="11pt" face="Arial">Jamb Score</font></b></th>
	<th align="center" valign="middle" width="7%"><b><font size="11pt" face="Arial">POST UTME Score</font></b></th>
	<th align="center" valign="middle" width="6%"><b><font size="11pt" face="Arial">AVG. Score</font></b></th>
	<th align="center" valign="middle" width="5%"><b><font size="11pt" face="Arial">Mode</font></b></th>
	<th align="center" valign="middle" width="8%"><b><font size="11pt" face="Arial">Remarks</font></b></th>
  </tr>
</thead>';
#SN,REGNO,CAND.NAME,STATE,GENDER,FACABREV,COURSEABREV,COURSECODE,JAMBSCORE,POSTUTMESCORE,AVGSCORE,MODE,REMARKS
#5,7,16,7,5,8,13,6,6,7,6,6,8
				$sn=0;
				
				while ($row = mysql_fetch_array($result)):
					$sn += 1;
					
					$rn='&nbsp;'; $cname='&nbsp;'; $state='&nbsp;'; $gen='&nbsp;'; $fac='&nbsp;'; $cou='&nbsp;';
					$code='&nbsp;';  $jamb='&nbsp;'; $putme='&nbsp;'; $ave='&nbsp;'; $mod='&nbsp;'; $rem='&nbsp;';
					
					if ($row['regno']) $rn=trim($row['regno']);
					if ($row['candidatename']) $cname=trim($row['candidatename']);
					if ($row['stateoforigin']) $state=trim($row['stateoforigin']);
					if ($row['gender']) $gen=trim($row['gender']);					
					if ($row['facabrev']) $fac=trim($row['facabrev']);					
					if ($row['courseabrev']) $cou=trim($row['courseabrev']);
					if ($row['coursecode']) $code=trim($row['coursecode']);
					if ($row['jambscore']) $jamb=trim($row['jambscore']);
					if ($row['postutme_score']) $putme=trim($row['postutme_score']);
					if ($row['average_score']) $ave=trim($row['average_score']);
					if ($row['mode']) $mod=trim($row['mode']);
					if ($row['remarks']) $rem=trim($row['remarks']);
					
					
					#SN,REGNO,CAND.NAME,STATE,GENDER,FACABREV,COURSEABREV,COURSECODE,JAMBSCORE,POSTUTMESCORE,AVGSCORE,MODE,REMARKS
#5,7,16,7,5,8,13,6,6,7,6,6,8				
						$tbrow .= '
<tr align="center">
	<td valign="middle" align="center"  width="4%"><font size="10pt" face="Georgia, Times New Roman, Times, serif">'.number_format($sn,0).'</font></td>
	<td valign="middle" align="center" width="9%"><font size="10pt" face="Georgia, Times New Roman, Times, serif">'.$rn.'</font></td>										
	<td valign="middle" align="center" width="17%"><font size="10pt" face="Georgia, Times New Roman, Times, serif">'.ucwords(strtolower($cname)).'</font></td>										
	<td valign="middle" align="center" width="7%"><font size="10pt" face="Georgia, Times New Roman, Times, serif">'.ucwords(strtolower($state)).'</font></td>									
	<td valign="middle" align="center" width="4%"><font size="10pt" face="Georgia, Times New Roman, Times, serif">'.strtoupper($gen).'</font></td>									
	<td valign="middle" align="center" width="8%"><font size="10pt" face="Georgia, Times New Roman, Times, serif">'.ucwords(strtolower($fac)).'</font></td>									
	<td valign="middle" align="center" width="13%"><font size="10pt" face="Georgia, Times New Roman, Times, serif">'.ucwords(strtolower($cou)).'</font></td>									
	<td valign="middle" align="center" width="6%"><font size="10pt" face="Georgia, Times New Roman, Times, serif">'.strtoupper($code).'</font></td>
	<td valign="middle" align="center" width="6%"><font size="10pt" face="Georgia, Times New Roman, Times, serif">'.$jamb.'</font></td>
	<td valign="middle" align="center" width="7%"><font size="10pt" face="Georgia, Times New Roman, Times, serif">'.$putme.'</font></td>
	<td valign="middle" align="center" width="6%"><font size="10pt" face="Georgia, Times New Roman, Times, serif">'.$ave.'</font></td>
	<td valign="middle" align="center" width="5%"><font size="10pt" face="Georgia, Times New Roman, Times, serif">'.ucwords(strtolower($mod)).'</font></td>
	<td valign="middle" align="center" width="8%"><font size="10pt" face="Georgia, Times New Roman, Times, serif">'.$rem.'</font></td>
</tr>';
				endwhile;
				
				//Build Report Html
				$content=$tableheader.$tbrow.'</table>';					

				//**************Display the chart here*******************
				#$reportheader =  '<font size="15pt" face="Arial,Helvetica, sans-serif"><b>'.strtoupper($school).'</b></font>';
				
				$reportheader .= '
					<div><p align="center" >
					<font size="13pt" face="Arial,Helvetica, sans-serif"><b>'.$session.' MERIT ADMISSION LIST</b></font>
					</p>
					<p align="center">
					<font size="13pt" face="Arial,Helvetica, sans-serif"><b>DEPARTMENT: '.strtoupper($dept).'</b></font>
					</p></div>';
				
				//////PDF OUT STARTS//////////////
				//////////////////////////////////
				// create new PDF document
				// page orientation (P=portrait, L=landscape)
				$pdf = new TCPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
				
				// set document information
				$pdf->SetCreator('African Stock');
				$pdf->SetAuthor('African Stock');
				$pdf->SetTitle('African Stock');
				$pdf->SetSubject('African Stock');
				$pdf->SetKeywords('POST UTME, University Of Uyo, Uniuyo, Admission, JAMB, African Stock');
				
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
								$pdf->SetHeaderData('', 0, '', 'Merit Admission List By Department');
							}else
							{
								$pdf->SetHeaderData('', 0, '', 'Merit Admission List By Department');	
							}
						}else
						{
							$pdf->SetHeaderData('', 0, '', 'Merit Admission List By Department');
						}
					}else
						{
							$pdf->SetHeaderData('', 0, '', 'Merit Admission List By Department');	
						}
				}else
				{
					$pdf->SetHeaderData('', 0, '', 'Merit Admission List By Department');
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
				$header='
					<table border="0" cellpadding="4" cellspacing="0">'; 
					if ($logo)
					{
						if (file_exists("../images/".$logo))
						{
							$ext=strtolower(trim(getExtension("../images/".$logo)));
									
							if (($ext=='jpg') || ($ext=='png') || ($ext=='gif'))
							{
								$header.='<tr>	
									<td valign="top" width="100%;" align="center"><img width="120px" src="../images/'.$logo.'"></td>
									
								</tr>';
								#<td valign="top" align="left" width="100%">'.$reportheader.'</td>
							}else
							{
								$header.='<tr>	
									<td valign="top" align="center" width="100%">'.$reportheader.'
								</td>
							</tr>';
							}							
						}else
						{
							$header.='<tr>	
								<td valign="top" align="center" width="100%">'.$reportheader.'</td>
							</tr>';
						}	
					}else
					{
						$header.='<tr>	
								<td valign="top" align="center" width="100%">'.$reportheader.'</td>
							</tr>';
					}
					
					$header.='</table>
				';
			
				//*******BUILD CONTENTS HERE***********************
								
				$content = $reportheader.$content;			
				
				$pdf->writeHTMLCell($w=0, $h=$pdf->GetY(), $x='', $y='0.30', $header, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
			
				$pdf->SetFont('times', '', 12);
				$pdf->writeHTMLCell($w=0, $h=$pdf->GetY(), $x='', $y='1.6', $content, $border=0, $ln=1, $fill=0, $reseth=true, $align='', $autopadding=true);
				
				$pdf->SetFont('times', '', 10);
				
				$chartheight=100;
				
				$factor=0.5;
				//Formular= 0.5 * (freq * 100)
				$x = 1; $y = $pdf->GetY();// + the highest chart height + 5
				$w = 6; $h = 50;
				
				//Insert Charts
				//$pdf->Image($file,TCPDF $x,TCPDF $y,image Width, Image Height, Image Type,$link, align, $resiz, $dpi,palign,ismask,imgmask,border,fitbox,hidden,fitonpage,alt,altimgs)	
				
				$pageDimensions=$pdf->getPageDimensions();
				$pagewidth=$pageDimensions['wk'];
				
				//Image(file,TCPDF x,TCPDF y,imagewidth,imageheight,Image Type,
				#if (file_exists($chartfilename)) $pdf->Image($chartfilename,$x,$y+0.5,$pagewidth*0.6,0,'PNG','','N',false,300,'C',false,false,0,true,false);		

				// Close and output PDF document
				// This method has several options, check the source code documentation for more information.
				$rows=array();
				
				$outputfile="../reports/DeptMeritListReport.pdf";
				
								
				$pdf->Output($outputfile, 'F');
					
				$rows[]="DeptMeritListReport.pdf";
				
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