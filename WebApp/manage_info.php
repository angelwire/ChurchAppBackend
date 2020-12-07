<?php
session_start();
if (empty($_SESSION["manager_id"]) or ($_SESSION["manager_info"]!="1" and $_SESSION["manager_unlimited"]!="1"))
{
	die("<script type='text/javascript'>window.location.href = '/AppManager/login_page.php'</script>");
}
echo "<!DOCTYPE html>";
echo "<html lang='en'>";
echo "<head>";
    echo "<meta charset='UTF-8'>";
    echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";
    echo "<link href='https://fonts.googleapis.com/css?family=Didact Gothic' rel='stylesheet'>";
    echo "<link rel='stylesheet' type='text/css' href='events_style.css'>";
echo "</head>";


echo "<body>
    <header>
    Manage Church Info
    </header>
    <section>";
    
include 'sidebar.php';

echo "<content>";

$connect = mysqli_connect($host_name, $user_name, $password, $database);
$sql_command = '';



if (!mysqli_connect_errno())
{
$sql_command = "SELECT extra_info FROM " . $database . ".`Extra` WHERE extra_type='church_info';";
$result = $connect->query($sql_command);
if ($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc()) 
        {
            $t_area = $row["extra_info"];
        }
    }
}
?>

<!--Done with php -->
<form action='edit_info.php' method='post' enctype='multipart/form-data' style='font-weight: bold; font-size:16px'>
<input type='textarea' name='pas' value='noodlesoupofchicken' hidden>
<input type="button" value="Enable Editing" onclick="var dis = document.getElementById('infoText').disabled; document.getElementById('infoText').disabled=!dis; value= dis ? 'Disable Editing': 'Enable Editing';"><br>
Church Info:<br><textarea type='textarea' name='inf' id='infoText' placeholder='Church Info here...' rows='8' disabled><?php echo $t_area; ?></textarea><br><br>
<hr>
<br><input type='submit' text='Post event' value='Save'>
</form>

</content></section></body>