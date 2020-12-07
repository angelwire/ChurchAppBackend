<?php
session_start();
if (empty($_SESSION["manager_id"]) or ($_SESSION["manager_events"]!="1" and $_SESSION["manager_unlimited"]!="1"))
{
	die("<script type='text/javascript'>window.location.href = '/AppManager/login_page.php'</script>");
}

echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";

$connect = mysqli_connect($host_name, $user_name, $password, $database);
$sql_command = '';

echo "<link href='https://fonts.googleapis.com/css?family=Didact Gothic' rel='stylesheet'>";
echo "<link rel='stylesheet' type='text/css' href='events_style.css'>";

echo "<body>
    <header>
    Create Event
    </header>
    <section>";
    
include 'sidebar.php';

echo "<content>";

if (mysqli_connect_errno()) {
    $error_value = mysqli_connect_error();
    echo "There was a problem adding the event. Please try to create the event again, if this problem persists, please email the app support technician and include this error message:
    <br><error> Failed to connect to MySQL:". $error_value . "</error>";
    
}

foreach($_POST as $key=> $value)
{
	echo "[".$key."] ". $value;
}

$in_password = $_POST["pas"];
$in_name = mysqli_real_escape_string($connect, $_POST["nam"]);
$in_location = mysqli_real_escape_string($connect, $_POST["loc"]);
$in_address = mysqli_real_escape_string($connect, $_POST["add"]);

$in_year_1 = $_POST["year1"];
$in_year_2 = $_POST["year2"];
$in_month_1 = $_POST["month1"];
$in_month_2 = $_POST["month2"];
$in_day_1 = $_POST["day1"];
$in_day_2 = $_POST["day2"];
$in_hour_1 = $_POST["hour1"];
$in_hour_2 = $_POST["hour2"];
$in_minute_1 = $_POST["minute1"];
$in_minute_2 = $_POST["minute2"];
$in_m_1 = $_POST["m1"];
$in_m_2 = $_POST["m2"];

if (strcmp($in_m_1,"pm") == 0)
{
$in_hour_1 = sprintf('%02d',(intval($in_hour_1) % 12) + 12);
}
else
{
$in_hour_1 = sprintf('%02d',intval($in_hour_1) % 12);
}

if (strcmp($in_m_2,"pm") == 0)
{
$in_hour_2 = sprintf('%02d',(intval($in_hour_2) % 12) + 12);
}
else
{
$in_hour_2 = sprintf('%02d',intval($in_hour_2) % 12);
}

$in_minute_1 = sprintf('%02d',$in_minute_1);
$in_minute_2 = sprintf('%02d',$in_minute_2);

$in_date_begin = $in_year_1 . "." . $in_month_1 . "." . $in_day_1 . "." . $in_hour_1 . "." . $in_minute_1;
$in_date_end   = $in_year_2 . "." . $in_month_2 . "." . $in_day_2 . "." . $in_hour_2 . "." . $in_minute_2;


echo "<br>in date begin: ".$in_date_begin;
echo "<br>in date end: " . $in_date_end;

$in_description = mysqli_real_escape_string($connect, $_POST["des"]);
$in_ages = mysqli_real_escape_string($connect, $_POST["age"]);
$in_registration = mysqli_real_escape_string($connect, $_POST["reg"]);
$in_persistant = mysqli_real_escape_string($connect, $_POST["per"]);

if (!mysqli_connect_errno())
{
    if (strcmp($check_password,$in_password) == 0)
    {
        //$sql_command = "INSERT INTO " . $database . ".`Events` (`id`, `name`, `event_date`, `description`, `ages`, `registration`, `volunteer`, `location`) VALUES (NULL,'$in_name','" . $in_date . "','" . $in_description . "','" . $in_ages . "','" . $in_registration . "','" . $in_volunteer . "','" . $in_location . "');";
        $sql_command = "INSERT INTO " . $database . ".`Events` (`event_id`, `event_name`, `event_begin`, `event_end`, `event_description`, `event_ages`, `event_location`, `event_address`) VALUES (NULL,'$in_name','$in_date_begin', '$in_date_end', '$in_description','$in_ages','$in_location', '$in_address');";
        if ($connect->query($sql_command) === TRUE) {
            echo "Successfully Added " .$in_name;
        }    else
        {
            echo "There was a problem adding the event. Please try to create the event again, if this problem persists, please email the app support technician and include this error message:
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
}
else
{
    echo "There was a problem adding the event. Please try to create the event again, if this problem persists, please email the app support technician and include this error message:
            <br><error>Database Connection Error</error>";
}
echo "</content></section></body>";

?>