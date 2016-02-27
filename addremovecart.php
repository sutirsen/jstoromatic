<?php
session_start();
if(!isset($_GET['iid']) && !isset($_GET['act']))
{
	header('Location: ' . $_SERVER['HTTP_REFERER']);
}
if($_GET['act'] == "add")
{
	if(isset($_SESSION['cartData']))
	{
		array_push($_SESSION['cartData']['items'], $_GET['iid']);
	}
	else
	{
		$_SESSION['cartData'] = "";
		$_SESSION['cartData']['items'] = array();
		array_push($_SESSION['cartData']['items'], $_GET['iid']);
	}	
}
else
{
	if(isset($_SESSION['cartData']) && in_array($_GET['iid'], $_SESSION['cartData']['items']))
	{
		if (($key = array_search($_GET['iid'], $_SESSION['cartData']['items'])) !== false) {
		    unset($_SESSION['cartData']['items'][$key]);
		}
	}
}


header('Location: ' . $_SERVER['HTTP_REFERER']);
?>