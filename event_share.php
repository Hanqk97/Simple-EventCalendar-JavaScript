<?php
    ini_set("session.cookie_httponly", 1);
    session_start();
    require 'database.php';
    header("Content-Type: application/json"); //json derulo

    if (!isset($_SESSION['user_id'])){
        exit;
    }
    
    $eventid = (int)htmlentities(htmlentities($_POST['eventid']));
    $target_username = (string)htmlentities(htmlentities($_POST['username']));
    $userid = $_SESSION['user_id'];

    //check the security token and see if it's a-ok
    if($_SESSION['token'] !== htmlentities(htmlentities($_POST['token']))){
        //if not, we kill
        die("Request forgery detected"); 
    }

    
    //check if input information is null
	if($target_username == null || $eventid ==null){
		echo json_encode(array(
			"success" => false
		));
		exit;
	}

    $stmt = $mysqli->prepare("SELECT count(*), userid from users where username = ?");
    if(!$stmt){
        echo json_encode(array(
            "success" => false
        ));
        exit;
    }
    //bind params
    $stmt->bind_param('s', $target_username);
    //execute and close
    $stmt->execute();
    $stmt->bind_result($cnt, $target_userid);
    $stmt->fetch();
    $stmt->close();

    if($cnt==0){
        echo json_encode(array(
            "success" => false
        ));
        exit;
    }

    $stmt = $mysqli->prepare("SELECT count(*) from events where eventid = ?");
    if(!$stmt){
        echo json_encode(array(
            "success" => false
        ));
        exit;
    }
    //bind params
    $stmt->bind_param('i', $eventid);
    //execute and close
    $stmt->execute();
    $stmt->bind_result($cnt);
    $stmt->fetch();
    $stmt->close();

    if($cnt==0){
        echo json_encode(array(
            "success" => false
        ));
        exit;
    }

    $stmt = $mysqli->prepare("SELECT userid from events where eventid = ?");
    if(!$stmt){
        echo json_encode(array(
            "success" => false
        ));
        exit;
    }
    //bind params
    $stmt->bind_param('i', $eventid);
    //execute and close
    $stmt->execute();
    $stmt->bind_result($owner_userid);
    $stmt->fetch();
    $stmt->close();

    if($owner_userid!=$userid){
        echo json_encode(array(
            "success" => false
        ));
        exit;
    }

    $stmt = $mysqli->prepare("SELECT title, year, month, day, tagname from events where eventid = ?");
    if(!$stmt){
        echo json_encode(array(
            "success" => false
        ));
        exit;
    }
    //bind params
    $stmt->bind_param('i', $eventid);
    //execute and close
    $stmt->execute();
    $stmt->bind_result($event_title, $event_year, $event_month, $event_day, $event_tag);
    $stmt->fetch();
    $stmt->close();

	//prepare the query for inserting authorid, title, and story body into the stories table
    $stmt = $mysqli->prepare("INSERT INTO events (userid, title,  year, month, day, tagname) VALUES (?, ?, ?, ?, ?, ?)");
    if(!$stmt){
        echo json_encode(array(
            "message" => 4,
            "success" => false
        ));
        exit;
    }
    //bind params
    $stmt->bind_param('isiiis', $target_userid, $event_title, $event_year, $event_month, $event_day, $event_tag);
	//execute and close
	$stmt->execute();
	$stmt->close();
	echo json_encode(array(
		"success" => true
	));
	exit;
?>