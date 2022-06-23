<!DOCTYPE html>
<html lang="en">

<head> <meta charset="UTF-8"> <title>News Site</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    
    <h1><strong>Login Page</strong></h1>

    <?php
        session_start();
        //CSRF check
        $_SESSION['token']=bin2hex(random_bytes(32));?>
    <form action="verify_user.php" method="POST">
    
        <h2>
        <!-- username--> 
        username<input type="text" name="username"  /><br>
        password<input type="password" name="password"  /><br>
        <input type="hidden" name ="token" value="<?php session_start(); echo $_SESSION['token'];?>"/>

        <input type="submit" value="login" />
        <input type="reset" />
</h2>
    </form>

    <form action="news_site.php" method="POST">
        <h2><input type="submit" value="continue as guest" /></h2>

    </form>

    <form action="register.html" method="POST">
       <h2> <input type="submit" value="register" /></h2>
    </form>

</body>
</html>