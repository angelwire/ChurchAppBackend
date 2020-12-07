<?php
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";
echo "<link href='https://fonts.googleapis.com/css?family=Didact Gothic' rel='stylesheet'>";
echo "<script type='text/javascript' src='show_popup.js'></script>";
echo "<link rel='stylesheet' type='text/css' href='events_style.css'>";

echo "<body>
    <header>
    Login
    </header>
    <section>";
    
include 'sidebar.php';

echo "<content>";

echo "<div style='font-size:1em;'>";
echo "<form action='create_login.php' method='post' enctype='multipart/form-data' style='line-height:1.7;'>
Welcome! Please create a username and a new password.<br>
New user name: <input type='text' name='ali' style='font-size:1em; font-family: Didact Gothic; max-width:100%;'>
	<div class='popup' onclick='showPopup(this)'>
		<img src='Images/question_icon.png' style='height:1em;'></img>
		<span class='popuptext' id='myPopup'>You will use this username to log in to the manager.</span>
	</div><br>
New Password: <input type='text' name='pas' style='font-size:1em; font-family: Didact Gothic; max-width:100%;'>
	<div class='popup' onclick='showPopup(this)'>
		<img src='Images/question_icon.png' style='height:1em;'></img>
		<span class='popuptext' id='myPopup'>Create a new password. You will use this password to log in to the manager. Make sure it's memorable.</span>
	</div><br>
Access Token: <input type='text' name='tok' style='font-size:1em; font-family: Didact Gothic; max-width:100%;'>
	<div class='popup' onclick='showPopup(this)'>
		<img src='Images/question_icon.png' style='height:1em;'></img>
		<span class='popuptext' id='myPopup'>This is the token that was included in the invitation email. This toekn will expire when you finish creating your account.</span>
	</div>
<hr>
Please confirm your user name and password:
	<div class='popup' onclick='showPopup(this)'>
		<img src='Images/question_icon.png' style='height:1em;'></img>
		<span class='popuptext' id='myPopup'>Confirm your new user name and password in order to make sure there are no mistakes or typos when you typed in your username and password.</span>
	</div><br>
Confirm User Name: <input type='text' name='ali2' style='font-size:1em; font-family: Didact Gothic; max-width:100%;'><br>
Confirm Password: <input type='text' name='pas2' style='font-size:1em; font-family: Didact Gothic; max-width:100%;'><br>
<hr>
<button style='font-size:1em; font-family: Didact Gothic;'>Create Account</button>";
echo "</div>";

echo "</content></section></body>";
?>