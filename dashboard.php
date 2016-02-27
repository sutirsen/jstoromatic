<?php
include('reusables/header.php');
?>
<script type="text/javascript">
	function updateRate(rateId){

			var newRate = $('#rate_'+rateId).val();
			$('#rteres_'+rateId).removeClass('glyphicon glyphicon-ok');
			$('#rteres_'+rateId).html("Updating...");
	        $.post("helpers/updaterates.php",
	        {
	          updateratefromdashboard: "Yes",
	          rateid: rateId,
	          ratevalue: newRate
	        },
	        function(data,status){
	           $('#rteres_'+rateId).removeClass('glyphicon glyphicon-ok');
	           $('#rteres_'+rateId).html("");
	           if(data == "saved")
	           {
	           		$('#rteres_'+rateId).addClass('glyphicon glyphicon-ok');
	           }
	           else
	           {
	           		$('#rteres_'+rateId).html("Error Updating Rate");
	           }
	        });
	    }
</script>
<div id="page-wrapper">
	<div class="row">
	    <div class="col-lg-12">
	        <h1 class="page-header">Dashboard</h1>
	        <div class="panel panel-green">
	            <div class="panel-heading">
	            	Update Rates (<?php echo date('Y-m-d'); ?>)
	            </div>
	            <div class="panel-body">
	                <?php 
	                $getAllRates = ORM::for_table('jst_pricing_rate_type')->where_not_equal('status','D')->find_many();
	                ?>
	                
	                <?php
	                foreach ($getAllRates as $rateDashboard) {
	                ?>
	                <div class="form-group input-group col-sm-12">
	                    <label for="rate_<?php echo $rateDashboard->id; ?>" class="col-sm-2 control-label"><?php echo $rateDashboard->type_name; ?></label>
	                    <div class="col-sm-10">
	                    	<div class="col-sm-6">
	                    		<input type="text" class="form-control" id="rate_<?php echo $rateDashboard->id; ?>" name="rate_<?php echo $rateDashboard->id; ?>" value="<?php echo $rateDashboard->type_value; ?>">
	                    	</div>
	                    	<div class="col-sm-4" id="rateResult_<?php echo $rateDashboard->id; ?>">
	                    		<button type="button" onclick="updateRate(<?php echo $rateDashboard->id; ?>)" name="rate_sbmt_<?php echo $rateDashboard->id; ?>" class="btn btn-default">Update</button> <span id="rteres_<?php echo $rateDashboard->id; ?>" <?php if(date('Y-m-d', strtotime($rateDashboard->updated_on)) == date('Y-m-d')){ ?> class="glyphicon glyphicon-ok" <?php } ?>></span>
	                    	</div>
	                    </div>
                  	</div>
	                <?php 
	            	}
	            	?>
	                  
	            </div>
	        </div>
	    </div>
	    <!-- /.col-lg-12 -->
	</div>
</div>
<?php include('reusables/footer.php'); ?>