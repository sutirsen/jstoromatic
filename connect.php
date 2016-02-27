<?php
	require_once('library/idiorm.php');

	ORM::configure('mysql:host=localhost;dbname=jstoromatic', null);
	ORM::configure('username', 'root');
	ORM::configure('password', '');
?>