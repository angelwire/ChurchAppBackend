<?php
require_once("Mail.php");
$in_device = $_POST["did"];
$in_message = $_POST["mes"];
$in_password = $_POST["pas"];

$from = "Cornerstone App Manager <screenguy@cornerstoneowasso.com>";
$to = "App Support <wjosiahjones@gmail.com>";
$subject = "App Support Contact from device id:$in_device";
$body = "Below is the message:\n$in_message";
$headers = array(
    'From' => $from,
    'To' => $to,
    'Subject' => $subject
);
$smtp = Mail::factory('smtp', array(
        'host' => 'smtp.office365.com',
        'port' => '587',
        'auth' => true,
        'username' => 'screenguy@cornerstoneowasso.com',
        'password' => 'JosiahJones!'
    ));
$mail = $smtp->send($to, $headers, $body);


echo "<meta id='viewport' name=viewport content='width=device-width; initial-scale=1'>";
echo "<link href='https://fonts.googleapis.com/css?family=Didact Gothic' rel='stylesheet'>";
echo "<link rel='stylesheet' type='text/css' href='in_app_style.css'>";
echo "<body>";
echo "<div>";


if (PEAR::isError($mail)) {
	echo "There was an error";
} else {
 echo "Your message has been sent successfully";
}
echo "</body></div>";
?>
