<?php
session_start();
$clientId = $_SESSION['clientToken'];       //Client individual ID
$typKonta = $_SESSION['typKonta'];          //Account type

//Connect to DB
require_once('db_ini.php');
$db = mysqli_connect($host, $db_user, $db_pass, $db);

?>

<html>
<head>
    <meta charset="utf-8">
    <title>Edycja profilu</title>
    <link rel="stylesheet" href="styl.css">
</head>

<body>
    <div class="top">
        <h2>Edycja konta

            <?php
            if($typKonta[0] != "1"){

                $query = mysqli_query($db, "SELECT imie, nazwisko FROM lokatorzy WHERE id = '$clientId[0]'");
                $resoult = mysqli_fetch_array($query);

                echo $resoult['imie']." ".$resoult['nazwisko'];

            }else if($typKonta[0] == "1"){
                echo "Administrator";
            }
            ?>
            <a href="logout.php"><button>Wyloguj się</button></a>
        </h2>
        <?php
        if($typKonta[0] != "1"){
            echo "<a href='profile.php'><input type='button' value='Wróć'></a> ";
            echo "<a href='czynsze.php'><input type='button' value='Czynsze' disabled></a>";
        }else if($typKonta[0] == "1"){
            echo "<a href='adminProfile.php'><input type='button' value='Wróć do Panelu Głównego'></a> ";
            echo "<a href='createUser.php'><input type='button' value='Kreator użytkownik'></a> ";
            echo "<a href='generateReport.php'><input type='button' value='Kreator raportów'></a> ";
        }
        ?>
    </div>

    <div class="main-full">
        <form method="post">
            Zmień login: <br>
            <input type="text" name="newLogin" maxlength="20" id="newLogin" onchange="check()" placeholder="Minimalna długość 4 znaków"><br>

            <?php
                if(isset($_SESSION['e_login'])){
                    echo "<div class='error'>".$_SESSION['e_login']."</div>";
                    unset($_SESSION['e_login']);
                }
            ?>

            Nowe hasło: <br>
            <input type="password" name="newPassword" maxlength="30" id="newPassword" onchange="check()" placeholder="Minimalna długość 8 znaków"><br>
            <?php
                if(isset($_SESSION['e_newPass'])){
                    echo "<div class='error'>".$_SESSION['e_newPass']."</div>";
                    unset($_SESSION['e_newPass']);
                }
            ?>
            Powtórz nowe hasło: <br>
            <input type="password" name="repeatPassword" maxlength="30" id="repeatPassword" onchange="check()"><br>
            Stare hasło: <br>
            <input type="password" name="actualPassword" id="actualPassword" onchange="check()"> <br>
            <br>
            <input type="submit" id="submit" value="Wprowadź zmiany" ><br>
        </form>

        <small>*Wypełnione pola zostaną zmienione <br> Maksymalna długość loginu i hasła to 30 znaków</small>
    </div>
</body>

<?php
    if(isset($_POST['actualPassword'])){

        $allCorrect = true;    //Inputs status

        //Inputs to check
        $newLogin = $_POST['newLogin'];
        $newPass = $_POST['newPassword'];
        $repPass = $_POST['repeatPassword'];
        $pass = $_POST['actualPassword'];

        //Check new login
        // if((strlen($newLogin)<3) || (strlen($newLogin)>20)){
        //     $allCorrect = false;
        //     $_SESSION['e_login'] = "Login musi posiadać od 3 do 20 znaków!";
        // }

        // if(ctype_alnum($newLogin)==false){
        //     $allCorrect = false;
        //     $_SESSION['e_login'] = "Login może składać się wyłącznie z liter i cyfr (bez polskich znaków)";
        // }

        //Check new password
        if((strlen($newPass)<8) || (strlen($newPass)>20)){
            $allCorrect = false;
            $_SESSION['e_newPass'] = "Hasło musi posiadać od 8 do 20 znaków!";
        }

        if($newPass != $repPass){
            $allCorrect = false;
            $_SESSION['e_newPass'] = "Podane hasła nie są identyczne!";
        }

        //Check actual password
        /*
            KOD SPRAWDZANIA AKTUALNEGO HASŁA
        */

        //Hash password
        $newPass_hash = password_hash($newPass, PASSWORD_DEFAULT);

        if($allCorrect == true){
            // if(isset($newLogin) && isset($newPass_hash)){
            //     mysqli_query($db, 'UPDATE lokatorzy SET login="'.$newLogin.'", haslo="'.$newPass_hash.'" WHERE id = "'.$clientId[0].'"');
            // }

            // if(isset($newLogin)){
            //     mysqli_query($db, 'UPDATE lokatorzy SET login="'.$newLogin.'" WHERE id = "'.$clientId[0].'"');
            // }

            if(isset($newPass_hash)){
                mysqli_query($db, 'UPDATE lokatorzy SET haslo="'.$newPass_hash.'"  WHERE id = "'.$clientId[0].'"');
            }
        }
        // echo("Error description: " . $db -> error);
    }

    mysqli_close($db);
?>
</html>