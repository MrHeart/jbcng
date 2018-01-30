<style>
	input {padding-left:5px;}
</style>

<script>
var post_url = "<?php echo base_url();?>ajax/checkstatus_model.php";
	
function addTransaction(sn,ref,amt,code,desc,tdate,pref,nm)
{
	try
	{
		var tr;	
				
		if ((sn % 2)==0)
		{
			tr=$('<tr>').attr('bgcolor','#E3EDF8').attr('align','center');
		}else
		{
			tr=$('<tr>').attr('bgcolor','#D1E7F5').attr('align','center');
		}
			
		tr.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'Row '+ sn}).html(sn))
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'Transaction Reference'}).html(ref))
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'Transaction Amount'}).html(number_format(amt,2,'.',',')))	
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'Response Code'}).html(code))
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'Response Code Description'}).html(desc))	
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'Transaction Date'}).html(tdate))
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'Payment Reference'}).html(pref))	
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'Support Staff'}).html(nm))				
		.appendTo('#resultsbody');
	}catch(e)
	{
		alert('Add Transaction Error: '+e)
	}
}//End addTransaction
	
var delflag="<?php echo $_SESSION['DeleteItem']; ?>";
var editflag="<?php echo $_SESSION['EditItem']; ?>";

$(document).ready(function(e)  
{
	$(document).ajaxStop($.unblockUI);
				
	var options = {
		  currPage : 1, 
		  optionsForRows : [5,7,10,15,20,25],
		  rowsPerPage : 10,
		  firstArrow : (new Image()).src="<?php echo base_url();?>images/first.png",
		  prevArrow : (new Image()).src="<?php echo base_url();?>images/prev.png",
		  lastArrow : (new Image()).src="<?php echo base_url();?>images/last.png",
		  nextArrow : (new Image()).src="<?php echo base_url();?>images/next.png",
		  topNav : false
		}
	
	$('#recorddisplay').tablePagination(options);
	
	$('#from_alternate').click(function() 
	{
		$('#from').datepicker('show');
	});	
	
	$('#to_alternate').click(function() 
	{
		$('#to').datepicker('show');
	});
	
	LoadInterswitchValues();
	
	function LoadInterswitchValues()
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
			
			var mydata={action: 'LOAD_INTERSWITCH_VALUES'};
			
			$.ajax({
				type: "POST",
				dataType: 'json',
				data: mydata,
				url: post_url,
				beforeSend: function() {					
					$('#txtpid').val('');
					$('#txtmac').val('');
				},
				complete: function(xhr, textStatus) {
					$.unblockUI;
				},
				success: function(data,status,xhr) //we're calling the response json array 'cntry'
				{
					if ($(data).length > 0)
					{						
						$.each($(data), function(i,e)
						{//interswitch_product_id,interswitch_mac_key
							if (e.interswitch_product_id) $('#txtpid').val(e.interswitch_product_id);
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
			alert('Error Loading Interswitch Values:\n'+e);
		}
	}//End LoadInterswitchValues
	
	$('#btnClear').click(function(e) {
        try
		{
			resetFilter();
		}catch(e)
		{
			alert('Clear Button Click Error: ' + e);
			return false;
		}
    });
	
	//Add Customer Record
    $('#btnAdd').click(function(e) {
		if (!checkForm()) return false;
		
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
		var ref=$.trim($('#txtref').val());
		var amt=$.trim($('#txtamount').val().replace(new RegExp(',', 'g'), ''));
		var pid=$.trim($('#txtpid').val());
		var mac=$.trim($('#txtmac').val());	
					
		var mydata={action:'ADD_TRANSACTION', trans_ref:ref, amount:amt, interswitch_product_id:pid, interswitch_mac_key:mac};
		
		$.ajax({
			url: post_url,
			data: mydata,
			type: 'POST',
			dataType: 'json',
			complete: function(xhr, textStatus) {
				$.unblockUI;
			},
			success: function(data,status,xhr) {				
				//Clear boxes
				$('#txtamount').val(''); $('#txtref').val('');
				
				alert(data);
			},
			error:  function(xhr,status,error) {
				alert('Error '+ xhr.status + ' Occurred: ' + error);
				}
		});	
		
	});//btnAdd.click
	
	//Display Transaction Record
    $('#btnDisplay').click(function(e) {
		if (!checkFilter()) return false;
		
		var options = {
		  currPage : 1, 
		  optionsForRows : [5,7,10,15,20,25,30,35,50,75,100],
		  rowsPerPage : 10,
		  firstArrow : (new Image()).src="<?php echo base_url();?>images/first.png",
		  prevArrow : (new Image()).src="<?php echo base_url();?>images/prev.png",
		  lastArrow : (new Image()).src="<?php echo base_url();?>images/last.png",
		  nextArrow : (new Image()).src="<?php echo base_url();?>images/next.png",
		  topNav : false
		}
	
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
		var from=$.trim($('#from').val());
		var to=$.trim($('#to').val());
		
		var mydata={action:'DISPLAY_RECORDS',FromDate:from, ToDate:to};
		
		//var serializedJSON = JSON.stringify(mydata);
		
		$.ajax({
			url: post_url,
			data: mydata,
			type: 'POST',
			dataType: 'json',
			beforeSend: function() {		
				$('#resultsbody').remove();//Clear Table
			},
			complete: function(xhr, textStatus) {
				$('#recorddisplay').tablePagination(options);
				$.unblockUI;
			},
			success: function(data,status,xhr) {				
				if ($(data).length > 0)
				{
					$('<tbody>').attr('id','resultsbody').appendTo('#recorddisplay');
											
					$.each($(data), function(i,e)
					{
//trans_ref,amount,responsecode,responsedescription,trans_date,paymentreference,support_name,support_username

						var sn=i+1;
						var ref=e.trans_ref;
						var amt=e.amount;
						var code=e.responsecode;
						var desc=e.responsedescription;
						var tdate=e.trans_date;
						var pref=e.paymentreference;
						var nm='';
						
						if (e.support_username) nm=e.support_username;
						
						if (e.support_name)
						{
							if ($.trim(nm) != '') nm += ' [' + e.support_name + ']'; else nm = e.support_name;
						}
						
						
						addTransaction(sn,ref,amt,code,desc,tdate,pref,nm);
					});
				}
			},
			error:  function(xhr,status,error) {
				alert('Error '+ xhr.status + ' Occurred: ' + error);
				}
		});	
		
	});//btnDisplay.click
});//End Ready

function checkFilter()
{
	try
	{
		var fm=$.trim($('#from').val());
		var to=$.trim($('#to').val());
		
		var m='';
										
		//From Date
		if (!fm)
		{
			alert('Starting date (FROM DATE) must be selected'); 		
			$('#from').focus();  return false;
		}
		
		//To date		
		if (!to)
		{
			alert('Ending date (TO DATE) must be selected'); 		
			$('#to').focus();  return false;
		}
				
		return true;
	}catch(e)
	{
		alert('Check Filter Error:\n'+e); 
		return false;
	}
}

function resetFilter()
{
	try
	{		
		$('#from').val('');		$('#from_alternate').val('');
		$('#to').val('');		$('#to_alternate').val('');
		
		$('#resultsbody').remove();//Clear Table
		//$('<tbody>').attr('id','resultsbody').appendTo('#recorddisplay');
		
		return true;
	}catch(e)
	{
		alert('Reset Filter Error:\n'+e); 
		return false;
	}
}

function checkForm()
 {
	  try
	 {
		var ref=$.trim($('#txtref').val());
		var amt=$.trim($('#txtamount').val().replace(new RegExp(',', 'g'), ''));
		var pid=$.trim($('#txtpid').val());
		var mac=$.trim($('#txtmac').val());
		
		var m;
				
		//Validate transaction reference number
		if (!ref)
		{
			m="Please enter transaction reference number.";
			alert(m);  $('#txtref').focus(); return false;
		}
				
		//Amount
		if (!amt)
		{
			m='Transaction amount MUST NOT be blank.';
			alert(m);  $('#txtamount').focus(); return false;
		}
		
		if (isNaN(amt))
		{
			m='Transaction amount MUST be a number.';
			alert(m);   $('#txtamount').focus(); return false;
		}
		
		if (parseFloat(amt)==0)
		{
			m='Transaction amount MUST NOT be zero.';
			alert(m);   $('#txtamount').focus(); return false;
		}
		
		if (parseFloat(amt)<0)
		{
			m='Transaction amount MUST NOT be a negative number.';
			alert(m);   $('#txtamount').focus(); return false;
		}
		
		//Interswitch Product ID
		if (!pid)
		{
			m='Interswitch Product ID MUST NOT be blank. Please make sure the correct product ID from Interswitch is captured under "System Settings" item of the "Admin" menu. You may contact your system administrator.';
			alert(m);  $('#txtpid').focus(); return false;
		}
		
		//Interswitch MAC Key ID
		if (!pid)
		{
			m='Interswitch MAC Key MUST NOT be blank. Please make sure the correct MAC Key from Interswitch is captured under "System Settings" item of the "Admin" menu. You may contact your system administrator.';
			alert(m);  $('#txtmac').focus(); return false;
		}
		
		if (!confirm('Are you sure you want to check the payment status for this transaction (Click OK to proceed and CANCEL to abort)?'))
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

function clearForm()
{
	try
	{		
		window.location.reload(true);
		
		return true;
	}catch(e)
	{
		alert('CLEAR FORM ERROR:\n'+e); 
		return false;
	}	
}//End Clear Form
  
</script>

<form data-inset="true" data-shadow="true" data-role="controlgroup">
<div align="center">

<fieldset class="fieldset98" data-inset="true">
<h1>Check Payment Status</h1>
<!--Tab-->
<div id="mytabs">        			
    <ul class="nav">
        <li class="nav-one" title="Check Payment Status" id="tabGeneral"><a href="#general" class="current" title="Check Payment Status">Check Status</a></li>
        <li class="nav-two" title="View Payment Status Records" id="tabView"><a href="#view" title="View Payment Status Records">View Payment Status Records</a></li>
    </ul>
    
    <div class="list-wrap"><!-- START Contents -->    
        <ul id="general">
            <table align="center" width="100%">	
            <tr>
                <td  class="label" align="right">Transaction Reference*&nbsp;</td>
                <td align="left"><input class="input" type="text" id="txtref" name="txtref" title="Transaction Reference Code" placeholder="Enter Transaction Reference Code" style="text-transform:none" /></td>
            </tr>
            
            <tr>
                <td valign="middle" class="label" align="right" title="Transaction Amount">Transaction Amount*&nbsp;</td>
                <td align="left"><input class="input" type="text" id="txtamount" name="txtamount" title="Transaction Amount" placeholder="Enter Transaction Amount" style="text-transform:none" /></td>
            </tr>
                       
             <tr>
                <td class="label" align="right" title="Interswitch Product ID">Interswitch Product ID*&nbsp;</td>
                <td align="left"><input class="input" type="text" id="txtpid" name="txtpid" title="Interswitch Product ID" readonly="readonly" style="text-transform:none" /></td>
            </tr>
            
            <tr>
                <td class="label" align="right" title="Interswitch MAC Key" valign="top">Interswitch MAC Key*&nbsp;</td>
                <td align="left"><textarea class="input" type="text" id="txtmac" name="txtmac" title="Interswitch MAC Key" placeholder="Enter Interswitch MAC Key" style="text-transform:none; width:70%; height:50px;" readonly="readonly"></textarea></td>
            </tr>    
                       
            
            <tr>
                <td></td>
                <td align="left">
                <?php
				if (($_SESSION['AddItem']==1) || ($_SESSION['EditItem']==1))
				{
					echo '
				 <button title="Check Payment Status" id="btnAdd" type="button" class="button" role="button" style="text-align:center;width:190px; height:40px;">
					<span class="ui-button-text">Check Payment Status</span>
				</button>
					';
				}
			 ?>
			 				  
                
                <button onClick="window.location.reload(true);" title="Refresh Form" id="btnRefresh" type="button" class="button" role="button" style="width:190px; height:40px;" >
                    <span class="ui-button-text">Reset</span>
                </button>
                </td>
                </tr>
        </table>
        </ul>
         
         <ul id="view" class="hide">
            <!--Display Table-->
            <table align="center" width="100%" border="0">	
        <tr>
            <td width="11%" align="right" valign="middle" class="label">From Date*&nbsp;</td>
            <td width="25%" align="left" valign="middle">
            <input readonly name="from" placeholder="FROM Date" required style="width:90px; text-align:center" class="input" type="text" id="from" />
            
            <input readonly type="text" id="from_alternate" class="input" style="width:170px; text-align:center; margin-left:5px;" />
            </td>
       
            <td width="10%" align="right" class="label">To Date*&nbsp;</td>
            <td width="28%" align="left">
            <input readonly name="to" placeholder="TO Date" required style="width:90px; text-align:center" class="input" type="text" id="to" />
            <input readonly type="text" id="to_alternate" class="input" style="width:170px; text-align:center; margin-left:5px;" />
            </td>
            
             <td width="15%" style="padding-left:15px;"><button title="Display Transaction Records" id="btnDisplay" type="button" class="button2" style="text-align:center; width:170px;">
                <span class="ui-button-text">Display Transactions</span>
            </button>
           </td>
           
           <td width="11%" colspan="8"> <button title="Reset Options" id="btnClear" type="button" class="button2" role="button" style="width:120px;" >
                <span class="ui-button-text">Clear</span>
            </button></td>
        </tr>
        
        <tr>
            <td></td>
           
            
        </tr>
        
    </table>
            
            <table id="recorddisplay" width="100%" border="0" cellpadding="3" cellspacing="0" title="Available Users" class="displayborder">
                  <thead>
                    <tr class="bold" align="center" bgcolor="#333300" style="color:#ffffff">
                        <td width="6%" class="leftbottomborder" style="padding-top:5px; padding-bottom:5px"><strong>S/NO.</strong></td>
                                                                    
                        <td title="Transaction Reference" width="13%" class="leftbottomborder" style="padding-top:5px; padding-bottom:5px"><strong>TRANSACTION REFERENCE</strong></td>
                        
                        <td title="Transaction Amount" width="12%" class="leftbottomborder" style="padding-top:5px; padding-bottom:5px"><strong>AMOUNT</strong></td>
                        
                        <td title="Response Code" width="9%" class="leftbottomborder" style="padding-top:5px; padding-bottom:5px"><strong>RESPONSE CODE</strong></td>
                        
                        <td title="Response Code Description" width="18%" class="leftbottomborder" style="padding-top:5px; padding-bottom:5px"><strong>RESPONSE DESCRIPTION</strong></td>
                        
                        <td title="Transaction Date" width="13%" class="leftbottomborder" style="padding-top:5px; padding-bottom:5px"><strong>TRANSACTION DATE</strong></td>
                        
                        <td title="Payment Reference" width="14%" class="leftbottomborder" style="padding-top:5px; padding-bottom:5px"><strong>PAYMENT REFERENCE</strong></td>
                        
                        <td title="Support Staff" width="15%" class="leftbottomborder" style="padding-top:5px; padding-bottom:5px"><strong>SUPPORT STAFF</strong></td>
                    </tr>
                  </thead>
                  
                  <tbody id="resultsbody"></tbody>        
              </table>
         </ul>
         
     </div> <!-- END Contents -->
 </div> <!-- END General Tabs -->          
         
         <input type="hidden" name="hidID" id="hidID" value="" /> 
        </fieldset>
        </div>
        
	</form>
    
     <script>
        $(function() {
            $("#mytabs").organicTabs();
        });
		
    </script>    
<?php //echo $this->load->view("footer"); ?>

