<?php require_once('../connect.php'); ?>
<?php
	if(isset($_GET['cid']))
	{
		$resp  = [];
		$resp['status'] = "success";
		$customerId = $_GET['cid'];
		$advanceSale = ORM::for_table('jst_advance_sale')->where_equal('customer_id',$customerId)->where_null('sale_id')->find_many();
		if(count($advanceSale) > 0)
		{
			$resp['data'] = [];
			foreach ($advanceSale as $adS) {
				//Get Items of the sale
				$advanceSaleItems = ORM::for_table('jst_advance_sale_items')->where_equal('advance_sale_id',$adS->id)->find_many();
				$tmpArr = [];
				$tmpArr['advanceid'] = $adS->id;
				$tmpArr['ratestring'] = decodeRatingString($adS->rate_string);
				$tmpArr['items'] = [];
				foreach ($advanceSaleItems as $itemRecs) {
					//Making first row as the header
					$tmpArrItm = [];
					$tmpArrItm['itmid'] 				= $itemRecs->id;
					$tmpArrItm['itmname'] 				= $itemRecs->item_name;
					$tmpArrItm['itmtype'] 				= $itemRecs->item_type;
					$tmpArrItm['purity'] 				= $itemRecs->purity;
					$tmpArrItm['weightoramt'] 			= $itemRecs->weightoramt;
					$tmpArrItm['item_price_rating_id'] 	= $itemRecs->item_price_rating_id;					
					array_push($tmpArr['items'], $tmpArrItm);
				}
				array_push($resp['data'], $tmpArr);
			}
		}
		else
		{
			$resp['data'] = "No advance made from customer";
		}
		#header('Content-Type: application/json');
		echo json_encode($resp);
	}
	else
	{
		$resp  = [];
		$resp['status'] = "error";
		$resp['data'] = "";
		echo json_encode($resp);
	}

	function decodeRatingString($rtString)
	{
		$ratingString = [];
		$intermRateString = explode("|", $rtString);
		foreach ($intermRateString as $idvalue) {
			$idValArr = explode(",", $idvalue);
			$rateData = ORM::for_table('jst_pricing_rate_type')->find_one($idValArr[0]);
			$ratingString[$idValArr[0]]["name"] = $rateData->type_name;			
			$ratingString[$idValArr[0]]["value"] = $idValArr[1];			
		}
		return $ratingString;
	}
	
?>