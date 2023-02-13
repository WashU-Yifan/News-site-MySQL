<!DOCTYPE html>
<html lang="en">

<head> <meta charset="UTF-8"> <title>News Site</title>
    <link rel="stylesheet" href="style.css">
</head>
        <?php
        require "database.php";
        session_start();

        //filter input
        if(!$_POST['commentid']){
            echo "Comment must be filled. ";
            header("refresh:2; url=manage_post.php");
            exit;
        }
        $_SESSION['commentid']=(string)$_POST['commentid'];
        $user_id =(int)$_SESSION['user_id'];


         //make sure the user id does match
        $stmt = $mysqli->prepare("SELECT id from comments where commentid=?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            header("refresh:2; url=manage_post.php");
            exit;
        }
        $stmt->bind_param('i',$_SESSION['commentid']);
        $stmt->execute();
        $stmt->bind_result($user_id_result);
        $stmt->fetch();
        $stmt->close();


        if($user_id!=$user_id_result){
            echo "user id does not match, the table might be polluted.";
            header("refresh:2; url=user_login.php");
            exit;
        }
    ?>



    <form action="insert_edit_comment.php" method="POST">
        
        <p>
        <!-- insert your comment--> 
        Insert your comment<br><textarea name="comment" rows="15" cols="60"></textarea><br>
        <input type="hidden" name ="token" value=
        "<?php 
            session_start();
            echo $_SESSION['token'];
            ?>"/>

        <input type="submit" value="post" />
       
        </p>
    </form>
    </body>
</html> 
