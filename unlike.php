<!DOCTYPE html>
<html lang="en">

<head> <meta charset="UTF-8"> <title>News Site</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<?php 
    require "database.php";
    session_start();
    //Need to login to unlike a post
    if(!$_SESSION['user_id']){
        echo "<p>Must login first.</p>";
        header("refresh:2; url=user_login.php");
        exit;
    }

    $user_id=(int)$_SESSION['user_id'];
    $story_id=(int)$_SESSION['story_id'];


    $stmt = $mysqli->prepare(
        "DELETE from  likes where storyid=? and id= ?");
    if(!$stmt){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        header("refresh:2; url=show_comments.php");
        exit;
    }

    
    $stmt->bind_param('ii', $story_id, $user_id);
        
    $stmt->execute();
        
    $stmt->close();
    printf("<p> Operation succeed, returning to the previous page.</p>");
    header("refresh:2; url=show_comments.php");
    ?>
       </body>
</html> 

