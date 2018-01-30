<style>
	input {padding-left:5px;}
</style>

<script>
var post_url = "<?php echo base_url();?>ajax/general_model.php";

var delflag="<?php echo $_SESSION['DeleteItem']; ?>";
var editflag="<?php echo $_SESSION['EditItem']; ?>";

$(document).ready(function(e)  
{		
	$(document).ajaxStop($.unblockUI);
			
	LoadCurrencies();
			
	function LoadGeneral()
	{
		try
		{
			$.blockUI({ 
				message: '<h1><img src="<?php echo base_url();?>images/loader.gif" /><p>Please Wait...</p></h1>',
				css: { 
				border: 'none', 
				padding: '15px', 
				backgroundColor: '#000', 
				'-webkit-border-radius': '10px', 
				'-moz-border-radius': '10px', 
				opacity: 1, 
				color: '#fff' 
			} });
			
			var mydata={action: 'LOAD_GENERAL'};
			
			$.ajax({
				type: "POST",
				dataType: 'json',
				data: mydata,
				url: post_url,
				beforeSend: function() {
					$('#txtcompany').val('');
					$('#txtaddress').val('');
					$('#txtphone').val('');
					$('#txtemail').val('');
					$('#txtpaymenturl').val('');
					$('#txtresturl').val('');
					$('#txtproductid').val('');
					$('#txtpayitemid').val('');
					$('#cboCurrency').val('');	
					$('#txtmac').val('');
				},
				complete: function(xhr, textStatus) {
					$.unblockUI;
				},
				success: function(data,status,xhr) //we're calling the response json array 'cntry'
				{
					if ($(data).length > 0)
					{
						//company,address,phone,email,interswitch_payment_url,interswitch_REST_url,interswitch_product_id,interswitch_pay_item_id,interswitch_currency,interswitch_mac_key
						$.each($(data), function(i,e)
						{
							if (e.company) $('#txtcompany').val(e.company);
							if (e.address) $('#txtaddress').val(e.address);
							if (e.phone) $('#txtphone').val(e.phone);
							if (e.email) $('#txtemail').val(e.email);
							if (e.interswitch_payment_url) $('#txtpaymenturl').val(e.interswitch_payment_url);
							if (e.interswitch_REST_url) $('#txtresturl').val(e.interswitch_REST_url);
							if (e.interswitch_product_id) $('#txtproductid').val(e.interswitch_product_id);
							if (e.interswitch_pay_item_id) $('#txtpayitemid').val(e.interswitch_pay_item_id);		
							if (e.interswitch_currency) $('#cboCurrency').val(e.interswitch_currency);
							if (e.interswitch_mac_key) $('#txtmac').val(e.interswitch_mac_key);
						});
					}
				},
				error:  function(xhr,status,error) {
					alert('Error '+ xhr.status + ' Occurred: ' + error);
					}
			 }); //end AJAX
		}catch(e)
		{
			alert('Error LOADING PARAMETERS ERROR:\n'+e);
		}
	}//End LoadGeneral
	
	function LoadCurrencies()
	{
		try
		{
			$.blockUI({ 
				message: '<h1><img src="<?php echo base_url();?>images/loader.gif" /><p>Please Wait...</p></h1>',
				css: { 
				border: 'none', 
				padding: '15px', 
				backgroundColor: '#000', 
				'-webkit-border-radius': '10px', 
				'-moz-border-radius': '10px', 
				opacity: 1, 
				color: '#fff' 
			} });
			
			var mydata={action: 'LOAD_CURRENCIES'};
			
			$.ajax({
				type: "POST",
				dataType: 'json',
				data: mydata,
				url: post_url,
				beforeSend: function() {
					$('#cboCurrency').empty();
				},
				complete: function(xhr, textStatus) {
					LoadGeneral();
					$.unblockUI;
				},
				success: function(data,status,xhr) //we're calling the response json array 'cntry'
				{
					if ($(data).length > 0)
					{
						$('#cboCurrency').append( new Option('[SELECT]','') );		
						$.each($(data), function(i,e)
						{//currencycode,currency
							$('#cboCurrency').append( new Option(e.currency,e.currencycode) );	
						});
					}
				},
				error:  function(xhr,status,error) {
					alert('Error '+ xhr.status + ' Occurred: ' + error);
					}
			 }); //end AJAX
		}catch(e)
		{
			alert('Error LOADING CURRENCIES ERROR:\n'+e);
		}
	}//End LoadAcademicSession
	
			
	//Update General Info Record
    $('#btnUpdate').click(function(e) {
		if (!checkForm('UPDATE')) return false;
		
		//Send values here
		$.blockUI({ 
			message: '<h1><img src="<?php echo base_url();?>images/loader.gif" /><p>Please Wait...</p></h1>',
			css: { 
			border: 'none', 
			padding: '15px', 
			backgroundColor: '#000', 
			'-webkit-border-radius': '10px', 
			'-moz-border-radius': '10px', 
			opacity: 1, 
			color: '#fff' 
		} });
		
		//Make Ajax Request
		var cm=$.trim($("#txtcompany").val());
		var ad=$.trim($("#txtaddress").val());
		var ph=$.trim($("#txtphone").val());
		var em=$.trim($("#txtemail").val());
		var purl=$.trim($("#txtpaymenturl").val());
		var rurl=$.trim($("#txtresturl").val());
		var pid=$.trim($('#txtproductid').val());
		var payid=$.trim($('#txtpayitemid').val());
		var mac=$.trim($('#txtmac').val());
		var cu=$("#cboCurrency").val();
		
		var mydata={action:'UPDATE_INFO',address:ad, company:cm, phone:ph, email:em, interswitch_payment_url:purl, interswitch_REST_url:rurl, interswitch_product_id:pid, interswitch_pay_item_id:payid, interswitch_currency:cu, interswitch_mac_key:mac};
		
		$.ajax({
			url: post_url,
			data: mydata,
			type: 'POST',
			dataType: 'json',
			complete: function(xhr, textStatus) {
				$.unblockUI;
				LoadGeneral();
			},
			success: function(data,status,xhr) {
				alert(data);
			},
			error:  function(xhr,status,error) {
				alert('Error '+ xhr.status + ' Occurred: ' + error);
				}
		});	
		
	});//btnUpdate.click
	
	$('#btnDelete').click(function(e) {
        try
		 {									
			if (!confirm('Are you sure you want to delete the general information from the database?. Please note that this action is irreversible. To continue, click OK otherwise, click CANCEL'))
			{
				return false;
			}else//Delete
			{
				$.blockUI({ 
						message: '<h1><img src="<?php echo base_url();?>images/loader.gif" /><p>Please Wait...</p></h1>',
						css: { 
						border: 'none', 
						padding: '15px', 
						backgroundColor: '#000', 
						'-webkit-border-radius': '10px', 
						'-moz-border-radius': '10px', 
						opacity: 1, 
						color: '#fff' 
					} });
					
				//Make Ajax Request
				var mydata={action:'DELETE_INFO'};
				var serializedJSON = JSON.stringify(mydata);
				
				$.ajax({
					url: '<?php echo base_url();?>ajax/general_model.php',
					data: mydata,
					type: 'POST',
					dataType: 'json',
					complete: function(xhr, textStatus) {
						$.unblockUI;
						LoadGeneral();
						},
					success: function(data,status,xhr) {alert(data);},
					error:  function(xhr,status,error) {
						alert('Error '+ xhr.status + ' Occurred: ' + error);
						}
				});
			}//End Delete
		 }catch(e)
		 {
			alert('Delete ERROR: '+e);
			return false;
		 }
    });
});//End Ready

