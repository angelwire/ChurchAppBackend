<?php 
session_start();
if (empty($_SESSION["manager_id"]) or ($_SESSION["manager_alerts"]!="1" and $_SESSION["manager_unlimited"]!="1"))
{
	die("<script type='text/javascript'>window.location.href = '/AppManager/login_page.php'</script>");
}


$connect = mysqli_connect($host_name, $user_name, $password, $database);
$sql_command = 'SELECT event_id,event_name,event_begin FROM `Events` ORDER BY event_id ASC';

$additional_options = "";

if (!mysqli_connect_errno())
{
    $result = mysqli_query($connect, $sql_command);
	$current_option = 1;
    while($row = mysqli_fetch_array($result))
    {
        $additional_options .= "<input type='checkbox' value='" .$row['event_id']. "' name='box'>     ".$row['event_name']."</input><br>";
		$current_option+=1;
    }
}
?>


<!DOCTYPE html>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<link href='https://fonts.googleapis.com/css?family=Didact Gothic' rel='stylesheet'>
<link rel='stylesheet' type='text/css' href='events_style.css'>


<body>
	<script>
	function submit_alert()
	{
		var submissionForm = document.createElement("form");
		document.body.appendChild(submissionForm);
		submissionForm.setAttribute("action","send_notification.php");
		submissionForm.setAttribute("method","post");
		submissionForm.setAttribute("enctype","multipart/form-data");
		
		var jsonArray = {};
		var eventBoxes = document.getElementsByName('box');
		for (ii=0; ii<eventBoxes.length; ii+=1)
		{
			var currentBox = eventBoxes[ii];
			jsonArray[currentBox.value] = (currentBox.checked ? "1":"0");
		}
		
		var titleBoxValue = document.getElementById("messageTitle").value;
		var messageBoxValue = document.getElementById("messageText").value;
		
		addPostToForm(submissionForm,"eve", JSON.stringify(jsonArray));
		addPostToForm(submissionForm,"tit", titleBoxValue);	
		addPostToForm(submissionForm,"pas", "noodlesoupofchicken");	
		addPostToForm(submissionForm,"mes", messageBoxValue);
		submissionForm.submit();
	}

	function addPostToForm(element, name, value)
	{
		var node = document.createElement("input");
		node.setAttribute("type","text");
		node.setAttribute("name",name);
		node.setAttribute("value",value);
		node.setAttribute("style","display:none");

		element.appendChild(node);
	}
	
    </script>

    <header>
    Send Alert
    </header>
    <section>
    
    <?php
    include 'sidebar.php';
    ?>

    <content>
        <!--Done with php -->
        <form action='send_notification.php' method='post' enctype='multipart/form-data' style='font-weight: bold; font-size:16px'>
            <input type='textarea' name='pas' value='noodlesoupofchicken' hidden>
            Alert Title:<br><input type='textarea' name='tit' id='messageTitle' placeholder='Alert title here...'><br><br>
            Alert Message:<br><textarea type='textarea' name='mes' id='messageText' placeholder='Alert message here...' rows='3'></textarea><br><br>
            Alert Targets:<br>
					<div id='eventCheckboxes' style="line-height: 150%; font-size: 1.5em;">
                    <?php echo $additional_options ?>
                    <input type='checkbox' value='-1' name='box'>--All Users--</input>
					</div>
            <hr>
            <br>
			<button input='button' type='button' text='Post event' value='Send Alert' onclick='submit_alert();'>Send Alert</button>
            </form>
        </content>
    </section>
</body>