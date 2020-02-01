<html>
<?php
session_start();
$clientId = $_SESSION['clientToken'];       //Data as array
$typKonta = $_SESSION['typKonta'];
    
//Connect to DB
include('db_ini.php');
$db = mysqli_connect($host, $user, $password, $db);

?>

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

                $query = mysqli_query($db, "SELECT imie, nazwisko FROM lokatorzy JOIN lokatorzy_logowanie ON lokatorzy.id = lokatorzy_logowanie.id_lokatorzy WHERE id_logowanie = '$clientId[0]'");
                $resoult = mysqli_fetch_array($query);

                echo $resoult['imie']." ".$resoult['nazwisko'];

                mysqli_close($db);
            }else if($typKonta[0] == "1"){
                echo "Administrator";
            }
            ?>
            <a href="logout.php"><button>Wyloguj się</button></a>
        </h2>
        <?php
        if($typKonta[0] != "1"){
            echo "<a href='profile.php'><input type='button' value='Wróć'></a>";
        }else if($typKonta[0] == "1"){
            echo "<a href='adminProfile.php'><input type='button' value='Wróć do Panelu Głównego'></a> ";
            echo "<a href='createUser.php'><input type='button' value='Kreator użytkownik'></a> ";
            echo "<a href='generateReport.php'><input type='button' value='Kreator raportów'></a> ";
        }
        ?>
    </div>

    <div class="main-full">
        <form action="sendEdit.php" method="post">
            Zmień login: <br>
            <input type="text" name="newLogin" maxlength="30" id="newLogin" onchange="check()" placeholder="Minimalna długość 4 znaków"><br>
            Nowe hasło: <br>
            <input type="password" name="newPassword" maxlength="30" id="newPassword" onchange="check()" placeholder="Minimalna długość 8 znaków"><br>
            Powtóż nowe hasło: <br>
            <input type="password" name="newPasswordRepeat" maxlength="30" id="newPasswordRepeat" onchange="check()"><br>
            Stare hasło: <br>
            <input type="password" name="actualPassword" id="actualPassword" onchange="check()"> <br>
            <br>
            <input type="submit" id="submit" value="Wprowadź zmiany" disabled><br>
        </form>
        <small>*Wypełnione pola zostaną zmienione <br> Maksymalna długość loginu i hasła to 30 znaków</small>
    </div>
</body>
<script>
    function check() {
        var newLogin = document.getElementById("newLogin").value;
        var newPassword = document.getElementById("newPassword").value;
        var newPasswordRepeat = document.getElementById("newPasswordRepeat").value;
        var actualPassword = document.getElementById("actualPassword").value;
        var passwrdCorrect = false;

        if ((newPassword == newPasswordRepeat) && ((newPassword.length >= 8) && (newPasswordRepeat.length >= 8))) {
            document.getElementById("newPassword").style.border = "solid 5px #1baf00";
            document.getElementById("newPasswordRepeat").style.border = "solid 5px #1baf00";
            passwrdCorrect = true;
        } else if ((newPassword != newPasswordRepeat) || ((newPassword.length < 8) || (newPasswordRepeat.length < 8))) {
            document.getElementById("newPassword").style.border = "solid 5px red";
            document.getElementById("newPasswordRepeat").style.border = "solid 5px red";
            passwrdCorrect = false;
        } else {
            document.getElementById("newPassword").style.border = "none";
            document.getElementById("newPasswordRepeat").style.border = "none";
            passwrdCorrect = false;
        }

        if ((passwrdCorrect == true) && (actualPassword.length > 0)) {
            document.getElementById("submit").disabled = false;
            document.getElementById("actualPassword").style.border = "none";
        } else if ((passwrdCorrect == true) && (newLogin.length >= 4) && (actualPassword.length > 0)) {
            document.getElementById("submit").disabled = false;
            document.getElementById("actualPassword").style.border = "none";
        } else if ((newLogin.length >= 4) && (actualPassword.length > 0)) {
            document.getElementById("submit").disabled = false;
            document.getElementById("actualPassword").style.border = "none";
        } else {
            document.getElementById("submit").disabled = true;
            document.getElementById("actualPassword").style.border = "solid 5px red";
        }
    }
</script>

</html>