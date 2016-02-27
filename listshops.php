<?php
include('reusables/header.php'); 

$getAllshops = ORM::for_table('jst_shop')->find_many();
?>
<div id="page-wrapper">
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header">Shops</h1>
	    </div>
	    <!-- /.col-lg-12 -->
	</div>
	<a href="addshop.php" role="button" class="btn btn-success">Add Shop</a>
	<div class="clearfix" style="height:10px;"></div>
	<table class="table table-bordered genericDataTable">
		<thead>
			<tr>
				<th><input type="checkbox" id="selectAll"/></th>
				<th>Name</th>
				<th>Address</th>
				<th>Phone</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			if(count($getAllshops) == 0)
			{
				?>
				<tr>
					<td colspan="5">No shops yet!</td>
				</tr>
				<?php
			}
			else
			{				
				foreach ($getAllshops as $shop) {
				?>
				<tr>
					<td><input type="checkbox" class="selectable" value='<?php echo $shop->id; ?>'/></td>
					<td><?php echo $shop->shop_name; ?></td>
					<td><?php echo $shop->shop_address; ?></td>
					<td><?php echo $shop->shop_phone; ?></td>
					<td><a href="addshop.php?shopid=<?php echo $shop->id; ?>" role="button" class="btn btn-info btn-sm">Edit</a></td>
				</tr>
				<?php 
				}
			}
			?>
		</tbody>
	</table>
</div>
<?php include('reusables/footer.php'); ?>