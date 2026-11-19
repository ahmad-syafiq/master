<?php  if (!defined('_VALID_BBC')) exit('No direct script access allowed');

// Repair scripts change the database schema: administrators only.
if (empty($_SESSION['bbcAuthAdmin']['id']))
{
	header('HTTP/1.1 403 Forbidden');
	die('Forbidden');
}
$file = 'repair-'.@menu_save($_GET['id']).'.php';

if (file_exists($file))
{
	include $file;
	if (!empty($_GET['redirect']))
	{
		redirect($_GET['redirect']);
	}else{
		redirect();
	}
}
