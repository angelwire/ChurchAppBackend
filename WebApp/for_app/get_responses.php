<?php

/*
Returns the 20 most recent form results (if the form is still visible)
It's formed in a JSON like so:

	[{"response_id":"id", "sheet_name":"name", "sheet_answers":[{"label":"answer"},{"label":["answer2","answer3"]}]},
	 {"sheet_name":"name2", "sheet_answers":[{"label":"answer"},{"label":["answer2","answer3"]}]}]
	 
warnings: the array of answers could be empty, or could have just a single answer,
in both of those cases it will still be treated like an array with size  0 and 1 respectively
*/

$connect = mysqli_connect($host_name, $user_name, $password, $database);

$in_device = $_POST["did"];
$in_password = $_POST["pas"];

if (strcmp($in_password,$check_password) != 0) { exit("-p"); }
if (mysqli_connect_errno()) { exit("-22"); }

//Get responses belonging to the current device
$responsesArray = array();
$responseQuery = "SELECT * FROM `Responses` WHERE device_id = '$in_device' LIMIT 20";
$responseResults = mysqli_query($connect, $responseQuery) or die("-28".mysqli_error($connect));

$responseObject = array();

while ($response = mysqli_fetch_assoc($responseResults))
{
	//How many responses there are
	$responseCount = count($responseObject);
	$responseObject[$responseCount] = new stdClass();
	
	//get sheet name
	$sheetNameQuery = "SELECT sheet_name,sheet_id FROM `Sheets`
	WHERE sheet_id='{$response["sheet_id"]}'";
	$sheetNameResults = mysqli_query($connect, $sheetNameQuery) or die("-37".mysqli_error($connect));
	
	//Get sheet values
	$fetchedSheetName = mysqli_fetch_assoc($sheetNameResults)["sheet_name"];
	$fetchedSheetId = mysqli_fetch_assoc($sheetNameResults)["sheet_id"];
	
	//Add the sheet name to the JSON object
	$responseObject[$responseCount]->sheet_name = $fetchedSheetName;
	
	//Add the resposne id to the JSON object
	$responseObject[$responseCount]->response_id = $response["response_id"];
	
	//Now get the questions and their answers
	$questionQuery = "SELECT question_label,question_id FROM `Questions`
	WHERE sheet_id='{$response["sheet_id"]}'";
	$questionResults = mysqli_query($connect, $questionQuery) or die("-52".mysqli_error($connect));
	$questionsArray = array();
	
	//For each question get every answer (as long as they come from the right response)
	while ($question = mysqli_fetch_assoc($questionResults))
	{
		//This will hold the question label as well as the array of answers
		$questionObject = new stdClass();
		
		//get answers
		$answerQuery = "SELECT * FROM `Answers`
		WHERE question_id='{$question["question_id"]}'
		AND response_id ='{$response["response_id"]}'";
		$answerResults = mysqli_query($connect, $answerQuery) or die("-43".mysqli_error($connect));
		
		$answerArray = array(); //This array holds all the answers
		while ($answer = mysqli_fetch_assoc($answerResults)) //For each anser
		{
			//Add the answer to the answer array
			array_push($answerArray,$answer["answer_value"]);
		}
		//Set the question's array to the answer array that was just built
		$questionObject->{$question["question_label"]} = $answerArray;
		array_push($questionsArray,$questionObject);
	}
	//Add the answers to the response
	$responseObject[$responseCount]->sheet_answers=$questionsArray; 
	
	//Add the response object to the responses array
	array_push($responsesArray, $responseObject[$responseCount]);
}
$json_string = json_encode($responsesArray);
if ($json_string != FALSE)
{
	echo $json_string;
}
else
{
	echo "-1";
}
?>
