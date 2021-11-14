<?php
    session_start();
    if(!isset($_SESSION['user_id']))
    {
        $home_url = 'http://localhost/headfirstPHP/mismatch/session/login.php';
        header('Location: ' . $home_url);
    }
?>

<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title>edit profile</title>
    </head>
    <body>
        <h3>Mismatch - edit profiles</h3>

        <?php
            require_once('appvars.php');
            require_once('navbar.php');
            $dbc = mysqli_connect('localhost','root','','mismatch')
                or die('trouble connecting');

            /*if(isset($_GET['id']))
            {
                $user_id = $_GET['id'];
                $query = "select * from mismatch_user where user_id=$user_id";
                $data = mysqli_query($dbc,$query) 
                    or die('trouble querying');

                $row = mysqli_fetch_array($data);
                
                $first_name = $row['first_name']; 
                $last_name = $row['last_name']; 
                $gender = $row['gender']; 
                $birthdate = $row['birthdate']; 
                $city = $row['city']; 
                $state = $row['state']; 
                $picture = $row['picture']; 
            }
            else*/if(isset($_POST['submit']))
            {
                $first_name = $_POST['first_name']; 
                $last_name = $_POST['last_name']; 
                $gender = $_POST['gender']; 
                $birthdate = $_POST['birthdate']; 
                $city = $_POST['city']; 
                $state = $_POST['state']; 
                $picture = $_FILES['picture']['name']; 
                $user_id = $_POST['user_id'];
                $type = $_FILES['picture']['type'];

                if(empty($picture))
                {
                    $picture = $_POST['old_picture'];
                }
                elseif(($_FILES['picture']['size']<=0 || $_FILES['picture']['size']>MM_MAXFILESIZE) || ($type != 'image/gif' && $type != 'image/jpeg' && $type != 'image/pjpeg' && $type != 'image/png'))
                {
                    $picture = $_POST['old_picture'];
                    echo '<p class="error">';
                    echo 'The screenshot must be a GIF, JPEG, or PNG image. It must be smaller than ' . MM_MAXFILESIZE . ' KB in size.';
                    echo '</p>';
                }
                else
                {
                    @unlink(MM_UPLOADPATH . $_POST['old_picture']);
                    if(move_uploaded_file($_FILES['picture']['tmp_name'],MM_UPLOADPATH . $picture))
                    {
                        echo 'done<br>';
                    }
                    else
                    {
                        die('Do not work');
                    }
                    $query = "update mismatch_user set picture='$picture' where user_id=$user_id";
                    mysqli_query($dbc,$query) 
                        or die('trouble querying'); 
                }

                if(!empty($first_name) && !empty($last_name) && !empty($gender) && !empty($birthdate) && !empty($city) && !empty($state))
                {
                    $query = "update mismatch_user set first_name='$first_name', last_name='$last_name', gender='$gender', birthdate='$birthdate', "
                            . "city='$city', state='$state', picture='$picture' where user_id=$user_id";
                    mysqli_query($dbc,$query) 
                        or die('trouble querying');
                }
                else
                {
                    echo '<p class="error">';
                    echo 'You need to fill in all the data';
                    echo '</p>';
                }

                mysqli_close($dbc);
            }
            elseif(isset($_SESSION['user_id']))
            {
                $user_id = $_SESSION['user_id'];
                $query = "select * from mismatch_user where user_id=$user_id";
                $data = mysqli_query($dbc,$query) 
                    or die('trouble querying');

                $row = mysqli_fetch_array($data);
                
                $first_name = $row['first_name']; 
                $last_name = $row['last_name']; 
                $gender = $row['gender']; 
                $birthdate = $row['birthdate']; 
                $city = $row['city']; 
                $state = $row['state']; 
                $picture = $row['picture']; 
            }
            

        ?>
        <form enctype="multipart/form-data" method="post" action="editprofile.php">
            <fieldset>
                <legend>Personal information</legend>
                <label for="firstname">Firstname: </label>
                <input type="text" id="firstname" name="first_name" value="<?php echo $first_name; ?>"><br>
                
                <label for="lastname">Lastname: </label>
                <input type="text" id="lastname" name="last_name" value="<?php echo $last_name; ?>"><br>
                
                <label for="gender">Gender: </label>
                <select id="gender" name="gender">
                    <option value="M" <?php if($gender=='M') echo 'selected="selected"'; ?>>Male</option>
                    <option value="F" <?php if($gender=='F') echo 'selected="selected"'; ?>>Female</option>
                </select>
                <br>
                <label for="birthdate">birthdate: </label>
                <input type="text" id="birthdate" name="birthdate" value="<?php echo $birthdate; ?>"><br>
                
                <label for="city">City: </label>
                <input type="text" id="city" name="city" value="<?php echo $city; ?>"><br>
                
                <label for="state">State: </label>
                <input type="text" id="state" name="state" value="<?php echo $state; ?>"><br>
                
                <label for="picture">File: </label>
                <input type="file" id="picture" name="picture"><br>
                
                <input type="hidden" name="old_picture" value=<?php echo $picture; ?>>
                <input type="hidden" name="user_id" value=<?php echo $user_id; ?>>         
                <?php
                    $file_location = MM_UPLOADPATH . $picture;
                    echo 'current picture:';
                    if(is_file($file_location) && filesize($file_location)>0)
                    {
                        echo '<img src="images/' . $picture . '" alt="picture">';
                    }
                    else
                    {
                        echo '<img src="images/nopic.jpg" alt="picture"';
                    }
                ?>         
                <br>
            </fieldset>
            <input type="submit" name=submit value="save profile">
        </form>
            

    </body>
</html>