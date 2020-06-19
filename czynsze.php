<html>
<?php
session_start();
$clientId[0] = $_SESSION['clientToken'];       //Data as array

//Connect to DB
require_once('db_ini.php');
$db = mysqli_connect($host, $db_user, $db_pass, $db);

?>
<head>
    <meta charset="utf-8">
        
    <!--- Favicon --->
    <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
    <link rel="manifest" href="/favicons/site.webmanifest">
    <link rel="mask-icon" href="/favicons/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="theme-color" content="#ffffff">
    
    <title>Panel Główny</title>
    <link rel="stylesheet" href="styl.css">
</head>

<body>
    <div class="top">
        <h2>Konto

            <?php
//            $clientId = $_SESSION['clientToken'];       //Data as array

            $query = mysqli_query($db, "SELECT id, imie, nazwisko FROM lokatorzy WHERE id = ".$clientId[0]."");
            $resoult = mysqli_fetch_array($query);

            echo $resoult['imie']." ".$resoult['nazwisko'];

            ?>
            <a href="logout.php"><button class="logout">Wyloguj się</button></a>
        </h2>
        <a href="profile.php"><input type="button" value="Wróć"></a>
        <a href="profileEdit.php"><input type="button" value="Zmień login/hasło"></a>
        <a href="mainCounter.php"><input type="button" value="Główny licznik"></a>
    </div>

<!---Client history--->
    <div class="main">
        <ul>
        <?php

            $hashedFile = sha1($clientId[0]); //Hash ID
            if(file_exists("czynsze/".$hashedFile."/")){
                $clientFiles = glob("czynsze/".$hashedFile."/*.*");

                krsort($clientFiles);
                foreach($clientFiles as $file)
                {
                    $publicStr = substr(basename($file), 0, -10);    //Public string
                    echo "<li><a href=".$file."  target='_blank'>$publicStr</a></li>";
                }
            }else{
                echo "<li>Brak czynszów</li>";
            }
        ?>
        </ul>
    </div>
</body>

</html>