<script>
var post_url = "<?php echo base_url();?>ajax/clearlog_model.php";

$(document).ready(function(e) 
{		
	$(document).ajaxStop($.unblockUI);
	LoadUsers();
	
	$('#chkSelect').attr('title','Click To Select All User Accounts');
	$('#lblSelect').attr('title','Click To Select All User Accounts');
		
	function LoadUsers()
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
						
			var mydata={action:'LOAD_USERS'};
			var serializedJSON = JSON.stringify(mydata);
			
			$.ajax({
				type: "POST",
				dataType: 'json',
				data: mydata,
				url: '<?php echo base_url();?>ajax/clearlog_model.php',
				beforeSend: function() {			
					$('#cbousers').empty();
				},
				complete: function(xhr, textStatus) {
					$.unblockUI;
				},
				success: function(data,status,xhr) //we're calling the response json array 'cntry'
				{
					if ($(data).length > 0)
					{						
						$.each($(data), function(i,e)
						{
							$('#cbousers').append( new Option(e.fullname,e.username) );
						});
					}
				},
				error:  function(xhr,status,error) {
					alert('Error '+ xhr.status + ' Occurred: ' + error);
					}
			 }); //end AJAX
		}catch(e)
		{
			alert('LOAD USERS ERROR: '+e)
		}
	}//End LoadUsers
	
	//Clear Entries
    $('#btnClear').click(function(e) {
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
		var from=trim($('#from').val());
		var to=trim($('#to').val());
		
		var users = $("#cbousers").val() || [];
      	var usr=users.join(",");
		
		if (!usr){
			$('#cbousers option').attr('selected', 'selected');	
			users=$("#cbousers").val() || [];
			usr=users.join(",");
		}
		
		if (usr)
		{
			var t=usr.split(',');
			
			if (t.length==$('#cbousers option').length) usr="ALL";
		}
		
		//alert('Selected: '+t.length+'   Total: '+$('#cbousers option').length+'   Users: '+usr);
		
		var mydata={action: 'DELETE_LOG',username:usr, fromdate:from, todate:to};
		var serializedJSON = JSON.stringify(mydata);
		
		$.ajax({
			url: post_url,
			data: mydata,
			type: 'POST',
			dataType: 'json',
			complete: function(xhr, textStatus) {
				$.unblockUI;
			},
			success: function(data,status,xhr) { alert(data); },
			error:  function(xhr,status,error) {
				alert('Error '+ xhr.status + ' Occurred: ' + error);
				}
		});	
		
	});//btnClear.click
	
	$('#btnRefresh').click(function(e) {
		try
		{			
			window.location.reload(true);
		}catch(e)
		{
			alert('CLEAR FORM ERROR:\n'+e); 
			return false;
		}	
	});//End clearForm
	
	$('#from_alternate').click(function() 
	{
		$('#from').datepicker('show');
	});	
	
	$('#to_alternate').click(function() 
	{
		$('#to').datepicker('show');
	});	
	
	$('#chkSelect').click(function(e) {
        try
		{
			if ($(this).attr('checked'))
			{
				$('#cbousers option').attr('selected', 'selected');
				$(this).attr('title','Click To Deselect All User Accounts');
				$('#lblSelect').attr('title','Click To Deselect All User Accounts');
			}else
			{
				$('#cbousers option').removeAttr('selected');
				$(this).attr('title','Click To Select All User Accounts');
				$('#lblSelect').attr('title','Click To Select All User Accounts');
			}
		}catch(e)
		{
			alert('Select Checkbox Click Error:\n'+e);
		}
    });//End chkSelect Click
	
});//End Ready
 
