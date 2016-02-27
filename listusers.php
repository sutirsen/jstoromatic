<?php
include('reusables/header.php'); 
$getAllUsers = ORM::for_table('jst_users')->order_by_desc('createdon')->find_many();
?>
<div id="page-wrapper">
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header">All Users</h1>
	    </div>
	    <!-- /.col-lg-12 -->
	</div>
	<a href="adduser.php"><button class="btn btn-success">Add User</button></a>
	<button class="btn btn-danger" onclick="deleteChecked('deletionForm','ids');">Delete Users</button>
	<div class="clearfix" style="height:10px;"></div>
	<table class="table table-bordered userListDataTable">
		<thead>
			<tr>
				<th><input type="checkbox" id="selectAll"/></th>
				<th>Name</th>
				<th>Email</th>
				<th>Contact</th>
				<th>Status</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			if(count($getAllUsers) == 0)
			{
				?>
				<tr>
					<td colspan="6">No users yet!</td>
				</tr>
				<?php
			}
			else
			{				
				foreach ($getAllUsers as $user) {
				?>
				<tr>
					<td><?php if($_SESSION['user']['id'] != $user->id) { ?><input type="checkbox" class="selectable" value='<?php echo $user->id; ?>'/><?php } else {?><span class="glyphicon  glyphicon-user"></span><?php } ?></td>
					<td><?php echo $user->firstname." ".$user->lastname; ?></td>
					<td><?php echo $user->email; ?></td>
					<td><?php echo $user->phnnumber; ?></td>
					<td><?php if($user->status == 'A') { echo "Active"; } else { echo "Deactive"; } ?></td>
					<td><button onclick="showDetails('<?php echo $user->id; ?>')" type="button" class="btn btn-info btn-sm">View</button> <a href="adduser.php?uid=<?php echo $user->id; ?>" class="btn btn-info btn-sm">Edit</a></td>
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