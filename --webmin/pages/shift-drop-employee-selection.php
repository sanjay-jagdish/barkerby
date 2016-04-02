<?php include_once('redirect.php'); ?>
<?php
session_start();
ini_set('display_errors',0);

include '../config/config.php';

if(isset($_GET['id'])){
?>
Skiftbyte # <strong><?php echo $_GET['id']; ?></strong>.
<!--You are about to approve SR# <strong><?php //echo $_GET['id']; ?></strong>.-->
<br /><br />
<i>Välj den person som ska få skiften.</i><br /><br />
<?php			
	$q=mysql_query("SELECT other_employee, accepted FROM shift_request WHERE id=".$_GET['id']) or die(mysql_error());
	$r=mysql_fetch_assoc($q);
	$emp = explode(',',$r['other_employee']);
	$options='';
	foreach($emp as $k => $eid){
		$emp_det = acctid_name_email($eid);
		$sel='';
		$note='';
		
		if($eid==$r['accepted']){ $sel='selected="selected"'; $note=' (Accepted)'; }
		
		$options .= '<option value="'.$eid.'" '.$sel.'>'.$emp_det['name'].' '.$note.' &lt;'.$emp_det['email'].'&gt; </option>'."\n";
	}
}
?>
<form>
<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
<select name="employee_id" style="width:100%">
	<option> - </option>
	<?php echo $options; ?>
</select>
</form>