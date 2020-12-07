<?php
session_start();
if (empty($_SESSION["manager_id"]) or ($_SESSION["manager_events"]!="1" and $_SESSION["manager_unlimited"]!="1"))
{
	die("<script type='text/javascript'>window.location.href = '/AppManager/login_page.php'</script>");
}

$connect = mysqli_connect($host_name, $user_name, $password, $database);
$sql_command = '';

if (mysqli_connect_errno()) {
    die('Failed to connect to MySQL: '.mysqli_connect_error().'');
} else {
    echo 'Connection to MySQL server successfully established.';
}

$in_password = $_POST["pas"];
$in_name = $_POST["nam"];
$in_location = $_POST["loc"];
$in_date_begin = $_POST["dab"];
$in_date_end = $_POST["dae"];
$in_description = $_POST["des"];
$in_ages = $_POST["age"];
$in_persistant = $_POST["per"];

if (strcmp($check_password,$in_password) == 0)
{
    //$sql_command = "INSERT INTO " . $database . ".`Events` (`id`, `name`, `event_date`, `description`, `ages`, `registration`, `volunteer`, `location`) VALUES (NULL,'$in_name','" . $in_date . "','" . $in_description . "','" . $in_ages . "','" . $in_registration . "','" . $in_volunteer . "','" . $in_location . "');";
    $sql_command = "INSERT INTO " . $database . ".`Events` (`event_id`, `event_name`, `event_begin`, `event_end`, `event_description`, `event_ages`, `event_location`, `event_persistant`) VALUES (NULL,'$in_name','$in_date_begin', '$in_date_end', '$in_description','$in_ages','$in_location','$in_persistant');";
    echo "Attempting connection...";
    if ($connect->query($sql_command) === TRUE) {
        echo "SUCCESS: " . $sql_command;
    }    else
    {
        echo "FAILURE: " . $sql_command . " - " . $connect->error;
    }
    $connect->close();    
}
else
{
    echo " - FAILURE TO VALIDATE";
}
?>