<?php

$connect = mysqli_connect($host_name, $user_name, $password, $database);
$sql_command = '';

if (mysqli_connect_errno()) {
    echo "-1";
}
else
{
    $sql_command = "SELECT * FROM `Extra` WHERE extra_id='0'";
    $question_result = $connect->query($sql_command);
    
    while ($row_q = mysqli_fetch_assoc($question_result))
    {
        echo $row_q["extra_info"];
    }
    
    $connect->close();    
}

?>