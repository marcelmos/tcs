
<?php

//session_start();
//
//if((isSet($_SESSION['clientToken']))&&($_SESSION['clientToken']==true)){
//    if()
//    header('Location: profile.php');
//    exit();
//}

?>



<html>
<head>
    <meta charset="utf-8">
    <title>Zaloguj się</title>
    <link rel="stylesheet" href="styl.css">
</head>
    <body>
        <div class="login-window">
            <form action="login.php" method="post">
                Login:<br>
                <input type="text" name="login"><br>
                Hasło:<br>
                <input type="password" name="password"><br>
                <br>
                <input type="submit" value="Zaloguj się">
            </form>
        </div>
    </body>
</html>