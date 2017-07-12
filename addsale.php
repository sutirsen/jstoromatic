<?php require_once('reusables/header.php'); ?>
<?php
	
	//Some of the obligation we are dealing with
	/*********************************************************************************************************************************************
	* 1. Any sales can be assoicated with only one advance sale (lets reduce the complexity of interaction)
	* 2. All cash advances will be deducted from final amount
	* 3. The rating system will be changed to advance sale creation date as stored in date string
	* 4. The making charge is recorded in
	*		-	Items from product (Will be considered as static making charge) [And will be prioritized over product category making charge]
	*		-	Product categories (Will be considered as per unit weight making charge ) [making charge X weight = final making charge]
	*		-	Manual entry in this page [Over rides above two]
	* 5. The priorities of making charge will be given first to manual entry
	* 6. On selection of customer all of the advance which is not associated with any sales will be loaded
	* 7. Selection of advances will be done through a modal (We need to store the advances incase the advance need to be reapplied)
	* 8. Rates will be multiplied with weight to get the value
	*
	***********************************************************************************************************************************************/
	if(!isset($_SESSION['cartData']) || !isset($_SESSION['cartData']['items'])){
		echo "<script>window.location.href = 'listitems.php';</script>";
	}
	//delete code for users
	if(isset($_POST['removesales']) && isset($_POST['ids']))
	{
		//Deal with deletes later
	}

	//Add Pricing Rates
	if(isset($_POST['addsale']))
	{
		//First objective is implement add
	}
	
	//This will prepare the rate section
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
	$allParentCats = [];
	function getParentBreadCrumb($id)
	{
		$pcat = ORM::for_table('jst_product_category')->find_one($id);
		if($pcat->parent_id != "" && $pcat->parent_id != 0)
		{
			$GLOBALS["allParentCats"][$pcat->name] = $pcat->making_charge;
			getParentBreadCrumb($pcat->parent_id);
		}
		else
		{
			$GLOBALS["allParentCats"][$pcat->name] = $pcat->making_charge;
		}
	}

	function getCartData(){
		$items = $_SESSION['cartData']['items'];
		$preparedItems = [];
		foreach ($items as $itemId) {
			$tmpItem = ORM::for_table('jst_product_item')
								->table_alias('prod_item')
								->select('prod_item.*')
								->select('prod.name', 'product_name')
								->select('prod.category_id', 'category_id')
								->select('prod_category.name', "category_name")
								->select('prod_category.making_charge', "category_making_charge")
								->select('prod_category.parent_id', "category_parent_id")
								->join('jst_product', array('prod_item.product_id', '=', 'prod.id'), 'prod')
								->join('jst_product_category', array('prod.category_id', '=', 'prod_category.id'), 'prod_category')
								->find_one(trim($itemId));
			$preparedItems[$itemId] = $tmpItem; 
		}
		return $preparedItems;
	}

	$rtStr = "";
	//fetch Product Categories details 
	if(isset($_GET['saleid']))
	{
		//Deal with fetch later
	}

	//Initial setup
	$getAllCustomers = ORM::for_table('jst_customers')->find_many();
	$getAllShopForSale = ORM::for_table('jst_shop')->find_many();

