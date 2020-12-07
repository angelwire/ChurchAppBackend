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
echo "<script type='text/javascript' src='manage_managers.js'></script>";
echo "<link rel='stylesheet' type='text/css' href='events_style.css'>";

echo "<body>
    <header>
    Control Manager Access
    </header>
    <section>";
    
include 'sidebar.php';

echo "<content>";


$check_password = 'noodlesoupofchicken';
$in_password = $_POST["pas"];
$in_info = mysqli_real_escape_string($connect, $_POST["inf"]);

if (!mysqli_connect_errno())
{
	$sql_command = "SELECT * FROM  `Managers`";
	
	$result = $connect->query($sql_command);
	
	echo "<div style='overflow-x:auto'>";
		echo "<table border='1'>";
		echo "<tr><th>Name</th><th>Full access</th><th>Event Access</th><th>Sheet access</th><th>Alert access</th><th>Church info access</th></tr>";
		while ($manager = mysqli_fetch_array($result))
		{
			echo "<tr>";
			echo "<td> <img src='/AppManager/Images/delete_icon.png' onclick='deleteManager(".$manager["manager_id"].")' style='width:32px; height:32px;'> " .$manager["manager_name"]."</td>";
			echo "<td> " .($manager["manager_unlimited"] ? "Yes": "No")."</td>";
			echo "<td> " .($manager["manager_events"] ? "Yes": "No")."</td>";
			echo "<td> " .($manager["manager_sheets"] ? "Yes": "No")."</td>";
			echo "<td> " .($manager["manager_alerts"] ? "Yes": "No")."</td>";
			echo "<td> " .($manager["manager_info"] ? "Yes": "No")."</td>";
			echo "</tr>";
		}
		echo "</table>";
	echo "</div>";
	echo "<hr>";
	echo "<form id='submissionForm' action='create_manager.php' method='post' enctype='multipart/form-data style='font-weight: bold; font-size:16px'>";
	echo "Create new manager:";
		echo "<div style='font-size:.8em; background-color: rgba(0,0,0,.3); width:auto; padding-left:1em; line-height:1.5;'>";
		echo "Name: <input type='text' name='nam' required></input>";
			echo "<div class='popup' onclick='showPopup(this)'>
				<img src='Images/question_icon.png' style='height:1em;'>
				<span class='popuptext' id='myPopup'>The name of the manager you wish to invite.</span>
			</div><br>";
		echo "E-mail address: <input type='text' name='add' required></input>";
					echo "<div class='popup' onclick='showPopup(this)'>
				<img src='Images/question_icon.png' style='height:1em;'>
				<span class='popuptext' id='myPopup'>The email address of the manager you wish to send the invitation to.</span>
			</div><br>";
		echo "Give full access: <input type='checkbox' name='accessCheckbox'>";
			echo "<div class='popup' onclick='showPopup(this)'>
				<img src='Images/question_icon.png' style='height:1em;'>
				<span class='popuptext' id='myPopup'>Gives the manager FULL COMPLETE ACCESS TO EVERYTHING including adding new managers. Overrides all other accesses.</span>
			</div><br>";
		echo "Give access to edit events: <input type='checkbox' name='accessCheckbox'>";
			echo "<div class='popup' onclick='showPopup(this)'>
				<img src='Images/question_icon.png' style='height:1em;'>
				<span class='popuptext' id='myPopup'>Gives the manager access to edit, create, and delete events.</span>
			</div><br>";		
		echo "Give access to edit sign-up sheets: <input type='checkbox' name='accessCheckbox'>";
			echo "<div class='popup' onclick='showPopup(this)'>
					<img src='Images/question_icon.png' style='height:1em;'>
					<span class='popuptext' id='myPopup'>Allows the manager to edit and create sign up sheets.</span>
				</div><br>";
		echo "Give access send alerts: <input type='checkbox' name='accessCheckbox'>";
			echo "<div class='popup' onclick='showPopup(this)'>
					<img src='Images/question_icon.png' style='height:1em;'>
					<span class='popuptext' id='myPopup'>Allows the manager to send alerts.</span>
				</div><br>";		
		echo "Give access to edit church info: <input type='checkbox' name='accessCheckbox'>";
			echo "<div class='popup' onclick='showPopup(this)'>
					<img src='Images/question_icon.png' style='height:1em;'>
					<span class='popuptext' id='myPopup'>Allows the manager to edit the church info.</span>
				</div><br>";
		echo "<button input='button' type='button' style='font-size:1em;' onclick='submitForm()'>Invite Manager</button>";
		echo "</div>";
	echo "</form>";
}
else
{
    echo "There was a problem loading this web page Please try to load the page again, if this problem persists, please email the app support technician and include this error message:
            <br><error>Database Connection Error</error>";
}
echo "</content></section></body>";

?>