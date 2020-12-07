<?php

/*
responses should be submitted with this scheme
$_Post['sid'] //Sheet id
$_Post['did'] //Device id
{<question ID>:"<answer>", <question2 id>:["<answer2>","<answer3>","<answer4>"]}

the JSON string could also look like this:
{<question ID>:"<answer>", <question2 id>:"<answer2>", <question2 id>:"<answer3>", <question2 id>:"<answer4>"}
 
responds with json{"status":"1","message":"sheet response message"} or
 * json{"status":"-1","message":"error message with info"}
*/

$connect = mysqli_connect($host_name, $user_name, $password, $database);
$sql_command = '';

$in_password = $_POST["pas"];


$in_sheet = $connect->real_escape_string($_POST["sid"]);
$in_device = $connect->real_escape_string($_POST["did"]);

$str = preg_replace(
    array(
        '/\x00/', '/\x01/', '/\x02/', '/\x03/', '/\x04/',
        '/\x05/', '/\x06/', '/\x07/', '/\x08/', '/\x09/', '/\x0A/',
        '/\x0B/','/\x0C/','/\x0D/', '/\x0E/', '/\x0F/', '/\x10/', '/\x11/',
        '/\x12/','/\x13/','/\x14/','/\x15/', '/\x16/', '/\x17/', '/\x18/',
        '/\x19/','/\x1A/','/\x1B/','/\x1C/','/\x1D/', '/\x1E/', '/\x1F/'
    ), 
    array(
        "\u0000", "\u0001", "\u0002", "\u0003", "\u0004",
        "\u0005", "\u0006", "\u0007", "\u0008", "\u0009", "\u000A",
        "\u000B", "\u000C", "\u000D", "\u000E", "\u000F", "\u0010", "\u0011",
        "\u0012", "\u0013", "\u0014", "\u0015", "\u0016", "\u0017", "\u0018",
        "\u0019", "\u001A", "\u001B", "\u001C", "\u001D", "\u001E", "\u001F"
    ), 
    $_POST["qna"]
);

$in_response = json_decode($str, true);
//echo $_POST["qna"];


$json_response["status"] = -1;
$json_response["message"] = "Response received, thank you!";

if (!mysqli_connect_errno())
{
    //get the submission response to echo if the form is submitted properly
    $message_query = "SELECT sheet_response, sheet_id FROM " . $database . ".`Sheets` WHERE `sheet_id`='$in_sheet'";
    $return_message = $connect->query($message_query);
    while($message = mysqli_fetch_assoc($return_message))
    {
        $json_response["message"] = $message["sheet_response"];
    }
	
    $response_success = FALSE;
    $answer_success = FALSE;
    
    $error_message = "none";
    
    $connect->autocommit(FALSE);
    if (strcmp($check_password,$in_password) == 0)
    {
        mysqli_begin_transaction($connect);
        $response_query = "INSERT INTO " . $database . ".`Responses` (`response_id`, `sheet_id`, `device_id`) VALUES (NULL,'$in_sheet','$in_device');";
        $response_success = $connect->query($response_query);
        $new_response_id = mysqli_insert_id($connect);
        
        foreach ($in_response as $this_response)
        {    
            $do_question = array_keys($in_response, $this_response)[0];
            $do_response = $new_response_id;
            $do_answer = $this_response;
            if (is_array($do_answer))
            {
                foreach ($do_answer as $final_answer)
                {            
                    $answer_query = "INSERT INTO " . $database . ".`Answers` (`answer_id`, `question_id`, `response_id`, `answer_value`) VALUES (NULL, '$do_question', '$do_response', '$final_answer');";
                    $answer_success = $connect->query($answer_query);
                    $new_question_id = $connect->insert_id;
                    
                    if ($answer_success == false)
                    {
                        $error_message = mysqli_error($connect);
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
                    $error_message = mysqli_error($connect);
                    break;
                }
            }
            
        }
        
        if (($response_success == true) && ($answer_success == true))
        {
            $connect->commit();
            $json_response["status"] = "1"; 
            echo json_encode($json_response);
        }   else 
        {
            $json_response["message"] = "Query error: response_success is ".$response_success
                    ." and answer_success is ".$answer_success." and the message is:".$error_message;
            $json_response["status"] = "-4"; 
            $connect->rollback();
            echo json_encode($json_response);
        }
        $connect->autocommit(TRUE);
        $connect->close();    
    }
    else
    {
            $json_response["message"] = "Validation error";
            $json_response["status"] = "-5";
            echo json_encode($json_response);
    }
}
?>
