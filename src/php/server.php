<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
$app = new \Slim\App;


//Get All Users
$app->get('/user', function(Request $request, Response $respose)
{

	require_once ('C:\xampp\htdocs\slimapiCRUD\src\FileMakerCWP/FileMaker.php');

	$layout_name = 'CRUD';

	//New FileMaker Instantiation
	$fm = new FileMaker();
	$fm->setProperty('database', 'CRUD');
	$fm->setProperty('hostspec', '172.16.9.42');
	$fm->setProperty('username', 'admin');
	$fm->setProperty('password', 'mindfire');

	$findCommand = $fm->newFindAllCommand($layout_name);

	$result = $findCommand->execute();



	If (FileMaker::isError($result)) {
    echo "Error: " . $result->getMessage() . "\n";
    exit; }

    $records = $result->getRecords();

    $layout_object = $fm->getLayout($layout_name);
    $field_objects = $layout_object->getFields();

    foreach ($records as $record){
    	$recid = $record->getRecordId();
    	foreach ($field_objects as $field_object) 
    	{
			$record = $fm->getRecordById($layout_name, $recid);
			$field_name = $field_object->getName();
			$field_value = $record->getField($field_name);
			$field_value = htmlspecialchars($field_value);
			$field_value = nl2br($field_value);

			echo  $field_name.': '.$field_value.'<br>';
		}
		echo '<br>';
    }	
});








/*
	$name = "Niraj Prasad";
	$address = "Jatani";
	$dob = "11/12/1996";
	$email = "niraj@email.com";


$recid = (array_key_exists('id', $_GET)) ? htmlspecialchars($_GET['id']) : '';

	$record = $fm->getRecordById($layout_name, $recid);

	$connected = $fm->listLayouts();

	$layout_object = $fm->getLayout($layout_name);

	$field_objects = $layout_object->getFields();

	If(FileMaker::isError($connected)){
	        echo "Error:".$connected;
	}
	foreach ($field_objects as $field_object) {
		$record = $fm->getRecordById($layout_name, $recid);
		$field_name = $field_object->getName();
		$field_value = $record->getField($field_name);
		$field_value = htmlspecialchars($field_value);
		$field_value = nl2br($field_value);
		//JSONSetElement("{$field_name:$field_valued}";JSONObject);
		echo  $field_name.': '.$field_value.'<br>';
		//$myJSON;
	 }*/

