<?php session_start();
	include '../config/config.php';
	
	$catid=strip_tags($_POST['cat']);
	$val=0;
	if($catid!=''){
		
		$q=mysql_query("select * from catering_subcategory where catering_category_id='".$catid."' and deleted=0");
		
		if(mysql_num_rows($q) > 0){
			while($r=mysql_fetch_assoc($q)){
?>
			<option value="<?php echo $r['id']; ?>"><?php echo $r['name']; ?></option>
<?php			
			}
			$val=1;
		}
		
	}
	
	
	if($val==0){
?>
		<option value="">Select</option>
<?php	
	}
?>
