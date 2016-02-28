<?php 
// $getAllFileName = scandir(getcwd());
// foreach ($getAllFileName as $files) {
// 	if(preg_match('/.*\.php$/', $files))
// 	{
// 		if($files != "connect.php" && $files != "logout.php" && $files != "index.php" && $files != "cannotaccess.php")
// 			echo trim($files,".php")."</br>";
// 	}
// }

function introduceBreaks($str, $length, $breakStr)
{
	$newStr = "";
	$start = 0;
	while($part = substr($str, $start, $length))
	{
		$part = $part.$breakStr;
		$newStr .= $part;
		$start += $length;
	}

	return trim($newStr,$breakStr);
}

echo introduceBreaks('45, Chandicharan Ghosh Road, Barisha Silpara, Kolkata- 700 008', 46, '<br/>');
echo "<hr/>";
echo date('d/m/Y',strtotime('2016-02-27 21:55:41'));
?>
