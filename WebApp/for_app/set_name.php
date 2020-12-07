<?php
$connect = mysqli_connect($host_name, $user_name, $password, $database);
$sql_command = '';


$check_password = "noodlesoupofchicken";
$in_password = $connect->real_escape_string($_POST["pas"]);;


$in_id = $connect->real_escape_string($_POST["did"]);
$in_name = $connect->real_escape_string($_POST["nam"]);

if (!mysqli_connect_errno())
{   
    if (strcmp($check_password,$in_password) == 0)
    {
        $sql_command = "UPDATE " . $database . ".`Devices`
        SET device_name='$in_name'
        WHERE device_id = '$in_id'";
        $response_success = $connect->query($sql_command);
        $connect->close();
        echo "1";
    }
    else {echo "-1";}
} else {echo "-2";}

?>