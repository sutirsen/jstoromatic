<?php require_once('reusables/header.php'); ?>
<?php
	//Add Pricing Rates
	if(isset($_POST['addCustomer']))
	{
		$subscribed_to_ad = "N";
		if(isset($_POST['subscribed_to_ad']))
		{
			$subscribed_to_ad = "Y";
		}
		$customerTbl = ORM::for_table('jst_customers')->create();
		$customerTbl->card_id			= $_POST['card_id'];
		$customerTbl->fullname			= $_POST['fullname'];
		$customerTbl->phnnumber			= $_POST['phnnumber'];
		$customerTbl->altphnnumber		= $_POST['altphnnumber'];
		$customerTbl->tempaddress		= $_POST['tempaddress'];
		$customerTbl->permanentaddress	= $_POST['permanentaddress'];
		$customerTbl->email				= $_POST['email'];
		$customerTbl->dateofbirth		= $_POST['dateofbirth'];
		$customerTbl->remarks			= $_POST['remarks'];
		$customerTbl->subscribed_to_ad	= $subscribed_to_ad;
		$customerTbl->created_on		= date('Y-m-d H:i:s');
		// Save the object to the database
		$customerTbl->save();
		addMessageFlash("success","adedwc",$customerTbl->id(),"customers",'fullname');
		echo "<script>window.location='listcustomers.php';</script>";
	}

	//Edit Pricing Rates
	if(isset($_POST['editCustomer']))
	{
		$subscribed_to_ad = "N";
		if(isset($_POST['subscribed_to_ad']))
		{
			$subscribed_to_ad = "Y";
		}
		$customerEditTbl = ORM::for_table('jst_customers')->find_one($_GET['customerid']);
		$dataArr = array(
					    'fullname'			=> $_POST['fullname'],
					    'phnnumber'			=> $_POST['phnnumber'],
					    'altphnnumber'		=> $_POST['altphnnumber'],
					    'tempaddress'		=> $_POST['tempaddress'],
					    'permanentaddress'	=> $_POST['permanentaddress'],
					    'email'				=> $_POST['email'],
					    'dateofbirth'		=> $_POST['dateofbirth'],
					    'remarks'			=> $_POST['remarks'],
					    'subscribed_to_ad'	=> $subscribed_to_ad 
					);
		$customerEditTbl->set($dataArr);		
		$customerEditTbl->save();
		addMessageFlash("success","edtdwc",$_GET['customerid'],"customers",'fullname');
		echo "<script>window.location='listcustomers.php';</script>";
	}
	
	if(isset($_POST['removecustomers']) && isset($_POST['ids']))
	{
		$ids = trim($_POST['ids'],",");
		$id_arr = explode(",", $ids);

		foreach ($id_arr as $id) {
			$ifanysaledata = ORM::for_table('jst_sales')->where(array(
											                'to_id' 	=> $id,
											                'to_type' 	=> 'C'
											            ))
											            ->find_many();
			if(count($ifanysaledata) > 0)
			{
				addMessageFlash("danger","custom","","","","Unable to delete, sales record exists!");
				echo "<script>window.location='listcustomers.php';</script>";
				die();
			}
		}
		foreach ($id_arr as $id) {
			$customerToDel = ORM::for_table('jst_customers')->find_one($id);
			$customerToDel->delete();
		}
		addMessageFlash("success","custom","","","","Desired records deleted successfully!");
		echo "<script>window.location='listcustomers.php';</script>";
	}

	//fetch Product Categories details 
	if(isset($_GET['customerid']))
	{
		$getCustomer = ORM::for_table('jst_customers')->find_one($_GET['customerid']);
	}
