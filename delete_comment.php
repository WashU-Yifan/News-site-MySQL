<!DOCTYPE html>
<html lang="en">

<head> <meta charset="UTF-8"> <title>News Site</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php
        require "database.php";
        session_start();
        
        //detect CSRF attack
        if(!hash_equals($_SESSION['token'],$_POST['token'])){
            echo "<p>Request forgery detected</p>";
            header("refresh:2; url=user_login.php");
            exit;
        }

        //filter input

        if(!$_POST['commentid']){
            echo "Comment must be filled. ";
            header("refresh:2; url=manage_post.php");
            exit;
        }
        $_SESSION['commentid']=(int)$_POST['commentid'];


      

        //update the comment
        $stmt = $mysqli->prepare("DELETE from comments where commentid=?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            header("refresh:2; url=manage_post.php");
            exit;
        }
        $stmt->bind_param('i',  $_SESSION['commentid']);
        
        $stmt->execute();
        
        $stmt->close();
        echo "<p>You have successfully delete the comment. Returning to the previous page now. </p>";
        header("refresh:2; url=manage_post.php");
        
        
    ?>
        </body>
</html>