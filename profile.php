<html>
<?php
session_start();
$clientId = $_SESSION['clientToken'];       //Data as array  
        
//Connect to DB
include('db_ini.php');
$db = mysqli_connect($host, $user, $password, $db);
    
?>
<head>
    <meta charset="utf-8">
    <title>Panel Główny</title>
    <link rel="stylesheet" href="styl.css">
</head>

<body>
    <div class="top">
        <h2>Konto

            <?php
//            $clientId = $_SESSION['clientToken'];       //Data as array

            $query = mysqli_query($db, "SELECT imie, nazwisko FROM lokatorzy JOIN lokatorzy_logowanie ON lokatorzy.id = lokatorzy_logowanie.id_lokatorzy WHERE id_logowanie = '$clientId[0]'");
            $resoult = mysqli_fetch_array($query);

            echo $resoult['imie']." ".$resoult['nazwisko'];

            ?>
            <a href="logout.php"><button>Wyloguj się</button></a>
        </h2>
        <a href="profileEdit.php"><input type="button" value="Zmień login/hasło"></a>
    </div>

    <div class="main">
        <table>
            <tr>
                <th>Poprzedni Stan Licznika</th>
                <th>Najnowszy Stan Licznika</th>
                <th>Data Najnowszego Odczytu<br><small>Format daty rok-miesiąc-dzień</small></th>
                <th>Różnica</th>
            </tr>
            <tr>
                <td>
                    <select name="sortStan" onchange="sortStan()" disabled>
                        <option value="0">sortuj</option>
                        <option value="1">sortuj od najniższej</option>
                        <option value="2">sortuj od najwyższej</option>
                    </select>
                </td>
                <td>
                    <select name="sortDate" onchange="sortDate()" disabled>
                        <option value="0">sortuj</option>
                        <option value="1">sortuj od najstarszych</option>
                        <option value="2">sortuj od najnowszych</option>
                    </select>
                </td>
                <td>
                    <select name="sortKoszt" onchange="sortKoszt()" disabled>
                        <option value="0">sortuj</option>
                        <option value="1">sortuj od najniższej</option>
                        <option value="2">sortuj od najwyższej</option>
                    </select>
                </td>
                <td></td>
            </tr>
            <?php
                
//                $clientId = $_SESSION['clientToken'];       //Data as array

                $query = mysqli_query($db, "SELECT stanLicznika, dataOdczytu FROM dane WHERE idLokatora = '$clientId[0]' ORDER BY dataOdczytu ASC");
            
//                $resoult = mysqli_fetch_array($query);
                $lastValue = 0;
                $difference = 0;
                while($resoult = mysqli_fetch_array($query)){
                    if($lastValue > 0){
                        $difference = $resoult['stanLicznika'] - $lastValue;
                        $lastValue = $lastValue."m<sup>3</sup>";
                    }else{
                        $lastValue = "Brak Danych";
                    }
                    
                    echo "<tr>
                    <td>".$lastValue."</td>
                    <td>".$resoult['stanLicznika']."m<sup>3</sup></td>
                    <td>".$resoult['dataOdczytu']."</td>
                    <td>".$difference."m<sup>3</sup></td></tr>";
                    
                    $lastValue = $resoult['stanLicznika'];
                }
                
                mysqli_close($db);
                
                ?>
        </table>
    </div>
    <div class="main insert">
        <form action="sendData.php" method="post">
            Stan licznika:<br>
            <input type="number" step="0.01" name="licznik">m<sup>3</sup><br>
            Data odczytu:<br>
            <input type="date" name="data"><br>
            <br>
            <input type="submit" value="Wyślij">
        </form>
    </div>
</body>

</html>