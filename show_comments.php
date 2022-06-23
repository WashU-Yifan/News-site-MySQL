<!DOCTYPE html>
<html lang="en">

<head> <meta charset="UTF-8"> <title>News Site</title>
    <link rel="stylesheet" href="style.css">
</head>


<body>
        <form action="news_site.php" method="POST">
                    
            <p>Return to main page <input type="submit" value="go" /></p>
                                
        </form>
        <h2> Story</h2>
        <h3>Title:</h3>
        <?php
            require "database.php";
            session_start();
            //the story id is required to fecth the whole story and comments
            if($_POST['storyid']){
                $story_id =(int)$_POST['storyid'];
                $_SESSION['story_id']=$story_id;
            }   
            if(!$_SESSION['story_id']){
                echo "<p>Invalid story id, returning to main page.</p>";
                header("refresh:2; url=news_site.php");
                exit;
            }

            $user_id=(int)$_SESSION['user_id'];
            $story_id=(int)$_SESSION['story_id'];


            $stmt = $mysqli->prepare(
                "SELECT title ,story,link,username 
                FROM news join users 
                on news.id=users.id
                where storyid= ?");
            if(!$stmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }


            $stmt->bind_param('i', $story_id);
            $stmt->execute();  
            $stmt->bind_result($title,$story,$link,$name);
           
            //Printed out the story.
            $stmt->fetch();
            printf('<p>%s</p>',htmlentities($title));
    ?>
  
            <h3>Author:</h3>
           <?php printf('<p>%s</p>',htmlentities($name));?>
            
   
            <h3>Content:</h3>
            <?php printf('<p>%s</p>',htmlentities($story));?>

    
            
            <?php if($link){
                printf('<a href="http://%s"><h3> Link</h3></a>',$link);
            };
            $stmt->close(); 


            //get the number of likes related to this story
            $stmt=$mysqli->prepare(
                "SELECT COUNT(likes.likeid) 
                from likes Where storyid=?");
            if(!$stmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            $stmt->bind_param('i', $story_id);
            $stmt->execute();  
            $stmt->bind_result($cnt);
            $stmt->fetch();
            printf('<p>Likes: %u</p>',$cnt);
            $stmt->close();  


            //Identify whether the current user has liked the post.
            //If yes, the user can unlike the news,
            //otherwise, the user can like the news.
            if($user_id){
                $stmt=$mysqli->prepare(
                    "SELECT COUNT(likes.likeid) 
                    from likes Where storyid=? and id=?");
                if(!$stmt){
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }
                $stmt->bind_param('ii', $story_id,$user_id);
                $stmt->execute();  
                $stmt->bind_result($like_or_not);
                $stmt->fetch();
                //the user has already liked the post
                
                if($like_or_not>0){
                    
                    printf("<a href='unlike.php'>Unlike</a>");
                }
                else{
                    
                    printf("<a href='like.php'>Like</a>");
                    
                }
            }
            else{//The users must login before commenting.
                printf("<a href='like.php'>Like</a>");
            }
            ?>
    <!--publish a comment-->
    <form action="insert_comment.php" method="POST">
    
        <p>
        Insert your comment<br><textarea name="comment" rows="15" cols="60"></textarea><br>
        <input type="hidden" name ="token" value=
        "<?php 
            session_start();
            $_SESSION['token']=bin2hex(random_bytes(32));  
            echo $_SESSION['token'];
            ?>"/>
        <input type="submit" value="post" />
       
        </p>
    </form>

    
    <?php
        $stmt->close(); 
        
        //display all comments under this story
        $stmt = $mysqli->prepare(
            "SELECT  comment,users.id,username
            FROM  comments  join users 
            on comments.id=users.id
            where comments.storyid= ?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }

        $stmt->bind_param('i', $story_id);
        $stmt->execute();  
        $stmt->bind_result($comment,$id,$comment_name);

        printf("<h2>Comments: </h2>");
        while($stmt->fetch()){
            printf("<h3>By: %s</h3> 
             <p>%s</p>",
            htmlentities($comment_name),
            htmlentities($comment));
        }
        $stmt->close();
    ?>
    
   
   </body>
</html> 