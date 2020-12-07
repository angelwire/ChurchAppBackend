<?php
session_start();
if (empty($_SESSION["manager_id"]) or ($_SESSION["manager_sheets"]!="1" and $_SESSION["manager_unlimited"]!="1"))
{
	die("<script type='text/javascript'>window.location.href = '/AppManager/login_page.php'</script>");
}

$connect = mysqli_connect($host_name, $user_name, $password, $database);
$sql_command = '';

echo "<link href='https://fonts.googleapis.com/css?family=Didact Gothic' rel='stylesheet'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";
echo "<link rel='stylesheet' type='text/css' href='events_style.css'>";

echo "<body>
    <header>
    Delete Sign-Up Sheet
    </header>
    <section>";
    
include 'sidebar.php';

echo "<content>";

if (mysqli_connect_errno()) {
    $error_value = mysqli_connect_error();
    echo "There was a problem deleting the sign-up sheet. Please try to delete the sign-up sheet again, if this problem persists, please email the app support technician and include this error message:
    <br><error> Failed to connect to MySQL:". $error_value . " - Cannot delete sign-up sheet</error>";
    
}

$check_password = 'noodlesoupofchicken';
$in_password = $_POST["pas"];
$in_id = $_POST["id"];

if (strcmp($check_password,$in_password) == 0)
{
    //$sql_command = "INSERT INTO " . $database . ".`Events` (`id`, `name`, `event_date`, `description`, `ages`, `registration`, `volunteer`, `location`) VALUES (NULL,'$in_name','" . $in_date . "','" . $in_description . "','" . $in_ages . "','" . $in_registration . "','" . $in_volunteer . "','" . $in_location . "');";   
    $sql_command = "DELETE FROM " . $database . ".`Sheets` WHERE sheet_id=" . $in_id . " LIMIT 1";
    
    if ($connect->query($sql_command) === TRUE) {
        echo "Successfully deleted sign-up sheet.";
    }    else
    {
            echo "There was a problem deleting the sign-up sheet. Please try to delete the sign-up sheet again, if this problem persists, please email the app support technician and include this error message:
            <br><error>" . $sql_command . " - " . $connect->error .
            "cannot delete sign-up sheet</error>";
    }
    $connect->close();    
}
else
{
        echo "There was a problem deleting the sheet. Please try to delete the sign-up sheet again, if this problem persists, please email the app support technician and include this error message:
            <br><error>Validation Error for deleting sign-up sheet</error>";
}
echo "</content></section></body>";

?>