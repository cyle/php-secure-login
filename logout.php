<?php

// oh fuck, log em out...

if (isset($_COOKIE['test_session']) && trim($_COOKIE['test_session']) != '') { // user has a session already?
	// delete the saved session token from the database
	require_once('dbconn_mysql.php');
	$user_session_id = trim($_COOKIE['test_session']);
	$user_session_id_db = "'".$mysqli->escape_string($user_session_id)."'";
	$delete_session = $mysqli->query("DELETE FROM user_sessions WHERE session_id=$user_session_id_db");
}

$_COOKIE = array();
unset($_COOKIE);
setcookie('test_session', '', time() - 3600);
setcookie('test_session', '', time() - 3600, '/', 'cylesoft.com');

header('Location: index.php');
die();

?>