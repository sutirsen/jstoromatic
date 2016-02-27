<?php 
$getAllFileName = scandir(getcwd());
foreach ($getAllFileName as $files) {
	if(preg_match('/.*\.php$/', $files))
	{
		if($files != "connect.php" && $files != "logout.php" && $files != "index.php" && $files != "cannotaccess.php")
			echo trim($files,".php")."</br>";
	}
}
?>
