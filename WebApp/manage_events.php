<?php
session_start();
if (empty($_SESSION["manager_id"]) or ($_SESSION["manager_events"]!="1" and $_SESSION["manager_unlimited"]!="1"))
{
	die("<script type='text/javascript'>window.location.href = '/AppManager/login_page.php'</script>");
}

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

echo "<!DOCTYPE html>
";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";

$connect = mysqli_connect($host_name, $user_name, $password, $database);
$sql_command = '';

if (mysqli_connect_errno()) {
    die('Failed to connect to MySQL: '.mysqli_connect_error().'');
}

$sql_command = "
SELECT *
FROM `Events`
WHERE (event_id >= 0)
ORDER BY event_begin ASC";

$result = mysqli_query($connect, $sql_command) or die("ERROR retrieving database entries " . mysqli_error($connect));

//$sql_command = "INSERT INTO " . $database . ".`Events` (`id`, `name`, `event_date`, `description`, `ages`, `registration`, `location`) VALUES (NULL,'$in_name','" . $in_date . "','" . $in_description . "','" . $in_ages . "','" . $in_registration . "','" . $in_volunteer . "','" . $in_location . "');";

echo "<script type='text/javascript' src='control_events.js'></script>";
echo "<link href='https://fonts.googleapis.com/css?family=Didact Gothic' rel='stylesheet'>";
echo "<link rel='stylesheet' type='text/css' href='events_style.css'>";



echo "<body>
    <header>
    Manage Events
    </header>
    <section>";
    
include 'sidebar.php';

echo "<content>
<hr>
<a href='create_event_page.php'><button type='button' style='font-size:24px'>Create New Event</button></a>
<hr>
<div style='overflow-x:auto'>
<table border='1'>
<tr>
<th>ID</th>
<th>Name</th>
<th>Begin Date</th>
<th>End Date</th>
<th>Description</th>
<th>Ages</th>
<th>Location</th>
<th colspan='4'>Actions</th>
</tr>";

$edit_path = "http://www.angelwirestudio.com/HTTPTEST/edit_event.php?id=";
$delete_path = "http://www.angelwirestudio.com/HTTPTEST/delete_event.php?id=";

while($row = mysqli_fetch_array($result))
{
	$row_visible_text = ($row['event_visible']=='1' ? "Make Event Hidden" : "Make Event Visible");        
	
	$is_hidden = $row['event_visible']=='0';
	$is_past = (time() > strtotime($row['event_end']));
	
	$is_hidden_string = $is_hidden ? "hidden":"visible";
	$row_red_string = ($is_past || $is_hidden) ? "red" : "not";
	
    echo "<tr name=".$row_red_string.">";
    echo "<td>" . $row['event_id'] . "</td>";
    echo "<td>" . $row['event_name'] . "</td>";
    echo "<td>" . $row['event_begin'] . "</td>";
    echo "<td>" . $row['event_end'] . "</td>";
    echo "<td>" . $row['event_description'] . "</td>";
    echo "<td>" . $row['event_ages'] . "</td>";
    echo "<td>" . $row['event_location'] . "<hr>" .$row['event_address']. "</td>";
    echo "<td>";
	
	if (strcmp($_SESSION["manager_sheets"],"1") == 0 or strcmp($_SESSION["manager_unlimited"],"1") == 0)
	{
		echo "<form action='edit_event.php' method='post' enctype='multipart/form-data'>
            <input type='text' name='pas' style='display:none' value='noodlesoupofchicken'>
            <input type='text' name='id' style='display:none' value='" .$row['event_id']. "'>
            <button type='button' onclick='goToSignUpPage(".$row['event_id'].")'>View Sign-Up</button>
         </form>";
	}	
	echo "</td>";
    echo "<td>
            <form action='edit_event_page.php' method='post' enctype='multipart/form-data'>
            <input type='text' name='pas' style='display:none' value='noodlesoupofchicken'>
            <input type='text' name='eid' style='display:none' value='". $row['event_id'] ."'>
            <button type='submit'>Edit Event</button>
            </form>
            </td>";
			
	$name = str_replace("'","&#1370;",$row["event_name"]);
	$delete_confirm_script = 'return confirm("Do you really want to delete &#1370;' .$name.'&#1370;?");';
    echo "<td>
            <form action='delete_event.php' method='post' enctype='multipart/form-data' onsubmit='".$delete_confirm_script."'>
            <input type='text' name='pas' style='display:none' value='noodlesoupofchicken'>
            <input type='text' name='id' style='display:none' value='". $row['event_id'] ."'>
            <button type='submit'>Delete Event</button>
            </form>
            </td>";

	echo "<td>
            <form action='toggle_event_visible.php' method='post' enctype='multipart/form-data'>
            <input type='text' name='pas' style='display:none' value='noodlesoupofchicken'>
            <input type='text' name='id' style='display:none' value='". $row['event_id'] ."'>
			<input type='text' name='val' style='display:none' value='". $row['event_visible'] ."'>
            <button type='submit' id='visibleButton' name='$is_hidden_string'>$row_visible_text</button>
            </form>
            </td>";
    echo "</tr>";
}

echo "</table></div>";
mysqli_close($connect);

echo "</content></section>";    
?>