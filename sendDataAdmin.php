<?php
    session_start();
    $typKonta[0] = $_SESSION['typKonta'];

    if($typKonta[0] != "1"){
        header("Location: logout.php");     //Check if client token is is admin
    }

    //Connect to DB
    require_once('db_ini.php');
    $db = mysqli_connect($host, $db_user, $db_pass, $db);

    if($_POST['insertNewData']){
        $licznikInsert = $_POST['licznikInsert'];
        $dateInsert = $_POST['dateInsert'];
        $lokatorInsert = $_POST['lokatorInsert'];

        if(($lokatorInsert>0)&&$licznikInsert&&$dateInsert){
            mysqli_query($db, "INSERT INTO dane (idLokatora, stanLicznika, dataOdczytu) VALUES ($lokatorInsert, ROUND($licznikInsert, 2), '$dateInsert')");
            $_SESSION['i_action'] = "Pomyślnie dodano wpis.";
            header("Location: adminProfile.php");
        }else{
            $_SESSION['e_insert'] = "Niepowodzenie!<br>Należy wypełnić wszystkie pola.";
            header("Location: adminProfile.php");
        }
    }
    //Poprawić kod
    //
    //Działanie:
    //Wprowadzanie wartości w przez administratora w imieniu innego urzytkownika
    mysqli_close($db);
?>