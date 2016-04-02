<?php
session_start();
echo $_SESSION['ccb_name'].'<^>'.$_SESSION['ccb_desc'].'<^>'.$_SESSION['ccb_price'];
?>