<?php
ini_set("session.cookie_httponly", 1);
session_start();
require 'database.php';
header("Content-Type: application/json");


// //Because you are posting the data via fetch(), php has to retrieve it elsewhere.
// $json_str = file_get_contents('php://input');
// //This will store the data into an associative array
// $json_obj = json_decode($json_str, true);

///check user
if (!isset($_SESSION['user_id'])){
	echo json_encode(array(
		"success" => false
	));
	exit;
}else{
	$event_year =  $_POST['year'];
	$event_month =  $_POST['month'];
	$event_day =  $_POST['day'];
	$stmt = $mysqli->prepare("SELECT eventid FROM events WHERE userid = ? AND year = ? AND month = ? AND day = ?"); 
			
	//exit if query prep fails
	if(!$stmt){
		echo json_encode(array(
			"success" => false));
		exit;
	}
    //bind parameters
    $stmt->bind_param('iiii', $_SESSION['user_id'], $event_year, $event_month, $event_day);
    $stmt->execute();
	$num = 0;
//
	$ids = array();
	$result = $stmt->get_result();
	while($row = $result->fetch_assoc()){
		$ids[$num] = $row["eventid"];
		$num ++;
	}
//
	// $ids = array();
    // $stmt->bind_result($event_id);
	// while($stmt->fetch()) {
	// 	$ids[$num] = $event_id;
	// 	$num ++;
	// }

	// $str_num = $num;
	echo json_encode(array(
		"success" => true,
		"eventnum" => $num,
		"eventid" => $ids
	));
	$stmt->close();
	exit;

}
?>