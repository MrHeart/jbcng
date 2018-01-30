<script>
var post_url = "<?php echo base_url();?>ajax/user_model.php";
	
function addUsers(sn,sur,on,pw,un,rl,us,pm,id)
{
	try
	{//surname,othernames,role,username,pwd,accountstatus
		var tr;
		var par=id+",'"+sur+"','"+on+"','"+pw+"','"+un+"','"+rl+"','"+us+"','"+pm+"'";
		//var ed='<a style="color: #000" onclick="ClickToEdit('+par+')" href="#">Edit</a>';

		var parDel=id+",'"+sur+"','"+on+"','"+pw+"','"+un+"','"+rl+"','"+us+"','"+pm+"'";
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
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'User Access Name'}).html(un))
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'User Surname'}).html(sur))	
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'User Othernames'}).html(on))
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'User Role'}).html(rl))	
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'User Account Status'}).html(us))	
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'User Permissions'}).html(pm))				
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'Edit ' + un.toUpperCase() + "'s Information"}).append(ed))
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'Delete ' + un.toUpperCase() + "'s Information"}).append(del))
		.appendTo('#resultsbody');
	}catch(e)
	{
		alert('Add User Error: '+e)
	}
}//End addUsers
	
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
	
	LoadUsers();
	
	function LoadUsers()
	{
		try
		{		
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
				url: '<?php echo base_url();?>ajax/user_model.php',
				beforeSend: function() {					
					$('#resultsbody').remove();//Clear Table
					
					document.getElementById("hidID").value='';
					$('#txtusername').val('');  
					$('#txtsname').val('');
					$('#txtoname').val('');   
					$('#txtpwd').val('');
					$('#txtcpwd').val('');  
					$('#cborole').val('');
					$('#cbostatus').val('');
					
					UnSelectAll();
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
						{//surname,othernames,role,username,pwd,datecreated,accountstatus,id
							var sn = i+1;   var sur = e.surname;  var on = e.othernames;   var un = e.username;
							var pw = e.pwd;  var rl = e.role;  var us = e.accountstatus;
	//AddItem,EditItem,DeleteItem,CreateAccount,ClearLog,CheckPaymentStatus,SetParameter,ViewLogs,ViewReport			
							//Permissions
							var ai = e.AddItem;   var ei = e.EditItem;   var di = e.DeleteItem;   var sp = e.SetParameter;
							var ca = e.CreateAccount;   var cl = e.ClearLog;   var vl = e.ViewLogs;   
							var vr = e.ViewReport; var dl = e.CheckPaymentStatus; 													
							var id = e.id;
							var pm='';
									
							if (ai=='1')pm='Add Item';
							if (ei=='1'){ if (pm=='')pm='Edit Item'; else pm=pm + ',Edit Item'; }
							if (di=='1'){ if (pm=='')pm='Delete Item'; else pm=pm + ',Delete Item'; }
							if (sp=='1'){ if (pm=='')pm='Set Parameters'; else pm=pm + ',Set Parameters'; }
							if (ca=='1'){ if (pm=='')pm='Create User Account'; else pm=pm + ',Create User Account'; }
							if (cl=='1'){ if (pm=='')pm='Delete Log Files'; else pm=pm + ',Delete Log Files'; }
							if (vl=='1'){ if (pm=='')pm='View Log Reports'; else pm=pm + ',View Log Reports'; }
							if (vr=='1'){ if (pm=='')pm='View Reports'; else pm=pm + ',View Reports'; }
							if (dl=='1'){ if (pm=='')pm='Check Payment Status'; else pm=pm + ',Check Payment Status'; }						
														
							addUsers(sn,sur,on,pw,un,rl,us,pm,id);
						});
					}
				},
				error:  function(xhr,status,error) {
					alert('Error '+ xhr.status + ' Occurred: ' + error);
					}
			 }); //end AJAX
		}catch(e)
		{
			alert('ERROR LOADING USERS:\n'+e);
		}
	}//End LoadUsers

	
	//Edit Customer record
	$('#btnEdit').click(function(e){//EDIT
		if (!checkForm('EDIT')) return false;
		
		var un=trim($('#txtusername').val());		
		var rl=trim($('#cborole').val());
		var sta=trim($('#cbostatus').val());
		var sn=trim($('#txtsname').val());		
		var on=trim($('#txtoname').val());
		var pwd=trim($('#txtpwd').val());
		var id=trim($('#hidID').val());
		
		//Permissions
		var ai,ei,di,sp,ca,cl,vl,vr,dl,pp,jm;
		
		if ($('#AddItem').attr('checked'))ai='1'; else ai='0';
		if ($('#EditItem').attr('checked')) ei='1'; else ei='0';
		if ($('#DeleteItem').attr('checked')) di='1'; else di='0';
		if ($('#SetParameter').attr('checked')) sp='1'; else sp='0';
		if ($('#CreateAccount').attr('checked')) ca='1'; else ca='0';
		if ($('#ClearLog').attr('checked')) cl='1'; else cl='0';
		if ($('#ViewLogs').attr('checked')) vl='1'; else vl='0';
		if ($('#ViewReport').attr('checked')) vr='1'; else vr='0';
		if ($('#CheckPaymentStatus').attr('checked')) dl='1'; else dl='0';	
				
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
		var mydata={action: 'EDIT_USER',surname:sn, othernames:on, pwd:pwd, username:un, role:rl, accountstatus:sta,AddItem:ai, EditItem:ei, DeleteItem:di, SetParameter:sp, CreateAccount:ca, ClearLog:cl, ViewLogs:vl, ViewReport:vr, CheckPaymentStatus:dl, id:id};
		//var serializedJSON = JSON.stringify(mydata);
		
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
				$('#txtsname').val('');	$('#txtcpwd').val('');	$('#txtusername').val('');
				$('#txtoname').val(''); $('#txtpwd').val('');
				$('#cborole').val(''); $('#cbostatus').val('');
				
				$('input:checkbox').attr('checked',false);
				
				if (document.getElementById('btnAdd')) document.getElementById('btnAdd').disabled=false;
				if (document.getElementById('btnEdit')) document.getElementById('btnEdit').disabled=true;
				
				LoadUsers();
				alert(data);
			},
			error:  function(xhr,status,error) {
				alert('Error '+ xhr.status + ' Occurred: ' + error);
				}
		});		
	});//End click-btnEdit
	
	//Add Customer Record
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
		var un=trim($('#txtusername').val());		
		var rl=trim($('#cborole').val());
		var sta=trim($('#cbostatus').val());
		var sn=trim($('#txtsname').val());		
		var on=trim($('#txtoname').val());
		var pwd=trim($('#txtpwd').val());
				
		//Permissions
		var ai,ei,di,sp,ca,cl,vl,vr,dl,pp,jm;
		
		if ($('#AddItem').attr('checked'))ai='1'; else ai='0';
		if ($('#EditItem').attr('checked')) ei='1'; else ei='0';
		if ($('#DeleteItem').attr('checked')) di='1'; else di='0';
		if ($('#SetParameter').attr('checked')) sp='1'; else sp='0';
		if ($('#CreateAccount').attr('checked')) ca='1'; else ca='0';
		if ($('#ClearLog').attr('checked')) cl='1'; else cl='0';
		if ($('#ViewLogs').attr('checked')) vl='1'; else vl='0';
		if ($('#ViewReport').attr('checked')) vr='1'; else vr='0';
		if ($('#CheckPaymentStatus').attr('checked')) dl='1'; else dl='0';		
					
		var mydata={action:'ADD_USER', surname:sn, othernames:on, pwd:pwd, username:un, role:rl, accountstatus:sta,AddItem:ai, EditItem:ei, DeleteItem:di, SetParameter:sp, CreateAccount:ca, ClearLog:cl, ViewLogs:vl, ViewReport:vr, CheckPaymentStatus:dl};
		var serializedJSON = JSON.stringify(mydata);
		
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
				$('#txtsname').val('');	$('#txtcpwd').val('');	$('#txtusername').val('');
				$('#txtoname').val(''); $('#txtpwd').val('');
				$('#cborole').val(''); $('#cbostatus').val();	
				
				LoadUsers();
				alert(data);
			},
			error:  function(xhr,status,error) {
				alert('Error '+ xhr.status + ' Occurred: ' + error);
				}
		});	
		
	});//btnAdd.click
	
});//End Ready
	
