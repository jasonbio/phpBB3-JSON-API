<?php
// Gets associated username from a user ID and returns in JSON format if one is found

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

if ($_GET["uid"] && $_GET['callback']) {
	$uid = $_GET["uid"];
	if (is_numeric($uid)) { // verify input is a number
		$sql = 'SELECT *
				FROM ' . USERS_TABLE . '
				WHERE ' . (($uname) ? "username_clean = '" . $db->sql_escape(utf8_clean_string($uname)) . "'" : "user_id = $uid");
		$result = $db->sql_query($sql);
		$member = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);
		if ($member) { // verify member found
			$uname = $member['username'];
			header('content-type: application/json; charset=utf-8');
			echo $_GET['callback'] . '('.json_encode($uname).')';
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