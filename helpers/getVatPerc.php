<?php require_once('../connect.php'); ?>
<?php
	$vatPerc = ORM::for_table('jst_settings')->where_equal('name',"VAT_PERCENTAGE")->find_one();
	echo $vatPerc->value;
?>