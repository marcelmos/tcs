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
        //$lastValue = 0;
        $sumValue = 0;
        $x = 0;

        $query = mysqli_query($db, "SELECT lokatorzy.imie, lokatorzy.nazwisko, idLokatora, stanLicznika, dataOdczytu FROM dane JOIN lokatorzy ON dane.idLokatora = lokatorzy.id WHERE dataOdczytu >= '$year-$month-01' AND dataOdczytu <= '$year-$month-31' ORDER BY dataOdczytu ASC");

//        $resoult = mysqli_fetch_array($query);

        while($resoult = mysqli_fetch_array($query)){
            $x++; //Auto incrementation
//            $lastValue = array(0); //Setting var

            if($month = "01"){
                $prevYearMonth = (($year-1)."-12");
//                $year -= 1;
            }else{
                $prevYearMonth = (($year-1)."-".($month-1));
            }

            $queryLast = mysqli_query($db, "SELECT stanLicznika FROM dane WHERE idLokatora = ".$resoult['idLokatora']." AND (dataOdczytu BETWEEN '$prevYearMonth-01' AND '$prevYearMonth-31') GROUP BY dataOdczytu ASC");

//            $queryLast = mysqli_query($db, "SELECT stanLicznika FROM dane JOIN lokatorzy ON  dane.idLokatora = lokatorzy.id WHERE lokatorzy.id = ".$resoult['id']." AND (dataOdczytu >= '$prevYearMonth-01' AND dataOdczytu <= '$prevYearMonth-31') GROUP BY dataOdczytu DESC");

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
        mysqli_close($db);
        ?>

    </tr>
</table>

</html>