<?php
session_start();

$connect = mysqli_connect($host_name, $user_name, $password, $database);
$sql_command = '';

echo "<meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";
echo "<link href='https://fonts.googleapis.com/css?family=Didact Gothic' rel='stylesheet'>";
echo "<link rel='stylesheet' type='text/css' href='events_style.css'>";

echo "<body>
    <header>
    Login
    </header>
    <section>";
	
if (!empty($_SESSION["manager_id"]))
{
	include 'sidebar.php';
	echo "<content>";		
	echo "You are already logged in. If you would like to login as a different manager enter the details below.<br><br>";
	echo "<div style='font-size:1em;'>";
	echo "<form action='login.php' method='post' enctype='multipart/form-data'>
	User name: <input type='text' name='ali' style='font-size:1em; font-family: Didact Gothic; max-width:100%;'><br>
	Password:  <input type='text' name='pas' style='font-size:1em; font-family: Didact Gothic; max-width:100%;'><br>
	<button style='font-size:1em; font-family: Didact Gothic;'>Login</button>";
	echo "</div>";
	die("</content></section></body>");    
}
    
if (mysqli_connect_errno()) {
    $error_value = mysqli_connect_error();
	include 'sidebar.php';
	echo "<content>";
    die("There was a database error while logging in. Please try again, if this problem persists please contact the app support technician and include this message:
    <br><error> Failed to connect to MySQL:". $error_value . "</error></content></section></body>");    
}


$in_alias = mysqli_real_escape_string($connect, $_POST["ali"]);
$in_password = mysqli_real_escape_string($connect, $_POST["pas"]);


if (!mysqli_connect_errno())
{
	$sql_command = "SELECT * FROM `Managers` WHERE manager_alias='$in_alias';";
	$result = $connect->query($sql_command);
	$result = mysqli_fetch_assoc($result);
	if ($result["manager_password"] == $in_password)
	{
		session_unset();
		$_SESSION["manager_id"]= $result["manager_id"];
		$_SESSION["manager_alerts"]= $result["manager_alerts"];
		$_SESSION["manager_sheets"]= $result["manager_sheets"];
		$_SESSION["manager_events"]= $result["manager_events"];
		$_SESSION["manager_info"]= $result["manager_info"];
		$_SESSION["manager_unlimited"]= $result["manager_unlimited"];
		$_SESSION["manager_name"]= $result["manager_name"];
		$_SESSION["manager_alias"]= $result["manager_alias"];
		include 'sidebar.php';

		echo "<content>";
		echo "Welcome " .$in_alias. "!<br>";
	}    else
	{
		session_destroy();
		include 'sidebar.php';
		echo "<content>";		
		echo "Incorrect user name or password.<br><br>";
		echo "<div style='font-size:1em;'>";
		echo "<form action='login.php' method='post' enctype='multipart/form-data'>
		User name: <input type='text' name='ali' style='font-size:1em; font-family: Didact Gothic; max-width:100%;'><br>
		Password:  <input type='text' name='pas' style='font-size:1em; font-family: Didact Gothic; max-width:100%;'><br>
		<button style='font-size:1em; font-family: Didact Gothic;'>Login</button>";
		echo "</div>";
		
	}
	$connect->close();    
}
else
{
    echo "There was a problem logging in. If this problem persists, please email the app support technician and include this error message:
            <br><error>Database Connection Error</error>";
}
echo "</content></section></body>";
?>