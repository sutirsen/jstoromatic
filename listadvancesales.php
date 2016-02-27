<?php
include('reusables/header.php'); 
$getAllAdvanceSales = ORM::for_table('jst_advance_sale')->table_alias('ad')
													    ->select('ad.*')
													    ->select('cus.fullname', 'customer_name')
													    ->select('shop.shop_name', 'shop_name')
													    ->join('jst_customers', array('ad.customer_id', '=', 'cus.id'), 'cus')
													    ->join('jst_shop', array('ad.customer_id', '=', 'cus.id'), 'cus')
													    ->order_by_desc('createdon')->find_many();

?>
<div id="page-wrapper">
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header">All Advances</h1>
	    </div>
	    <!-- /.col-lg-12 -->
	</div>
	<a href="adduser.php"><button class="btn btn-success">Add Advance Sale</button></a>
	<button class="btn btn-danger" onclick="deleteChecked('deletionForm','ids');">Delete</button>
	<div class="clearfix" style="height:10px;"></div>
	<table class="table table-bordered userListDataTable">
		<thead>
			<tr>
				<th><input type="checkbox" id="selectAll"/></th>
				<th>Customer Name</th>
				<th>Shop Name</th>
				<th>No of Items</th>
				<th>Sale Made</th>
				<th>Created</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			if(count($getAllAdvanceSales) == 0)
			{
				?>
				<tr>
					<td colspan="7">No advances towards any sales!</td>
				</tr>
				<?php
			}
			else
			{				
				foreach ($getAllAdvanceSales as $adsale) {
					#Get items information
					$itemsRec = ORM::for_table('jst_advance_sale_items')->where_equals('advance_sale_id',$adsale->id)->find_many();
				?>
				<tr>
					<td><input type="checkbox" class="selectable" value='<?php echo $adsale->id; ?>'/></td>
					<td><?php echo $adsale->customer_name; ?></td>
					<td><?php echo $adsale->shop_name; ?></td>
					<td><?php echo count($itemsRec); ?></td>
					<td><?php if($adsale->sale_id != "") { echo "Yes"; } else { echo "No"; } ?></td>
					<td><?php echo $adsale->created_on; ?></td>
					<td><button onclick="showDetails('<?php echo $adsale->id; ?>')" type="button" class="btn btn-info btn-sm">View</button> <a href="createadvanceinvoice.php?uid=<?php echo $adsale->id; ?>" class="btn btn-info btn-sm">Reciept</a> <a href="addadvancesale.php?uid=<?php echo $adsale->id; ?>" class="btn btn-info btn-sm">Edit</a></td>
				</tr>
				<?php 
				}
			}
			?>
		</tbody>
	</table>
</div>
<script type="text/javascript">
	function showDetails(id)
	{
		$.get("helpers/getuserdetails.php?uid="+id, function(data, status){
			if(status == "success")
			{
				data = JSON.parse(data);
				if(data.status == "success")
				{
					var userTbl = document.createElement("TABLE");
					userTbl.style.borderSpacing = '5px';
					userTbl.style.borderCollapse = 'separate';
					for(var i in data.data)
					{
						var row = userTbl.insertRow();
						var legends = row.insertCell(0);
						legends.innerHTML = "<b>"+i+"</b>";
						var dataVal = row.insertCell(1);
						dataVal.innerHTML = data.data[i];
					}

					var insertUserData = document.getElementById('insertUserDataHere');
					insertUserData.innerHTML = "";
					insertUserData.appendChild(userTbl);
					$('#userView').modal();
				}
				else
				{
					bootbox.alert("Something is going wrong, there is some error with the server");
				}
			}
			else
			{
				bootbox.alert("Something is going wrong, there is some error with the server");
			}
		});
	}
</script>
<div class="modal fade" id="userView" tabindex="-1" role="dialog" aria-labelledby="userView">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="userView">View Details</h4>
      </div>
      <div class="modal-body" id="insertUserDataHere">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div style="display:none;">
	<form id="deletionForm" action="adduser.php" method="post">
		<input type="hidden" name="ids" id="ids" value=""/>
		<input type="hidden" name="removeusers" value="1"/>
	</form>
</div>
<?php include('reusables/footer.php'); ?>