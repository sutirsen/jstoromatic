<?php require_once('../connect.php'); ?>
<?php
	if(isset($_POST['updateratefromdashboard']))
	{
		if(isset($_POST['rateid']) && isset($_POST['ratevalue']))
		{
			$pricingrateEditTbl = ORM::for_table('jst_pricing_rate_type')->find_one($_POST['rateid']);
			$dataArr = array(
						    'type_value' 	=> $_POST['ratevalue'],
						    'updated_on' 	=> date('Y-m-d H:i:s')
						);
			$pricingrateEditTbl->set($dataArr);		
			$pricingrateEditTbl->save();
			echo "saved";
			die();
		}
		else
		{
			echo "error";
			die();
		}
	}
	else
	{
		echo "error";
		die();
	}
	
?>