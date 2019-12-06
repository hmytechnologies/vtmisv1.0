 <script type="text/javascript">
 
 $(document).ready(function () {
           $('#districts').dataTable(
               {
                   paging: true,
                   dom: 'Blfrtip',
                   buttons:[
                       {
                           extend:'excel',
                           footer:false,
                           /*exportOptions:{
                               columns:[0,1,2,3]
                           }*/
                       },
                       ,
                       {
                           extend: 'print',
                           title: 'List of Records',
                           footer: false,
                          /* exportOptions: {
                               columns: [0, 1, 2, 3]
                           }*/
                       },
                       {
                           extend: 'pdfHtml5',
                           title: 'List of Records',
                           footer: true,
                          /* exportOptions: {
                               columns: [0, 1, 2, 3,5,6]
                           }*/
                           orientation: 'landscape',
                       }

                       ]
               });
         });
</script>
<div class="chosen-container-single">
<div class="row"> 
	<div class="col-sm-7">
		<h2 class="text-info" style="font-family: segoe UI;">List of All Districts</h2>
	</div><br/>
	<div class="col-sm-3">
		<a href="index3.php?sp=HR_configurations" class="btn btn-info form-control"> Back to HR Configurations</a>
	</div>
		<div class="col-sm-2">
		
		    <button class="btn btn-success form-control" data-toggle="modal" data-target="#add_new_record_modal">Add New District</button>
		            
		 </div>
</div>
<div class="row">
        <div class="col-md-12">
            <hr style="border-color:#6599ff;">

<?php 
if(!empty($_REQUEST['msg']))
{
  if($_REQUEST['msg']=="succ")
  {
    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=districts' class='close' data-dismiss='alert'>&times;</a>
    <strong>New District has been added successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="unsucc")
  {
    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=districts' class='close' data-dismiss='alert'>&times;</a>
    <strong>District already Exist!!!</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="unblocked")
  {
  	echo "<div class='alert alert-success fade in'><a href='index3.php?sp=districts' class='close' data-dismiss='alert'>&times;</a>
    <strong>District has been unblocked successfully</strong>.
</div>";
  }
  
  else if($_REQUEST['msg']=="edited")
  {
      echo "<div class='alert alert-success fade in'><a href='index3.php?sp=districts' class='close' data-dismiss='alert'>&times;</a>
    <strong>District Information has been edited successfully</strong>.
</div>";
  }
  
  else if($_REQUEST['msg']=="blocked")
  {
  	echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=districts' class='close' data-dismiss='alert'>&times;</a>
    <strong>District has been blocked successfully</strong>.
</div>";
  }
}
?> 


        </div>
    </div>
<div class="row">
 <div class="col-md-12">   
<?php
          
            $db = new DBHelper();
           $districts = $db->getRows('hrmx_district',array('order_by'=>'districtCode ASC'));
?>
<table  id="districts" class="display nowrap" cellspacing="0" width="100%">
  <thead>
  <tr>
    <th width="5px">No.</th>
    <th>Code</th>
    <th>Name</th>
     <th>Region</th>
      <th>Edit</th>
    <th>Delete</th>
     </tr>
  </thead>
  <tbody>
