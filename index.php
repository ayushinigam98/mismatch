<?php
    session_start();
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
        <meta http-equiv="Pragma" content="no-cache" />
        <meta http-equiv="Expires" content="0" />
        <title>mismathch</title>
    </head>
    <body>
        <h3>Mismatch - Where opposites attract!</h3>

        <?php
            require_once('appvars.php');
            require_once('navbar.php');
            /*
            if(isset($_SESSION['username']))
            {    
                echo '&#10084; <a href="viewprofile.php">View Profile</a><br>';
                echo '&#10084; <a href="editprofile.php">Edit Profile</a><br>';
                echo '&#10084; <a href="logout.php">Log out ' . $_SESSION['username'] . '</a><hr>';
            }
            else
            {
                echo '&#10084; <a href="login.php">Log in</a><br>';
                echo '&#10084; <a href="signup.php">Sign up</a><hr>';
            }
            */
            //connect to the databse
            $dbc = mysqli_connect('localhost','root','','mismatch');
            //retrieve data from the table mismatch_user
            $query = "select user_id, first_name, picture from mismatch_user where first_name is not null order by join_date desc limit 5";
            $data = mysqli_query($dbc,$query);
            var_dump($data);
            //print out the 5 latest users
            echo '<h4>Latest Members</h4>';
            echo '<table>';
            while($row = mysqli_fetch_array($data))
            {
                $file_location = MM_UPLOADPATH . $row['picture'];
                
                echo '<tr>';
                
                echo '<td>';
                if(is_file($file_location) && filesize($file_location)>0)
                {
                    echo '<img src="images/' . $row['picture'] . '" alt="picture">';
                }
                else
                {
                    echo '<img src="images/nopic.jpg" alt="picture"';
                }
                echo '</td>';
                
                echo '<td>';
                echo '<a href="viewprofile.php?';
                echo 'id=' . $row['user_id'] . '">';
                echo $row['first_name'];
                echo '</a>';
                echo '</td>';
                
                echo '</tr>';
            }
            echo '</table>';
            mysqli_close($dbc);
        ?>

    </body>
</html>