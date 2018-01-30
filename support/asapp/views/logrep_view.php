<script>
var post_url = "<?php echo base_url();?>ajax/logrep_model.php";

$(document).ready(function(e) 
{		
	$(document).ajaxStop($.unblockUI);
		
	LoadUsers();
	
	$('#from_alternate').click(function() 
	{
		$('#from').datepicker('show');
	});	
	
	$('#to_alternate').click(function() 
	{
		$('#to').datepicker('show');
	});	
			
	$('#btnRefresh').click(function(e) {
        try
		{
			window.location.reload(true);
		}catch(e)
		{
			alert('CLEAR FORM ERROR:\n'+e); 
			return false;
		}
    });
	
	$('#divUser').click(function(e) {
       try
	   {
			var cnt=$('#tbUser tr td').length;
			var sel=0;
		  
			$('#tbUser tr td').each(function(i, e) {
				if ($('#chk'+i).prop('checked')==true) sel +=1;
			});
		
			if (cnt==sel) $('#chkSelect').prop('checked',true); else  $('#chkSelect').prop('checked',false);
	   }catch(e)
	   {
		   	alert('DIV CLICK ERROR:\n'+e); 
			return false;
	   }
    });
	
	$('#chkSelect').click(function(e) {
        try
		{
			var usr=$('#divUser').html();
			var cnt=$('#tbUser tr td').length;
						
			if ($(this).attr('checked'))
			{
				$('#tbUser tr td').each(function(i, e) {
                    $('#chk'+i).prop('checked', true);
					$(this).attr('title','Click To Deselect All Users');
					$('#lblSelect').attr('title','Click To Deselect All Users');
                });				
			}else
			{
				$('#tbUser tr td').each(function(i, e) {
					$('#chk'+i).prop('checked', false);
					$(this).attr('title','Click To Select All Users');
					$('#lblSelect').attr('title','Click To Select All Users');
				});	
			}
		}catch(e)
		{
			alert('Select Checkbox Click Error:\n'+e);
		}
    });//End chkSelect Click
	
	//Display Record
    $('#btnDisplay').click(function(e) {
		if (!checkForm()) return false;
		
		//Send values here
		
		$.blockUI({ 
			message: '<h1><img src="<?php echo base_url();?>images/loader.gif" /><p>Generating Report. Please Wait...</p></h1>',
			css: { 
			border: 'none', 
			padding: '15px', 
			backgroundColor: '#000', 
			'-webkit-border-radius': '10px', 
			'-moz-border-radius': '10px', 
			opacity: 1, 
			color: '#fff' 
		} });
			
		var to=$.trim($("#to").val());
		var from=$.trim($("#from").val());
		
		var cnt=$('#tbUser tr td').length;
		var sel=0;
		var usr='';
	  
		$('#tbUser tr td').each(function(i, e) {
			if ($('#chk'+i).prop('checked')==true)
			{
				sel +=1;
				
				if (usr=='') usr=$('#chk'+i).val(); else usr += ','+$('#chk'+i).val();
			}
		});
	
		if (cnt==sel) usr='ALL';

		var mydata={action: 'DISPLAY_REPORT',User:usr, fromdate:from, todate:to};
				
		$.ajax({
			url: "<?php echo base_url();?>reportmaker/logrep_report.php",
			data: mydata,
			type: 'POST',
			dataType: 'json',
			complete: function(xhr, textStatus) {
				$.unblockUI;
			},
			success: function(data,status,xhr) {				
				//Clear boxes
				var fl='';
				fl=data.toString();
				
				if (fl=='There is no audit trail for the selected query criteria')
				{
					alert(fl);
				}else
				{
					if (fl)
					{
						window.open('<?php echo base_url(); ?>reports/'+fl,null,'left=0, top=0, height=700, width= 1000, status=no, resizable= yes, scrollbars= yes, toolbar= no,location= no, menubar= no');
					}
				}
				
              // alert(fl);				
			},
			error:  function(xhr,status,error) {
				alert('Error '+ xhr.status + ' Occurred: ' + error);
				}
		});		
	});//btnDisplay.click
	
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
				url: '<?php echo base_url();?>ajax/logrep_model.php',
				beforeSend: function() {					
					$('#divUser').html('');
				},
				complete: function(xhr, textStatus) {
					$.unblockUI;
				},
				success: function(data,status,xhr) //we're calling the response json array 'cntry'
				{
					if ($(data).length > 0)
					{//name,username
						var tb='<table id="tbUser" align="center" width="100%" cellpadding="0" cellspacing="0">';
						var j=0;
											
						$.each($(data), function(i,e)
						{//user
							if (e.username && e.name)
							{							
								j++;
								
								tb += '<tr><td align="left" title="Select '+e.name+'"><input id="chk'+i+'" type="checkbox" value="'+e.username+'" />&nbsp;&nbsp;'+e.name+'</td><tr>';
							}
						});//End each
																
						tb +='</table>';		
													
						$("#divUser").append(tb);
					}
				},
				error:  function(xhr,status,error) {
					alert('Error '+ xhr.status + ' Occurred: ' + error);
					}
			 }); //end AJAX
		}catch(e)
		{
			alert('LOAD USERS ERROR:\n'+e)
		}
	}//End LoadUsers
	
	function checkForm()
	 {
		 try
		 {
			var to=document.getElementById("to");
			var from=document.getElementById("from");
			var usr=$('#divUser').html();
			
			var m;
			
			to.value=trim(to.value);
			from.value=trim(from.value);
				
					
			//User
			if (!usr)
			{
				m='No user record has been captured. Please contact your system administrator.';
				alert(m); $.unblockUI; return false;
			}

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
				
			return true;			
		 }catch(e)
		 {
			alert('CHECK FORM ERROR:\n'+e); 
			return false;
		 }
	 }//End CheckForm
	
	function trim(str)
	{
		 str=str.toString();
		 
		return str.replace(/^\s+|\s+$/g,'');
	}
	

});//End	 
</script>

