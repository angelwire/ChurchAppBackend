<?php
session_start();
if (empty($_SESSION["manager_id"]) or ($_SESSION["manager_sheets"]!="1" and $_SESSION["manager_unlimited"]!="1"))
{
    die("<script type='text/javascript'>window.location.href = '/AppManager/login_page.php'</script>");
}

$connect = mysqli_connect($host_name, $user_name, $password, $database);
$sql_command = '';

echo "<link href='https://fonts.googleapis.com/css?family=Didact Gothic' rel='stylesheet'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";
echo "<link rel='stylesheet' type='text/css' href='events_style.css'>";

echo "<body>
    <header>
    Create Sign-Up Sheet*
    </header>
    <section>";
    
include 'sidebar.php';

echo "<content>";

if (mysqli_connect_errno()) {
    $error_value = mysqli_connect_error();
    echo "There was a problem adding the sign-up sheet. Please try to create the sign-up sheet again, if this problem persists, please email the app support technician and include this error message:
    <br><error> Failed to connect to MySQL:". $error_value . "</error>";
    exit();
}

$check_password = "noodlesoupofchicken";
$in_password = $_POST["pas"];
$in_name = $connect->real_escape_string($_POST["nam"]);
$in_description = $connect->real_escape_string($_POST["des"]);
$in_event = $connect->real_escape_string($_POST["eve"]);
$in_response = $connect->real_escape_string($_POST["res"]);
$in_homepage = $connect->real_escape_string($_POST["hom"]);

$in_question = json_decode($_POST["que"]);

if (!mysqli_connect_errno())
{
    $option_success = FALSE;
    $question_success = FALSE;
    $sign_up_success = FALSE;
    
    $error_message = "none";
    $connect->autocommit(FALSE);
    if (strcmp($check_password,$in_password) == 0)
    {
        mysqli_begin_transaction($connect);
        $sign_up_query = "INSERT INTO " . $database . ".`Sheets` (`sheet_id`, `event_id`, `sheet_name`, `sheet_description`, `sheet_response`, `sheet_homepage`) VALUES (NULL,'$in_event','$in_name', '$in_description', '$in_response', '$in_homepage');";
        $sign_up_success = $connect->query($sign_up_query);
        $new_sheet_id = mysqli_insert_id($connect);
        if ($sign_up_success == false)
        {
            $error_message = "Sign-Up query error: " .$sign_up_query. " " .$connect->error;
            echo "There was a problem adding the sign-up sheet. Please try to create the sign-up sheet again, if this problem persists, please email the app support technician and include this error message:
            <br><error>Sign-Up Sheet creation failed: $error_message </error>";
            exit();
        }
        
        foreach ($in_question as $question)
        {    
            $do_label = $connect->real_escape_string($question->{"label"});
            $do_type = $connect->real_escape_string($question->{"type"});
            $do_autofill = $connect->real_escape_string($question->{"autofill"});
            $question_query = "INSERT INTO " . $database . "
            .`Questions` (`question_id`, `sheet_id`, `question_label`, `question_type`, `question_can_autofill`)
            VALUES (NULL, '$new_sheet_id', '$do_label','$do_type','$do_autofill');";
            $question_success = $connect->query($question_query);
            $new_question_id = $connect->insert_id;
            
            if ($question_success == false)
            {
                $error_message = "Question query error: " .$question_query;
                break;
            }
            
            if ($question->{"options"} != "")
            {
				$options= explode(",",$question->{"options"});
				$limits = explode(",",$question->{"limits"});
                for($ii=0; $ii<sizeof($options); $ii+=1)
                {
                    $option = $connect->real_escape_string($options[$ii]);
					$limit = $connect->real_escape_string($limits[$ii]);
                    $option_query = "INSERT INTO " .$database. ".`Options` (`option_id`, `question_id`, `option_value`, `option_limit`) VALUES (NULL,'$new_question_id','$option', '$limit');";
                    $option_success = $connect->query($option_query);
                    if ($option_success == FALSE)
                    {
                        $error_message = "Option query error: " .$option_query;
                        break;
                    }
                }
            } 
            else
            {
                $option_success =TRUE;
            }
        }
        
        if (($sign_up_success == true) && ($question_success == true) && ($option_success == true))
        {
            $connect->commit();
            echo "Successfully Added " .$in_name;
        }   else 
        {
            $error_message = $error_message. "  " .mysqli_error($connect);
            $connect->rollback();
            echo "There was a problem adding the sign-up sheet. Please try to create the sign-up sheet again, if this problem persists, please email the app support technician and include this error message:
            <br><error> Rollback Error: <br>
            Sign Up Success: $sign_up_success <br>
            Question Success: $question_success <br>
            Option Success: $option_success <br>
            "  . $error_message . " - " .$connect->error.
            "</error>";
        }
        $connect->autocommit(TRUE);
        $connect->close();    
    }
    else
    {
        echo "There was a problem adding the sign-up sheet. Please try to create the sign-up sheet again, if this problem persists, please email the app support technician and include this error message:
            <br><error>Validation Error</error>";
    }
}
echo "</content></section></body>";

function cleanValue($in)
{
    
}

?>