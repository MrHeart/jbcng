<?php 
	 
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta content="charset=utf-8" />
<meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>

<title><?php echo $title;?></title>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.10.2.min.js"></script>
<script type="text/javascript">
<!--
	var CI = {'base_url': '<?php echo base_url(); ?>'};
-->
</script>
        
 <link rel="shortcut icon" href="<?php echo base_url();?>favicon.gif" >
<link rel="icon" type="image/gif" href="<?php echo base_url();?>images/animated_favicon1.gif" >

<style> @import url('<?php echo base_url();?>css/form.css'); </style>
<style> @import url('<?php echo base_url();?>css/page.css'); </style>
<style> @import url('<?php echo base_url();?>css/style.css'); </style>
<style> @import url('<?php echo base_url();?>css/tab.css'); </style>
<style> @import url('<?php echo base_url();?>css/tabs.css'); </style>
<style> @import url('<?php echo base_url();?>css/jquery-ui.css'); </style>
<style> @import url('<?php echo base_url();?>css/jquery-ui-timepicker-addon.css'); </style>
<style> @import url('<?php echo base_url();?>css/jquery.window.css'); </style>

<script type="text/javascript" src="<?php echo base_url();?>calendar/datetimepicker_css.js"></script>
        
<style type="text/css">
body { 
	background-image:url(<?php echo base_url();?>images/bg2.jpg);
}
</style>
<meta charset="utf-8">
</head>
<body style="height:100%; margin:0; width:100%; background-color: #eee;" id="main" role="main">
	
    
    <section id="contents" style="height:100%; position:absolute; margin-top:150px; width:100% ">
    	<div style="overflow:auto; height:100%; background-color:inherit; width:100%">
	    	<?php $this->load->view($include);?>
        </div>
    </section>
    
    <style>
		#idfooter {
		text-align:center;
		
		padding-left: 12px;
		margin-top: 0px;
		padding-bottom:0px;
		
		/********************************************/
		width:100%;	
		height:20x; 
		position:absolute; 
		bottom:0; 
		left:0; 
		background-color:#fff; 
		overflow:auto;
		
		display: block;
		font-family:Georgia, "Times New Roman", Times, serif;
		font-size:0.75em;
		font-style:italic;
		}
	</style>
<!--
    <footer id="idfooter">
    	<a style="color:#fff" href="http://www.timelessnet.com" target="new">Copyright &copy;Powered By Javedi Network Service <?php echo date('Y'); ?>. All rights Reserved.</a>
    </footer>-->


<script src="<?php echo base_url();?>js/jquery.min.js"></script>
<script src="<?php echo base_url();?>js/modernizr.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.blockUI.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/organictabs.jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/sha1.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>js/general.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/tabs.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/tabs_old.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui-sliderAccess.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.tablePagination.0.5.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.form.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>js/jquery.window.min.js"></script>

<!--<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyAVl5Y6j7DL7fe46o-tBCKiSmAWETJutz8&sensor=false">
</script>-->


 <script>
$(function() 
{
	$( "#datepicker" ).datepicker({
		dateFormat: "yy-mm-dd",
		buttonText: "Choose",
		showButtonPanel: true,
		showOtherMonths: true,
		selectOtherMonths: true,
		changeMonth: true,
		numberOfMonths: 1,
		changeYear: true,
		showOn: "both",
		buttonImage: "<?php echo base_url();?>calendar/cal.gif",
		buttonImageOnly: false
		//showWeek: true,
		//firstDay: 1
	});

	 $( "#from" ).datepicker({
		dateFormat: "yy-mm-dd",
		buttonText: "Choose",
		showButtonPanel: true,
		showOtherMonths: true,
		selectOtherMonths: true,
		changeMonth: true,
		numberOfMonths: 3,
		changeYear: true,
		showOn: "both",
		buttonImage: "<?php echo base_url();?>calendar/cal.gif",
		buttonImageOnly: false,
		altField: "#from_alternate",
		altFormat: "DD, d M, yy",		 
		//defaultDate: "",
		onClose: function( selectedDate ) {
			$( "#to" ).datepicker( "option", "minDate", selectedDate );
		}
	});
	
	
	
	$( "#to" ).datepicker({
		dateFormat: "yy-mm-dd",
		showButtonPanel: true,
		showOtherMonths: true,
		selectOtherMonths: true,
		changeMonth: true,
		numberOfMonths: 3,
		changeYear: true,
		showOn: "both",
		buttonImage: "<?php echo base_url();?>calendar/cal.gif",
		buttonImageOnly: false,
		altField: "#to_alternate",
		altFormat: "DD, d M, yy",		 
		//defaultDate: "+1w",		
		onClose: function( selectedDate ) {
			$( "#from" ).datepicker( "option", "maxDate", selectedDate );
		}
	});
	
	//$( "#from" ).datepicker( "option", "dateFormat", "yy-mm-dd" );});
	//$( "#to" ).datepicker( "option", "dateFormat", "yy-mm-dd" );});
});
</script>

<!--[if IE]>
	<script src="<?php echo base_url();?>js/html5.js"></script>
<![endif]-->

</body>
</html>
