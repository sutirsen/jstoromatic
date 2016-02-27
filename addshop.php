<?php require_once('reusables/header.php'); ?>
<?php
	//Add Pricing Rates
	if(isset($_POST['addshop']))
	{
		$shopTbl = ORM::for_table('jst_shop')->create();
		$shopTbl->shop_name		= $_POST['shop_name'];
		$shopTbl->shop_address	= $_POST['shop_address'];
		$shopTbl->shop_phone	= $_POST['shop_phone'];
		// Save the object to the database
		$shopTbl->save();
		addMessageFlash("success","adedwc",$shopTbl->id(),"shop",'shop_name');
		echo "<script>window.location='listshops.php';</script>";
	}

	//Edit Pricing Rates
	if(isset($_POST['editshop']))
	{
		$shopEditTbl = ORM::for_table('jst_shop')->find_one($_GET['shopid']);
		$dataArr = array(
					    'shop_name' 	=> $_POST['shop_name'],
					    'shop_address' 	=> $_POST['shop_address'],
					    'shop_phone' 	=> $_POST['shop_phone'],
					);
		$shopEditTbl->set($dataArr);		
		$shopEditTbl->save();
		addMessageFlash("success","edtdwc",$_GET['shopid'],"shop",'shop_name');
		echo "<script>window.location='listshops.php';</script>";
	}
	


	//fetch Product Categories details 
	if(isset($_GET['shopid']))
	{
		$getshop = ORM::for_table('jst_shop')->find_one($_GET['shopid']);
	}
?>
<div id="page-wrapper">
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header"><?php if(isset($getshop)) { ?>Edit Shop<?php } else { ?>Add Shop<?php } ?></h1>
	    </div>
	    <!-- /.col-lg-12 -->
	</div>	
	<div class="panel panel-default">
		<div class="panel-body">
			<form role="form" method="post" action="" data-toggle="validator">
				<div class="form-group">
					<label for="shop_name">Name *</label>
					<input type="text" class="form-control" id="shop_name" name="shop_name" placeholder="Shop Name" <?php if(isset($getshop)) { ?>value="<?php echo $getshop->shop_name; ?>"<?php } ?>  required>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="shop_address">Address *</label>
					<input type="text" class="form-control" id="shop_address" name="shop_address" placeholder="Address" <?php if(isset($getshop)) { ?>value="<?php echo $getshop->shop_address; ?>"<?php } ?>  required>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="shop_phone">Phone *</label>
					<input type="text" class="form-control" id="shop_phone" name="shop_phone" placeholder="Phone Numbers" <?php if(isset($getshop)) { ?>value="<?php echo $getshop->shop_phone; ?>"<?php } ?>  required>
					<span class="help-block with-errors"></span>
				</div>

				<button type="submit" <?php if(isset($getshop)) { ?>name="editshop"<?php } else { ?>name="addshop"<?php } ?> class="btn btn-warning">Save</button>
			</form>
		</div>
	</div>
</div>
<?php require_once('reusables/footer.php'); ?>