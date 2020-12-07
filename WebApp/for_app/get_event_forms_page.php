<?php>
session_start();
?>

<meta id='viewport' name=viewport content='width=device-width; initial-scale=1'>

<?php

//Set up HTML stuff
echo "<!DOCTYPE html>
";

echo "<link href='https://fonts.googleapis.com/css?family=Didact Gothic' rel='stylesheet'>
";
echo "<link rel='stylesheet' type='text/css' href='in_app_style.css'>
";
echo "<body>
";



$connect = mysqli_connect($host_name, $user_name, $password, $database);
$sql_command = '';
$selected_event_id = $_GET["eid"];
$selected_sheet_id = $_GET["sid"];
$selected_device_id = $_GET["did"];

if (mysqli_connect_errno()) {
        echo "failed to connect to database";
        die('Failed to connect to MySQL: '.mysqli_connect_error().'');
    }

if (empty($_SESSION["device_id"]))
{
    if(!empty($_GET["did"]))
    {
        $_SESSION["device_id"] = $selected_device_id;
        
        $current_time = time();
        $get_time_command = "SELECT device_register_time, device_id FROM `Devices` WHERE device_id = '$selected_device_id'";
        $device_id_result = mysqli_query($connect, $get_time_command) or die("ERROR BRO");
        while($register_result = mysqli_fetch_assoc($device_id_result))
        {
            $register_time = strtotime($register_result["device_register_time"]);
        }
        echo "Selected device id: $selected_device_id <br> Register time: $register_time <br>";

        echo "Current time is: $current_time and registered time is: $register_time <br>";
        echo "So the difference is " .($current_time-$register_time). "<br>";
        
        if ($current_time - $register_time < 120)
        {
            echo "Time is good!";
        }
        else
        {
            echo "Not authorized: session expired";
        }
        
    }
    else
    {
        echo "Not authorized, no device ID";
        die();
    }
}

if (!mysqli_connect_errno())
{
	if (empty($_GET["sid"]))
	{
		$sheets_array = array();
		$questions_array = array();
		$options_array = array();

		$current_sheet_id = "-1";
		$current_question_id = "-1";

		$sql_command = "SELECT * FROM Sheets WHERE event_id = '$selected_event_id'";
		$sheet_result = mysqli_query($connect, $sql_command);
		
		$result_size = 0;
		while (mysqli_fetch_array($sheet_result))
		{
		$result_size += 1;
		}
		mysqli_data_seek($sheet_result,0);
		
		$current_sheet_id = mysqli_fetch_assoc($sheet_result)["sheet_id"];
	}
	else
	{
		$current_sheet_id = $selected_sheet_id;
		$result_size = 1;
	}
    if ($result_size == 1)
    {
        $URL="/AppManager/forapp/get_forms_page.php?sid=$current_sheet_id";
        echo "<script type='text/javascript'>document.location.href='{$URL}';</script>
        ";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">
        ';
        die();
    }
    
    mysqli_data_seek($sheet_result,0);
    echo "<div class='total-sheet'>There are multiple sign-up sheets for this event, please select one.</div>";
    while($row = mysqli_fetch_assoc($sheet_result))
    {
        $current_sheet_id = $row["sheet_id"];
        echo "<div class='total-sheet'>
        ";
            echo "<div class='title'>
            ";
                echo $row["sheet_name"];
            echo "</div>
            ";
            echo "<div class='description'>
            ";
                echo $row["sheet_description"];
                echo "<br>
                ";
                echo "<button style='background-color:rgba(0,180,180,255);'>
                ";
                echo "<a href='get_forms_page.php?sid=$current_sheet_id'>
                ";
                    echo "View form";
                echo "</a>
                ";
                echo "</button>
                ";

            echo "</div>
            ";
        echo "</div>
        ";
    }
}
else
{
    echo "There was a problem retrieving form. Please contact the app support technician and include this error message:
            <br><error>GET_FORM: Database Connection Error</error>";
}

mysqli_close($connect);



echo "</body>";
?>

<script type="text/javascript">
    //finishedLoading();
</script>