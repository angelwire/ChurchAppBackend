<?php
//Deletes the response (and all associated database entries)

$connect = mysqli_connect($host_name, $user_name, $password, $database);
$sql_command = '';

$in_password = $_POST["pas"];

$in_response = $connect->real_escape_string($_POST["rid"]);

$result["status"] = -1;
$result["message"] = "ERROR:NOTING HAPPENED";

if (!mysqli_connect_errno())
{   
    if (strcmp($check_password,$in_password) == 0)
    {
        $sql_command = "DELETE FROM " . $database . ".`Responses`
        WHERE response_id = '$in_response'";
        $response_success = $connect->query($sql_command);
        $connect->close();
        if ($response_success)
        {
            $result["status"] = 1;
            $result["message"] = "Good";
        }
        else
        {
            $result["status"] = -1;
            $result["message"] = "ERROR REMOVING RESPONSE: ".$connect->error();
        }
    }
    else
    {
        $result["status"] = -1;
        $result["message"] = "ERROR REMOVING RESPONSE: bad password";
    }
} else
{
    $result["status"] = -1;
    $result["message"] = "ERROR REMOVING RESPONSE: bad connection";
}

echo json_encode($result);
?>