<?php
include('reusables/header.php'); 
$getUserTypes = ORM::for_table('jst_user_type')->find_many();
?>
<div id="page-wrapper">
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header">All User Types</h1>
	    </div>
	    <!-- /.col-lg-12 -->
	</div>
	<a href="addusertypes.php"><button class="btn btn-success">Add User Types</button></a>
	<button class="btn btn-danger" onclick="deleteChecked('deletionForm','ids');">Delete User Types</button>
	<div class="clearfix" style="height:10px;"></div>
	<table class="table table-bordered genericDataTable">
		<thead>
			<tr>
				<th><input type="checkbox" id="selectAll"/></th>
				<th>Name</th>
				<th>Description</th>
				<th>Action</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			if(count($getUserTypes) == 0)
			{
				?>
				<tr>
					<td colspan="6">No users yet!</td>
				</tr>
				<?php
			}
			else
			{				
				foreach ($getUserTypes as $userType) {
				?>
				<tr>
					<td><input type="checkbox" class="selectable" value='<?php echo $userType->id; ?>'/></td>
					<td><?php echo $userType->type_name; ?></td>
					<td><?php echo $userType->type_description; ?></td>
					<td><a href="addusertypes.php?utypeid=<?php echo $userType->id; ?>" class="btn btn-info btn-sm">Edit</a></td>
				</tr>
				<?php 
				}
			}
			?>
		</tbody>
	</table>
</div>
<div style="display:none;">
	<form id="deletionForm" action="addusertypes.php" method="post">
		<input type="hidden" name="ids" id="ids" value=""/>
		<input type="hidden" name="removeusertypes" value="1"/>
	</form>
</div>
<?php include('reusables/footer.php'); ?>