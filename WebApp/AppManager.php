<?php
session_start();
if (empty($_SESSION["manager_id"]))
{
	die("<script type='text/javascript'>window.location.href = '/AppManager/login_page.php'</script>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> 
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://fonts.googleapis.com/css?family=Didact Gothic' rel='stylesheet'>
    <link rel='stylesheet' type='text/css' href='events_style.css'>
</head>

<style>
</style>

</head>

<body>
    <header>
    Cornerstone FWB Church App Manager
    </header>

    <section>
        <?php include 'sidebar.php';?>
        <content>
            To the left are links to manage the app.
        </content>
    </section>
</body>