<html>
<?php
error_reporting(E_ALL ^ E_NOTICE);      //Hide notices ENABLE IF PUBLIC
session_start();
$clientId[0] = $_SESSION['clientToken'];       //Data as array
$typKonta[0] = $_SESSION['typKonta'];          //Account type

//Connect to DB
require_once('db_ini.php');
$db = mysqli_connect($host, $db_user, $db_pass, $db);

?>
<head>
    <meta charset="utf-8">
    <title>Główny licznik</title>
    <link rel="stylesheet" href="styl.css">
</head>

<body>
    <div class="top">
        <h2>Główny licznik
            <a href="logout.php"><button class="logout">Wyloguj się</button></a>
        </h2>
        <?php
        if($typKonta[0] != "1"){
            echo "<a href='profile.php'><input type='button' value='Wróć'></a> ";
            echo "<a href='profileEdit.php'><input type='button' value='Zmień login/hasło'></a> ";
            echo "<a href='czynsze.php'><input type='button' value='Czynsze'></a> ";
        }else if($typKonta[0] == "1"){
            echo "<a href='adminProfile.php'><input type='button' value='Wróć do Panelu Głównego'></a> ";
            echo "<a href='profileEdit.php'><input type='button' value='Zmień login/hasło'></a> ";
            echo "<a href='createUser.php'><input type='button' value='Kreator użytkownik'></a> ";
            echo "<a href='accountManager.php'><input type='button' value='Menedżer kont'></a> ";
            echo "<a href='generateReport.php'><input type='button' value='Kreator raportów'></a> ";
            echo "<a href='sendFile.php'><input type='button' value='Prześlij czynsz'></a>";
        }
        ?>
    </div>

    <?php
        //Send new data to DB
        if($typKonta[0] == "1"){
            echo "<div class='main insert' style='display: flex; justify-content: space-between'>
            <div class='newData'>
            <form method='post'>
            <h3>Wprowadź odczyt głównego licznika</h3><br>
            Stan licznika*:<br>
            <input type='number' step='0.01' name='licznikGlowny' placeholder='1234,56'>m<sup>3</sup><br>
            Data odczytu*:<br>
            <input type='date' name='data'><br>
            <br>
            <input type='submit' name='submit' value='Wyślij'><br>
            <br>
            <small>*Pola wymagane</small>
            </form>
            </div>
            <div class='edit'>
            <form method='post'>
            <h3>Edytuj odczyt głównego licznika</h3><br>
            <label>
            Id rekordu:<br>
            <input type='number' name='id'><br>
            </label>
            <label>
            Stan licznika:<br>
            <input type='number' step='0.01' name='newLicznikGlowny' placeholder='1234,56'>m<sup>3</sup><br>
            </label>
            <label>
            Data odczytu:<br>
            <input type='date' name='data'><br>
            </label>
            <br>
            <input type='submit' name='submitEdit' value='Edytuj wpis'> <input type='submit' name='deleteValue' class='btnDelete' value='Usuń wpis'>
            </div>
            </div>";

            if(isset($_SESSION['i_action'])){
                echo "<div class='correct' style='text-align: center;'>".$_SESSION['i_action']."</div>";
                unset($_SESSION['i_action']);
            }
            if(isset($_SESSION['e_action'])){
                echo "<div class='error' style='text-align: center;'>".$_SESSION['e_action']."</div>";
                unset($_SESSION['e_action']);
            }

        }
    ?>

    <div class="main">
        <table>
            <tr>
                <?php if($typKonta[0] == "1") echo "<th>ID</th>"; ?>
                <th>Poprzedni Stan Licznika</th>
                <th>Najnowszy Stan Licznika</th>
                <th>Data Najnowszego Odczytu<br><small>Format daty rok-miesiąc-dzień</small></th>
                <th>Różnica</th>
            </tr>
            <?php

                $query = mysqli_query($db, "SELECT id, ROUND(stanLicznika, 2) AS stanLicznika, dataOdczytu FROM glowny_licznik ORDER BY dataOdczytu ASC");

                $lastValue = 0;
                $difference = 0;
                while($resoult = mysqli_fetch_array($query)){
                    if($lastValue > 0){
                        $difference = $resoult['stanLicznika'] - $lastValue;
                        $lastValue = $lastValue."m<sup>3</sup>";
                    }else{
                        $lastValue = "Brak Danych";
                    }

                    if($typKonta[0] == "1"){
                        echo "<tr>
                        <td>".$resoult['id']."</td>
                        <td>".$lastValue."</td>
                        <td>".$resoult['stanLicznika']."m<sup>3</sup></td>
                        <td>".$resoult['dataOdczytu']."</td>
                        <td>".number_format($difference, 2)."m<sup>3</sup></td></tr>";
                    }else{
                        echo "<tr>
                        <td>".$lastValue."</td>
                        <td>".$resoult['stanLicznika']."m<sup>3</sup></td>
                        <td>".$resoult['dataOdczytu']."</td>
                        <td>".number_format($difference, 2)."m<sup>3</sup></td></tr>";
                    }

                    $lastValue = $resoult['stanLicznika'];
                }



                ?>
        </table>
    </div>
