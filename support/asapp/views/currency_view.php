
<script>
var post_url = "<?php echo base_url();?>ajax/currency_model.php";

var delflag="<?php echo $_SESSION['DeleteItem']; ?>";
var editflag="<?php echo $_SESSION['EditItem']; ?>";

$(document).ready(function(e)  
{		
	$(document).ajaxStop($.unblockUI);
	
	//$('#btnAdd').removeAttr('disabled');
	//$('#btnEdit').attr('disabled','disabled');
			
	var options = {
	  currPage : 1, 
	  optionsForRows : [5,10,15,20,25,50],
	  rowsPerPage : 10,
	  firstArrow : (new Image()).src="<?php echo base_url();?>images/first.png",
	  prevArrow : (new Image()).src="<?php echo base_url();?>images/prev.png",
	  lastArrow : (new Image()).src="<?php echo base_url();?>images/last.png",
	  nextArrow : (new Image()).src="<?php echo base_url();?>images/next.png",
	  topNav : false
	}
	
	$('#recorddisplay').tablePagination(options);
	
	LoadCurrencies();
	
	function addCurrency(sn,cu,cd,id)
	{
		try
		{
			var tr;
			var par=id+",'"+cu+"','"+cd+"'";
			//var ed='<a style="color: #000" onclick="ClickToEdit('+par+')" href="#">Edit</a>';
	
			var parDel=id+",'"+cu+"','"+cd+"'";
			//var del='<a style="color: #000" href="#" onclick="confirmDelete('+parDel+')">Delete</a>';
			
			if (editflag==1)
			{
				var ed='<a style="color: #000" onclick="ClickToEdit('+par+')" href="#">Edit</a>';
			}else
			{
				var ed='';
			}
			
			if (delflag==1)
			{
				var del='<a style="color: #000" href="#" onclick="confirmDelete('+parDel+')">Delete</a>';
			}else
			{
				var del='';
			}
							
			if ((sn % 2)==0)
			{
				tr=$('<tr>').attr('bgcolor','#E3EDF8').attr('align','center');
			}else
			{
				tr=$('<tr>').attr('bgcolor','#D1E7F5').attr('align','center');
			}
								
			tr.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'Row '+ sn}).html(sn))
			.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'Currency'}).html(cu))
			.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'Currency Code'}).html(cd))				
			.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'Edit Currency ' + cu}).append(ed))
			.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'Delete Currency '+ cu}).append(del))
			.appendTo('#resultsbody');
		}catch(e)
		{
			alert('Add Currency Error: '+e)
		}
	}//End addCurrency
			
	//Edit Faculty record
	$('#btnEdit').click(function(e){//EDIT
		if (!checkForm('EDIT')) return false;
		
		var cd=$.trim($('#txtcode').val());
		var cu=$.trim($('#txtcurrency').val());
		var id=trim($('#hidID').val());
				
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
		var mydata={action: 'EDIT_CURRENCY',currencycode:cd, currency:cu, id:id};
		
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
				$("#txtcurrency").val(''); $("#txtcode").val(''); 
				if (document.getElementById('btnAdd')) document.getElementById('btnAdd').disabled=false;
				if (document.getElementById('btnEdit')) document.getElementById('btnEdit').disabled=true;
				
				LoadCurrencies();				
				alert(data);
			},
			error:  function(xhr,status,error) {
				alert('Error '+ xhr.status + ' Occurred: ' + error);
				}
		});		
	});//End click-btnEdit
	
    $('#btnAdd').click(function(e) {
		if (!checkForm('ADD')) return false;
		
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
		var cd=$.trim($('#txtcode').val());
		var cu=$.trim($('#txtcurrency').val());
			
		var mydata={action: 'ADD_CURRENCY',currencycode:cd, currency:cu};
		
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
				$("#txtcurrency").val(''); $("#txtcode").val('');
				
				LoadCurrencies();
				alert(data);
			},
			error:  function(xhr,status,error) {
				alert('Error '+ xhr.status + ' Occurred: ' + error);
				}
		});	
		
	});//btnAdd.click
	
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
		
			var mydata={action:'LOAD_CURRENCIES'};
			var serializedJSON = JSON.stringify(mydata);
			
			$.ajax({
				type: "POST",
				dataType: 'json',
				data: mydata,
				url: '<?php echo base_url();?>ajax/currency_model.php',
				beforeSend: function() {
					$('#resultsbody').remove();//Clear Table					
					$('#txtcurrency').val(''); $('#txtcode').val(''); 
				},
				complete: function(xhr, textStatus) {
					$('#recorddisplay').tablePagination(options);
					$.unblockUI;
				},
				success: function(data,status,xhr) //we're calling the response json array 'cntry'
				{
					if ($(data).length > 0)
					{
						$('<tbody>').attr('id','resultsbody').appendTo('#recorddisplay');
						
						
						$.each($(data), function(i,e)
						{//currency,currencycode,id
							var sn = i+1;
							var st = e.currency;
							var cd = e.currencycode;
							var id = e.id;
							
							addCurrency(sn,st,cd,id);
						});
					}
				},
				error:  function(xhr,status,error) {
					alert('Error '+ xhr.status + ' Occurred: ' + error);
					}
			 }); //end AJAX
		}catch(e)
		{
			alert('Load Faculties Error: '+e)
		}
	}//End LoadCurrencies
});//End Ready

