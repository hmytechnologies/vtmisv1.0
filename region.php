 <script type="text/javascript">
 
 $(document).ready(function () {
           $('#regions').dataTable(
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
		<h2 class="text-info" style="font-family: segoe UI;">List of All Regions</h2>
	</div><br/>
	<div class="col-sm-3">
		<a href="index3.php?sp=HR_configurations" class="btn btn-info form-control"> Back to HR Configurations</a>
	</div>
		<div class="col-sm-2">
		
		    <button class="btn btn-success form-control" data-toggle="modal" data-target="#add_new_record_modal">Add Region</button>
		            
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
    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=regions' class='close' data-dismiss='alert'>&times;</a>
    <strong>New Region has been added successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="unsucc")
  {
    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=regions' class='close' data-dismiss='alert'>&times;</a>
    <strong>Region already Exist!!!</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="unblocked")
  {
  	echo "<div class='alert alert-success fade in'><a href='index3.php?sp=regions' class='close' data-dismiss='alert'>&times;</a>
    <strong>Region has been unblocked successfully</strong>.
</div>";
  }
  
  else if($_REQUEST['msg']=="edited")
  {
      echo "<div class='alert alert-success fade in'><a href='index3.php?sp=regions' class='close' data-dismiss='alert'>&times;</a>
    <strong>Region Information has been edited successfully</strong>.
</div>";
  }
  
  else if($_REQUEST['msg']=="blocked")
  {
  	echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=regions' class='close' data-dismiss='alert'>&times;</a>
    <strong>Region has been blocked successfully</strong>.
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
            $regions = $db->getRows('hrmx_region',array('order_by'=>'regionCode ASC'));
?>
<table  id="regions" class="display nowrap" cellspacing="0" width="100%">
  <thead>
  <tr>
    <th width="5px">No</th>
    <th>Code</th>
    <th>Name</th>
      <th>Edit</th>
    <th>Block/Unblock</th>
     </tr>
  </thead>
  <tbody>
<?php 
if(!empty($regions)){ $count = 0; foreach($regions as $region){ $count++;

$name=$region['regionName'];
$code='RGN00'.$region['regionCode'];
?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $code ?></td> 
                <td><?php echo $name ?></td> 
         		         		
              
                   
                <td>
                 <button data-toggle="modal" data-target="#edit<?php echo $code?>" class="btn btn-default fa fa-pencil" title="Edit Region"></button></td>
                 <td>
                 <?php 
                 if($region['status']=="1")
                 {
                 	?>
                    <a href="action_region.php?action_type=block&id=<?php echo $db->my_simple_crypt($region['regionCode'],'e') ?>" class=" btn btn-default fa fa-check" title="Block Region" onclick="return confirm('Are you sure you want to block?')"></a>
                 <?php }else{?>
                     <a href="action_region.php?action_type=unblock&id=<?php echo  $db->my_simple_crypt($region['regionCode'],'e') ?>" class=" btn btn-default fa fa-remove" title="Unblock Region"></a>  
             <?php } ?>
                </td>
            </tr>
 <!-- Modal for editting -->
 <div class="modal fade" id="edit<?php echo $code?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">

<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

<h4 class="modal-title" id="myModalLabel">Edit Region</h4>
</div>

<div class="row">
    <form name="" method="post" action="action_region.php">
<div class="col-md-12">
<div class="modal-body">
<div class="row">
<div class="col-lg-12">
<label><b>Name</b></label>
<input type="text" name="regionName" value="<?php echo $name?>" class="form-control"/>
 </div></div>

<br/>
</div>
    <div class="modal-footer">
<button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
<input type="hidden" name="action_type" value="edit"/>
<input type="hidden" name="regionCode" value="<?php echo $region['regionCode']?>"/>
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

<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

<h4 class="modal-title" id="myModalLabel">Add Region</h4>
</div>

<div class="row">
    <form name="district" method="post" action="action_region.php" >
<div class="col-md-12">
<div class="modal-body">
<div class="row">
<div class="col-lg-12">
<label><b>Name</b></label>
<input type="text" name="regionName"  class="form-control"/>
 </div></div>

<br/>



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
