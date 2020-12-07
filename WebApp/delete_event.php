<?php
session_start();
if (empty($_SESSION["manager_id"]) or ($_SESSION["manager_events"]!="1" and $_SESSION["manager_unlimited"]!="1"))
{
	die("<script type='text/javascript'>window.location.href = '/AppManager/login_page.php'</script>");
}

$connect = mysqli_connect($host_name, $user_name, $password, $database);
$sql_command = '';

echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";
echo "<link href='https://fonts.googleapis.com/css?family=Didact Gothic' rel='stylesheet'>";
echo "<link rel='stylesheet' type='text/css' href='events_style.css'>";

echo "<body>
    <header>
    Delete Event
    </header>
    <section>";
    
include 'sidebar.php';

echo "<content>";

if (mysqli_connect_errno()) {
    $error_value = mysqli_connect_error();
    echo "There was a problem adding the event. Please try to create the event again, if this problem persists, please email the app support technician and include this error message:
    <br><error> Failed to connect to MySQL:". $error_value . "</error>";
    
}

$check_password = 'noodlesoupofchicken';
$in_password = $_POST["pas"];
$in_id = $_POST["id"];

if (strcmp($check_password,$in_password) == 0)
{
    //$sql_command = "INSERT INTO " . $database . ".`Events` (`id`, `name`, `event_date`, `description`, `ages`, `registration`, `volunteer`, `location`) VALUES (NULL,'$in_name','" . $in_date . "','" . $in_description . "','" . $in_ages . "','" . $in_registration . "','" . $in_volunteer . "','" . $in_location . "');";   
    $sql_command = "DELETE FROM " . $database . ".`Events` WHERE event_id=" . $in_id . " LIMIT 1";
    
    if ($connect->query($sql_command) === TRUE) {
        echo "Successfully deleted event.";
    }    else
    {
            echo "There was a problem deleting the event. Please try to delete the event again, if this problem persists, please email the app support technician and include this error message:
            <br><error>" . $sql_command . " - " . $connect->error .
            "</error>";
    }
    $connect->close();    
}
else
{
        echo "There was a problem adding the event. Please try to create the event again, if this problem persists, please email the app support technician and include this error message:
            <br><error>Validation Error</error>";
}
echo "</content></section></body>";

?>