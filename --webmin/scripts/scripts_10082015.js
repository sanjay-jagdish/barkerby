// Javahiftcript Document

var is_keyboard = false;
var is_landscape = false;
var initial_screen_size = window.innerHeight;

function logIn(){
	
	var em=jQuery('#em').val();
	var pw=jQuery('#pw').val();
	var check = jQuery('#keep').prop('checked');
	if(check){
		check = 1;
	}else{
		check = 0;
	}
	
	jQuery('.displaymsg').fadeOut('slow');
	// alert(check);
	
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
			 data: 'em='+encodeURIComponent(em)+'&pw='+encodeURIComponent(pw)+'&check='+check,
			 success: function(value){
				 
				if(value.error=='Invalid'){
					jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Ogiltigt användarnamn / lösenord.');
				}
				else{
					if(check){
						var expires = new Date();  
   						expires.setTime(expires.getTime() + 31536000000); //1 year  
						
						jQuery.cookie('webmin_id', value['id'], { expires: expires, path: '/' });
						jQuery.cookie('email', value['email'], { expires: expires, path: '/' });
						jQuery.cookie('name', value['name'], { expires: expires, path: '/' });
						jQuery.cookie('type', value['type'], { expires: expires, path: '/' });
						jQuery.cookie('signatory', value['signatory'], { expires: expires, path: '/' });
					}else{
						jQuery.cookie('webmin_id', value['id'], { path: '/' });
						jQuery.cookie('email', value['email'], { path: '/' });
						jQuery.cookie('name', value['name'], { path: '/' });
						jQuery.cookie('type', value['type'], { path: '/' });
						jQuery.cookie('signatory', value['signatory'], { path: '/' });
					}
					
						// jQuery.ajax({
						// 	 url: "http://www.limoneristorante.se/support/login_ajax.php",
						// 	 type: 'POST',
						// 	data:'use='+encodeURIComponent(em)+'&pass='+encodeURIComponent(pw)
						// });
					//window.open('http://www.limoneristorante.se/support/login_ajax.php?use='+em+'&pass='+pw);
					window.location='crisp.php?page=dashboard';
				}
			 }
		});
		
	}
	else{
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Ogiltigt användarnamn / lösenord.');
	}
	
}

function sendNewPasswordLink(){
	var em=jQuery('#em').val();
	jQuery('.displaymsg').fadeOut('fast');
	
	if(em == ''){
		jQuery('.displaymsg').html('Uppge din e-post.').fadeIn('slow').addClass('errormsg');
	}else{
		jQuery.ajax({
			 url: "actions/send-new-password-link.php",
			 type:"POST",
			 data: 'em='+encodeURIComponent(em),
			 success: function(value){
					if(value == 'invalid'){
						jQuery('.displaymsg').html('Ogiltig e-postadress.').fadeIn('slow').addClass('errormsg');
					}else{
						setTimeout(function(){ window.location = 'index.php?checkemail=confirm'; }, 3000);
					}
			}
		});
	}
}

function resetPassword(){
	var id=jQuery('.reset-password-btn').attr('data-rel');
	var pass1=jQuery('#pass1').val();
	var pass2=jQuery('#pass2').val();
	
	jQuery('.displaymsg').fadeOut('fast');
	
	if(pass1 != pass2){
		jQuery('.displaymsg').html('Lösenorden matchar inte.').fadeIn('slow').addClass('errormsg');	
	}else if(pass1 == '' || pass2 == ''){
		jQuery('.displaymsg').html('Uppge ditt lösenord.').fadeIn('slow').addClass('errormsg');
	}else if(pass1 == pass2){
		jQuery.ajax({
			 url: "actions/reset-password.php",
			 type:"POST",
			 data: 'id='+encodeURIComponent(id)+'&pass1='+encodeURIComponent(pass1)+'&pass2='+encodeURIComponent(pass2),
			 success: function(value){
				
				if(value == 'successfull'){
					jQuery('.displaymsg').html('Ditt lösenord har återställts.').removeClass('errormsg').addClass('successmsg').fadeIn('slow');
					setTimeout(function(){ window.location = 'index.php'; }, 3000);
				}else{
					jQuery('.displaymsg').html('Ogiltig e-postadress.').addClass('errormsg').fadeIn('slow');
				}
				
			}
		});
	}
}