<form data-inset="true" data-shadow="true" data-role="controlgroup">
<div align="center">
<fieldset class="fieldset50" data-inset="true">
<h1>Audit Trail Report</h1>

<table width="100%">            
            <tr>
                <td valign="top" class="label" align="right" title="System Users. Press and hold down CTRL key while clicking on each user to make multiple selections.">Users&nbsp;</td>
                <td>                    
                    <div id="divUser" style="border:2px solid #ccc; width:350px; height: 170px; overflow-y: auto; background-color:#ddd; padding-left:3px;">
                    </div>
            	</td>
            </tr>
            
             <tr>
     	<td class="label" align="right" valign="top"></td>
        <td align="left" style="padding-bottom:5px; font-family:Arial, Helvetica, sans-serif; font-weight:bold; font-style:italic; font-size:0.9em; color:#900;">
        	<input type="checkbox" id="chkSelect" name="chkSelect" title="Click To Select All Users" />
        	<label id="lblSelect" for="chkSelect" title="Click To Select All Users">Select All Users</label>
        </td>
     </tr>
            
            <tr>
                <td class="label" align="right" title="FROM Date (Date Format is YYYY-MM-DD)">From Date*&nbsp;</td>
                <td title="FROM Date (Date Format is YYYY-MM-DD)"><input name="from" placeholder="FROM Date" required="required" style="width:90px; text-align:center" class="input" type="text" id="from" />
        
        <input readonly="readonly" type="text" id="from_alternate" class="input" style="width:170px; text-align:center; margin-left:5px;" />        <span style="font-style:italic; color:#900; margin-left:5px;">Y-M-D</span></td>
            </tr>
            
            <tr>
                <td class="label" align="right" title="TO Date (Date Format is YYYY-MM-DD)">To Date*&nbsp;</td>
                <td align="left" title="To Date (Date Format is YYYY-MM-DD)"><input readonly="readonly" style="width:90px; text-align:center" class="input" type="text" id="to" name="to" title="TO Date (Date format is YYYY-MM-DD)" placeholder="TO Date" required="required" />
        
        <input readonly="readonly" type="text" id="to_alternate" class="input" style="width:170px; text-align:center; margin-left:5px;" />
        <span style="font-style:italic; color:#900; margin-left:5px;">Y-M-D</span></td>
            </tr>
            
            <tr>
            	<td></td>
              <td>        
                <button title="Display Report" id="btnDisplay" type="button" class="button" role="button" style="text-align:center; width:140px;">
                  <span class="ui-button-text">Display Report</span>
                  </button>
                
                       
                <button title="Refresh Form" id="btnRefresh" type="button" class="button" role="button" style="text-align:center; width:140px;" >
                  <span class="ui-button-text">Reset</span>
                  </button>
              </td>
              </tr>
            </table>
        </fieldset>
        </div>
        
	</form>
    
<?php //echo $this->load->view("footer"); ?>

