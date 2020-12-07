<?php
session_start();

//This is a deprecated page that uses a web page to submit a form response -- don't use probably

/*
responses should be submitted with this scheme
$_Post['sid'] //Sheet id
$_Post['did'] //Device id
{<question ID>:"<answer>", <question2 id>:["<answer2>","<answer3>","<answer4>"]}

the JSON string could also look like this:
{<question ID>:"<answer>", <question2 id>:"<answer2>", <question2 id>:"<answer3>", <question2 id>:"<answer4>"}
*/


$connect = mysqli_connect($host_name, $user_name, $password, $database);
$sql_command = '';
echo "<meta id='viewport' name=viewport content='width=device-width; initial-scale=1'>";
echo "<link href='https://fonts.googleapis.com/css?family=Didact Gothic' rel='stylesheet'>";
echo "<link rel='stylesheet' type='text/css' href='in_app_style.css'>";
echo "<body>";
echo "<div>";


$in_sheet = $connect->real_escape_string($_POST["sid"]);
if (!empty($_SESSION["device_id"]))
{
    $in_user = $_SESSION["device_id"];
}
else
{
    echo "Session not authorized. Please return to the app and try again to fill out the form.";
    die();
}
$in_response = json_decode($_POST["qna"], true);

$form_response = "";

if (!mysqli_connect_errno())
{
	$form_query = "SELECT * FROM Sheets WHERE sheet_id = '$in_sheet'";
	$response_response = $connect->query($form_query);
	$form_response = mysqli_fetch_array($response_response)["sheet_response"];
		
    $response_success = FALSE;
    $answer_success = FALSE;
    
    $error_message = "none";
    
    $connect->autocommit(FALSE);
    if (strcmp($check_password,$in_password) == 0)
    {
        mysqli_begin_transaction($connect);
        $response_query = "INSERT INTO " . $database . ".`Responses` (`response_id`, `sheet_id`, `device_id`) VALUES (NULL,'$in_sheet','$in_user');";
        $response_success = $connect->query($response_query);
        $new_response_id = mysqli_insert_id($connect);
        if ($response_success == false)
        {
            $error_message = "Response query error, please try again";
            echo "Error, please try again. If this problem persists please contact the app support technician.";
            exit();
        }
        
        foreach ($in_response as $this_response)
        {    
            $do_question = array_keys($this_response)[0];
            $do_response = $new_response_id;
            $do_answer = $this_response[$do_question];
            
            if (is_array($do_answer))
            {
                foreach ($do_answer as $final_answer)
                {            
                    $answer_query = "INSERT INTO " . $database . ".`Answers` (`answer_id`, `question_id`, `response_id`, `answer_value`) VALUES (NULL, '$do_question', '$do_response', '$final_answer');";
                    $answer_success = $connect->query($answer_query);
                    $new_question_id = $connect->insert_id;
                    
                    if ($answer_success == false)
                    {
                        $error_message = "Answer query error, please try again. If this problem persists please contact the app support technician.";
                        break;
                    }
                }
            }
            else
            {
                $final_answer = $do_answer;
                $answer_query = "INSERT INTO " . $database . ".`Answers` (`answer_id`, `question_id`, `response_id`, `answer_value`) VALUES (NULL, '$do_question', '$do_response', '$final_answer');";
                $answer_success = $connect->query($answer_query);
                $new_question_id = $connect->insert_id;
                
                if ($answer_success == false)
                {
                    $error_message = "Answer query error, please try again. If this problem persists please contact the app support technician.";
                    break;
                }
            }
            
        }
        
        if (($response_success == true) && ($answer_success == true))
        {
            $connect->commit();
            echo "Form submitted successfully.<hr>";
			echo $form_response;
        }   else 
        {
            $error_message = $error_message. "  " .mysqli_error($connect);
			echo "<error>$error_message</error>";
            $connect->rollback();
            echo "Rolled back due to error. If this problem persists please contact the app support technician.";
        }
        $connect->autocommit(TRUE);
        $connect->close();    
    }
    else
    {
        echo "Validation Error. If this problem persists please contact the app support technician.";
    }
}
echo "</div>";
echo "</body>";
session_destroy();
?>