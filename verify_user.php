
<!DOCTYPE html>
<html lang="en">

<head> <meta charset="UTF-8"> <title>News Site</title>
    <link rel="stylesheet" href="style.css">
</head>
<body><?php
session_start();
require 'database.php';
//detect CSRF attack
if(!hash_equals($_SESSION['token'],$_POST['token'])){
    echo "<p>Request forgery detected</p>";
    session_destroy();
    header("refresh:2; url=user_login.php");
    exit;
}


// Use a prepared statement
$stmt = $mysqli->prepare("SELECT COUNT(*),id, hashed_password FROM users WHERE username = ?");


// Bind the parameter
//check for SQL injection& filter input
$user = $mysqli->real_escape_string((string)$_POST['username']);

if( !preg_match('/^[\w_\-]+$/', $user) ){
    echo "<p>Invalid username</p>";
    header("refresh:2; url=user_login.php");
    exit;
}
$_SESSION['username']=$user;

$stmt->bind_param('s', $user);

$stmt->execute();

// Bind the results
$stmt->bind_result($cnt, $user_id,$pwd_hash);
$stmt->fetch();
$stmt->close();
$pwd_guess = $_POST['password'];
// Compare the submitted password to the actual password hashe

if( $cnt==1 && password_verify($pwd_guess, $pwd_hash)){
	// Login succeeded!
	$_SESSION['user_id'] = $user_id;
	// Redirect to your target page
    echo "<p>Login successfully as ".htmlentities($user)."</p>";
    header("refresh:2; url=news_site.php");
} else{
	// Login failed; redirect back to the login screen
    echo "<p>Could not verify your login credential, try again.</p>";
    header("refresh:2; url=user_login.php");
}

?>
</body>
</html>