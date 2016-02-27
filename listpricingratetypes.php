<?php
include('reusables/header.php'); 

$getAllPricingRateTypes = ORM::for_table('jst_pricing_rate_type')->find_many();
?>
<div id="page-wrapper">
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header">Pricing Rate Types</h1>
	    </div>
	    <!-- /.col-lg-12 -->
	</div>
	<a href="addpricingratetypes.php" role="button" class="btn btn-success">Add Pricing Rate Types</a>
	<div class="clearfix" style="height:10px;"></div>
	<table class="table table-bordered genericDataTable">
		<thead>
			<tr>
				<th><input type="checkbox" id="selectAll"/></th>
				<th>Name</th>
				<th>Rate</th>
				<th>Status</th>
				<th>Last Updated On</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			if(count($getAllPricingRateTypes) == 0)
			{
				?>
				<tr>
					<td colspan="6">No product pricing rate types yet!</td>
				</tr>
				<?php
			}
			else
			{				
				foreach ($getAllPricingRateTypes as $pricingratetype) {
				?>
				<tr>
					<td><input type="checkbox" class="selectable" value='<?php echo $pricingratetype->id; ?>'/></td>
					<td><?php echo $pricingratetype->type_name; ?></td>
					<td><?php echo $pricingratetype->type_value; ?></td>
					<td><?php if($pricingratetype->status == "E") { echo "Active"; } else { echo "Disabled"; } ?></td>
					<td><?php echo $pricingratetype->updated_on; ?></td>
					<td><a href="addpricingratetypes.php?pricingrateid=<?php echo $pricingratetype->id; ?>" role="button" class="btn btn-info btn-sm">Edit</a></td>
				</tr>
				<?php 
				}
			}
			?>
		</tbody>
	</table>
</div>
<?php include('reusables/footer.php'); ?>