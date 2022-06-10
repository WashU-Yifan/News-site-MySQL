<?php
    require 'database.php';
    // Use a prepared statement
    $stmt = $mysqli->prepare("SELECT COUNT(*) FROM users WHERE username=?");

    // Bind the parameter
    //check for SQL injection & filter input
    $user = $mysqli->real_escape_string((string)$_POST['username']);

    $stmt->bind_param('s', $user);
    $stmt->execute();

    // Bind the results
    $stmt->bind_result($cnt);
    $stmt->fetch();
    
    //check for duplicate
    if($cnt>=1){
        echo "Username already exist, try again."
        header("refresh:2; url=register.html");
    }
?>