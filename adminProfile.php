<html>
<?php
session_start();
$typKonta = $_SESSION['typKonta'];

    if($typKonta[0] != "1"){
    header("Location: logout.php");
}
    
//Connect to DB
require_once('db_ini.php');
$db = mysqli_connect($host, $user, $password, $db);
    
?>
<head>
    <meta charset="utf-8">
    <title></title>
    <link rel="stylesheet" href="styl.css">
</head>

<body>
    <div class="top">
        <h2>Konto Administrator
            <a href="logout.php"><button>Wyloguj się</button></a>
        </h2>
        <a href="profileEdit.php"><input type="button" value="Zmień login/hasło"></a>
        <a href="createUser.php"><input type="button" value="Kreator użytkownik"></a>
        <a href="generateReport.php"><input type="button" value="Kreator raportów"></a>
    </div>

    <div class="main-full">
       
        <table  >
            <tr>
                <td class="hide">ID</td>
                <th>Imię i Nazwisko</th>
                <th>Poprzedni Stan Licznika</th>
                <th>Najnowszy Stan Licznika</th>
                <th>Data Najnowszego Odczytu<br><small>Format daty rok-miesiąc-dzień</small></th>
                <th>Różnica</th>
            </tr>
            <tr>
                <td class="hide"></td>
                <td>
                    <select name="sortLokator" onchange="sortLokator(this.value)">
                        <option value="0">sortuj</option>
                        <?php
                        
                        $query = mysqli_query($db, "SELECT id, imie, nazwisko FROM lokatorzy");
                        while($resoult = mysqli_fetch_array($query)){
                            echo "<option value=".$resoult['id'].">".$resoult['imie']." ".$resoult['nazwisko']."</option>";
                        }
                        
                        ?>
                    </select>
                </td>
                <td>
                    <select name="sortStan" onchange="sortStan()">
                        <option value="0">sortuj</option>
                        <option value="1">sortuj od najniższej</option>
                        <option value="2">sortuj od najwyższej</option>
                    </select>
                </td>
                <td>
                    <select name="sortDate" onchange="sortDate()">
                        <option value="0">sortuj</option>
                        <option value="1">sortuj od najstarszych</option>
                        <option value="2">sortuj od najnowszych</option>
                    </select>
                </td>
                <td>
                    <select name="sortKoszt" onchange="sortKoszt()">
                        <option value="0">sortuj</option>
                        <option value="1">sortuj od najniższej</option>
                        <option value="2">sortuj od najwyższej</option>
                    </select>
                </td>
                <td></td>
            </tr>
            <?php
            
            $clientId = $_SESSION['clientToken'];       //Data as array

            $query = mysqli_query($db, "SELECT lokatorzy.id, lokatorzy.imie, lokatorzy.nazwisko, stanLicznika, dataOdczytu FROM dane JOIN lokatorzy ON  dane.idLokatora = lokatorzy.id ORDER BY concat(lokatorzy.id, lokatorzy.nazwisko) ASC, dataOdczytu ASC");
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
                    
                    
                echo "<tr><td class='hide' id='change'>".$resoult['id']."</td>
                <td>".$resoult['imie']." ".$resoult['nazwisko']."</td> 
                <td id='lastValue'>".$lastValue."</td>
                <td id='newValue'>".$resoult['stanLicznika']."m<sup>3</sup></td>
                <td>".$resoult['dataOdczytu']."</td>
                <td>".$difference."m<sup>3</sup></td></tr>";
                
                
                $lastValue = $resoult['stanLicznika'];
            }
            
            mysqli_close($db);
            ?>
        </table>
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
    
</script>
</html>