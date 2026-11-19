<?php  if ( ! defined('_VALID_BBC')) exit('No direct script access allowed');

// Block untuk menampilkan tag html/javascript yang dimasukkan admin.
// Tipe "php" tidak lagi dijalankan: isi block disimpan di database, sehingga
// siapa pun yang bisa menulis ke bbc_block (SQL injection, akun admin yang
// dicuri) bisa menanam backdoor yang berjalan di setiap halaman. Pilihan "php"
// juga sudah tidak tersedia di form admin (_setting.php).
if (@$config['type'] == 'php')
{
	return;
}
echo unhtmlentities(@$config['content']);
