<?php
session_start();
if (empty($_SESSION["manager_id"]) or ($_SESSION["manager_events"]!="1" and $_SESSION["manager_unlimited"]!="1"))
{
	die("<script type='text/javascript'>window.location.href = '/AppManager/login_page.php'</script>");
}

$eid = $_POST["eid"];

$connect = mysqli_connect($host_name, $user_name, $password, $database);

$sql_command = "SELECT * FROM Events WHERE event_id = '$eid'";
$query_result = mysqli_query($connect, $sql_command) or die("ERROR retrieving Event " . mysqli_error($connect));
$event = mysqli_fetch_assoc($query_result);

$begin_date = date_parse($event["event_begin"]);
$begin_am = (intval($begin_date["hour"]) < 12);
$begin_date["hour"] = "".(intval($begin_date["hour"]) % 12);
if (intval($begin_date["hour"]) == 0)
{
    $begin_date["hour"] == "12";
}

$end_date = date_parse($event["event_end"]);
$end_am = (intval($end_date["hour"]) < 12);
$end_date["hour"] = "".(intval($end_date["hour"]) % 12);
if (intval($end_date["hour"]) == 0)
{
    $end_date["hour"] == "12";
}


$e_name = $event["event_name"];
$e_description = $event["event_description"];
$e_location = $event["event_location"];
$e_address = $event["event_address"];
$e_ages = $event["event_ages"];

echo "<!DOCTYPE html>
";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";
echo "<script type='text/javascript' src='control_edit_events.js'></script>";
echo "<link href='https://fonts.googleapis.com/css?family=Didact Gothic' rel='stylesheet'>";
echo "<link rel='stylesheet' type='text/css' href='events_style.css'>";

echo "<body>
    <header>
    Edit Event: ";    
echo $e_name;

echo "</header>
    <section>";
    
include 'sidebar.php';
echo "<content>";
$regex = "[^\x22]+";
echo "<form action='edit_event.php' method='post' enctype='multipart/form-data' style='font-weight: bold; font-size:16px'>";
echo "Event name: <input type='text' name='nam' required pattern='".$regex."' title='No quotation marks allowed'"; echo ' value="' .$e_name. '">';
echo "<div class='popup' onclick='showPopup(this)'>
			<img src='Images/question_icon.png' style='height:1em;'></img>
			<span class='popuptext' id='myPopup'>The name of the event, try to keep it under 60 characters long.</span>
		</div><br>";
echo "Event description:";
echo "<div class='popup' onclick='showPopup(this)'>
			<img src='Images/question_icon.png' style='height:1em;'></img>
			<span class='popuptext' id='myPopup'>The description of the event, feel free to give as much information as possible. Also, be sure to note whether or not the user needs to register or fill out any kind of sign up sheet.</span>
		</div>";
echo "<br><textarea type='textarea' rows='4' cols='40' name='des' required>" .$e_description. "</textarea><br>";
echo "Event ages: <input type='text' name='age' required pattern='".$regex."' title='No quotation marks allowed'";  echo ' value="' .$e_ages. '">';
echo "<div class='popup' onclick='showPopup(this)'>
			<img src='Images/question_icon.png' style='height:1em;'></img>
			<span class='popuptext' id='myPopup'>A very short description of the target ages for the event. Examples include 'All ages', 'Kids 12 and up', 'Adults over 60', 'Young adults'. The ages don't always have to be exact.</span>
		</div><br>";
echo "Event location name: <input type='text' name='loc' required pattern='".$regex."' title='No quotation marks allowed' oninput='checkChurch(this)'"; echo ' value="' .$e_location. '">';
echo "<div class='popup' onclick='showPopup(this)'>
			<img src='Images/question_icon.png' style='height:1em;'></img>
			<span class='popuptext' id='myPopup'>The name of the location. DO NOT PUT THE ADDRESS IN THIS FIELD. For example: 'Church', 'Chick-fil-a', 'Pastor Linton's House' etc...</span>
		</div><br>";
echo "Event address: <input type='text' name='add' required pattern='".$regex."' title='No quotation marks allowed'  id='addressField'"; echo ' value="' .$e_address. '">';
echo "<button type='button' onclick='testAddress()'>Test</button>";
echo "<div class='popup' onclick='showPopup(this)'>
			<img src='Images/question_icon.png' style='height:1em;'></img>
			<span class='popuptext' id='myPopup'>The address for the location. This is what will be used in the map application to find the location. Be sure to test the address to make sure it points to the right place.</span>
		</div><br>";
