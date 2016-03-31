<?php include 'config.php';

	//Thu Sep 25 2014 16:20:52 GMT+0800 (PHT)
	$themonth = date("m");
	$theday = date("d");
	$theyear = date("Y");
	$thehour = date("H");
	$themin = date("i");
	
	echo $theyear.'-'.$themonth.'-'.$theday.' '.$thehour.':'.$themin;


?>