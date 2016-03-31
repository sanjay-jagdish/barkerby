<?php
error_reporting(1);
echo 'gago';
require('php_serial.class.php');
require('php_receipt.class.php');

$serial = new phpSerial;
$recibo = new Receipt;

$serial->deviceSet("COM6");
$serial->deviceOpen('w'); 


$recibo->init();
$recibo->writeLf("Testing printer");;
$recibo->feedCut();
$recibo->finalize();
$escribir = $recibo->__toString();
$serial->sendMessage($escribir);

$serial->deviceClose();
?>