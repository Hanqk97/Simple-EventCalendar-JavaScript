<?php
ini_set("session.cookie_httponly", 1);
session_start();
require 'database.php';
header("Content-Type: application/json");

///check user
if (!isset($_SESSION['user_id'])){
	echo json_encode(array(
		"success" => false
	));
	exit;
}else{
	$eventid = htmlentities($_POST['eventid']);
	
	//check the security token and see if it's a-ok
    if($_SESSION['token'] !== htmlentities($_POST['token'])){
	//if not, we kill
	die("Request forgery detected"); 
	}

	//check if input information is null
	if($eventid == null){
		echo json_encode(array(
			"success" => false
		));
		exit;
	}

	$stmt = $mysqli->prepare("SELECT title, year, month, day, tagname FROM events WHERE eventid = ?"); 
			
	//exit if query prep fails
	if(!$stmt){
		echo json_encode(array(
			"success" => false));
		exit;
	}
    //bind parameters
    $stmt->bind_param('i', $eventid);
    $stmt->execute(); 
    $stmt->bind_result($title, $year, $month, $day, $tag);
	$stmt->fetch();
    $datetime = $year . "-" . $month . "-" . $day ;
	echo json_encode(array(
		"success" => true,
		"title" => $title,
        "datetime" => $datetime,
        "tag" => $tag,
		
	));
	$stmt->close();
	exit;
}
?>