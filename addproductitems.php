<?php require_once('reusables/header.php'); ?>
<?php
	if(!isset($_GET['iid']))
	{
		if(!isset($_GET['pid']))
		{
			addMessageFlash("warning","custom","","","","Please provide the product Id");
			echo "<script>window.location='listitems.php';</script>";
		}
	}

	if(isset($_POST['additems']))
	{
		$itemsTbl = ORM::for_table('jst_product_item')->create();
		$itemsTbl->product_id			= $_GET['pid'];
		$itemsTbl->uniqueid				= createUniqueID();
		$itemsTbl->item_name			= $_POST['item_name'];
		$itemsTbl->weight				= $_POST['weight'];
		$itemsTbl->purity				= $_POST['purity'];
		$itemsTbl->pricewithoutmkchrg	= $_POST['pricewithoutmkchrg'];
		$itemsTbl->makingcharge			= $_POST['makingcharge'];
		$itemsTbl->pricing_rate_type_id	= $_POST['pricing_rate_type_id'];
		$itemsTbl->createdon			= date('Y-m-d H:i:s');
		$itemsTbl->save();
		// Save the object to the database
		addMessageFlash("success","adedwc",$itemsTbl->id(),"product_item",'uniqueid');
		echo "<script>window.location='listitems.php?product_id=".$_GET['pid']."';</script>";
	}

	//Edit code for product items
	if(isset($_POST['edititems']))
	{
		$itemsEditTbl = ORM::for_table('jst_product_item')->find_one($_GET['iid']);
		$dataArr = array(
					    	'item_name' 			=> $_POST['item_name'],
					    	'weight' 				=> $_POST['weight'],
					    	'purity' 				=> $_POST['purity'],
					    	'pricewithoutmkchrg' 	=> $_POST['pricewithoutmkchrg'],
					    	'makingcharge' 			=> $_POST['makingcharge'],
					    	'pricing_rate_type_id' 	=> $_POST['pricing_rate_type_id']
						);
		$itemsEditTbl->set($dataArr);		
		$itemsEditTbl->save();
		addMessageFlash("success","edtdwc",$_GET['iid'],"product_item",'uniqueid');
		if(isset($_GET['pid']))
		{
			echo "<script>window.location='listitems.php?product_id=".$_GET['pid']."';</script>";			
		}
		else
		{
			echo "<script>window.location='listitems.php';</script>";
		}
		
	}

	//Delete code for product items
	//Lets not do it now
	/*
	if(isset($_POST['removeitems']) && isset($_POST['ids']))
	{
		$ids = trim($_POST['ids'],",");
		$id_arr = explode(",", $ids);
		foreach ($id_arr as $id) {
			$usr = ORM::for_table('jst_product_item')->find_one($id);
			$usr->delete();
		}
		addMessageFlash("success","dltd");
		echo "<script>window.location='listitems.php';</script>";
	}
	*/
	
	//fetch product item
	if(isset($_GET['iid']))
	{
		$getitems = ORM::for_table('jst_product_item')->find_one($_GET['iid']);		
	}

	$getAllRateTypesProdItem = ORM::for_table('jst_pricing_rate_type')->where_equal('status','E')->find_many();

	function createUniqueID()
	{
		$digits = 5;
		$unqId = rand(pow(10, $digits-1), pow(10, $digits)-1);
		$checkIfIDExsts = ORM::for_table('jst_product_item')->where_equal('uniqueid', $unqId)->find_one();
		while($checkIfIDExsts)
		{
			$unqId = rand(pow(10, $digits-1), pow(10, $digits)-1);
			$checkIfIDExsts = ORM::for_table('jst_product_item')->where_equal('uniqueid', $unqId)->find_one();
		}
		return $unqId;
	}
?>
<div id="page-wrapper">
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header"><?php if(isset($getitems)) { ?>Edit<?php } else { ?>Add <?php } ?> Items</h1>
	    </div>
	    <!-- /.col-lg-12 -->
	</div>	
	<div class="panel panel-default">
		<div class="panel-body">
			<form role="form" method="post" action="" data-toggle="validator">
				<?php
				if(isset($getitems))
				{
					?>
					Item ID : <?php echo $getitems->uniqueid; ?><br/>
					<?php
				}
				?>
				<div class="form-group">
					<label for="item_name">Item Name</label>
					<input type="text" class="form-control" id="item_name" name="item_name" placeholder="Item Name" <?php if(isset($getitems)) { ?>value="<?php echo $getitems->item_name; ?>"<?php } ?>>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="weight">Weight *</label>
					<input type="text" class="form-control" id="weight" name="weight" placeholder="Weight" <?php if(isset($getitems)) { ?>value="<?php echo $getitems->weight; ?>"<?php } ?> required>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="purity">Purity</label>
					<input type="text" class="form-control" id="purity" name="purity" placeholder="Purity" <?php if(isset($getitems)) { ?>value="<?php echo $getitems->purity; ?>"<?php } ?>>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="pricewithoutmkchrg">Price (Without Making Charge) *</label>
					<input type="text" class="form-control" id="pricewithoutmkchrg" name="pricewithoutmkchrg" placeholder="Price (Without Making Charge)" <?php if(isset($getitems)) { ?>value="<?php echo $getitems->pricewithoutmkchrg; ?>"<?php } ?> required>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="makingcharge">Making Charge</label>
					<input type="text" class="form-control" id="makingcharge" name="makingcharge" placeholder="Making Charge" <?php if(isset($getitems)) { ?>value="<?php echo $getitems->makingcharge; ?>"<?php } ?>>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="pricing_rate_type_id">Pricing Group *</label>
					<select class="form-control" id="pricing_rate_type_id" name="pricing_rate_type_id" required>
						<?php foreach ($getAllRateTypesProdItem as $rateTypesProdItem) {
							?>
							<option value="<?php echo $rateTypesProdItem->id; ?>" <?php if(isset($getitems) && $getitems->pricing_rate_type_id == $rateTypesProdItem->id) { echo "selected"; } ?>><?php echo $rateTypesProdItem->type_name; ?></option>
							<?php
						}
						?>
					</select>
					<span class="help-block with-errors"></span>
				</div>

				<button type="submit" <?php if(isset($getitems)) { ?>name="edititems"<?php } else { ?>name="additems"<?php } ?> class="btn btn-warning">Save</button>
			</form>
		</div>
	</div>
</div>
<?php require_once('reusables/footer.php'); ?>