?>
<div id="page-wrapper">
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header"><?php if(isset($getCustomer)) { ?>Edit Pricing Category<?php } else { ?>Add Pricing Category<?php } ?></h1>
	    </div>
	    <!-- /.col-lg-12 -->
	</div>	
	<div class="panel panel-default">
		<div class="panel-body">
			<form role="form" method="post" action="" data-toggle="validator">
				<?php if(!isset($getCustomer)) { ?>
				<div class="form-group">
					<label class="control-label" for="card_id">Card Number *</label>
					<div class="input-group">
						<input type="text" class="form-control" id="card_id" name="card_id" placeholder="Card Number" required>
	                    <span class="input-group-btn">
	                        <button class="btn btn-default" id="generateCardId" type="button"><i class="glyphicon glyphicon-refresh"></i>
	                        </button>
	                    </span>
                    </div>
					<span class="help-block with-errors" id="cardIdError"></span>
				</div>
				<?php } else { ?>
				<b>Customer ID : <?php echo $getCustomer->card_id; ?></b><br/><br/>
				<?php } ?>
				<div class="form-group">
					<label for="fullname">Full Name *</label>
					<input type="text" class="form-control" id="fullname" name="fullname" placeholder="Full Name" <?php if(isset($getCustomer)) { ?>value="<?php echo $getCustomer->fullname; ?>"<?php } ?>  required>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="phnnumber">Phone Number *</label>
					<input type="number" class="form-control" id="phnnumber" name="phnnumber" placeholder="Phone Number" <?php if(isset($getCustomer)) { ?>value="<?php echo $getCustomer->phnnumber; ?>"<?php } ?>  required>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="altphnnumber">Alternative Phone Number</label>
					<input type="number" class="form-control" id="altphnnumber" name="altphnnumber" placeholder="Alternative Phone Number" <?php if(isset($getCustomer)) { ?>value="<?php echo $getCustomer->altphnnumber; ?>"<?php } ?>>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="tempaddress">Current Address *</label>
					<textarea class="form-control" id="tempaddress" name="tempaddress" required><?php if(isset($getCustomer)) { echo $getCustomer->tempaddress; } ?></textarea>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="permanentaddress">Permanent Address *</label> <input type="checkbox" id="sameastempaddress"> Same as current address
					<textarea class="form-control" id="permanentaddress" name="permanentaddress" required><?php if(isset($getCustomer)) { echo $getCustomer->permanentaddress; } ?></textarea>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="email">Email </label>
					<input type="email" class="form-control" id="email" name="email" placeholder="Email Address" <?php if(isset($getCustomer)) { ?>value="<?php echo $getCustomer->email; ?>"<?php } ?>  required>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="dateofbirth">Date of Birth</label>
					<input type="text" class="form-control" id="dateofbirth" name="dateofbirth" <?php if(isset($getCustomer)) { ?>value="<?php echo $getCustomer->dateofbirth; ?>"<?php } ?>  placeholder="Date Of Birth">
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="remarks">Remarks</label>
					<textarea class="form-control" id="remarks" name="remarks" ><?php if(isset($getCustomer)) { echo $getCustomer->remarks; } ?></textarea>
					<span class="help-block with-errors"></span>
				</div>
				<div class="checkbox">
					<label>
						<input type="checkbox" id="subscribed_to_ad" name="subscribed_to_ad" <?php if(isset($getCustomer) && $getCustomer->subscribed_to_ad == "Y") { echo "checked"; } ?>> 
						Subscribe to Advertisement ?
					</label>
				</div>
				<button type="submit" <?php if(isset($getCustomer)) { ?>name="editCustomer"<?php } else { ?>name="addCustomer"<?php } ?> class="btn btn-warning">Save</button>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function () {
		$('#dateofbirth').datepicker({
			format: 'yyyy-mm-dd'
		});

		$('#sameastempaddress').click(function(){
			if($(this).prop('checked') == true)
			{
				$('#permanentaddress').val($('#tempaddress').val());	
			}
			else
			{
				$('#permanentaddress').val("");		
			}
		});

		$('#generateCardId').click(function(){
			$.post("helpers/createuniqueid.php",
			{
			  len: "5",
			  tblname: "customers",
			  tblfld: "card_id"
			},
			function(data,status){
			   if(data != "error")
			   {
			   		$('#card_id').val(data);
			   }
			   else
			   {
			   		$('#card_id').val("");
			   		$('#cardIdError').html("Error generating card ID");
			   }
			});
		});
	});
</script>
<?php require_once('reusables/footer.php'); ?>