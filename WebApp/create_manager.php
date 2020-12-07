<?php
session_start();
if (empty($_SESSION["manager_id"]) or $_SESSION["manager_unlimited"]!="1")
{
	die("<script type='text/javascript'>window.location.href = '/AppManager/login_page.php'</script>");
}
require_once("Mail.php");

$connect = mysqli_connect($host_name, $user_name, $password, $database);
$sql_command = '';

echo "<meta charset='UTF-8'>";
echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";
echo "<link href='https://fonts.googleapis.com/css?family=Didact Gothic' rel='stylesheet'>";
echo "<script type='text/javascript' src='manage_managers.js'></script>";
echo "<link rel='stylesheet' type='text/css' href='events_style.css'>";

echo "<body>
    <header>
    Control Manager Access
    </header>
    <section>";
    
include 'sidebar.php';
echo "<content>";


$generated_password = generate_password();
$in_name = $_POST["nam"];
$in_address = $_POST["add"];
$in_events = $_POST["eve"];
$in_sheets = $_POST["she"];
$in_alerts = $_POST["ale"];
$in_info   = $_POST["inf"];
$in_unlimited = $_POST["unl"];

$from = "Cornerstone App Manager <screenguy@cornerstoneowasso.com>";
$to = "$in_name <$in_address>";
$subject = "Cornerstone Owasso App Manager Invitation";
$body = "Hello $in_name. \nYou have been invited to become an app manager for the Cornerstone Owasso Church App. Please click on the link below and use the access token provided to create an account. \n\nAccess Token: $generated_password \nLink: www.angelwirestudio.com/HTTPTEST/login_create_page.php";
$headers = array(
    'From' => $from,
    'To' => $to,
    'Subject' => $subject
);
$smtp = Mail::factory('smtp', array(
        'host' => '',
        'port' => '',
        'auth' => true,
        'username' => '',
        'password' => ''
    ));
$mail = $smtp->send($to, $headers, $body);
if (PEAR::isError($mail)) {
	echo "<error>";
	echo "Unable to send email. The manager has not been added. Please try again.";
    echo('<p>' . $mail->getMessage() . '</p>');
	echo "</error>";
} else {
	
	if (!mysqli_connect_errno())
	{
		$sql_command = "INSERT INTO `Managers` (`manager_id`, `manager_name`, `manager_password`, `manager_events`, `manager_sheets`, `manager_alerts`, `manager_info`, `manager_unlimited`, `manager_hash`, `manager_address`)
										VALUES (NULL,         '$in_name',   '$generated_password',  $in_events,     $in_sheets,   $in_alerts,     $in_info,      '$in_unlimited'   , 0,            '$in_address');";
		$result = $connect->query($sql_command);
		if ($result === true)
		{
		echo "Successfully added " .$in_name. " as a manager. An email has been sent to " .$in_address. " with instructions on how to log in.";
        }    else
        {
            echo "There was a problem adding the manager. Please try to add the manager again, if this problem persists, please email the app support technician and include this error message:
            <br><error>" . $connect->error .
            "</error>";
        }
		
	}
}
 
function generate_password()
{
	return bin2hex(openssl_random_pseudo_bytes(3));
}



echo "</content></section></body>";
?>
