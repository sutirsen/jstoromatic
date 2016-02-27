<?php require_once('../connect.php'); ?>
<?php
	if(isset($_GET['email']))
	{
		$number_of_users = ORM::for_table('jst_users')->where_equal('email',$_GET['email'])->count();
		$resp  = [];
		if($number_of_users == 0)
		{
			$resp['status'] = "success";
			$resp['msg'] = "";
		}
		else
		{
			$resp['status'] = "error";
			$resp['msg'] = "Email already exists";
		}
		
		echo json_encode($resp);
	}
	else
	{
		$resp  = [];
		$resp['status'] = "error";
		$resp['msg'] = "Invalid email";
		echo json_encode($resp);
	}
	
?>