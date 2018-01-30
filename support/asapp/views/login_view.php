<script>
var post_url = "<?php echo base_url();?>ajax/login_model.php";

$(document).ready(function(e) 
{
	$(document).ajaxStop($.unblockUI);
	
	$('#txtusername').keypress(function(e)//Submit when enter key is pressed
	{
      if(e.keyCode==13) $('#btnSubmit').click();
    });
	
	$('#txtpwd').keypress(function(e){
      if(e.keyCode==13) $('#btnSubmit').click();
    });
	
	$('#btnRefresh').click(function(e) {
        try
		{
			clearForm();
		}catch(e)
		{
			alert('REFRESH BUTTON CLICK ERROR:\n'+e);
			return false;
		}
    });
	
	$('#lnkChange').click(function(e) {
       try
		{
			window.location.href='<?php echo site_url("changepwd");?>';
		}catch(e)
		{
			alert('CHANGE PASSWORD BUTTON CLICK ERROR:\n'+e);
			return false;
		} 
    });
	
	/*
	$('#btnHome').click(function(e) {
        try
		{
			//window.location.href='<?php echo site_url("intro");?>';
		}catch(e)
		{
			alert('HOME BUTTON CLICK ERROR:\n'+e);
			return false;
		}
    });
	*/
	
	$('#btnSubmit').click(function(e) {
        try
		{
			if (!checkForm()) return false;
			
			//Send values here
			$.blockUI({ 
				message: '<h1><img src="<?php echo base_url();?>images/loader.gif" /><p>Validating Login. Please Wait...</p></h1>',
				css: { 
				border: 'none', 
				padding: '15px', 
				backgroundColor: '#000', 
				'-webkit-border-radius': '10px', 
				'-moz-border-radius': '10px', 
				opacity: 1, 
				color: '#fff' 
			} });
			
			//cn,cm,cbostate,pid,bt
			
			//Make Ajax Request
			var un=$('#txtusername').val();
			var pwd=$('#txtpwd').val()		
			
			pwd=sha1(pwd);
			
			var mydata={action: 'LOGIN',username:un, pwd:pwd};
					
			$.ajax({
				url: "<?php echo base_url();?>ajax/login_model.php",
				data: mydata,
				type: 'POST',
				dataType: 'json',
				complete: function(xhr, textStatus) {
					$.unblockUI;
				},
				success: function(data,status,xhr) {	
					$.unblockUI;
								
					//Clear boxes
					var fl='';
					fl=data.toString();
					
					if ($.trim(fl.toUpperCase())=='OK')
					{
						$.unblockUI;
						
						window.location.href='<?php echo site_url("home");?>';
					}else
					{
						$.unblockUI;
						
						alert(fl);
					}
					
				},
				error:  function(xhr,status,error) {
						$.unblockUI;
					alert('Error '+ xhr.status + ' Occurred: ' + error);
					}
			});	
		}catch(e)
		{
			$.unblockUI;
			alert('SUBMIT BUTTON CLICK ERROR:\n'+e);
			return false;
		}
    });
	
	function checkForm()
	{
		try
		{
			var username,password,remember;
			
			var username=document.getElementById("txtusername");
			var pwd=document.getElementById("txtpwd");
			
			username.value=$.trim(username.value);
			
			if (username.value=='')
			{
				alert('Please enter your username.');
				username.focus();
				return false;
			}
			
			//Pwd
			if (trim(pwd.value)=='')
			{
				alert('Blank password is not allowed. Please enter a valid password.');
				pwd.focus();
				return false;
			}
			
			return true;
		}catch(e)
		{
			alert('CHECK FORM ERROR:\n'+e);
			return false;
		}
	}//End checkForm

	function clearForm()
	{
		try
		{
			window.location.reload(true);
		}catch(e)
		{
			alert('CLEAR FORM ERROR:\n'+e); 
			return false;
		}	
	}
	
	function trim(str)
	{
		return str.replace(/^\s+|\s+$/g,'');
	}
});

