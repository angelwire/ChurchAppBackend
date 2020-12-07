<?php

$connect = mysqli_connect($host_name, $user_name, $password, $database);
$sql_command = '';

if (mysqli_connect_errno()) {
    $error_value = mysqli_connect_error();
    echo "-1";
}

$in_password = $_POST["pas"];
$in_id = $_POST["did"];

$current_time = date('Y-m-d H:i:s');

if (!mysqli_connect_errno())
{
    if (strcmp($check_password,$in_password) == 0)
    {
        $sql_command = "UPDATE Devices SET device_register_time = '$current_time' WHERE device_id = '$in_id'";
        if ($connect->query($sql_command) === TRUE) {
            echo 1;
        }    else
        {
            echo "-2" . $connect->error;
        }
        $connect->close();    
    }
    else
    {
        echo "-3";
    }
}

?>