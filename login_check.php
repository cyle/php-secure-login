<?php

// see if user is currently logged in
// or trying to log in
// check for cookie
// check for session based on cookie

// if not, parse username/password
// based on https://gist.github.com/dzuelke/972386

// this is from http://stackoverflow.com/questions/637278/what-is-the-best-way-to-generate-a-random-key-within-php
// generate a random key from /dev/random
function get_key($bit_length = 128){
	$fp = @fopen('/dev/urandom','rb'); // should be /dev/random but it's too slow
	if ($fp !== FALSE) {
		$key = substr(base64_encode(@fread($fp,($bit_length + 7) / 8)), 0, (($bit_length + 5) / 6)  - 2);
		@fclose($fp);
		$key = str_replace(array('+', '/'), array('0', 'X'), $key);
		return $key;
	}
	return null;
}

// the defaults:
$current_user = array();
$current_user['loggedin'] = false;

if (isset($_COOKIE['test_session']) && trim($_COOKIE['test_session']) != '') { // user has a session already?
	
	require_once('dbconn_mysql.php');
	
	$user_session_id = trim($_COOKIE['test_session']);
	$user_session_id_db = "'".$mysqli->escape_string($user_session_id)."'";
	
	$check_for_session = $mysqli->query("SELECT * FROM user_sessions WHERE session_id=$user_session_id_db AND expires > UNIX_TIMESTAMP()");
	if ($check_for_session->num_rows == 1) {
		// oh snap -- they have a session!
		$user_session_row = $check_for_session->fetch_assoc();
		$current_user_id = $user_session_row['user_id'];
		$current_user['loggedin'] = true;
		$current_user['user_id'] = $current_user_id;
		$new_session_key_expires = time() + (60*60*24*30);
		setcookie('test_session', $user_session_id, $new_session_key_expires, '/', 'cylesoft.com');
		$update_session_expiry = $mysqli->query("UPDATE user_sessions SET expires=$new_session_key_expires WHERE session_id=$user_session_id_db");
		if ($_SERVER['PHP_SELF'] == 'login.php') {
			header('Location: protected.php');
			die();
		}
	} else {
		// session is expired, make them log in again!
		header('Location: logout.php');
		die();
	}
	
} else if (isset($_POST['e']) && isset($_POST['p'])) { // user is trying to log in?
	
	require_once('dbconn_mysql.php');	
	
	$users_email_db = "'".$mysqli->escape_string(trim($_POST['e']))."'";
	
	$check_for_user = $mysqli->query("SELECT * FROM users WHERE email=$users_email_db");
	if ($check_for_user->num_rows == 1) {
		
		$current_user_row = $check_for_user->fetch_assoc();
		
		// check password
		if (crypt(trim($_POST['p']), $current_user_row['password']) != $current_user_row['password']) {
			die('Your password was incorrect, please try again.');
		}
		
		// ok, cool
		$current_user['loggedin'] = true;
		$current_user['user_id'] = (int) $current_user_row['user_id'] * 1;
		$current_user_id = $current_user['user_id'];
		$new_session_key = get_key(256);
		$new_session_key_expires = time() + (60*60*24*30);
		setcookie('test_session', $new_session_key, $new_session_key_expires, '/', 'cylesoft.com');
		
		// write session to database
		$new_session_key_db = "'".$mysqli->escape_string($new_session_key)."'";
		$new_session_row = $mysqli->query("INSERT INTO user_sessions (session_id, user_id, expires) VALUES ($new_session_key_db, $current_user_id, $new_session_key_expires)");
		
		// logged in, cool
		header('Location: protected.php');
		die();
	} else {
		die('Could not find that email address, sorry. Try again, I guess.');
	}
				
} else if (isset($login_required) && $login_required == true) {
	
	header('Location: login.php');
	die();
	
}

?>