<html>
<?php
error_reporting(E_ALL ^ E_NOTICE);      //Hide notices ENABLE IF PUBLIC
session_start();
$typKonta[0] = $_SESSION['typKonta'];

    if($typKonta[0] != "1"){
    header("Location: logout.php");     //Check if client token is is admin
}

//Connect to DB
require_once('db_ini.php');
$db = mysqli_connect($host, $db_user, $db_pass, $db);

?>
<head>
    <meta charset="utf-8">
    <title>Panel Główny</title>
    <link rel="stylesheet" href="styl.css">
</head>

<body>
    <div class="top">
        <h2>Edycja wpisu
            <a href="logout.php"><button class="logout">Wyloguj się</button></a>
        </h2>
    </div>

    <div class="main-full">
        <table>
            <tr>
                <th>Imię i nazwisko</th>
                <th>Stan najnowszego odczytu</th>
                <th>Data odczytu</th>
            </tr>
            <?php

                $recordId = $_POST['btnAction'];  //Take record ID to work

                $_SESSION['recordIdSession'] = $recordId; //Create record ID session

                $query = mysqli_query($db, "SELECT lokatorzy.id, lokatorzy.imie, lokatorzy.nazwisko, ROUND(stanLicznika, 2) AS stanLicznika, dataOdczytu FROM dane JOIN lokatorzy ON  dane.idLokatora = lokatorzy.id WHERE dane.id = $recordId ORDER BY concat(lokatorzy.id, lokatorzy.nazwisko) ASC, dataOdczytu ASC");

                while($resoult = mysqli_fetch_array($query)){
                    echo "<tr><td>".$resoult['imie']." ".$resoult['nazwisko']."</td><td>".$resoult['stanLicznika']."</td><td>".$resoult['dataOdczytu']."</td></tr>";
                }
            ?>
        </table>

        <form action="dataEdit.php" method="post">
            Nowa wartość stanu licznika:<br>
            <input type="number" step="0.01" name="newValue" placeholder="1234,56">m<sup>3</sup><br>
            <input type="hidden" name="recordId" value=<?php echo $recordId;?>>
            Edytuj date: <br>
            <input type="date" name="newDate"><br>
            <br>
            <input type="submit" name="editValue" class="btnEdit" value="Zapisz zmiany">   <input type="submit" name="deleteValue" class="btnDelete" value="Usuń wpis">
        </form>
        <br>
        <h4>Uwaga!<br> Usunięcie rekordu jest nieodwracalne!</h4>
        <br>
        <a href="adminProfile.php"><button class="btnAction">Anuluj działań</button></a>
    </div>
</body>
</html>

<?php
    if($_POST['deleteValue']){
        $recordId = $_POST['recordId'];
        $queryStat = mysqli_query($db, "DELETE FROM dane WHERE id = $recordId");
        $_SESSION['i_action'] = "Pomyślnie usunięto wpis";
        header("Location: adminProfile.php");
    }
    if($_POST['editValue']){
        $recordId = $_POST['recordId'];
        $newValue = $_POST['newValue'];
        $newDate = $_POST['newDate'];

        if($_POST['newValue']){
            $queryStat = mysqli_query($db, "UPDATE dane SET stanLicznika = '$newValue' WHERE dane.id = $recordId");
            $_SESSION['i_action'] = "Pomyślnie zedytowano wartość licznika";
            header("Location: adminProfile.php");
        }
        if($_POST['newDate']){
            $queryStat = mysqli_query($db, "UPDATE dane SET dataOdczytu = '$newDate' WHERE dane.id = $recordId");
            $_SESSION['i_action'] = "Pomyślnie zedytowano date wpisu";
            header("Location: adminProfile.php");
        }
        if($_POST['newValue'] && $_POST['newDate']){
            $queryStat = mysqli_query($db, "UPDATE dane SET stanLicznika = '$newValue', dataOdczytu = '$newDate' WHERE dane.id = $recordId");
            $_SESSION['i_action'] = "Pomyślnie zedytowano date oraz stan licznika wpisu";
            header("Location: adminProfile.php");
        }
    }

    mysqli_close($db);
?>