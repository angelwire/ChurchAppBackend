<?php

$connect = mysqli_connect($host_name, $user_name, $password, $database);
$sql_command = '';



$in_password = $connect->real_escape_string($_POST["pas"]);;


$in_device = $connect->real_escape_string($_POST["did"]);
$in_event = $connect->real_escape_string($_POST["eid"]);
$in_set = $connect->real_escape_string($_POST["set"]); //0 for undo sub 1 for add sub

$subscribe_action = intval($in_set);
$do_query = true;

switch ($subscribe_action)
{
    case 0: $sql_command = "DELETE FROM " .$database. ".`Subscriptions` 
    WHERE (device_id='$in_device' and event_id='$in_event')"; break;
    case 1: $sql_command = "INSERT INTO `Subscriptions` (device_id, event_id)
    SELECT '$in_device', '$in_event'
    FROM  `Subscriptions`
    WHERE (device_id='$in_device' and event_id='$in_event')
    HAVING COUNT(*) = 0"; break;
    default: $do_query = false; break;
}

if (!mysqli_connect_errno())
{   
    if (strcmp($check_password,$in_password) == 0)
    {
        if ($do_query)
        {
        $response_success = $connect->query($sql_command);
        }
        $check_subscribed_command = "SELECT * FROM " .$database. ".`Subscriptions` 
        WHERE (device_id='$in_device' and event_id='$in_event') LIMIT 1";
        
        $return_response = $connect->query($check_subscribed_command);
        
        if (sizeof(mysqli_fetch_array($return_response)) > 1)
        {
            echo "1";
        }
        else
        {
            echo "0";
        }
        
        $connect->close();    
    }
    else {echo "-1";}
} else {echo "-2";}

?>