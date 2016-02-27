<?php require_once('reusables/header.php'); ?>
<?php
	//Add Pricing Rates
	if(isset($_POST['addadvancesale']))
	{
		$getAllPriceRatingsAdvance = ORM::for_table('jst_pricing_rate_type')->where_not_equal('status','D')->find_many();
		//Converting all Rating Present to a string 
		//This is a bad way of storing current day ratings
		//#TODO improve it
		$rateString = "";
		foreach ($getAllPriceRatingsAdvance as $prating) {
			$rateString .= $prating->id . ",". $prating->type_name."|"; 	
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
		echo "<script>window.location='listadvancesales.php';</script>";
	}

	//Edit Pricing Rates
	if(isset($_POST['editadvancesale']))
	{
		$advancesaleEditTbl = ORM::for_table('jst_pricing_rate_type')->find_one($_GET['advancesaleid']);
		$dataArr = array(
					    'type_name' 	=> $_POST['type_name'],
					    'type_value' 	=> $_POST['type_value'],
					    'status' 		=> $_POST['status'],
					    'updated_on' 	=> date('Y-m-d H:i:s')
					);
		$advancesaleEditTbl->set($dataArr);		
		$advancesaleEditTbl->save();
		echo "<script>window.location='listadvancesales.php';</script>";
	}
	


	//fetch Product Categories details 
	if(isset($_GET['advancesaleid']))
	{
		$getadvancesale = ORM::for_table('jst_pricing_rate_type')->find_one($_GET['advancesaleid']);
	}

	$getAllCustomersForAdvanceSale = ORM::for_table('jst_customers')->find_many();
	$getAllShopForAdvanceSale = ORM::for_table('jst_shop')->find_many();
?>
<div id="page-wrapper">
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header"><?php if(isset($getadvancesale)) { ?>Edit Advance Sale<?php } else { ?>Add Advance Sale<?php } ?></h1>
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
							<option value="<?php echo $customer->id; ?>"><?php echo $customer->card_id." ".$customer->fullname; ?></option>
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
							<option value="<?php echo $shop->id; ?>"><?php echo $shop->shop_name; ?></option>
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
					</tbody>
				</table>
				<input type="submit" value= "Save" <?php if(isset($getadvancesale)) { ?>name="editadvancesale"<?php } else { ?>name="addadvancesale"<?php } ?> class="btn btn-warning"/>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	var numberOfRows = 1;

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
		var rowToDel = elem.parentNode.parentNode;
		var parentNode = rowToDel.parentNode;
		parentNode.removeChild(rowToDel);
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
		createRatingDropdown(document.getElementById("populatePrType1"));
		$('#createNewRow').click(function(){
			createNewItemRow();
		});

	});
</script>
<?php require_once('reusables/footer.php'); ?>