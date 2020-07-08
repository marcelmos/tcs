
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
    <link rel="stylesheet" href="loginStyle.css">
    <script src='https://kit.fontawesome.com/a076d05399.js'></script>
</head>
    <body>
        <div class="login-block">
            <h2>Logowanie do systemu</h2>
            <div class="form">
                <form action="login.php" method="post">
                    <span class="login-before"><input type="text" name="login" class="input-login" placeholder="Login">
                    </span><br>
                    <span class="password-before glyphicon"><input type="password" name="password" class="input-password" placeholder="Hasło">
                    </span><br>
                    <span class="btn-after"><input type="submit" class="btn-login" value="Zaloguj się">
                    </span>
                </form>
            </div>
            <p class="pass-forget">Zapomniałeś hasła? <br> Skontaktuj się z administratorem.</p>
        </div>
    </body>
</html>

