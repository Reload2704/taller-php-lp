<?php
/**
 * logout.php — Cierra la sesión y regresa al inicio.
 */
session_start();
$_SESSION = [];
session_destroy();
header("Location: index.php");
exit;
?>
