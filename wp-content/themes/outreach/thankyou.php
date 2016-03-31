<?php
/*
	Template Name: Thank You
*/

	get_header();
	
	include 'config.php';

?>
<div class="thankyoupage">
	<div class="thankyoupage-inner">
		<p style="color:white;">Thank you for registering with us.  You can now make online orders and reservations.</p>
        <?php $id=encrypt_decrypt('decrypt', $_GET['str']);
		
			$q=mysql_query("update account set confirmed=1 where id=".$id);
			
		?>
    </div>
</div>
	<?php if($q){?>
	<script type="text/javascript">
		
    	setTimeout('window.location="<?php echo $site_url; ?>"',2000);
		
    </script>
	<?php } ?>
<?php
	get_footer();
?>