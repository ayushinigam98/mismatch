<?php
    session_start();
?>
<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>mismatch</title>
    </head>
    <body>
        <h3>Mismatch - View profile</h3>

        <?php
            require_once('appvars.php');
            require_once('navbar.php');
            //connect to the database
            $dbc = mysqli_connect('localhost','root','','mismatch')
                or die('trouble connecting');
            //get the profile data
            if(isset($_GET['id']))
            {
                $query = "select * from mismatch_user where user_id=" . $_GET['id'] . ' limit 1';
            }
            elseif(isset($_SESSION['user_id']))
            {
                $user_id = $_SESSION['user_id'];
                $query = "select * from mismatch_user where user_id=" . $user_id . ' limit 1';;
            }

            $data = mysqli_query($dbc,$query)
                or die('trouble querying');

            $row = mysqli_fetch_array($data);

            echo '<table>';
            foreach($row as $k=>$v)
            {
                echo '<tr>';
                if(!is_numeric($k) && $k!='password' && $k!='picture' && $k!='user_id')
                    echo '<td>' . $k . ': ' . $v . '</td>';
                echo '</tr>';
            }

            if(is_file(MM_UPLOADPATH . $row['picture']) && filesize(MM_UPLOADPATH . $row['picture'])>0)
            {
                echo '<tr>';
                    echo '<td>' . '<img src="images/' . $row['picture'] . '" alt="picture">' . '</td>';
                echo '</tr>';
            }
            echo '</table>';

            mysqli_close($dbc);
        ?>

    </body>
</html>