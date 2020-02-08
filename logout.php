<?php
session_start();
unset($_SESSION['clientToken']);
unset($_SESSION['typKonta']);
header("Location:index.php");
?>