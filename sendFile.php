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

    <!--- Favicon --->
    <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
    <link rel="manifest" href="/favicons/site.webmanifest">
    <link rel="mask-icon" href="/favicons/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="theme-color" content="#ffffff">

    <title>Zarządzaj czynszami</title>
    <link rel="stylesheet" href="styl.css">
</head>

<body>
    <div class="top">
        <h2>Zarządzaj czynszami
            <a href="logout.php"><button class="logout">Wyloguj się</button></a>
        </h2>
        <a href="adminProfile.php"><input type="button" value="Wróć do Panelu Głównego"></a>
        <a href="profileEdit.php"><input type="button" value="Zmień login/hasło"></a>
        <a href="createUser.php"><input type="button" value="Kreator użytkownik"></a>
        <a href="accountManager.php"><input type="button" value="Menedżer kont"></a>
        <a href="mainCounter.php"><input type="button" value="Główny licznik"></a>
        <a href="generateReport.php"><input type="button" value="Kreator raportów"></a>
    </div>

    <div class="form-block main-full">
        <form enctype="multipart/form-data" method="post">
            Wybierz lokatora:<br>
            <select name="lokator">
                <option value="0">Brak</option>
                <?php
                $query = mysqli_query($db, "SELECT id, nrLokalu, imie, nazwisko FROM lokatorzy WHERE typKonta_id = 2 ORDER BY nrLokalu ASC");
                while($resoult = mysqli_fetch_array($query)){
                    if($resoult['nrLokalu'] < 10){
                        $nrLokalu = "K0".$resoult['nrLokalu'];
                    }else{
                        $nrLokalu = "K".$resoult['nrLokalu'];
                    }

                    echo "<option value=".$resoult['id'].">[$nrLokalu] ".$resoult['imie']." ".$resoult['nazwisko']."</option>";
                }
                ?>
            </select><br>
            Wybierz plik:<br>
            <input type="file" name="uploadedFile"><br>
            <br>
            <input type="submit" name="send" value="Wyślij plik">
        </form>
        <br>
        <div class="error">
            <p>Wysyłane pliki nie powinny zawierać spacji.<br>Optymalna nazwa pliku: "rrrr-mm-dd-[inne dane].pdf"</p>
        </div>
        <div style="margin-top: 25px">
            <h4>Przeglądaj pliki</h4>
            <form method="post">
                Wybierz lokatora:<br>
                <select name="selectUser">
                    <option value="0">Brak</option>
                    <?php
                    $query = mysqli_query($db, "SELECT id, nrLokalu, imie, nazwisko FROM lokatorzy WHERE typKonta_id = 2 ORDER BY nrLokalu ASC");
                    while($resoult = mysqli_fetch_array($query)){
                        if($resoult['nrLokalu'] < 10){
                            $nrLokalu = "K0".$resoult['nrLokalu'];
                        }else{
                            $nrLokalu = "K".$resoult['nrLokalu'];
                        }

                        echo "<option value=".$resoult['id'].">[$nrLokalu] ".$resoult['imie']." ".$resoult['nazwisko']."</option>";
                    }
                    ?>
            </select><br>
            <br>
                <input type="submit" name="delFileOf" value="Wybierz lokatora">
            </form>
            <br>
            <form method='post'>
                <ul>
                <?php
                    if($_POST['delFileOf']){
                        $selected = $_POST['selectUser'];

                        $hashedFile = sha1($selected); //Hash ID
                        if(file_exists("czynsze/".$hashedFile."/")){
                            $clientFiles = glob("czynsze/".$hashedFile."/*.*");

                            krsort($clientFiles);
                            foreach($clientFiles as $file){
                                echo "<li><a href=".$file."  target='_blank'>".basename($file)."</a> <button type='submit' name='deleteThis' value='$file'>Usuń plik</button></li>";
                            }
                        }else{
                            echo "<li>Brak czynszów</li>";
                        }
                    }
                ?>
                </ul>
            </form>
        </div>
    </div>

    <div class="data-sent main-full">
            <h3>Lista przesłanych plików</h3>
            <br>
            <div class="file-list">
            <?php

                $query = mysqli_query($db, "SELECT id, nrLokalu, imie, nazwisko FROM lokatorzy WHERE typKonta_id = 2 ORDER BY nrLokalu ASC");
                while($resoult = mysqli_fetch_array($query)){
                    if($resoult['nrLokalu'] < 10){
                        $nrLokalu = "K0".$resoult['nrLokalu'];
                    }else{
                        $nrLokalu = "K".$resoult['nrLokalu'];
                    }

                    echo "<p><u>[$nrLokalu] ".$resoult['imie']." ".$resoult['nazwisko']."</u></p>";
                    echo "<ul>";

                    //Show files
                    $selected = $resoult['id'];

                    $hashedFile = sha1($selected); //Hash ID
                    if(file_exists("czynsze/".$hashedFile."/")){
                        $clientFiles = glob("czynsze/".$hashedFile."/*.*");

                        krsort($clientFiles);
                        foreach($clientFiles as $file){
                            echo "<li><a href=".$file."  target='_blank'>".basename($file)."</a></li>";
                        }
                    }else{
                        echo "<li>Brak czynszów</li>";
                    }

                    echo "</ul><br>";
                }
            ?>
            </div>
    </div>
