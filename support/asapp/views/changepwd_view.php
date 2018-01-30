<script>
var post_url = "<?php echo base_url();?>ajax/changepwd_model.php";

$(document).ready(function(e) 
{
	$(document).ajaxStop($.unblockUI);
	
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
	
	$('#btnLogin').click(function(e) {
       try
		{
			window.location.href='<?php echo site_url("login");?>';
		}catch(e)
		{
			alert('LOGIN BUTTON CLICK ERROR:\n'+e);
			return false;
		} 
    });
	
	$('#btnChange').click(function(e) {
        try
		{
			if (!checkForm()) return false;
			
			//Send values here
			$.blockUI({ 
				message: '<h1><img src="<?php echo base_url();?>images/loader.gif" /><p>Validating Password. Please Wait...</p></h1>',
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
			var oldpwd=$('#txtoldpwd').val()
			var newpwd=$('#txtnewpwd').val()		
			
			newpwd=sha1(newpwd);
			oldpwd=sha1(oldpwd);
			
			var mydata={action: 'CHANGE',username:un, oldpwd:oldpwd, pwd:newpwd};
					
			$.ajax({
				url: "<?php echo base_url();?>ajax/changepwd_model.php",
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
					
					if ($.trim(fl.toLowerCase())=='password was changed successfully!')
					{
						$.unblockUI;
						
						alert(fl);
						
						window.location.href='<?php echo site_url("login");?>';
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
			var username=document.getElementById("txtusername");
			var oldpwd=document.getElementById("txtoldpwd");
			var newpwd=document.getElementById("txtnewpwd");
			var cpwd=document.getElementById("txtcpwd");

	
			username.value=$.trim(username.value);
			
			if (username.value=='')
			{
				alert('Please enter a username.');
				username.focus();
				return false;
			}
			
			if (username.value.length < 4)
			{
				alert('Minimum username (login name) character length is 4 (four).');
				username.focus();
				return false;
			}
			
			//oldpwd
			if ($.trim(oldpwd.value)=='')
			{
				alert('Blank password is not allowed. Please enter a valid old password.');
				oldpwd.focus();
				return false;
			}
			
			//newpwd
			if ($.trim(newpwd.value)=='')
			{
				alert('Blank password is not allowed. Please enter a valid new password.');
				newpwd.focus();
				return false;
			}
			
			if (newpwd.value.length < 4)
			{
				alert('The new password must be at least four (4) characters long.');
				newpwd.focus();
				return false;
			}
			
			//Confirm Password
			if ($.trim(cpwd.value) == '')
			{
				alert('The confirming password entry must not be blank.');
				cpwd.focus();
				return false;
			}
				
			if (($.trim(newpwd.value))!=($.trim(cpwd.value)))
			{
				alert('New password and confirming password do not match.');
				cpwd.focus();
				return false;
			}
			
			if (!confirm('Are you sure you want to change the current user password (Click OK to proceed and CANCEL to abort)?'))
			{
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
	background-color:#9CF;
	border: 1px solid #003366;
	color:#600;
}

body {
    background: #25272F;
    font: 12px verdana;
    margin: 0;
    padding: 0;
    height: 100%;
}
.login_wrap {
    background:#C9F;
    /*background: rgba(255,255,255,0.9);*/
    width: 500px;
    margin: -200px 0 0 -200px;
    top: 50%;
    left: 50%;
    position: absolute;
    height: 300px;
    *height: 400px;
    *margin: -225px 0 0 -200px;
    
	padding: 0px 0px;
    border-radius: 8px;
    box-shadow: 0 2px 3px rgba(0,0,0,0.2);
}
.logo {
    text-align: center;
    padding: 10px 0 1px;
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
	min-width: 150px;
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
	width: 200px;
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
	margin-bottom: 20px;
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
</style>



    <noscript><font color="#ff0000"><h3>JavaScript must be enabled in order for you to run this application.</h3> 
      However, it seems JavaScript is either disabled or not supported by your browser. 
      Please enable JavaScript by changing your browser options, and then 
      try again.</font>
    </noscript> 
	  
      
      
      <center>
      <div class="login_wrap">
    <div class="logo">
        <img alt="African Stock" src="<?php echo base_url();?>images/africanstock.png">
    </div>
    <div class="vform">
        <form class="login_wrap" data-inset="true" data-role="controlgroup" data-shadow="true">
            <fieldset class="float">
            <h3 style="color:#603">Change User Password</h3><hr style="margin-top:10px; margin-bottom:10px; border-color:#990000;" />
              <label style="margin-top:-5px; color:#FFF;">Username</label>
                <input name="txtusername" type="text" id="txtusername" style="width:250px; text-transform:none;" class="input" title="Enter Username" placeholder="Enter Username" autofocus required />
                <br><br>
                
                <label style="margin-top:-5px; color:#FFF;">Old Password</label>
                <input name="txtoldpwd" type="password" id="txtoldpwd" style="width:250px; text-transform:none;" title="Enter Old Password" class="input" placeholder="Enter Old Password" autofocus required />
                <br><br>
                
               
               <label style="margin-top:-5px; color:#FFF;">New Password</label>
                <input name="txtnewpwd" type="password" id="txtnewpwd" style="width:250px; text-transform:none;" title="Enter New Password" class="input" placeholder="Enter New Password" autofocus required />
                <br><br>
                
                 <label style="margin-top:-5px; color:#FFF;">Confirm New Password</label>
                <input name="txtcpwd" type="password" id="txtcpwd" style="width:250px; text-transform:none;" title="Confirm New Password" class="input" placeholder="Confirm New Password" autofocus required />
                <br><br>
                                
                <label class="clear"></label>				               
                 <button title="Change Password" id="btnChange" type="button" class="button" role="button" style="text-align:center; width:80px; float:left">
                  <span class="ui-button-text">Change</span>
                  </button>
                  
                <button title="Login" id="btnLogin" type="button" class="button" role="button" style="text-align:center; width:80px; float:left;" >
                  <span class="ui-button-text">Login</span>
                  </button>
                  
                  <button title="Refresh Form" id="btnRefresh" type="button" class="button" role="button" style="text-align:center; width:80px; float:left;" >
                  <span class="ui-button-text">Reset</span>
                  </button>
            </fieldset>
            
            <div class="clear"></div>
               
            <div style="text-align: center;">
                 <br>
                   
                        <div class="clear"></div><br><br>
                </div>
        </form>
                   
    </div>
		<div id="me_box">
		    		    		 </div>
                    
</div>
 </center>   
