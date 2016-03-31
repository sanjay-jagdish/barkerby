<?php
include '../config/config.php';

$res_id=mysql_real_escape_string(strip_tags($_POST['res_id']));
$login_userid=mysql_real_escape_string(strip_tags($_POST['login_userid']));

$getReservation = mysql_query("SELECT * FROM reservation WHERE id='".$res_id."' AND reservation_type_id in (2,3)  AND (approve=13 OR approve=19)") or die(mysql_error());
$ReservationRow = mysql_fetch_assoc($getReservation);
$checkResult = mysql_num_rows($getReservation);

if($checkResult > 0){
	
	$getSettings = mysql_query("SELECT * FROM settings") or die(mysql_error());
	while($SiteSettings = mysql_fetch_assoc($getSettings)){
		if($SiteSettings['var_name'] == 'site_url'){
			$siteURL = $SiteSettings['var_value'];
		}
		
		if($SiteSettings['var_name'] == 'smtp_from'){
			$siteName = $SiteSettings['var_value'];
		}
		
		if($SiteSettings['var_name'] == 'takeaway_content'){
			$siteDesc = $SiteSettings['var_value'];
		}
	}
	
	$derivedTotalAmount = 0;
	$getTotalAmount = mysql_query("SELECT * FROM reservation_detail WHERE reservation_id=".$res_id) or die(mysql_error());
	while($ResTotalAmount = mysql_fetch_assoc($getTotalAmount)){
		$derivedTotalAmount = $derivedTotalAmount+(($ResTotalAmount['quantity'])*($ResTotalAmount['price']));
	}

	$ActiveFee = 0;
	//$dateObj = new DateTime();
	//$dateCurrnt = $dateObj->format('Y-m-d H:i:s');
	$dateCurrnt = $ReservationRow['asap_datetime'];
	$dateApproved = new DateTime($dateCurrnt);
	$dateReservation = new DateTime($ReservationRow['date_time']);
	$AdminResponseTime = $dateReservation->diff($dateApproved);
	
	//if( ($AdminResponseTime->i) <= 10 ){
		$PortalDB = new mysqli(PORTAL_HOST,PORTAL_USER,PORTAL_PASSWORD,PORTAL_DBNAME);
		
		// Check connection
		if ($PortalDB->connect_errno) {
			die('Connect Error: ' . $PortalDB->connect_errno);
		} else {
			
			$CheckPortal = "SELECT * FROM portal WHERE siteURL='".$siteURL."'";
			$CheckPortalExec = $PortalDB->query($CheckPortal);
			$PortalInfo = $CheckPortalExec->fetch_assoc();
			
			if( ($CheckPortalExec->num_rows) >= 1 ){
				$portalID = $PortalInfo['id'];
			
				$CheckFee = "SELECT * FROM fixedFee WHERE portal_id=".$portalID." and status = 1 ";
				$CheckFeeExec = $PortalDB->query($CheckFee);
				
				if( ($CheckFeeExec->num_rows) >= 1 ){
					$SiteActiveFee = $CheckFeeExec->fetch_assoc();
					$ActiveFee = $SiteActiveFee['fixedFeeID'];
				}else{
					$insertIniFee = "INSERT INTO fixedFee (portal_id,account_id,fee,date_set,status,feebase) VALUES('".$portalID."', '0', '10', NOW(), '1', '1')";
					$insertIniFeeExec = $PortalDB->query($insertIniFee);
					
					$CheckFee = "SELECT * FROM fixedFee WHERE portal_id=".$portalID." and status = 1";
					$CheckFeeExec = $PortalDB->query($CheckFee);
					
					if( ($CheckFeeExec->num_rows) >= 1 ){
						$SiteActiveFee = $CheckFeeExec->fetch_assoc();
						$ActiveFee = $SiteActiveFee['fixedFeeID'];
					}
					
				}
			} else {
				$InsertToPortalTableQuery = "INSERT INTO portal(siteURL,siteName,status) VALUES('".$siteURL."','".$siteName."',1)";
				$InsertEntryToPortalTable = $PortalDB->query($InsertToPortalTableQuery);
				$portalID = $PortalDB->insert_id;
				
				$insertIniFee = "INSERT INTO fixedFee (portal_id,account_id,fee,date_set,status,feebase) VALUES('".$portalID."', '0', '10', NOW(), '1', '1')";
				$insertIniFeeExec = $PortalDB->query($insertIniFee);
				
				$CheckFee = "SELECT * FROM fixedFee WHERE portal_id=".$portalID." and status = 1";
				$CheckFeeExec = $PortalDB->query($CheckFee);
				
				if( ($CheckFeeExec->num_rows) >= 1 ){
					$SiteActiveFee = $CheckFeeExec->fetch_assoc();
					$ActiveFee = $SiteActiveFee['fixedFeeID'];
				}
				
			}
			
			//Check if the order isn't saved in the portal fee
			
			$qrycheck = "Select `transTakeAwayID` from transtakeaway where MEPTakeAwayID = ".$res_id;
			$qrycheckres = $PortalDB->query($qrycheck);
			
			if(!($qrycheckres->num_rows) >= 1){
					
				$InsertEntryToPortalQuery = "INSERT INTO transtakeaway(MEPTakeAwayID,portal_id,customer_no,customer_name,fixedfee_id,amount,description,respondTime,approved,MEPUserID,MEPTransDate,MEPOrderDate,payment_mode,kco_payment) VALUES(".$res_id.",".$portalID.",'".$ReservationRow['account_id']."','".acctid_name($ReservationRow['account_id'])."',".$ActiveFee.",".number_format($derivedTotalAmount,2).",'".$siteDesc."','".($AdminResponseTime->h.':'.$AdminResponseTime->i.':'.$AdminResponseTime->s)."',1,".$_SESSION['login']['id'].",'".$dateCurrnt."','".$ReservationRow['date_time']."','".$ReservationRow['payment_mode']."','".$ReservationRow['kco_payment']."')";
				$InsertEntryToPortal = $PortalDB->query($InsertEntryToPortalQuery);
				
				if($InsertEntryToPortal){
					
					mysql_query("INSERT INTO portal_entry(portalID,MEPTakeAwayID,MEPUserID,MEPTransDate) VALUES(".$portalID.",".$res_id.",".$login_userid.",'".$dateCurrnt."')") or die(mysql_error());
				
					echo 1;
				
				}else{
				
					echo 0;
				
				}
					
			}
					
			$PortalDB->close();
		}
		
	//}// ($AdminResponseTime->i) <= 10 end

}// $checkResult end

?>