</script>

<style type="text/css">
.displayborder{
	border:1px; 
	border-style:solid;
}

.button {
	background-color:#9CF;/*C90*/
	border: 1px solid #003366;
	color:#600;
}

body {
	
 
    font: 12px verdana;
    margin: 0;
    padding: 0;
    height: 100%;
}
.login_wrap {
    /*background:#C2462C;/*#069
	background: -webkit-gradient(linear, left top, left 25, from(black), color-stop(4%, #3c3c3c), to(#292929));*/
    background:#666666;
    width: 330px;
    margin: -200px 0 0 -200px;
    top: 50%;
    left: 50%;
    position: absolute;
    height: 270px;
    *height: 400px;
    *margin: -225px 0 0 -200px;
    
	padding: 20px 55px;
    border-radius: 8px;
    box-shadow: 0 2px 3px rgba(0,0,0,0.2);
}
.logo {
    text-align:left;
    padding: 20px 0 15px;
}
.vform {
	margin: 0 auto;
}
a {
	color: #366107;
    font-size: 10px;
    text-decoration: none;
}
.vform fieldset.float label {
	float:left;
	min-width: 90px;
    line-height: 30px;
}
.vform p, .vform label {
	color: #777;
	text-align: justify;
}
.vform label, .vform input {
	margin: 2px;
}
.vform fieldset input {
	line-height: 20px;
	width: 179px;
	padding: 3px;
	color: #000;
    box-sizing: border-box;
}

.vform textarea {
	height: 100px;
}
.vform fieldset.float {
	float: left;
	margin: 0;
}
.vform .clear {
	clear: both;
}
.vform fieldset {
	margin-bottom: 10px;
}

.vform .btn2 {
	background: #fff;
}
.vform .vcontact {
	background: #f9f9f9;
	border-radius: 6px;
	box-shadow: 0 1px 1px #ccc;
	padding: 20px 30px;
}
.vform.buy {
	background: #eee;
	border-radius: 6px;
	box-shadow: 0 1px 1px #ccc;
	width: 85%;
	padding: 20px 30px;
}
fieldset {
	border: 0;
	width:450px;
}
.cute_button {
	padding: 8px;
	background: #366107;
	text-decoration: none;
	-webkit-border-radius: 2px;
	-moz-border-radius: 2px;
	-o-border-radius: 2px;
	border-radius: 2px;
	border: 1px solid #d3d5d4;
	border-bottom: solid 2px #acacac;
	cursor: pointer;
	transition-duration: 0.3s;
	-moz-transition-duration: 0.3s;
	-webkit-transition-duration: 0.3s;
	color: #fff!important;
	clear: both;
}
.cute_button:hover {
background:#930;/*#D5392C;*/
color: #fff!important;
}

.cute_button:active {
	color: #999;
	background: #e8e8e8;
}

.get_started {
    margin: 0 auto;
    text-align: center;
}
.get_started a {
    z-index: 100;
    background: #777;
    transition-duration: 0.2s;
    -moz-transition-duration: 0.2s;
    -webkit-transition-duration: 0.2s;
    -o-transition-duration: 0.2s;
    padding: 15px 30px;
    border-radius: 5px;
    border-bottom: solid 3px #444;
    color: #fff;
    font: 300 13px Verdana, sans-serif;
    text-shadow: 0 1px #999;
    text-decoration: none;
}

.get_started a:hover {
    background: #9eb74d;
    border-bottom-color: #360;
}
#me_box {
	margin-top:70px;
	color:#D5392C;
}
#me_box li {
	list-style-type:none;
}

/* Vertical Align */

html { height: 100% }

.login_wrap {
	margin: 0;
	position: static;
	top: auto;
	left: auto;
	display: inline-block;
}

button:hover{
	background-color:#FF5500;
	color:#FFF;
	}

