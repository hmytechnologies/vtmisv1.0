<?php 
$recruitment=$_POST['recruitmentType'];
if($recruitment==2){?>
	<div class="row">
	<div class="col-md-6">
		<div class="form-group">
			<label>Contract Start Date<span class="text-danger">*</span></label><br/>
			<input type="date" name="contractStartDate" required class="form-control">
		</div>	
	</div>
	<div class="col-md-6">
		<div class="form-group">
			<label>Contract End Date<span class="text-danger">*</span></label><br/>
			<input type="date" name="contractEndDate" required class="form-control">
		</div>	
	</div>
	</div>
<?php }?>
