<?php session_start(); ?>
<?php require_once('connect.php'); ?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<div id="output"></div>
	<!-- Load Babel -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/babel-standalone/6.18.1/babel.min.js"></script>
	<script src="https://unpkg.com/react@15/dist/react.js"></script>
	<script src="https://unpkg.com/react-dom@15/dist/react-dom.js"></script>
	<script type="text/babel" src="js/cart.js"></script>	


	<!-- Load cart items -->
	<?php 
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
	?>

<script type="text/babel">
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
</script>
</body>
</html>
