<?php require_once('../connect.php'); ?>
<?php
	if(isset($_GET['customerid']))
	{
		$customer = ORM::for_table('jst_customers')->find_one($_GET['customerid']);
		if($customer)
		{
			$resp  = [];
			$resp['status'] = "success";
			$resp['data'] = '';
			$resp['data']['Card Id'] = $customer->card_id;
			$resp['data']['Name'] = $customer->fullname;
			$resp['data']['Phone Number'] = $customer->phnnumber;
			$resp['data']['Alternative Phone Number'] = $customer->altphnnumber;
			$resp['data']['Current Address'] = $customer->tempaddress;
			$resp['data']['Permanent Address'] = $customer->permanentaddress;
			$resp['data']['Email'] = $customer->email;
			$resp['data']['Date of Birth'] = $customer->dateofbirth;
			$resp['data']['Remarks'] = $customer->remarks;
			$resp['data']['Subscribed'] = $customer->subscribed_to_ad;
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