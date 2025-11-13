<?php
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
header('Location: ' . $scriptDir . '/views/login.php');
exit;
?>