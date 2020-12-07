<?php

$connect = mysqli_connect($host_name, $user_name, $password, $database);
$sql_command = '';


$in_password = $connect->real_escape_string($_POST["pas"]);;
$in_id = $connect->real_escape_string($_POST["did"]);

if (!mysqli_connect_errno())
{   
    if (strcmp($check_password,$in_password) == 0)
    {
        $sql_command = "SELECT device_name, device_id FROM " . $database . ".`Devices`        
        WHERE device_id = '$in_id'";
        $response = $connect->query($sql_command);
        while($device = mysqli_fetch_assoc($response))
        {
            echo base64_decode($device["device_name"]);
        }
        
        $connect->close();
    }
    else {echo "-1";}
} else {echo "-2";}

?>