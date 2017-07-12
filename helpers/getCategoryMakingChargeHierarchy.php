<?php require_once('../connect.php'); ?>
<?php
	$allParentCats = [];
	if(isset($_GET['catid'])){
		getParentBreadCrumb($_GET['catid']);
		$resp  = [];
		$resp['status'] = "success";
		$resp['data'] = $allParentCats;
		echo json_encode($resp);
	} else {
		$resp  = [];
		$resp['status'] = "error";
		$resp['data'] = "";
		echo json_encode($resp);
	}

	function getParentBreadCrumb($id)
	{
		$pcat = ORM::for_table('jst_product_category')->find_one($id);
		if($pcat->parent_id != "" && $pcat->parent_id != 0)
		{
			$GLOBALS["allParentCats"][$pcat->name] = $pcat->making_charge;
			getParentBreadCrumb($pcat->parent_id);
		}
		else
		{
			$GLOBALS["allParentCats"][$pcat->name] = $pcat->making_charge;
		}
	}
?>