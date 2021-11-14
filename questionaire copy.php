<?php
    session_start();
    if(!isset($_SESSION['user_id']))
    {
        $home_url = 'http://localhost/headfirstPHP/mismatch/session/login.php';
        header('Location: ' . $home_url);
    }
?>

<?php
    //connect to the database
    $dbc = mysqli_connect('localhost','root','','mismatch')
        or die('trouble connecting');
    
    $query = "select * from mismatch_response where user_id = " . $_SESSION['user_id'];
    $data = mysqli_query($dbc,$query)
        or die('trouble querying1');
       
   $response_set = array();
   //create an array to store the reponse for each topic_id for this unique user
   
   //check if this is the first time that the user is visiting this page ever. first time ever.
   if(mysqli_num_rows($data) == 0)
   {
        //we need to fill empty answers into the form data
        //for each topic we need to fill in response = unknown, so need to know what all the topics are
        $querymt = "select * from mismatch_topic";
        $datamt = mysqli_query($dbc,$querymt)
            or die('trouble querying2');
        //now insert into the table mismatch_reponse values = 0, since that means unknown; for every topic
        while($row = mysqli_fetch_array($datamt))
        {
            $query_insert = "insert into mismatch_response (user_id,topic_id,response) values (" . $_SESSION['user_id'] . "," . $row['topic_id'] . ",0)";
            mysqli_query($dbc,$query_insert)
                or die('trouble querying3');
            $response_set[$row['topic_id']] = 0;
        }                     
   }     
   elseif(isset($_POST['submit']))
   {
        //so the user may have changed their response, we need to update the table
        //the name of each response is the topic_id, the value is either 0,1 or 2; we need to update the row with user_id = $_SESSION['user_id']
        foreach($_POST as $topic_id => $response)
        {
            $query_update = "update mismatch_response set response = $response where user_id = " . $_SESSION['user_id'] . " and topic_id = $topic_id";
            if($topic_id!='submit')
            {
                mysqli_query($dbc,$query_update)
                    or die('trouble querying4');
            }
            $response_set[$topic_id] = $response;            
        }

        echo 'Your responses have been saved';
   }
   else
   {
        //the user has visited this page before today, but we are displaying the form for the first time since the page opened.
        //we need to select the data from the database
        $query_select = "select * from mismatch_response where user_id =" .  $_SESSION['user_id'];
        $data_select = mysqli_query($dbc,$query_select)
            or die('trouble querying5');
        
        while($row = mysqli_fetch_array($data_select))
        {
            $response_set[$row['topic_id']] = $row['response'];
        }
   }

   $response_topic_name = array();
   $response_topic_category = array();
   $topic_ids = array();

   $query_names = "select name, category, topic_id from mismatch_topic order by topic_id asc";
   $data_names = mysqli_query($dbc,$query_names)
            or die('trouble querying6');
   
   while($row = mysqli_fetch_array($data_names))
   {
        $response_topic_name[$row['topic_id']] = $row['name'];
        $response_topic_category[$row['topic_id']] = $row['category'];
        array_push($topic_ids,$row['topic_id']);
   }
    
   mysqli_close($dbc);
?>

<!doctype html>
<html>
   <head>
        <meta charset="utf-8">
        <title>Questionnaire</title>
        <link rel="stylesheet" href="style.css">
   </head>
   <body>
        <h3>Mismatch - Questionnaire</h3>
        <?php require_once('navbar.php'); ?>
        <p>How do you feel about each topic?</p>
        <form method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
        
        <?php 
            $current_legend="";
            foreach($topic_ids as $topic_id)
            {
                //echo $current_legend . ' = ' . $response_topic_category[$topic_id]; 
                if($current_legend != $response_topic_category[$topic_id])
                {
                    if($topic_id != 1)
                        echo '</fieldset>';
                    echo '<fieldset><legend>' . $response_topic_category[$topic_id] . '</legend>';
                    $current_legend = $response_topic_category[$topic_id]; 
                }

                echo '<label for="' . $topic_id . '">' . $response_topic_name[$topic_id] . ': </label>';
                echo '<input type="radio" id="' . $topic_id . '" name="' . $topic_id . '" value="1" ';
                    if(isset($response_set[$topic_id]) && $response_set[$topic_id] == 1)
                        echo 'checked="checked"';
                echo '>' . 'love  ';
                echo '<input type="radio" id="' . $topic_id . '" name="' . $topic_id . '" value="2" ';
                    if(isset($response_set[$topic_id]) && $response_set[$topic_id] == 2)
                        echo 'checked="checked"';
                echo '>' . 'hate';
                echo '<br>';
            }
        ?>
        </fieldset>
        <input type="submit" name="submit" value="save questionnaire">
        <br>
        
        </form>
 
   </body>
</html>
