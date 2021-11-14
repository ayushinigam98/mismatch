<?php
    session_start();
?>
<?php
    if(isset($_POST['submit']))
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        if(!empty($username) && !empty($password))
        {
            $dbc = mysqli_connect('localhost','root','','mismatch')
                or die('trouble connecting');
            $query = "select user_id, username from mismatch_user where username = '$username' and password = SHA('$password')";
            $data = mysqli_query($dbc,$query)
                or die('trouble querying');
            
            if(mysqli_num_rows($data) == 1)
            {
                $row = mysqli_fetch_array($data);
                
                $_SESSION['username'] = $row['username'];
                $_SESSION['user_id'] = $row['user_id'];

                $home_url = 'http://localhost/headfirstPHP/mismatch/session/index.php';
                header('Location: ' . $home_url);
            }
            else
            {
                echo '<p>You need to enter a valid username and password. Try again</p>';
            }

            mysqli_close($dbc);
        }
        else
        {
            echo '<p>Please dont leave any category empty. Try again</p>';
        }
    }
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Log in</title>
    </head>
    <body>
        <h3>Mismatch - Log in</h3>
        
        <?php
            if(isset($_SESSION['username']))
            {
                echo '<p>You are logged in as ' . $_SESSION['username'] . '</p>';
            }
        ?>
        
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <fieldset>
                <legend>Log in</legend>
                
                <label for="username">Username: </label>
                <input type="text" id="username" name="username" value="<?php if(!empty($username)) echo $username ?>">

                <label for="password">Password:</label>
                <input type="password" id="password" name="password">
            </fieldset>
            <input type="submit" value="Log In" name="submit" />
       </form>  

    </body>
</html>




