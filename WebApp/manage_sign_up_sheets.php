<?php
session_start();
if (empty($_SESSION["manager_id"]) or ($_SESSION["manager_sheets"]!="1" and $_SESSION["manager_unlimited"]!="1"))
{
	die("<script type='text/javascript'>window.location.href = '/AppManager/login_page.php'</script>");
}
echo "<!DOCTYPE html>
";


$connect = mysqli_connect($host_name, $user_name, $password, $database);
$sql_command = '';

if (mysqli_connect_errno()) {
    die('<error>Failed to connect to MySQL: '.mysqli_connect_error().'</error>');
}

$sql_command = "
SELECT Sheets.event_id, Sheets.sheet_visible, Sheets.sheet_id, Sheets.sheet_name, Sheets.sheet_description, Events.event_id, Events.event_name
FROM `Sheets`
LEFT JOIN `Events`
ON Sheets.event_id = Events.event_id";

$result = mysqli_query($connect, $sql_command) or die("ERROR retrieving Sheet database entries " . mysqli_error($connect));

$event_command = "SELECT Events.event_id, Events.event_name FROM `Events`";
$eventResult = mysqli_query($connect, $event_command) or die("ERROR retrieving Event database entries " . mysqli_error($connect));

echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";
echo "<script type='text/javascript' src='manage_sheets.js'></script>";
echo "<link href='https://fonts.googleapis.com/css?family=Didact Gothic' rel='stylesheet'>";
echo "<link rel='stylesheet' type='text/css' href='events_style.css'>";



echo "<body>
    <header>
    Manage Sign-Up Sheets
    </header>
    <section>";
    
include 'sidebar.php';

echo "<content>
<hr>
<a href='create_sign_up_sheet_page.php'><button type='button' style='font-size:24px'>Create New Sign-Up Sheet</button></a>
<hr>

<select id='filterSelect'>
    <option value='-1'>--All--</option>
    <option value='-2'>--No Event--</option>";
    
while($rr = mysqli_fetch_array($eventResult))
{
    echo "<option value='" .$rr['event_id']. "'>" .$rr['event_name']. "</option>";
}
    
echo "
</select>
<button onclick='filterSheetTable();' type='button' style='font-size:12px'>Filter</button>

<div style='overflow-x:auto'>
<table border='1'>
<tr>
<th>ID</th>
<th>Name</th>
<th>Event</th>
<th>Description</th>
<th colspan=3>Actions</th>
</tr>";

while($row = mysqli_fetch_array($result))
{
	$row_visible_text = ($row['sheet_visible']=='1' ? "Make Sheet Hidden" : "Make Sheet Visible");
	
	$is_hidden = $row['sheet_visible']=='0';
	
	$is_hidden_string = $is_hidden ? "hidden":"visible";
	$row_red_string = $is_hidden ? "red" : "not";	
	
		
	echo "<tr name=$row_red_string>";
	echo "<td>" . $row['sheet_id'] . "</td>";
	echo "<td>" . $row['sheet_name'] . "</td>";
	echo "<td name='nameColumn'>" . $row['event_name'] . "</td>";
	echo "<td>" . $row['sheet_description'] . "</td>";
	echo "<td><button onclick=viewReplies('" .$row['sheet_id']. "')>View Replies</button></td>";

	echo "<td>
			<form action='toggle_sheet_visible.php' method='post' enctype='multipart/form-data'>
			<input type='text' name='pas' style='display:none' value='noodlesoupofchicken'>
			<input type='text' name='id' style='display:none' value='". $row['sheet_id'] ."'>
			<input type='text' name='val' style='display:none' value='". $row['sheet_visible'] ."'>		
			<button type='submit' name='$is_hidden_string'>$row_visible_text</button>
		</form>
		</td>";



	//$delete_confirm_script = 'return confirm("Do you really want to delete '.str_replace("'","&#8217;",$row["sheet_name"]).' "?);';
	//$delete_confirm_script = 'return false;';
	$name = str_replace("'","&#1370;",$row["sheet_name"]);
	$delete_confirm_script = 'return confirm("Do you really want to delete &#1370;' .$name.'&#1370;?");';


	echo "<td>
			<form action='delete_sheet.php' method='post' enctype='multipart/form-data' onsubmit='".$delete_confirm_script."'>
			<input type='text' name='pas' style='display:none' value='noodlesoupofchicken'>
			<input type='text' name='id' style='display:none' value='". $row['sheet_id'] ."'>
			<button type='submit'>Delete Sheet</button>
			</form>
			</td>";
			
	echo "</tr>";
}

echo "</table></div>";

mysqli_close($connect);

echo "</content></section>";

echo "<p id='get' style='display:hidden' value='".$_GET['sig']."'></p>";
?>

<script type="text/javascript">
    finishedLoading();
</script>