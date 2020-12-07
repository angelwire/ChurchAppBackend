<?php session_start(); ?>
<meta id='viewport' name=viewport content='width=device-width; initial-scale=1'>

<?php
function build_select($question_option_result, $build_question_id)
{
	echo "<select type='select' name='answer' questionid='".$build_question_id."'>";
	while ($row_o = mysqli_fetch_assoc($question_option_result))
	{
		
		echo "<option value='".$row_o['option_value']."'>".$row_o['option_value']."</option>
		";
	}
	echo "</select>";
}

function build_select_many($question_option_result, $build_question_id)
{
	echo "<div type='selectmany' name='answer' questionid='".$build_question_id."'>";
	while ($row_o = mysqli_fetch_assoc($question_option_result))
	{
		echo "<label class='container'>";
		echo "<input type='checkbox'  name='selectboxes' value='".$row_o['option_value']."'>".$row_o['option_value']."<br>
		";
		echo "<span class='checkmark'></span>";
		echo "</label>";
	}
	echo "</div>";
}

function build_text($build_question_id)
{
	echo "<input type='text' placeholder='".$row_q["question_label"]."' questionid='".$build_question_id."' name='answer'>
	";
}

function build_text_long($build_question_id)
{
	echo "<input type='textarea' placeholder='".$row_q["question_label"]."' rows='3'  name='answer' questionid='".$build_question_id."'>
	";
}

function build_yes_no($build_question_id)
{
	echo "<div type='selectmany' name='answer' questionid='".$build_question_id."'>
		<label class='container'>
			<input type='radio'  name='selectboxes' value='yes'>Yes<br>
			<span class='checkmark'></span>
		</label>
		<label class='container'>
			<input type='radio'  name='selectboxes' value='no'>No<br>
			<span class='checkmark'></span>
		</label>
	</div>";
}

function build_list($build_question_id)
{
	$onclick_code = "addListResponse(this,$build_question_id)";
	echo "<button onclick='$onclick_code'>Add</button>";
}

//Set up HTML stuff
echo "<!DOCTYPE html>
";

echo "<script type='text/javascript' src='control_forms_page.js'></script>";
echo "<link href='https://fonts.googleapis.com/css?family=Didact Gothic' rel='stylesheet'>";
echo "<link rel='stylesheet' type='text/css' href='in_app_style.css'>";
echo "<body>";


$connect = mysqli_connect($host_name, $user_name, $password, $database);
$sql_command = '';

$selected_sheet_id = $_GET["sid"];
if(!empty($_SESSION["device_id"]))
{
    $selected_user_id = $_SESSION["device_id"];
}
else
{
        echo "Not authorized";
        die();
	//$selected_user_id = $_SESSION["device_id"];
}


if (mysqli_connect_errno()) {
    echo "failed to connect to database";
    die('Failed to connect to MySQL: '.mysqli_connect_error().'');
}

if (!mysqli_connect_errno())
{
    $sheets_array = array();
    $questions_array = array();
    $options_array = array();

    $current_sheet_id = "-1";
    $current_question_id = "-1";

    $total_questions=0;
    
    //Get sheet attributes
    $sheetArray =[];
    $sheetQuery = "SELECT * FROM `Sheets` WHERE sheet_id = '$selected_sheet_id'";
    $sheetResult = mysqli_query($connect, $sheetQuery) or die("ERROR retrieving Sheet database entries " . mysqli_error($connect));
    $sheetValue = mysqli_fetch_assoc($sheetResult);
    $sheetEventId = $sheetValue["event_id"];

    //Get event attributes
    $eventArray =[];
    $eventQuery = "SELECT * FROM `Events` WHERE event_id = '$sheetEventId'";
    $eventResult = mysqli_query($connect, $eventQuery) or die("ERROR retrieving Event database entries " . mysqli_error($connect));
    $eventValue = mysqli_fetch_assoc($eventResult);

    //Fetch question IDs
    $questionIDs = [];
    $questionQuery = "SELECT question_id FROM `Questions` WHERE sheet_id = '$selected_sheet_id' ORDER BY question_id ASC";
    $questionResult = mysqli_query($connect, $questionQuery) or die("ERROR retrieving Question database entries " . mysqli_error($connect));
    while($row = mysqli_fetch_array($questionResult))
    {
        array_push($questionIDs, $row);
    }

    $questionLabels = [];
    $questionLabelQuery = "SELECT question_label,question_id FROM `Questions` WHERE sheet_id = '$selected_sheet_id' ORDER BY question_id ASC";
    $questionLabelResult = mysqli_query($connect, $questionLabelQuery) or die("ERROR retrieving Question database entries " . mysqli_error($connect));
    while ($row = mysqli_fetch_array($questionLabelResult))
    {
        array_push($questionLabels,$row);
    }

    $responseArray =[];
    $responseQuery = "SELECT `response_id`, `sheet_id` FROM `Responses` WHERE sheet_id = '$selected_sheet_id' AND device_id = '$selected_user_id' AND response_deleted = 0";
    $responseResult = mysqli_query($connect, $responseQuery) or die("ERROR retrieving Response database entries " . mysqli_error($connect));
    while($row = mysqli_fetch_assoc($responseResult))
    {
        array_push($responseArray,$row);
    }

    echo "<hr><div style='text-align:center'>"
    .$sheetValue["sheet_name"].
    "</div><hr>";

    $sql_command = "SELECT * FROM Sheets WHERE sheet_id = '$selected_sheet_id'";
    $sheet_result = mysqli_query($connect, $sql_command) or die("ERROR retrieving Sheets " . mysqli_error($connect));

    while($row = mysqli_fetch_assoc($sheet_result))
    {
        echo "<sheet>
        <input type='text' name='pas' style='display:none' value='noodlesoupofchicken'>
        <input type='text' name='uid' style='display:none' value='".$selected_user_id."'>
        <input type='text' name='sid' style='display:none' value='" .$row['sheet_id']. "'>";
        
        echo "<table id='responseTable'>
        ";
        
        $question_number= 0;
        $current_sheet_id = $row["sheet_id"];
        $sql_command = "SELECT * FROM Questions WHERE sheet_id = '$current_sheet_id'";
        $question_result = mysqli_query($connect, $sql_command) or die("ERROR retrieving Questions " . mysqli_error($connect));
        while ($row_q = mysqli_fetch_assoc($question_result))
        {
            echo "<tr>";
            $current_question_id = $row_q["question_id"];
            
            echo "<td>" .$row_q["question_label"]. ":<br>";
            $type = $row_q["question_type"];
			
            $sql_command = "SELECT * FROM Options WHERE question_id = '$current_question_id'";
            $option_result = mysqli_query($connect, $sql_command) or die("ERROR retrieving Options " . mysqli_error($connect));
            
            switch ($type)
            {
                case "s": build_select($option_result, $current_question_id); break;
                case "ss": build_select_many($option_result,$current_question_id); break;
                case "t": build_text($current_question_id); break;
                case "tt": build_text_long($current_question_id); break;
				case "yn": build_yes_no($current_question_id); break;
				case "l": build_list($current_question_id); break;
            }

            echo "</td>";
            
            $question_number += 1;
            $total_questions = $question_number + 1;
            echo "</tr>
            ";
        }
        echo "<tr><td><button onclick='submitForm()'>Submit</button></tr></td>";
        echo "</table>";
        echo "</sheet>";
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
    finishedLoading();
</script>