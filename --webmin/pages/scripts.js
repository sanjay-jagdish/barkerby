                                // JavaScript Document

function logIn(){
	
	var em=jQuery('#em').val();
	var pw=jQuery('#pw').val();
	
	jQuery('.displaymsg').fadeOut('slow');
	
	
	if(em!='' & pw!=''){
		
		jQuery.ajax({
			 url: "actions/login.php",
			 type: 'POST',
			 dateType:'json',
			 beforeSend: function(x) {
				if(x && x.overrideMimeType) {
					x.overrideMimeType("application/json;charset=UTF-8");
				}
			 },
			 data: 'em='+encodeURIComponent(em)+'&pw='+encodeURIComponent(pw),
			 success: function(value){
				 
				if(value.error=='Invalid'){
					jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Invalid username/password.');
				}
				else{
					window.location='crisp.php?page=dashboard';
				}
			 }
		});
		
	}
	else{
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Invalid username/password.');
	}
	
}

function addUser(){
	var ut=jQuery('.user-type').val();
	var ue=jQuery('.user-email').val();
	var up=jQuery('.user-pass').val();
	var uc=jQuery('.user-confirm').val();
	var uf=jQuery('.user-fname').val();
	var um=jQuery('.user-mname').val();
	var ul=jQuery('.user-lname').val();
	var uph=jQuery('.user-phone').val();
	var umo=jQuery('.user-mobile').val();
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(ue!='' & up!='' & uc!='' & uf!='' & ul!=''){
		if(validateEmail(ue)){
			if(up==uc){
				
				jQuery.ajax({
					 url: "actions/add-user.php",
					 type: 'POST',
					 data: 'ut='+encodeURIComponent(ut)+'&ue='+encodeURIComponent(ue)+'&up='+encodeURIComponent(up)+'&uf='+encodeURIComponent(uf)+'&um='+encodeURIComponent(um)+'&ul='+encodeURIComponent(ul)+'&uph='+encodeURIComponent(uph)+'&umo='+encodeURIComponent(umo),
					 success: function(value){
						 if(value!='Invalid'){
							jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('User successfully added.');
							setTimeout("window.location='?page=users'",2000);
						 }
						 else{
							jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Email address already exists.');	
						 }
					 }
				});
				
			}
			else{
				jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Password does not match the confirm password.');
			}
		}
		else{
			jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Invalid email address.');
		}
	}
	else{
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Fields with asterisk are required.');
	}
	
}

function editUser(){
	var ut=jQuery('.user-type').val();
	var ue=jQuery('.user-email').val();
	var uf=jQuery('.user-fname').val();
	var um=jQuery('.user-mname').val();
	var ul=jQuery('.user-lname').val();
	var uph=jQuery('.user-phone').val();
	var umo=jQuery('.user-mobile').val();
	var id=jQuery('.edit-user-btn').attr('data-rel');
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(ue!='' & uf!='' & ul!=''){
		if(validateEmail(ue)){
		
				
			jQuery.ajax({
				 url: "actions/edit-user.php",
				 type: 'POST',
				 data: 'ut='+encodeURIComponent(ut)+'&ue='+encodeURIComponent(ue)+'&uf='+encodeURIComponent(uf)+'&um='+encodeURIComponent(um)+'&ul='+encodeURIComponent(ul)+'&uph='+encodeURIComponent(uph)+'&umo='+encodeURIComponent(umo)+'&id='+encodeURIComponent(id),
				 success: function(value){
					 if(value!='Invalid'){
						jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('User account successfully modified.');
						setTimeout("window.location.reload()",2000);
					 }
					 else{
						jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Email address already exists.');	
					 }
				 }
			});
			
		}
		else{
			jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Invalid email address.');
		}
	}
	else{
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Fields with asterisk are required.');
	}
	
}

function validateEmail(email)   
{  
 if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email))  
    return (true)  
 else  
    return (false)  
}  

