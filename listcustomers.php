<?php
include('reusables/header.php'); 
$getAllCustomers = ORM::for_table('jst_customers')->order_by_desc('created_on')->find_many();
?>
<div id="page-wrapper">
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header">All Customers</h1>
	    </div>
	    <!-- /.col-lg-12 -->
	</div>
	<a href="addcustomer.php"><button class="btn btn-success">Add Customer</button></a>
	<button class="btn btn-danger" onclick="deleteChecked('deletionForm','ids');">Delete customers</button>
	<div class="clearfix" style="height:10px;"></div>
	<table class="table table-bordered genericDataTable">
		<thead>
			<tr>
				<th><input type="checkbox" id="selectAll"/></th>
				<th>Name</th>
				<th>Card Number</th>
				<th>Contact</th>
				<th>Email</th>
				<th>No of Sales</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			if(count($getAllCustomers) == 0)
			{
				?>
				<tr>
					<td colspan="7">No customers yet!</td>
				</tr>
				<?php
			}
			else
			{				
				foreach ($getAllCustomers as $customer) {
				?>
				<tr>
					<td><input type="checkbox" class="selectable" value='<?php echo $customer->id; ?>'/></td>
					<td><?php echo $customer->fullname; ?></td>
					<td><?php echo $customer->card_id; ?></td>
					<td><?php echo $customer->phnnumber; ?></td>
					<td><?php echo $customer->email; ?></td>
					<?php
					$allSalesByCustomer = ORM::for_table('jst_sales')->where(array(
																	                'to_id' 	=> $customer->id,
																	                'to_type' 	=> 'C'
																	            ))
																	            ->find_many();
					?>
					<td><?php echo count($allSalesByCustomer); ?></td>
					<td><button onclick="showDetails('<?php echo $customer->id; ?>')" type="button" class="btn btn-info btn-sm">View</button> <a href="addcustomer.php?customerid=<?php echo $customer->id; ?>" class="btn btn-info btn-sm">Edit</a></td>
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
		$.get("helpers/getcustomerdetails.php?customerid="+id, function(data, status){
			if(status == "success")
			{
				data = JSON.parse(data);
				if(data.status == "success")
				{
					var customerTbl = document.createElement("TABLE");
					customerTbl.style.borderSpacing = '5px';
					customerTbl.style.borderCollapse = 'separate';
					for(var i in data.data)
					{
						var row = customerTbl.insertRow();
						var legends = row.insertCell(0);
						legends.innerHTML = "<b>"+i+"</b>";
						var dataVal = row.insertCell(1);
						dataVal.innerHTML = data.data[i];
					}

					var insertcustomerData = document.getElementById('insertcustomerDataHere');
					insertcustomerData.innerHTML = "";
					insertcustomerData.appendChild(customerTbl);
					$('#customerView').modal();
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
<div class="modal fade" id="customerView" tabindex="-1" role="dialog" aria-labelledby="customerView">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="customerView">View Details</h4>
      </div>
      <div class="modal-body" id="insertcustomerDataHere">

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div style="display:none;">
	<form id="deletionForm" action="addcustomer.php" method="post">
		<input type="hidden" name="ids" id="ids" value=""/>
		<input type="hidden" name="removecustomers" value="1"/>
	</form>
</div>
<?php include('reusables/footer.php'); ?>