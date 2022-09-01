<?php
ini_set("session.cookie_httponly", 1);
session_start();
require 'database.php';
header("Content-Type: application/json"); //json derulo

if (!isset($_SESSION['user_id'])){
	exit;
}
else {

	//Filter the inputs 
	$event_id = (int)htmlentities($_POST['eventid']);
	$event_title = (string) htmlentities($_POST['title']);
	$tag = htmlentities($_POST['tag']);
	if(isset($tag)){
		$event_tag = (string) htmlentities($_POST['tag']);
	}
	$event_day =  htmlentities($_POST['day']);
	$event_month =  htmlentities($_POST['month']);
	$event_year =  htmlentities($_POST['year']);
	
	//check the security token and see if it's a-ok
    if($_SESSION['token'] !== htmlentities($_POST['token'])){
	//if not, we kill
	die("Request forgery detected"); 
	}

	//check if input information is null
	if($event_id == null || $event_title == null || $event_tag ==null || $event_day == null || (INT)$event_day > 31){
		echo json_encode(array(
			"success" => false
		));
		exit;
	}

	//prepare the query for inserting authorid, title, and story body into the stories table
	$stmt = $mysqli->prepare("UPDATE events SET userid = ?, title = ?, year = ?, month = ?, day = ?, tagname = ? where eventid=?");
	if(!$stmt){
		echo json_encode(array(
			"success" => false
		));
		exit;
	}
	//bind params
	$stmt->bind_param('isiiisi', $_SESSION['user_id'], $event_title, $event_year, $event_month, $event_day, $event_tag, $event_id);
	
	//execute and close
	$stmt->execute();
	$stmt->close();
	echo json_encode(array(
		"success" => true
	));
	exit;
}
?>