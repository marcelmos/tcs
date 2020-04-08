<html>
<?php
session_start();
$typKonta[0] = $_SESSION['typKonta'];

if($typKonta[0] != "1"){
    header("Location: logout.php");         //Check if client token is is admin
}

//Connect to DB
require_once('db_ini.php');
$db = mysqli_connect($host, $db_user, $db_pass, $db);

?>

<head>
    <meta charset="utf-8">
    <title>Kreator użytkownik</title>
    <link rel="stylesheet" href="styl.css">
</head>

<body>
    <div class="top">
        <h2>Kreator użytkownik
            <a href="logout.php"><button class="logout">Wyloguj się</button></a>
        </h2>
        <a href="adminProfile.php"><input type="button" value="Wróć do Panelu Głównego"></a>
        <a href="profileEdit.php"><input type="button" value="Zmień login/hasło"></a>
        <a href="accountManager.php"><input type="button" value="Menedżer kont"></a>
        <a href="mainCounter.php"><input type="button" value="Główny licznik"></a>
        <a href="generateReport.php"><input type="button" value="Kreator raportów"></a>
        <a href="sendFile.php"><input type="button" value="Zarządzaj czynszami"></a>
    </div>

    <div class="main-full">
        <div class="alert"><h2>UWAGA</h2>Nie należy odświerzać strony po wprowadzeniu wartości w pola.</div>

        <form method="post">
            Dane lokatora:<br>
            <br>
            Imie: <br>
            <input type="text" name="firstName"><br>
            Nazwisko: <br>
            <input type="text" name="lastName"><br>
            <hr>
            Dane konta:<br>
            <br>
            Login: <br>
            <input type="text" name="login"><br>
            <?php
                if(isset($_SESSION['e_login'])){
                    echo "<div class='error'>".$_SESSION['e_login']."</div>";
                    unset($_SESSION['e_login']);
                }
            ?>
            Hasło: <br>
            <input type="password" name="password"><br>
            <?php
                if(isset($_SESSION['e_newPass'])){
                    echo "<div class='error'>".$_SESSION['e_pass']."</div>";
                    unset($_SESSION['e_newPass']);
                }
            ?>
            Powtórz hasło: <br>
            <input type="password" name="repPassword"><br>
            Typ konta:<br>
            <select name="idKonta">
                <option value="2">Lokator</option>
                <option value="1">Administrator</option>
            </select><br>
            <br>
            <input type="submit">
        </form>
        <?php
            // if(isset($_SESSION["usrCreated"])){
            //     echo $_SESSION["usrCreated"];
            //     unset($_SESSION["usrCreated"]);
            // }else if(isset($_SESSION["err_usrCreate"])){
            //     echo $_SESSION["err_usrCreate"];
            //     unset($_SESSION["err_usrCreated"]);
            // }else{
            //     echo "";
            // }
        ?>

    </div>
    <!--
    <div class="main insert">
        <form action="sendData.php" method="post">
            Stan licznika:<br>
            <input type="number" name="licznik">m<sup>3</sup><br>
            Data odczytu:<br>
            <input type="date" name="data"><br>
            <br>
            <input type="submit" value="Wyślij">
        </form>
    </div>
-->
</body>
<!-- <script>
    function check(){
        var password = document.getElementsByName("password")[0].value;
        var checkPass = document.getElementsByName("password")[1].value;

        if(password == checkPass){
            document.getElementsByName("password")[0].style.border = "solid 2px green";
            document.getElementsByName("password")[1].style.border = "solid 2px green";
            document.getElementById("submit").disabled = false;
        }else{
            document.getElementsByName("password")[0].style.border = "solid 2px red";
            document.getElementsByName("password")[1].style.border = "solid 2px red";
            document.getElementById("submit").disabled = true;
        }
    }
</script> -->
<?php
    if(isset($_POST['login'])){
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $login = $_POST['login'];
        $pass = $_POST['password'];
        $repPass = $_POST['repPassword'];
        $accountType = $_POST['idKonta'];

        $all_OK = true;

        $newPass_hash = password_hash($pass, PASSWORD_DEFAULT);


        // $checkResoult = mysqli_query($db, "INSERT INTO lokatorzy(imie, nazwisko, login, haslo, typKonta_id) VALUES ('$firstName', '$lastName','$login','$newPass_hash','$accountType')"); //Create new account

         //Check login
         if((strlen($login)<3) || (strlen($login)>20)){
            $all_OK = false;
            $_SESSION['e_login'] = "Login musi posiadać od 3 do 20 znaków!";
        }

        if(ctype_alnum($newLogin)==false){
            $all_OK = false;
            $_SESSION['e_login'] = "Login może składać się wyłącznie z liter i cyfr (bez polskich znaków)";
        }

        //Check new password
        if((strlen($pass)<8) || (strlen($pass)>20)){
            $all_OK = false;
            $_SESSION['e_pass'] = "Hasło musi posiadać od 8 do 20 znaków!";
        }

        if($pass != $repPass){
            $all_OK = false;
            $_SESSION['e_pass'] = "Podane hasła nie są identyczne!";
        }

        //Create account
        if(all_OK==true){
            mysqli_query($db, "INSERT INTO lokatorzy(imie, nazwisko, login, haslo, typKonta_id) VALUES ('$firstName', '$lastName','$login','$newPass_hash','$accountType')"); //Create new account
            $_SESSION["usrCreated"] = "<br><br><div class='correct'>Użytkownik <b>$firstName $lastName</b> utworzony pomyślnie</div>";
            header("Location: createUser.php");
            exit();
        }

        if(all_OK==false){
            $_SESSION["err_usrCreate"] = "<br><br><div class='error'>Wystąpił błąd przy tworzeniu nowego użytkownika</div>";
            header("Location: createUser.php");
            exit();
        }

        mysqli_close($db);
    }
//    if($succes == true){
//        echo "Urzytkownik utworzony poprawnie.";
//    }else if($succes == false){
//        echo "Tworzenie urzytkownika zakończyło się niepowodzeniem.";
//    }
?>
</html>