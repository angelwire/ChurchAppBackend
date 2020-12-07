<?php
session_start();
if (empty($_SESSION["manager_id"]) or ($_SESSION["manager_sheets"]!="1" and $_SESSION["manager_unlimited"]!="1"))
{
	die("<script type='text/javascript'>window.location.href = '/AppManager/login_page.php'</script>");
}

$connect = mysqli_connect($host_name, $user_name, $password, $database);
$sql_command = '';

echo "<!DOCTYPE html>
";

echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";
echo "<script type='text/javascript' src='control_sign_up_sheet.js'></script>";
echo "<link href='https://fonts.googleapis.com/css?family=Didact Gothic' rel='stylesheet'>";
echo "<link rel='stylesheet' type='text/css' href='events_style.css'>";

echo "<body>
    <header>
    Create Sign-Up Sheet
    </header>
    <section>";
    
include 'sidebar.php';

echo "<content>
    <form id='signUpSheetForm' action='create_sign_up_sheet.php' method='post' enctype='multipart/form-data' style='font-weight: bold; font-size:16px'>
    <input type='text' name='pas' style='display:none' value='noodlesoupofchicken'>
    ";
    
echo "<div style='overflow-x:auto'>
	<div id='formsBox'>
    </div>
	</div>	
    <hr>
    <button id='submitSignUpSheet' input='button' type='button' onclick='submitForm()'>Submit Sign-Up Sheet</button>
    <br>
    <hr>
    </form>
    </content>
    </section>";
    
if (!mysqli_connect_errno())
{
    $sql_command = "SELECT event_name, event_id FROM " . $database . ".`Events`;";
    $result = $connect->query($sql_command);
    echo "<script type='text/javascript'>
    finishedLoading();
    createEventSelectorOption('-1','No Event');
    ";
    if ($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc()) 
        {
            //Only show "no event" and created events (not the built-in weekly services)
            if (intval($row["event_id"]) >= -1)
            {
                echo 'createEventSelectorOption("' .$row["event_id"]. '","' .$row["event_name"].'");
                ';
            }
        }
        echo "</script>
        ";
    }
    $connect->close();    
}
else
{
    echo "(db connection warning)";
}
    
?>