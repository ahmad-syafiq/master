<?php  if (!defined('_VALID_BBC')) exit('No direct script access allowed');

// The token is decoded with the public _SALT, so its table / db are validated
// and only an admin or the owner of the content may (un)publish its comments.
if (!empty($_GET['token']) && is_string($_GET['token']))
{
	_func('user');
	@list($token, $config) = _class('comment')->decode($_GET['token']);
	$config = is_array($config) ? $config : array();
	if (!empty($config['db']))
	{
		$db = user_token_db($config['db']);
		unset($config['db']);
	}

	$output  = array('success' => 0);
	$id      = (!empty($_GET['cid']) && is_numeric($_GET['cid'])) ? intval($_GET['cid']) : intval(decode(str_replace(' ', '+', urldecode(@$_GET['cid']))));
	$publish = @$_GET['id'] == 'publish' ? 1 : 0;
	if ( !empty($id)
		&& !empty($config['id'])
		&& !empty($config['expire'])
		&& $config['expire'] > time()
		&& user_token_is_identifier(@$config['table'])
		&& preg_match('~_comment$~i', $config['table'])
		&& user_token_comment_access($config['table'], $db->getRow("SELECT * FROM `".$config['table']."` WHERE `id`={$id}")))
	{
		$q = "UPDATE `".$config['table']."` SET `publish`={$publish} WHERE `id`=".$id;
		if ($db->Execute($q))
		{
			if ($db->Affected_rows())
			{
				$output = array('success'=>1);
				$par_id = $db->getOne("SELECT `par_id` FROM `".$config['table']."` WHERE `id`={$id}");
				if ($par_id)
				{
					$a_q = $publish ? '+' : '-';
					$db->Execute("UPDATE `".$config['table']."` SET `reply_on`=(`reply_on`{$a_q}1) WHERE id={$par_id}");
				}
			}
		}
	}
	output_json($output);
}