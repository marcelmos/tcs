<html>
<?php
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
    
    <!--- Favicon --->
    <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
    <link rel="manifest" href="/favicons/site.webmanifest">
    <link rel="mask-icon" href="/favicons/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="theme-color" content="#ffffff">
    
    <title>Menedżer Kont</title>
    <link rel="stylesheet" href="styl.css">
    <!--- Icons --->
    <!-- <script src="https://kit.fontawesome.com/cbb96ef56b.js" crossorigin="anonymous"></script> -->
</head>

<body>
    <div class="top">
        <h2>Meneżer Kont
            <a href="logout.php"><button class="logout">Wyloguj się</button></a>
        </h2>
        <a href="adminProfile.php"><input type="button" value="Wróć do Panelu Głównego"></a>
        <a href="profileEdit.php"><input type="button" value="Zmień login/hasło"></a>
        <a href="createUser.php"><input type="button" value="Kreator użytkownik"></a>
        <a href="mainCounter.php"><input type="button" value="Główny licznik"></a>
        <a href="generateReport.php"><input type="button" value="Kreator raportów"></a>
        <a href="sendFile.php"><input type="button" value="Zarządzaj czynszami"></a>
    </div>

    <div class="main-full">

        <form method="post">
            Podaj id konta: <br>
            <input type="number" name="accountId"><br>
            Nowe hasło: <br>
            <input type="text" name="newPass"><br>
            <!-- <i>
                <?php
                    // function randomPassword() {
                    //     $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
                    //     $pass = array(); //remember to declare $pass as an array
                    //     $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
                    //     for ($i = 0; $i < 8; $i++) {
                    //         $n = rand(0, $alphaLength);
                    //         $pass[] = $alphabet[$n];
                    //     }
                    //     return implode($pass); //turn the array into a string
                    // }
                    // // $_SESSION["newPassword"] = randomPassword();
                    // echo randomPassword();
                ?>
            </i> -->
            <br>
            <input type="submit" value="Wykonaj">
        </form>

        <table>
            <tr>
                <th>ID konta</th>
                <th>Imię i nazwisko</th>
                <th>Login</th>
            </tr>
            <?php

            $queryDatas = mysqli_query($db, "SELECT id, imie, nazwisko, login FROM lokatorzy WHERE typKonta_id <> 1 GROUP BY id ASC");
            while($resoult = mysqli_fetch_array($queryDatas)){
                echo "<tr>
                    <td>".$resoult['id']."</td>
                    <td>".$resoult['imie']." ".$resoult['nazwisko']."</td>
                    <td>".$resoult['login']."</td>
                </tr>";
            }
            ?>
        </table>

    </div>
</body>

<?php
    if(isset($_POST['accountId'])){
        $accountId = $_POST['accountId'];
        // $newPass = $_SESSION["newPassword"];
        // $newPass = randomPassword();
        $newPass = $_POST['newPass'];
        // unset($_SESSION["newPassword"]);

        $newPass_hash = password_hash($newPass, PASSWORD_DEFAULT);

        if(isset($_POST['newPass'])){
            mysqli_query($db, "UPDATE lokatorzy SET haslo = '$newPass_hash' WHERE lokatorzy.id = $accountId");
        }
    }
?>
</html>