function addCategory(){
	var cat=jQuery('.category-name').val();
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(cat!=''){
		
		jQuery.ajax({
				 url: "actions/add-category.php",
				 type: 'POST',
				 data: 'cat='+encodeURIComponent(cat),
				 success: function(value){
					 if(value!='Invalid'){
						jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Category successfully added.');
						setTimeout("window.location='?page=category'",2000);
					 }
					 else{
						jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Category already exists.');	
					 }
				 }
			});
		
	}
	else{
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Category should not be empty.');
	}
	
}

function editCategory(){
	var cat=jQuery('.category-name').val();
	var id=jQuery('.edit-category-btn').attr('data-rel');
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(cat!=''){
		
		jQuery.ajax({
				 url: "actions/edit-category.php",
				 type: 'POST',
				 data: 'cat='+encodeURIComponent(cat)+'&id='+encodeURIComponent(id),
				 success: function(value){
					 if(value!='Invalid'){
						jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Menyn har uppdaterats.');
						setTimeout("window.location='?page=category'",2000);
					 }
					 else{
						jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Category already exists.');	
					 }
				 }
			});
		
	}
	else{
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Category should not be empty.');
	}
	
}

function addMenu(){
	
	var cat=jQuery('.menu-category').val();
	var name=jQuery('.menu-name').val();
	var des=jQuery('.menu-desc').val();
	var price=jQuery('.menu-price').val();
	var currency=jQuery('.menu-currency').val();
	var featured=jQuery('.menu-featured').attr('checked');
	var img=jQuery('#preview img').attr('title');
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(featured=='checked'){
		featured=1;
	}
	else{
		featured=0;
	}
	
	
	if(name!='' & des!='' & price!=''){
		
		if(img!=undefined){
			img=jQuery('#preview img').attr('title');
		} 
		else{
			img='';
		}
		
		jQuery.ajax({
				 url: "actions/add-menu.php",
				 type: 'POST',
				 data: 'cat='+encodeURIComponent(cat)+'&name='+encodeURIComponent(name)+'&des='+encodeURIComponent(des)+'&price='+encodeURIComponent(price)+'&img='+encodeURIComponent(img)+'&currency='+encodeURIComponent(currency)+'&featured='+encodeURIComponent(featured),
				 success: function(value){
					 if(value!='Invalid'){
						jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Menu successfully added.');
						setTimeout("window.location='?page=menu'",2000);
					 }
					 else{
						jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Menu already exists.');	
					 }
				 }
			});
		
	}
	else{
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Fields with asterisk are required.');
	}
	
}

function addCurrency(){
	var cname=jQuery('.currency-name').val();
	var sname=jQuery('.abbr-name').val();
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(cname!='' & sname!=''){
		
		jQuery.ajax({
				 url: "actions/add-currency.php",
				 type: 'POST',
				 data: 'cname='+encodeURIComponent(cname)+'&sname='+encodeURIComponent(sname),
				 success: function(value){
					 if(value!='Invalid'){
						jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Currency successfully added.');
						setTimeout("window.location='?page=currency'",2000);
					 }
					 else{
						jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Currency already exists.');	
					 }
				 }
			});
		
	}
	else{
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('All fields are required.');
	}
	
}

function editCurrency(){
	var cname=jQuery('.currency-name').val();
	var sname=jQuery('.abbr-name').val();
	var id=jQuery('.edit-currency-btn').attr('data-rel');
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(cname!='' & sname!=''){
		
		jQuery.ajax({
				 url: "actions/edit-currency.php",
				 type: 'POST',
				 data: 'cname='+encodeURIComponent(cname)+'&sname='+encodeURIComponent(sname)+'&id='+encodeURIComponent(id),
				 success: function(value){
					 if(value!='Invalid'){
						jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Currency successfully modified.');
						setTimeout("window.location='?page=currency'",2000);
					 }
					 else{
						jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Currency already exists.');	
					 }
				 }
			});
		
	}
	else{
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('All fields are required.');
	}
	
}

