<?php
// Gets requested custom profile field for the user that's currently logged in and returns in JSON format
// you must know the name of the custom field you're requested, e.g. pf_mycustom_field

// start phpBB3 environment
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_user.' . $phpEx);

// start session management
$user->session_begin();
$auth->acl($user->data);
$user->setup();

if ($_GET['customfield'] && $_GET['callback']) {
	$customfield = $_GET['customfield'];
	$target_username = $user->data['username']; // gets current active user session, change this to get input from query string if you want to search other users
	$username_ary = array();
	$user_id_ary = array();
	$username_ary[0] = $target_username;
	user_get_id_name($user_id_ary, $username_ary, $user_type = false);
	if ($user_id_ary[0] != NULL) { // if a profile exists for user
		$uid = $user_id_ary[0];
		include_once($phpbb_root_path . 'includes/functions_profile_fields.' . $phpEx); // loop in profile_fields functions
		$user->get_profile_fields( $user->data['user_id'] );
		if ($user->profile_fields[$customfield]) { // if custom profile field requested exists
			$customresult = $user->profile_fields[$customfield];
			header('content-type: application/json; charset=utf-8');
			echo $_GET['callback'] . '('.json_encode($customresult).')';
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