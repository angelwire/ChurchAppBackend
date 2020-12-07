<?php session_start(); ?>
<meta id='viewport' name=viewport content='width=device-width; initial-scale=1'>

<?php
function build_select($question_option_array, $build_question_id)
{
	echo "<select type='select' id='type' name='answer' questionid='".$build_question_id."'>";
	foreach ($question_option_array as $row_o)
	{
		
		echo "<option value='".$row_o."'>".$row_o."</option>
		";
	}
	echo "</select>";
}

function build_text($place_holder,$build_question_id)
{
	echo "<input type='text' id='name' placeholder='".$place_holder."' questionid='".$build_question_id."' name='answer'>
	";
}

function build_text_long($place_holder, $build_question_id)
{
	echo "<input type='textarea' id='message' placeholder='".$place_holder."' rows='3'  name='answer' questionid='".$build_question_id."'>
	";
}

//Set up HTML stuff
echo "<!DOCTYPE html>
";

echo "<script type='text/javascript' src='control_forms_page.js'></script>";
echo "<link href='https://fonts.googleapis.com/css?family=Didact Gothic' rel='stylesheet'>";
echo "<link rel='stylesheet' type='text/css' href='in_app_style.css'>";
echo "<body>";

    $device_id = $_GET["did"];

    $current_sheet_id = "-1";
    $current_question_id = "-1";

    $total_questions=0;
    
    echo "<hr><div style='text-align:center'>Contact App Support</div><hr>";

    echo "<sheet>
    <input type='text' name='uid' style='display:none' value='".$device_id."'>";

    echo "<table id='responseTable'>
    ";
    
    echo "<tr>";
    echo "<td>Name:<br>";
    build_text("Name","name");
    echo "</td>";
    $question_number += 1;
    $total_questions = $question_number + 1;
    echo "</tr><br>";
    
    echo "<tr>";
    echo "<td>Reason for contact:<br>";
    $options_array = array(0=>"Suggestion",1=>"Error",2=>"Compliment");
    build_select($options_array,"reason");
    echo "</td>";
    $question_number += 1;
    $total_questions = $question_number + 1;
    echo "</tr><br>";
    
    echo "<tr>";
    echo "<td>Message:<br>";
    build_text_long("Message here","message");
    echo "</td>";
    $question_number += 1;
    $total_questions = $question_number + 1;
    echo "</tr><br>";
    echo "<tr><td><button onclick='submitToSupport()'>Submit</button></tr></td>";
    echo "</table>";
    echo "</sheet>";

echo "</body>";
?>

<script type="text/javascript">
    finishedLoading();
</script>