<!DOCTYPE html>
<html lang="en">

<head> <meta charset="UTF-8"> <title>News Site</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
        require "database.php";
        session_start();
        
        //filter input and check for SQL injection attacks
        //detect CSRF attack
        if(!hash_equals($_SESSION['token'],$_POST['token'])){
            echo "<p>Request forgery detected</p>";
            header("refresh:2; url=user_login.php");
            exit;
        }
        $user_id =(int)$_SESSION['user_id'];
        $title =$mysqli->real_escape_string((string)$_POST['title']);
        $story =$mysqli->real_escape_string((string)$_POST['story']);
        $link = $mysqli->real_escape_string((string)$_POST['link']);

        //If any of these information are not provided, we can not update the table
        if(!$title||!$story){
            echo "<p>Tile and story must be filled.</p> ";
            header("refresh:2; url=publish.php");
            exit;
        }

        if(!$user_id){
            echo "<p>Must login to perform this action. </p>";
            header("refresh:2; url=user_login.php");
            exit;
        }
            

        //update the new table
        $stmt = $mysqli->prepare("INSERT into news (title, story, link,id) values (?, ?, ?,?)");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            header("refresh:2; url=publish.php");
            exit;
        }


        $stmt->bind_param('sssi', $title, $story, $link,$user_id);
        
        $stmt->execute();
        
        $stmt->close();


        echo "<p>You have successfully published the story. Returning to main page now. </p>";
        header("refresh:2; url=news_site.php");
        
        
    ?>
    </body>
</html>