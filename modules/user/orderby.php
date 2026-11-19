<?php  if (!defined('_VALID_BBC')) exit('No direct script access allowed');

#!/includes/lib/pea/form/FormOrderby.php
// Used by admin and member (PEA) lists. The token is decoded with the public
// _SALT, so it is attacker controlled: a login is required, table / field names
// must be plain identifiers, the field must be a sort field, and the raw SQL
// condition / order from the token is no longer used (the rows are selected by
// the posted ids instead, which gives the same current values).
if (!empty($_POST['token']) && !empty($_POST['ids']) && is_string($_POST['token']) && is_string($_POST['ids']))
{
	_func('user');
	$output = array('success' => 0, 'output' => 'Failed to save data sorting!');
	$token  = json_decode(decode(str_replace(' ', '+', urldecode($_POST['token']))), 1);
	$ids    = trim(preg_replace('~[^0-9,]+~s', ',', $_POST['ids']), ',');
	$ids    = implode(',', array_unique(array_filter(array_map('intval', explode(',', $ids)))));
	if (!empty($ids) && is_array($token) && !empty($token['expire']) && $token['expire'] > time() && user_token_is_logged())
	{
		// extract($token);// tableId, fieldName, tableName, sqlCondition, sqlOrder
		$tableId   = @$token['tableId'];
		$fieldName = @$token['fieldName'];
		$tableName = @$token['tableName'];
		if (is_string($tableName) && preg_match('~([a-z0-9_]+)~is', $tableName, $m))
		{
			$tableName = $m[1];
		}
		if (user_token_is_identifier($tableId)
			&& user_token_is_identifier($fieldName)
			&& user_token_is_identifier($tableName)
			&& preg_match('~(order|sort|position|urut)~i', $fieldName))
		{
			$db   = user_token_db(@$token['db']);
			$j    = 0;
			$q    = "SELECT `{$tableId}`, `{$fieldName}` FROM `{$tableName}` WHERE `{$tableId}` IN ({$ids})";
			$data = $db->getAssoc($q);
			$r_id = explode(',', $ids);
			foreach ($r_id as $id)
			{
				$j++;
				$id = intval($id);
				if (!isset($data[$id]) || $data[$id] != $j)
				{
					if (!$db->Execute("UPDATE `{$tableName}` SET `{$fieldName}`=$j WHERE `{$tableId}`={$id}"))
					{
						$output['output'] = 'Failed to save sorting number '.$j;
						break;
					}
				}
			}
			if (count($r_id)==$j)
			{
				$output = array('success' => 1, 'output' => 'Data sorting has been saved');
			}
		}
	}
	output_json($output);
}