function addDeleteUser(sn,sur,on,pw,un,rl,us,pm,id)
{
	try
	{
		var tr;
		var par=id+",'"+sur+"','"+on+"','"+pw+"','"+un+"','"+rl+"','"+us+"','"+pm+"'";
		//var ed='<a style="color: #000" onclick="ClickToEdit('+par+')" href="#">Edit</a>';

		var parDel=id+",'"+sur+"','"+on+"','"+pw+"','"+un+"','"+rl+"','"+us+"','"+pm+"'";
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
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'User Access Name'}).html(un))
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'User Surname'}).html(sur))	
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'User Othernames'}).html(on))
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'User Role'}).html(rl))	
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'User Account Status'}).html(us))	
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'User Permissions'}).html(pm))			
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'Edit ' + un.toUpperCase() + "'s Information"}).append(ed))
		.append($('<td>').addClass('leftbottomborder').attr({align:'center',title:'Delete ' + un.toUpperCase() + "'s Information"}).append(del))
		.appendTo('#resultsbody');
	}catch(e)
	{
		alert('ADD USER ERROR:\n'+e)
	}
}//End addDeleteUser

function LoadDeleteUsers()
{
	try
	{
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
			url: '<?php echo base_url();?>ajax/user_model.php',
			beforeSend: function() {
				$('#resultsbody').remove();//Clear Table
				
				document.getElementById("hidID").value='';
			
				$('#txtusername').val('');  
				$('#txtsname').val('');
				$('#txtoname').val('');   
				$('#txtpwd').val('');
				$('#txtcpwd').val('');  
				$('#cborole').val('');
				$('#cbostatus').val('');
			},
			complete: function(xhr, textStatus) {
				$('#recorddisplay').tablePagination(options);
				
				//Display The Detailed tab
				$allListWrap = $(".list-wrap"),
				curListHeight = $allListWrap.height();
				$allListWrap.height(curListHeight);
						
				// Fade out current list
				$("#view").fadeOut(300, function() {
					
					// Fade in new list on callback
					$("#general").fadeIn(300);
					
					// Adjust outer wrapper to fit new list snuggly
					var newHeight = $("#general").height();
					$allListWrap.animate({
						height: newHeight
					});
					
					// Remove highlighting - Add to just-clicked tab
					$('.nav-two > a').removeClass('current');
					$('.nav-one > a').addClass('current');				
				});		
				
				
				$('#view').addClass('hide');			
				$('#general').removeClass('hide');
				
				$.unblockUI;
			},
			success: function(data,status,xhr) //we're calling the response json array 'cntry'
			{
				if ($(data).length > 0)
				{
					$('<tbody>').attr('id','resultsbody').appendTo('#recorddisplay');
									
					$.each($(data), function(i,e)
					{//surname,othernames,role,username,pwd,datecreated,accountstatus,id
						var sn = i+1;   var sur = e.surname;  var on = e.othernames;   var un = e.username;
						var pw = e.pwd;  var rl = e.role;  var us = e.accountstatus;
						
//DeleteItem,EditItem,AddItem,CreateAccount,ViewReport,ClearLog,CheckPaymentStatus,SetParameter,ViewLogs					
						//Permissions
							var ai = e.AddItem;   var ei = e.EditItem;   var di = e.DeleteItem;   var sp = e.SetParameter;
							var ca = e.CreateAccount;   var cl = e.ClearLog;   var vl = e.ViewLogs;   
							var vr = e.ViewReport; var dl = e.CheckPaymentStatus;
													
							var id = e.id;
							var pm='';
									
							if (ai=='1')pm='Add Item';
							if (ei=='1'){ if (pm=='')pm='Edit Item'; else pm=pm + ',Edit Item'; }
							if (di=='1'){ if (pm=='')pm='Delete Item'; else pm=pm + ',Delete Item'; }
							if (sp=='1'){ if (pm=='')pm='Set Parameters'; else pm=pm + ',Set Parameters'; }
							if (ca=='1'){ if (pm=='')pm='Create User Account'; else pm=pm + ',Create User Account'; }
							if (cl=='1'){ if (pm=='')pm='Delete Log Files'; else pm=pm + ',Delete Log Files'; }
							if (vl=='1'){ if (pm=='')pm='View Log Reports'; else pm=pm + ',View Log Reports'; }
							if (vr=='1'){ if (pm=='')pm='View Reports'; else pm=pm + ',View Reports'; }
							if (dl=='1'){ if (pm=='')pm='Check Payment Status'; else pm=pm + ',Check Payment Status'; }
												
						addDeleteUser(sn,sur,on,pw,un,rl,us,pm,id);
					});
				}
			},
			error:  function(xhr,status,error) {
				alert('Error '+ xhr.status + ' Occurred: ' + error);
				}
		 }); //end AJAX
	}catch(e)
	{
		alert('LOAD DELETE USERS ERROR:\n'+e)
	}
}//End LoadDeleteUsers
	
