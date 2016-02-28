<?php 
require_once('../connect.php');
require_once '../library/dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

//configurations 
$maxLineLengthofDescription = 35;
$minLineofItems = 10;


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

function decodeRatingString($rtString)
{
	$ratingString = [];
	$intermRateString = explode("|", $rtString);
	foreach ($intermRateString as $idvalue) {
		$idValArr = explode(",", $idvalue);
		$ratingString[$idValArr[0]] = $idValArr[1];			
	}
	return $ratingString;
}

if(isset($_GET['adid']))
{
	$advanceSale = ORM::for_table('jst_advance_sale')->table_alias('ad')
												    ->select('ad.*')
												    ->select('cust.fullname', 'customer_name')
												    ->select('cust.tempaddress', 'customer_address')
												    ->select('shop.shop_name', 'shop_name')
												    ->select('shop.shop_address', 'shop_address')
												    ->select('shop.shop_phone', 'shop_phone')
												    ->join('jst_customers', array('ad.customer_id', '=', 'cust.id'), 'cust')
												    ->join('jst_shop', array('ad.shop_id', '=', 'shop.id'), 'shop')
												    ->order_by_desc('created_on')->find_one($_GET['adid']);
	//Get advance sale items
	$advanceSaleItems = ORM::for_table('jst_advance_sale_items')->where_equal('advance_sale_id',$_GET['adid'])->find_many();

	//Get rating details
	$ratingDetails = decodeRatingString($advanceSale->rate_string);

	$advanceSaleId = $_GET['adid'];
	$advanceSaleDate = date('d/m/Y',strtotime($advanceSale->created_on));
	$customerName = $advanceSale->customer_name;
	$customerAddress = $advanceSale->customer_address;
	$shopName = $advanceSale->shop_name;
	$shopAddress = introduceBreaks($advanceSale->shop_address,46,'<br/>');

	$itemLinesHTML = "";
	$slNoCounter = 1;
	$cashTotal = 0;
	foreach ($advanceSaleItems as $itemRecs) {
		$itemDescriptionPrep = introduceBreaks($itemRecs->item_name,$maxLineLengthofDescription,'<br/>');
		$rateValue = $ratingDetails[$itemRecs->item_price_rating_id];
		$wtoramt = $itemRecs->weightoramt;
		if($itemRecs->item_type == "C")
		{
			$lineHTML = '<tr>
									<td valign="top" style="border: 1px solid #2F3976;">'.$slNoCounter.'</td>
									<td style="border: 1px solid #2F3976;">'.$itemDescriptionPrep.'</td>
									<td valign="bottom" style="border: 1px solid #2F3976;"> - </td>
									<td valign="bottom" style="border: 1px solid #2F3976;"> - </td>
									<td valign="bottom" style="border: 1px solid #2F3976;">'.$wtoramt.'</td>
								</tr>';
			$cashTotal += $wtoramt;
		}
		else
		{
			$lineHTML = '<tr>
									<td valign="top" style="border: 1px solid #2F3976;">'.$slNoCounter.'</td>
									<td style="border: 1px solid #2F3976;">'.$itemDescriptionPrep.'</td>
									<td valign="bottom" style="border: 1px solid #2F3976;">'.$rateValue.'</td>
									<td valign="bottom" style="border: 1px solid #2F3976;">'.$wtoramt.'</td>
									<td valign="bottom" style="border: 1px solid #2F3976;"> - </td>
								</tr>';
		}
		$itemLinesHTML .= $lineHTML;
		$slNoCounter++;
	}

	if(count($advanceSaleItems) < $minLineofItems)
	{
		for($i=0; $i<=($minLineofItems - count($advanceSaleItems)); $i++)
		{
			$itemLinesHTML .= '<tr>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
					</tr>';
		}
	}

	$shopPhone = $advanceSale->shop_phone;

	$htmlData = '<!DOCTYPE html>
	<html>
	<head>
		<title></title>
		<style type="text/css">
			body
			{
				font-family: Arial, Helvetica, sans-serif;
			}
		</style>
	</head>
	<body>
		<table border="2" cellspacing="0" cellpadding="5" style="border: 2px solid #2F3976; border-collapse: collapse;">
			<tr>
				<td colspan="3" style="width:595px; border: 2px solid #2F3976;" align="center">
					<img src="../img/omsriguru.png"/><br/>
					Ordery Bill
				</td>
			</tr>
			<tr>
				<td style="width:225px; border: 2px solid #2F3976;"><b>No:</b> #'.$advanceSaleId.'</td>
				<td style="width:145px; color:white; background-color: #2F3976; border: 2px solid #2F3976;" align="center"><b>Estimate/Advance Bill</b></td>
				<td style="width:225px; border: 2px solid #2F3976;"><b>Date :</b> '.$advanceSaleDate.'</td>
			</tr>
			<tr>
				<td colspan="3" align="center" style="border: 2px solid #2F3976;">
					<table>
						<tr>
							<td style="width:115px;" valign="center" align="center"><img src="../img/jewel.png" style="height:70px;" /></td>
							<td style="width:365px;" valign="center" align="center">
								<strong>
								'.$shopName.'<br/>
								'.$shopAddress.' # Phone : '.$shopPhone.'
								</strong>
							</td>
							<td style="width:115px;" valign="center" align="center"><img src="../img/diamond.png" style="height:70px;"/></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="width:595px;">
					<b>Name:</b> '.$customerName.' <br/>
					<b>Address:</b> '.$customerAddress.'
				</td>
			</tr>
			<tr>
				<td colspan="3" style="width:595px; border: 2px solid #2F3976;">
					<table cellspacing="0" border="1" style=" border: 1px solid #2F3976; border-collapse: collapse;">
						<tr>
							<td style="width:10px; border: 1px solid #2F3976;"><b>Sl. No</b></td>
							<td style="width:295px; border: 1px solid #2F3976;"><b>Description</b></td>
							<td style="width:90px; border: 1px solid #2F3976;"><b>Rate</b></td>
							<td style="width:90px; border: 1px solid #2F3976;"><b>Weight</b></td>
							<td style="width:110px; border: 1px solid #2F3976;"><b>Amount</b></td>
						</tr>
						'.$itemLinesHTML.'
						<tr>
							<td colspan="2" rowspan="3" style="border: 1px solid #2F3976;"></td>
							<td colspan="2" align="right" style="border: 1px solid #2F3976;"><b>Total</b></td>
							<td style="border: 1px solid #2F3976;">0</td>
						</tr>
						<tr>
							<td style="border: 1px solid #2F3976;" colspan="2" align="right"><b>Advance</b></td>
							<td style="border: 1px solid #2F3976;">'.$cashTotal.'</td>
						</tr>					
						<tr>
							<td style="border: 1px solid #2F3976;" colspan="2" align="right"><b>Balance</b></td>
							<td style="border: 1px solid #2F3976;">0</td>
						</tr>					
						<tr>
							<td colspan="2" valign="bottom" style="height:50px; border: 1px solid #2F3976;"><i>Signature of Customer</i></td>
							<td colspan="3" align="center" style="border: 1px solid #2F3976;">E. & O. E. <br/><i>For '.$shopName.'</i></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td colspan="3" style="width:595px; color:white; background-color: #2F3976; border: 2px solid #2F3976;" align="center">
					<b>SUNDAY CLOSED</b>
				</td>
			</tr>
		</table>
	</body>
	</html>';
	// echo $htmlData;
	// instantiate and use the dompdf class
	$dompdf = new Dompdf();
	$dompdf->loadHtml($htmlData);

	// (Optional) Setup the paper size and orientation
	$dompdf->setPaper('A4', 'potrait');

	// Render the HTML as PDF
	$dompdf->render();

	// Output the generated PDF to Browser
	$dompdf->stream();

}
?>