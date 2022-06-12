<?php
require 'database.php';

// Use a prepared statement
$stmt = $mysqli->prepare("SELECT COUNT(*), hashed_password FROM users WHERE username = ?");


// Bind the parameter
//check for SQL injection& filter input
$user = $mysqli->real_escape_string((string)$_POST['username']);

if( !preg_match('/^[\w_\-]+$/', $user) ){
    echo "Invalid username";
    header("refresh:2; url=user_login.html");
    exit;

}


$stmt->bind_param('s', $user);

$stmt->execute();
/*
// Bind the results
$stmt->bind_result($cnt, $pwd_hash);
$stmt->fetch();

$pwd_guess = $_POST['password'];
// Compare the submitted password to the actual password hash
echo "here2";
/*
if($cnt == 1 && password_verify($pwd_guess, $pwd_hash)){
	// Login succeeded!
	$_SESSION['user_id'] = $user_id;
	// Redirect to your target page
    echo "Login successfully as ".$user;
    header("refresh:2; url=user_login.html");
} else{
	// Login failed; redirect back to the login screen
    echo "Could not verify your login credential, try again."
    header("refresh:2; url=user_login.html");
}
*/
?>