<html>
<?php
session_start();
$typKonta = $_SESSION['typKonta'];
    
if($typKonta[0] != "1"){
    header("Location: logout.php");
}
        
//Connect to DB
include('db_ini.php');
$db = mysqli_connect($host, $user, $password, $db);
    
?>

<head>
    <meta charset="utf-8">
    <title>Kreator użytkownik</title>
    <link rel="stylesheet" href="styl.css">
</head>

<body>
    <div class="top">
        <h2>Kreator użytkownik
            <a href="logout.php"><button>Wyloguj się</button></a>
        </h2>
        <a href="adminProfile.php"><input type="button" value="Wróć do Panelu Głównego"></a>
        <a href="profileEdit.php"><input type="button" value="Zmień login/hasło"></a>
        <a href="generateReport.php"><input type="button" value="Kreator raportów"></a>
    </div>

    <div class="main-full">
       <div class="alert"><h2>UWAGA</h2>Nie należy odświerzać strony po wprowadzeniu wartości w pola.</div>
       
        <form action="createUser.php" method="post">
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
            Hasło: <br>
            <input type="password" name="password" onchange="check()"><br>
            Powtórz hasło: <br>
            <input type="password" name="password" onchange="check()"><br>
            Typ konta:<br>
            <select name="idKonta">
                <option value="2">Lokator</option>
                <option value="1">Administrator</option>
            </select><br>
            <br>
            <input type="submit" id="submit" disabled>
        </form>
        
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
<script>
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
</script>
<?php
    
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $login = $_POST['login'];
    $pass = $_POST['password'];
    $accountType = $_POST['idKonta'];
    

    if($accountType == 2){
        mysqli_query($db, "INSERT INTO logowanie(idKonta, login, haslo) VALUES ($accountType, '$login', '$pass')"); //Create login datas
        mysqli_query($db, "INSERT INTO lokatorzy(imie, nazwisko) VALUES ('$firstName', '$lastName')"); //Create person
        $queryRelation = mysqli_query($db, "SELECT lokatorzy.id, logowanie.id FROM lokatorzy, logowanie WHERE (lokatorzy.imie = '$firstName')AND(lokatorzy.nazwisko = '$lastName')AND(logowanie.login = '$login')AND(logowanie.haslo='$pass')"); //Search id to relation
//        $result = mysqli_fetch_array($queryRelation);
//        $queryRelationCreate = mysqli_query($db, "INSERT INTO lokatorzy_logowanie(id_lokatorzy, id_logowanie) VALUES (".$result['lokatorzy.id'].", ".$result['logowanie.id'].")");
        while($result = mysqli_fetch_array($queryRelation)){
            mysqli_query($db, "INSERT INTO `lokatorzy_logowanie` (`id_lokatorzy`, `id_logowanie`) VALUES ('$result[0]', '$result[1]')"); //Create relation
        }
        $succes = $db;
    }else{
        mysqli_query($db, "INSERT INTO logowanie(idKonta, login, haslo) VALUES ($accountType, '$login', '$pass')"); //Create login datas
        $succes = $db;
    }
    
    if($succes == true){
        echo "Urzytkownik utworzony poprawnie.";
    }else if($succes == false){
        echo "Tworzenie urzytkownika zakończyło się niepowodzeniem.";
    }
    
    mysqli_close($db);
    
//    if($succes == true){
//        echo "Urzytkownik utworzony poprawnie.";
//    }else if($succes == false){
//        echo "Tworzenie urzytkownika zakończyło się niepowodzeniem.";
//    }
?>
</html>