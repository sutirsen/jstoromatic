<?php require_once('reusables/header.php'); ?>
<?php

	//delete code for users
	if(isset($_POST['removeadvancesales']) && isset($_POST['ids']))
	{
		$ids = trim($_POST['ids'],",");
		$id_arr = explode(",", $ids);
		$errorWhileDeletingAny = false;
		foreach ($id_arr as $id) {

			$advanceSale = ORM::for_table('jst_advance_sale')->find_one($id);
			if($advanceSale->sale_id == "")
			{
				$advanceSale->delete();
				ORM::for_table('jst_advance_sale_items')->where_equal('advance_sale_id',$id)->delete_many();
			}
			else
			{
				$errorWhileDeletingAny = true;
			}			
		}
		if($errorWhileDeletingAny)
		{
			addMessageFlash("success","custom","","","","Some of it were not deleted because sales record exist for them!");
		}
		else
		{
			addMessageFlash("success","custom","","","","All of them were deleted!");
		}
		echo "<script>window.location='listadvancesales.php';</script>";
	}

	//Add Pricing Rates
	if(isset($_POST['addadvancesale']))
	{
		$getAllPriceRatingsAdvance = ORM::for_table('jst_pricing_rate_type')->where_not_equal('status','D')->find_many();
		//Converting all Rating Present to a string 
		//This is a bad way of storing current day ratings
		//#TODO improve it
		$rateString = "";
		foreach ($getAllPriceRatingsAdvance as $prating) {
			$rateString .= $prating->id . ",". $prating->type_value."|"; 	
		} 
		$rateString = trim($rateString, "|");

		#Creating advance sale
		$advancesaleTbl = ORM::for_table('jst_advance_sale')->create();
		$advancesaleTbl->customer_id		= $_POST['customer_id'];
		$advancesaleTbl->rate_string		= $rateString;
		$advancesaleTbl->shop_id			= $_POST['shop_id'];
		$advancesaleTbl->created_on		= date('Y-m-d H:i:s');
		// Save the object to the database
		$advancesaleTbl->save();
		$advanceSaleId = $advancesaleTbl->id;

		#Creating procured item or cash record
		foreach ($_POST['itemtype'] as $sid => $itmType) {
			$advanceSaleItemTbl = ORM::for_table('jst_advance_sale_items')->create();
			$advanceSaleItemTbl->advance_sale_id 		= $advanceSaleId;
			$advanceSaleItemTbl->item_name 				= $_POST['itemname'][$sid];
			$advanceSaleItemTbl->item_type 				= $itmType;
			$advanceSaleItemTbl->purity 				= $_POST['itempurity'][$sid];
			$advanceSaleItemTbl->weightoramt 			= $_POST['itemweightoramt'][$sid];
			$advanceSaleItemTbl->item_price_rating_id 	= $_POST['itempricing'][$sid];
			$advanceSaleItemTbl->save();
		}

		addMessageFlash("success","custom","","","","Advance added to the system!");
		echo "<script>window.location='listadvancesales.php';</script>";
	}
	
	function decodeRatingString($rtString)
	{
		$ratingString = [];
		$intermRateString = explode("|", $rtString);
		foreach ($intermRateString as $idvalue) {
			$idValArr = explode(",", $idvalue);
			$rateObj = ORM::for_table('jst_pricing_rate_type')->find_one($idValArr[0]);
			$ratingString[$idValArr[0]] = $rateObj->type_name;
		}
		return $ratingString;
	}

	$rtStr = "";
	//fetch Product Categories details 
	if(isset($_GET['adid']))
	{
		$advanceSale = ORM::for_table('jst_advance_sale')->find_one($_GET['adid']);
		$advanceSalesItems = ORM::for_table('jst_advance_sale_items')->where_equal('advance_sale_id',$_GET['adid'])->find_many();		
		$rtStr = decodeRatingString($advanceSale->rate_string);

		//Deal with edit later
		if(isset($_POST['editadvancesale']))
		{
			$dataArr = array(
						    'customer_id' 	=> $_POST['customer_id'],
						    'shop_id' 		=> $_POST['shop_id']
						);
			$advanceSale->set($dataArr);		
			$advanceSale->save();
			ORM::for_table('jst_advance_sale_items')->where_equal('advance_sale_id',$_GET['adid'])->delete_many();

			foreach ($_POST['itemtype'] as $sid => $itmType) {
				$advanceSaleItemTbl = ORM::for_table('jst_advance_sale_items')->create();
				$advanceSaleItemTbl->advance_sale_id 		= $_GET['adid'];
				$advanceSaleItemTbl->item_name 				= $_POST['itemname'][$sid];
				$advanceSaleItemTbl->item_type 				= $itmType;
				$advanceSaleItemTbl->purity 				= $_POST['itempurity'][$sid];
				$advanceSaleItemTbl->weightoramt 			= $_POST['itemweightoramt'][$sid];
				$advanceSaleItemTbl->item_price_rating_id 	= $_POST['itempricing'][$sid];
				$advanceSaleItemTbl->save();
			}
			addMessageFlash("success","custom","","","","Advance edited successfully!");
			echo "<script>window.location='listadvancesales.php';</script>";
		}
	}

	$getAllCustomersForAdvanceSale = ORM::for_table('jst_customers')->find_many();
	$getAllShopForAdvanceSale = ORM::for_table('jst_shop')->find_many();

