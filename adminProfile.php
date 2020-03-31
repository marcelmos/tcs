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
        <h2>Konto Administrator
            <a href="logout.php"><button class="logout">Wyloguj się</button></a>
        </h2>
        <a href="profileEdit.php"><input type="button" value="Zmień login/hasło"></a>
        <a href="createUser.php"><input type="button" value="Kreator użytkownik"></a>
        <a href="accountManager.php"><input type="button" value="Menedżer kont"></a>
        <a href="mainCounter.php"><input type="button" value="Główny licznik"></a>
        <a href="generateReport.php"><input type="button" value="Kreator raportów"></a>
        <a href="sendFile.php"><input type="button" value="Prześlij czynsz"></a>
        <a href="changelog.txt" target="_blank"><input type="button" value="[v1.7b] Lista zmian"></a>
    </div>

    <div class="main-full">

        <h3>Historia odczytów</h3>
        <div class="topMain" style='display: flex; justify-content: space-between'>

            <div class="filter">
                <p>Filtruj dane według:
                    <form method="post">
                        Lokatorzy:
                        <select name="filterLokator">
                            <option value="0">Brak</option>
                            <?php
                            $query = mysqli_query($db, "SELECT id, imie, nazwisko FROM lokatorzy WHERE typKonta_id = 2");
                            while($resoult = mysqli_fetch_array($query)){
                                echo "<option value=".$resoult['id'].">".$resoult['imie']." ".$resoult['nazwisko']."</option>";
                            }
                            ?>
                        </select>&nbsp;&nbsp;
                        Data:
                        <select name="filterDate">
                            <option value="0">Brak</option>
                            <option value=""></option>
                            <option value=""></option>
                        </select>
                        <input type="submit" name="filterSubmit" value="Filtruj dane">
                    </form>
                </p>
            </div>

            <?php
                if(isset($_SESSION['i_action'])){
                    echo "<div class='correct' style='text-align: center;'>".$_SESSION['i_action']."</div>";
                    unset($_SESSION['i_action']);
                }
                if(isset($_SESSION['e_insert'])){
                    echo "<div class='error' style='text-align: center;'>".$_SESSION['e_insert']."</div>";
                    unset($_SESSION['e_insert']);
                }
            ?>

            <div>
                <h3>Wstaw nowy wpis</h3>
                <form action="sendDataAdmin.php" method="post" style='display: flex; justify-content: space-between'>
                    <label>
                        Lokator:<br>
                        <select name="lokatorInsert">
                            <option value="0">Brak</option>
                            <?php
                                $query = mysqli_query($db, "SELECT id, imie, nazwisko FROM lokatorzy WHERE typKonta_id = 2");
                                while($resoult = mysqli_fetch_array($query)){
                                    echo "<option value=".$resoult['id'].">".$resoult['imie']." ".$resoult['nazwisko']."</option>";
                                }
                            ?>
                        </select>
                    </label>&nbsp;&nbsp;

                    <label>
                        Stan licznika:<br>
                        <input type="number" step="0.01" name="licznikInsert" placeholder="1234.56">m<sup>3</sup>
                    </label>&nbsp;&nbsp;

                    <label>
                        Data odczytu:<br>
                        <input type="date" name="dateInsert">
                    </label>&nbsp;&nbsp;
                    <br>
                    <input type="submit" name="insertNewData" value="Wstaw odczyt">
                </form>
            </div>
        </div>

        <!-- <form action="dataEdit.php" method="post"> -->
            <table>
                <tr>
                    <th>Imię i Nazwisko</th>
                    <th>Poprzedni Stan Licznika</th>
                    <th>Najnowszy Stan Licznika</th>
                    <th>Data Najnowszego Odczytu<br></th>
                    <th>Różnica</th>
                    <th>Akcje</th>
                </tr>
                <?php
                    $clientId = $_SESSION['clientToken'];       //Data as array

                    if($_POST['filterSubmit'] && (($_POST['filterLokator'] != "0")||($_POST['filterDate'] != "0"))){

                        $filterLokator = $_POST['filterLokator'];
                        $query = mysqli_query($db, "SELECT lokatorzy.id, lokatorzy.imie, lokatorzy.nazwisko, ROUND(stanLicznika, 2) AS stanLicznika, dataOdczytu, dane.id FROM dane JOIN lokatorzy ON  dane.idLokatora = lokatorzy.id WHERE idLokatora = $filterLokator ORDER BY concat(lokatorzy.id, lokatorzy.nazwisko) ASC, dataOdczytu ASC");
            //                $resoult = mysqli_fetch_array($query);

                        $idLokatora = 0;
                        while($resoult = mysqli_fetch_array($query)){

                            if ($idLokatora!=$resoult[0])
                            {
                                $lastValue = 0;
                                $difference = 0;
                                $idLokatora=$resoult[0];
                            }
                            if($lastValue > 0){
                                $difference = $resoult['stanLicznika'] - $lastValue;
                                $lastValue = $lastValue."m<sup>3</sup>";
                            }else{
                                $lastValue = "Brak Danych";
                            }

                            echo "<form action='dataEdit.php' method='post'><tr>
                            <td>".$resoult['imie']." ".$resoult['nazwisko']."</td>
                            <td id='lastValue'>".$lastValue."</td>
                            <td id='newValue'>".$resoult['stanLicznika']."m<sup>3</sup></td>
                            <td>".$resoult['dataOdczytu']."</td>
                            <td>".number_format($difference, 2)."m<sup>3</sup></td>
                            <td><button type='submit' class='btnAction' name='btnAction' value='".$resoult['id']."'>Edytuj/Usuń</button></td>
                            </tr></form>";

                            $lastValue = $resoult['stanLicznika'];
                        }
                    }
                    else{
                        $query = mysqli_query($db, "SELECT lokatorzy.id, lokatorzy.imie, lokatorzy.nazwisko, ROUND(stanLicznika, 2) AS stanLicznika, dataOdczytu, dane.id FROM dane JOIN lokatorzy ON  dane.idLokatora = lokatorzy.id ORDER BY concat(lokatorzy.id, lokatorzy.nazwisko) ASC, dataOdczytu ASC");
            //                $resoult = mysqli_fetch_array($query);

                        $idLokatora = 0;
                        while($resoult = mysqli_fetch_array($query)){

                            if ($idLokatora!=$resoult[0])
                            {
                                $lastValue = 0;
                                $difference = 0;
                                $idLokatora=$resoult[0];
                            }
                            if($lastValue > 0){
                                $difference = $resoult['stanLicznika'] - $lastValue;
                                $lastValue = $lastValue."m<sup>3</sup>";
                            }else{
                                $lastValue = "Brak Danych";
                            }

                            echo "<form action='dataEdit.php' method='post'><tr>
                            <td class='hide' id='change'>".$resoult['id']."</td>
                            <td>".$resoult['imie']." ".$resoult['nazwisko']."</td>
                            <td id='lastValue'>".$lastValue."</td>
                            <td id='newValue'>".$resoult['stanLicznika']."m<sup>3</sup></td>
                            <td>".$resoult['dataOdczytu']."</td>
                            <td>".number_format($difference, 2)."m<sup>3</sup></td>
                            <td><button type='submit' class='btnAction' name='btnAction' value='".$resoult['id']."'>Edytuj/Usuń</button></td>
                            </tr></form>";

                            $lastValue = $resoult['stanLicznika'];
                        }
                    }
                ?>
            </table>
        <!-- </form> -->
    </div>

</body>
<?php
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
</html>