<?php
session_start();
$clientId[0] = $_SESSION['clientToken'];       //Client individual ID
$typKonta[0] = $_SESSION['typKonta'];          //Account type

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
            <a href="logout.php"><button class="logout">Wyloguj się</button></a>
        </h2>
        <?php
        if($typKonta[0] != "1"){
            echo "<a href='profile.php'><input type='button' value='Wróć'></a> ";
            echo "<a href='czynsze.php'><input type='button' value='Czynsze'></a> ";
            echo "<a href='mainCounter.php'><input type='button' value='Główny licznik'></a> ";
        }else if($typKonta[0] == "1"){
            echo "<a href='adminProfile.php'><input type='button' value='Wróć do Panelu Głównego'></a> ";
            echo "<a href='createUser.php'><input type='button' value='Kreator użytkownik'></a> ";
            echo "<a href='accountManager.php'><input type='button' value='Menedżer kont'></a> ";
            echo "<a href='mainCounter.php'><input type='button' value='Główny licznik'></a> ";
            echo "<a href='generateReport.php'><input type='button' value='Kreator raportów'></a> ";
            echo "<a href='sendFile.php'><input type='button' value='Zarządzaj czynszami'></a>";
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
            <?php
                if(isset($_SESSION['e_actualPass'])){
                    echo "<div class='error'>".$_SESSION['e_actualPass']."</div>";
                    unset($_SESSION['e_actualPass']);
                }
            ?>
            <br>
            <input type="submit" id="submit" value="Wprowadź zmiany" ><br>
            <?php
                if(isset($_SESSION['i_passAndLogin'])){
                    echo "<div class='correct'>".$_SESSION['i_passAndLogin']."</div>";
                    unset($_SESSION['i_passAndLogin']);
                }
                else if(isset($_SESSION['i_login'])){
                    echo "<div class='correct'>".$_SESSION['i_login']."</div>";
                    unset($_SESSION['i_login']);
                }
                else if(isset($_SESSION['i_pass'])){
                    echo "<div class='correct'>".$_SESSION['i_pass']."</div>";
                    unset($_SESSION['i_pass']);
                }
            ?>
        </form>

        <small>*Wypełnione pola zostaną zmienione <br> Maksymalna długość loginu i hasła to 30 znaków</small>
    </div>
</body>

<?php
    if(isset($_POST['actualPassword'])){

        //Inputs status
        $loginCorrect = true;
        $passCorrect = true;
        $accessCheck = false; //Check if actual pass correct

        //Inputs to check
        $newLogin = $_POST['newLogin'];
        $newPass = $_POST['newPassword'];
        $repPass = $_POST['repeatPassword'];
        $actualPass = $_POST['actualPassword'];

        //Check new login
        if((strlen($newLogin)<3) || (strlen($newLogin)>20)){
            $loginCorrect = false;
            $_SESSION['e_login'] = "Login musi posiadać od 3 do 20 znaków!";
        }

        if(ctype_alnum($newLogin)==false){
            $loginCorrect = false;
            $_SESSION['e_login'] = "Login może składać się wyłącznie z liter i cyfr (bez polskich znaków)";
        }

        //Check new password
        if((strlen($newPass)<8) || (strlen($newPass)>20)){
            $passCorrect = false;
            $_SESSION['e_newPass'] = "Hasło musi posiadać od 8 do 20 znaków!";
        }

        if($newPass != $repPass){
            $passCorrect = false;
            $_SESSION['e_newPass'] = "Podane hasła nie są identyczne!";
        }

        //Check actual password


        //Hash password
        $newPass_hash = password_hash($newPass, PASSWORD_DEFAULT);

        //Take datas to check password
        $checkQuery = mysqli_query($db, "SELECT id, haslo FROM lokatorzy WHERE id = '$clientId[0]'");
        $check = mysqli_fetch_array($checkQuery);

        if(($check["id"] == $clientId[0]) && (password_verify($actualPass, $check["haslo"]))){

            if(($loginCorrect == true) && ($passCorrect == true)){
                mysqli_query($db, 'UPDATE lokatorzy SET login="'.$newLogin.'", haslo="'.$newPass_hash.'" WHERE id = "'.$clientId[0].'"');
                $_SESSION['i_passAndLogin'] = "Login i hasło zostały poprawnie zmienione.";
            }else if(($loginCorrect == true) && ($passCorrect == false)){
                mysqli_query($db, 'UPDATE lokatorzy SET login="'.$newLogin.'" WHERE id = "'.$clientId[0].'"');
                $_SESSION['i_login'] = "Login został poprawnie zmieniony.";
            }else if(($loginCorrect == false) && ($passCorrect == true)){
                mysqli_query($db, 'UPDATE lokatorzy SET haslo="'.$newPass_hash.'"  WHERE id = "'.$clientId[0].'"');
                $_SESSION['i_pass'] = "Hasło zostało poprawnie zmienione.";
            }
        }else{
            $_SESSION['e_actualPass'] = "Podane hasło jest nieprawidłowe!";
        }

        // echo("Error description: " . $db -> error);
    }

    mysqli_close($db);
?>
</html>