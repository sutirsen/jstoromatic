<?php require_once('reusables/header.php'); ?>
<?php
	//Add code for User Type
	if(isset($_POST['addproduct']))
	{
		$productTbl = ORM::for_table('jst_product')->create();
		$productTbl->name			= $_POST['name'];
		$productTbl->description	= trim($_POST['description']);
		$productTbl->category_id	= trim($_POST['category_id']);
		$productTbl->save();
		// Save the object to the database
		addMessageFlash("success","adedwc",$productTbl->id(),"product",'name');
		echo "<script>window.location='listproducts.php';</script>";
	}

	//Edit code for User Type
	if(isset($_POST['editproduct']))
	{
		$productEditTbl = ORM::for_table('jst_product')->find_one($_GET['productid']);
		$dataArr = array(
					    	'name' 			=> $_POST['name'],
					    	'description' 	=> trim($_POST['description']),
					    	'category_id' 	=> trim($_POST['category_id'])
						);
		$productEditTbl->set($dataArr);		
		$productEditTbl->save();
		addMessageFlash("success","edtdwc",$_GET['productid'],"product",'name');
		echo "<script>window.location='listproducts.php';</script>";
	}

	//Edit code for users
	if(isset($_POST['removeprod']) && isset($_POST['ids']))
	{
		$ids = trim($_POST['ids'],",");
		$id_arr = explode(",", $ids);
		foreach ($id_arr as $id) {
			$usr = ORM::for_table('jst_product')->find_one($id);
			$usr->delete();
		}
		addMessageFlash("success","dltd");
		echo "<script>window.location='listproducts.php';</script>";
	}
	$proCats = array();
	$getProductCategory = ORM::for_table('jst_product_category')->find_many();
	
	foreach ($getProductCategory as $cats) {
		$proCats[$cats->id] = getParentBreadCrumb($cats->id);
	}
	//fetch User Type details 
	if(isset($_GET['productid']))
	{
		$getproduct = ORM::for_table('jst_product')->find_one($_GET['productid']);		
	}

	function getParentBreadCrumb($id)
	{
		$pcat = ORM::for_table('jst_product_category')->find_one($id);
		if($pcat->parent_id != "" && $pcat->parent_id != 0)
		{
			return getParentBreadCrumb($pcat->parent_id) . " > ". $pcat->name;
		}
		else
		{
			return $pcat->name;
		}
	}
?>
<div id="page-wrapper">
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header"><?php if(isset($getproduct)) { ?>Edit<?php } else { ?>Add <?php } ?> Product</h1>
	    </div>
	    <!-- /.col-lg-12 -->
	</div>	
	<div class="panel panel-default">
		<div class="panel-body">
			<form role="form" method="post" action="" data-toggle="validator">
				<div class="form-group">
					<label for="name">Product Name *</label>
					<input type="text" class="form-control" id="name" name="name" placeholder="Product Name" <?php if(isset($getproduct)) { ?>value="<?php echo $getproduct->name; ?>"<?php } ?>  required>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="description">Product Description</label>
					<textarea class="form-control" id="description" name="description"><?php if(isset($getproduct)) {  echo $getproduct->description;  } ?></textarea>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="category_id">Category</label>
					<select class="form-control" id="category_id" name="category_id">
					<?php 
						foreach ($proCats as $catid => $cat) {
						?>	
						<option value="<?php echo $catid; ?>" <?php if(isset($getproduct)) { if($getproduct->category_id == $catid) { echo "selected"; } } ?> ><?php echo $cat; ?></option>
						<?php
						}
					?>
					</select>
					<span class="help-block with-errors"></span>
				</div>
				<button type="submit" <?php if(isset($getproduct)) { ?>name="editproduct"<?php } else { ?>name="addproduct"<?php } ?> class="btn btn-warning">Save</button>
			</form>
		</div>
	</div>
</div>
<?php require_once('reusables/footer.php'); ?>