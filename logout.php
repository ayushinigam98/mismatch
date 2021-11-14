<?php
    //if the user is logged in then, delete the cookie to log them out
    session_start();

    if(isset($_SESSION['user_id']))
    {
        //delete the variables
        $_SESSION = array();
    
        if(isset($_COOKIE[session_name()]))
        {
            //delete the cookie
            setcookie(session_name(),'',time()-3600);
        }

        session_destroy();
    }

    $home_url = 'http://localhost/headfirstPHP/mismatch/session/index.php';
    header('Location: ' . $home_url);
?>