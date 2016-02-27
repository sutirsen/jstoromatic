<?php require_once('reusables/header.php'); ?>
<?php
	//Add code for Product Categories
	if(isset($_POST['addpcat']))
	{
		$pCatTbl = ORM::for_table('jst_product_category')->create();
		$pCatTbl->name			= $_POST['name'];
		$pCatTbl->description	= $_POST['description'];
		$pCatTbl->parent_id		= $_POST['parent_id'];
		$pCatTbl->making_charge	= $_POST['making_charge'];
		// Save the object to the database
		$pCatTbl->save();
		echo "<script>window.location='listproductcategories.php';</script>";
	}

	//Edit code for Product Categories
	if(isset($_POST['editpcat']))
	{
		$pCatEditTbl = ORM::for_table('jst_product_category')->find_one($_GET['pcatid']);
		$dataArr = array(
					    'name' 			=> $_POST['name'],
					    'description' 	=> $_POST['description'],
					    'parent_id' 	=> $_POST['parent_id'],
					    'making_charge' => $_POST['making_charge']
					);
		$pCatEditTbl->set($dataArr);		
		$pCatEditTbl->save();
	}
	//Delete code for product categories
	if(isset($_POST['removepcats']) && isset($_POST['ids']))
	{
		$ids = trim($_POST['ids'],",");
		$id_arr = explode(",", $ids);
		$unableToDel = "";
		foreach ($id_arr as $id) {
			$pex = ORM::for_table('jst_product_category')->where_equal('parent_id', $id)->find_many();
			if(count($pex) > 0)
			{
				$unableToDel .= $id.",";
				continue;
			}

			//Second level checking 
			$pd = ORM::for_table('jst_product')->where_equal('category_id', $id)->find_many();
			if(count($pd) > 0)
			{
				$unableToDel .= $id.",";	
				continue;
			}
			ORM::for_table('jst_page_permission')->where_equal('user_type_id', $id)->delete_many();
		}
		if($unableToDel != "")
		{
			addMessageFlash("warning","dltfldwc",$unableToDel,"product_category",'name');
		}
		echo "<script>window.location='listproductcategories.php';</script>";
	}


	//fetch Product Categories details 
	if(isset($_GET['pcatid']))
	{
		$getPcat = ORM::for_table('jst_product_category')->find_one($_GET['pcatid']);
	}
	$getAllCategory = "";
	if(isset($_GET['pcatid']) != "")
	{
		$getAllCategory = ORM::for_table('jst_product_category')->where_not_equal('id',$_GET['pcatid'])->find_many();
	}
	else
	{
		$getAllCategory = ORM::for_table('jst_product_category')->find_many();	
	}
?>
<div id="page-wrapper">
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header">Add Product Category</h1>
	    </div>
	    <!-- /.col-lg-12 -->
	</div>	
	<div class="panel panel-default">
		<div class="panel-body">
			<form role="form" method="post" action="" data-toggle="validator">
				<div class="form-group">
					<label for="name">Category Name *</label>
					<input type="text" class="form-control" id="name" name="name" placeholder="Category Name" <?php if(isset($getPcat)) { ?>value="<?php echo $getPcat->name; ?>"<?php } ?>  required>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="description">Description *</label>
					<textarea class="form-control" id="description" name="description" required><?php if(isset($getPcat)) {  echo $getPcat->description;  } ?></textarea>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="parent_id">Parent Category</label>
					<select class="form-control" id="parent_id" name="parent_id">
						<option value="">None</option>
						<?php 
						foreach ($getAllCategory as $cat) {
							?>
							<option value="<?php echo $cat->id; ?>" <?php if(isset($getPcat)) { if($getPcat->parent_id == $cat->id) { echo "selected"; } } ?> ><?php echo $cat->name; ?></option>
							<?php
						}
						?>
					</select>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="making_charge">Making Charge</label>
					<input type="type" class="form-control" id="making_charge" name="making_charge" placeholder="Making Charge" <?php if(isset($getPcat)) { ?>value="<?php echo $getPcat->making_charge; ?>"<?php } ?> />
					<span class="help-block with-errors"></span>
				</div>
				<button type="submit" <?php if(isset($getPcat)) { ?>name="editpcat"<?php } else { ?>name="addpcat"<?php } ?> class="btn btn-warning">Save</button>
			</form>
		</div>
	</div>
</div>
<?php require_once('reusables/footer.php'); ?>