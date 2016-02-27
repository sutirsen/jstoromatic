<?php require_once('../connect.php'); ?>
<?php
	if(isset($_GET['uid']))
	{
		$user = ORM::for_table('jst_users')->join('jst_user_type', array('jst_users.type', '=', 'jst_user_type.id'))->find_one($_GET['uid']);
		if($user)
		{
			$currentDate = new DateTime("now");
			$dob = date_create($user->dateofbirth);
			$dob->setDate(date('Y'), $dob->format('m'), $dob->format('d'));
			$leftDays = "";
			if($dob > $currentDate)
			{
				$diffBetweenCurrent = date_diff($dob, $currentDate);
				$leftDays = ceil($diffBetweenCurrent->y * 365.25 + $diffBetweenCurrent->m * 30 + $diffBetweenCurrent->d + $diffBetweenCurrent->h/24 + $diffBetweenCurrent->i / 60);
			}
			
			$resp  = [];
			$resp['status'] = "success";
			$resp['data'] = '';
			$resp['data']['Name'] = $user->firstname." ".$user->lastname;
			$resp['data']['Email'] = $user->email;
			$resp['data']['Date of Birth'] = ($leftDays != "" )?date('jS, F, Y', strtotime($user->dateofbirth))." - Days left : <b>".$leftDays."</b>":date('jS, F, Y', strtotime($user->dateofbirth));
			$resp['data']['Phone Number'] = $user->phnnumber;
			$resp['data']['Alternative Phone Number'] = $user->altphnnumber;
			$resp['data']['Address'] = $user->address;
			$resp['data']['Status'] = ($user->status == "A")?"<span class='bg-success'>Active</span>":"<span class='bg-danger'>Deactivated</span>";
			$resp['data']['Position'] = $user->type_name;
			$resp['data']['Created On'] = $user->createdon;
			echo json_encode($resp);
		}
		else
		{
			$resp  = [];
			$resp['status'] = "error";
			$resp['data'] = "";
			echo json_encode($resp);
		}
		
	}
	else
	{
		$resp  = [];
		$resp['status'] = "error";
		$resp['data'] = "";
		echo json_encode($resp);
	}
	
?>