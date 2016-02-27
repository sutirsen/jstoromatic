<?php
include('reusables/header.php'); 
?>
<div id="page-wrapper">
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header">Checkout</h1>
	    </div>
	    <!-- /.col-lg-12 -->
	</div>
	<table class="table table-striped table-hover table-bordered">
        <tbody>
            <tr>
                <th>Item</th>
                <th>Action</th>
                <th>Exaplanation</th>
                <th>Price</th>
                <th>Total Price</th>
            </tr>
            <tr>
                <td rowspan="2">Awesome Product</td>
                <td rowspan="2"><a href="#" class="btn btn-info btn-xs">Remove</a></td>
                <td>12gm X (Rs 5 per gm) = Rs60</td>
                <td>60.00</td>
                <td rowspan="2">84.00</td>
            </tr>
            <tr>
                <td><span class="pull-right">Making Charge : per gram making charge 2, 12gm : 12 X 2 = 24</span><br/>
                	Discount : 
                	<form class="form-inline">
                	  <div class="form-group">
                	    <label for="disctype">Type</label>
                		<select class="form-control" id="disctype" name="disctype" ><option>Flat</option><option>Percentage</option></select>
                	  </div>
                	  <div class="form-group">
                	    <label for="amount">Amount</label>
                	    <input type="amount" class="form-control" id="amount">
                	  </div>
                	  <button type="submit" class="btn btn-default">Apply</button>
                	</form>
                <td>24.00</td>	

            </tr>
            <tr>
                <th colspan="4"><span class="pull-right">Sub Total</span></th>
                <th>£250.00</th>
            </tr>
            <tr id="vatRow">
                <th colspan="4"><span class="pull-right">VAT 20%</span></th>
                <th>£50.00</th>
            </tr>
            <tr>
                <th colspan="4"><span class="pull-right">Total</span></th>
                <th>£300.00</th>
            </tr>
            <tr>
                <td><button id="vtnvt" class="btn btn-primary">Non VAT Bill</button></td>
                <td colspan="4"><a href="#" class="pull-right btn btn-success">Checkout</a></td>
            </tr>
        </tbody>
    </table>  
</div>
<script>
	$(document).ready(function(){
		$('#vtnvt').click(function()
		{
			if($('#vtnvt').html() == "Non VAT Bill")
			{
				$('#vatRow').hide();	
				$('#vtnvt').html("VAT Bill");
			}
			else
			{
				$('#vatRow').show();	
				$('#vtnvt').html("Non VAT Bill");	
			}
			
		});
	});
</script>
<?php include('reusables/footer.php'); ?>