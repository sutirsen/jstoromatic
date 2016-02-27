<?php require_once('reusables/header.php'); ?>
<?php
	//Add code for users
	if(isset($_POST['adduser']))
	{
		$userTbl = ORM::for_table('jst_users')->create();
		$userTbl->firstname		= $_POST['firstname'];
		$userTbl->lastname		= $_POST['lastname'];
		$userTbl->email			= $_POST['email'];
		$userTbl->dateofbirth	= $_POST['dateofbirth'];
		$userTbl->password		= $_POST['password'];
		$userTbl->phnnumber		= $_POST['phnnumber'];
		$userTbl->altphnnumber	= $_POST['altphnnumber'];
		$userTbl->address		= $_POST['address'];
		$userTbl->status		= $_POST['status'];
		$userTbl->type			= $_POST['type'];
		$userTbl->createdon 	= date('Y-m-d H:i:s');
		// Save the object to the database
		$userTbl->save();
		echo "<script>window.location='listusers.php';</script>";
	}

	//Edit code for users
	if(isset($_POST['edituser']))
	{
		$usrEditTbl = ORM::for_table('jst_users')->find_one($_GET['uid']);
		$dataArr = array(
					    'firstname' 	=> $_POST['firstname'],
					    'lastname' 		=> $_POST['lastname'],
					    'dateofbirth' 	=> $_POST['dateofbirth'],
					    'phnnumber' 	=> $_POST['phnnumber'],
					    'altphnnumber' 	=> $_POST['altphnnumber'],
					    'address' 		=> $_POST['address'],
					    'status' 		=> $_POST['status'],
					    'type' 			=> $_POST['type']
					);
		if($_POST['password'] != "")
		{
			$dataArr['password'] = $_POST['password'];
		}
		$usrEditTbl->set($dataArr);		
		$usrEditTbl->save();
	}

	//fetch user details 
	if(isset($_GET['uid']))
	{
		$getUser = ORM::for_table('jst_users')->find_one($_GET['uid']);
	}
	$getAllUserType = ORM::for_table('jst_user_type')->find_many();


	//delete code for users
	if(isset($_POST['removeusers']) && isset($_POST['ids']))
	{
		$ids = trim($_POST['ids'],",");
		$id_arr = explode(",", $ids);
		foreach ($id_arr as $id) {
			$usr = ORM::for_table('jst_users')->find_one($id);
			$usr->delete();
		}
		echo "<script>window.location='listusers.php';</script>";
	}
?>
<div id="page-wrapper">
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header">Add User</h1>
	    </div>
	    <!-- /.col-lg-12 -->
	</div>	
	<div class="panel panel-default">
		<div class="panel-body">
			<form role="form" method="post" action="" data-toggle="validator">
				<div class="form-group">
					<label for="firstname">First Name *</label>
					<input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" <?php if(isset($getUser)) { ?>value="<?php echo $getUser->firstname; ?>"<?php } ?>  required>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="lastname">Last Name *</label>
					<input type="text" class="form-control" id="lastname" name="lastname" <?php if(isset($getUser)) { ?>value="<?php echo $getUser->lastname; ?>"<?php } ?>  placeholder="Last Name" required>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="email">Email *</label>
					<input type="email" class="form-control" data-remote="helpers/duplicatecheck.php" id="email" name="email" <?php if(isset($getUser)) { ?>value="<?php echo $getUser->email; ?>" disabled<?php } ?>  placeholder="Email" required>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="password">Password <?php if(isset($getUser)) { ?>(Keep empty if change is not required)<?php } else { echo "*"; } ?></label>
					<input type="password" class="form-control" id="password" name="password"  <?php if(!isset($getUser)) { ?>required<?php } ?>>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="phnnumber">Phone Number *</label>
					<input type="text" class="form-control" id="phnnumber" name="phnnumber" <?php if(isset($getUser)) { ?>value="<?php echo $getUser->phnnumber; ?>"<?php } ?>  placeholder="Phone Number" required>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="altphnnumber">Alternative Phone Number</label>
					<input type="text" class="form-control" id="altphnnumber" name="altphnnumber" <?php if(isset($getUser)) { ?>value="<?php echo $getUser->altphnnumber; ?>"<?php } ?>  placeholder="Alternative Phone Number">
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="address">Address *</label>
					<textarea class="form-control" id="address" name="address" placeholder="Address" required><?php if(isset($getUser)) { echo $getUser->address; } ?></textarea>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="dateofbirth">Date of Birth</label>
					<input type="text" class="form-control" id="dateofbirth" name="dateofbirth" <?php if(isset($getUser)) { ?>value="<?php echo $getUser->dateofbirth; ?>"<?php } ?>  placeholder="Date Of Birth">
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="status">Account Status *</label>
					<select class="form-control" id="status" name="status" required>
						<option value="A" <?php if(isset($getUser)) { if($getUser->status == "A") { echo "selected"; } } ?> >Active</option>
						<option value="D"  <?php if(isset($getUser)) { if($getUser->status == "D") { echo "selected"; } } ?> >Disabled</option>
					</select>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="type">User Type *</label>
					<select class="form-control" id="type" name="type" required>
					<?php 
						foreach ($getAllUserType as $usertype) 
						{
							?>
							<option value="<?php echo $usertype->id; ?>"  <?php if(isset($getUser)) { if($getUser->type == $usertype->id) { echo "selected"; } } ?> ><?php echo $usertype->type_name; ?></option>
							<?php 
						}
					?>
					</select>
					<span class="help-block with-errors"></span>
				</div>
				<button type="submit" <?php if(isset($getUser)) { ?>name="edituser"<?php } else { ?>name="adduser"<?php } ?> class="btn btn-warning">Save</button>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		$('#dateofbirth').datepicker({
			format: 'yyyy-mm-dd'
		});
	});
</script>
<?php require_once('reusables/footer.php'); ?>