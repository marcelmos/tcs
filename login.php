<?php
session_start();

//Connect to DB
include('db_ini.php');
$db = mysqli_connect($host, $user, $password, $db);

$login = $_POST['login'];
$passw = $_POST['password'];

$query = mysqli_query($db, "SELECT login, haslo FROM logowanie WHERE (login = '$login') AND (haslo = '$passw')");
$resoult = mysqli_fetch_array($query);

if(($resoult["login"] == $login) && ($resoult["haslo"] == $passw)){
    
    $query = mysqli_query($db, "SELECT id FROM logowanie WHERE (login = '$login') AND (haslo = '$passw')");
    $clientId = mysqli_fetch_array($query);
    $_SESSION['clientToken'] = $clientId;
    
    $queryTyp = mysqli_query($db, "SELECT idKonta FROM logowanie WHERE id = '$clientId[0]'");
    $typKonta = mysqli_fetch_array($queryTyp);
    $_SESSION['typKonta'] = $typKonta;
    
    if($typKonta[0] == "1"){
        header("Location: adminProfile.php");
    }else if($typKonta[0] == "2"){
        header("Location: profile.php");
    }
//    $query = mysqli_query($db, "SELECT imie, nazwisko")
//    echo "Poprawne zalogowanie do użytkownika ".
}else{
    header("Location: loginError.html");
}
echo mysqli_error($db);
mysqli_close($db);
    
?>