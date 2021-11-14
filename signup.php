<?php
    require_once('appvars.php');
    
    $username = '';
    $password1 = '';
    $password2 = '';
    //connect to database
    $dbc = mysqli_connect('localhost','root','','mismatch')
        or die('trouble connecting');

    //grab the data
    echo 'hi0';
    if(isset($_POST['submit']))
    {
        echo 'hi1';
        $username = $_POST['username'];
        $password1 = $_POST['password1'];
        $password2 = $_POST['password2'];
        
    

        if(!empty($username) && !empty($password1) && !empty($password2) && $password1==$password2)
        {
            //ensure that the username is not already is use
            $query = "select * from mismatch_user where username = '$username'";
            $data = mysqli_query($dbc,$query)
                or die('trouble querying 1');;

            if(mysqli_num_rows($data) == 0)
            {
                //the username is unique
                $query = "insert into mismatch_user (username,password,join_date) values ('$username',SHA('$password1'),NOW())";
                mysqli_query($dbc,$query)
                    or die('trouble querying 2');
                
                //confirm success
                echo '<p>';
                    echo 'Your new account is succesfully created. You\'re now ready to login and <a href="editprofile.php">edit your profile</a>';
                echo '</p>';

                mysqli_close($dbc);
                exit();
            }
            else
            {
                echo '<p class="error">';
                    echo 'An account is already created with that username. Please try using another name';
                echo '</p>';
                $username="";
            }
        }
        else
        {
            echo '<p class="error">';
                echo 'You need to fill in all the data and the passwrod needs to be written twice and equal to each other. Try again.';
            echo '</p>';
        }
    }

    mysqli_close($dbc);
?>
<p>Please enter your username and desired password to sign up to Mismatch</p>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
    <fieldset>
        <legend>Regestration Information</legend>
        <label for="username">Username: </label>
        <input type="text" id="username" name="username" value="<?php echo $username; ?>"><br>

        <label for="password1">Password1: </label>
        <input type="text" id="password1" name="password1" value="<?php echo $password1; ?>"><br>

        <label for="username">Password2: </label>
        <input type="text" id="password2" name="password2" value="<?php echo $password2; ?>"><br>
    </fieldset>
    <input type="submit" value="sign up" name="submit">
</form>