?>
<div id="page-wrapper">
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header"><?php if(isset($_GET['saleid'])) { ?>Edit Sale<?php } else { ?>Checkout<?php } ?></h1>
	    </div>
	    <!-- /.col-lg-12 -->
	</div>	
	<div class="panel panel-default">
		<div class="panel-body">
			<form role="form" method="post" action="" onsubmit="return validateForm()">
				<div class="form-group">
					<label for="type_name">Customer Sale</label>
					<select id="customer_id" name="customer_id" data-placeholder="Choose a Customer" class="chosen-select form-control">
						<option value=""></option>
						<?php 
						foreach ($getAllCustomers as $customer) {
							?>
							<option value="<?php echo $customer->id; ?>"><?php echo $customer->card_id." ".$customer->fullname; ?></option>
							<?php
						}
						?>
					</select>
					<span class="help-block with-errors" id="customerError"></span>
				</div>
				<div class="panel panel-primary" style="display:none;">
					<div class="panel-heading"><h3 class="panel-title">Advance Sale</h3></div>
					<div class="panel-body" id="advanceSaleHolder">
					</div>
					<input type="hidden" id="appliedAdvanceId" name="appliedAdvanceId"/>
				</div>
				<div class="form-group">
					<label for="type_name">Shop *</label>
					<select id="shop_id" name="shop_id" class="form-control">
						<option value="">--Select Shop--</option>
						<?php 
						foreach ($getAllShopForSale as $shop) {
							?>
							<option value="<?php echo $shop->id; ?>"><?php echo $shop->shop_name; ?></option>
							<?php
						}
						?>
					</select>
					<span class="help-block with-errors" id="shopError"></span>
				</div>
				<div class="checkbox">
				    <label>
				      <input type="checkbox" name="nonVat" id="nonVat"/> Non-Vat Sale
				    </label>
				  </div>
				<div id="cartTable" style="display:none;"></div>
				<input type="submit" value= "Checkout" <?php if(isset($_GET['saleid'])) { ?>name="editsale"<?php } else { ?>name="addsale"<?php } ?> class="btn btn-warning"/>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
