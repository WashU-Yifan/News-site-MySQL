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
        //filter input

        if(!$_POST['storyid']){
            echo "<p>Story must be filled. </p>";
            header("refresh:2; url=manage_post.php");
            exit;
        }
        $storyid=(int)$_POST['storyid'];


        //check userid does match the storyid
        $user_id =(int)$_SESSION['user_id'];
        $stmt = $mysqli->prepare("SELECT id from news where storyid=?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            header("refresh:2; url=manage_post.php");
            exit;
        }

        $stmt->bind_param('i',$storyid);
        $stmt->execute();
        $stmt->bind_result($user_id_result);
        $stmt->fetch();
        $stmt->close();

        //If the user id does not match, return to the main page now.
        if($user_id!=$user_id_result){
            echo "user id does not match, the table might be polluted.";
            header("refresh:2; url=user_login.php");
            exit;
        }
      

        //delete the story
        $stmt = $mysqli->prepare("DELETE from news where storyid=?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            header("refresh:2; url=manage_post.php");
            exit;
        }


        $stmt->bind_param('i',  $storyid);
        $stmt->execute();
        $stmt->close();


        //delete all comments under the story
        $stmt = $mysqli->prepare("DELETE from comments where storyid=?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            header("refresh:2; url=manage_post.php");
            exit;
        }

        $stmt->bind_param('i',  $storyid);
        $stmt->execute();
        $stmt->close();

        //remove all likes under this story
        $stmt = $mysqli->prepare("DELETE from likes where storyid=?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            header("refresh:2; url=manage_post.php");
            exit;
        }

        $stmt->bind_param('i',  $storyid);
        $stmt->execute();
        $stmt->close();


        echo "<p>You have successfully delete the story and all related comments. Returning to the previous page now. </p>";
        header("refresh:2; url=manage_post.php");
        
        
    ?>
    </body>
</html>