function editMenu(){
	
	var cat=jQuery('.menu-category').val();
	var name=jQuery('.menu-name').val();
	var des=jQuery('.menu-desc').val();
	var price=jQuery('.menu-price').val();
	var currency=jQuery('.menu-currency').val();
	var featured=jQuery('.menu-featured').attr('checked');
	var img=jQuery('#preview img').attr('title');
	var id=jQuery('.edit-menu-btn').attr('data-rel');
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(featured=='checked'){
		featured=1;
	}
	else{
		featured=0;
	}
	
	
	if(name!='' & des!='' & price!=''){
		
		if(img!=undefined){
			img=jQuery('#preview img').attr('title');
		} 
		else{
			img='';
		}
		
		jQuery.ajax({
				 url: "actions/edit-menu.php",
				 type: 'POST',
				 data: 'cat='+encodeURIComponent(cat)+'&name='+encodeURIComponent(name)+'&des='+encodeURIComponent(des)+'&price='+encodeURIComponent(price)+'&img='+encodeURIComponent(img)+'&currency='+encodeURIComponent(currency)+'&id='+encodeURIComponent(id)+'&featured='+encodeURIComponent(featured),
				 success: function(value){
					 if(value!='Invalid'){
						jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Menyn har uppdaterats.');
						setTimeout("window.location='?page=menu'",2000);
					 }
					 else{
						jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Menu already exists.');	
					 }
				 }
			});
		
	}
	else{
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Fields with asterisk are required.');
	}
	
}

function addOrderStatus(){
	var stat=jQuery('.status-name').val();
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(stat!=''){
		
		jQuery.ajax({
				 url: "actions/add-order-status.php",
				 type: 'POST',
				 data: 'stat='+encodeURIComponent(stat),
				 success: function(value){
					 if(value!='Invalid'){
						jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Status successfully added.');
						setTimeout("window.location='?page=order-status'",2000);
					 }
					 else{
						jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Status already exists.');	
					 }
				 }
			});
		
	}
	else{
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Field should not be empty.');
	}
	
}

function editOrderStatus(){
	var stat=jQuery('.status-name').val();
	var id=jQuery('.edit-order-status-btn').attr('data-rel');
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(stat!=''){
		
		jQuery.ajax({
				 url: "actions/edit-order-status.php",
				 type: 'POST',
				 data: 'stat='+encodeURIComponent(stat)+'&id='+encodeURIComponent(id),
				 success: function(value){
					 if(value!='Invalid'){
						jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Status successfully modified.');
						setTimeout("window.location='?page=order-status'",2000);
					 }
					 else{
						jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Status already exists.');	
					 }
				 }
			});
		
	}
	else{
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Field should not be empty.');
	}
	
}

function addAnnouncement() {    
    
    tinyMCE.triggerSave();
    
    var des = jQuery('.announcement-desc').val();
    
    jQuery('.displaymsg').fadeOut('slow');
    
    if(des != '') {
        jQuery.ajax( {
            url: "actions/add-announcement.php",
            type: 'POST',
            data: 'des=' + encodeURIComponent(des),
            success: function(value) {
                if(value != 'Invalid') {
                    jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Announcement successfully added.');
                    
                    setTimeout("window.location='?page=announcement'", 2000);
                } else {
                    jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Announcement already exists.');    
                }
            }
        });        
    } else {
        jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Fields with asterisk are required.');
    }    
}

