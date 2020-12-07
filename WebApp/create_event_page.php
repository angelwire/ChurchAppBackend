<?php
session_start();
if (empty($_SESSION["manager_id"]) or ($_SESSION["manager_events"]!="1" and $_SESSION["manager_unlimited"]!="1"))
{
    die("<script type='text/javascript'>window.location.href = '/AppManager/login_page.php'</script>");
}
echo "<!DOCTYPE html>
";
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";
echo "<script type='text/javascript' src='control_events.js'></script>";
echo "<link href='https://fonts.googleapis.com/css?family=Didact Gothic' rel='stylesheet'>";
echo "<link rel='stylesheet' type='text/css' href='events_style.css'>";

echo "<body>
    <header>
    Add Event
    </header>
    <section>";
    
include 'sidebar.php';

echo "<content>
    <form action='create_event.php' method='post' enctype='multipart/form-data' style='font-weight: bold; font-size:16px;'>
      Event name: <input type='text' name='nam' required pattern='[^\x22]+' title='No Quotation Marks Allowed' oninput='updateCharacterCount(this)'><span id='characterCount'>(0)</span>
		<div class='popup' onclick='showPopup(this)'>
			<img src='Images/question_icon.png' style='height:1em;'></img>
			<span class='popuptext' id='myPopup'>The name of the event, try to keep it under 60 characters long.</span>
		</div>
	  <br>
      Event description:
		<div class='popup' onclick='showPopup(this)'>
			<img src='Images/question_icon.png' style='height:1em;'></img>
			<span class='popuptext' id='myPopup'>The description of the event, feel free to give as much information as possible. Also, be sure to note whether or not the user needs to register or fill out any kind of sign up sheet.</span>
		</div>
	  <br><textarea type='textarea' rows='4' cols='40' name='des' style='max-width:100%' required></textarea><br>
      Event ages: <input type='text' name='age' required pattern='[^\x22]+' title='No Quotation Marks Allowed'>
		<div class='popup' onclick='showPopup(this)'>
			<img src='Images/question_icon.png' style='height:1em;'></img>
			<span class='popuptext' id='myPopup'>A very short description of the target ages for the event. Examples include 'All ages', 'Kids 12 and up', 'Adults over 60', 'Young adults'. The ages don't always have to be exact.</span>
		</div>
		<br>
      Event location name: <input type='text' name='loc' required pattern='[^\x22]+' title='No Quotation Marks Allowed' oninput='checkChurch(this)'>
		<div class='popup' onclick='showPopup(this)'>
			<img src='Images/question_icon.png' style='height:1em;'></img>
			<span class='popuptext' id='myPopup'>The name of the location. DO NOT PUT THE ADDRESS IN THIS FIELD. For example: 'Church', 'Chick-fil-a', 'Pastor Linton's House' etc...</span>
		</div><br>
		Event address: <input type='text' name='add' pattern='[^\x22]+' title='No Quotation Marks Allowed' id='addressField'>
		<button type='button' onclick='testAddress()'>Test</button>
		<div class='popup' onclick='showPopup(this)'>
			<img src='Images/question_icon.png' style='height:1em;'></img>
			<span class='popuptext' id='myPopup'>The address for the location. This is what will be used in the map application to find the location. Be sure to test the address to make sure it points to the right place.</span>
		</div>
        <p>
        Event start:<br>
        Year: <input type='number' name='year1' min='2019' max='2050' onChange = 'updateBeginDate();' value='2019' required>
        Month:    <select name='month1' onChange = 'updateBeginDate();' required>
                    <option value='01'>January</option>
                    <option value='02'>February</option>
                    <option value='03'>March</option>
                    <option value='04'>April</option>
                    <option value='05'>May</option>
                    <option value='06'>June</option>
                    <option value='07'>July</option>
                    <option value='08'>August</option>
                    <option value='09'>September</option>
                    <option value='10'>October</option>
                    <option value='11'>November</option>
                    <option value='12'>December</option>
                </select>
        Day: <input type='number' name='day1' min='1' max='32' onChange = 'updateBeginDate();' required>
        </select>
        
        <br>Start Time:<br>
            Hour: <input name='hour1' type='number' min='1' max='12' onChange = 'updateBeginDate();' required>
            Minute: <input name='minute1' type='number' min='0' max='59' value='00' onChange='updateBeginDate();' required>
            <select name='m1' onChange = 'updateBeginDate();' required>
                <option value='am'>AM</option>
                <option value='pm'>PM</option>
            </select><br>
        </p>
        
        <p>
        <button input='button' type='button' onclick='copyEvent();updateEndDate();'>Copy event start</button><br>Event end:<br>
        Year: <input type='number' name='year2' min='2019' max='2050' onChange = 'updateEndDate();' value='2019' required>
        Month:    <select name='month2' onChange = 'updateEndDate();' required>
                    <option value='01'>January</option>
                    <option value='02'>February</option>
                    <option value='03'>March</option>
                    <option value='04'>April</option>
                    <option value='05'>May</option>
                    <option value='06'>June</option>
                    <option value='07'>July</option>
                    <option value='08'>August</option>
                    <option value='09'>September</option>
                    <option value='10'>October</option>
                    <option value='11'>November</option>
                    <option value='12'>December</option>
                </select>
        Day: <input type='number' name='day2' min='1' max='31' onChange = 'updateEndDate();' required>
        </select>
        
        <br>End Time:<br>
            Hour: <input name='hour2' type='number' min='1' max='12' onChange = 'updateEndDate();' required>
            Minute: <input name='minute2' type='number' min='0' max='59' value='00' onChange='updateEndDate();' required>
            <select name='m2' onChange = 'updateEndDate();' required>
                <option value='am'>AM</option>
                <option value='pm'>PM</option>
            </select><br>
        </p>
    <input type='text' name='dab' disabled style='display:none'>
    <input type='text' name='dae' disabled style='display:none'>
    <input type='text' name='reg' style='display:none'>
    <input type='text' name='pas' style='display:none' value='noodlesoupofchicken'>
    <hr>
    <br>
    <button type='submit' style='font-size:20px'>Add event</button>
    <br>
    <hr>
    </form>
    </content>
    </section>";
?>

<script type="text/javascript">
    finishedLoading();
</script>