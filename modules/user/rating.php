<?php  if (!defined('_VALID_BBC')) exit('No direct script access allowed');

#!/includes/function/layout.php
$output = array();
if (!empty($_POST['id'])
	&&!empty($_POST['value'])
	&&!empty($_POST['token']))
{
	$output = array(
		'success' => 0,
		'content' => '',
		'message' => lang('Failed to vote this content')
		);
	// no extract($_POST): it let the request overwrite any variable ($db, $user, ...).
	// The token is decoded with the public _SALT, so its values are validated.
	_func('user');
	$id     = intval($_POST['id']);
	$value  = intval($_POST['value']);
	$value  = $value > 0 && $value < 6 ? $value : 1;
	$params = is_string($_POST['token']) ? to_array(json_decode(decode($_POST['token']), 1)) : array();
	$table  = '';
	$voter  = 'voter';
	if (!empty($params) && is_array($params))
	{
		$table = is_string(@$params['table']) ? $params['table'] : '';
		$voter = is_string(@$params['voter']) ? $params['voter'] : $voter;
		$db    = user_token_db(@$params['db']);
	}
	$table  = menu_save($table);
	$rating = '';
	if ($id > 0 && user_token_is_identifier($table))
	{
		$rating = $db->getOne("SELECT rating FROM `{$table}` WHERE id={$id}");
	}
	if ($id > 0 && user_token_is_identifier($table) && $db->Affected_rows())
	{
		$r = explode( ',', $rating );
		$o = array();
		for($i=0; $i < 5;$i++)
		{
			$j   = $i + 1;
			$v   = @intval($r[$i]);
			$v  += ($j==$value) ? 1 : 0;
			$o[] = $v;
		}
		$rating = implode(',', $o);
		$q      = "UPDATE `{$table}` SET `rating`='{$rating}' WHERE id={$id}";
		if ($db->Execute($q))
		{
			$output = array(
			'success' => 1,
			'content' => rating($rating, '', '', $voter),
			'message' => lang('Thank you for your rate!')
			);
			$_SESSION['bbc_rating'][$table][$id] = 1;
		}
	}
}
output_json($output);