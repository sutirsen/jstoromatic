<?php
include('reusables/header.php'); 
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

$getAllProducts = ORM::for_table('jst_product')
					->table_alias('prod')
					->select('prod.*')
					->select('cat.name', 'category')
					->join('jst_product_category', array('prod.category_id', '=', 'cat.id'), 'cat')
					->order_by_desc('createdon')
					->find_many();
?>
<div id="page-wrapper">
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header">All Products</h1>
	    </div>
	    <!-- /.col-lg-12 -->
	</div>
	<a href="addproduct.php"><button class="btn btn-success">Add Product</button></a>
	<button class="btn btn-danger" onclick="deleteChecked('deletionForm','ids');">Delete Products</button>
	<div class="clearfix" style="height:10px;"></div>
	<table class="table table-bordered genericDataTable">
		<thead>
			<tr>
				<th><input type="checkbox" id="selectAll"/></th>
				<th>Name</th>
				<th>Description</th>
				<th>Category</th>
				<th>Stock</th>				
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			if(count($getAllProducts) == 0)
			{
				?>
				<tr>
					<td colspan="6">No products yet!</td>
				</tr>
				<?php
			}
			else
			{
				$allSoldItems = ORM::for_table('jst_sale_items')->find_many();
				$allSoldItemIDs = array();
				foreach ($allSoldItems as $soldItm) {
					array_push($allSoldItemIDs, $soldItm['item_id']);
				}
				foreach ($getAllProducts as $product) {
					if(count($allSoldItemIDs) > 0)
					{
						$stockData = ORM::for_table('jst_product_item')->where_not_in('id', $allSoldItemIDs)->where_equal('product_id', $product->id)->find_many();
					}
					else
					{
						$stockData = ORM::for_table('jst_product_item')->where_equal('product_id', $product->id)->find_many();	
					}
					
				?>
				<tr>
					<td><input type="checkbox" class="selectable" value='<?php echo $product->id; ?>'/></td>
					<td><?php echo $product->name; ?></td>
					<td><?php echo $product->description; ?></td>
					<td><?php echo getParentBreadCrumb($product->category_id); ?></td>
					<td><?php echo count($stockData); ?></td>
					<td><a href="addproduct.php?productid=<?php echo $product->id; ?>" class="btn btn-info btn-sm">Edit</a> <a href="listitems.php?product_id=<?php echo $product->id; ?>" class="btn btn-info btn-sm">Items</a></td>
				</tr>
				<?php 
				}
			}
			?>
		</tbody>
	</table>
</div>
<div style="display:none;">
	<form id="deletionForm" action="addproduct.php" method="post">
		<input type="hidden" name="ids" id="ids" value=""/>
		<input type="hidden" name="removeprod" value="1"/>
	</form>
</div>
<?php include('reusables/footer.php'); ?>