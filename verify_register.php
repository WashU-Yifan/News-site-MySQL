<!DOCTYPE html>
<html lang="en">

<head> <meta charset="UTF-8"> <title>News Site</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
require 'database.php';

// Use a prepared statement
$stmt = $mysqli->prepare("SELECT COUNT(*)FROM users WHERE username=?");

// Bind the parameter
//check for SQL injection& filter input
$user = $mysqli->real_escape_string((string)$_POST['username']);

if( !preg_match('/^[\w_\-]+$/', $user) ){
    echo "Invalid username";
    header("refresh:2; url=register.html");
    exit;

}
if($_POST['password']!=$_POST['repeat_password']){
    echo "password does not match";
    header("refresh:2; url=register.html");
    exit;
}
$stmt->bind_param('s', $user);
$stmt->execute();

// Bind the results
$stmt->bind_result($cnt);
$stmt->fetch();
$stmt->close();
// Compare the submitted password to the actual password hash

if($cnt == 1 ){
	// username already exist
    echo "username already exist ".$user;
    header("refresh:2; url=register.html");
    exit;
} else{
    
	// register success
    $password_hash= password_hash($_POST['password'], PASSWORD_BCRYPT);
    echo $password_hash;
    $stmt = $mysqli->prepare("INSERT INTO users (username, hashed_password) VALUES (?, ?)");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }

    $stmt->bind_param('ss', $user,$password_hash);

    $stmt->execute();

    $stmt->close();

    echo "\nregister success!";
    header("refresh:2; url=user_login.php");
    exit;
}
?>
        </body>
</html>