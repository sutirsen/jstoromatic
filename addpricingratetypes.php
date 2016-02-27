<?php require_once('reusables/header.php'); ?>
<?php
	//Add Pricing Rates
	if(isset($_POST['addpricingrate']))
	{
		$pricingrateTbl = ORM::for_table('jst_pricing_rate_type')->create();
		$pricingrateTbl->type_name		= $_POST['type_name'];
		$pricingrateTbl->type_value		= $_POST['type_value'];
		$pricingrateTbl->status			= $_POST['status'];
		$pricingrateTbl->created_on		= date('Y-m-d H:i:s');
		$pricingrateTbl->updated_on		= date('Y-m-d H:i:s');
		// Save the object to the database
		$pricingrateTbl->save();
		echo "<script>window.location='listpricingratetypes.php';</script>";
	}

	//Edit Pricing Rates
	if(isset($_POST['editpricingrate']))
	{
		$pricingrateEditTbl = ORM::for_table('jst_pricing_rate_type')->find_one($_GET['pricingrateid']);
		$dataArr = array(
					    'type_name' 	=> $_POST['type_name'],
					    'type_value' 	=> $_POST['type_value'],
					    'status' 		=> $_POST['status'],
					    'updated_on' 	=> date('Y-m-d H:i:s')
					);
		$pricingrateEditTbl->set($dataArr);		
		$pricingrateEditTbl->save();
		echo "<script>window.location='listpricingratetypes.php';</script>";
	}
	


	//fetch Product Categories details 
	if(isset($_GET['pricingrateid']))
	{
		$getpricingrate = ORM::for_table('jst_pricing_rate_type')->find_one($_GET['pricingrateid']);
	}
?>
<div id="page-wrapper">
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header"><?php if(isset($getpricingrate)) { ?>Edit Pricing Category<?php } else { ?>Add Pricing Category<?php } ?></h1>
	    </div>
	    <!-- /.col-lg-12 -->
	</div>	
	<div class="panel panel-default">
		<div class="panel-body">
			<form role="form" method="post" action="" data-toggle="validator">
				<div class="form-group">
					<label for="type_name">Name *</label>
					<input type="text" class="form-control" id="type_name" name="type_name" placeholder="Rate Type Name" <?php if(isset($getpricingrate)) { ?>value="<?php echo $getpricingrate->type_name; ?>"<?php } ?>  required>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="type_value">Rate of Day *</label>
					<input type="text" class="form-control" id="type_value" name="type_value" placeholder="Rate" <?php if(isset($getpricingrate)) { ?>value="<?php echo $getpricingrate->type_value; ?>"<?php } ?>  required>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="status">Status *</label>
					<select class="form-control" id="status" name="status" required>
						<option value="E" <?php if(isset($getpricingrate) && $getpricingrate->status == "A") { echo "selected"; } ?>>Active</option>
						<option value="D" <?php if(isset($getpricingrate) && $getpricingrate->status == "D") { echo "selected"; } ?>>Disabled</option>
					</select>
					<span class="help-block with-errors"></span>
				</div>

				<button type="submit" <?php if(isset($getpricingrate)) { ?>name="editpricingrate"<?php } else { ?>name="addpricingrate"<?php } ?> class="btn btn-warning">Save</button>
			</form>
		</div>
	</div>
</div>
<?php require_once('reusables/footer.php'); ?>