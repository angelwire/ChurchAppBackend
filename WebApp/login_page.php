<?php
echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";
echo "<link href='https://fonts.googleapis.com/css?family=Didact Gothic' rel='stylesheet'>";
echo "<link rel='stylesheet' type='text/css' href='events_style.css'>";

echo "<body>
    <header>
    Login
    </header>
    <section>";
    
include 'sidebar.php';

echo "<content>";

echo "<div style='font-size:1em;'>";
echo "<form action='login.php' method='post' enctype='multipart/form-data'>
User name: <input type='text' name='ali' style='font-size:1em; font-family: Didact Gothic; max-width:100%;'><br>
Password:  <input type='password' name='pas' style='font-size:1em; font-family: Didact Gothic; max-width:100%;'><br>
<button style='font-size:1em; font-family: Didact Gothic;'>Login</button>";
echo "</div>";

echo "</content></section></body>";
?>