function logout(){
		jQuery.removeCookie('webmin_id', { path: '/' }); 
		jQuery.removeCookie('email', { path: '/' }); 
		jQuery.removeCookie('name', { path: '/' }); 
		jQuery.removeCookie('type', { path: '/' }); 
		// jQuery.removeCookie('in', { path: '/' }); 
			jQuery.ajax({
			 url: "actions/logout.php",
			 success: function(value){
				 
			 	window.location='index.php';
			 }
		});
}
function update_sub_category_order(id){
	var val = jQuery('.sdropdown-'+id).val();
	jQuery.ajax({
		url: "actions/update_sub_category_order.php",
		type: 'POST',
		data: 'order='+encodeURIComponent(val),
		success: function(value){
			// alert(value)
		}
	});
}
function update_category_order(id){
	var val = jQuery('.cdropdown-'+id).val();
	jQuery.ajax({
		url: "actions/update_category_order.php",
		type: 'POST',
		data: 'order='+encodeURIComponent(val),
		success: function(value){
			// alert(value)
		}
	});
}
function update_menu_order(id, allview){
	
	if(allview==0){
		var val = jQuery('.dropdown-'+id).val();
	}
	else{
		var val = jQuery('.adropdown-'+id).val();
	}
	
	jQuery.ajax({
		url: "actions/update_menu_order.php",
		type: 'POST',
		data: 'order='+encodeURIComponent(val),
		success: function(value){
			// alert(value)
		}
	});
	// alert("raph");
}
function addUser(){
	var ut=jQuery('.user-type').val();
	var ue=jQuery('.user-email').val();
	var up=jQuery('.user-pass').val();
	var uc=jQuery('.user-confirm').val();
	var uf=jQuery('.user-fname').val();
	var ul=jQuery('.user-lname').val();
	var uph=jQuery('.user-phone').val();
	var umo='';
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(ue!='' & up!='' & uc!='' & uf!='' & ul!=''){
		if(validateEmail(ue)){
			if(up==uc){
				
				jQuery.ajax({
					 url: "actions/add-user.php",
					 type: 'POST',
					 data: 'ut='+encodeURIComponent(ut)+'&ue='+encodeURIComponent(ue)+'&up='+encodeURIComponent(up)+'&uf='+encodeURIComponent(uf)+'&ul='+encodeURIComponent(ul)+'&uph='+encodeURIComponent(uph)+'&umo='+encodeURIComponent(umo),
					 success: function(value){
						 if(value!='Invalid'){
							jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('User successfully added.');
							setTimeout("window.location='?page=users&parent=staff'",2000);
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
	var ul=jQuery('.user-lname').val();
	var uph=jQuery('.user-phone').val();
	var umo='';
	
	var up=jQuery('.user-pass').val().trim();
	var uc=jQuery('.user-confirm').val().trim();
	
	var id=jQuery('.edit-user-btn').attr('data-rel');
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(ue!='' & uf!='' & ul!=''){
		if(validateEmail(ue)){
			
			var proceed=0;
			
			if(up=='' & uc==''){
				proceed=1;
			}
			else{
				if(up==uc){
					proceed=1;
				}
			}
			
			
			if(proceed==1){
				jQuery.ajax({
					 url: "actions/edit-user.php",
					 type: 'POST',
					 data: 'ut='+encodeURIComponent(ut)+'&ue='+encodeURIComponent(ue)+'&uf='+encodeURIComponent(uf)+'&ul='+encodeURIComponent(ul)+'&uph='+encodeURIComponent(uph)+'&umo='+encodeURIComponent(umo)+'&id='+encodeURIComponent(id)+'&up='+encodeURIComponent(up),
					 success: function(value){
						 if(value!='Invalid'){
							jQuery('.displaymsg').fadeIn('slow').removeClass('errormsg').addClass('successmsg').html('User account successfully modified.');
							//setTimeout("window.location.reload()",2000);
						 }
						 else{
							jQuery('.displaymsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('Email address already exists.');	
						 }
					 }
				});
			}
			else{
				jQuery('.displaymsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('Passwords do not match.');
			}
				
			
			
		}
		else{
			jQuery('.displaymsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('Invalid email address.');
		}
	}
	else{
		jQuery('.displaymsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('Fields with asterisk are required.');
	}
	
}
function addCustomer(){
	var ce=jQuery('.customer-email').val();
	var cp=jQuery('.customer-pass').val();
	var cc=jQuery('.customer-confirm').val();
	var cf=jQuery('.customer-fname').val();
	var cl=jQuery('.customer-lname').val();
	var cs=jQuery('.customer-street').val();
	var ct=jQuery('.customer-city').val();
	/*var cst=jQuery('.customer-state').val();*/
	var cst='';
	var cz=jQuery('.customer-zip').val();
	var co=jQuery('.customer-country').val();
	var cph=jQuery('.customer-phone').val();
	/*var cm=jQuery('.customer-mobile').val();*/
	var cm='';
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(ce!='' & cp!='' & cc!='' & cf!='' & cl!=''){
		if(validateEmail(ce)){
			if(cp==cc){
				
				jQuery.ajax({
					 url: "actions/add-customer.php",
					 type: 'POST',
					 data: 'ce='+encodeURIComponent(ce)+'&cp='+encodeURIComponent(cp)+'&cf='+encodeURIComponent(cf)+'&cl='+encodeURIComponent(cl)+'&cs='+encodeURIComponent(cs)+'&ct='+encodeURIComponent(ct)+'&cst='+encodeURIComponent(cst)+'&cz='+encodeURIComponent(cz)+'&cph='+encodeURIComponent(cph)+'&cm='+encodeURIComponent(cm)+'&co='+encodeURIComponent(co),
					 success: function(value){
						 
						
						 if(value!='Invalid'){
							jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Customer successfully added.');
							setTimeout("window.location='?page=customers'",2000);
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
function editCustomer(){
	var ce=jQuery('.customer-email').val();
	var cf=jQuery('.customer-fname').val();
	var cl=jQuery('.customer-lname').val();
	var cs=jQuery('.customer-street').val();
	var ct=jQuery('.customer-city').val();
	var cst=jQuery('.customer-state').val();
	var cz=jQuery('.customer-zip').val();
	var co=jQuery('.customer-country').val();
	var cph=jQuery('.customer-phone').val();
	var cm=jQuery('.customer-mobile').val();
	var id=jQuery('.edit-customer-btn').attr('data-rel');
	
	var up=jQuery('.customer-pass').val();
	var uc=jQuery('.customer-confirm').val();
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(ce!='' & cf!='' & cl!='' & ct!='' & cst!='' & cz!=''){
		if(validateEmail(ce)){
			
			var proceed=0;
			
			if(up=='' & uc==''){
				proceed=1;
			}
			else{
				if(up==uc){
					proceed=1;
				}
			}
			
			if(proceed==1){
			
				jQuery.ajax({
						 url: "actions/edit-customer.php",
						 type: 'POST',
						 data: 'ce='+encodeURIComponent(ce)+'&cf='+encodeURIComponent(cf)+'&cl='+encodeURIComponent(cl)+'&cs='+encodeURIComponent(cs)+'&ct='+encodeURIComponent(ct)+'&cst='+encodeURIComponent(cst)+'&cz='+encodeURIComponent(cz)+'&cph='+encodeURIComponent(cph)+'&cm='+encodeURIComponent(cm)+'&co='+encodeURIComponent(co)+'&id='+encodeURIComponent(id)+'&up='+encodeURIComponent(up),
						 success: function(value){
							
							 if(value!='Invalid'){
								jQuery('.displaymsg').fadeIn('slow').removeClass('errormsg').addClass('successmsg').html('Customer account successfully modified.');
								setTimeout("window.location.reload();",2000);
							 }
							 else{
								jQuery('.displaymsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('Email address already exists.');	
							 }
						 }
				});
			
			}
			else{
				jQuery('.displaymsg').fadeIn('slow').removeClass('successmsg').addClass('errormsg').html('Passwords do not match.');
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
function validateEmail(email)   
{  
 if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email))  
    return (true)  
 else  
    return (false)  
}  
function addCategory(){
	var cat=jQuery('.category-name').val();
	// var des=jQuery('.category-desc').val();
	var des = tinyMCE.activeEditor.getContent();
	var nav = jQuery('.add-category-btn').attr('data-id');
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(cat!=''){
		
		jQuery.ajax({
				 url: "actions/add-category.php",
				 type: 'POST',
				 data: 'cat='+encodeURIComponent(cat)+'&desc='+encodeURIComponent(des),
				 success: function(value){
					 if(value!='Invalid'){
						jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Category successfully added.');
						if(nav=='takeaway'){
							setTimeout('window.location="?page=takeaway-menu&parent=takeaway&tab=menyer"',2000);
						}
						else{
							setTimeout('window.location="?page=menu&tab=menyer"',2000);
						}
					 }
					 else{
						jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Category already exists.');	
					 }
				 }
			});
		
	}
	else{
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Fyll i alla obligatoriska fält.');
	}
	
}
function editCategory(){
	var cat=jQuery('.category-name').val();
	var des= tinyMCE.activeEditor.getContent(); //jQuery('.category-desc').val();
	var id=jQuery('.edit-category-btn').attr('data-rel');
	var nav = jQuery('.edit-category-btn').attr('data-id');
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(cat!=''){
		
		jQuery.ajax({
				 url: "actions/edit-category.php",
				 type: 'POST',
				 data: 'cat='+encodeURIComponent(cat)+'&id='+encodeURIComponent(id)+'&desc='+encodeURIComponent(des),
				 success: function(value){
					 if(value!='Invalid'){
						jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Menyn har uppdaterats.');
						
						if(nav=='takeaway'){
							setTimeout('window.location="?page=takeaway-menu&parent=takeaway&tab=menyer"',2000);
						}
						else{
							setTimeout('window.location="?page=menu&tab=menyer"',2000);
						} 
					 
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
function addSubCategory(){
	var cat = jQuery('.subcategory-category').val();
	var subcat=jQuery('.subcategory-name').val();
	var nav = jQuery('.add-subcategory-btn').attr('data-id');
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(subcat!=''){
		
		jQuery.ajax({
				 url: "actions/add-subcategory.php",
				 type: 'POST',
				 data: 'cat='+encodeURIComponent(cat)+'&subcat='+encodeURIComponent(subcat),
				 success: function(value){
					 
					 if(value!='Invalid'){
						jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Sub Category successfully added.');
						if(nav=='takeaway'){
							setTimeout('window.location="?page=takeaway-menu&parent=takeaway&tab=kategorier"',2000);
						}
						else{
							setTimeout('window.location="?page=menu&tab=kategorier"',2000);
						}
					 }
					 else{
						jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Sub Category for this category already exists.');	
					 }
				 }
			});
		
	}
	else{
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Fyll i alla obligatoriska fält.');
	}
	
}
function editSubCategory(){
	var cat = jQuery('.subcategory-category').val();
	var subcat=jQuery('.subcategory-name').val();
	var id=jQuery('.edit-subcategory-btn').attr('data-rel');
	var nav = jQuery('.edit-subcategory-btn').attr('data-id');
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(subcat!=''){
		
		jQuery.ajax({
				 url: "actions/edit-subcategory.php",
				 type: 'POST',
				 data: 'cat='+encodeURIComponent(cat)+'&subcat='+encodeURIComponent(subcat)+'&id='+encodeURIComponent(id),
				 success: function(value){
					 
					 if(value!='Invalid'){
						jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Menyn har uppdaterats.');
						
						if(nav=='takeaway'){
							setTimeout('window.location="?page=takeaway-menu&parent=takeaway&tab=kategorier"',2000);
						}
						else{
							setTimeout('window.location="?page=menu&tab=kategorier"',2000);
						}
					 }
					 else{
						jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Sub Category for this category already exists.');	
					 }
				 }
			});
		
	}
	else{
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Sub Category should not be empty.');
	}
	
}
function addMenu(){
	
	var cat_det=jQuery('.menu-category').val();
	var cat_det = cat_det.split("*",2);
	var cat = cat_det[0];
	var cat_type = cat_det[1];
	var name=jQuery('.menu-name').val();
	var des= tinyMCE.activeEditor.getContent(); // jQuery('.menu-desc').val();
	var price=jQuery('.menu-price').val();
	var currency=jQuery('.menu-currency').attr('data-rel');
	var featured=jQuery('.menu-featured').prop('checked');
	var img=jQuery('#preview img').attr('title');
	var menutype='';
	var nav = jQuery('.add-menu-btn').attr('data-id');
	
	var discount=jQuery('.menu-discount').val();
	var discount_unit=jQuery('.menu_discount_unit').val();
	
	jQuery('.menutype').each(function(){
		if(jQuery(this).prop('checked')){
			menutype+=jQuery(this).val()+',';
		}	
	});
	
	menutype=menutype.substr(0,menutype.length-1);
	
	jQuery('.displaymsg').fadeOut();
	
	if(featured){
		featured=1;
	}
	else{
		featured=0;
	}
	
	jQuery('.displaymsg').fadeOut();
	
	
	if(name!='' & des!='' & price!='' & menutype!=''){
		
		if(img!=undefined){
			img=jQuery('#preview img').attr('title');
		} 
		else{
			img='';
		}
		
		jQuery.ajax({
				 url: "actions/add-menu.php",
				 type: 'POST',
				 data: 'cat='+encodeURIComponent(cat)+'&cattype='+encodeURIComponent(cat_type)+'&name='+encodeURIComponent(name)+'&des='+encodeURIComponent(des)+'&price='+encodeURIComponent(price)+'&img='+encodeURIComponent(img)+'&currency='+encodeURIComponent(currency)+'&featured='+encodeURIComponent(featured)+'&menutype='+encodeURIComponent(menutype)+'&discount='+encodeURIComponent(discount)+'&discount_unit='+encodeURIComponent(discount_unit),
				 success: function(value){
					 if(value!='Invalid'){
						addOptions(value,nav);
						
					 }
					 else{
						 //Menu already exists.
						jQuery('.displaymsg').fadeIn().addClass('errormsg').html('Menyn finns redan.');
						$('.add-menu-btn').prop('disabled', false);	
					 }
				 }
			});
		
	}
	else{
		jQuery('.displaymsg').fadeIn().addClass('errormsg').html('Fyll i alla obligatoriska fält.');
		$('.add-menu-btn').prop('disabled', false);	
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
	
	var cat_det=jQuery('.menu-category').val();
	var cat_det = cat_det.split("*",2);
	var cat = cat_det[0];
	var cat_type = cat_det[1];
	var name=jQuery('.menu-name').val();
	var des= tinyMCE.activeEditor.getContent(); //jQuery('.menu-desc').val();
	var price=jQuery('.menu-price').val();
	var currency=jQuery('.menu-currency').attr('data-rel');
	var featured=jQuery('.menu-featured').prop('checked');
	var img=jQuery('#preview img').attr('title');
	var id=jQuery('.edit-menu-btn').attr('data-rel');
	var menutype='';
	var nav=jQuery('.edit-menu-btn').attr('data-id');
	
	var discount=jQuery('.menu-discount').val();
	var discount_unit=jQuery('.menu_discount_unit').val();
	
	jQuery('.menutype').each(function(){
		if(jQuery(this).prop('checked')){
			menutype+=jQuery(this).val()+',';
		}	
	});
	
	menutype=menutype.substr(0,menutype.length-1);
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(featured){
		featured=1;
	}
	else{
		featured=0;
	}
	
	if(name!='' & des!='' & price!='' & menutype!=''){
		
		if(img!=undefined){
			img=jQuery('#preview img').attr('title');
		} 
		else{
			img='';
		}
		
		jQuery.ajax({
				 url: "actions/edit-menu.php",
				 type: 'POST',
				 data: 'cat='+encodeURIComponent(cat)+'&cattype='+encodeURIComponent(cat_type)+'&name='+encodeURIComponent(name)+'&des='+encodeURIComponent(des)+'&price='+encodeURIComponent(price)+'&img='+encodeURIComponent(img)+'&currency='+encodeURIComponent(currency)+'&id='+encodeURIComponent(id)+'&featured='+encodeURIComponent(featured)+'&menutype='+encodeURIComponent(menutype)+'&discount='+encodeURIComponent(discount)+'&discount_unit='+encodeURIComponent(discount_unit),
				 success: function(value){
					 if(value!='Invalid'){
						editOptions(value, nav);
						//setTimeout("window.location.reload();",2000); 
					 }
					 else{
						jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Menyn finns redan.');	
						$('.edit-menu-btn').prop('disabled', false);
					 }
				 }
			});
		
	}
	else{
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Fyll i alla obligatoriska fält');
		$('.edit-menu-btn').prop('disabled', false);
	}
	
}
function addOrderStatus(){
	var stat=jQuery('.status-name').val();
	var type=jQuery('.status-type').val();
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(stat!=''){
		
		jQuery.ajax({
				 url: "actions/add-order-status.php",
				 type: 'POST',
				 data: 'stat='+encodeURIComponent(stat)+'&type='+encodeURIComponent(type),
				 success: function(value){
					 if(value!='Invalid'){
						jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Status successfully added.');
						//setTimeout("window.location='?page=order-status'",2000);
						setTimeout("window.location.reload();",2000);
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
	var type=jQuery('.status-type').val();
	var id=jQuery('.edit-order-status-btn').attr('data-rel');
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(stat!=''){
		
		jQuery.ajax({
				 url: "actions/edit-order-status.php",
				 type: 'POST',
				 data: 'stat='+encodeURIComponent(stat)+'&id='+encodeURIComponent(id)+'&type='+encodeURIComponent(type),
				 success: function(value){
					 if(value!='Invalid'){
						jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Status successfully modified.');
						//setTimeout("window.location='?page=order-status'",2000);
						setTimeout("window.location.reload();",2000);
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
	var snd = jQuery("input[name='send_option']").val();
	var sbj = jQuery('.announcement-subj').val();
	var msg = tinyMCE.activeEditor.getContent(); //jQuery('.announcement-desc').val();
	var rec = jQuery('.announcement-recipient').val();
	var opt = jQuery('.save_option').val();
	var recipients_error=0;
	
	var send_now = 0;
	
	if($('#send_now').is(':checked')) { 
		send_now = 1;
	}
	
	var send_all = 0;
	if($('#all').is(':checked')) { 
		send_all = 1;
	}else{
		var rec = ($("option:selected").map(function(){ return this.value }).get().join(","));		
				
		if(recipients.length == 0){
			recipients_error = 1;
		}
	
	}
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(msg != '' && sbj != '' && recipients_error==0) {
		
		jQuery('.add-announcement-btn').val('Utför… Dröj kvar…');
		jQuery('.add-announcement-btn').attr('disabled','disabled');
		jQuery('.add-announcement-btn').css('background-color','#ccc');
		jQuery('.add-announcement-btn').css('color','#000');
			
		jQuery.ajax( {
			url: "actions/add-announcement.php",
			type: 'POST',
			data: 'msg=' + encodeURIComponent(msg)+'&sbj='+encodeURIComponent(sbj)+'&opt='+send_now+'&all='+send_all+'&rec=' + encodeURIComponent(rec),
			success: function(value) {
			   
				jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Utskicket har lagts till.');
				
				setTimeout("window.location='?page=announcement'", 2000);
			  
			}
		});        
	} else {
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Fyll i samtliga fält.');
	} 
	if(recipients_error>0){
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Please select at least one (1) recipient.');	
	}
   
}
function editAnnouncement() {    
 	var id = jQuery("input[name='edit_id']").val();
 	var snd = jQuery("input[name='send_option']").val();
	var sbj = jQuery('.announcement-subj').val();
	var msg = tinyMCE.activeEditor.getContent(); //jQuery('.announcement-desc').val();
	var rec = jQuery('.announcement-recipient').val();
	var opt = jQuery('.save_option').val();
	var recipients_error=0;
	
	var send_now = 0;
	
	if($('#send_now').is(':checked')) { 
		send_now = 1;
	}
	
	var send_all = 0;
	if($('#all').is(':checked')) { 
		send_all = 1;
	}else{
		var rec = ($("option:selected").map(function(){ return this.value }).get().join(","));		
				
		if(recipients.length == 0){
			recipients_error = 1;
		}
	
	}
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(msg != '' && sbj != '' && recipients_error==0) {
		jQuery('.ououncement-btn').val('Utför… Dröj kvar…');
		jQuery('.edit-announcement-btn').attr('disabled','disabled');
		jQuery('.edit-announcement-btn').css('background-color','#ccc');
		jQuery('.edit-announcement-btn').css('color','#000');
			
		jQuery.ajax( {
			url: "actions/edit-announcement.php",
			type: 'POST',
			data: 'id='+id+'&msg=' + encodeURIComponent(msg)+'&sbj='+encodeURIComponent(sbj)+'&opt='+send_now+'&all='+send_all+'&rec=' + encodeURIComponent(rec),
			success: function(value) {
			   
				jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Utskicket har uppdaterats.');
				
				setTimeout("window.location='?page=announcement'", 2000);
			  
			}
		});        
	} else {
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Fyll i samtliga fält.');
	} 
	if(recipients_error>0){
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Please select at least one (1) recipient.');	
	}
}
function showDetail(id,typeid){
	//remove the sounds
	//jQuery('.blink').removeClass('sound');
	
	jQuery('body').attr('aria-valuetext',1);
	//jQuery('.mutebtn').attr('value','Ljud På').attr('data-rel',1);
	
	/*jQuery('.mutebtn').css('background','none');
	jQuery('.mutebtn').removeClass('muteoff').addClass('muteon');*/
	
	ion.sound.pause("notification");
	
	jQuery('.gradeX-'+id).removeClass('blink sound').css({'background':'','color':''});
	
	//jQuery('body').attr('aria-label','pause');
	
	//jQuery('.mutebtn').val('').attr('data-rel',1);
	jQuery(".gradeX-"+id+" td").removeClass("blink sound").removeAttr("style");
	
	// alert("gradeX-"+id+" td");
	
	if(typeid==1){
		jQuery('.orderbox h2 span').html('Bokningsinformation');
	}
	else{
		jQuery('.orderbox h2 span').html('Take Away Information');
	}
	
	jQuery('.orderbox .box-content').html('<img src="images/loader.gif" style="margin: 30px 0 0;">');
	jQuery.ajax( {
            url: "actions/order-detail.php",
            type: 'POST',
            data: 'id=' + encodeURIComponent(id),
            success: function(value) {
               
				jQuery('.orderbox .box-content').fadeIn().html(value);
	           
            }
        });
	
	jQuery('.fade, .orderbox').fadeIn();	
		jQuery.ajax({
			 url: "actions/viewedReservation.php",
			 success: function(value){
				 
				 if(value!=0){
					 // alert(value);
					 jQuery(".orders-li #notif").html(value);
				}else{
					jQuery(".orders-li div").remove();
				}
			 }
		});	
	
}
function addShift() {
    var des = tinyMCE.activeEditor.getContent(); //jQuery('.shift-desc').val();
    var dateTime = jQuery('.shift-datetime').val();
    
	var errors = 0;
	
	var acct_1 = jQuery('#account_id_1').val();
	var option = jQuery("input[name='option']:checked").val();
	var schedule_1 = jQuery('#schedule_1').val();
	var day_current = $('input[name=day_current]:checked', '#sched_switch').val();
	var acct_2 = jQuery('#account_id_2').val();
	var schedule_2 = jQuery('#schedule_2').val();
	var day_switch = $('input[name=day_switch]:checked', '#sched_switch').val()
	var other_emp = (jQuery("#other_emp_selected option:selected").map(function(){ return this.value }).get().join(","));		
	//other_emp_selected
	
	
	//var chk_str = 'Acc1: '+acct_1+' S1: '+schedule_1+' D1: '+day_current+' Acc2: '+acct_2+' S2: '+schedule_2+' D2:'+day_switch;
	
	if(option=='swap'){
		if(acct_1==0 || acct_2==0 || schedule_1==0 || schedule_2==0 || 
		   acct_1 == undefined || acct_2 == undefined || schedule_1 == undefined || schedule_2 == undefined
		){ errors++; }
		
		if(des=='' || day_current=='' || day_switch=='' || des==undefined || day_current==undefined || day_switch==undefined){ errors++; }		
	}else{
		//check if there is at least 1 staff selected	
		if(acct_1==0 || schedule_1==0 || acct_1 == undefined || schedule_1 == undefined ){ errors++; }
		
		if(des=='' || day_current=='' || des==undefined || day_current==undefined || other_emp==undefined || other_emp==''){ errors++; }	
	}	
	
    jQuery('.displaymsg').fadeOut('slow');
    
    if(errors==0) {
        jQuery.ajax( {
            url: "actions/add-shift.php",
            type: 'POST',
            data: 'option='+option+'&other_employees='+other_emp+'&day_current=' + encodeURIComponent(day_current)+'&day_switch='+encodeURIComponent(day_switch)+'&acct_1='+acct_1+'&acct_2='+acct_2+'&sched_1='+schedule_1+'&sched_2='+schedule_2+'&des='+encodeURIComponent(des),
            success: function(value) {
                if(value != '') {
                    jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Shift successfully added.');                    
                    setTimeout("window.location='?page=shift'", 2000);
                } else {
                    jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Error occured.  Shift request not saved.');    
                }
            }
        });        
    } else {
        jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Please fill-in the required fields. ');
    }    
}
function editShift() {
    var dateTime = jQuery('.shift-datetime').val();
    var des = tinyMCE.activeEditor.getContent(); //jQuery('.shift-desc').val();
    var id = jQuery('.edit-shift-btn').attr('data-rel');
    
    jQuery('.displaymsg').fadeOut('slow');    
    
    if(des != '') {
        jQuery.ajax( {
            url: "actions/edit-shift.php",
            type: 'POST',
            data: 'des=' + encodeURIComponent(des) + '&dt=' + encodeURIComponent(dateTime) + '&id=' + encodeURIComponent(id),
            success: function(value) {
                if(value != 'Invalid') {
                    jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Shift successfully modified.');
                    
                    setTimeout("window.location='?page=shift'", 2000);
                } else {
                    jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Shift already exists.');
                }
            }
        });        
    } else {
        jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Fields with asterisk are required.');
    }    
}
function strtoTime(str){
	jQuery.ajax( {
            url: "actions/strtoTime.php",
            type: 'POST',
            data: 'str=' + encodeURIComponent(str),
			async: false,
            success: function(value) {
                str=value;
            }
    }); 
	
	return str; 
}
function addSettings(){
	var name = jQuery('.name').val();
	var starttime=jQuery('.starttime').val();
	var endtime=jQuery('.endtime').val();
	var thedays=jQuery('.thedays:checked').length;
	var startdate=jQuery('.startdate').val();
	var enddate=jQuery('.enddate').val();
	/*var timeinterval=jQuery('.timeinterval').val();
	var dineinterval=jQuery('.dineinterval').val();
	var betweeninterval=jQuery('.betweeninterval').val();*/
	
	var timeinterval=Number(jQuery('.timespan label').attr('data-rel'));
	var dineinterval=Number(jQuery('.dinespan label').attr('data-rel'));
	var betweeninterval=Number(jQuery('.betweenspan label').attr('data-rel'));
	
	var untildate=jQuery('.untildate').prop('checked');
	var redborder=jQuery('.redborder').length;
	var numseats = jQuery('.numseats').val();
	
	
	jQuery('.displaymsg').fadeOut('slow');
	
	var count=jQuery('.the-tables .txt').length;
	var counter=0;
	var pax='';
	var loop=0;
	jQuery('.the-tables .txt').each(function(){
		loop+=1;
        if(jQuery(this).val()!=''){
			counter+=1;
			
			var val=jQuery(this).val();
			if(loop%2==0){
				sep='*';
			}
			else{
				sep='^';
			}
			
			pax+=jQuery(this).val()+sep;
		}
    });
	var totalseats=0;
   	jQuery('.thetable').each(function(){
   		var val=Number(jQuery(this).val());
   		totalseats+=val;
   	});
	
	if(startdate!='' & enddate!='' & starttime!='' & endtime!='' & (count==counter) & timeinterval!='' & dineinterval!='' & betweeninterval!='' & thedays!=0 & numseats!=''){
		
		   if(new Date(enddate) >= new Date(startdate)){
				
				if(timeinterval>0 & dineinterval>0 & betweeninterval>0){
					if(redborder > 0){
						jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('You cannot add the same table name.');
					}
					else{
						
				
						if(numseats >= 0 & numseats<=totalseats){
							var days='';
							jQuery('.thedays:checked').each(function() {
								days+=this.value+", ";
							});
							
							days=days.substr(0,days.length-2);
							
							pax=pax.substr(0,pax.length-1);
							
							
							jQuery.ajax( {
									url: "actions/addSettings.php",
									type: 'POST',
									data: 'name='+encodeURIComponent(name)+'&starttime='+encodeURIComponent(starttime)+'&endtime='+ encodeURIComponent(endtime)+'&days='+ encodeURIComponent(days)+'&startdate='+ encodeURIComponent(startdate)+'&enddate='+ encodeURIComponent(enddate)+'&pax='+ encodeURIComponent(pax)+'&interval='+ encodeURIComponent(timeinterval)+'&dineinterval='+ encodeURIComponent(dineinterval)+'&betweeninterval='+ encodeURIComponent(betweeninterval)+'&numseats='+ encodeURIComponent(numseats),
									success: function(value) {
									
										jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Settings successfully saved.');
								
										 setTimeout("window.location='?page=other-settings&parent=bordsbokning&tab=1'", 2000);
									}
							});
						}
						else{
							jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Total number of seats to be used should be greater than or equal to ZERO and should be less than or equal to overall total number of seats.');
						}
					}
				}
				else{
					jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Time Interval, Dine Interval and Between Interval should be greater than 0.');
				}
				
			}
			else{
				jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Start Date should be lesser than or equal to the End Date.');
			}
			
			
	}
	else{
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Fields are all required.');
	}
}
function editSettings(){
	// alert();
	var name = jQuery('.name').val();
	var starttime=jQuery('.starttime').val();
	var endtime=jQuery('.endtime').val();
	var thedays=jQuery('.thedays:checked').length;
	var startdate=jQuery('.startdate').val();
	var enddate=jQuery('.enddate').val();
	/*var timeinterval=jQuery('.timeinterval').val();
	var dineinterval=jQuery('.dineinterval').val();
	var betweeninterval=jQuery('.betweeninterval').val();*/
	
	var timeinterval=Number(jQuery('.timespan label').attr('data-rel'));
	var dineinterval=Number(jQuery('.dinespan label').attr('data-rel'));
	var betweeninterval=Number(jQuery('.betweenspan label').attr('data-rel'));
	var redborder=jQuery('.redborder').length;
	
	var numseats = jQuery('.numseats').val();
	
	var id=jQuery('.edit-settings-btn').attr('data-rel');
	
	jQuery('.displaymsg').fadeOut('slow');
	
	var count=jQuery('.the-tables .txt').length;
	var counter=0;
	var pax='';
	var loop=0;
	jQuery('.the-tables .txt').each(function(){
		loop+=1;
        if(jQuery(this).val()!=''){
			counter+=1;
			
			var val=jQuery(this).val();
			if(loop%2==0){
				sep='*';
			}
			else{
				sep='^';
			}
			
			pax+=jQuery(this).val()+sep;
		}
    });
    var totalseats=0;
   	jQuery('.thetable').each(function(){
   		var val=Number(jQuery(this).val());
   		totalseats+=val;
   	});
	
	
	if(name!='' & startdate!='' & enddate!='' & starttime!='' & endtime!='' & (count==counter) & timeinterval!='' & dineinterval!='' & betweeninterval!='' & thedays!=0 & numseats!=''){
			
			if(new Date(enddate) >= new Date(startdate)){
				
				if(timeinterval>0){
					if(redborder > 0){
						jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('You cannot add the same table name.');
					}
					else{
						
						
						if(numseats >= 0 & numseats<=totalseats){
				
							var days='';
							jQuery('.thedays:checked').each(function() {
								days+=this.value+", ";
							});
							
							days=days.substr(0,days.length-2);
							
							pax=pax.substr(0,pax.length-1);
							
							jQuery.ajax( {
									url: "actions/edit-settings.php",
									type: 'POST',
									data: 'name='+encodeURIComponent(name)+'&starttime='+encodeURIComponent(starttime)+'&endtime='+ encodeURIComponent(endtime)+'&days='+ encodeURIComponent(days)+'&startdate='+ encodeURIComponent(startdate)+'&enddate='+ encodeURIComponent(enddate)+'&pax='+ encodeURIComponent(pax)+'&interval='+ encodeURIComponent(timeinterval)+'&id='+ encodeURIComponent(id)+'&dineinterval='+ encodeURIComponent(dineinterval)+'&betweeninterval='+ encodeURIComponent(betweeninterval)+'&numseats='+ encodeURIComponent(numseats),
									success: function(value) {
										jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Settings successfully modified.');
								
										 setTimeout("window.location='?page=other-settings&parent=bordsbokning&tab=1'", 2000);
									}
							});
						}
						else{
							jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Total number of seats to be used should be greater than or equal to ZERO and should be less than or equal to overall total number of seats.');
						}
					}
				
				}
				else{
					jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Time Interval should be greater than 0.');
				}
				
			}
			else{
				jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Start Date should be lesser than or equal to the End Date.');
			}
		
	}
	else{
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Fields are all required.');
	}
}
function addSchedule(){
	var empname=jQuery('.empname').val();
	var starttime=jQuery('.starttime').val();
	var endtime=jQuery('.endtime').val();
	var thedays=jQuery('.thedays:checked').length;
	var startdate=jQuery('.startdate').val();
	var enddate=jQuery('.enddate').val();
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(empname!='' & startdate!='' & enddate!='' & starttime!='' & endtime!='' & thedays!=0){
		
		if(enddate >= startdate){
			
			var days='';
			jQuery('.thedays:checked').each(function() {
				days+=this.value+", ";
			});
			
			days=days.substr(0,days.length-2);
			
			jQuery.ajax( {
					url: "actions/add-schedule.php",
					type: 'POST',
					data: 'starttime='+encodeURIComponent(starttime)+'&endtime='+ encodeURIComponent(endtime)+'&days='+ encodeURIComponent(days)+'&startdate='+ encodeURIComponent(startdate)+'&enddate='+ encodeURIComponent(enddate)+'&id='+ encodeURIComponent(empname),
					success: function(value) {
						jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Schedule successfully saved.');
				
						 setTimeout("window.location.reload();", 2000);
					}
			});
			
		}
		else{
			jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Start Date should be lesser than or equal to the End Date.');
		}
		
	}
	else{
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Fields are all required.');
	}
}
function editSchedule(){
	var empname=jQuery('.empname').val();
	var starttime=jQuery('.starttime').val();
	var endtime=jQuery('.endtime').val();
	var thedays=jQuery('.thedays:checked').length;
	var startdate=jQuery('.startdate').val();
	var enddate=jQuery('.enddate').val();
	
	var id=jQuery('.edit-scheduler-btn').attr('data-rel');
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(empname!='' & startdate!='' & enddate!='' & starttime!='' & endtime!='' & thedays!=0){
		
		if(enddate >= startdate){
			
			var days='';
			jQuery('.thedays:checked').each(function() {
				days+=this.value+", ";
			});
			
			days=days.substr(0,days.length-2);
			
			jQuery.ajax( {
					url: "actions/edit-schedule.php",
					type: 'POST',
					data: 'starttime='+encodeURIComponent(starttime)+'&endtime='+ encodeURIComponent(endtime)+'&days='+ encodeURIComponent(days)+'&startdate='+ encodeURIComponent(startdate)+'&enddate='+ encodeURIComponent(enddate)+'&id='+ encodeURIComponent(empname)+'&schedid='+ encodeURIComponent(id),
					success: function(value) {
						jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Schedule successfully modified.');
				
						 setTimeout("window.location.reload();", 2000);
					}
			});
			
		}
		else{
			jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Start Date should be lesser than or equal to the End Date.');
		}
		
	}
	else{
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Fields are all required.');
	}
}
function addCatering(){
	var name=jQuery('.catering-name').val();
	var des= tinyMCE.activeEditor.getContent(); //jQuery('.catering-desc').val();
	var price=jQuery('.catering-price').val();
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(name!='' & des !='' & price!=''){
		
		jQuery.ajax({
				 url: "actions/add-catering.php",
				 type: 'POST',
				 data: 'name='+encodeURIComponent(name)+'&desc='+encodeURIComponent(des)+'&price='+encodeURIComponent(price),
				 success: function(value){
					 if(value!='Invalid'){
						jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Catering Package successfully added.');
						setTimeout("window.location='?page=catering'",2000);
					 }
					 else{
						jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Catering Package already exists.');	
					 }
				 }
			});
		
	}
	else{
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('All fields should not be empty.');
	}
	
}
function editCatering(){
	var name=jQuery('.catering-name').val();
	var des= tinyMCE.activeEditor.getContent(); //jQuery('.catering-desc').val();
	var price=jQuery('.catering-price').val();
	var id=jQuery('.edit-catering-btn').attr('data-rel');
	
	jQuery('.displaymsg').fadeOut('slow');
	
	if(name!='' & des !='' & price!=''){
		
		jQuery.ajax({
				 url: "actions/edit-catering.php",
				 type: 'POST',
				 data: 'name='+encodeURIComponent(name)+'&desc='+encodeURIComponent(des)+'&price='+encodeURIComponent(price)+'&id='+encodeURIComponent(id),
				 success: function(value){
					 if(value!='Invalid'){
						jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Catering Package successfully modified.');
						setTimeout("window.location='?page=catering'",2000);
					 }
					 else{
						jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('Catering Package already exists.');	
					 }
				 }
			});
		
	}
	else{
		jQuery('.displaymsg').fadeIn('slow').addClass('errormsg').html('All fields should not be empty.');
	}
	
}
function addOptions(id, nav){
	var opt_arr = [];
	$('.opt-holder .opt-item').each(function(){
		opt_id = $(this).attr('id');
		opt_name = $('#optname-'+opt_id).val();
		opt_pr = $('#optpr-'+opt_id).val();
		opt_item = opt_name+':'+opt_pr;
		opt_arr.push(opt_item);
	});
	//console.log(opt_arr);
	jQuery.ajax({
		 url: "actions/add-menu-option.php",
		 type: 'POST',
		 data: {option:opt_arr,id:id},
		 success: function(value){
			 jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Menyn har lagts till.');
			//setTimeout("window.location='?page=menu'",2000);
			if(nav=='takeaway'){
				setTimeout('window.location="?page=takeaway-menu&parent=takeaway&tab=huvudmeny"',2000);
			}
			else{
				setTimeout('window.location="?page=menu&tab=huvudmeny"',2000);
			}
		 }
	});
}
function editOptions(id,nav){
	var opt_arr = [];
	var remove = $('#remove').attr('class');
	$('.opt-holder .opt-item.new').each(function(){
		opt_id = $(this).attr('id');
		opt_name = $('#optname-'+opt_id).val();
		opt_pr = $('#optpr-'+opt_id).val();
		opt_item = opt_name+':'+opt_pr;
		opt_arr.push(opt_item);
	});
	//console.log(opt_arr);
	//alert(opt_arr);
	jQuery.ajax({
		 url: "actions/edit-menu-option.php",
		 type: 'POST',
		 data: {option:opt_arr,id:id,remove:remove},
		 success: function(value){
			 //alert(value);
			 jQuery('.displaymsg').fadeIn('slow').addClass('successmsg').html('Menyn har uppdaterats.');
			 if(nav=='takeaway'){
			 	setTimeout('window.location="?page=takeaway-menu&parent=takeaway&tab=huvudmeny"',2000);
			 }
			 else{
				setTimeout('window.location="?page=menu&tab=huvudmeny"',2000);
			}
		 }
	});
}
function gotoModal(){
	/*jQuery('html, body').animate({
        scrollTop: jQuery('.modalbox').offset().top-80
    }, 500);*/
	
	jQuery.fn.center = function ()
	{
		this.css("position","fixed");
		this.css("top", (jQuery(window).height() / 2) - (this.outerHeight() / 2));
		//this.css("left", ($(window).width() / 2) - (this.outerWidth() / 2));
		return this;
	}
	
	jQuery('.modalbox').center();
	
	
}
jQuery(function(){
	
	
	jQuery('#em').focus();
	
	jQuery('.menu-price, .numtables, .customtime, .thetable, .timeinterval, .dineinterval, .betweeninterval, .numseats, .menu-discount, .catering-price').numeric();
	
	//for login page
	jQuery('.loginbtn').click(function(e){
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		logIn();
	});
	
	jQuery('.create-new-password-link-btn').click(function(e){
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		sendNewPasswordLink();
	});
	
	jQuery('.reset-password-btn').click(function(e){
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		resetPassword();
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
		
		//if(e.which==13)
			//addAnnouncement();
	});
	
	//for add-menu-page
	jQuery('.add-menu-btn').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		$(this).prop('disabled', true);
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
	
	//for add-category-page
	jQuery('.add-catering-btn').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		addCatering();
	});
	
	jQuery('.add-catering-page input').keyup(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		if(e.which==13)
			addCatering();
	});
	
	
	//for add-subcategory-page
	jQuery('.add-subcategory-btn').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		addSubCategory();
	});
	
	jQuery('.add-subcategory-page input').keyup(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		if(e.which==13)
			addSubCategory();
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
	
	
	//for add-customer-page
	jQuery('.add-customer-btn').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		addCustomer();
	});
	
	jQuery('.add-customers-page input').keyup(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		if(e.which==13)
			addCustomer();
	});
	
	//for edit-menu-page
	jQuery('.edit-menu-btn').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		$(this).prop('disabled', true);
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
	
	//for edit-users-page
	jQuery('.edit-customer-btn').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		editCustomer();
	});
	
	jQuery('.edit-customers-page input').keyup(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		if(e.which==13)
			editCustomer();
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
	
	
	//for edit-category-page
	jQuery('.edit-catering-btn').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		editCatering();
	});
	
	jQuery('.edit-catering-page input').keyup(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		if(e.which==13)
			editCatering();
	});
	
	//for edit-category-page
	jQuery('.edit-subcategory-btn').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		editSubCategory();
	});
	
	jQuery('.edit-subcategory-page input').keyup(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		if(e.which==13)
			editSubCategory();
	});
	//for data-table * users.php
	jQuery('#theusers').dataTable( {
		"aaSorting": [[ 0, "asc" ]],
		"iDisplayLength" : 100,
		"oLanguage": {
                "sUrl": "scripts/datatable-swedish.txt"
        }
	} );
	
	//for data-table * customers.php
	jQuery('#thecustomers').dataTable( {
		"aaSorting": [[ 0, "asc" ]],
		"iDisplayLength" : 100,
		"oLanguage": {
                "sUrl": "scripts/datatable-swedish.txt"
        }
	} );
	
	//for data-table * category.php
	jQuery('#thecategories').dataTable( {
		"aaSorting": [[ 0, "asc" ]],
		"iDisplayLength" : 100,
		"oLanguage": {
                "sUrl": "scripts/datatable-swedish.txt"
        }
	} );
	
	//for data-table * subcategory.php
	jQuery('#thesubcategories').dataTable( {
		"aaSorting": [[ 0, "asc" ]],
		"iDisplayLength" : 100,
		"oLanguage": {
                "sUrl": "scripts/datatable-swedish.txt"
        }
	} );
	
	//for data-table * currency.php
	jQuery('#thecurrencies').dataTable( {
		"aaSorting": [[ 0, "asc" ]],
		"iDisplayLength" : 100,
		"oLanguage": {
                "sUrl": "scripts/datatable-swedish.txt"
        }
	} );
	
	//for data-table * menu.php
	jQuery('#themenus').dataTable( {
		"aaSorting": [[ 0, "asc" ]],
		"iDisplayLength" : 100,
		"oLanguage": {
                "sUrl": "scripts/datatable-swedish.txt"
        }
	} );
	
	jQuery('#themenusdine').dataTable( {
		"aaSorting": [[ 0, "asc" ]],
		"iDisplayLength" : 100,
		"oLanguage": {
                "sUrl": "scripts/datatable-swedish.txt"
        }
	} );
	
	jQuery('#themenustake').dataTable( {
		"aaSorting": [[ 0, "asc" ]],
		"iDisplayLength" : 100,
		"oLanguage": {
                "sUrl": "scripts/datatable-swedish.txt"
        }
	} );
	
	//for data-table * orders.php
	jQuery('#theorders').dataTable( {
		"aaSorting": [[ 2, "desc" ]],
		"iDisplayLength" : 100,
		"oLanguage": {
                "sUrl": "scripts/datatable-swedish.txt"
        }
	} );
	
	//for data-table * orders.php
	jQuery('#theordersbook').dataTable( {
		"aaSorting": [[ 1, "desc" ]],
		"iDisplayLength" : 100,
		"oLanguage": {
                "sUrl": "scripts/datatable-swedish.txt"
        }
	} );
	
	//for data-table * orders.php
	jQuery('#theorderstake').dataTable( {
		"aaSorting": [[ 0, "asc" ]],
		"iDisplayLength" : 100,
		"oLanguage": {
                "sUrl": "scripts/datatable-swedish.txt"
        }
	} );
	
	//for data-table * logs.php
	jQuery('#thelogs').dataTable( {
		"aaSorting": [[ 2, "desc" ]],
		"iDisplayLength" : 100,
		"oLanguage": {
                "sUrl": "scripts/datatable-swedish.txt"
        }
	} );
	
	//for data-table * order-status.php
	jQuery('#theorderstatus').dataTable( {
		"aaSorting": [[ 0, "asc" ]],
		"iDisplayLength" : 100,
		"oLanguage": {
                "sUrl": "scripts/datatable-swedish.txt"
        }
	} );
			//for data-table * booking_report.php
	jQuery('#booking_report').dataTable( {
		"aaSorting": [[ 0, "desc" ]],
		"iDisplayLength" : 100,
		"oLanguage": {
                "sUrl": "scripts/datatable-swedish.txt"
        }
	} );
	
	//for data-table * announcement.php
	jQuery('#theannouncements').dataTable( {
       	"aaSorting": [[ 0, "desc" ]],
       	"iDisplayLength" : 100,
		"oLanguage": {
                "sUrl": "scripts/datatable-swedish.txt"
        }
	} );
	
	//for data-table * shift.php
    jQuery('#theshifts').dataTable( {
        "aaSorting": [[ 0, "desc" ]],
        "iDisplayLength" : 100,
		"oLanguage": {
                "sUrl": "scripts/datatable-swedish.txt"
        }
    } );
	
	//for data-table * settings.php
    jQuery('#thesettings').dataTable( {
        "aaSorting": [[ 2, "asc" ]],
        "iDisplayLength" : 100,
		"oLanguage": {
                "sUrl": "scripts/datatable-swedish.txt"
        }
    } );
	
	//for data-table * schedule.php
    jQuery('#theschedules').dataTable( {
        "aaSorting": [[ 2, "asc" ]],
        "iDisplayLength" : 100,
		"oLanguage": {
                "sUrl": "scripts/datatable-swedish.txt"
        }
    } );
	
	//for data-table * settings.php
    jQuery('#thenotifications').dataTable( {       
        "aaSorting": [[ 0, "desc" ]],
        "iDisplayLength" : 100,
		"oLanguage": {
                "sUrl": "scripts/datatable-swedish.txt"
        }
    } );
	
	
	//for data-table * account.php
    jQuery('#theaccounts').dataTable( {       
        "aaSorting": [[ 0, "asc" ]],
		"iDisplayLength" : 100,
		"oLanguage": {
                "sUrl": "scripts/datatable-swedish.txt"
        }
    } );
    //for data-table * table-masterlist.php
    jQuery('#themasterlist').dataTable( {       
        "aaSorting": [[ 0, "asc" ]],
		"iDisplayLength" : 100,
		"oLanguage": {
                "sUrl": "scripts/datatable-swedish.txt"
        }
    } );
	
	//for data-table * orders.php
    jQuery('#thecatering').dataTable( {       
        "aaSorting": [[ 0, "asc" ]],
		"iDisplayLength" : 100,
		"oLanguage": {
                "sUrl": "scripts/datatable-swedish.txt"
        }
    } );
	
	jQuery('.closebox').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade, .modalbox, .orderbox').fadeOut();
		
		if(jQuery(this).attr('data-rel')=='order'){
			//setTimeout("window.location.reload();",1000);
			jQuery('.mutebtn').css('background','none');
			jQuery('.mutebtn').removeClass('muteon').addClass('muteoff');
			jQuery('.mutebtn').val('');
			jQuery('body').attr('aria-valuetext',0);
		}
		
	});
	
	jQuery('.closebox2').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.order-status').val(jQuery('.order-status').attr('data-rel'));
		jQuery('.fade2, .cancelbox').fadeOut();
		
	});
	
	jQuery('.closebox3').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade2, .proceedbox').fadeOut();
		
	});
	
	jQuery('.closebox4').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade2, .custombox').fadeOut();
		
	});
	
	
	jQuery('.delete-category').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade, .delete-category-box').fadeIn();
		gotoModal();
		
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
				 //setTimeout("window.location.reload()",1000);
			 }
		});
		
	});
	
	jQuery('.delete-catering').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade, .modalbox').fadeIn();
		gotoModal();
		
		var id=jQuery(this).attr('data-rel');
		jQuery('.delete-catering-box input').attr('data-rel',id);
		
		
	});
	
	jQuery('.delete-catering-box input[type=button]').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		var id=jQuery(this).attr('data-rel');
		
		jQuery.ajax({
			 url: "actions/delete-catering.php",
			 type: 'POST',
			 data: 'id='+encodeURIComponent(id),
			 success: function(value){
				 
				 jQuery('.fade, .modalbox, .gradeX-'+id).fadeOut();
				 setTimeout("window.location.reload()",1000);
			 }
		});
		
	});
	
	jQuery('.delete-subcategory').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade, .delete-subcategory-box').fadeIn();
		gotoModal();
		
		var id=jQuery(this).attr('data-rel');
		jQuery('.delete-subcategory-box input').attr('data-rel',id);
		
		
	});
	
	jQuery('.delete-subcategory-box input[type=button]').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		var id=jQuery(this).attr('data-rel');
		
		jQuery.ajax({
			 url: "actions/delete-subcategory.php",
			 type: 'POST',
			 data: 'id='+encodeURIComponent(id),
			 success: function(value){
				 
				 jQuery('.fade, .modalbox, .gradeX-'+id).fadeOut();
				 //setTimeout("window.location.reload()",1000);
			 }
		});
		
	});
	
	jQuery('.delete-user').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade, .modalbox').fadeIn();
		gotoModal();
		
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
				 //setTimeout("window.location.reload()",1000);
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
		gotoModal();
		
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
	
	jQuery('.delete-setting').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade, .delete-setting-box').fadeIn();
		gotoModal();
		//gotoModal();
		
		var id=jQuery(this).attr('data-rel');
		jQuery('.delete-setting-box input').attr('data-rel',id);
		
		
	});
	
	jQuery('.delete-setting-box input[type=button]').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		var id=jQuery(this).attr('data-rel');
		
		jQuery.ajax({
			 url: "actions/delete-setting.php",
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
		
		jQuery('.fade, .delete-order-status-box').fadeIn();
		gotoModal();
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
		
		jQuery('.fade, .delete-menu-box').fadeIn();
		gotoModal();
		
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
				 //setTimeout("window.location.reload()",1000);
			 }
		});
		
	});
	
	jQuery('.cboxs').click(function(){
		var val=jQuery(this).prop('checked');
		var id=jQuery(this).attr('data-rel');
		
		if(val==true){
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
		gotoModal();
		
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
		gotoModal();
		
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
	
	//for edit-currency-page
	jQuery('.edit-announcement-btn').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		editAnnouncement();
	});
	
	jQuery('.edit-announcement-page input').keyup(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		//if(e.which==13)
			//editAnnouncement();
	});
	
	jQuery('.the_currency').click(function(){
		var id=jQuery(this).attr('data-rel');
		
		jQuery.ajax({
			 url: "actions/default-currency.php",
			 type: 'POST',
			 data: 'id='+encodeURIComponent(id),
			 success: function(value){}
		});
		
	});
	
	
	jQuery(function(){
        // For date and time drop down.
        jQuery('#shift-datetime').datetimepicker({
            timeFormat: "hh:mm TT"
        });
		
		jQuery('.starttime, .endtime, .bookingstarttime, .bookingendtime, .business_start_time, .business_end_time').timepicker({
			//timeFormat: 'hh:mm tt'
			minuteGrid: 15,
			addSliderAccess: true,
			sliderAccessArgs: { touchonly: false }
		});
		
		jQuery('.startdate').datepicker({
			firstDay: 1,
			minDate: 0, 
			dateFormat: 'mm/dd/yy',
			onSelect: function(selectedDate){
				var sdate = jQuery(this).datepicker('getDate');
				var mm=sdate.getMonth()+1;
				var dd=sdate.getDate();
				var yyyy=sdate.getFullYear();
				
				if(mm<10){
					mm="0"+mm;
				}
				if(dd<10){
					dd="0"+dd;
				}
				
				var sd=mm+"/"+dd+"/"+yyyy;
				var ed=jQuery('.enddate').val();
				
				if(ed!=''){
					
					
					if(new Date(sd)<= new Date(ed)){
						
						var nsd=new Date(sd);
						var ned=new Date(ed);
						
						var datediff=Number((ned-nsd)/(24*60*60*1000));
						
						if(datediff>=7){
							jQuery('.thedays').each(function() {
                               jQuery(this).prop('checked',true).attr('disabled',false);
                            });
						}
						else{
							
							jQuery('.thedays').each(function() {
                                jQuery(this).prop('checked',false).attr('disabled',true);
                            });
							
							var dayName= new Array('sun','mon','tue','wed','thu','fri','sat');
							var d=nsd.getDay();
							
							var count=d+datediff;
							
							var checker=0;
							for(i=d;i<=count;i++){
								if(i<=6){
									jQuery('#'+dayName[i]).prop('checked',true).attr('disabled',false);
								}
								else{
									checker+=1;
								}
							}
							
							if(checker>0){
								for(i=0;i<checker;i++){
									jQuery('#'+dayName[i]).prop('checked',true).attr('disabled',false);
								}
							}
							
							
						}
						
					}
					else{
						alert('Start Date should be less than or equal to the End Date.');
						jQuery(this).val('');
					}
				}
				
			}
		});
		
		jQuery('.enddate').datepicker({
			firstDay: 1,
			minDate: 0, 
			dateFormat: 'mm/dd/yy',
			onSelect: function(selectedDate){
				var edate = jQuery(this).datepicker('getDate');
				
				var mm=edate.getMonth()+1;
				var dd=edate.getDate();
				var yyyy=edate.getFullYear();
				
				if(mm<10){
					mm="0"+mm;
				}
				if(dd<10){
					dd="0"+dd;
				}
				
				var ed=mm+"/"+dd+"/"+yyyy;
				var sd=jQuery('.startdate').val();
				
				if(sd!=''){
					
					if(new Date(sd)<=new Date(ed)){
						
						var nsd=new Date(sd);
						var ned=new Date(ed);
						
						var datediff=Number((ned-nsd)/(24*60*60*1000));
						
						if(datediff>=7){
							jQuery('.thedays').each(function() {
                                jQuery(this).prop('checked',true).attr('disabled',false);
                            });
						}
						else{
							
							jQuery('.thedays').each(function() {
                                jQuery(this).prop('checked',false).attr('disabled',true);
                            });
							
							var dayName= new Array('sun','mon','tue','wed','thu','fri','sat');
							var d=nsd.getDay();
							
							var count=d+datediff;
							
							var checker=0;
							for(i=d;i<=count;i++){
								if(i<=6){
									jQuery('#'+dayName[i]).prop('checked',true).attr('disabled',false);
								}
								else{
									checker+=1;
								}
							}
							
							if(checker>0){
								for(i=0;i<checker;i++){
									jQuery('#'+dayName[i]).prop('checked',true).attr('disabled',false);
								}
							}
							
							
						}
						
					}
					else{
						alert('Start Date should be less than or equal to the End Date.');
						jQuery(this).val('');
					}
				}
				
			}
		});
		
    });
	
	 // For add-shift-page, add new shift.
    jQuery('.add-shift-btn').click(function(e){
        
        /* Prevent default actions */
        e.preventDefault();
        e.stopPropagation();
        
        addShift();
    });
    
    jQuery('.add-shift-page input').keyup(function(e){
        
        /* Prevent default actions */
        e.preventDefault();
        e.stopPropagation();
        
        if(e.which==13)
            addShift();
    });
    
    // For edit-shift-page, edit shift by id.
    jQuery('.edit-shift-btn').click(function(e) {
        /* Prevent default actions */
        e.preventDefault();
        e.stopPropagation();
        
        editShift();
    });
    
    jQuery('.edit-shift-page input').keyup(function(e) {
        /* Prevent default actions */
        e.preventDefault();
        e.stopPropagation();
        
        if(e.which == 13) {
            editShift();
        }
    });
    
    // For delete-shift, delete shift by id.
    jQuery('.delete-shift').click(function(e) {
        /* Prevent default actions */
        e.preventDefault();
        e.stopPropagation();
        
        jQuery('.fade, .modalbox').fadeIn();
        gotoModal();
        
        var id=jQuery(this).attr('data-rel');
        
        jQuery('.delete-shift-box input').attr('data-rel',id);
    });
    
    jQuery('.delete-shift-box input[type=button]').click(function(e) {
        /* Prevent default actions */
        e.preventDefault();
        e.stopPropagation();
        
        var id = jQuery(this).attr('data-rel');
        
        jQuery.ajax( {
             url: "actions/delete-by-id.php",
             type: 'POST',
             data: 'table=shift_request&id=' + encodeURIComponent(id),
             success: function(value) {
                 jQuery('.fade, .modalbox, .gradeX-' + id).fadeOut();
                 
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
            val=2;
        }
        
        jQuery.ajax({
             url: "actions/status-shift.php",
             type: 'POST',
             data: 'id='+id+'&val='+val,
             success: function(value){
                 jQuery('.fade, .modalbox, .gradeX-' + id).fadeOut();
                 alert(value);
                 return false;
                 setTimeout("window.location.reload()");
            }
        });
        
    });
	
	
	// For add-settings-page
    jQuery('.settings-btn').click(function(e) {
        /* Prevent default actions */
        e.preventDefault();
        e.stopPropagation();
        
        addSettings();
    });
    
    /*jQuery('.settings-page input').keyup(function(e) {
      
        e.preventDefault();
        e.stopPropagation();
        
        if(e.which == 13) {
            addSettings();
        }
    });*/
	
	// For scheduler-page
    jQuery('.scheduler-btn').click(function(e) {
        /* Prevent default actions */
        e.preventDefault();
        e.stopPropagation();
        
        addSchedule();
    });
	
    
    /*jQuery('.scheduler-page input').keyup(function(e) {
      
        e.preventDefault();
        e.stopPropagation();
        
        if(e.which == 13) {
            addSchedule();
        }
    });*/
	
	jQuery('.delete-schedule').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade, .modalbox').fadeIn();
		gotoModal();
		
		var id=jQuery(this).attr('data-rel');
		jQuery('.delete-schedule-box input').attr('data-rel',id);
		
		
	});
	
	jQuery('.delete-schedule-box input[type=button]').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		var id=jQuery(this).attr('data-rel');
		
		jQuery.ajax({
			 url: "actions/delete-schedule.php",
			 type: 'POST',
			 data: 'id='+encodeURIComponent(id),
			 success: function(value){
				 
				 jQuery('.fade, .modalbox, .gradeX-'+id).fadeOut();
				 //setTimeout("window.location.reload()",1000);
			 }
		});
		
	});
	jQuery('.delete-notification').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade, .modalbox').fadeIn();
		gotoModal();
		
		var id=jQuery(this).attr('data-rel');
		jQuery('.delete-notification-box input').attr('data-rel',id);
		
		
	});
	
	jQuery('.delete-notification-box input[type=button]').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		var id=jQuery(this).attr('data-rel');
		
		
		jQuery.ajax({
			 url: "actions/delete-notification.php",
			 type: 'POST',
			 data: 'id='+encodeURIComponent(id),
			 success: function(value){
				 
				 jQuery('.fade, .modalbox, .gradeX-'+id).fadeOut();
				 //setTimeout("window.location.reload()",1000);
			 }
		});		
		
	});
	jQuery('.copy-notification').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		jQuery('.fade, .modalbox').fadeIn();
		gotoModal();
		
		var id=jQuery(this).attr('data-rel');
		jQuery('.copy-notification-box input').attr('data-rel',id);
		
		
	});
	
	jQuery('.copy-notification-box input[type=button]').click(function(e){
		
		/* Prevent default actions */
		e.preventDefault();
		e.stopPropagation();
		
		var id=jQuery(this).attr('data-rel');
		
		
		jQuery.ajax({
			 url: "actions/copy-notification.php",
			 type: 'POST',
			 data: 'id='+encodeURIComponent(id),
			 success: function(value){
				 
				 //jQuery('.fade, .modalbox, .gradeX-'+id).fadeOut();
				 setTimeout("window.location.reload()",1000);
			 }
		});		
		
	});
	
});
function timeFormat(mins){
	
	var hour=parseInt(Number(mins)/60);
	var mins=mins-(hour*60);
	
	var time=mins+' min';
	
	if(hour>0){
		 if(mins>0)
			time=hour+' tim '+mins+' min';
		 else
			time=hour+' tim';
	}
	
	return time;
	
}
jQuery(function() {
  setInterval(function(){ 
  		
		jQuery.ajax({
			 url: "actions/viewedReservation.php",
			 success: function(value){
				 
				 if(value!=0){
					 // alert(value);
					 jQuery(".orders-li").prepend("<div id='notif'>"+value+"</div>");
				}else{
					jQuery(".orders-li #notif").remove();
				}
			 }
		});		
		
   }, 500);
   
  
	/* Android */
	window.addEventListener("resize", function() {
		is_keyboard = (window.innerHeight < initial_screen_size);
		is_landscape = (screen.height < screen.width);
	
		updateViews();
	}, false);
	
	/* iOS */
	$("#signatory").bind("focus blur",function() {
		$(window).scrollTop(10);
		is_keyboard = $(window).scrollTop() > 0;
		$(window).scrollTop(0);
		updateViews();
	});
});

function updateViews() {
	var top = 0;
	var position = $(".detail-order-box").position();
	top = position.top();
	console.log(top+'px '+is_keyboard);
	
	if(top==0){
		top = '30%';
	}
	
    if (is_keyboard) {
        $(".proceed-order-box.proceedbox").css({'top':top+'px'});
        if (is_landscape) {
           //do something for landscape
        }
        else {
           //portrait
        }
    }else {
       $(".proceed-order-box.proceedbox").css({'top':'30%'});
    }
}