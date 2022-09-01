<?php
	ini_set("session.cookie_httponly", 1);
	session_start();
	require 'database.php';
	header("Content-Type: application/json"); //JSON DERULO

	if(!isset($_SESSION['user_id'])){
		exit;
	}

	$eventid = htmlentities($_POST['eventid']);
	$userid = $_SESSION['user_id'];

	//check the security token and see if it's a-ok
	if($_SESSION['token'] !== htmlentities($_POST['token'])){
		//if not, we kill
		die("Request forgery detected"); 
	}

	//check if input information is null
	if($eventid == null){
		echo json_encode(array(
			"success" => false,
			"message" => 1
		));
		exit;
	}

	$stmt = $mysqli->prepare("DELETE FROM events WHERE eventid=? and userid=? ");
	if(!$stmt){
		echo json_encode(array(
			"success"=>false,
			"message" => 2
		));
		exit;
	}
	$stmt->bind_param('ii',$eventid, $userid );
	$stmt->execute();
	$stmt->close();
	echo json_encode(array("success"=>true));
	exit;

?>