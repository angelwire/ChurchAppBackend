<?php
session_start();
if (empty($_SESSION["manager_id"]) or $_SESSION["manager_unlimited"]!="1")
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
    Delete Manager
    </header>
    <section>";
    
include 'sidebar.php';

echo "<content>";

if (mysqli_connect_errno()) {
    $error_value = mysqli_connect_error();
    echo "There was a problem deleting the manager. If this problem persists, please email the app support technician and include this error message:
    <br><error> Failed to connect to MySQL:". $error_value . "</error>";
    
}

$in_id = $_POST["mid"];


$sql_command = "DELETE FROM " . $database . ".`Managers` WHERE manager_id=" . $in_id . " LIMIT 1";

if ($connect->query($sql_command) === TRUE) {
	echo "Successfully deleted manager.";
}    else
{
		echo "There was a problem deleting the manager. If this problem persists, please email the app support technician and include this error message:
		<br><error>" . $sql_command . " - " . $connect->error .
		"</error>";
}
$connect->close();    

echo "</content></section></body>";

?>