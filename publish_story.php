<!DOCTYPE html>
<html lang="en">

<head> <meta charset="UTF-8"> <title>News Site</title>
    <link rel="stylesheet" href="style.css">
</head>


<body>
    <h1>Share Your Story</h1>
    <form action="news_site.php" method="POST">
                    
            <p>Return to main page <input type="submit" value="go" /></p>
                                
        </form>
    <?php
        require "database.php";
        session_start();
        $username =(string)$_SESSION['username'];
        //need to login first publish a new story
        if($username)
        {
            printf("<form action='logout.php' method='POST'>
                    
            <p>  Login as %s.<input type='submit' value='Logout' /></p>  
            </form>\n",htmlentities($username));
        }
        else{
            
            echo "<p>You need to login first.</p>";
            header("refresh:2; url=user_login.php");
            exit;
        }
        
    ?>
     
<p>
    <form action="insert_story.php" method="POST">
    title<br><input type="text" name="title"  /><br>
    story<br><textarea name="story" rows="15" cols="60"></textarea><br>
    link<br><input type="text" name="link"  /><br>
    <input type="hidden" name ="token" value=
        "<?php 
            session_start();
            $_SESSION['token']=bin2hex(random_bytes(32));  
            echo $_SESSION['token'];
            ?>"/>
        <input type="submit" value="post" />
    </form>
    </p>
    </body>
</html>