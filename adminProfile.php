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
        <a href="changelog.txt" target="_blank"><input type="button" value="[v1.6b] Lista zmian"></a>
    </div>

    <div class="main-full">

       <!-- <h3>Najnowsze odczyty</h3>
       <table>
            <tr>
                <td class="hide">ID</td>
                <th>Imię i Nazwisko</th>
                <th>Poprzedni Stan Licznika</th>
                <th>Najnowszy Stan Licznika</th>
                <th>Data Najnowszego Odczytu<br><small>Format daty rok-miesiąc-dzień</small></th>
                <th>Różnica</th>
            </tr>

            <?php
            /*

            $dateNow = date("Y-m");
            $difference = 0;
            //$lastValue = 0;
            $sumValue = 0;
            $x = 0;

            $queryHist = mysqli_query($db, "SELECT lokatorzy.imie, lokatorzy.nazwisko, idLokatora, stanLicznika, dataOdczytu FROM dane JOIN lokatorzy ON dane.idLokatora = lokatorzy.id WHERE dataOdczytu >= '$dateNow-01' AND dataOdczytu <= '$dateNow-31' ORDER BY dataOdczytu DESC");
    //        $resoult = mysqli_fetch_array($query);

            while($resoult = mysqli_fetch_array($queryHist)){
                $x++; //Auto incrementation
    //            $lastValue = array(0); //Setting var

                //Check if month is 01
                if(date("m") == "01"){
                    $prevYearMonth = ((date("Y")-1)."-12");
                }else{
                    $prevYearMonth = (date("Y")."-".(date("m")-1));
                }

                $queryLastHist = mysqli_query($db, "SELECT stanLicznika FROM dane WHERE idLokatora = ".$resoult['idLokatora']." AND (dataOdczytu BETWEEN '$prevYearMonth-01' AND '$prevYearMonth-31') GROUP BY dataOdczytu DESC");

    //            $queryLast = mysqli_query($db, "SELECT stanLicznika FROM dane JOIN lokatorzy ON  dane.idLokatora = lokatorzy.id WHERE lokatorzy.id = ".$resoult['id']." AND (dataOdczytu >= '$prevYearMonth-01' AND dataOdczytu <= '$prevYearMonth-31') GROUP BY dataOdczytu DESC");

                $lastValue = mysqli_fetch_array($queryLastHist);

                if($lastValue > 0){
                    $lastValueInt = $lastValue[0];
                    $lastValueShow = $lastValueInt."m<sup>3</sup>";
                    $difference = ($resoult['stanLicznika'] - $lastValue[0]);
                }else{
                    $lastValueShow = "Brak Danych";
                    $difference = 0;
                    $lastValueInt = 0;
                }

    //            echo "<tr><td>".$resoult['imie']." ".$resoult['nazwisko']."</td>
    //                <td>".$lastValue['stanLicznika']."</td>
    //                <td>".$resoult['stanLicznika']."m<sup>3</sup></td>
    //                <td>".$resoult['dataOdczytu']."</td>
    //                <td>".($resoult['stanLicznika'] - $lastValue['stanLicznika'])."m<sup>3</sup></td></tr>";

                echo "<tr><td>".$resoult['imie']." ".$resoult['nazwisko']."</td>
                    <td>".$lastValueShow."</td>
                    <td>".$resoult['stanLicznika']."m<sup>3</sup></td>
                    <td>".$resoult['dataOdczytu']."</td>
                    <td>".$difference."m<sup>3</sup></td></tr>";

                $sumValue = ($resoult['stanLicznika']-$lastValueInt);
            }
            echo "<tr><td colspan='3'></td> <th>Średnia: </th> <td>".($sumValue/$x)."m<sup>3</sup></td></tr>";

            */
           ?>
        </table> -->
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
                        <input type="number" step="0.01" name="licznikInsert" placeholder="1234,56">m<sup>3</sup>
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

                    // if($_POST['deleteItem']){
                    //     $deleteItem = $_POST['deleteItem'];
                    //     mysqli_query($db, "DELETE FROM dane WHERE id = $deleteItem");
                    //     $_SESSION['i_action'] = "Pomyślnie usunięto wpis";
                    //     header("Refresh:0");
                    //     exit();
                    // }
                ?>
            </table>
        <!-- </form> -->
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