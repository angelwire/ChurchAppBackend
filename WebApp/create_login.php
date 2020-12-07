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
	echo "You are already logged in. Use this form to log in as a different manager.<br><br>";
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
    die("There was a database error while creating accunt. Please try again, if this problem persists please contact the app support technician and include this message:
   <br><error> Failed to connect to MySQL:". $error_value . "</error></content></section></body>");
    
}

$in_alias = $_POST["ali"];
$safe_alias = mysqli_real_escape_string($connect, $_POST["ali"]);
if (strcmp($in_alias,$safe_alias) != 0 or strcmp($in_alias,"") == 0)
{
	die("<error>Bad user name</error>");
}

$in_password = $_POST["pas"];
$safe_password = mysqli_real_escape_string($connect, $_POST["pas"]);
if (strcmp($in_alias,$safe_alias) != 0 or strcmp($in_password,"") == 0)
{
	die("<error>Bad password</error>");
}
$in_token = mysqli_real_escape_string($connect, $_POST["tok"]);

if (!mysqli_connect_errno())
{	
	$sql_command = "SELECT COUNT(manager_id) AS total FROM `Managers` WHERE (manager_password='$in_token');";
	$token_result = mysqli_fetch_assoc($connect->query($sql_command));
	$token_result = $token_result["total"];
	$sql_command = "SELECT COUNT(manager_id) AS total FROM `Managers` WHERE (manager_alias='$safe_alias');";
	$alias_result = mysqli_fetch_assoc($connect->query($sql_command));
	$alias_result = $alias_result["total"];
	
	if ($token_result == 1 and $alias_result == 0)
	{
		$sql_command = "UPDATE `Managers`
						SET
						manager_alias='$safe_alias',
						manager_password='$safe_password'
						WHERE (manager_password='$in_token')";
		$result = $connect->query($sql_command);
		if ($result == true)
		{		
			$sql_command = "SELECT * FROM `Managers` WHERE (manager_alias='$safe_alias');";
			$result = $connect->query($sql_command);
			$result = mysqli_fetch_assoc($result);
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
			echo "Welcome " .$result["manager_name"]. "!<br>";
		}
		else
		{
			include 'sidebar.php';
			echo "<content>";
			echo "There was an error creating your account, please try again. If this problem persists please contact the app support technician and include this error message:";
			echo "<error>";
			echo "Creating manager: " .$connect->error;
			echo "</error>";
			echo "<a href='/AppManager/login_create_page.php'>Click here to try again</a>";
		}
	}
	else
	{
		include 'sidebar.php';
		echo "<content>";
		if ($token_result != 1)
		{
			if ($token_result < 1)
			{
			echo "There was an error creating your account, please check to make sure the access token was entered correctly.";
			}
			else
			{
			echo "There was a major issue creating your account, please contact the app support technician and include this error message:<error>Multiple Error tokens: [$in_token]</error>";
			}
		}
		if ($alias_result != 0)
		{
			echo "<error>";
			echo "There was an error creating your account,The user name you selected is already in use. If you believe this is an error and the problem persists please contact the app support technician.";
			echo "</error>";
		}
		echo "<a href='/AppManager/login_create_page.php'>Click here to try again</a>";
	}
	$connect->close();    
}
else
{
    echo "There was a problem logging in. If this problem persists, please email the app support technician and include this error message:
            <br><error>Database Connection Error</error>";
	echo "<a href='AppManager/login_create_page.php'>Click here to try again</a>";
}
echo "</content></section></body>";
?>