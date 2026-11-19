<?php defined( '_VALID_BBC' ) or die( 'Restricted access' );

$id = @intval($_GET['id']);
$q = "SELECT 1 FROM bbc_user WHERE id=$id";
if($db->getOne($q))
{
	$time = strtotime('+30 SECOND');
	file_write(_CACHE.'/force_login_'.$id.'.txt', $time);
	redirect(_URL.'user/force2Login/'.urlencode(urlencode(encode($time.'-'.$id))));
}