</body>
<?php

    if($_POST['send']){

        $lokator = $_POST['lokator'];
        $hashedLokator = sha1($lokator);    //Hashowanie ID lokatora
        $fileTmp = $_FILES['uploadedFile']['tmp_name'];
        $fileName = $_FILES['uploadedFile']['name'];

        if(($lokator>0)&&(!empty($_FILES['uploadedFile']))){

            //Random string generator
            $random_string_length = 5;
            $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
            $string = '';
            $max = strlen($characters) - 1;
            for ($i = 0; $i < $random_string_length; $i++) {
                $string .= $characters[mt_rand(0, $max)];
            }
            $addString = "-".$string.".pdf";  //Ready string

            $fileName = str_replace(' ', '-', $fileName);   //Remove space
            $newFileName = substr($fileName, 0, -4);    //Remove ".pdf" from string

            if(file_exists("czynsze")){

                if(file_exists("czynsze/$hashedLokator")){
                    move_uploaded_file($fileTmp, "czynsze/$hashedLokator/$fileName");
                    rename("czynsze/$hashedLokator/$fileName", "czynsze/$hashedLokator/".$newFileName.$addString);
                    echo "Pomyślnie przesłano plik $fileName.";
                }else{
                    mkdir("czynsze/$hashedLokator");
                    move_uploaded_file($fileTmp, "czynsze/$hashedLokator/$fileName");
                    rename("czynsze/$hashedLokator/$fileName", "czynsze/$hashedLokator/".$newFileName.$addString);
                    echo "Utworzono folder dla lokatora o ID $lokator, oraz plik $fileName został zainportowany.";
                }
            }else{
                mkdir("czynsze");
                mkdir("czynsze/$hashedLokator");
                move_uploaded_file($fileTmp, "czynsze/$hashedLokator/$fileName");
                rename("czynsze/$hashedLokator/$fileName", "czynsze/$hashedLokator/".$newFileName.$addString);
                echo "Utworzono folder 'czynsze', katalog lokatora o ID $lokator, oraz plik $fileName został zainportowany.";
            }
        }else{
            echo "Nie wybrano lokatora lub pliku.";
        }
    }

    if($_POST['deleteThis']){
        $deleteFile = $_POST['deleteThis'];

        if (!unlink($deleteFile)) {
            echo ("Nie udało się usunąć pliku.");
        }
        else {
            echo ("Plik został pomyślnie usunięty!");
        }
    }

    mysqli_close($db);
?>
</html>