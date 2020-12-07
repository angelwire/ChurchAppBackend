<?php

/*
Gets the forms (aka sign-up sheets) for the event ID passed through $_GET["eid"]
Data is returned via JSON in the following way

{
    sheet1data
    questions:
    [
        {
        question1data
        options:
            [
                option1 data
                option2 data
                option3 data
                ...
            ]
        },
        {
        question2
        options2:
            [
                option1 data
                option2 data
                option3 data
                ...
            ]
        }
        question3...
    ]
    sheet2 data...
}

*/

$connect = mysqli_connect($host_name, $user_name, $password, $database);
$sql_command = '';

$selected_sheet_id = $_GET["sid"];
$selected_event_id = $_GET["eid"];
$in_password = $_GET["pas"];
    if (mysqli_connect_errno()) {
        die('Failed to connect to MySQL: '.mysqli_connect_error().'');
    }

if (!mysqli_connect_errno())
{
    $sheets_array = array();
    $questions_array = array();
    $options_array = array();

    $current_sheet_id = "-1";
    $current_question_id = "-1";

    if (empty($_GET["eid"]))
    {
        $sql_command = "SELECT * FROM Sheets WHERE sheet_id = '$selected_sheet_id' AND sheet_visible='1' LIMIT 1 ";
    }
    else
    {
        $sql_command = "SELECT * FROM Sheets WHERE event_id = '$selected_event_id' AND sheet_visible='1' LIMIT 1 ";    
    }
    $sheet_result = mysqli_query($connect, $sql_command) or die("ERROR retrieving Sheets " . mysqli_error($connect));

    while($row = mysqli_fetch_assoc($sheet_result))
    {
        $sheets_array = $row;
        $current_sheet_id = $row["sheet_id"];
        $sheets_array += array("sheet_questions" => array());
        
        $sql_command = "SELECT * FROM Questions WHERE sheet_id = '$current_sheet_id'";
        $question_result = mysqli_query($connect, $sql_command) or die("ERROR retrieving Questions " . mysqli_error($connect));
        while ($row_q = mysqli_fetch_assoc($question_result))
        {
            $current_question_index = count($sheets_array["sheet_questions"]);
            array_push($sheets_array["sheet_questions"], $row_q);
            $current_question_id = $row_q["question_id"];
            
            $sheets_array["sheet_questions"][$current_question_index] += array("question_options" => array());

            $sql_command = "SELECT * FROM Options WHERE question_id = '$current_question_id'";
            $option_result = mysqli_query($connect, $sql_command) or die("ERROR retrieving Options " . mysqli_error($connect));
            while ($row_o = mysqli_fetch_assoc($option_result))
            {
                array_push($sheets_array["sheet_questions"][$current_question_index]["question_options"], $row_o);
            }
        }
    }
}
else
{
    echo "There was a problem retrieving form. Please contact the app support technician and include this error message:
            <br><error>GET_FORM: Database Connection Error</error>";
}

$return_string = json_encode($sheets_array);

echo $return_string;

mysqli_close($connect);

?>