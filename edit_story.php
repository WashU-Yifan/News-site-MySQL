<!DOCTYPE html>
<html lang="en">

<head> <meta charset="UTF-8"> <title>News Site</title>
    <link rel="stylesheet" href="style.css">
</head>
    <?php
        require "database.php";
        session_start();
        //filter input

        if(!$_POST['storyid']){
            echo "storyid must be filled. ";
            header("refresh:2; url=manage_post.php");
            exit;
        }
        $_SESSION['storyid']=(int)$_POST['storyid'];

        //check if the userid match
        $user_id =(int)$_SESSION['user_id'];
        $stmt = $mysqli->prepare("SELECT id from news where storyid=?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            header("refresh:2; url=manage_post.php");
            exit;
        }



        $stmt->bind_param('i',$_SESSION['storyid']);
        $stmt->execute();
        $stmt->bind_result($user_id_result);
        $stmt->fetch();
        $stmt->close();


        //return to the main page if the table user id does not match with the session user id
        if($user_id!=$user_id_result){
            echo "user id does not match, the table might be polluted.";
            header("refresh:2; url=user_login.php");
            exit;
        }

    ?>



    <p>
        <form action="insert_edit_story.php" method="POST">
        title<br><input type="text" name="title"  /><br>
        story<br><textarea name="story" rows="15" cols="60"></textarea><br>
        link<br><input type="text" name="link"  /><br>
        <input type="hidden" name ="token" value=
        "<?php 
            session_start();
            echo $_SESSION['token'];
            ?>"/>
            <input type="submit" value="post" />
        </form>
    </p>



    <form action="manage_post.php" method="POST">
       
        <input type="submit" value="cancel" />
    </form>



    <body>
</html> 
