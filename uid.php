<?php
// Gets associated user ID from a username and returns in JSON format if one is found

// start phpBB3 environment
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);

// start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();

if ($_GET["uname"] && $_GET['callback']) {
	$target_username = $_GET["uname"];
	$username_ary = array();
	$user_id_ary = array();
	$username_ary[0] = $target_username;
	user_get_id_name($user_id_ary, $username_ary, $user_type = false); // gets ID associated with username
	if ($user_id_ary[0] != NULL) { // validates that the user exists by checking to see if ID was returned
		$uid = $user_id_ary[0];
		if ($uid !== ANONYMOUS) { // further check to account for cache/cookie session problems
			header('content-type: application/json; charset=utf-8');
			echo $_GET['callback'] . '('.json_encode($uid).')';
		} else {
			header('content-type: application/json; charset=utf-8');
			return false;
		}
	} else {
		header('content-type: application/json; charset=utf-8');
		return false;
	}
} else {
	header('content-type: application/json; charset=utf-8');
	return false;
}

?>