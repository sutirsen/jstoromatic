<?php
include('reusables/header.php'); 
$productId = "";
if(isset($_GET['product_id']))
{
	$productId = $_GET['product_id'];
}
$getAllItems = "";
$materialRates = ORM::for_table('jst_pricing_rate_type')->where_equal('status', "E")->find_many();
function searchForMaterialType($materialId){
	foreach ($GLOBALS["materialRates"] as $rateObj) {
		if($rateObj->id == $materialId){
			return $rateObj->type_name;
		}
	}
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
if($productId != "")
{
	$getAllItems = ORM::for_table('jst_product_item')
						->table_alias('prod_item')
						->select('prod_item.*')
						->select('prod.name', 'product_name')
						->select('prod.category_id', 'category_id')
						->join('jst_product', array('prod_item.product_id', '=', 'prod.id'), 'prod')
						->where_equal('prod_item.product_id',trim($productId))
						->order_by_desc('createdon')
						->find_many();
}
else
{
	$getAllItems = ORM::for_table('jst_product_item')
						->table_alias('prod_item')
						->select('prod_item.*')
						->select('prod.name', 'product_name')
						->select('prod.category_id', 'category_id')
						->join('jst_product', array('prod_item.product_id', '=', 'prod.id'), 'prod')
						->order_by_desc('createdon')
						->find_many();
}

?>
<div id="page-wrapper">
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header">Stock Items</h1>
	    </div>
	    <!-- /.col-lg-12 -->
	</div>
	<?php if(isset($_GET['product_id'])) { ?><a href="addproductitems.php?pid=<?php echo $_GET['product_id']; ?>" role="button" class="btn btn-success">Add Items</a><?php } ?>
	<div class="clearfix" style="height:10px;"></div>
	<table class="table table-bordered genericDataTable">
		<thead>
			<tr>
				<th><input type="checkbox" id="selectAll"/></th>
				<th>Product Name</th>
				<th>Item Name</th>
				<th>Unique ID</th>
				<th>Weight</th>
				<th>Purity</th>
				<th>Price</th>				
				<th>Making Charge</th>				
				<th>Material Type (Pricing)</th>				
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			if(count($getAllItems) == 0)
			{
				?>
				<tr>
					<td colspan="9">No Items yet!</td>
				</tr>
				<?php
			}
			else
			{				
				foreach ($getAllItems as $item) {

					$ifAlreadySold = ORM::for_table('jst_sale_items')->where_equal('item_id', $item->id)->find_many();
					if(count($ifAlreadySold) != 0)
					{
						continue;
					}

					//Checking for if it is up for sale
					
					$ifTempBooked = ORM::for_table('jst_temp_booked')->where_equal('item_id', $item->id)->find_many();
					if(count($ifTempBooked) != 0)
					{
						continue;
					}
				?>
				<tr>
					<td><input type="checkbox" class="selectable" value='<?php echo $item->id; ?>'/></td>
					<td><span class="pname" 
								data-toggle="tooltip" 
								data-placement="bottom" 
								title="<?php echo getParentBreadCrumb($item->category_id); ?>"><?php echo $item->product_name; ?></span></td>
					<td><?php echo $item->item_name; ?></td>
					<td><?php echo $item->uniqueid; ?></td>
					<td><?php echo $item->weight; ?></td>
					<td><?php echo $item->purity; ?></td>
					<td><?php echo $item->pricewithoutmkchrg; ?></td>
					<td><?php echo $item->makingcharge; ?></td>
					<td><?php echo searchForMaterialType($item->pricing_rate_type_id); ?></td>
					<td>
					<?php if(isset($_GET['product_id'])) { ?>
					<a href="addproductitems.php?pid=<?php echo $_GET['product_id']; ?>&iid=<?php echo $item->id; ?>" class="btn btn-info btn-sm">Edit</a>
					<?php }
					else {
						?>
						<a href="addproductitems.php?iid=<?php echo $item->id; ?>" class="btn btn-info btn-sm">Edit</a>
						<?php
						} 
						?>
						<?php 
						if(isset($_SESSION['cartData']) && in_array($item->id, $_SESSION['cartData']['items']))
						{
							?>
							<a href="addremovecart.php?iid=<?php echo $item->id; ?>&act=rem" class="btn btn-info btn-sm">Remove from Cart</a>
							<?php
						}
						else
						{
							?>
							<a href="addremovecart.php?iid=<?php echo $item->id; ?>&act=add" class="btn btn-info btn-sm">Add to Cart</a>
							<?php
						}
						?>
						
					</td>
				</tr>
				<?php 
				}
			}
			?>
		</tbody>
	</table>
</div>
<div style="display:none;">
	<form id="deletionForm" action="addproductitems.php" method="post">
		<input type="hidden" name="ids" id="ids" value=""/>
		<input type="hidden" name="removeitem" value="1"/>
	</form>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$('.pname').tooltip();
	});
</script>
<?php include('reusables/footer.php'); ?>