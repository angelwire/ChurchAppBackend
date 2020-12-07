<?php

//-2 Sunday Morning
//-3 Sunday Evening
//-4 Wednesday Visitation
//-5 Wednesday Service
//-6 Wednesday Youth


$connect = mysqli_connect($host_name, $user_name, $password, $database);
$sql_command = '';

if (mysqli_connect_errno()) {
    $error_value = mysqli_connect_error();
    echo "-1";
}

$in_password = $_POST["pas"];
$in_device = $_POST["did"];


if (!mysqli_connect_errno())
{
    try
    {
    if (strcmp($check_password,$in_password) == 0)
    {
        $sql_command = "SELECT event_id, device_id FROM " . $database . ".`Subscriptions` WHERE device_id='$in_device' ORDER BY event_id ASC";
        $result = $connect->query($sql_command);        
        while($row = mysqli_fetch_assoc($result))
        {
            echo $row["event_id"]. "-=-";
        }
        $connect->close();    
    }
    else
    {
        throw new Exception('Authorization Error');
    }
    }
    catch (Exception $e)
    {
        die("there is a php authorization error");
    }
}

?>