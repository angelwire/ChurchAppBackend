<?php
session_start();
if (empty($_SESSION["manager_id"]) or ($_SESSION["manager_info"]!="1" and $_SESSION["manager_unlimited"]!="1"))
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
    Update Church Info
    </header>
    <section>";
    
include 'sidebar.php';

echo "<content>";

if (mysqli_connect_errno()) {
    $error_value = mysqli_connect_error();
    echo "There was a problem updating the info. Please try to update the info again, if this problem persists, please email the app support technician and include this error message:
    <br><error> Failed to connect to MySQL:". $error_value . "</error>";
    
}

$check_password = 'noodlesoupofchicken';
$in_password = $_POST["pas"];
$in_info = mysqli_real_escape_string($connect, $_POST["inf"]);

if (!mysqli_connect_errno())
{
    if (strcmp($check_password,$in_password) == 0)
    {
        $sql_command = "UPDATE " . $database . ".`Extra`
        SET extra_info='$in_info'
        WHERE extra_type='church_info';";
        
        if ($connect->query($sql_command) === TRUE) {
            echo "Successfully updated church info.";
        }    else
        {
            echo "There was a problem updating the church info. Please try to update the info again, if this problem persists, please email the app support technician and include this error message:
            <br><error>" . $sql_command . " - " . $connect->error .
            "</error>";
        }
        $connect->close();    
    }
    else
    {
        echo "There was a problem updating the info. Please try to update the info again, if this problem persists, please email the app support technician and include this error message:
            <br><error>Validation Error</error>";
    }
}
else
{
    echo "There was a problem updating the info. Please try to update the info again, if this problem persists, please email the app support technician and include this error message:
            <br><error>Database Connection Error</error>";
}
echo "</content></section></body>";

?>