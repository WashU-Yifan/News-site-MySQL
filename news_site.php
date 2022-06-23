<!DOCTYPE html>
<html lang="en">

<head> <meta charset="UTF-8"> <title>News Site</title>
    <link rel="stylesheet" href="style.css">
</head>


<body>
    <h1>News Site</h1>
    <?php
        require "database.php";
        session_start();
        $username =(string)$_SESSION['username'];


        //If the username is provided, the logout option will be displayed,
        //otherwise, the login option will be shown.
        if($username)
        {
            printf("<form action='logout.php' method='POST'>
                    
            <p>  Login as %s.<input type='submit' value='Logout' /></p>
                        
            </form>\n",htmlentities($username));
        ?>

        <p> <form action="publish_story.php" method="POST">
                <input type="submit" value="post news" />
            </form>
        </p>
        <p> <form action="manage_post.php" method="POST">
                <input type="submit" value="manage your posts" />
            </form>
        </p>
       <?php
        }
        else{
            
            printf("<form action='logout.php' method='POST'>
                    
            <p>  Visiting as guest.<input type='submit' value='Login' /></p>
                        
            </form>\n");
        }
        
    ?>
     
    
    
    <table styele="width:100%">
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Content</th>
            <th>Likes</th>
            <th>Comment</th>
        </tr>
        <?php
           
            //display the stories in a table format. 
            //The whole story and the comments will not be shown here
            $stmt = $mysqli->prepare(
                "SELECT news.storyid,title ,story,username,COUNT(likes.likeid)
                 FROM news join users on news.id=users.id
                 left join likes on news.storyid=likes.storyid
                 GROUP BY news.storyid");
            if(!$stmt){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            $stmt->execute();   
            $stmt->bind_result($story_id,$title,$story,$name,$cnt);
        
            while($stmt->fetch()){
                
           
                printf('<tr><td>%s</td><td>%s</td><td>%s</td><td>%u</td>',
                    htmlentities($title),
                    htmlentities($name),
                    htmlentities((strlen($story)>40)?substr($story,0,40).'...':$story),
                    htmlentities($cnt)
                );
                ?>
                
                    <form action="show_comments.php" method="POST">
                        <td>
                        <input type="hidden" value='<?php echo "$story_id";?>' name ="storyid"/>
                        <input type="submit" value="more" />
                        </td>
                    </form>
                <?php
           
                printf(" </tr>\n");

            }
        $stmt->close();  
        
        ?>
    </table>
   
    </body>
</html>