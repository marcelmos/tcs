<?php
session_start();
$clientId = $_SESSION['clientToken'];       //Id aktualnego urzytkownika w danej sesji
    
//Connect to DB
include('db_ini.php');
$db = mysqli_connect($host, $user, $password, $db);

$stanLicznika = $_POST['licznik'];
$dataOdczytu = $_POST['data'];


if((($stanLicznika >= 0)&&($stanLicznika <= 99999))&&($dataOdczytu > 0)){
    $query = mysqli_query($db, "INSERT INTO dane (idLokatora, stanLicznika, dataOdczytu) VALUES ('$clientId[0]', '$stanLicznika', '$dataOdczytu')");
    echo "Klient: ".$clientId[0]." Stan Licznika: ".$stanLicznika." Data odczytu: ".$dataOdczytu;
    $_SESSION['clientToken'] = $clientId;
//    header("Location: profile.php");
}else{
    echo "Wartość stanu licznika lub data odczytu jest niepoprawna.";
}

mysqli_close($db);
?>