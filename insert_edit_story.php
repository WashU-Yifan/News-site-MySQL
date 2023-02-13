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
            session_destroy();
            header("refresh:2; url=user_login.php");
            exit;
        }
        //filter input and check for SQL injection attacks
        
        $storyid =(int)$_SESSION['storyid'];
        $title =$mysqli->real_escape_string((string)$_POST['title']);
        $story =$mysqli->real_escape_string((string)$_POST['story']);
        $link = $mysqli->real_escape_string((string)$_POST['link']);

        if(!$title||!$story){
            echo "Tile and story must be filled. ";
            header("refresh:2; url=manage_post.php");
            exit;
        }

        //Update tje story table
        $stmt = $mysqli->prepare("UPDATE news set title=?, story=?, link=? where storyid=?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            header("refresh:2; url=manage_post.php");
            exit;
        }

        $stmt->bind_param('sssi', $title, $story, $link, $storyid);
        $stmt->execute();
        $stmt->close();


        echo "<p>You have successfully published the story. Returning to the previous page now. </p>";
        header("refresh:2; url=manage_post.php");
        
        
    ?>
</body>
</html>