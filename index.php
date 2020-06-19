
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
        
    <!--- Favicon --->
    <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
    <link rel="manifest" href="/favicons/site.webmanifest">
    <link rel="mask-icon" href="/favicons/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="theme-color" content="#ffffff">
    
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
                <br>
                <input type="submit" value="Zaloguj się">
            </form>
        </div>
    </body>
</html>