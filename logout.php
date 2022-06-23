<!DOCTYPE html>
<html lang="en">

<head> <meta charset="UTF-8"> <title>News Site</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
    session_start();
    session_destroy();
   
    echo "<p>Returning to the login page now </p>";
    header("refresh:2; url=user_login.php");
    ?>
        </body>
</html>