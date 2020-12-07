<?php

$connect = mysqli_connect($host_name, $user_name, $password, $database);
$sql_command = '';

if (mysqli_connect_errno()) {
    die('Failed to connect to MySQL: '.mysqli_connect_error().'');
}

$sql_command = "
SELECT *, DATE_FORMAT(event_begin,'%Y:%c:%d:%H:%i:%a') as event_begin,  DATE_FORMAT(event_end,'%Y:%c:%d:%H:%i:%a') as event_end
FROM Events
WHERE (event_begin >= DATE_FORMAT(NOW(),'%Y:%c:%d:%H:%i:%a') AND event_visible = '1')
ORDER BY event_begin ASC";

$result = mysqli_query($connect, $sql_command) or die("ERROR retrieving database entries " . mysqli_error($connect));
$return_string = "";
$return_array = array();

while($row = mysqli_fetch_assoc($result))
{
    $return_array[] = $row;
}



$sql_command = "
SELECT *
FROM Sheets
WHERE (sheet_visible ='1' AND (event_id = '-1' OR sheet_homepage = '1'))";

$result = mysqli_query($connect, $sql_command) or die("ERROR retrieving database entries " . mysqli_error($connect));

while($row = mysqli_fetch_assoc($result))
{
    $return_array[] = $row;
}
$return_string = json_encode($return_array);

echo $return_string;
mysqli_close($connect);

?>