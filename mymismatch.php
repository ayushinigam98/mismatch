<?php
    session_start();
    if(!isset($_SESSION['user_id']))
    {
        $home_url = 'http://localhost/headfirstPHP/mismatch/session/login.php';
        header('Location: ' . $home_url);
    }
?>
<?php
    require_once('appvars.php');
    $dbc = mysqli_connect('localhost','root','','mismatch');
    $query = "select * from mismatch_response where user_id = " . $_SESSION['user_id'];
    $data = mysqli_query($dbc,$query)
        or die('trouble querying');
    
    if(mysqli_num_rows($data) == 0)
    {     
        header('Refresh: 3; url=http://localhost/headfirstPHP/mismatch/session/questionaire.php');
        echo 'You need to fill out the questionnaire first. We\'ll relocate you there.';
    }
    
    //we need to find the responses of the users questionnaire
    $query_select_user_responses = "select mr.response_id, mr.topic_id, mr.response, mt.name as topic_name "
                            . "from mismatch_response as mr "
                            . "inner join mismatch_topic as mt "
                            . "using (topic_id) "
                            . "where mr.user_id = " . $_SESSION['user_id']
                            . " order by mt.topic_id";
                        
    echo $query_select_user_responses;
    $data_user_responses = mysqli_query($dbc,$query_select_user_responses)
        or die('trouble querying0');
    
    $user_responses = array();

    while($row_user_responses = mysqli_fetch_array($data_user_responses))
    {
        array_push($user_responses, $row_user_responses);
        //var_dump($row_user_responses);
        //echo '<br>';
    }
    //we've selected the user responses and stored them in an array, each index in the array stores all the info related to a topic

    //now we need to initialize the variables to store the identity of the best mismatch and other info
    $mismatch_score = 0;
    $mismatch_user_id = -1;
    $mismatch_topics = array();

    //now we need to find the best mismatch, by looping throught the responses of all the other users
    $query_mismatchee_user_id = "select user_id from mismatch_user where user_id !=" . $_SESSION['user_id'];
    $data_mismatchee_user_id = mysqli_query($dbc,$query_mismatchee_user_id)
        or die("trouble querying1");
    
    while($row_mismatchee_user_id = mysqli_fetch_array($data_mismatchee_user_id))
    {
        $query_mismatchee_responses = "select response_id, topic_id, response from mismatch_response "
                                    . "where user_id = " . $row_mismatchee_user_id['user_id']
                                    . " order by topic_id";

        $data_mismatchee_responses = mysqli_query($dbc,$query_mismatchee_responses)
            or die('trouble querying2');
        
        $mismatchee_responses = array();

        while($row_mismatchee_response = mysqli_fetch_array($data_mismatchee_responses))
        {
            array_push($mismatchee_responses,$row_mismatchee_response);
        }
        
        $score = 0;
        $topics = array();

        for($i=0;$i<count($user_responses);$i++)
        {
            if(((int)$user_responses[$i]['response']) + ((int)$mismatchee_responses[$i]['response']) == 3)
            {
                $score += 1;
                array_push($topics, $user_responses[$i]['topic_name']);
            }          
        }

        if($score > $mismatch_score)
        {
            $mismatch_score = $score;
            $mismatch_user_id = $row_mismatchee_user_id['user_id'];
            $mismatch_topics = array_slice($topics,0); 
        }
    }
    
    echo '<h3>My Mismatch!</h3>';
    require_once('navbar.php');
    if($mismatch_user_id != -1)
    {
        $query_select_mismatchee = "select username, first_name, last_name, city, state, picture from mismatch_user where user_id = " . $mismatch_user_id;
        $data_mismatchee = mysqli_query($dbc,$query_select_mismatchee)
            or die('did\'nt work');
        $row_mismatchee = mysqli_fetch_array($data_mismatchee);

        echo '<table>';
        foreach($row_mismatchee as $k=>$v)
        {
            echo '<tr>';
            if(!is_numeric($k) && $k!='password' && $k!='picture' && $k!='user_id')
                echo '<td>' . $k . ': ' . $v . '</td>';
            echo '</tr>';
        }

        if(is_file(MM_UPLOADPATH . $row_mismatchee['picture']) && filesize(MM_UPLOADPATH . $row_mismatchee['picture'])>0)
        {
            echo '<tr>';
                echo '<td>' . '<img src="images/' . $row_mismatchee['picture'] . '" alt="picture">' . '</td>';
            echo '</tr>';
        }
        echo '</table>';

        echo '<br>You are mismatched on the following topics:<br>';

        foreach($mismatch_topics as $topic)
        {
            echo $topic . '<br>';
        }

        echo '<br>' . '<a href="viewprofile.php?id=' . $mismatch_user_id . '">Click Here </a>if you want to check out their profile';  
    }

?>