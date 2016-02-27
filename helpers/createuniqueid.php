<?php require_once('../connect.php'); ?>
<?php 
//create any unique id for any table.fields
if(isset($_POST['len']) && isset($_POST['tblname']) && isset($_POST['tblfld']))
{
	$digits = $_POST['len'];
	$unqId = rand(pow(10, $digits-1), pow(10, $digits)-1);
	$checkIfIDExsts = ORM::for_table('jst_'.$_POST['tblname'])->where_equal($_POST['tblfld'], $unqId)->find_one();
	while($checkIfIDExsts)
	{
		$unqId = rand(pow(10, $digits-1), pow(10, $digits)-1);
		$checkIfIDExsts = ORM::for_table('jst_'.$_POST['tblname'])->where_equal($_POST['tblfld'], $unqId)->find_one();
	}
	echo $unqId;	
}
else
{
	echo "error";
}
die();
?>