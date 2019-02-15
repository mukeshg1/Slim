<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
$app = new \Slim\App;


//Get All Users
$app->get('/user', function(Request $request, Response $respose)
{

	require_once ('C:\xampp\htdocs\FileMakerCWP/FileMaker.php');

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
	$arr2 = array();
	$arr3 = array();
	$arr4 = array();

    foreach ($records as $record){
    	$recid = $record->getRecordId();
    	foreach ($field_objects as $field_object) 
    	{
			$record = $fm->getRecordById($layout_name, $recid);
			$field_name = $field_object->getName();
			$field_value = $record->getField($field_name);
			$field_value = htmlspecialchars($field_value);
			$field_value = nl2br($field_value);

			$arr1 = array($field_name=>$field_value);
			$arr2 = array_merge($arr2,$arr1);
			//echo  $field_name.': '.$field_value.'<br>';
		}
		$arr3 = array_merge($arr3,$arr2);
		echo json_encode($arr3);
	}
	//return json_encode($arr3);
		
});

//Add user to the database
$app->post('/user/add', function(Request $request, Response $respose)
{

	require_once ('C:\xampp\htdocs\FileMakerCWP/FileMaker.php');

	$layout_name = 'CRUD';

	//New FileMaker Instantiation
	$fm = new FileMaker();
	$fm->setProperty('database', 'CRUD');
	$fm->setProperty('hostspec', '172.16.9.42');
	$fm->setProperty('username', 'admin');
	$fm->setProperty('password', 'mindfire');

	//Receive datas
	$name = $request['name'];
	$address = $request['address'];
	$dob = $request['dob'];
	$email = $request['email'];


	$fmquery = $fm->newAddCommand($layout_name);
	$fmquery->setField("name", $name);
	$fmquery->setField("address", $address);
	$fmquery->setField("dob", $dob);
	$fmquery->setField("email", $email);

	$result = $fmquery->execute();
	if(FileMaker::isError($result))
	{
		echo "Error".$result;
	}
	else
	{
		echo "User Added";		
	}
});


//Get a single user with id
$app->get('/user/{recid}', function(Request $request, Response $respose, array $args)
{

	require_once ('C:\xampp\htdocs\FileMakerCWP/FileMaker.php');

	$layout_name = 'CRUD';

	//New FileMaker Instantiation
	$fm = new FileMaker();
	$fm->setProperty('database', 'CRUD');
	$fm->setProperty('hostspec', '172.16.9.42');
	$fm->setProperty('username', 'admin');
	$fm->setProperty('password', 'mindfire');


	$recid = $args['recid'];
    $record = $fm->getRecordById($layout_name, $recid);

	$connected = $fm->listLayouts();

	$layout_object = $fm->getLayout($layout_name);

	$field_objects = $layout_object->getFields();

	If(FileMaker::isError($connected)){
	        echo "Error:".$connected;
	        exit;
	}
	IF(FileMaker::isError($record)){
		echo "Error, record doesn't exist.";
		exit;
	}
	$arr2 = array();
	foreach ($field_objects as $field_object) {
		$field_name = $field_object->getName();
		$field_value = $record->getField($field_name);
		$field_value = htmlspecialchars($field_value);
		$field_value = nl2br($field_value);
		
		$arr1 = array($field_name=>$field_value);
		$arr2 = array_merge($arr2,$arr1);
		//echo  $field_name.': '.$field_value.'<br>';
	 }
	 return json_encode($arr2);
});



//Delete a record by Id
$app->post('/user/delete/{recid}', function(Request $request, Response $respose, array $args)
{

	require_once ('C:\xampp\htdocs\FileMakerCWP/FileMaker.php');

	$layout_name = 'CRUD';

	//New FileMaker Instantiation
	$fm = new FileMaker();
	$fm->setProperty('database', 'CRUD');
	$fm->setProperty('hostspec', '172.16.9.42');
	$fm->setProperty('username', 'admin');
	$fm->setProperty('password', 'mindfire');


	$recid = $args['recid'];
    $record = $fm->getRecordById($layout_name, $recid);

	$connected = $fm->listLayouts();

	$layout_object = $fm->getLayout($layout_name);

	$field_objects = $layout_object->getFields();

	If(FileMaker::isError($connected)){
	        echo "Error:".$connected;
	        exit;
	}
	$fmquery = $fm->newDeleteCommand($layout_name,$recid);
	$result = $fmquery->execute();
	IF(FileMaker::isError($result)){
		echo $result->getMessage();
		exit;
	}
	else{
		return 'Deleted';
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

