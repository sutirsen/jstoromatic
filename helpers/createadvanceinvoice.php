<?php 
require_once '../library/dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

// $htmlData = '<!DOCTYPE html>
// <html>
// <head>
// 	<title></title>
// 	<style type="text/css">
// 		body
// 		{
// 			font-family: Arial, Helvetica, sans-serif;
// 		}
// 	</style>
// </head>
// <body>
// 	<table border="2" bordercolor="2F3976" cellspacing="0" cellpadding="5">
// 		<tr>
// 			<td colspan="3" style="width:595px;" align="center">
// 				Ordery Bill
// 			</td>
// 		</tr>
// 		<tr>
// 			<td style="width:225px;"><b>No:</b> #4715</td>
// 			<td style="width:145px; color:white; background-color: #2F3976;" align="center"><b>Estimate/Advance Bill</b></td>
// 			<td style="width:225px;"><b>Date :</b> 22/07/2015</td>
// 		</tr>
// 		<tr>
// 			<td colspan="3" align="center">
// 				<table>
// 					<tr>
// 						<td style="width:115px;" valign="center" align="center"><img src="img/jewel.png" style="height:70px;" /></td>
// 						<td style="width:365px;" valign="center" align="center">
// 							<strong>
// 							S.B Jewellers<br/>
// 							45, Chandicharan Ghosh Road, Barisha Silpara, <br/>
// 							Kolkata - 700 008 # Phone : 033-2447 2096
// 							</strong>
// 						</td>
// 						<td style="width:115px;" valign="center" align="center"><img src="img/diamond.png" style="height:70px;"/></td>
// 					</tr>
// 				</table>
// 			</td>
// 		</tr>
// 		<tr>
// 			<td colspan="3" style="width:595px;">
// 				<b>Name:</b> S. K Dutta <br/>
// 				<b>Address:</b> 51 Vidya Sagar Sarani, Kolkata 700008 
// 			</td>
// 		</tr>
// 		<tr>
// 			<td colspan="3" style="width:595px;">
// 				<table cellspacing="0" border="1">
// 					<tr>
// 						<td style="width:10px;"><b>Sl. No</b></td>
// 						<td style="width:295px;"><b>Description</b></td>
// 						<td style="width:90px;"><b>Rate</b></td>
// 						<td style="width:90px;"><b>Weight</b></td>
// 						<td style="width:110px;"><b>Amount</b></td>
// 					</tr>
// 					<tr>
// 						<td valign="top">1</td>
// 						<td>Gold Ring</td>
// 						<td valign="bottom">2500</td>
// 						<td valign="bottom">25gm</td>
// 						<td valign="bottom">12000.00</td>
// 					</tr>
// 					<tr>
// 						<td valign="top">2</td>
// 						<td>
// 							xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx<br/>
// 							xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx<br/>
// 							xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx<br/>
// 							xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx<br/>
// 							xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
// 						</td>
// 						<td valign="bottom">2500</td>
// 						<td valign="bottom">25gm</td>
// 						<td valign="bottom">25000.00</td>
// 					</tr>
// 					<tr>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 					</tr>

// 					<tr>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 					</tr>
// 					<tr>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 					</tr>
// 					<tr>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 					</tr>
// 					<tr>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 					</tr>
// 					<tr>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 					</tr>
// 					<tr>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 						<td>&nbsp;</td>
// 					</tr>