function checkForm()
 {
	  try
	 {
		 var to=document.getElementById("to");
		 var from=document.getElementById("from");
		 var m;
		
		to.value=trim(to.value);
		from.value=trim(from.value);
						
		//From Date
		if (!from.value)
		{
			alert('Starting date (FROM DATE) must be selected'); 		
			from.focus();  return false;
		}
		
		//To date		
		if (!to.value)
		{
			alert('Ending date (TO DATE) must be selected'); 		
			to.focus();  return false;
		}
		
		if (!CompareDates(from.value,to.value))
		{
			alert('"TO" date must not come before "FROM" date.');
			from.focus();
			return false;
		}
		
		if (!confirm('Are you sure you want to delete the log entries for the selected period (Click OK to proceed and CANCEL to abort)?'))
		{
			return false;
		}
							
		return true;			
	 }catch(e)
	 {
		alert('Check Form Error:\n'+e); 
		return false;
	 }
 }//End CheckForm

function trim(str)
{
	if (str) return str.replace(/^\s+|\s+$/g,''); else return '';
}

	 
</script>

<form data-inset="true" data-shadow="true" data-role="controlgroup">
<div align="center">
<fieldset class="fieldset60" data-inset="true">
<h1>Delete Log File Entries</h1>

<table align="center" width="100%">	       
    <tr>
    	<td class="label" align="right" valign="top" title="User Accounts To Delete (Optional). Press and hold down CTRL while clicking on each user account to make multiple selection.">User Account&nbsp;</td>
    	<td align="left"><select multiple="multiple" style="height:150px; width:450px; text-transform:none;" class="input" id="cbousers" name="cbousers" required title="User Accounts To Delete (Optional). Press and hold down CTRL key while clicking on each user account to make multiple selection." ></select>       
        </td>
    </tr>
    
     <tr>
     	<td class="label" align="right" valign="top"></td>
        <td align="left" style="padding-bottom:5px; font-family:Arial, Helvetica, sans-serif; font-weight:bold; font-style:italic; font-size:0.9em; color:#900;">
        	<input type="checkbox" id="chkSelect" name="chkSelect" title="Click To Select All Users" />
        	<label id="lblSelect" for="chkSelect" title="Click To Select All Users">SELECT ALL</label>
        </td>
     </tr>
    
    <tr>
    	<td valign="middle" class="label" align="right" title="FROM Date (Date Format is YYYY-MM-DD)">From Date*&nbsp;</td>
    	<td align="left">
        <input name="from" placeholder="FROM Date" required="required" style="width:90px; text-align:center" class="input" type="text" id="from" />
        
        <input readonly="readonly" type="text" id="from_alternate" class="input" style="width:170px; text-align:center; margin-left:5px;" />        <span style="font-style:italic; color:#900; margin-left:5px;">Format is YYYY-MM-DD</span>
        </td>
    </tr>
    
    <tr>
    	<td class="label" align="right"> To Date*&nbsp;</td>
    	<td align="left" valign="middle"><input readonly="readonly" style="width:90px; text-align:center" class="input" type="text" id="to" name="to" title="TO Date (Date format is YYYY-MM-DD)" placeholder="TO Date" required="required" />
        
        <input readonly="readonly" type="text" id="to_alternate" class="input" style="width:170px; text-align:center; margin-left:5px;" />
        <span style="font-style:italic; color:#900; margin-left:5px;">Format is YYYY-MM-DD</span>
        </td>
    </tr>
    
    <tr>
    	<td></td>
        <td align="left" style="padding-bottom:10px;">
        <?php
        if ($_SESSION['DeleteItem']==1)
			{
				echo '
			 <button title="Clear Log Entries" id="btnClear" type="button" class="button" role="button" style="text-align:center; width:130px;">
				<span class="ui-button-text">Delete Entries</span>
			</button>
				';
			}
         ?>
            
        <button title="Refresh Form" id="btnRefresh" type="button" class="button" role="button" style="text-align:center; width:130px;" >
			<span class="ui-button-text">Refresh</span>
		</button>
		</td>
        </tr>
</table>
     
        </fieldset>
        </div>
        
	</form>
    
<?php //echo $this->load->view("footer"); ?>

