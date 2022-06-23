<!DOCTYPE html>
<html lang="en">

<head> <meta charset="UTF-8"> <title>News Site</title>
    <link rel="stylesheet" href="style.css">
</head>


<body>
    <h1>Manage Your Posts</h1>
    
    <form action="news_site.php" method="POST">
                    
        <p>Return to main page <input type="submit" value="go" /></p>
                    
    </form>
        
    <?php
        require "database.php";
        session_start();
        $username =$_SESSION['username'];
        
        if($username)
        {
            printf("<form action='logout.php' method='POST'>
                    
            <p>  Login as %s.<input type='submit' value='Logout' /></p>
                        
            </form>\n",htmlentities($username));
            echo "<p>Below are the listing of your stoies and comments.</p>";
        }
        else{
            
            echo "You need to login first.";
            header("refresh:2; url=user_login.php");
            exit;
        }


        //fetching all columns from the news table, inlcuding storyid, title, story and the link of the news
        $stmt = $mysqli->prepare(
            "SELECT news.storyid,title,story,link, COUNT(likes.likeid)
            FROM news left join likes on
            news.storyid=likes.storyid
            where news.id=?
            GROUP BY news.storyid");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
       
        $userid=(int)$_SESSION['user_id'];
       

        $stmt->bind_param('i',$userid);
        $stmt->execute();   
        $stmt->bind_result($story_id,$title,$story,$link,$cnt);
        printf('<h2> Posted Stories</h2>');

        //Print all stories
        while($stmt->fetch()){
           


            printf('<h2> Story Title: %s</h2>',htmlentities($title));
            printf('<p>Likes: %u</p>',$cnt);
            if($link){
                printf('<a href="http://%s"><h3> Link</h3></a>',$link);
            }
            printf('<h3> Story:</h3><p>%s</p>',    
                htmlentities((strlen($story)>103)?substr($story,0,100).'...':$story));
            //buttons for modidying the current story
           ?>
                <form action="delete_story.php" method="POST">
                    
                    <input type="hidden" name ="storyid" value='<?php echo "$story_id";?>'/>
                    <input type="hidden" name ="token" value=
                    "<?php 
                        session_start();
                        $_SESSION['token']=bin2hex(random_bytes(32));  
                        echo $_SESSION['token'];
                        ?>"/>
                    <input type="submit" value="delete story" />
                    
                </form>
           
            <form action="edit_story.php" method="POST">
               
               <input type="hidden" name ="storyid" value='<?php echo "$story_id";?>'/>
               <input type="hidden" name ="token" value=
                "<?php 
                    session_start();
                    $_SESSION['token']=bin2hex(random_bytes(32));  
                    echo $_SESSION['token'];
                    ?>"/>
               <input type="submit" value="edit story" />
               
            </form>
           <?php
       
           

        }

        $stmt->close();  
         

        
        $stmt = $mysqli->prepare(
            "SELECT commentid,title,comment
            FROM comments join news 
            on comments.storyid=news.storyid
            where comments.id=?");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->bind_param('i',$_SESSION['user_id']);
        $stmt->execute();   
        $stmt->bind_result($commentid, $title, $comment);

        //print out all comments
        printf('<h2> Posted Comments</h2>');
        while($stmt->fetch()){
            printf('<h2> Story Title: %s</h2><h3> Comment:</h3><p>%s</p>',
                htmlentities($title),
                htmlentities((strlen($comment)>100)?substr($comment,0,100).'...':$comment)
            );
        ?>
                <form action="delete_comment.php" method="POST">
                    
                    <input type="hidden" name ="commentid" value='<?php echo "$commentid";?>'/>
                    <input type="hidden" name ="token" value=
                    "<?php 
                        session_start();
                        $_SESSION['token']=bin2hex(random_bytes(32));  
                        echo $_SESSION['token'];
                        ?>"/>
                    <input type="submit" value="delete comment" />

                    
                </form>
            
            <form action="edit_comment.php" method="POST">
               
               <input type="hidden" name ="commentid" value='<?php echo "$commentid";?>'/>
               <input type="hidden" name ="token" value=
                "<?php 
                    session_start();
                    $_SESSION['token']=bin2hex(random_bytes(32));  
                    echo $_SESSION['token'];
                    ?>"/>
               <input type="submit" value="edit comment" />
               
            </form>
        <?php
       
            

        }
         $stmt->close();  
    ?>

    </body>
</html>