?>
<div id="page-wrapper">
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header"><?php if(isset($advanceSale)) { ?>Edit Advance Sale<?php } else { ?>Add Advance Sale<?php } ?></h1>
	    </div>
	    <!-- /.col-lg-12 -->
	</div>	
	<div class="panel panel-default">
		<div class="panel-body">
			<form role="form" method="post" action="" onsubmit="return validateForm()">
				<div class="form-group">
					<label for="type_name">Customer *</label>
					<select id="customer_id" name="customer_id" data-placeholder="Choose a Customer" class="chosen-select form-control">
						<option value=""></option>
						<?php 
						foreach ($getAllCustomersForAdvanceSale as $customer) {
							?>
							<option value="<?php echo $customer->id; ?>" <?php if(isset($_GET['adid'])) { if($advanceSale->customer_id == $customer->id){ echo "selected"; } } ?>><?php echo $customer->card_id." ".$customer->fullname; ?></option>
							<?php
						}
						?>
					</select>
					<span class="help-block with-errors" id="customerError"></span>
				</div>
				<div class="form-group">
					<label for="type_name">Shop *</label>
					<select id="shop_id" name="shop_id" class="form-control">
						<option value="">--Select Shop--</option>
						<?php 
						foreach ($getAllShopForAdvanceSale as $shop) {
							?>
							<option value="<?php echo $shop->id; ?>" <?php if(isset($_GET['adid'])) { if($advanceSale->shop_id == $shop->id){ echo "selected"; } } ?>><?php echo $shop->shop_name; ?></option>
							<?php
						}
						?>
					</select>
					<span class="help-block with-errors" id="shopError"></span>
				</div>

				<button id="createNewRow" class="btn btn-sm btn-info" type="button">Add Item</button>
				<div style="clear:both; height:5px;"></div>
				<div class="help-block with-errors" id="itemError"></div>
				<table class="table table-bordered" id="itemTable">
					<thead>
						<tr>
							<th>Item Name</th>
							<th>Type</th>
							<th>Purity</th>
							<th>Weight or Amount</th>
							<th>Pricing Type</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
					<?php 
							if(!isset($_GET['adid']))
							{
								?>
						<tr>
							
							<td>
								<input type="text" id="itemname_1" name="itemname[]"/>
							</td>
							<td>
								<select id="itemtype_1" name="itemtype[]" onchange="changeItemNameToCash(this)">
									<option value="O">Ornament</option>
									<option value="R">Raw</option>
									<option value="C">Cash</option>
								</select>
							</td>
							<td>
								<input type="text" id="itempurity_1" name="itempurity[]"/>
							</td>
							<td>
								<input type="text" id="itemweightoramt_1" name="itemweightoramt[]"/>
							</td>
							<td id="populatePrType1">
								
							</td>
							<td>
								
							</td>
						</tr>
						<?php
							}
							else
							{
								$cnt = 1;
								foreach ($advanceSalesItems as $itms) {
									?>
									<tr>										
										<td>
											<input type="text" id="itemname_<?php echo $cnt; ?>" name="itemname[]" value="<?php echo $itms->item_name; ?>"/>
										</td>
										<td>
											<select id="itemtype_<?php echo $cnt; ?>" name="itemtype[]" onchange="changeItemNameToCash(this)">
												<option value="O" <?php if($itms->item_type == "O") { echo "selected"; } ?>>Ornament</option>
												<option value="R" <?php if($itms->item_type == "R") { echo "selected"; } ?>>Raw</option>
												<option value="C" <?php if($itms->item_type == "C") { echo "selected"; } ?>>Cash</option>
											</select>
										</td>
										<td>
											<input type="text" id="itempurity_<?php echo $cnt; ?>" name="itempurity[]" value="<?php echo $itms->purity; ?>" <?php if($itms->item_type == "C") { echo "readonly"; } ?>/>
										</td>
										<td>
											<input type="text" id="itemweightoramt_<?php echo $cnt; ?>" name="itemweightoramt[]" value="<?php echo $itms->weightoramt; ?>"/>
										</td>
										<td>
										 	<?php if($itms->item_type == "C") { echo "<span id='removespan_".$cnt."'>Not Applicable</span>"; } ?>
											<select id="itempricing_<?php echo $cnt; ?>" name="itempricing[]" <?php if($itms->item_type == "C") { echo "style='display:none;'"; } ?>>
											<?php
											foreach ($rtStr as $rid => $rname) {
												?>
												<option value="<?php echo $rid; ?>" <?php if($itms->item_price_rating_id == $rid) { echo "selected"; } ?>><?php echo $rname; ?></option>
												<?php
											}
											?>
											</select>
										</td>
										<td>
											<input type="button" class="btn btn-sm btn-info" id="itemDelete_<?php echo $cnt; ?>" onclick="removeRow(this)" value="Delete" />
										</td>
									</tr>
									<?php
									$cnt++;
								}
							}
						?>
					</tbody>
				</table>
				<input type="submit" value= "Save" <?php if(isset($advanceSale)) { ?>name="editadvancesale"<?php } else { ?>name="addadvancesale"<?php } ?> class="btn btn-warning"/>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