jQuery(function(){
	
	jQuery('#em').focus();
	
	jQuery('.menu-price').numeric();
	
	//for login page
	jQuery('.loginbtn').click(function(e){
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		logIn();
	});
	
	jQuery('.login-container input').keyup(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		if(e.which==13)
			logIn();
	});
	
	//for add-order-status-page page
	jQuery('.add-order-status-btn').click(function(e){
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		addOrderStatus();
	});
	
	jQuery('.add-order-status-page input').keyup(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		if(e.which==13)
			addOrderStatus();
	});
	
	//for add-announcement-page page
	jQuery('.add-announcement-btn').click(function(e){
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		addAnnouncement();
	});
	
	jQuery('.add-announcement-page input').keyup(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		if(e.which==13)
			addAnnouncement();
	});
	
	//for add-menu-page
	jQuery('.add-menu-btn').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		addMenu();
	});
	
	jQuery('.add-menu-page input').keyup(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		if(e.which==13)
			addMenu();
	});
	
	//for add-currency-page
	jQuery('.add-currency-btn').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		addCurrency();
	});
	
	jQuery('.add-currency-page input').keyup(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		if(e.which==13)
			addCurrency();
	});
	
	
	//for add-category-page
	jQuery('.add-category-btn').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		addCategory();
	});
	
	jQuery('.add-category-page input').keyup(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		if(e.which==13)
			addCategory();
	});
	
	//for add-users-page
	jQuery('.add-user-btn').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		addUser();
	});
	
	jQuery('.add-users-page input').keyup(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		if(e.which==13)
			addUser();
	});
	
	//for edit-menu-page
	jQuery('.edit-menu-btn').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		editMenu();
	});
	
	jQuery('.edit-menu-page input').keyup(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		if(e.which==13)
			editMenu();
	});
	
	
	//for edit-users-page
	jQuery('.edit-user-btn').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		editUser();
	});
	
	jQuery('.edit-users-page input').keyup(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		if(e.which==13)
			editUser();
	});
	
	//for edit-category-page
	jQuery('.edit-category-btn').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		editCategory();
	});
	
	jQuery('.edit-category-page input').keyup(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		if(e.which==13)
			editCategory();
	});

	//for data-table * users.php
	jQuery('#theusers').dataTable( {
		"aoColumns": [
			null,
			{ "asSorting": [ "desc", "asc", "asc" ] },
			{ "asSorting": [ "desc", "asc", "asc" ] },
			{ "asSorting": [ "desc", "asc", "asc" ] },
			null,
			null
		]
	} );
	
	//for data-table * customers.php
	jQuery('#thecustomers').dataTable( {
		"aoColumns": [
			{ "asSorting": [ "desc", "asc", "asc" ] },
			{ "asSorting": [ "desc", "asc", "asc" ] },
			{ "asSorting": [ "desc", "asc", "asc" ] },
			{ "asSorting": [ "desc", "asc", "asc" ] },
			{ "asSorting": [ "desc", "asc", "asc" ] },
			null
		]
	} );
	
	//for data-table * category.php
	jQuery('#thecategories').dataTable( {
		"aoColumns": [
			{ "asSorting": [ "desc", "asc", "asc" ] },
			{ "asSorting": [ "desc", "asc", "asc" ] }
		]
	} );
	
	//for data-table * currency.php
	jQuery('#thecurrencies').dataTable( {
		"aoColumns": [
			{ "asSorting": [ "desc", "asc", "asc" ] },
			{ "asSorting": [ "desc", "asc", "asc" ] },
			null
		]
	} );
	
	//for data-table * users.php
	jQuery('#themenus').dataTable( {
		"aoColumns": [
			{ "asSorting": [ "desc", "asc", "asc" ] },
			{ "asSorting": [ "desc", "asc", "asc" ] },
			{ "asSorting": [ "desc", "asc", "asc" ] },
			{ "asSorting": [ "desc", "asc", "asc" ] },
			{ "asSorting": [ "desc", "asc", "asc" ] },
			null,
			null
		]
	} );
	
	//for data-table * orders.php
	jQuery('#theorders').dataTable( {
		"aoColumns": [
			{ "asSorting": [ "desc", "asc", "asc" ] },
			{ "asSorting": [ "desc", "asc", "asc" ] },
			{ "asSorting": [ "desc", "asc", "asc" ] },
			{ "asSorting": [ "desc", "asc", "asc" ] },
			{ "asSorting": [ "desc", "asc", "asc" ] },
			null,
			null
		]
	} );
	
	//for data-table * logs.php
	jQuery('#thelogs').dataTable( {
		"aoColumns": [
			{ "asSorting": [ "desc", "asc", "asc" ] },
			{ "asSorting": [ "desc", "asc", "asc" ] },
			{ "asSorting": [ "desc", "asc", "asc" ] }
		]
	} );
	
	//for data-table * order-status.php
	jQuery('#theorderstatus').dataTable({
		"aaSorting": [[ 0, "asc" ]],
		"iDisplayLength" : 100,
		"oLanguage": {
                "sUrl": "scripts/datatable-swedish.txt"
        }
	});
	
	//for data-table * announcement.php
	jQuery('#theannouncements').dataTable( {
		"aoColumns": [
			{ "asSorting": [ "desc", "asc", "asc" ] },
			{ "asSorting": [ "desc", "asc", "asc" ] },
			{ "asSorting": [ "desc", "asc", "asc" ] },
			{ "asSorting": [ "desc", "asc", "asc" ] },
			null
		]
	} );
	
	//for data-table * settings.php
	jQuery('#thesettings').dataTable( {
		"aoColumns": [
			{ "asSorting": [ "desc", "asc", "asc" ] },
			{ "asSorting": [ "desc", "asc", "asc" ] },
			{ "asSorting": [ "desc", "asc", "asc" ] },
			{ "asSorting": [ "desc", "asc", "asc" ] },
			null
		]
	} );
	
	jQuery('.closebox').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade, .modalbox').fadeOut();
	});
	
	
	jQuery('.delete-category').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade, .modalbox').fadeIn();
		
		var id=jQuery(this).attr('data-rel');
		jQuery('.delete-category-box input').attr('data-rel',id);
		
		
	});
	
	jQuery('.delete-category-box input[type=button]').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		var id=jQuery(this).attr('data-rel');
		
		jQuery.ajax({
			 url: "actions/delete-category.php",
			 type: 'POST',
			 data: 'id='+encodeURIComponent(id),
			 success: function(value){
				 
				 jQuery('.fade, .modalbox, .gradeX-'+id).fadeOut();
				 setTimeout("window.location.reload()",1000);
			 }
		});
		
	});
	
	jQuery('.delete-user').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade, .modalbox').fadeIn();
		
		var id=jQuery(this).attr('data-rel');
		jQuery('.delete-user-box input').attr('data-rel',id);
		
		
	});
	
	jQuery('.delete-user-box input[type=button]').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		var id=jQuery(this).attr('data-rel');
		
		jQuery.ajax({
			 url: "actions/delete-user.php",
			 type: 'POST',
			 data: 'id='+encodeURIComponent(id),
			 success: function(value){
				 
				 jQuery('.fade, .modalbox, .gradeX-'+id).fadeOut();
				 setTimeout("window.location.reload()",1000);
			 }
		});
		
	});
	
	jQuery('a.edit-password').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.edit-password-box input[type=button]').attr('data-rel', jQuery(this).attr('data-rel'));
		
		jQuery('.modalbox .displaymsg').removeClass('errormsg').removeClass('successmsg').html('');
		jQuery('.modalbox input[type=password]').val('');
		jQuery('.fade, .modalbox').fadeIn();
		
	});

	jQuery('.edit-password-box input[type=button]').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		var cp=jQuery('.current-password').val();
		var np=jQuery('.new-password').val();
		var cnp=jQuery('.confirm-new-password').val();
		
		var id=jQuery(this).attr('data-rel');
		
		jQuery('.displaymsg').fadeOut('slow');
		
		if(cp!='' & np!='' & cnp!=''){
			
			if(np==cnp){
				jQuery.ajax({
					 url: "actions/edit-password.php",
					 type: 'POST',
					 data: 'id='+encodeURIComponent(id)+'&cp='+encodeURIComponent(cp)+'&np='+encodeURIComponent(np),
					 success: function(value){
						 
						 if(value=='Invalid'){
							 jQuery('.modalbox .displaymsg').fadeIn('slow').addClass('errormsg').html('Invalid current password.');
						 }
						 else{
							 
							jQuery('.modalbox .displaymsg').fadeIn('slow').addClass('successmsg').html('Password successfully modified.'); 
							 
						 	setTimeout("jQuery('.fade, .modalbox').fadeOut()",1500);
						 }
						 
					 }
				});
			}
			else{
				jQuery('.modalbox .displaymsg').fadeIn('slow').addClass('errormsg').html('Password does not match the confirm new password.');
			}
			
		}
		else{
			jQuery('.modalbox .displaymsg').fadeIn('slow').addClass('errormsg').html('All fields are required.');
		}
		
	});
	
	
	jQuery('.delete-currency').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade, .modalbox').fadeIn();
		
		var id=jQuery(this).attr('data-rel');
		jQuery('.delete-currency-box input').attr('data-rel',id);
		
		
	});
	
	jQuery('.delete-currency-box input[type=button]').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		var id=jQuery(this).attr('data-rel');
		
		jQuery.ajax({
			 url: "actions/delete-currency.php",
			 type: 'POST',
			 data: 'id='+encodeURIComponent(id),
			 success: function(value){
				 
				 jQuery('.fade, .modalbox, .gradeX-'+id).fadeOut();
				 setTimeout("window.location.reload()",1000);
			 }
		});
		
	});
	
	
	jQuery('.delete-order-status').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade, .modalbox').fadeIn();
		
		var id=jQuery(this).attr('data-rel');
		jQuery('.delete-order-status-box input').attr('data-rel',id);
		
	});
	
	jQuery('.delete-order-status-box input[type=button]').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		var id=jQuery(this).attr('data-rel');
		
		jQuery.ajax({
			 url: "actions/delete-order-status.php",
			 type: 'POST',
			 data: 'id='+encodeURIComponent(id),
			 success: function(value){
				 
				 jQuery('.fade, .modalbox, .gradeX-'+id).fadeOut();
				 setTimeout("window.location.reload()",1000);
			 }
		});
		
	});
	
	
	//for edit-currency-page
	jQuery('.edit-currency-btn').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		editCurrency();
	});
	
	jQuery('.edit-currency-page input').keyup(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		if(e.which==13)
			editCurrency();
	});
	
	jQuery('.delete-menu').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade, .modalbox').fadeIn();
		
		var id=jQuery(this).attr('data-rel');
		jQuery('.delete-menu-box input').attr('data-rel',id);
		
		
	});
	
	jQuery('.delete-menu-box input[type=button]').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		var id=jQuery(this).attr('data-rel');
		
		jQuery.ajax({
			 url: "actions/delete-menu.php",
			 type: 'POST',
			 data: 'id='+encodeURIComponent(id),
			 success: function(value){
				 
				 jQuery('.fade, .modalbox, .gradeX-'+id).fadeOut();
				 setTimeout("window.location.reload()",1000);
			 }
		});
		
	});
	
	jQuery('.cbox').click(function(){
		var val=jQuery(this).attr('checked');
		var id=jQuery(this).attr('data-rel');
		
		if(val=='checked'){
			val=1;
		}
		else{
			val=0;
		}
		
		jQuery.ajax({
			 url: "actions/featured-menu.php",
			 type: 'POST',
			 data: 'id='+encodeURIComponent(id)+'&val='+encodeURIComponent(val),
			 success: function(value){}
		});
		
	});
	
	jQuery('.delete-customer').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade, .modalbox').fadeIn();
		
		var id=jQuery(this).attr('data-rel');
		jQuery('.delete-customer-box input').attr('data-rel',id);
		
		
	});
	
	jQuery('.delete-customer-box input[type=button]').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		var id=jQuery(this).attr('data-rel');
		
		jQuery.ajax({
			 url: "actions/delete-customer.php",
			 type: 'POST',
			 data: 'id='+encodeURIComponent(id),
			 success: function(value){
				 
				 jQuery('.fade, .modalbox, .gradeX-'+id).fadeOut();
				 setTimeout("window.location.reload()",1000);
			 }
		});
		
	});
	
	
	//for edit-currency-page
	jQuery('.edit-order-status-btn').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		editOrderStatus();
	});
	
	jQuery('.edit-order-status-page input').keyup(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		if(e.which==13)
			editOrderStatus();
	});
	
	jQuery('.delete-announcement').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade, .modalbox').fadeIn();
		
		var id=jQuery(this).attr('data-rel');
		jQuery('.delete-announcement-box input').attr('data-rel',id);
		
		
	});
	
	jQuery('.delete-announcement-box input[type=button]').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		var id=jQuery(this).attr('data-rel');
		
		jQuery.ajax({
			 url: "actions/delete-announcement.php",
			 type: 'POST',
			 data: 'id='+encodeURIComponent(id),
			 success: function(value){
				 
				 jQuery('.fade, .modalbox, .gradeX-'+id).fadeOut();
				 setTimeout("window.location.reload()",1000);
			 }
		});
		
	});
	
});
                            