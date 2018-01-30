<?php
	error_reporting(E_ERROR);
?>

<link rel="shortcut icon" href="<?php echo base_url();?>favicon.gif" />
<link rel="icon" type="image/gif" href="<?php echo base_url();?>animated_favicon1.gif" />

<link type="text/css" href="<?php echo base_url();?>css/menu3level.css" rel="stylesheet" />

<style> @import url('<?php echo base_url();?>css/tab.css'); </style>
<style> @import url('<?php echo base_url();?>css/tabs.css'); </style>

<script type="text/javascript" src="<?php echo base_url();?>js/general.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.effects.core.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/scripts.js"></script>

<!--<script type="text/javascript" src="<?php #echo base_url();?>js/menu.js"></script>-->

<!--[if IE]>
    <script src="<?php echo base_url();?>js/html5.js"></script>
<![endif]-->

<style type="text/css">
* { margin:0;
    padding:0;
}
html { /*background:#D1A4FF; */
}
body {
    margin:0px auto;
    width:100%;
    overflow:hidden;
	/*height:auto;*/
}

div#copyright { display: none; }

#nav li a:hover {
 background: #703E81;
 background: -moz-linear-gradient(top, #11032e, #703E81);
 background: -webkit-gradient(linear, left top, left bottom, from(#11032e), to(#703E81));
}
</style>

<span style="font-weight:bold; font-size:0.9em;font-family: Helvetica, sans-serif; padding-right:20px; margin-top:0px;">
<?php 
	echo '
	<table border="0" width="100%" >
	<tr>
		<td style="float:left;" valign="middle" align="left"><img src="'.base_url().'images/africanstock.png" style="padding-top:5px; padding-left:5px; width:auto;"></td>
		<td align="right" valign="middle"><span style="color:#FF0;font-size:1.2em; font-style:italic; ">welcome&nbsp;</span>'.'<span style="color:#00FFFF;font-size:1.2em; font-style:italic;">'.$_SESSION['fullname'].'!&nbsp;&nbsp;</span></td>
	</tr>
	</table>';
	
	//echo '<div style="color:#ECA8x1E;">'.trim($_SESSION['address']).'</div>';
	echo '';
?>

<ul id="nav" style="width:100%; background:#2A2A2A;">     
        <li><a style="border:none; font:16px Tahoma, Sans-serif;" target="_self" href="<?php echo site_url("logout");?>" title="Logout" onclick="return confirm('This action will terminate every operation being carried. Do you really want to LOGOUT (Click OK to logout or CANCEL to continue working)?');">Log Out</a></li>
        <li><a href="<?php echo site_url("home");?>" title="Close Current Page" style=" font:16px Tahoma, Sans-serif;">Close Form</a></li>
        
        
        
              <?php
			  	echo '<li><a href="#" title="Main Tasks" style=" font:16px Tahoma, Sans-serif; ">Tasks</a>
					<ul>';
				
					if ($_SESSION['CheckPaymentStatus']==1)
					{#<li><a href="" title=""><hr /></a></li>
						echo '
						<li><a href="'.site_url("checkstatus").'" title="Check Payment Status" style="width:150px;">Check Payment Status</a></li>';
					}
										
				
				
				echo '</ul></li>';
			   ?>
                
        
             <!--ADMIN-->  
              <?php
				if (trim(strtoupper($_SESSION['role'])) == 'ADMIN')
				{
					echo '
		 <li><a href="#" title="Admin" style=" font:16px Tahoma, Sans-serif;">Admin</a>
      		 <ul style="width:160px;">';
				
				if ($_SESSION['SetParameter']==1)
				{
					echo '
						<li><a href="'.site_url("currency").'" title="Interswitch Currencies Information" style="width:150px;">Currencies</a></li>
						';
						
				}
				
				if ($_SESSION['CreateAccount']==1)
				{#<li><a href="" title=""><hr /></a></li>
					echo '
					<li><a href="'.site_url("user").'" title="Create/Modify Users Account" style="width:150px;">Users Accounts</a></li>';
				}
				
				if ($_SESSION['SetParameter']==1)
				{
					echo '	
						
						<li><a href="'.site_url("general").'" title="Create/Modify System Settings" style="width:150px;">System Settings</a></li>
					';
				}                 
				 
				
                
				if ($_SESSION['ClearLog']==1)
				{ #<li><a href="" title=""><hr /></a></li>
					echo '
					
                
                <li class="last"><a href="'.site_url("clearlog").'" title="Delete Log Records" style="width:150px;">Delete Log Entries</a></li>

					';
				}
				
          echo '     
            </ul>
      </li>';
		}
	?>        
       
 <!--VIEW REPORTS-->
     <?php
		if ($_SESSION['ViewReport']==1)
		{
			echo '
		<li><a href="#" title="Reports" style=" font:16px Tahoma, Sans-serif;">Reports</a>
        <ul>';
			echo '<li><a href="'.site_url("paystatusrep").'" style="width:170px;" title="Check Payment Status Report">Check Payment Status Report</a></li>
			<li><a href="'.site_url("logrep").'" style="width:170px;" title="Audit Trail Report">Audit Trail Report</a></li>
			';
		}
		echo '</ul></li>';
	?>

      
      <!--<li class="last"><a href="#" onclick="window.open('<?php echo base_url(); ?>downloads/African Stock Web Help.pdf',null,'left=0, top=0, height=700, width= 1000, status=no, resizable= yes, scrollbars= yes, toolbar= no,location= no, menubar= no');" title="Click To Display Help">Help</a></li>-->
  </ul>
</span>


        


<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.10.2.min.js"></script>


<script src="<?php echo base_url();?>js/modernizr.js"></script>