function addDeleteCurrency(sn,cu,cd,id)
{
	try
	{
		var tr;
		var par=id+",'"+cu+"','"+cd+"'";
		//var ed='<a style="color: #000" onclick="ClickToEdit('+par+')" href="#">Edit</a>';

		var parDel=id+",'"+cu+"','"+cd+"'";
		//var del='<a style="color: #000" href="#" onclick="confirmDelete('+parDel+')">Delete</a>';
		if (editflag==1)
		{
			var ed='<a style="color: #000" onclick="ClickToEdit('+par+')" href="#">Edit</a>';
		}else
		{
			var ed='';
		}
		
		if (delflag==1)
		{
			var del='<a style="color: #000" href="#" onclick="confirmDelete('+parDel+')">Delete</a>';
		}else
		{
			var del='';
		}
						
		if ((sn % 2)==0)
		{
			tr=$('<tr>').attr('bgcolor','#E3EDF8').attr('align','center');
		}else
		{
			tr=$('<tr>').attr('bgcolor','#D1E7F5').attr('align','center');
		}
							
		tr.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'Row '+ sn}).html(sn))
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'Currency'}).html(cu))
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'Currency Code'}).html(cd))				
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'Edit Currency ' + cu}).append(ed))
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'Delete Currency '+ cu}).append(del))
		.appendTo('#resultsbody');
	}catch(e)
	{
		alert('Add Currency Error: '+e)
	}
}//End addDeleteCurrency
	
function LoadCurrenciesAfterDelete()
{
	try
	{
		var opts = {
		  currPage : 1, 
		  optionsForRows : [5,10,15,20,25,50],
		  rowsPerPage : 10,
		  firstArrow : (new Image()).src="<?php echo base_url();?>images/first.png",
		  prevArrow : (new Image()).src="<?php echo base_url();?>images/prev.png",
		  lastArrow : (new Image()).src="<?php echo base_url();?>images/last.png",
		  nextArrow : (new Image()).src="<?php echo base_url();?>images/next.png",
		  topNav : false
		}
		
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
		
		var mydata={action:'LOAD_CURRENCIES'};
		
		$.ajax({
			type: "POST",
			dataType: 'json',
			data: mydata,
			url: '<?php echo base_url();?>ajax/currency_model.php',
			beforeSend: function() {					
				$('#resultsbody').remove();//Clear Table
			},
			complete: function(xhr, textStatus) {
				$('#recorddisplay').tablePagination(opts);
				$.unblockUI;
			},
			success: function(data,status,xhr) //we're calling the response json array 'cntry'
			{
				if ($(data).length > 0)
				{
					$('<tbody>').attr('id','resultsbody').appendTo('#recorddisplay');
					
					
					$.each($(data), function(i,e)
					{//currency,currencycode,id
						var sn = i+1;
						var st = e.currency;
						var cd = e.currencycode;
						var id = e.id;
						
						addDeleteCurrency(sn,st,cd,id);
					});
				}
			},
			error:  function(xhr,status,error) {
				alert('Error '+ xhr.status + ' Occurred: ' + error);
				}
		 }); //end AJAX
	}catch(e)
	{
		alert('Error Loading Currencies:\n'+e);
	}
}//End Load All
	
//Confirm Delete
function confirmDelete(id,st,cd)
 {
	 try
	 {						
		if (!confirm('Are you sure you want to delete the currency '+st.toUpperCase()+' from the database?. Please note that this action is irreversible. To continue, click OK otherwise, click CANCEL'))
		{
			return false;
		}else//Delete
		{
			$(document).ready(function(e) {					
				if (id)
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
					var mydata={action:'DELETE_CURRENCY', id: id};
					
					$.ajax({
						url: '<?php echo base_url();?>ajax/currency_model.php',
						data: mydata,
						type: 'POST',
						dataType: 'json',
						complete: function(xhr, textStatus) {
							$.unblockUI;
							LoadCurrenciesAfterDelete();
							//Clear Controls
							$('#txtcurrency').val('');   $('#txtcode').val('');  $('#hidID').val('');														
							if (document.getElementById('btnAdd')) document.getElementById('btnAdd').disabled=false;
							if (document.getElementById('btnEdit')) document.getElementById('btnEdit').disabled=true;
							$.unblockUI;
							},
						success: function(data,status,xhr) {alert(data);},
						error:  function(xhr,status,error) {
							alert('Error '+ xhr.status + ' Occurred: ' + error);
							}
					});
				}				
			});
		}//End Delete
	 }catch(e)
	 {
		alert('confirmDelete ERROR: '+e);
		return false;
	 }
 }//End confirmDelete
 
