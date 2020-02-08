<?php

session_start(); //Start session

if((!isset($_POST['login'])) || (!isset($_POST['password']))){                       //Check if inputs are filled
    header("Location: index.php");
    exit();
}else{

    //Connect to DB
    require_once('db_ini.php');
    $db = mysqli_connect($host, $db_user, $db_pass, $db);

    $login = $_POST['login'];
    $passw = $_POST['password'];

    $query = mysqli_query($db, "SELECT id, typKonta_id, login, haslo FROM lokatorzy WHERE (login = '$login') AND (haslo = '$passw')");
    $resoult = mysqli_fetch_array($query);

    if(($resoult["login"] == $login) && ($resoult["haslo"] == $passw)){

        $_SESSION['clientToken'] = $resoult["id"];          //Take client id and create token
        $_SESSION['typKonta'] = $resoult["typKonta_id"];    //Take client type id and create token
        
        if($resoult[1] == "1"){
            header("Location: adminProfile.php");
        }else if($resoult[1] == "2"){
            header("Location: profile.php");
        }
    //    $query = mysqli_query($db, "SELECT imie, nazwisko")
    //    echo "Poprawne zalogowanie do użytkownika ".
    }else{
        header("Location: loginError.html");
    }
    echo mysqli_error($db);
    mysqli_close($db);
    
}
    
?>