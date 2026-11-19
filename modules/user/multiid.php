<?php  if (!defined('_VALID_BBC')) exit('No direct script access allowed');

#!/includes/lib/pea/form/FormMultiid.php
// FormMultiid is only used by admin forms. The token is decoded with the public
// _SALT, so every identifier / SQL fragment in it is validated.
if (!empty($_POST) && !empty($_POST['id']) && !empty($_POST['token']) && is_string($_POST['id']) && is_string($_POST['token']))
{
	_func('user');
	$output = array('success' => 0, 'value' => '', 'html' => '');
	if (!user_token_is_admin())
	{
		output_json($output);
		die();
	}
	$ids    = user_formmultiid_ids($_POST['id']);
	$token  = json_decode(decode(str_replace(' ', '+', urldecode($_POST['token']))), 1);;
	if (!empty($ids) && is_array($token) && !empty($token['expire']) && $token['expire'] > time())
	{
		if (!empty($token['reference']) && is_array($token['reference']))
		{
			$links = !empty($token['links']) ? $token['links'] : '';
			$table = '';
			$value = '';
			$label = '';
			$sql   = '';
			$out   = array();
			// extract($token['reference']);// table, value, label, sql
			$table     = @$token['reference']['table'];
			$value     = @$token['reference']['value'];
			$label     = @$token['reference']['label'];
			$sql       = @$token['reference']['sql'];
			$db        = user_token_db(@$token['reference']['db']);
			$sql       = empty($sql) ? array() : (array)$sql;
			$is_ok     = user_token_is_identifier($value, true)
				&& user_token_sql_is_safe($label)
				&& user_token_sql_is_safe($table);
			foreach ($sql as $s)
			{
				$is_ok = $is_ok && user_token_sql_is_safe($s);
			}
			if (!$is_ok)
			{
				output_json($output);
				die();
			}
			$q = "SELECT {$value}, {$label} FROM {$table} WHERE {$value} IN ({$ids})";
			if (!empty($sql))
			{
				$q .= " AND ".implode(' AND ', $sql);
			}
			$r1 = $db->getAssoc($q);
			$r2 = explode(',', $ids);
			foreach ($r2 as $i)
			{
				if (!empty($r1[$i]))
				{
					$out[]           = $i;
					$a1              = $links ? '<a href="'.$links.$i.'">' : '';
					$a2              = $links ? '</a>' : '';
					$output['html'] .= '<li class="list-group-item">'.$a1.$r1[$i].$a2.'</li>';
				}
			}
			if (!empty($out))
			{
				$output['value']   = implode(',', $out);
				$output['success'] = 1;
			}
		}
	}
	output_json($output);
	die();
}
function user_formmultiid_ids($value)
{
	$str = preg_replace('~[^0-9,]+~s', ',', $value);
	$r   = array_unique(explode(',', $str));
	return trim(implode(',', $r), ',');
}
die();