var sale = {};
(function(obj){
	var advanceMadeByCustomer;
	var customerId = "";
	var originalRatingId;
	// PHP JS Mix - Used for getting ratings
	var ratingIds = [
		<?php
		$getAllPriceRatingsAdvance = ORM::for_table('jst_pricing_rate_type')->where_not_equal('status','D')->find_many();
		foreach ($getAllPriceRatingsAdvance as $prating) {
		 	?>
			{"id":"<?php echo $prating->id; ?>", "typename":"<?php echo $prating->type_name; ?>", "typeval":"<?php echo $prating->type_value; ?>"}, 	
		 	<?php
		 } 
		?>
	];

	// PHP JS Mix - Used for getting cart items 
	<?php $cartItemDetails = getCartData(); ?>
	var cartItems = {
		<?php 
			foreach ($cartItemDetails as $itemId => $details) {
				?>
				"<?php echo $itemId; ?>" : {
					"item_name" 				: "<?php echo $details->item_name; ?>",
					"product_name"				: "<?php echo $details->category_name." / ".$details->product_name; ?>",
					"uniqueid" 					: "<?php echo $details->uniqueid; ?>",
					"weight" 					: "<?php echo $details->weight; ?>",
					"purity" 					: "<?php echo $details->purity; ?>",
					"fixedprice" 				: "",
					"item_making_charge" 		: "<?php echo $details->makingcharge; ?>",
					"rating_id" 				: "<?php echo $details->pricing_rate_type_id; ?>",
					"category_making_charge"	: "<?php echo $details->category_making_charge; ?>",
					"category_parent_id"		: "<?php echo $details->category_parent_id; ?>",
					"category_parent_hirarchy"	: {<?php getParentBreadCrumb($details->category_id);
															foreach($allParentCats as $k => $v){
																echo "'".$k."':'".$v."',";
															}
													 ?>}
				},
				<?php 
				$allParentCats = [];
			}
		?>
	};

	var finalSaleData = {};

	var finalDeductionData = [];

	obj.getRatings = function(){
		return _.cloneDeep(ratingIds);
	};

	obj.searchWithRatingId = function(rateId){
		var rtObjFinal;
		if(rateId && rateId != ""){
			_(ratingIds).forEach(function(rtObj){
				if(Number(rtObj["id"]) == Number(rateId)){
					rtObjFinal = rtObj;
				}
			});
			if(rtObjFinal){
				return rtObjFinal;
			} else {
				return false;
			}
		} else {
			return false;
		}
	};

	obj.init = function(){
		$("#customer_id").chosen().change(function(ev){
			sale.getAdvanceSalesForUser(ev.target);
			sale.unsetAdvanceId();
		});
		document.getElementById("nonVat").addEventListener("click", function(){
			sale.listCart();
		}, false);
		sale.listCart();
	}

	obj.setAdvanceId = function(advanceid){
		$("#appliedAdvanceId").val(advanceid);
		sale.listCart();
	};

	obj.unsetAdvanceId = function(){
		$("#appliedAdvanceId").val("");
		sale.listCart();
	};

	obj.dump = function(){
		console.log(JSON.stringify(finalSaleData));
		console.log(JSON.stringify(finalDeductionData));
	}

	obj.cartConsole = function(){
		console.log(ratingIds);
	};

	obj.listCart = function(){
		var advanceSaleId = null, deAct = null;
		var advanceSaleObj = null;
		var advanceObj = null;
		finalSaleData = null;
		finalSaleData = {};
		finalDeductionData = null;
		finalDeductionData = [];
		if($("#appliedAdvanceId").val() == ""){
			deAct = true;
		} else {
			advanceSaleId = $("#appliedAdvanceId").val();
		}
		if(deAct){
			if(originalRatingId){
				ratingIds = _.cloneDeep(originalRatingId);
			}
		} else if(advanceSaleId){
			advanceObj = _.cloneDeep(advanceMadeByCustomer);
			// If present switch rating hash
			if(!originalRatingId){
				originalRatingId = sale.getRatings();
			}
			_(advanceObj).forEach(function(adSaleItm){
				if(adSaleItm["advanceid"] == advanceSaleId){
					advanceSaleObj = _.cloneDeep(adSaleItm);
					ratingIds = [];
					_(adSaleItm["ratestring"]).forEach(function(rtDet, rtId){
						var tmpRtOb = {};
						tmpRtOb["id"] = rtId;
						tmpRtOb["typename"] = rtDet["name"];
						tmpRtOb["typeval"] = rtDet["value"];
						ratingIds.push(tmpRtOb);
					});
				}
			});
		}
		var calculatePrice = function(fixedPrice, weight, rateid, deductedWeight){
			if(advanceSaleId && advanceSaleId!= ""){
				//TODO : Take care of this
				if(fixedPrice && fixedPrice != "" && _.isNumber(Number(fixedPrice)) && Number(fixedPrice) != 0 && deductedWeight){
					var rtObj = sale.searchWithRatingId(rateid);
					fixedPrice -= Number(deductedWeight) * Number(rtObj["typeval"]);
				}
			}
			if(fixedPrice && fixedPrice != "" && _.isNumber(Number(fixedPrice)) && Number(fixedPrice) != 0){
				return Number(fixedPrice);
			} else if(weight && rateid) {
				var rate = 0;
				var rtObj;
				if(rtObj = sale.searchWithRatingId(rateid)){
					return Number(weight) * Number(rtObj["typeval"]);
				} else {
					return 0;
				}
			} else {
				return 0;
			}
		};

		var calculateMakingCharge = function(itmMakingChrg, weight, category_making_charge, category_parent_id){
			if(itmMakingChrg && itmMakingChrg != "" && _.isNumber(Number(itmMakingChrg)) && Number(itmMakingChrg) != 0){
				return Number(itmMakingChrg);
			} else if(weight && category_making_charge && Number(category_making_charge) != 0) {
				return Number(weight) * Number(category_making_charge);
			} else if(weight && category_parent_id && category_parent_id != "") {
				$.get("helpers/getCategoryMakingChargeHierarchy.php?catid="+category_parent_id, function(data, status){
					if(status == "success"){
						data = JSON.parse(data);
						if(data.status == "success"){
							_(data.data).forEach(function(catVal, catName){
								if(catVal && catVal != "" && catVal != 0){
									return Number(weight) * Number(catVal);
								}
							});
						} else {
							return false;
						}
					} else {
						return false;
					}
				});
			} else {
				return false;
			}
		};

		var saveDeductionObject = function(advanceSaleObject, actualWeight, itmId){
			var tmpDeductionDescription = {};
			tmpDeductionDescription["item_id"] 			= itmId;
			tmpDeductionDescription["previous_weight"] 	= actualWeight;
			if(Number(advanceSaleObject["weightoramt"]) > actualWeight){
				tmpDeductionDescription["current_weight_advance"] = Number(advanceSaleObject["weightoramt"]) - actualWeight;
				tmpDeductionDescription["current_weight_item"] = 0;				
			} else {
				if((Number(advanceSaleObject["weightoramt"]) - actualWeight) == 0){
					tmpDeductionDescription["current_weight_advance"] = 0
					tmpDeductionDescription["current_weight_item"] = 0;
				} else {
					tmpDeductionDescription["current_weight_advance"] = 0
					tmpDeductionDescription["current_weight_item"] = actualWeight - advanceSaleObject["weightoramt"];
				}				
			}
			tmpDeductionDescription["advancesaleItem"] = advanceSaleObject;
			finalDeductionData.push(tmpDeductionDescription);
		};

		var processAllFinalDeductions = function(totalPrice, tbody){
			var finalPriceNeedtobeCalculated = false;
			if(advanceSaleId && !deAct){
				_(advanceSaleObj["items"]).forEach(function(itm){
					if(Number(itm["weightoramt"]) != 0){
						finalPriceNeedtobeCalculated = true;
						if(itm["itmtype"] == "O"){
							var deductionTdDesc;
							var deductionTdAmt;
							var dedTr = crel(crel("tr"), deductionTd = crel("td",{"colspan":8}), deductionTdAmt = crel("td"));
							var rtObj = sale.searchWithRatingId(itm["item_price_rating_id"]);
							var totalDeductedPrice = Number(itm["weightoramt"]) * Number(rtObj["typeval"]);

							finalDeductionData.push({
																				"itmid": "Cash Deduction",
																				"deductionFor" : "O",
																				"deductionAmount" : totalDeductedPrice,
																				"advancesaleItem" : itm
																			});

							deductionTd.innerHTML = "Deducted Cash for <b>"+itm["itmname"]+"</b>";
							deductionTdAmt.innerHTML = totalDeductedPrice;
							tbody.appendChild(dedTr);
						} else {
							var deductionTd;
							var deductionTdAmt;
							var dedTr = crel(crel("tr"), deductionTd = crel("td",{"colspan":8}), deductionTdAmt = crel("td"));
							var totalDeductedPrice = Number(itm["weightoramt"]);

							finalDeductionData.push({
																				"itmid": "Cash Deduction",
																				"deductionFor" : "C",
																				"deductionAmount" : totalDeductedPrice,
																				"advancesaleItem" : itm
																			});

							deductionTd.innerHTML = "Deducted Cash Amount";
							deductionTdAmt.innerHTML = totalDeductedPrice;
							totalPrice -= totalDeductedPrice;
							tbody.appendChild(dedTr);
						}
					}
				});
			}
			
			if(finalPriceNeedtobeCalculated){
				crel(tbody, crel(crel("tr"), crel(crel("td"), crel("b", "Final Price after all deductions")), crel("td",{"colspan":"7"}), crel("td",totalPrice)));
			}
		};

		var cartTbl = crel("table",{"class":"table"});
		crel(cartTbl, crel(crel("thead"), crel(crel("tr"), 
											crel("th","Item Name"),
											crel("th","Category/Product"),
											crel("th","Unique ID"),
											crel("th","Weight"),
											crel("th","Purity"),
											crel("th","Rating"),
											crel("th","Price"),
											crel("th","Making Charge"),
											crel("th","Total"))));
		var tbody;
		crel(cartTbl, tbody = crel("tbody"));
		var totalPrice = 0;
		var totalPriceWithoutDeductionForVat = 0;
		_(cartItems).forEach(function(itemDetails, itemId){
			finalSaleData[itemId] = {};
			var itmMakingChrg = itemDetails["item_making_charge"];
			// Have a single data source of weight - subject to modification
			var weight = itemDetails["weight"];
			var saveWeightForVat = itemDetails["weight"];
			var weightDeductionObj;
			var category_making_charge = itemDetails["category_making_charge"];
			var category_parent_id = itemDetails["category_parent_id"];
			var tmpTr = crel("tr");

			var processNormal = ["item_name","product_name","uniqueid","weight","purity"];
			var priceWithOutMakingCharge = 0;
			_(itemDetails).forEach(function(componentValue, componentName){
				if(_.indexOf(processNormal, componentName) >= 0){
					
					if(componentName == "weight"){
						// Highly Complex weight calculation
						if(advanceSaleId && !deAct){
							var ratingId = itemDetails["rating_id"];
							//var itmIndex = 0;
							for(var itmIndex in advanceSaleObj["items"]){
								if(advanceSaleObj["items"][itmIndex]["itmtype"] == "O" 
										&& advanceSaleObj["items"][itmIndex]["weightoramt"] != 0 
										&& advanceSaleObj["items"][itmIndex]["item_price_rating_id"] == ratingId){
									if(Number(advanceSaleObj["items"][itmIndex]["weightoramt"]) > weight){
										saveDeductionObject(_.cloneDeep(advanceSaleObj["items"][itmIndex]), weight, itemId);
										weightDeductionObj = _.cloneDeep(advanceSaleObj["items"][itmIndex]);
										advanceSaleObj["items"][itmIndex]["weightoramt"] = Number(advanceSaleObj["items"][itmIndex]["weightoramt"]) - weight;
										weightDeductionObj["weightoramt"] = weight;
										weight = 0;
										
									} else {
										saveDeductionObject(_.cloneDeep(advanceSaleObj["items"][itmIndex]), weight, itemId);
										weightDeductionObj = _.cloneDeep(advanceSaleObj["items"][itmIndex]);
										if((Number(advanceSaleObj["items"][itmIndex]["weightoramt"]) - weight) == 0){
											weight = 0;
										} else {
											weight = weight - advanceSaleObj["items"][itmIndex]["weightoramt"];
										}
										advanceSaleObj["items"][itmIndex]["weightoramt"] = 0;
										
									}
								}
							}


							/*_(advanceSaleObj["items"]).forEach(function(adItmObj){
								console.log(JSON.stringify(adItmObj));
								if(adItmObj["itmtype"] == "O" && adItmObj["item_price_rating_id"] == ratingId){
									if(adItmObj["weightoramt"] > weight){
										weight = 0;
										adItmObj["weightoramt"] = Number(adItmObj["weightoramt"]) - weight;
										weightDeductionObj = _.cloneDeep(adItmObj);
										weightDeductionObj["weightoramt"] = weight;
									} else {
										if((adItmObj["weightoramt"] - weight) == 0){
											weight = 0;
										} else {
											weight = weight - adItmObj["weightoramt"];
										}
										weightDeductionObj = _.cloneDeep(adItmObj);
										advanceSaleObj["items"].splice(itmIndex, 1);
									}
								}
								itmIndex++;
							});*/
							finalSaleData[itemId][componentName] = weight;
						} else {
							finalSaleData[itemId][componentName] = componentValue;
						}
					} else {
						finalSaleData[itemId][componentName] = componentValue;	
					}
					crel(tmpTr, crel("td",finalSaleData[itemId][componentName]));					
				}
			});
			var rtObj = sale.searchWithRatingId(itemDetails["rating_id"]);
			finalSaleData[itemId]["rate_id"] = itemDetails["rating_id"];
			crel(tmpTr, crel("td", finalSaleData[itemId]["rate_val"] = rtObj["typeval"]));
			if(weightDeductionObj){
				crel(tmpTr, crel("td",finalSaleData[itemId]["price"] = calculatePrice(itemDetails["fixedprice"], weight, itemDetails["rating_id"], weightDeductionObj["weightoramt"])));
			} else {
				crel(tmpTr, crel("td",finalSaleData[itemId]["price"] = calculatePrice(itemDetails["fixedprice"], weight, itemDetails["rating_id"])));
			}

			totalPriceWithoutDeductionForVat += calculatePrice(itemDetails["fixedprice"], saveWeightForVat, itemDetails["rating_id"]);
			
			finalSaleData[itemId]["makingcharge"] = 0;
			var actualWeight = weight;
			if(advanceSaleId && !deAct){
				_(finalDeductionData).forEach(function(itmObj){
					if(itmObj["item_id"] == itemId){
						actualWeight = itmObj["previous_weight"];
					}
				});
			}
			//Calculating making charge
			if(itmMakingChrg && itmMakingChrg != "" && _.isNumber(Number(itmMakingChrg)) && Number(itmMakingChrg) != 0){
				finalSaleData[itemId]["makingcharge"] = Number(itmMakingChrg);
			} else if(actualWeight && category_making_charge && Number(category_making_charge) != 0) {
				finalSaleData[itemId]["makingcharge"] = Number(actualWeight) * Number(category_making_charge);
			} else if(actualWeight && category_parent_id && category_parent_id != "") {
				finalSaleData[itemId]["makingcharge"] = 0;
				_(itemDetails["category_parent_hirarchy"]).forEach(function(catVal, catName){
					if(catVal != "" && catVal != 0 && finalSaleData[itemId]["makingcharge"] == 0){
						finalSaleData[itemId]["makingcharge"] = Number(actualWeight) * Number(catVal);
						return;
					}
				});
			} else {
				finalSaleData[itemId]["makingcharge"] = 0;
			}
			crel(tmpTr, crel("td",finalSaleData[itemId]["makingcharge"]));
			crel(tmpTr, crel("td",finalSaleData[itemId]["price"] + finalSaleData[itemId]["makingcharge"]));
			tbody.appendChild(tmpTr);
			if(weightDeductionObj){
				var deductionTd;
				var dedTr = crel(crel("tr"), deductionTd = crel("td",{"colspan":9}));
				var rtObj = sale.searchWithRatingId(itemDetails["rating_id"]);
				var totalDeductedPrice = Number(weightDeductionObj["weightoramt"]) * Number(rtObj["typeval"]);
				deductionTd.innerHTML = "Deducted weight for <b>"+weightDeductionObj["itmname"]+
										"</b><br/>Weight : "+weightDeductionObj["weightoramt"]+", Amount : "+totalDeductedPrice;
				tbody.appendChild(dedTr);
			}
			totalPrice += finalSaleData[itemId]["price"]+finalSaleData[itemId]["makingcharge"];
		});
		if($("#nonVat").is(":checked") != true){
			$.get("helpers/getVatPerc.php", function(data, status){
				if(status == "success"){
					crel(tbody, crel(crel("tr"), crel(crel("td"), crel("b", "VAT Amount ("+data+"%)")), crel("td",{"colspan":"7"}), crel("td",_.round(((totalPrice*data)/100),2))));
					crel(tbody, crel(crel("tr"), crel(crel("td"), crel("b", "Total Price")), crel("td",{"colspan":"7"}), crel("td",totalPrice = _.round((totalPrice+_.round(((totalPrice*data)/100),2)),2))));
					processAllFinalDeductions(totalPrice, tbody);
				}
			});
		} else {
			crel(tbody, crel(crel("tr"), crel(crel("td"), crel("b", "Total Price")), crel("td",{"colspan":"7"}), crel("td",totalPrice)));
			processAllFinalDeductions(totalPrice, tbody);
		}
		
		
		cartTbl.appendChild(tbody);
		document.getElementById("cartTable").innerHTML = "";
		document.getElementById("cartTable").appendChild(cartTbl);
		document.getElementById("cartTable").style.display = "";
	};

	obj.getAdvanceSalesForUser = function(elm){

		var createTableRowFromLineItem = function(lineItemObj, trHolder, ratingObj, saleTr, saleId){
			_(lineItemObj).forEach(function(value, key){
				if(key == "itmid"){
					return;
				}
				if(key == "item_price_rating_id"){
					value = ratingObj[value]["name"];
				}

				if(key == "itmtype"){
					value = (value == "O") ? "Ornament" : "Cash";
				}
				crel(trHolder, crel("td", value));
			});
			if(saleTr){
				var saleActTd = crel("td",{"rowspan":saleTr});
				var tmpActButton;
				var tmpDeActButton;
				crel(saleActTd, tmpActButton = crel("input",{"type":"button", "class":"btn btn-info", "id":"actbtn"+lineItemObj["id"], "value":"Apply"}));
				crel(saleActTd, tmpDeActButton = crel("input",{"type":"button", "class":"btn btn-success", "id":"actbtn"+lineItemObj["id"], "value":"Remove"}));
				tmpDeActButton.style.display = "none";
				(function(){
					var adSaleId = saleId;
					var actBtn = tmpActButton;
					var dactBtn = tmpDeActButton;
					tmpActButton.addEventListener("click", function(){
						if($("#appliedAdvanceId").val() != ""){
							bootbox.alert("Advance Items already deducted once, please remove them and try again!");
						} else {							
							sale.setAdvanceId(adSaleId);
							actBtn.style.display = "none";						
							dactBtn.style.display = "";	
						}
					}, false);
					tmpDeActButton.addEventListener("click", function(){
						sale.unsetAdvanceId();
						actBtn.style.display = "";						
						dactBtn.style.display = "none";						
					}, false);

				})();
				saleActTd.appendChild(tmpActButton);
				trHolder.appendChild(saleActTd);
			} 
		}

		if(customerId != elm.value){
			customerId = elm.value;
			if(customerId == ""){
				//Customer ID is blank we don't need to do any thing
				bootbox.alert("Please select a customer!");
			} else {
				//Fetch all advance sales made for this customer
				$.get("helpers/getadvancesaleforcustomer.php?cid="+customerId, function(data, status){
					if(status == "success"){
						data = JSON.parse(data);
						if(data.status == "success"){
							if(Array.isArray(data.data)){
								advanceMadeByCustomer = data.data;
								var adTbl = crel("table",{"class":"table"});
								crel(adTbl, crel(crel("thead"), crel(crel("tr"), 
																		crel("th","Advance Sale ID"),
																		crel("th","Item Name"),
																		crel("th","Iem Type"),
																		crel("th","Purity"),
																		crel("th","Weight or Amount"),
																		crel("th","Rating"),
																		crel("th","Action"))));
								var tbody;
								crel(adTbl, tbody = crel("tbody"));
								_(data.data).forEach(function(adSaleObj){
									var numberofItems = adSaleObj["items"].length;
									var adPrimaryRow,adIdHolder;
									crel(tbody, adPrimaryRow = crel("tr"));
									crel(adPrimaryRow, adIdHolder = crel("td",{"rowspan":numberofItems}));
									adIdHolder.innerHTML = "ADSALE-"+adSaleObj["advanceid"];
									var processingLineNumber = 0;
									_(adSaleObj["items"]).forEach(function(lineItems){
										processingLineNumber++;
										if(processingLineNumber == 1){
											createTableRowFromLineItem(lineItems, adPrimaryRow, adSaleObj["ratestring"], numberofItems, adSaleObj["advanceid"]);
										} else {
											var trElem = crel("tr");
											createTableRowFromLineItem(lineItems, trElem, adSaleObj["ratestring"]);
											tbody.appendChild(trElem);
										}
									}); 
								});
								document.getElementById("advanceSaleHolder").innerHTML = "";
								document.getElementById("advanceSaleHolder").appendChild(adTbl);
								document.getElementById("advanceSaleHolder").parentElement.style.display = "";
							} else {
								bootbox.alert("No advances were made");
								document.getElementById("advanceSaleHolder").innerHTML = "";
								document.getElementById("advanceSaleHolder").parentElement.style.display = "none";
							}
						} else {
							bootbox.alert("Something is going wrong, there is some error with the server");
							document.getElementById("advanceSaleHolder").innerHTML = "";
							document.getElementById("advanceSaleHolder").parentElement.style.display = "none";
						}
					} else {
						bootbox.alert("Something is going wrong, there is some error with the server");
						document.getElementById("advanceSaleHolder").innerHTML = "";
						document.getElementById("advanceSaleHolder").parentElement.style.display = "none";
					}
				});
			}
		} else {
		}
	};

})(sale);

$(document).ready(function(){
	sale.init();
});
</script>
<?php require_once('reusables/footer.php'); ?>