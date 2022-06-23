
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
        $comment =$mysqli->real_escape_string((string)$_POST['comment']);
        $story_id =(int)$_SESSION['story_id'];

        if(!$comment){
            echo "Comment must be filled. ";
            header("refresh:2; url=show_comments.php");
            exit;
        }

        if(!$user_id){
            echo "Must login to perform this action. ";
            header("refresh:2; url=user_login.php");
            exit;
        }
            
        $stmt = $mysqli->prepare("INSERT into comments (storyid, comment, id) values (?, ?, ?)");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            header("refresh:2; url=show_comments.php");
            exit;
        }
        $stmt->bind_param('isi', $story_id, $comment, $user_id);
        
        $stmt->execute();
        
        $stmt->close();
        echo "<p>You have successfully published the comment. Returning to the previous page now. </p>";
        header("refresh:2; url=news_site.php");
        
        
    ?>
      <body>
</html> 