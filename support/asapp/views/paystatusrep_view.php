<script>
var post_url = "<?php echo base_url();?>ajax/paystatusrep_model.php";

$(document).ready(function(e) 
{		
	$(document).ajaxStop($.unblockUI);
	
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

		var mydata={action: 'DISPLAY_REPORT',fromdate:from, todate:to};
				
		$.ajax({
			url: "<?php echo base_url();?>reportmaker/paystatusrep_report.php",
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
<h1>Check Payment Status Report</h1>

<table width="100%">
            
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