function ClickToEdit(id,cu,cd) 
{
	try
	{	
		if (cu=='null')cu=''; if (cd=='null')cd='';
		
		//Load Controls
		$('#txtcode').val(cd);
		$('#txtcurrency').val(cu);
		$('#hidID').val(id);
		
		if (document.getElementById('btnAdd')) document.getElementById('btnAdd').disabled=true;
		if (document.getElementById('btnEdit')) document.getElementById('btnEdit').disabled=false;		
	}catch(e)
	{
		alert('ClickToEdit Error: '+e);
		return false;
	}
}//ClickToEdit Ends

function checkForm(fn)
 {
	  try
	 {
		 var currencycode=document.getElementById("txtcode");
		 var cu=document.getElementById("txtcurrency");
		
		cu.value=$.trim(cu.value);
		currencycode.value=$.trim(currencycode.value);
		
		//Validate cu
		if (cu.value=='')
		{
			alert("Please enter currency.");
			cu.focus();
			return false;
		}
				
		if (!isNaN(cu.value))
		{
			alert("Currency must not be a number.");
			cu.focus();
			return false;
		}
		
		if (currencycode.value=='')
		{
			alert("Please enter currency code.");
			currencycode.focus();
			return false;
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

function trim(str)
{
	 str=str.toString();
	 
	return str.replace(/^\s+|\s+$/g,'');
}

function clearForm()
	 {
		 try
		 {
			document.getElementById("hidID").value='';
			document.getElementById("txtcode").value='';	
			document.getElementById("txtcurrency").value='';
			
			$('#resultsbody').remove();//Clear Table
			$('#btnAdd').removeAttr('disabled');
			$('#btnEdit').attr('disabled','disabled');
				
			return true;
		 }catch(e)
		 {
			alert('Clear Form Error:\n'+e); 
			return false;
		 }	
	 }
	 
</script>

<form data-inset="true" data-shadow="true" data-role="controlgroup">
<div align="center">
<fieldset class="fieldset50" data-inset="true">
<h1>Interswitch Currencies Information</h1>

<table align="center">	
	
    
    <tr>
    	<td class="label" align="right">Currency*&nbsp;</td>
    	<td align="left"><input style="text-transform:capitalize" class="input" type="text" id="txtcurrency" name="txtcurrency" required title="Interswitch Currency" placeholder="Enter Currency" /></td>
    </tr>
    
    <tr>
    	<td class="label" align="right">Currency Code&nbsp;</td>
    	<td align="left"><input style="text-transform:uppercase" class="input" type="text" id="txtcode" name="txtcode" title="Interswitch Currency Code" placeholder="Enter Currency Code" /></td>
    </tr>
    
    <tr>
    	<td></td>
        <td align="left">        
         <?php
        if ($_SESSION['AddItem']==1)
		{
			echo '
		 <button title="Add Record" id="btnAdd" type="button" class="button" role="button" style="text-align:center;">
			<span class="ui-button-text">Add</span>
		</button>
			';
		}
         ?>
        
        <?php
			if ($_SESSION['EditItem']==1)
			{
				echo '
			 <button title="Edit Record" id="btnEdit" type="button" class="button" role="button" style="text-align:center;">
				<span class="ui-button-text">Edit</span>
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

       
        
        <!--Display Table-->
        <table id="recorddisplay" width="100%" border="0" cellpadding="3" cellspacing="0" title="Available Currencies" class="displayborder">
              <thead>
             	<tr class="bold" align="center" bgcolor="#333300" style="color:#ffffff">
              		<td width="10%" class="leftbottomborder" style="padding-top:5px; padding-bottom:5px"><strong>S/NO.</strong></td>
              		
                    <td title="Currency" width="35%" class="leftbottomborder" style="padding-top:5px; padding-bottom:5px"><strong>CURRENCY</strong></td>
                    
                    <td title="Currency Code" width="35%" class="leftbottomborder" style="padding-top:5px; padding-bottom:5px"><strong>CURRENCY CODE</strong></td>
					<td title="Edit Currency Record" width="10%" class="leftbottomborder">&nbsp;</td>
					<td title="Delete Currency Record" width="10%" class="leftbottomborder">&nbsp;</td>
            	</tr>
              </thead>
              
              <tbody id="resultsbody"></tbody>        
          </table>
          
         
         <input type="hidden" name="hidID" id="hidID" value="" /> 
        </fieldset>
        </div>
        
	</form>
    
<?php //echo $this->load->view("footer"); ?>