#btnHome:hover{color:#FF5500;}
</style>



    <noscript><font color="#ff0000"><h3>JavaScript must be enabled in order for you to run this application.</h3> 
      However, it seems JavaScript is either disabled or not supported by your browser. 
      Please enable JavaScript by changing your browser options, and then 
      try again.</font>
    </noscript> 
	  
      
      
      <center>
      <div class="login_wrap" style="height:340px;">
    <div class="logo">
        <img alt="African Stock" src="<?php echo base_url();?>images/africanstock.png">
    </div>
    <div class="vform">
        <form data-inset="true" data-shadow="true" data-role="controlgroup">
            <fieldset class="float">
              <label style="margin-top:-5px; color:#FFF;">Username</label>
                <input name="txtusername" type="text" id="txtusername" style="width:230px; text-transform:none;" class="input" title="Enter Username" placeholder="Enter Username" autofocus required />
                <br><br>
              <label style="margin-top:-5px; color:#FFF;" class="clear">Password</label>
                <input class="input" name="txtpwd" type="password" id="txtpwd" placeholder="Enter Password"  title="Enter User Password" autofocus required style="width:230px; text-transform:none;" />
                <br><br>
                <label class="clear"></label>
				               
                 <button title="Login" id="btnSubmit" type="button" class="button" role="button" style="text-align:center; width:110px; float:left; background-color:#000;">
                  <span class="ui-button-text">Login</span>
                  </button>
                  
                <button title="Refresh Form" id="btnRefresh" type="button" class="button" role="button" style="text-align:center; width:110px; float:left; background-color:#000;" >
                  <span class="ui-button-text">Reset</span>
                 </button>
                 
                 <!-- <button title="Go To Home" id="btnHome" type="button" class="button" role="button" style="text-align:center; width:70px; float:left; background-color:#6F9;" >
                  <span class="ui-button-text">Home</span>
                 </button>-->
            </fieldset>
            
            <div class="clear"></div>
               
            <div style="text-align: center;">
                 <br>
                   <a id="lnkChange" style="font-style:italic; font-weight:bold; font-size:0.9em; color:#ff0" title="Click Here To Change Your Password!" href="#"><u>Change Password!</u></a>
                 
           			<div class="clear"></div><br />
           </div>
           
           <div style="text-align: left;">
                   <strong style="color:#ea0; margin-left:-15px;" title="You can download any of the following applications by clicking on the corresponding link below.	">Downloads:</strong><br />
                   <ul style="color:#ea0">
                   	<li title='Download Adobe Reader 10.0'>
                    	<a href='<?php echo base_url();?>ajax/download.php?download_file=AdobeReader1000_en_US.exe' style='margin-top:3px; margin-bottom:3px'><span style="color:#ea0;">Download Adobe Reader 10.0</span></a>
                    </li>
                    
                    <li title='Download Adobe Reader 11.0.3'>
                    	<a href='<?php echo base_url();?>ajax/download.php?download_file=AdobeReader11003_en_US.exe' style='margin-top:3px; margin-bottom:3px'><span style="color:#ea0;">Download Adobe Reader 11.0.3</span></a>
                    </li>
                    
                    <li title='Download Mozilla Firefox 13.0.1'>
                    	<a href='<?php echo base_url();?>ajax/download.php?download_file=Firefox Setup 13.0.1.exe' style='margin-top:3px; margin-bottom:3px'><span style="color:#ea0;">Download Mozilla Firefox 13.0.1</span></a>
                    </li>
                    
                    <li title='Download Mozilla Firefox 22.0'>
                    	<a href='<?php echo base_url();?>ajax/download.php?download_file=Firefox Setup 22.0.exe' style='margin-top:3px; margin-bottom:3px'><span style="color:#ea0;">Download Mozilla Firefox 22.0</span></a>
                    </li>
                   </ul>
            	
                <div class="clear"></div><br><br>
           </div>       
                        
        </form>
                   
    </div>
		<div id="me_box">
		    		    		 </div>
                    
</div>
 </center>   
