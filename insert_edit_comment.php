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

        $user_id =(int)$_SESSION['user_id'];
        $commentid =(int)$_SESSION['commentid'];
        $comment =$mysqli->real_escape_string((string)$_POST['comment']);

        //If either information is not provided, we can't update the comment
        if(!$commentid){
            echo "Comment must be filled. ";
            header("refresh:2; url=manage_post.php");
            exit;
        }
        if(!$comment){
            echo "Comment must be filled. ";
            header("refresh:2; url=show_comments.php");
            exit;
        }
  


        //update the comment table
        $stmt = $mysqli->prepare("UPDATE comments set comment=? where commentid=?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            header("refresh:2; url=manage_post.php");
            exit;
        }

        $stmt->bind_param('si',  $comment, $commentid);
        $stmt->execute();
        $stmt->close();


        echo "<p>You have successfully published the comment. Returning to the previous page now. </p>";
        header("refresh:2; url=manage_post.php");
        
        
    ?>
</body>
</html>