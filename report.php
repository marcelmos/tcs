<html>
<?php
session_start();
$typKonta = $_SESSION['typKonta'];

if($typKonta[0] != "1"){
    header("Location: logout.php");
}

//Connect to DB
require_once('db_ini.php');
$db = mysqli_connect($host, $db_user, $db_pass, $db);

?>
<table border="5">
    <tr>
        <th colspan="5">Raport stanu liczników</th>
    </tr>
    <tr>
        <th colspan="3"></th>
        <th>Data wykonanego raportu:</th>
        <td><?php echo date("Y/m/d");?></td>
    </tr>
    <tr>
        <th>Imię i nazwisko lokatora</th>
        <th>Stan poprzedniego odczytu</th>
        <th>Stan najnowszego odczytu</th>
        <th>Data najnowszego odczytu</th>
        <th>Różnica</th>
    </tr>
    <tr>

        <?php

        $year = $_POST['year'];
        $month = $_POST['month'];
        $difference = 0;
        $differenceCounter = 0;
        $sumValue = 0;

        $query = mysqli_query($db, "SELECT lokatorzy.imie, lokatorzy.nazwisko, idLokatora, ROUND(stanLicznika, 2) AS stanLicznika, dataOdczytu FROM dane JOIN lokatorzy ON dane.idLokatora = lokatorzy.id WHERE  MONTH(dataOdczytu) = '$month'  and YEAR(dataOdczytu) = '$year' ORDER BY idLokatora ASC");

        while($resoult = mysqli_fetch_array($query)){

            if($month == "01"){
                $prevMonth = 12;
                $prevYear = ($year-1);
            }
            if($month != "01"){
                $prevMonth = ($month-1);
                $prevYear = $year;
            }

            $queryLast = mysqli_query($db, "SELECT ROUND(stanLicznika, 2) AS stanLicznika FROM dane WHERE idLokatora = '".$resoult['idLokatora']."' AND MONTH(dataOdczytu) = '$prevMonth' and YEAR(dataOdczytu) = '$prevYear' GROUP BY dataOdczytu ASC");

            $lastValue = mysqli_fetch_array($queryLast);

            if($lastValue > 0){
                $lastValueInt = $lastValue[0];
                $lastValueShow = $lastValueInt."m<sup>3</sup>";
                $difference = ($resoult['stanLicznika'] - $lastValue[0]);
            }else{
                $lastValueShow = "Brak Danych";
                $difference = 0;
                $lastValueInt = 0;
            }

            echo "<tr><td>".$resoult['imie']." ".$resoult['nazwisko']."</td>
                <td>".$lastValueShow."</td>
                <td>".$resoult['stanLicznika']."m<sup>3</sup></td>
                <td>".$resoult['dataOdczytu']."</td>
                <td>".number_format($difference, 2)."m<sup>3</sup></td></tr>";

            $sumValue += $difference;
        }



        //Main counter row
        $queryMainCounter = mysqli_query($db, "SELECT ROUND(stanLicznika, 2) AS stanLicznika, dataOdczytu FROM glowny_licznik WHERE MONTH(dataOdczytu) = '$month'  and YEAR(dataOdczytu) = '$year'");

        while($counterStr = mysqli_fetch_array($queryMainCounter)){

            if($month == "01"){
                $prevMonth = 12;
                $prevYear = ($year-1);
            }
            if($month != "01"){
                $prevMonth = ($month-1);
                $prevYear = $year;
            }

            $queryMainCounterLast = mysqli_query($db, "SELECT ROUND(stanLicznika, 2) AS stanLicznika, dataOdczytu FROM glowny_licznik WHERE MONTH(dataOdczytu) = '$prevMonth'  and YEAR(dataOdczytu) = '$prevYear'");


            $lastValue = mysqli_fetch_array($queryMainCounterLast);

            if($lastValue > 0){
                $lastValueInt = $lastValue[0];
                $lastValueShow = $lastValueInt."m<sup>3</sup>";
                $differenceCounter = ($counterStr['stanLicznika'] - $lastValue[0]);
            }else{
                $lastValueShow = "Brak Danych";
                $differenceCounter = 0;
                $lastValueInt = 0;
            }

            echo "<tr><th>Główny licznik</th>
            <td>".$lastValueShow."</td>
            <td>".$counterStr['stanLicznika']."m<sup>3</sup></td>
            <td>".$counterStr['dataOdczytu']."</td>
            <td>".number_format($differenceCounter, 2)."m<sup>3</sup></td></tr>";
        }
        echo "<tr><td colspan='3'></td> <th>Suma różnicy z lokatorów: </th> <td>".number_format($sumValue, 2)."m<sup>3</sup></td></tr>";

        $glownyOdLokatorow = $differenceCounter - $sumValue;
        echo "<tr><td colspan='2'></td> <th colspan='2'>Różnica głównego licznika od sumy różnicy lokatorów: </th> <td>".number_format($glownyOdLokatorow, 2)."m<sup>3</sup></td></tr>";
        mysqli_close($db);
        ?>

    </tr>
</table>
</html>