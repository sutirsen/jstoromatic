<?php
include('reusables/header.php'); 
/*********************** Helper Functions ***********************/
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
/********************* End Helper Functions *********************/

$getAllProductCategories = ORM::for_table('jst_product_category')->order_by_desc('name')->find_many();
?>
<div id="page-wrapper">
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header">Product Categories</h1>
	    </div>
	    <!-- /.col-lg-12 -->
	</div>
	<a href="addproductcategory.php" role="button" class="btn btn-success">Add Product Categories</a>
	<button class="btn btn-danger" onclick="deleteChecked('deletionForm','ids');">Delete Product Categories</button>
	<div class="clearfix" style="height:10px;"></div>
	<table class="table table-bordered genericDataTable">
		<thead>
			<tr>
				<th><input type="checkbox" id="selectAll"/></th>
				<th>Name</th>
				<th>Description</th>
				<th>Subsection of</th>
				<th>Making Charge</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			if(count($getAllProductCategories) == 0)
			{
				?>
				<tr>
					<td colspan="6">No product categories yet!</td>
				</tr>
				<?php
			}
			else
			{				
				foreach ($getAllProductCategories as $categories) {
				?>
				<tr>
					<td><input type="checkbox" class="selectable" value='<?php echo $categories->id; ?>'/></td>
					<td><?php echo $categories->name; ?></td>
					<td><?php echo $categories->description; ?></td>
					<td><?php if($categories->parent_id != "" && $categories->parent_id != 0) { echo getParentBreadCrumb($categories->parent_id); } else { echo "None"; } ?></td>
					<td><?php echo $categories->making_charge; ?></td>
					<td><a href="addproductcategory.php?pcatid=<?php echo $categories->id; ?>" role="button" class="btn btn-info btn-sm">Edit</a></td>
				</tr>
				<?php 
				}
			}
			?>
		</tbody>
	</table>
</div>

<div style="display:none;">
	<form id="deletionForm" action="addproductcategory.php" method="post">
		<input type="hidden" name="ids" id="ids" value=""/>
		<input type="hidden" name="removepcats" value="1"/>
	</form>
</div>
<?php include('reusables/footer.php'); ?>