echo "<p>
        Event start:<br>
        Year: <input type='number' name='year1' min='2019' max='2050' onChange = 'updateBeginDate();' value='" .$begin_date["year"]. "' required>
        Month:    <select name='month1' onChange = 'updateBeginDate();' required>
                    <option value='01' ".($begin_date["month"]=="1"? "selected" : "").">January</option>
                    <option value='02' ".($begin_date["month"]=="2"? "selected" : "").">February</option>
                    <option value='03' ".($begin_date["month"]=="3"? "selected" : "").">March</option>
                    <option value='04' ".($begin_date["month"]=="4"? "selected" : "").">April</option>
                    <option value='05' ".($begin_date["month"]=="5"? "selected" : "").">May</option>
                    <option value='06' ".($begin_date["month"]=="6"? "selected" : "").">June</option>
                    <option value='07' ".($begin_date["month"]=="7"? "selected" : "").">July</option>
                    <option value='08' ".($begin_date["month"]=="8"? "selected" : "").">August</option>
                    <option value='09' ".($begin_date["month"]=="9"? "selected" : "").">September</option>
                    <option value='10' ".($begin_date["month"]=="10"? "selected" : "").">October</option>
                    <option value='11' ".($begin_date["month"]=="11"? "selected" : "").">November</option>
                    <option value='12' ".($begin_date["month"]=="12"? "selected" : "").">December</option>
                </select>
        Day: <input type='number' name='day1' min='1' max='32' onChange = 'updateBeginDate();' required value='" .$begin_date["day"]. "'>
        
        <br>Start Time:<br>
            Hour: <input name='hour1' type='number' min='1' max='12' onChange = 'updateBeginDate();' required value='" .$begin_date["hour"]. "'>
            Minute: <input name='minute1' type='number' min='0' max='59' onChange='updateBeginDate();' required value='" .$begin_date["minute"]. "'>
            <select name='m1' onChange = 'updateBeginDate();' required>
                <option value='am' ".($begin_am ? "selected" : "").">AM</option>
                <option value='pm' ".(!$begin_am ? "selected" : "").">PM</option>
            </select><br>
        </p>
        
        <p>
        <button input='button' type='button' onclick='copyEvent();updateEndDate();'>Copy event start</button><br>Event end:<br>
        Year: <input type='number' name='year2' min='2019' max='2050' onChange = 'updateEndDate();' value='" .$end_date["year"]. "' required>
        Month:    <select name='month2' onChange = 'updateEndDate();' required>
                    <option value='01' ".($end_date["month"]=="1"? "selected" : "").">January</option>
                    <option value='02' ".($end_date["month"]=="2"? "selected" : "").">February</option>
                    <option value='03' ".($end_date["month"]=="3"? "selected" : "").">March</option>
                    <option value='04' ".($end_date["month"]=="4"? "selected" : "").">April</option>
                    <option value='05' ".($end_date["month"]=="5"? "selected" : "").">May</option>
                    <option value='06' ".($end_date["month"]=="6"? "selected" : "").">June</option>
                    <option value='07' ".($end_date["month"]=="7"? "selected" : "").">July</option>
                    <option value='08' ".($end_date["month"]=="8"? "selected" : "").">August</option>
                    <option value='09' ".($end_date["month"]=="9"? "selected" : "").">September</option>
                    <option value='10' ".($end_date["month"]=="10"? "selected" : "").">October</option>
                    <option value='11' ".($end_date["month"]=="11"? "selected" : "").">November</option>
                    <option value='12' ".($end_date["month"]=="12"? "selected" : "").">December</option>
                </select>
        Day: <input type='number' name='day2' min='1' max='31' onChange = 'updateEndDate();' required value='" .$end_date["day"]. "'>
        
        <br>End Time:<br>
            Hour: <input name='hour2' type='number' min='1' max='12' onChange = 'updateEndDate();' required value='" .$end_date["hour"]. "'>
            Minute: <input name='minute2' type='number' min='0' max='59' onChange='updateEndDate();' required value='" .$end_date["minute"]. "'>
            <select name='m2' onChange = 'updateEndDate();' required>
                <option value='am' ".($end_am ? "selected" : "").">AM</option>
                <option value='pm' ".(!$end_am ? "selected" : "").">PM</option>
            </select><br>
        </p>
        <div id='formsBox'>
        </div>
    <input type='text' name='dab' disabled style='display:none'>
    <input type='text' name='dae' disabled style='display:none'>
    <input type='text' name='reg' style='display:none'>
    <input type='text' name='eid' style='display:none' value='$eid'>
    <input type='text' name='pas' style='display:none' value='noodlesoupofchicken'>
    <hr>
    <br>
    <button type='submit' style='font-size:20px'>Update event</button>
    <br>
    <hr>
    </form>
    </content>
    </section>";
?>

<script type="text/javascript">
    finishedLoading();
</script>