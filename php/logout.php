<?php
session_start();
$_SESSION = array();
header( 'Location: /photobook/views/my_pins.php' ) ;
?>