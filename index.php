
<?php

session_start();

if((isSet($_SESSION['clientToken']))&&($_SESSION['typKonta']=="1")){
   header('Location: adminProfile.php');
   exit();
}
if((isSet($_SESSION['clientToken']))&&($_SESSION['typKonta']=="2")){
    header('Location: profile.php');
    exit();
 }

?>



<html>
<head>
    <meta charset="utf-8">
    <title>Zaloguj się do systemu</title>
    <link rel="stylesheet" href="styl.css">
</head>
    <body>
        <div class="login-window">
            <form action="login.php" method="post">
                <p>Login:</p>
                <input type="text" name="login"><br>
                <p>Hasło:</p>
                <input type="password" name="password"><br>
                <br><br><br>
                <input type="submit" value="Zaloguj się">
            </form>
        </div>
    </body>
</html>