<?php
    echo '<hr>';
    echo '<span>';
    if(isset($_SESSION['username']))
    {    
        echo '<a href="index.php">Home</a> &#10084;';
        echo '<a href="viewprofile.php">View Profile</a> &#10084;';
        echo '<a href="editprofile.php">Edit Profile</a> &#10084;';
        echo '<a href="questionaire.php">Questionnaire</a> &#10084;';
        echo '<a href="mymismatch.php">My Mismatch</a> &#10084;';
        echo '<a href="logout.php">Log out ' . $_SESSION['username'] . '</a><hr>';
    }
    else
    {
        echo '<a href="index.php">Home</a> &#10084; ';
        echo '<a href="login.php">Log in</a> &#10084; ';
        echo '<a href="signup.php">Sign up</a><hr>';
    }
    echo '</span>';
?>