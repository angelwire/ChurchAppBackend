<?php

$connect = mysqli_connect($host_name, $user_name, $password, $database);
$sql_command = '';

if (mysqli_connect_errno()) {
    $error_value = mysqli_connect_error();
    echo "-1";
}

$in_password = $_POST["pas"];
$in_token = $_POST["tok"];
$in_os = $_POST["os"];
$in_details = $_POST["det"];
$in_user = $_POST["use"];
$in_name = $_POST["nam"];

if (!mysqli_connect_errno())
{
    if (strcmp($check_password,$in_password) == 0)
    {
        $sql_command = "INSERT INTO " . $database . ".`Devices` (`device_token`, `device_os`, `device_id`, `device_details`, `user_id`, `device_name`) VALUES ('$in_token','$in_os',NULL, '$in_details', '$in_user', '$in_name');";
        if ($connect->query($sql_command) === TRUE)
		{
			$inserted_id = mysqli_insert_id($connect);
			$new_command = "INSERT INTO " .$database. ".`Subscriptions` (`device_id`,`event_id`)
					VALUES  ('$inserted_id','-6'),
							('$inserted_id','-5'),
							('$inserted_id','-4'),
							('$inserted_id','-3'),
							('$inserted_id','-2');";
			$connect->query($new_command);
			echo $inserted_id;
        }
		else
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