function checkForm(fn)
 {
	  try
	 {
		var cm=$.trim($("#txtcompany").val());
		var address=$.trim($("#txtaddress").val());
		var phone=$.trim($("#txtphone").val());
		var email=$.trim($("#txtemail").val());		
		var purl=$.trim($("#txtpaymenturl").val());
		var rurl=$.trim($("#txtresturl").val());
		var pid=$.trim($('#txtproductid').val());
		var payid=$.trim($('#txtpayitemid').val());
		var currency=$("#cboCurrency").val();	
		var mac=$.trim($("#txtmac").val());
				
		var m;
		
										
		//Validate Company Name
		if (!cm)
		{
			alert("Please enter company name.");  $("#txtcompany").focus(); return false;
		}
				
		if (!isNaN(cm))
		{
			alert("Company name must not be a number."); $("#txtcompany").focus();  return false;
		}
		
		//Validate address
		if (address)
		{
			if (!isNaN(address))
			{
				alert("Company address must not be a number."); $("#txtaddress").focus();  return false;
			}	
		}
		
		//Email
		if (email)
		{
			//  /^[a-zA-Z0-9.!#$%&’*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/
			var rx=new RegExp("^[\\w\.=-]+@[\\w\\.-]+\\.[a-zA-Z]{2,4}$");
			if(!rx.test(email))
			{
				alert('Invalid company email address.');   $('#txtemail').focus(); return false;
			}
		}
		
		//Validate Payment URL
		if (!purl)
		{
			alert("Please enter Interswitch payment url. You may contact your system administrator.");  $('#txtpaymenturl').focus(); return false;
		}
		
		if (purl.toLowerCase().indexOf('http')== -1)
		{
			alert("Please enter a valid Interswitch payment url. You may contact your system administrator.");  $('#txtpaymenturl').focus(); return false;
		}
		
		//REST url
		if (!rurl)
		{
			alert("Please enter Interswitch REST url. You may contact your system administrator.");  $('#txtresturl').focus(); return false;
		}
		
		if (rurl.toLowerCase().indexOf('http')== -1)
		{
			alert("Please enter a valid Interswitch REST url. You may contact your system administrator.");  $('#txtresturl').focus(); return false;
		}
		
		//Interswitch Product ID
		if (!pid)
		{
			alert("Please enter Interswitch Product ID. You may contact your system administrator.");  $("#txtproductid").focus(); return false;
		}
		
		//Interswitch Payitem ID
		if (!payid)
		{
			alert("Please enter Interswitch Payitem ID. You may contact your system administrator.");  $("#txtpayitemid").focus(); return false;
		}
		
		//Interswitch Default Currency
		if (!currency)
		{
			m='Please select the Interswitch default currency. You may contact your system administrator.';  alert(m); $("#cboCurrency").focus();  return false;
		}
		
		//	Interswitch MAC Key
		if (!mac)
		{
			alert("Please enter Interswitch MAC Key. You may contact your system administrator."); $("#txtmac").focus();  return false;
		}
		
		if (!confirm('Are you sure you want to '+fn+' this record (Click OK to proceed and CANCEL to abort)?'))
		{
			return false;
		}
							
		return true;			
	 }catch(e)
	 {
		alert('CHECK FORM ERROR:\n'+e); 
		return false;
	 }
 }//End CheckForm