<?php 
 if(!empty($districts)){ $count = 0; foreach($districts as $district){ $count++;

$name=$district['districtName'];
$code='DTT00'.$district['districtCode'];
$regionCode=$district['regionCode'];
$regionName=$db->getData("hrmx_region", "regionName", "regionCode", $regionCode);



?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $code ?></td> 
                <td><?php echo $name ?></td> 
         		      <td><?php 
         			echo $db->getData('hrmx_region', 'regionName', 'regionCode',$district['regionCode']);
         			?></td>
              
                   
                <td>
                 <button data-toggle="modal" data-target="#edit<?php echo $district['districtCode'] ?>" class="btn btn-default fa fa-pencil" title="Update District"></button></td>
                 <td>
                 <?php 
                 if($district['status']=="1")
                 {
                 	?>
                    <a href="action_district.php?action_type=block&id=<?php echo $db->my_simple_crypt($district['districtCode'],'e') ?>" class=" btn btn-default fa fa-check" title="Block District" onclick="return confirm('Are you sure you want to block?')"></a>
                 <?php }else{?>
                     <a href="action_district.php?action_type=unblock&id=<?php echo $db->my_simple_crypt($district['districtCode'],'e') ?>" class=" btn btn-default fa fa-remove" title="Unblock District"></a>  
             <?php } ?>
                </td>
            </tr>
 <!-- Modal for editting -->
 <div class="modal fade" id="edit<?php echo $district['districtCode']?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">

<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

<h4 class="modal-title" id="myModalLabel">Edit District</h4>
</div>

<div class="row">
    <form name="" method="post" action="action_district.php">
<div class="col-md-12">
<div class="modal-body">
<div class="row">
<div class="col-lg-12">
<label><b>Name</b></label>
<input type="text" name="districtName" value="<?php echo $district['districtName']?>" class="form-control"/>
 </div></div>

<br/>

<div class="row">
<div class=col-lg-12>
<label><b>Region</b></label>
<select name="regionCode" class="form-control">

<option value="<?php echo $region['regionCode']?>"><?php echo $regionName?></option>
<?php 
$regions=$db->getRows('hrmx_region',array('order_by'=>'regionCode DESC','where'=>array('status'=>1)));
if($regions){
	foreach ($regions as $region){
		
		?>
		<option value="<?php echo $region['regionCode']?>"><?php echo $region['regionName']?></option><?php 
	}
}
?>
</select>


</div></div><br/>

</div>
    <div class="modal-footer">
<button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
<input type="hidden" name="action_type" value="edit"/>
<input type="hidden" name="districtCode" value="<?php echo $district['districtCode']?>"/>
<input type="submit" name="doSubmit" value="Save Records" class="btn btn-success">
<!--<button type="button" class="btn btn-primary" onclick="addRecord()">Add Record</button>-->

</div>
</div></form>
</div>
</div>

 
            <?php } } ?>
</tbody>
 </table>
 </div></div>  
</div>
<div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<script>
function validateDistrict()
{
	var districtName=document.district.districtName;
	var regionCode=document.district.regionCode;

	if(districtName.value=="")
	{
		alert('Please fill in district name');
		districtName.focus();
		return false;
	}
	else if(regionCode.options[regionCode.selectedIndex].value=="0")
	{
		alert('Please select region');
		return false;
	}
	else {
		return true
	}
}
</script>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

<h4 class="modal-title" id="myModalLabel">Add New District</h4>
</div>

<div class="row">
    <form name="district" method="post" action="action_district.php" onsubmit="return validateDistrict();">
<div class="col-md-12">
<div class="modal-body">
<div class="row">
<div class="col-lg-12">
<label><b>Name</b></label>
<input type="text" name="districtName" placeholder="Enter the District Name" class="form-control"/>
 </div></div>

<br/>

<div class="row">
<div class=col-lg-12>
<label><b>Region</b></label>
<select name="regionCode" class="form-control"/>
<option value="0">Select here...</option>
<?php 
$regions=$db->getRows('hrmx_region',array('order_by'=>'regionCode DESC','where'=>array('status'=>1)));
if($regions){
	foreach ($regions as $region){
		$count++;
		?>
		<option value="<?php echo $region['regionCode']?>"><?php echo $region['regionName']?></option><?php 
	}
}
?>
</select>


</div></div><br/>

</div>
    <div class="modal-footer">
<button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
<input type="hidden" name="action_type" value="add"/>
<input type="submit" name="doSubmit" value="Save Records" class="btn btn-success">
<!--<button type="button" class="btn btn-primary" onclick="addRecord()">Add Record</button>-->

</div>
</div></form>
</div>
</div>

