<?php
ini_set("session.cookie_httponly", 1);
session_start();
require 'database.php';
header("Content-Type: application/json");

//check user
if (!isset($_SESSION['user_id'])){
	echo json_encode(array(
		"success" => false
	));
	exit;
}else{
	$tag = htmlentities($_POST['eventtag']);
	$userid = $_SESSION['user_id'];

	//check the security token and see if it's a-ok
    if($_SESSION['token'] !== htmlentities($_POST['token'])){
	//if not, we kill
	die("Request forgery detected"); 
	}

	//check if input information is null
	if($tag == null){
		echo json_encode(array(
			"success" => false
		));
		exit;
	}
	
	$stmt = $mysqli->prepare("SELECT eventid, title, year, month, day FROM events WHERE tagname = ? AND userid = ?"); 
			
	//exit if query prep fails
	if(!$stmt){
		echo json_encode(array(
			"success" => false));
		exit;
	}
    //bind parameters
    $stmt->bind_param('si', $tag, $userid);
    $stmt->execute(); 
    $stmt->bind_result($eventid, $title, $year, $month, $day );

    $i = 0;
	$events = array();
	$str = "";
	while($stmt->fetch()){
		$events[$i] = "Event" . $eventid . ": " . $title .", " . $year . "-" . $month . "-" . $day ;
		$i++;
	}

	echo json_encode(array(
		"success" => true,
		"events" => $events,
        "eventnum" => $i
	));
	$stmt->close();
	exit;

}
?>