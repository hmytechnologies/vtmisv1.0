<div class="container">
<div class="row"> 
    <h2>List of Grades</h2>
<div class="col-md-12">
<div class="pull-right">
                <button class="btn btn-success" data-toggle="modal" data-target="#add_new_record_modal">Define New Grade</button>
            </div>
 </div>
</div>
<div class="row">
        <div class="col-md-12">
            <hr>
<?php 
if(!empty($_REQUEST['msg']))
{
  if($_REQUEST['msg']=="succ")
  {
    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=gpa_system' class='close' data-dismiss='alert'>&times;</a>
    <strong>Subject data has been inserted successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="edited")
  {
    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=gpa_system' class='close' data-dismiss='alert'>&times;</a>
    <strong>Subject data has been edited Successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=='unsucc')
  {
    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=gpa_system' class='close' data-dismiss='alert'>&times;</a>
    <strong>Error-Sory, Subject Code/Subject Name is already Exists</strong>.
</div>";
  }
 else {
      echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=gpa_system' class='close' data-dismiss='alert'>&times;</a>
    <strong>Error-Sory, Something Wrong happen, Contact System Administrator for more Information</strong>.
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
            $gpa = $db->getRows('gpa',array('order_by'=>'startPoint DESC'));
?>
<table  id="example" class="display">
  <thead>
  <tr>
    <th>No.</th>
    <th>Class</th>
    <th>Points Range</th>
    <th>Programme Level</th>
    <th>Academic Year</th>
    <th>Remarks</th>
    <th>Status</th>
    <th>Edit</th>
     </tr>
  </thead>
  <tbody>
<?php 
if(!empty($gpa)){ $count = 0; foreach($gpa as $gd){ $count++;
 ?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $gd['gpaClass']; ?></td>
                <td><?php echo $gd['startPoint'];?> - <?php echo $gd['endPoint'];?></td>
                <td><?php echo $db->getData("programme_level","programmeLevel","programmeLevelID",$gd['programmeLevelID']);?></td>
               <td><?php echo  $db->getData("academic_year","academicYear","academicYearID",$gd['academicYearID']);?></td>
               <td><?php echo $db->getData("remarks","remark","remarkID",$gd['remarkID']);?></td>
               <td><?php echo  $gd['status']; ?></td>
                
              <td>
                    <a href="index3.php?sp=edit_grade&id=<?php echo $gd['gpaID']; ?>" class="glyphicon glyphicon-edit"></a>
                   
                </td>
            </tr>
            <?php } }?>
</tbody>
 </table>
 </div></div>  
</div>


<div class="modal fade" id="add_new_record_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<div class="modal-content">
<form name="" method="post" action="action_gpa.php">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

<h4 class="modal-title" id="myModalLabel">Add New Record</h4>
</div>
<div class="row">
<div class="col-md-12">
<div class="modal-body">

<div class="form-group">
<label for="email">GPA Class</label>
<input type="text" id="gpaClass" name="gpaClass" placeholder="Eg. First Class" maxlength="20" class="form-control" required />
</div>

<div class="form-group">
<label for="email">Start Point</label>
<input type="text" id="startPoint" name="startPoint" placeholder="Eg.5" maxlength="3" class="form-control" required />
</div>

<div class="form-group">
<label for="email">End Point</label>
<input type="text" id="endPoint" name="endPoint" placeholder="Eg.4.4" maxlength="3" class="form-control" required />
</div>



<div class="form-group">
<label for="MiddleName">Programme Level</label>
                            <select name="programmeLevelID" id="programmeLevelID" class="form-control" required>
                              <?php
                               $programmes = $db->getRows('programme_level',array('order_by'=>'programmeLevel ASC'));
                               if(!empty($programmes)){ 
                                echo"<option value=''>Please Select Here</option>";
                                $count = 0; foreach($programmes as $prog){ $count++;
                                $programme_name=$prog['programmeLevel'];
                                $programmeID=$prog['programmeLevelID'];
                               ?>
                               <option value="<?php echo $programmeID;?>"><?php echo $programme_name;?></option>
                               <?php }}
           ?>
                           </select>
</div>

<div class="form-group">
<label for="MiddleName">Programme Level</label>
                           <select name="academicYearID" id="academicYearID" class="form-control" required>
                              <?php
                                 $academicYear = $db->getRows('academic_year',array('order_by'=>'academicYear ASC'));
                                 if(!empty($academicYear)){
                                  echo"<option value=''>Please Select Here</option>";
                                  $count = 0; foreach($academicYear as $sm){ $count++;
                                  $academicYear=$sm['academicYear'];
                                  $academicYearID=$sm['academicYearID'];
                                 ?>
                                 <option value="<?php echo $academicYearID;?>"><?php echo $academicYear;?></option>
                                 <?php }}

                                 ?>
                           </select>
</div>

<div class="form-group">
<label for="MiddleName">Remarks</label>
                           <select name="remarkID" id="remarkID" class="form-control" required>
                              <?php
                                 $remarks = $db->getRows('remarks',array('order_by'=>'remark ASC'));
                                 if(!empty($remarks)){
                                  echo"<option value=''>Please Select Here</option>";
                                  $count = 0; foreach($remarks as $sm){ $count++;
                                  $remark=$sm['remark'];
                                  $remarkID=$sm['remarkID'];
                                 ?>
                                 <option value="<?php echo $remarkID;?>"><?php echo $remark;?></option>
                                 <?php }}

                                 ?>
                           </select>
</div>

</div>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
<input type="hidden" name="action_type" value="add"/>
<input type="submit" name="doSubmit" value="Add Record" class="btn btn-primary">
<!--<button type="button" class="btn btn-primary" onclick="addRecord()">Add Record</button>-->

</div></div></div>
</form>
</div>
</div>
</div>