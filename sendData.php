<?php
session_start();
$clientId = $_SESSION['clientToken'];       //Id aktualnego urzytkownika w danej sesji

//Connect to DB
require_once('db_ini.php');
$db = mysqli_connect($host, $db_user, $db_pass, $db);

$stanLicznika = $_POST['licznik'];
$dataOdczytu = date("Y-m-d");


if((($stanLicznika >= 0)&&($stanLicznika <= 99999))&&($dataOdczytu > 0)){
    $query = mysqli_query($db, "INSERT INTO dane (idLokatora, stanLicznika, dataOdczytu) VALUES ('$clientId[0]', '$stanLicznika', '$dataOdczytu')");
    echo "Klient: ".$clientId[0]." Stan Licznika: ".$stanLicznika." Data odczytu: ".$dataOdczytu;
    $_SESSION['clientToken'] = $clientId;
    header("Location: profile.php");
}else{
    $_SESSION["err_valInput"] = "<br><br><div class='error'>Wartość stanu licznika lub data odczytu jest niepoprawna.</div>";
    header("Location: profile.php");
}

mysqli_close($db);
?>