<?php
    ini_set("session.cookie_httponly", 1);
	session_start();
	require 'database.php';
    //check to make sure variables are set
        //filter inputs
	header("Content-Type: application/json"); //sending json response
    $username = (string) htmlentities($_POST['user']);
    $pwd_guess = (string) htmlentities($_POST['pass_guess']);
    
    //check if input information is null
	if($username == null || $pwd_guess == null){
		echo json_encode(array(
			"success" => false
		));
		exit;
	}

    //prep query
    $stmt = $mysqli->prepare("SELECT COUNT(*), userid, username, password FROM users WHERE username=?");
        
    // Bind the parameter
    $stmt->bind_param('s', $username);
    $stmt->execute();
    
    // Bind the results
    $stmt->bind_result($cnt, $user_id, $user_name, $pwd_hash);
    $stmt->fetch();
    
    // Compare the submitted password to the actual password hash
    if( $cnt == 1 && password_verify($pwd_guess,$pwd_hash)){
        // Login succeeded!
        $_SESSION['user_id'] = $user_id;
        $_SESSION['user_name'] = $user_name;
        //Generate CSRF token
        $_SESSION['token'] = substr(md5(rand()), 0, 10);
        // Redirect to your target page
    
        echo json_encode(array(
            "success" => true,
            "token" => $_SESSION['token'],
        ));
        exit;    
    }else{
        // Login failed; redirect back to the login screen
    echo json_encode(array(
        "success" => false
    ));
    exit;
}
    
?>