<?php 
	if(isset($_GET['adid']))
	{
			?>
		var numberOfRows = <?php echo count($advanceSalesItems); ?>;
		var unaffectedRowNum = <?php echo count($advanceSalesItems); ?>;
		<?php
	}
	else
	{
		?>
		var numberOfRows = 1;
		var unaffectedRowNum = 1;
		<?php
	}
	?>

	var ratingIds = [
		<?php
		$getAllPriceRatingsAdvance = ORM::for_table('jst_pricing_rate_type')->where_not_equal('status','D')->find_many();
		foreach ($getAllPriceRatingsAdvance as $prating) {
		 	?>
			{"id":"<?php echo $prating->id; ?>", "typename":"<?php echo $prating->type_name; ?>"}, 	
		 	<?php
		 } 
		?>
	];

	function changeItemNameToCash(elem)
	{
		var rowId = elem.id.split("_");
		rowId = rowId[1];
		var itemNameElem = document.getElementById("itemname_"+rowId);
		if(elem.value == "C")
		{
			itemNameElem.value = "Cash";
			var itemPurityElem = document.getElementById("itempurity_"+rowId);
			itemPurityElem.value = "";
			itemPurityElem.readOnly = true;
			var itemPricingParentTd = document.getElementById("itempricing_"+rowId).parentNode;
			document.getElementById("itempricing_"+rowId).style.display = "none";
			var spanTxt = document.createElement("SPAN");
			spanTxt.id="removespan_"+rowId;
			spanTxt.innerHTML = "Not Applicable";
			itemPricingParentTd.appendChild(spanTxt);
		}
		else
		{
			var itemPurityElem = document.getElementById("itempurity_"+rowId);
			itemPurityElem.readOnly = false;
			var itemPricingParentTd = document.getElementById("itempricing_"+rowId).parentNode;
			document.getElementById("itempricing_"+rowId).style.display = "";
			if(document.getElementById("removespan_"+rowId))
			{
				var spanElm = document.getElementById("removespan_"+rowId);
				itemPricingParentTd.removeChild(spanElm);
			}			
		}
	}

	function removeRow(elem){
		if(unaffectedRowNum != 1)
		{
			unaffectedRowNum--;
			var rowToDel = elem.parentNode.parentNode;
			var parentNode = rowToDel.parentNode;
			parentNode.removeChild(rowToDel);	
		}
		else
		{
			alert("Can not delete the last row");
		}
	}

	function generateRow()
	{
		//alert("Hellow");
		var itemTypeOptions = {
			"O":"Ornament",
			"R":"Raw",
			"C":"Cash"
		};
		numberOfRows++;
		unaffectedRowNum++;
		var row = document.createElement('TR');
		//Create Item Name 
		var itemNameTd = document.createElement('TD');
		var itemNameInput = document.createElement('input');
		itemNameInput.type = "text";
		itemNameInput.id = "itemname_"+numberOfRows;
		itemNameInput.name = "itemname[]";
		itemNameTd.appendChild(itemNameInput);
		row.appendChild(itemNameTd);

		//Create Type
		var itemTypeTd = document.createElement('TD');
		var itemTypeSelect = document.createElement("SELECT");
		for(var typ in itemTypeOptions)
		{
			var optionItmType = document.createElement("option");
			optionItmType.value = typ;
			optionItmType.text = itemTypeOptions[typ];	
			itemTypeSelect.appendChild(optionItmType);
		}
		itemTypeSelect.id = "itemtype_"+numberOfRows;
		itemTypeSelect.name = "itemtype[]";
		itemTypeSelect.addEventListener('change', function(){
															var selfObj = this;
															changeItemNameToCash(selfObj);
														});
		itemTypeTd.appendChild(itemTypeSelect);
		row.appendChild(itemTypeTd);		
		
		//Create Item Purity 
		var itemPurityTd = document.createElement('TD');
		var itemPurityInput = document.createElement('input');
		itemPurityInput.type = "text";
		itemPurityInput.id = "itempurity_"+numberOfRows;
		itemPurityInput.name = "itempurity[]";
		itemPurityTd.appendChild(itemPurityInput);
		row.appendChild(itemPurityTd);
		
		//Create Item Weight or amount (in case of cash) 
		var itemWeightTd = document.createElement('TD');
		var itemWeightInput = document.createElement('input');
		itemWeightInput.type = "text";
		itemWeightInput.id = "itemweightoramt_"+numberOfRows;
		itemWeightInput.name = "itemweightoramt[]";
		itemWeightTd.appendChild(itemWeightInput);
		row.appendChild(itemWeightTd);
		
		//Create Item Weight or amount (in case of cash) 
		var itempriceratingTd = document.createElement('TD');
		itempriceratingTd = createRatingDropdown(itempriceratingTd)
		row.appendChild(itempriceratingTd);
		
		//Action is still blank
		var itemActionTd = document.createElement('TD');
		var deleteButton = document.createElement("input");
		deleteButton.type = "button";
		deleteButton.className = "btn btn-sm btn-info";
		deleteButton.id = "itemDelete_"+numberOfRows;
		deleteButton.addEventListener('click', function(){
															var selfObj = this;
															removeRow(selfObj);
														});
		deleteButton.value = "Delete";
		itemActionTd.appendChild(deleteButton);
		row.appendChild(itemActionTd);

		return row;

	}

	function createRatingDropdown(parentelem)
	{
		//var myParentContainer = document.getElementById(parentid);
		var myParentContainer = parentelem;
		var selectElm = document.createElement("SELECT");
		selectElm.id = "itempricing_"+numberOfRows;
		selectElm.name = "itempricing[]";
		myParentContainer.innerHTML = "";
		//Create and append the options
		for (var rt in ratingIds) {
		    var option = document.createElement("option");
		    option.value = ratingIds[rt].id;
		    option.text = ratingIds[rt].typename;
		    selectElm.appendChild(option);
		}
		myParentContainer.appendChild(selectElm);
		return myParentContainer;
	}

	function createNewItemRow()
	{
		var itemTbl = document.getElementById("itemTable");
		var tblBodyArr = itemTbl.getElementsByTagName('tbody');
		var tblBody = tblBodyArr[0];
		/*var tblRows = tblBody.getElementsByTagName('tr');
		for (var i = 0; i < tblRows.length; i++) {
		    if (tblRows[i].getElementsByTagName("td").length > 0) {
		        var tblColData = tblRows[i].getElementsByTagName("td");
		        var lastCol = tblColData[tblColData.length - 1].innerHTML;
		        alert(lastCol);
		    }
		}*/
		tblBody.appendChild(generateRow());
	}

	//Writing custom validation as this won't be possible by autovalidation plugins
	function validateForm()
	{
		//alert("Hi");
		var triggerReturnFalse = false;
		document.getElementById("customerError").innerHTML = "";
		document.getElementById("shopError").innerHTML = "";
		document.getElementById("itemError").innerHTML = "";
		if(document.getElementById("customer_id").value == "")
		{
			document.getElementById("customerError").innerHTML = "Please select a customer";
			triggerReturnFalse = true;
		}
		else
		{
			document.getElementById("customerError").innerHTML = "";	
		}
		if(document.getElementById("shop_id").value == "")
		{
			document.getElementById("shopError").innerHTML = "Please select a shop";
			triggerReturnFalse = true;
		}
		else
		{
			document.getElementById("shopError").innerHTML = "";	
		}

		var itemTbl = document.getElementById("itemTable");
		var tblBodyArr = itemTbl.getElementsByTagName('tbody');
		var tblBody = tblBodyArr[0];
		var tblRows = tblBody.getElementsByTagName('tr');
		var completeErrorMessage = "";
		var rowCount = 1;
		for (var i = 0; i < tblRows.length; i++) {
			var errorMessage = "";
		    if (tblRows[i].getElementsByTagName("td").length > 0) {
		    	var tblColData = tblRows[i].getElementsByTagName("td");
		    	var itemnameVal = "";
		    	var itemtypeVal = "";
		    	var itempurityVal = "";
		    	var itemweightoramtVal = "";
		    	var itempricingVal = "";
		    	//Data collector section
		    	for(var tdI in tblColData)
		    	{
		    		if(tdI == (tblColData.length - 1))
		    		{
		    			break;
		    		}
		    		var tdChildren = tblColData[tdI].children;
		    		if(tdChildren.length > 1)
		    		{
		    			continue;
		    		}
		    		var childId = tdChildren[0].id;
		    		childId = childId.split("_");
		    		childId = childId[0];
		    		//alert(tdChildren[0].value);
		    		eval(childId+"Val = '"+tdChildren[0].value+"'");
		    		//this[childId+"Val"] = tdChildren[0].value;
		    	}
		    	if(itemtypeVal != "C")
		    	{
		    		if(itemnameVal == "")
		    		{
		    			triggerReturnFalse = true;
		    			if(errorMessage == "")
		    			{
		    				errorMessage +="In Row ["+rowCount+"] : ";
		    			}
		    			errorMessage += "Item Name can not be empty - ";
		    		}

		    		if(itempricingVal == "")
		    		{
		    			triggerReturnFalse = true;
		    			if(errorMessage == "")
		    			{
		    				errorMessage +="In Row ["+rowCount+"] : ";
		    			}
		    			errorMessage += "Item Pricing type can not be empty - ";
		    		}	
		    	}

		    	if(itemweightoramtVal == "")
		    	{
		    		triggerReturnFalse = true;
		    		if(errorMessage == "")
		    		{
		    			errorMessage +="In Row ["+rowCount+"] : ";
		    		}
		    		if(itemtypeVal == "C")
		    		{
		    			errorMessage += "Amount can not be empty - ";	
		    		}
		    		else
		    		{
		    			errorMessage += "Weight can not be empty - ";	
		    		}		    		
		    	}
		        rowCount++;
		        if(errorMessage != "")
		        {
		        	errorMessage = errorMessage.substr(0,(errorMessage.length-3));
		        	if(completeErrorMessage != "")
		        	{
		        		completeErrorMessage += "<br/>";	
		        	}
		        	completeErrorMessage += errorMessage;		        	
		        }
		        // var lastCol = tblColData[tblColData.length - 1].innerHTML;
		        // alert(lastCol);
		    }
		}
		document.getElementById("itemError").innerHTML = completeErrorMessage
		if(triggerReturnFalse)
		{
			return false;	
		}
		else
		{
			return true;
		}
		
	}

	$(document).ready(function(){
		<?php 
		if(!isset($_GET['adid']))
		{
		?>
		createRatingDropdown(document.getElementById("populatePrType1"));
		<?php
		}
		?>
		$('#createNewRow').click(function(){
			createNewItemRow();
		});

	});
</script>
<?php require_once('reusables/footer.php'); ?>