</body>
</html>

<?php
    if($_POST['submit']){
        $stanLicznika = $_POST['licznikGlowny'];
        $dataOdczytu = $_POST['data'];


        if(($stanLicznika >= 0)&&($stanLicznika <= 99999)&&$dataOdczytu){
            $query = mysqli_query($db, "INSERT INTO glowny_licznik (stanLicznika, dataOdczytu) VALUES (ROUND($stanLicznika, 2), '$dataOdczytu')");
            // echo "Stan Licznika: ".$stanLicznika." Data odczytu: ".$dataOdczytu;
            header("Location: mainCounter.php");
            exit();
        }
    }

    if($_POST['submitEdit']){
        $id = $_POST['id'];
        $newValue = $_POST['newLicznikGlowny'];
        $newDate = $_POST['data'];

        if($_POST['newLicznikGlowny']){
            mysqli_query($db, "UPDATE glowny_licznik SET stanLicznika = '$newValue' WHERE id = $id");
            $_SESSION['i_action'] = "Pomyślnie zedytowano wartość licznika";
            header("Location: mainCounter.php");
            exit();
        }else if($newDate = $_POST['data']){
            mysqli_query($db, "UPDATE glowny_licznik SET dataOdczytu = '$newDate' WHERE id = $id");
            $_SESSION['i_action'] = "Pomyślnie zedytowano date licznika";
            header("Location: mainCounter.php");
            exit();
        }else if($_POST['newLicznikGlowny'] && $newDate = $_POST['data']){
            mysqli_query($db, "UPDATE glowny_licznik SET stanLicznika = '$newValue', dataOdczytu = '$newDate' WHERE id = $id");
            $_SESSION['i_action'] = "Pomyślnie zedytowano wartość i date licznika";
            header("Location: mainCounter.php");
            exit();
        }else{
            $_SESSION['e_action'] = "Edycja niepowiodła się!<br>Proszę wypełnić odpowiednie pola";
            header("Location: mainCounter.php");
            exit();
        }
    }

    if($_POST['deleteValue']){
        $id = $_POST['id'];

        if($id){
        $recordId = $_POST['recordId'];
        $queryStat = mysqli_query($db, "DELETE FROM glowny_licznik WHERE id = $id");
        $_SESSION['i_action'] = "Pomyślnie usunięto wpis";
        header("Location: mainCounter.php");
        exit();
        }else{
            $_SESSION['e_action'] = "Usuwanie niepowiodło się!<br>Proszę podać identyfikator";
            header("Location: mainCounter.php");
            exit();
        }
    }

    mysqli_close($db);
?>