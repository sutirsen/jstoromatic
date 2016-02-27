<?php require_once('../connect.php'); ?>
<?php
	if(isset($_GET['adid']))
	{
		$advanceSale = ORM::for_table('jst_advance_sale')->table_alias('ad')
													    ->select('ad.*')
													    ->select('cust.fullname', 'customer_name')
													    ->select('shop.shop_name', 'shop_name')
													    ->join('jst_customers', array('ad.customer_id', '=', 'cust.id'), 'cust')
													    ->join('jst_shop', array('ad.shop_id', '=', 'shop.id'), 'shop')
													    ->order_by_desc('created_on')->find_one($_GET['adid']);
		if($advanceSale)
		{
			//Get advance sale items
			$advanceSaleItems = ORM::for_table('jst_advance_sale_items')->where_equal('advance_sale_id',$_GET['adid'])->find_many();

			//Get Sales date if sale id exists on record
			$soldOn = "";
			if($advanceSale->sale_id != "")
			{
				$saleInformation = ORM::for_table('jst_sales')->find_one($advanceSale->sale_id);
				$soldOn = $saleInformation->sold_on;
			}

			//Get rating details
			$ratingDetails = decodeRatingString($advanceSale->rate_string);

			$resp  = [];
			$resp['status'] = "success";
			$resp['data'] = '';
			$resp['data']['Customer Name'] = $advanceSale->customer_name;
			$resp['data']['Shop Name'] = $advanceSale->shop_name;
			$resp['data']['Sold on'] = ($soldOn == "") ? "Not sold yet" : $soldOn;
			$resp['data']['Advance Date'] = $advanceSale->created_on;
			//Lets Include the items
			$resp['data']['Items'] = [];

			//Making first row as the header
			$tmpArr = [];
			array_push($tmpArr, "Name");
			array_push($tmpArr, "Type");
			array_push($tmpArr, "Purity");
			array_push($tmpArr, "Rating Type");
			array_push($resp['data']['Items'], $tmpArr);
			foreach ($advanceSaleItems as $itemRecs) {
				//Making first row as the header
				$tmpArr = [];
				array_push($tmpArr, $itemRecs->item_name);
				array_push($tmpArr, $itemRecs->item_type);
				array_push($tmpArr, $itemRecs->purity);
				array_push($tmpArr, $itemRecs->weightoramt);
				$ratingType =  ORM::for_table('jst_pricing_rate_type')->find_one($itemRecs->item_price_rating_id);
				array_push($tmpArr, $ratingType->type_name);
				array_push($resp['data']['Items'], $tmpArr);
			}

			//Including the rating stamp
			$resp['data']['Ratings'] = [];
			$tmpArr = [];
			array_push($tmpArr, "Type");
			array_push($tmpArr, "Price");
			array_push($resp['data']['Ratings'], $tmpArr);
			foreach ($ratingDetails as $typName => $typVal) {
				$tmpArr = [];
				array_push($tmpArr, $typName);
				array_push($tmpArr, $typVal);
				array_push($resp['data']['Ratings'], $tmpArr);
			}
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

	function decodeRatingString($rtString)
	{
		$ratingString = [];
		$intermRateString = explode("|", $rtString);
		foreach ($intermRateString as $idvalue) {
			$idValArr = explode(",", $idvalue);
			$rateObj = ORM::for_table('jst_pricing_rate_type')->find_one($idValArr[0]);
			$ratingString[$rateObj->type_name] = $idValArr[1];			
		}
		return $ratingString;
	}
	
?>