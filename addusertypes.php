<?php require_once('reusables/header.php'); ?>
<?php
	//Add code for User Type
	if(isset($_POST['addutype']))
	{
		$utypeTbl = ORM::for_table('jst_user_type')->create();
		$utypeTbl->type_name			= $_POST['type_name'];
		$utypeTbl->type_description		= trim($_POST['type_description']);
		$utypeTbl->save();
		$userTypeId = $utypeTbl->id();
		foreach ($_POST['filenames'] as $flname) {
			$prmTbl = ORM::for_table('jst_page_permission')->create();
			$prmTbl->user_type_id = $userTypeId;
			$prmTbl->page_name = $flname;
			$prmTbl->save();
		}
		// Save the object to the database
		
		echo "<script>window.location='listusertypes.php';</script>";
	}

	//Edit code for User Type
	if(isset($_POST['editutype']))
	{
		$utypeEditTbl = ORM::for_table('jst_user_type')->find_one($_GET['utypeid']);
		$dataArr = array(
					    'type_name' 		=> $_POST['type_name'],
					    'type_description' 	=> trim($_POST['type_description'])
					);
		$utypeEditTbl->set($dataArr);		
		$utypeEditTbl->save();
		ORM::for_table('jst_page_permission')->where_equal('user_type_id', $_GET['utypeid'])->where_not_equal('page_name','addusertypes.php')->delete_many();
		foreach ($_POST['filenames'] as $flname) {
			$prmTbl = ORM::for_table('jst_page_permission')->create();
			$prmTbl->user_type_id = $_GET['utypeid'];
			$prmTbl->page_name = $flname;
			$prmTbl->save();
		}
	}

	//Edit code for users
	if(isset($_POST['removeusertypes']) && isset($_POST['ids']))
	{
		$ids = trim($_POST['ids'],",");
		$id_arr = explode(",", $ids);
		foreach ($id_arr as $id) {
			$usr = ORM::for_table('jst_user_type')->find_one($id);
			$usr->delete();
			ORM::for_table('jst_page_permission')->where_equal('user_type_id', $id)->delete_many();
		}
		echo "<script>window.location='listusertypes.php';</script>";
	}

	//fetch User Type details 
	if(isset($_GET['utypeid']))
	{
		$getutype = ORM::for_table('jst_user_type')->find_one($_GET['utypeid']);
		$getPerms = ORM::for_table('jst_page_permission')->where_equal('user_type_id',$_GET['utypeid'])->find_many();
		$processPerms = array();
		foreach ($getPerms as $prms) {
			if(!isset($processPerms[$prms->user_type_id]))
			{
				$processPerms[$prms->user_type_id] = array();				
			}
			array_push($processPerms[$prms->user_type_id], $prms->page_name);
		}
	}
?>
<script>
	function selectAll()
	{
		$('#filenames option').prop('selected', true);
	}

	function deselectAll()
	{
		$('#filenames option').prop('selected', false);
	}

</script>
<div id="page-wrapper">
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header"><?php if(isset($getutype)) { ?>Edit<?php } else { ?>Add <?php } ?> User Type</h1>
	    </div>
	    <!-- /.col-lg-12 -->
	</div>	
	<div class="panel panel-default">
		<div class="panel-body">
			<form role="form" method="post" action="" data-toggle="validator">
				<div class="form-group">
					<label for="type_name">User Type Name *</label>
					<input type="text" class="form-control" id="type_name" name="type_name" placeholder="Type Name" <?php if(isset($getutype)) { ?>value="<?php echo $getutype->type_name; ?>"<?php } ?>  required>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="type_description">Type Description *</label>
					<textarea class="form-control" id="type_description" name="type_description" required><?php if(isset($getutype)) {  echo $getutype->type_description;  } ?></textarea>
					<span class="help-block with-errors"></span>
				</div>
				<div class="form-group">
					<label for="filenames">Files</label>
					<select class="form-control" id="filenames" name="filenames[]" multiple>
					<?php 
						$getAllFileName = scandir(getcwd());
						foreach ($getAllFileName as $files) {
							if(preg_match('/.*\.php$/', $files))
							{
								if($files != "connect.php" && $files != "logout.php" && $files != "index.php" && $files != "cannotaccess.php")
								{
									if(isset($getPerms) && $files == "addusertypes.php" && in_array($files, $processPerms[$getutype->id]) && $_SESSION['user']['type'] == $_GET['utypeid'])
									{
										continue;
									}
								?>	
									<option value="<?php echo $files; ?>" <?php if(isset($getPerms)) { if(in_array($files, $processPerms[$getutype->id])) { echo "selected"; } } ?> ><?php echo trim($files,".php"); ?></option>
								<?php
								}
							}
						}

					?>
					</select>
					<span onclick="selectAll()" style="cursor:pointer; color:blue;">Select All</span> / <span onclick="deselectAll()" style="cursor:pointer; color:blue;">Deselect All</span>
					<span class="help-block with-errors"></span>
				</div>
				<button type="submit" <?php if(isset($getutype)) { ?>name="editutype"<?php } else { ?>name="addutype"<?php } ?> class="btn btn-warning">Save</button>
			</form>
		</div>
	</div>
</div>
<?php require_once('reusables/footer.php'); ?>