<?php require_once('reusables/header.php'); ?>
<?php
	//Edit Pricing Rates
	if(isset($_POST['editvatnumber']))
	{
		$settingsEditVatNumberTbl = ORM::for_table('jst_settings')->where_equal('name','VAT_NUMBER')->find_one();
		$dataArr = array(
					    'value' 	=> $_POST['vat_number'],
					    'updated_on' 	=> date('Y-m-d H:i:s')
					);
		$settingsEditVatNumberTbl->set($dataArr);		
		$settingsEditVatNumberTbl->save();
		addMessageFlash("success","custom","","","","Vat Number updated successfully!");
		echo "<script>window.location='vatsettings.php';</script>";
	}
	
	if(isset($_POST['editvatamount']))
	{
		$settingsEditVatAmountTbl = ORM::for_table('jst_settings')->where_equal('name','VAT_PERCENTAGE')->find_one();
		$dataArr = array(
					    'value' 	=> $_POST['vat_amount'],
					    'updated_on' 	=> date('Y-m-d H:i:s')
					);
		$settingsEditVatAmountTbl->set($dataArr);		
		$settingsEditVatAmountTbl->save();
		addMessageFlash("success","custom","","","","Vat percentage value updated successfully!");
		echo "<script>window.location='vatsettings.php';</script>";
	}
	
?>
<div id="page-wrapper">
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header">Update VAT settings</h1>
	    </div>
	    <!-- /.col-lg-12 -->
	</div>	
	<div class="panel panel-default">
		<div class="panel-heading">VAT Number</div>
		<div class="panel-body">
			<form role="form" method="post" action="" data-toggle="validator">
				<div class="form-group">
					<label for="vat_number">VAT Number *</label>
					<?php $settingsEditVatNumberTbl = ORM::for_table('jst_settings')->where_equal('name','VAT_NUMBER')->find_one(); ?>
					<input type="text" class="form-control" id="vat_number" name="vat_number" placeholder="VAT Number" value="<?php echo $settingsEditVatNumberTbl->value; ?>"  required>
					<span class="help-block with-errors"></span>
				</div>
				<button type="submit" name="editvatnumber" class="btn btn-warning">Save VAT Number</button>
			</form>
		</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">VAT Amount (Percentage)</div>
		<div class="panel-body">
			<form role="form" method="post" action="" data-toggle="validator">
				<div class="form-group">
					<label for="vat_amount">VAT Amount *</label>
					<?php $settingsEditVatAmountTbl = ORM::for_table('jst_settings')->where_equal('name','VAT_PERCENTAGE')->find_one(); ?>
					<input type="text" class="form-control" id="vat_amount" name="vat_amount" placeholder="VAT Amount" value="<?php echo $settingsEditVatAmountTbl->value; ?>"  required>
					<span class="help-block with-errors"></span>
				</div>
				<button type="submit" name="editvatamount" class="btn btn-warning">Save VAT Amount</button>
			</form>
		</div>
	</div>

</div>
<?php require_once('reusables/footer.php'); ?>