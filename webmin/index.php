<?php session_start();

   if(isset($_COOKIE['webmin_id'])){
	   
        header('Location:crisp.php?page=dashboard');exit;
		
   }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Mise en Place</title>
<link rel="stylesheet" type="text/css" href="css/style.css">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="scripts/jquery_cookie.js"></script>
<script type="text/javascript" src="scripts/scripts.js"></script>
<script type="text/javascript" src="scripts/ajaximage/scripts/jquery.form.js"></script>
<script type="text/javascript" src="scripts/numeric.js"></script>




<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<!-- TranslateClient BEGIN -->
<script type="text/javascript" src="http://www.google.com/jsapi"></script><script language="javascript">var gtc_stl='http://translateclient.com/js/widget/gtc.css';</script><script type="text/javascript" src="http://translateclient.com/js/widget/gtc.js"></script><script language="javascript">translateclient.srclang="en";translateclient.checkload();gtc_ws=1;</script><div id="gtc_pan"><div id="gtc_t">Just select text on the page and get instant translation from Google Translate!</div><label><input id="gtc_chk" type="checkbox" checked="checked" />Translate to </label><select id="gtc_lang"></select><br><a id="gtc_w" href=""></a> <a id="gtc_d" href="http://translateclient.com">Google Translate Client</a></div>
<!-- TranslateClient END -->
<!-- for data table -->
<!--<script type="text/javascript" language="javascript" src="scripts/data-table/media/js/jquery.dataTables.js"></script>

<SCRIPT LANGUAGE="JavaScript">  
<!-- Disable  
function disableselect(e){  
return false  
}  

function reEnable(){  
return true  
}  

//if IE4+  
document.onselectstart=new Function ("return false")  
document.oncontextmenu=new Function ("return false")  
//if NS6  
if (window.sidebar){  
document.onmousedown=disableselect  
document.onclick=reEnable  
}  -->

</script>
</head>

<?php

//$_GET['log_action'] == 1 = Input Email
//$_GET['log_action'] == 2 = Input Passwords

	if($_GET['log_action'] == '1'){
		?>
        <body class="create-new-password-link">
        <div class="wrapper">
            <div class="login-container">
                <div class="logo">
                    <!--<h2>Logo Goes Here</h2>-->
                </div>
                <p class="message">Uppge din din registrerade mailadress</p>
                <ul>
                    <li style="height: 37px;">
                        <input type="text" id="em" class="logintxt" placeholder="E.post">
                    </li>
                    <li>
                        <input type="button" class="create-new-password-link-btn" value="Återställ lösenord" placeholder="">
                    </li>
                     <li>
                        <div class="added">
                            <a href="http://<?php echo $_SERVER['HTTP_HOST'].'/webmin'?>">Logga in</a>
                        </div>
                    </li>
                </ul>
                <div class="displaymsg"></div>
            </div>
        </div>
        </body>
        
     <?php
		
	}else if($_GET['log_action'] == '2'){
		?>
        <body class="create-new-password">
        <div class="wrapper">
            <div class="login-container">
                <div class="logo">
                    <!--<h2>Logo Goes Here</h2>-->
                </div>
                <p class="message">Uppge ett nytt lösenord.</p>
                <ul>
                    <li style="height: 37px;">
                        <input type="password" id="pass1" class="logintxt" placeholder="Nytt lösenord">
                    </li>
                    <li style="height: 37px;">
                        <input type="password" id="pass2" class="logintxt" placeholder="Uppge lösenordet igen">
                    </li>
                    <li>
                        <input type="button" class="reset-password-btn" value="Uppdatera lösenord" placeholder="" data-rel="<?php echo $_GET['id']; ?>">
                    </li>
                     <li>
                        <div class="added">
                            <a href="http://<?php echo $_SERVER['HTTP_HOST'].'/webmin'?>">Logga in</a>
                        </div>
                    </li>
                </ul>
                <div class="displaymsg"></div>
            </div>
        </div>
        </body>
        
        <?php
		
	}else{
		?>
		<body class="loginpage">
		<div class="wrapper">
			<div class="login-container">
				<div class="logo">
					<!--<h2>Logo Goes Here</h2>-->
				</div>
              	<?php  
					if($_GET['checkemail'] == 'confirm'){
					 	echo '<p class="message">Check your e-mail for the confirmation link.</p>';
					}
                ?>
				<ul>
					<li style="height: 37px;">
						<input type="text" id="em" class="logintxt" placeholder="E.post">
					</li>
					<li style="height: 37px;">
						<input type="password" id="pw" class="logintxt" placeholder="Lösenord">
					</li>
					<li>
						<input type="button" class="loginbtn" value="Logga in" placeholder="">
					</li>
					<li>
						<div class="added">
							<input type="checkbox" id="keep"> <label for="keep">Kom ihåg mig</label>
						</div>
					</li>
					<li>
						<div class="added">
							<a href="?log_action=1">Glömt ditt lösenord?</a>
						</div>
					</li>
				</ul>
				<div class="displaymsg"></div>
			</div>
		</div>
		<div id="google_translate_element"></div><script>
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: 'en'
  }, 'google_translate_element');
}
</script><script src="http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script> 
		</body>
	<?php
	}
?>

</html>
