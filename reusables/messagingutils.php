<?php
//Messagin Utility 
//Getting MU Data
$infoTypesToClassMap = [
							'success' 	=> 'alert-success',
							'info' 		=> 'alert-info',
							'warning' 	=> 'alert-warning',
							'danger' 	=> 'alert-danger'
			 			];
//Use custom if message need to passed explicitly
$msgQuotes = [
				'dltd' 		=> 'Deletion completed successfully!',
				'dltdwc' 	=> 'Deletion of %data% completed successfully',
				'dltfldwc'	=> 'Unable to delete, %data% - Dependency exists!',
				'edtd'		=> 'Successfully updated!',
				'edtdwc'	=> '%data% - successfully updated!',
				'aded'		=> 'Successfully added!',
				'adedwc'	=> '%data% - successfully added!'
			 ];
$msgSection = "";
if(isset($_SESSION['mud'])) //mud : Message Utility Data
{
	$encData = $_SESSION['mud'];
	unset($_SESSION['mud']);
	$actData = base64_decode(trim($encData));
	$allParts = explode("DIV", $actData);
	//First Part is taken as info type
	$className = "alert ";
	if(isset($infoTypesToClassMap[$allParts[0]]))
	{
		$className .= $infoTypesToClassMap[$allParts[0]];
	}
	else
	{
		$className .= $infoTypesToClassMap['info'];	
	}
	$className .= " alert-dismissable";
	//Second Part is taken as message code 
	$txtDt = "";
	if($allParts[1] == "custom")
	{
		$txtDt = $allParts[2];
	}
	else
	{
		if(isset($msgQuotes[$allParts[1]]) && $msgQuotes[$allParts[1]] != "")
		{
			$chkifWc = substr($allParts[1], -2);
			if($chkifWc == "wc")
			{
				if(isset($allParts[2]) && isset($allParts[3]) && isset($allParts[4]))
				{
					$arrOfIds = explode(",", $allParts[2]);
					$dtFetched = ORM::for_table("jst_".$allParts[3])->where_id_in($arrOfIds)->find_many();
					$actDt = "";
					foreach ($dtFetched as $rqdt) {
						$actDt .= $rqdt->{$allParts[4]}.",";
					}
					$actDt = trim($actDt, ",");
					$txtDt = str_replace("%data%", $actDt, $msgQuotes[$allParts[1]]);
				}
				else
				{
					$mWord = trim($allParts[1], 'wc');
					$txtDt = $msgQuotes[$mWord];
				}
				//str_replace("%data%", replace, subject)
			}
			else
			{
				$txtDt = $msgQuotes[$allParts[1]];
			}
		}
	}
	
	//Third part is taken as id (Opt)
	//Fourth Part is taken as table to fetch data (Opt) - Not Rec Pass name without suffix
	//Fifth part is taken as fieldname to fetch (Opt) - Not Rec
	$msgSection = "<div class=\"$className\">";
	$msgSection .= "<button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>";
	$msgSection .= "$txtDt</div>";
}

//Function add temporary flash messages
function addMessageFlash($type, $msgCode, $ids = "", $tblName = "", $fldName = "", $cstmMsg = "")
{
	$msgData = $type."DIV".$msgCode;
	if($ids != "" && $tblName != "" && $fldName != "")
	{
		$msgData .= "DIV".trim($ids,",")."DIV".$tblName."DIV".$fldName;
	}
	if($cstmMsg != "")
	{
		$msgData .= "DIV".$cstmMsg;	
	}
	$_SESSION['mud'] = base64_encode($msgData);
	/*echo $_SESSION['mud'];
	die();*/
}

?>
