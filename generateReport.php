<html>
<?php
session_start();
$typKonta[0] = $_SESSION['typKonta'];

if($typKonta[0] != "1"){
    header("Location: logout.php");         //Check if client token is is admin
}
?>
<head>
    <meta charset="utf-8">
    <title>Kreator raportów</title>
    <link rel="stylesheet" href="styl.css">
</head>

<body>
    <div class="top">
        <h2>Kreator raportów
            <a href="logout.php"><button class="logout">Wyloguj się</button></a>
        </h2>
        <a href="adminProfile.php"><input type="button" value="Wróć do Panelu Głównego"></a>
        <a href="profileEdit.php"><input type="button" value="Zmień login/hasło"></a>
        <a href="accountManager.php"><input type="button" value="Menedżer kont"></a>
        <a href="createUser.php"><input type="button" value="Kreator użytkownik"></a>
    </div>

    <div class="main-full">
        <form action="report.php" target="_blank" method="post">
           Wybierz miesiąc:<br>
            <select name="month">
                <option value="1">Styczeń</option>
                <option value="2">Luty</option>
                <option value="3">Marzec</option>
                <option value="4">Kwiecień</option>
                <option value="5">Maj</option>
                <option value="6">Czerwiec</option>
                <option value="7">Lipiec</option>
                <option value="8">Sierpień</option>
                <option value="9">Wrzesień</option>
                <option value="10">Październik</option>
                <option value="11">Listopad</option>
                <option value="12">Grudzień</option>
            </select><br>
            Wybierz rok: <br>
            <select name="year" id="years"></select><br>
            <br>
            <input type="submit" value="Wygeneruj raport">
        </form>
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
    var min = 2012,
        now = new Date(),
        max = now.getFullYear(),
        select = document.getElementById('years');

    for (var i = min; i<=max; i++){
        var opt = document.createElement('option');
        opt.value = i;
        opt.innerHTML = i;
        select.appendChild(opt);
    }

    select.value = new Date().getFullYear();
</script>

</html>