</script>


<form data-inset="true" data-shadow="true" data-role="controlgroup">
<div align="center">
<fieldset class="fieldset75" data-inset="true">
<h1>General Information</h1>


<table align="center" width="100%">	   
    <tr>
    	<td width="27%" align="right" class="label">Company*&nbsp;</td>
    	<td width="73%" align="left"><input style="text-transform:capitalize; width:70%;" class="input" type="text" id="txtcompany" name="txtcompany" required title="Company Name" placeholder="Enter Company Name" autofocus /></td>
    	</tr>
    
    <tr>
    	<td class="label" align="right">Address&nbsp;</td>
    	<td align="left"><input style="text-transform:capitalize;width:70%;" class="input" type="text" id="txtaddress" name="txtaddress" required title="Company Address" placeholder="Enter Company Address" /></td>
    	</tr>
    
    <tr>
    	<td class="label" align="right">Phone No&nbsp;</td>
    	<td align="left"><input class="input" type="text" id="txtphone" name="txtphone" title="Company Phone Number" placeholder="Enter Company Phone No" required style="width:70%;" /></td>
    	</tr>
    
    <tr>
      <td class="label" align="right">Email</td>
      <td align="left"><input class="input" type="text" id="txtemail" name="txtemail" title="Company Email" placeholder="Enter Company Email" style="text-transform:none;width:70%;" /></td>
      </tr>
    <tr>
    	<td class="label" align="right">Interswitch Payment URL*&nbsp;</td>
    	<td align="left"><input class="input" type="text" id="txtpaymenturl" name="txtpaymenturl" title="Interswitch Payment URL" placeholder="Enter Interswitch Payment URL" style="text-transform:none;width:70%;" /></td>
    	</tr>
    
    <tr>
    	<td class="label" align="right">Interswitch REST URL*&nbsp;</td>
    	<td align="left"><input class="input" type="text" id="txtresturl" name="txtresturl" title="Interswitch REST URL" placeholder="Enter Interswitch REST URL" style="text-transform:none;width:70%;" /></td>
    	</tr>
    
    <tr>
    	<td  class="label" align="right">Interswitch Product ID*&nbsp;</td>
        <td align="left"><input class="input" type="text" id="txtproductid" name="txtproductid" title="Interswitch Product ID" placeholder="Enter Interswitch Product ID" style="text-transform:none;width:70%;" /></td>
        </tr>
    
    <tr>
    	<td class="label" align="right">Interswitch Payitem ID*</td>
    	<td align="left"><input class="input" type="text" id="txtpayitemid" name="txtpayitemid" title="Interswitch Payitem ID" placeholder="Enter Interswitch Payitem ID" style="text-transform:none;width:70%;" /></td>
    	</tr>
        
    <tr>
    	<td class="label" align="right">Interswitch Default Currency*</td>
    	<td align="left"><select name="cboCurrency" id="cboCurrency" class="input" title="Interswitch Default Currency">
            </select></td>
    	</tr>
    
     <tr>
       <td class="label" align="right"  valign="top">Interswitch MAC Key*</td>
       <td align="left"  valign="top"> 
        <textarea class="input" type="text" id="txtmac" name="txtmac" title="Interswitch MAC Key" placeholder="Enter Interswitch MAC Key" style="text-transform:none; width:70%; height:50px;"></textarea>
       </td>
       </tr>
        
         
    <tr>
    	<td></td>
        <td align="left">
          <?php
        if (($_SESSION['AddItem']==1) || ($_SESSION['EditItem']==1))
		{
			echo '
		 <button title="Update Record" id="btnUpdate" type="button" class="button" role="button" style="text-align:center;">
			<span class="ui-button-text">Update</span>
		</button>
			';
		}
         ?>
          
          <?php
        if ($_SESSION['DeleteItem']==1)
		{
			echo '
		 <button title="Delete Record" id="btnDelete" type="button" class="button" role="button" style="text-align:center;">
			<span class="ui-button-text">Delete</span>
		</button>
			';
		}
         ?>
          
          <button onClick="window.location.reload(true);" title="Refresh Form" id="btnRefresh" type="button" class="button" role="button" >
            <span class="ui-button-text">Refresh</span>
            </button>
        </td>
        </tr>
</table>
         
        </fieldset>
        </div>
        
	</form>
    
<?php //echo $this->load->view("footer"); ?>