// 					<tr>
// 						<td colspan="2" rowspan="3"></td>
// 						<td colspan="2" align="right"><b>Total</b></td>
// 						<td>2500</td>
// 					</tr>
// 					<tr>
// 						<td colspan="2" align="right"><b>Advance</b></td>
// 						<td>2500</td>
// 					</tr>					
// 					<tr>
// 						<td colspan="2" align="right"><b>Balance</b></td>
// 						<td>0</td>
// 					</tr>					
// 					<tr>
// 						<td colspan="2" valign="bottom" style="height:50px;"><i>Signature of Customer</i></td>
// 						<td colspan="3" align="center">E. & O. E. <br/><i>For S. B Jewellers</i></td>
// 					</tr>
// 				</table>
// 			</td>
// 		</tr>
// 		<tr>
// 			<td colspan="3" style="width:595px; color:white; background-color: #2F3976;" align="center">
// 				<b>SUNDAY CLOSED</b>
// 			</td>
// 		</tr>
// 	</table>
// </body>
// </html>';

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
				Ordery Bill
			</td>
		</tr>
		<tr>
			<td style="width:225px; border: 2px solid #2F3976;"><b>No:</b> #4715</td>
			<td style="width:145px; color:white; background-color: #2F3976; border: 2px solid #2F3976;" align="center"><b>Estimate/Advance Bill</b></td>
			<td style="width:225px; border: 2px solid #2F3976;"><b>Date :</b> 22/07/2015</td>
		</tr>
		<tr>
			<td colspan="3" align="center" style="border: 2px solid #2F3976;">
				<table>
					<tr>
						<td style="width:115px;" valign="center" align="center"><img src="../img/jewel.png" style="height:70px;" /></td>
						<td style="width:365px;" valign="center" align="center">
							<strong>
							S.B Jewellers<br/>
							45, Chandicharan Ghosh Road, Barisha Silpara, <br/>
							Kolkata - 700 008 # Phone : 033-2447 2096
							</strong>
						</td>
						<td style="width:115px;" valign="center" align="center"><img src="../img/diamond.png" style="height:70px;"/></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="3" style="width:595px;">
				<b>Name:</b> S. K Dutta <br/>
				<b>Address:</b> 51 Vidya Sagar Sarani, Kolkata 700008 
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
					<tr>
						<td valign="top" style="border: 1px solid #2F3976;">1</td>
						<td style="border: 1px solid #2F3976;">Gold Ring</td>
						<td valign="bottom" style="border: 1px solid #2F3976;">2500</td>
						<td valign="bottom" style="border: 1px solid #2F3976;">25gm</td>
						<td valign="bottom" style="border: 1px solid #2F3976;">12000.00</td>
					</tr>
					<tr>
						<td valign="top" style="border: 1px solid #2F3976;">2</td>
						<td style="border: 1px solid #2F3976;">
							xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx<br/>
							xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx<br/>
							xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx<br/>
							xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx<br/>
							xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
						</td>
						<td style="border: 1px solid #2F3976;" valign="bottom">2500</td>
						<td valign="bottom" style="border: 1px solid #2F3976;">25gm</td>
						<td valign="bottom" style="border: 1px solid #2F3976;">25000.00</td>
					</tr>
					<tr>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
					</tr>
					<tr>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
					</tr>
					<tr>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
					</tr>
					<tr>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
					</tr>
					<tr>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
					</tr>
					<tr>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
					</tr>
					<tr>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
					</tr>
					<tr>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
						<td style="border: 1px solid #2F3976;">&nbsp;</td>
					</tr>


					<tr>
						<td colspan="2" rowspan="3" style="border: 1px solid #2F3976;"></td>
						<td colspan="2" align="right" style="border: 1px solid #2F3976;"><b>Total</b></td>
						<td style="border: 1px solid #2F3976;">2500</td>
					</tr>
					<tr>
						<td style="border: 1px solid #2F3976;" colspan="2" align="right"><b>Advance</b></td>
						<td style="border: 1px solid #2F3976;">2500</td>
					</tr>					
					<tr>
						<td style="border: 1px solid #2F3976;" colspan="2" align="right"><b>Balance</b></td>
						<td style="border: 1px solid #2F3976;">0</td>
					</tr>					
					<tr>
						<td colspan="2" valign="bottom" style="height:50px; border: 1px solid #2F3976;"><i>Signature of Customer</i></td>
						<td colspan="3" align="center" style="border: 1px solid #2F3976;">E. & O. E. <br/><i>For S. B Jewellers</i></td>
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
// instantiate and use the dompdf class
$dompdf = new Dompdf();
$dompdf->loadHtml($htmlData);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'potrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream();
?>