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
        $lastValue = 0;
        $sumValue = 0;
        $x = 0;
        
        $query = mysqli_query($db, "SELECT lokatorzy.id, lokatorzy.imie, lokatorzy.nazwisko, stanLicznika, dataOdczytu FROM dane JOIN lokatorzy ON  dane.idLokatora = lokatorzy.id WHERE dataOdczytu >= '$year-$month-01' AND dataOdczytu <= '$year-$month-31' ORDER BY dataOdczytu ASC");
        
//        $resoult = mysqli_fetch_array($query);

        while($resoult = mysqli_fetch_array($query)){
            $x++;
            $queryLast = mysqli_query($db, "SELECT stanLicznika, dataOdczytu FROM dane JOIN lokatorzy ON  dane.idLokatora = lokatorzy.id WHERE lokatorzy.id = ".$resoult['id']." AND (dataOdczytu >= '$year-".($month-1)."-01' AND dataOdczytu <= '$year-".($month-1)."-31') GROUP BY dataOdczytu DESC");
        
            $lastValue = mysqli_fetch_array($queryLast);
            
//            if($lastValue['stanLicznika'] > 0){
//                $difference = ($resoult['stanLicznika'] - $lastValue['stanLicznika']);
//                $lastValue = $lastValue['stanLicznika']."m<sup>3</sup>";
//            }else{
//                $lastValue = "Brak Danych";
//            }
            
            echo "<tr><td>".$resoult['imie']." ".$resoult['nazwisko']."</td> 
                <td>".$lastValue['stanLicznika']."</td>
                <td>".$resoult['stanLicznika']."m<sup>3</sup></td>
                <td>".$resoult['dataOdczytu']."</td>
                <td>".($resoult['stanLicznika'] - $lastValue['stanLicznika'])."m<sup>3</sup></td></tr>";
            
//            echo "<tr><td>".$resoult['imie']." ".$resoult['nazwisko']."</td> 
//                <td>".$lastValue['stanLicznika']."</td>
//                <td>".$resoult['stanLicznika']."m<sup>3</sup></td>
//                <td>".$resoult['dataOdczytu']."</td>
//                <td>".$difference."m<sup>3</sup></td></tr>";
            
            $sumValue = ($resoult['stanLicznika']-$lastValue['stanLicznika']);
        }
        echo "<tr><td colspan='3'></td> <th>Średnia: </th> <td>".($sumValue/$x)."m<sup>3</sup></td></tr>";
        mysqli_close($db);
        ?>
        
    </tr>
</table>

</html>