//Confirm Delete
function confirmDelete(id,sur,on,pw,un,rl,us,pm)
 {
	 try
	 {								
		if (!confirm("Are you sure you want to delete the user '" + un.toUpperCase() + "' from the database?. Please note that this action is irreversible. To continue, click OK otherwise, click CANCEL"))
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
					var mydata={action:'DELETE_USER', id: id};
					var serializedJSON = JSON.stringify(mydata);
					
					$.ajax({
						url: '<?php echo base_url();?>ajax/user_model.php',
						data: mydata,
						type: 'POST',
						dataType: 'json',
						complete: function(xhr, textStatus) {
							LoadDeleteUsers();
							$.unblockUI;
														    														
							if (document.getElementById('btnAdd')) document.getElementById('btnAdd').disabled=false;
							if (document.getElementById('btnEdit')) document.getElementById('btnEdit').disabled=true;
														
							$('#txtusername').focus();
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

function ClickToEdit(id,sur,on,pw,un,rl,us,pm) 
{
	try
	{		
		if (sur=='null')sur=''; if (on=='null')on='';
		if (pw=='null')pw=''; 	if (un=='null')un='';
		if (rl=='null')rl='';	if (us=='null')us='';
		if (pm=='null')pm='';
		
		UnSelectAll();
								
		//Load Controls
		$('#txtusername').val(un); $('#txtsname').val(sur); 
		$('#txtoname').val(on);	
		//$('#txtpwd').val(pw);    $('#txtcpwd').val('');
		$('#cborole').val(rl);  $('#cbostatus').val(us);
		$('#hidID').val(id);
		
		//Process permissions
		var arr=pm.split(',');
		var i,t,ai,ei,di,sp,ca,cl,vl,vr,um;
						
		ai=0; ei=0; di=0; sp=0; ca=0; cl=0; vl=0; vr=0; um=0;
										
		if (arr.length>0)
		{
			for (i=0; i<arr.length; i++)
			{
				t='';
				t=$.trim(arr[i].toUpperCase());
				
				switch (t)
				{
					case "ADD ITEM":
					  ai=1; $('input[name=AddItem]').attr('checked', true); break;
					case "EDIT ITEM":
					  ei=1; $('input[name=EditItem]').attr('checked', true); break;
					case "DELETE ITEM":
					  di=1; $('input[name=DeleteItem]').attr('checked', true); break;
					case "SET PARAMETERS":
					  sp=1; $('input[name=SetParameter]').attr('checked', true); break;
					case "CREATE USER ACCOUNT":
					  ca=1; $('input[name=CreateAccount]').attr('checked', true); break;
					case "DELETE LOG FILES":
					  cl=1; $('input[name=ClearLog]').attr('checked', true); break;
					case "VIEW LOG REPORTS":
					  vl=1; $('input[name=ViewLogs]').attr('checked', true); break;
					case "VIEW REPORTS":
					  vr=1; $('input[name=ViewReport]').attr('checked', true); break;
					case "CHECK PAYMENT STATUS":
					  um=1; $('input[name=CheckPaymentStatus]').attr('checked', true); break;
				}//End Switch				
			}//End for			
		}//End if
		
		if (document.getElementById('btnAdd')) document.getElementById('btnAdd').disabled=true;
		if (document.getElementById('btnEdit')) document.getElementById('btnEdit').disabled=false;
		
		//Display The Detailed tab
		$allListWrap = $(".list-wrap"),
		curListHeight = $allListWrap.height();
		$allListWrap.height(curListHeight);
				
		// Fade out current list
		$("#view").fadeOut(300, function() {
			
			// Fade in new list on callback
			$("#general").fadeIn(300);
			
			// Adjust outer wrapper to fit new list snuggly
			var newHeight = $("#general").height();
			$allListWrap.animate({
				height: newHeight
			});
			
			// Remove highlighting - Add to just-clicked tab
			$('.nav-two > a').removeClass('current');
			$('.nav-one > a').addClass('current');				
		});		
		
		
		$('#view').addClass('hide');			
		$('#general').removeClass('hide');
						
		$.unblockUI;				
	}catch(e)
	{
		alert('ClickToEdit Error: '+e);
		return false;
	}
}//End ClickToEdit

function checkForm(fn)
 {
	  try
	 {
		 var oname=document.getElementById("txtoname");
		 var role=document.getElementById("cborole");
		 var status=document.getElementById("cbostatus");

		 var sname=document.getElementById("txtsname");
		 var username=document.getElementById("txtusername");
		 var pwd=document.getElementById("txtpwd");
		 var cpwd=document.getElementById("txtcpwd");
		 var m;
		
		oname.value=trim(oname.value);
		sname.value=trim(sname.value);
		username.value=trim(username.value);
		pwd.value=trim(pwd.value);
		cpwd.value=trim(cpwd.value);
		
		//Validate username
		if (username.value=='')
		{
			m="Please enter username.";
			alert(m);  username.focus(); return false;
		}
		
		if (username.value.length < 3)
		{
			alert('Minimum username (login name) character length is 3 (three).');
			username.focus();
			return false;
		}
		
		//surname
		if (!sname.value)
		{
			m='User surname must not be blank.';
			alert(m);  sname.focus(); return false;
		}
		
		if (!isNaN(sname.value))
		{
			m='User surname must not be a number. Please enter a valid user surname.';
			alert(m);  sname.focus(); return false;
		}
		
		if (sname.length<3)
		{
			m='Please enter user surname in full.';
			alert(m);  sname.focus(); return false;
		}
		
		//othername
		if (!oname.value)
		{
			m='User othernames must not be blank.';
			alert(m);  oname.focus(); return false;
		}
		
		if (!isNaN(oname.value))
		{
			m='User othernames must not be a number. Please enter a valid user othernames.';
			alert(m);  oname.focus(); return false;
		}
		
		if (oname.length<3)
		{
			m='Please enter user othernames in full.';
			alert(m);  oname.focus(); return false;
		}
				
		
		if (trim(fn.toUpperCase())=='ADD')
		{
			//Password
			if (pwd.value == '')
			{
				alert('Please enter password.');  pwd.focus();  return false;
			}
			
			if (pwd.value.length < 4)
			{
				alert('The password must be at least four (4) characters long.');   pwd.focus();   return false;
			}
			
			
			//Confirm Password
			if (cpwd.value == '')
			{
				alert('The confirming password entry must not be blank.');  cpwd.focus();   return false;
			}
				
			if ((trim(pwd.value))!=(trim(cpwd.value)))
			{
				alert('Password and confirming password do not match.');  cpwd.focus();   return false;
			}
		}
		
		//Role
		if (trim(role.options[role.selectedIndex].value)=="")
		{
			m='User role has not been selected.';
			alert(m);  role.focus();  return false;
		}
		
		//status		
		if (trim(status.options[status.selectedIndex].value)=="")
		{
			m='User account status has not been selected.';  alert(m);   status.focus();
			return false;
		}	
		
		//Permissions
		var n = $("input:checked").length;
		
		if (n==0)
		{
			m='No user permission has been set. At least a user permission must be set before you can continue';
			alert(m);
			return false;
		}
		
		if (!confirm('Are you sure you want to '+fn+' this user record (Click OK to proceed and CANCEL to abort)?'))
		{
			return false;
		}
		
		pwd.value=sha1(pwd.value);
						
		return true;			
	 }catch(e)
	 {
		alert('CHECK FORM ERROR:\n'+e); 
		return false;
	 }
 }//End CheckForm

function trim(str)
{
	if (str==null) return "";
	if (str) return str.replace(/^\s+|\s+$/g,''); else return '';
}

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

function UnSelectAll() 
{
	try {
			$('input:checkbox').attr('checked',false);
			document.getElementById('btnSelect').style.backgroundColor='#E6F5FD';
	}catch(e) {
		alert('UNSELECT ALL ERROR:\n'+e);
	}
}//End UnSelectAll

function SelectAll() 
{
	try {				
		$('input:checkbox').attr('checked',false);
		$('input:checkbox').attr('disabled',false);
		
		var rol=document.getElementById("cborole");
	 
		if (trim(rol.options[rol.selectedIndex].value)=='Others')
		{
			$('input:checkbox').attr('checked',false);
		}else if (trim(rol.options[rol.selectedIndex].value)=='Admin')
		{
			$('input:checkbox').attr('checked',true);
			$('input:checkbox').attr('disabled',true);
		}else
		{
			 $('input:checkbox').attr('disabled',true);
			 $('input:checkbox').attr('checked',false);
		}
		document.getElementById('btnSelect').style.backgroundColor='#E6F5FD';
	}catch(e) {
		alert('SELECT ALL ERROR\n'+e);
	}
}//End SelectAll
 
 function AutoSelectPermissions()
 {
	 try
	 {
		var rol=document.getElementById("cborole");	
		 
		 if (trim(rol.options[rol.selectedIndex].value)=='Others')
		 {
			$('input:checkbox').attr('checked',false);
			$('input:checkbox').attr('disabled',false);
		 }else if (trim(rol.options[rol.selectedIndex].value)=='Admin')
		 {
			$('input:checkbox').attr('checked',true);
			$('input:checkbox').attr('disabled',true);
		 }else
		 {
			$('input:checkbox').attr('checked',false);
			$('input:checkbox').attr('disabled',false);
		 }	 
	 }catch(e)
	 {
		alert('AUTO-SELECT PERMISSION ERROR:\n'+e); 
	 }	
 }//End AutoSelectPermissions
  
</script>

<form data-inset="true" data-shadow="true" data-role="controlgroup">
<div align="center">

<fieldset class="fieldset98" data-inset="true">
<h1>Users Account</h1>
<!--Tab-->
<div id="mytabs">        			
    <ul class="nav">
        <li class="nav-one" title="User Detailed Information" id="tabGeneral"><a href="#general" class="current" title="User Detailed Information">View User Detailed Information</a></li>
        <li class="nav-two" title="View Users Records" id="tabView"><a href="#view" title="View User Records">View Users Records</a></li>
    </ul>
    
    <div class="list-wrap"><!-- START Contents -->    
        <ul id="general">
            <table align="center" width="100%">	
            <tr>
                <td  class="label" align="right">Username*&nbsp;</td>
                <td align="left"><input class="input" type="text" id="txtusername" name="txtusername" title="User Username" placeholder="Enter Username" style="text-transform:none" /></td>
                
                <td  class="label" align="right">&nbsp;</td>
                <td align="left"></td>
            </tr>
            
            <tr>
                <td valign="middle" class="label" align="right" title="User Surname">Surname*&nbsp;</td>
                <td align="left"><input class="input" type="text" id="txtsname" name="txtsname" title="User Full Name" placeholder="Enter User Surname" style="text-transform:capitalize" /></td>
                
                <td class="label" align="right" title="Othername">Othernames*&nbsp;</td>
                <td align="left"><input class="input" type="text" id="txtoname" name="txtoname" title="Othernames" placeholder="Enter Othernames" style="text-transform:none" /></td>
            </tr>
                       
             <tr>
                <td class="label" align="right" title="User Password">User Password*&nbsp;</td>
                <td align="left"><input class="input" type="password" id="txtpwd" name="txtpwd" title="User Password" placeholder="Enter Password" style="text-transform:none" /></td>
                
                <td class="label" align="right" title="Confirm User Password">Confirm Password&nbsp;</td>
                <td align="left"><input class="input" type="password" id="txtcpwd" name="txtcpwd" title="Confirm User Password" placeholder="Confirm Password" style="text-transform:none" /></td>
            </tr>
            
            <tr>
                <td class="label" align="right" title="User Role">User Role*&nbsp;</td>
                <td align="left"><select name="cborole" id="cborole" class="input" title="User Role" onchange="AutoSelectPermissions();">
              <option value="">[SELECT]</option>
              <option value="Others">Others</option>
              <option value="Admin">Admin</option>
            </select></td>
                
                 <td class="label" align="right" title="User Status">Account Status*&nbsp;</td>
                <td align="left"><select name="cbostatus" id="cbostatus" class="input" title="Account Status">
              	<option value="">[SELECT]</option>
				<option value="Disabled">Disabled</option>
                <option value="Active">Active</option>
            </select></td>
            </tr> 
            
             <tr>
             	<td class="label" align="right" title="User Permissions" valign="top">User Permissions*&nbsp;</td>
               <td colspan="3">
               
               	<TABLE id="permissions" width="1020px" border="0" cellpadding="0" cellspacing="0" bgcolor="#993399" style="color:#132C13">

                    <TR style="color:#FF0; ">
                        <TD align="left" style="padding-top:10px; padding-left:5px;" width="25%"><INPUT type="checkbox" name="AddItem" id="AddItem" title="Add Item" value="AddItem" /> 
                        <label for="AddItem" title="Add Item">Add Item</label></TD>
                        
                        <TD align="left" style="padding-top:10px; padding-left:5px;" width="25%"><INPUT type="checkbox" name="EditItem" id="EditItem" title="Edit Item" value="EditItem" /> 
                        <label for="EditItem" title="Edit Item">Edit Item</label></TD>
                        <TD align="left" style="padding-top:10px; padding-left:5px;" width="25%"><INPUT type="checkbox" name="DeleteItem" id="DeleteItem" title="Delete Item" value="DeleteItem" /> 
                        <label for="DeleteItem" title="Delete Item">Delete Item</label></TD>
                        <TD align="left" rowspan="5" style="padding-top:5px">
                        	
                            <input style="height:28px;width:190px; font-weight:bold; " id="btnSelect" type="button" value="Select All Permissions" onclick="SelectAll()" title="Click To Select All The User Permissions" class="button2" /><br />                          
                        
                        	<input style="height:28px;width:190px; font-weight:bold; margin-top:5px; " id="btnUnSelect" type="button" value="Deselect All Permissions" onclick="UnSelectAll()" title="Click To Deselect All The User Permissions" class="button2" /></TD>
                  </TR>
                    
                    
                    <TR align="left" style="color:#FF0">
                        <TD width="25%" height="24" style="padding-left:5px;"><input type="checkbox" name="CheckPaymentStatus" id="CheckPaymentStatus" title="Check Payment Status Records" value="CheckPaymentStatus" />
                         <label for="CheckPaymentStatus" title="Check Payment Status">Check Payment Status</label>
                        
                        </TD>
                        <TD width="25%" style="padding-left:5px;"><input type="checkbox" name="SetParameter" id="SetParameter" title="Set Parameters" value="SetParameter" />
                      <label for="SetParameter" title="Set Parameters">Set Parameters</label></TD>
                        
                        <TD width="25%" style="padding-left:5px;"><input type="checkbox" name="CreateAccount" id="CreateAccount" title="Create User Account" value="CreateAccount" />
                          <label for="CreateAccount" title="Create User Account">Create User Account</label></TD>
                  </TR>                
                    
                  <TR align="left" style="color:#FF0">
                       <TD width="25%" style="padding-left:5px;"><input type="checkbox" name="ViewReport" id="ViewReport" title="View Reports" value="ViewReport" />
                         <label for="ViewReport" title="View Reports">View Reports</label></TD>
                       <TD width="25%" style="padding-left:5px;"><input type="checkbox" name="ViewLogs" id="ViewLogs" title="View Log Reports" value="ViewLogs" />
                         <label for="ViewLogs" title="View Log Reports">View Log Reports</label></TD>
                       <TD width="25%" style="padding-left:5px;"><input type="checkbox" name="ClearLog" id="ClearLog" title="Delete Log Files" value="ClearLog" />
                          <label for="ClearLog" title="Delete Log Files">Delete Log Files</label>
					  </TD>
                  </TR>            
                 
                  
                  <!--********************-->
                                      
                </TABLE>
               </td>
            </tr>    
                       
            
            <tr>
                <td></td>
                <td colspan="3" align="left">
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
				 <button disabled="disabled" title="Edit Record" id="btnEdit" type="button" class="button" role="button" style="text-align:center;">
					<span class="ui-button-text">Edit</span>
				</button>
					';
				}
			 ?>
				  
                
                <button onClick="window.location.reload(true);" title="Refresh Form" id="btnRefresh" type="button" class="button" role="button" >
                    <span class="ui-button-text">Reset</span>
                </button>
                </td>
                </tr>
        </table>
        </ul>
         
         <ul id="view" class="hide">
            <!--Display Table-->
            <table id="recorddisplay" width="100%" border="0" cellpadding="3" cellspacing="0" title="Available Users" class="displayborder">
                  <thead>
                    <tr class="bold" align="center" bgcolor="#333300" style="color:#ffffff">
                        <td width="4%" class="leftbottomborder" style="padding-top:5px; padding-bottom:5px"><strong>S/NO.</strong></td>
                                                                    
                        <td title="User Access Name" width="8%" class="leftbottomborder" style="padding-top:5px; padding-bottom:5px"><strong>USERNAME</strong></td>
                        
                        <td title="User Surname" width="12%" class="leftbottomborder" style="padding-top:5px; padding-bottom:5px"><strong>SURNAME</strong></td>
                        
                        <td title="User Other Names" width="13%" class="leftbottomborder" style="padding-top:5px; padding-bottom:5px"><strong>OTHER NAMES</strong></td>
                        
                        <td title="User Role" width="5%" class="leftbottomborder" style="padding-top:5px; padding-bottom:5px"><strong>ROLE</strong></td>
                        
                        <td title="User Account Status" width="5%" class="leftbottomborder" style="padding-top:5px; padding-bottom:5px"><strong>STATUS</strong></td>
                        
                        <td title="User Permissions" width="45%" class="leftbottomborder" style="padding-top:5px; padding-bottom:5px"><strong>PERMISSIONS</strong></td>
                        
                        <td title="Edit User Record" width="4%" class="leftbottomborder">&nbsp;</td>
                        <td title="Delete User Record" width="4%" class="leftbottomborder">&nbsp;</td>
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

