<script src="bootbox/bootbox.min.js" type="text/javascript"></script>
   <script type="text/javascript">
    $(document).ready(function () {
        $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
            localStorage.setItem('activeTab', $(e.target).attr('href'));
        });
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            $('#myTab a[href="' + activeTab + '"]').tab('show');
        }
    });

   
</script>
<style type="text/css">
	.bs-example{
		margin: 10px;
	}
</style>
<?php $db=new DBHelper();?>
<div class="container">
  <div class="content">
   <h3>Manage Grades,GPA Classes and Exam Category Setting</h3>

  <div class="pull-right">
                <a href="index3.php?sp=sysconf" class="btn btn-warning">Back to Main Setting</a>
            </div>
            <br>
  <hr>
    <ul class="nav nav-tabs" id="myTab">
        <li class="active"><a data-toggle="tab" href="#gradingyear">Grading Year</a></li>
        <li><a data-toggle="tab" href="#departments">Grading Setting</a></li>
       <li><a data-toggle="tab" href="#programme">Exam Category Setting</a></li>
        <li><a data-toggle="tab" href="#assessment_setting">Assessment Setting</a></li>
    </ul>
    <div class="tab-content">
        <div id="gradingyear" class="tab-pane fade in active">
            <script type="text/javascript">
                $(document).ready(function () {
                    $("#zonetable").DataTable({
                        paging:true,
                        dom: 'Blfrtip',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf'
                        ]
                    });
                });
            </script>
            <h3>Grading Year Setting</h3>
            <hr>
            <!-- Start -->
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right">
                        <button class="btn btn-success" data-toggle="modal" data-target="#add_new_grade_year">Define New Grade Year</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <br>
                    <?php
                    if(!empty($_REQUEST['msg']))
                    {
                        if($_REQUEST['msg']=="succ")
                        {
                            echo "<div class='alert alert-success fade in'><a href='index3.php?sp=misc_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>Data has been inserted successfully</strong>.
</div>";
                        }
                        else if($_REQUEST['msg']=="edited")
                        {
                            echo "<div class='alert alert-success fade in'><a href='index3.php?sp=misc_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>Data has been edited Successfully</strong>.
</div>";
                        }
                        else if($_REQUEST['msg']=='unsucc')
                        {
                            echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=misc_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>Error-Sory, Data Name is already Exists</strong>.
</div>";
                        }
                        else {
                            echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=misc_setting' class='close' data-dismiss='alert'>&times;</a>
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
                    $grade_year = $db->getRows('grading_year',array('order_by'=>'academicYearID ASC'));
                    ?>
                    <table  id="zonetable" class="display">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Grade Year Name</th>
                            <th>Academic Year</th>
                            <th>Number of Terms</th>
                            <th>Status</th>
                            <th>Edit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(!empty($grade_year)){ $g = 0; foreach($grade_year as $gy){ $g++;
                            ?>
                            <tr>
                                <td><?php echo $g; ?></td>
                                <td><?php echo $gy['gradingYearName']; ?></td>
                                <td><?php echo $db->getData("academic_year","academicYear","academicYearID",$gy['academicYearID']);?></td>
                                <td><?php echo $gy['numberOfTerms'];?></td>
                                <td>

                                    <?php if ($gy['status'] == 1) {
                                        ?>
                                        <span class="label label-success">Active</span>
                                    <?php } else { ?>
                                        <span class="label label-danger">Not Active</span>
                                    <?php } ?>

                                </td>
                                <td>
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#message<?php echo $gy['gradingYearID'];?>">
          	  <span class="fa fa-edit" aria-hidden="true" title="Edit">
                                </td>
                            </tr>
                            <div id="message<?php echo $gy['gradingYearID'];?>" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Edit Grade System</h4>
                                        </div>
                                        <form name="register" id="register" method="post" enctype="" action="action_grades.php">
                                            <div class="modal-body">
                                                <!--edit-->
                                                <div class="form-group">
                                                    <label for="email">Grade Year Name</label>
                                                    <input type="text" id="code" name="gradeYearName" value="<?php echo $gy['gradingYearName'];?>" class="form-control" required />
                                                </div>
                                                <div class="form-group">
                                                    <label for="MiddleName">Academic Year</label>
                                                    <select name="academicYearID" id="academicYearID" class="form-control" required>
                                                        <option value='<?php echo $db->getData('academic_year','academicYearID','academicYearID',$gy['academicYearID']);?>'>
                                                            <?php echo $db->getData("academic_year","academicYear","academicYearID",$gy['academicYearID']);?></option>
                                                        <?php
                                                        $academicYear = $db->getRows('academic_year',array('where'=>array('status'=>1),'order_by'=>'academicYear ASC'));
                                                       // $academicYear = $db->getRows('academic_year',array('order_by'=>'academicYear ASC'));
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
                                                    <label for="email">Number of Terms</label>
                                                    <input type="text" id="numberofTerms" name="numberOfTerms" value="<?php echo $gy['numberOfTerms'];?>" class="form-control" required />
                                                </div>

                                                <div class="form-group">
                                                    <label for="email">Status</label>
                                                    <?php if ($gy['status'] == 1) {
                                                        ?>
                                                        <input type="radio" name="status" value="1" checked>Active
                                                        <input type="radio" name="status" value="0">Not Active
                                                    <?php } else { ?>
                                                        <input type="radio" name="status" value="1">Active
                                                        <input type="radio" name="status" value="0" checked>Not Active
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <input type="hidden" name="gradingYearID" value="<?php echo $gy['gradingYearID'];?>">
                                                <input type="hidden" name="action_type" value="edit_grading_year"/>
                                                <input type="submit" name="doUpdate" value="Update Records" class="btn btn-success">
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        <?php }
                        }?>
                        </tbody>
                    </table>
                </div></div>



            <div class="modal fade" id="add_new_grade_year" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form name="" method="post" action="action_grades.php">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                                <h4 class="modal-title" id="myModalLabel">Add New Record</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="modal-body">

                                        <div class="form-group">
                                            <label for="email">Grade Year Name</label>
                                            <input type="text" id="gradeName" name="gradeName" placeholder="Grade Year Name" class="form-control" required />
                                        </div>

                                        <div class="form-group">
                                            <label for="MiddleName">Academic Year</label>
                                            <select name="academicYearID" id="academicYearID" class="form-control" required>
                                                <?php
                                                $academicYear = $db->getRows('academic_year',array('where'=>array('status'=>1),'order_by'=>'academicYear ASC'));
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
                                            <label for="email">Number of Terms</label>
                                            <input type="text" id="numberOfTerms" name="numberOfTerms" placeholder="Grade Year Name" class="form-control" required />
                                        </div>



                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                        <input type="hidden" name="action_type" value="addgradingyear"/>
                                        <input type="submit" name="doSubmit" value="Add Record" class="btn btn-primary">

                                    </div></div></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div id="departments" class="tab-pane fade">
           <h3>Grading System Setting</h3>
           <hr>
           <!-- Start -->
<div class="row"> 
<div class="col-md-12">
    <!--<div class="pull-right">
                <!--<button class="btn btn-success" data-toggle="modal" data-target="#add_new_grade_modal">Define New Grade</button>
                </div>-->

    <div class="pull-right">
        <a href="index3.php?sp=exam_grading_setting" class="btn btn-success">Define New Grade Setting</a>
    </div>
 </div>
</div>
<div class="row">
        <div class="col-md-12">
<br>
<?php 
if(!empty($_REQUEST['msg']))
{
  if($_REQUEST['msg']=="succ")
  {
    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=misc_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>Subject data has been inserted successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="edited")
  {
    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=misc_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>Subject data has been edited Successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=='unsucc')
  {
    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=misc_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>Error-Sory, Subject Code/Subject Name is already Exists</strong>.
</div>";
  }
 else {
      echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=misc_setting' class='close' data-dismiss='alert'>&times;</a>
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
            $grades = $db->getRows('grades',array('order_by'=>'gradePoints DESC'));
?>
<table  id="example" class="display">
  <thead>
  <tr>
    <th>No.</th>
    <th>Grade Code</th>
    <th>Grade Point</th>
    <th>Marks Range</th>
    <th>Programme Level</th>
    <th>Academic Year</th>
    <th>Remarks</th>
    <th>Status</th>
    <th>Edit</th>
     </tr>
  </thead>
  <tbody>
<?php 
if(!empty($grades)){ $jj = 0; foreach($grades as $gd){ $jj++;
 ?>
            <tr>
                <td><?php echo $jj; ?></td>
                <td><?php echo $gd['gradeCode']; ?></td>
                <td><?php echo $gd['gradePoints']; ?></td>
                <td><?php echo $gd['startMark'];?>-<?php echo $gd['endMark'];?></td>
                <td><?php echo $db->getData("programme_level","programmeLevel","programmeLevelID",$gd['programmeLevelID']);?></td>
               <td><?php echo $db->getData("grading_year","gradingYearName","gradingYearID",$gd['gradingYearID']);?></td>
               <td><?php echo $db->getData("remarks","remark","remarkID",$gd['remarkID']);?></td>
               <td><?php echo $gd['status']; ?></td>
              <td>
                  <button type="button" class="btn btn-success" data-toggle="modal" data-target="#message<?php echo $gd['gradeID'];?>">
          	  <span class="fa fa-edit" aria-hidden="true" title="Edit">
                    <!--<a href="index3.php?sp=edit_grade&id=<?php /*echo $gd['gradeID']; */?>" class="glyphicon glyphicon-edit"></a>-->
                </td>
            </tr>
    <div id="message<?php echo $gd['gradeID'];?>" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>

                    <h4 class="modal-title">Edit Grade System</h4>
                </div>
                <form name="register" id="register" method="post" enctype="" action="action_grades.php">
                    <div class="modal-body">
                        <!--edit-->
                        <div class="form-group">
                            <label for="email">Grade Code</label>
                            <input type="text" id="code" name="gradeCode" value="<?php echo $gd['gradeCode'];?>" maxlength="2" class="form-control" required />
                        </div>

                        <div class="form-group">
                            <label for="email">Start Mark</label>
                            <input type="number" id="startMark" name="startMark" value="<?php echo $gd['startMark'];?>" min="0" max="100" class="form-control" required />
                        </div>

                        <div class="form-group">
                            <label for="email">End Mark</label>
                            <input type="number" id="endMark" name="endMark" value="<?php echo $gd['endMark'];?>" min="0" max="100" class="form-control" required />
                        </div>

                        <div class="form-group">
                            <label for="email">Grade Points</label>
                            <input type="number" id="points" name="gradePoints" value="<?php echo $gd['gradePoints'];?>" min="0" max="10" class="form-control" required />
                        </div>

                        <div class="form-group">
                            <label for="MiddleName">Programme Level</label>
                            <select name="programmeLevelID" id="programmeLevelID" class="form-control" required>
                                <option value='<?php echo $db->getData('programme_level','programmeLevelID','programmeLevelID',$gd['programmeLevelID']);?>'>
                                    <?php echo $db->getData("programme_level","programmeLevel","programmeLevelID",$gd['programmeLevelID']);?></option>
                                <?php
                                $programmes = $db->getRows('programme_level',array('order_by'=>'programmeLevel ASC'));
                                if(!empty($programmes)){
                                    echo"<option value=''>Please Select Here</option>";
                                    $count = 0; foreach($programmes as $prog){ $count++;
                                        $programme_name=$prog['programmeLevel'];
                                        $programmeID=$prog['programmeLevelID'];
                                        ?>
                                        <option value="<?php echo $programmeID;?>"><?php echo $programme_name;?></option>
                                    <?php }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="MiddleName">Academic Year</label>
                            <select name="academicYearID" id="academicYearID" class="form-control" required>
                                <option value='<?php echo $db->getData('academic_year','academicYearID','academicYearID',$gd['academicYearID']);?>'>
                                    <?php echo $db->getData("academic_year","academicYear","academicYearID",$gd['academicYearID']);?></option>
                                <?php
                                $academicYear = $db->getRows('academic_year',array('where'=>array('status'=>1),'order_by'=>'academicYear ASC'));
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
                                <option value='<?php echo $db->getData("remarks","remarkID","remarkID",$gd['remarkID']);?>'>
                                    <?php echo $db->getData("remarks","remark","remarkID",$gd['remarkID']);?></option>
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

                        <!--edit-->


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <input type="hidden" name="gradeID" value="<?php echo $gd['gradeID'];?>">
                        <input type="hidden" name="action_type" value="edit_grade"/>
                        <input type="submit" name="doUpdate" value="Update Records" class="btn btn-success">
                    </div>
                </form>
            </div>

        </div>
    </div>
            <?php }
}?>
</tbody>
 </table>
 </div></div>  



<div class="modal fade" id="add_new_grade_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
<div class="modal-dialog" role="document">
<div class="modal-content">
<form name="" method="post" action="action_grades.php">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

<h4 class="modal-title" id="myModalLabel">Add New Record</h4>
</div>
<div class="row">
<div class="col-md-12">
<div class="modal-body">

<div class="form-group">
<label for="email">Grade Code</label>
<input type="text" id="code" name="gradeCode" placeholder="GradeCode" maxlength="2" class="form-control" required />
</div>

<div class="form-group">
<label for="email">Start Mark</label>
<input type="number" id="startMark" name="startMark" placeholder="Start Mark" min="0" max="100" class="form-control" required />
</div>

<div class="form-group">
<label for="email">End Mark</label>
<input type="number" id="endMark" name="endMark" placeholder="End Mark" min="0" max="100" class="form-control" required />
</div>

<div class="form-group">
<label for="email">Grade Points</label>
<input type="number" id="points" name="gradePoints" placeholder="Grade Points" min="0" max="10" class="form-control" required />
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
<label for="MiddleName">Academic Year</label>
                           <select name="academicYearID" id="academicYearID" class="form-control" required>
                              <?php
                                 $academicYear = $db->getRows('academic_year',array('where'=>array('status'=>1),'order_by'=>'academicYear ASC'));
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
</div>
         <!--end-->
        <div id="programme" class="tab-pane fade">
            <h3>Exam Category System Setting</h3>
            <hr>
          <div class="col-md-12">
<div class="pull-right">
                <a href="index3.php?sp=exam_category_setting" class="btn btn-success">Define New Exam Category Setting</a>
            </div>
 </div>

<div class="row">
        <div class="col-md-12">
         <br>
<?php 
if(!empty($_REQUEST['msg']))
{
  if($_REQUEST['msg']=="succ")
  {
    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=misc_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>Subject data has been inserted successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="edited")
  {
    echo "<div class='alert alert-success fade in'><a href='index3.php?sp=misc_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>Subject data has been edited Successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=='unsucc')
  {
    echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=misc_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>Error-Sory, Subject Code/Subject Name is already Exists</strong>.
</div>";
  }
 else {
      echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=misc_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>Error-Sory, Something Wrong happen, Contact System Administrator for more Information</strong>.
</div>";
  }
}
?> 


        </div>
    </div>
                <div class="row">
            <?php
               $examCategory = $db->getRows('exam_category_setting',array('order_by programmeLevelID ASC'));
               if(!empty($examCategory))
               {
                    ?>
                    <h4><span class="text-danger">
                    List of Exam Category Setting
                    </span></h4>
                    <hr>
                    <table  id="exampleexampleexample" class="display nowrap">
                      <thead>
                      <tr>
                        <th>No.</th>
                        <th>Programme Level</th>
                        <th>Grading Year</th>
                        <th>Exam Category</th>
                        <th>Max.Mark</th>
                        <th>Weighted Mark</th>
                        <th>Pass Mark</th>
                        <th>Status</th>
                      </tr>
                      </thead>
                      <tbody>
                    <?php 
                        $count = 0; 
                        foreach($examCategory as $ec)
                        { 
                            $count++;
                                 ?>
                                            <tr>
                                                <td><?php echo $count; ?></td>
                                                <td><?php echo $db->getData("programme_level","programmeLevel","programmeLevelID",$ec['programmeLevelID']);?></td>
                                                <td><?php echo $db->getData("grading_year","gradingYearName","gradingYearID",$ec['academicYearID']);?></td>
                                                <td><?php echo $db->getData("exam_category","examCategory","examCategoryID",$ec['examCategoryID']); ?></td>
                                                <td><?php echo $ec['mMark']; ?></td>
                                                <td><?php echo $ec['wMark']; ?></td>
                                                <td><?php echo $ec['passMark']; ?></td>
                                                <td>
                                                    <?php if ($ec['status'] == 1) {
                                                        ?>
                                                        <span class="label label-success">Active</span>
                                                    <?php } else { ?>
                                                        <span class="label label-danger">Not Active</span>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                            <?php 
                            } 
                             ?>
                		</tbody>
                 </table>
                 <?php
                }
                else
                    { 
                        ?>
                        <h4 class="text-danger">No Setting Defined(s) found......</h4>
                        <?php 
                    } 
?>
        </div>
        
        </div>

        <!--start assessment setting-->
        <div id="assessment_setting" class="tab-pane fade">
            <script type="text/javascript">
                $(document).ready(function () {
                    $("#assessmenttable").DataTable({
                        paging:true,
                        dom: 'Blfrtip',
                        buttons: [
                            'copy', 'csv', 'excel', 'pdf'
                        ]
                    });
                });
            </script>
            <h3>Grading Year Setting</h3>
            <hr>
            <!-- Start -->
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right">
                        <button class="btn btn-success" data-toggle="modal" data-target="#add_new_assessment_year">Define New Assessment</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <br>
                    <?php
                    if(!empty($_REQUEST['msg']))
                    {
                        if($_REQUEST['msg']=="succ")
                        {
                            echo "<div class='alert alert-success fade in'><a href='index3.php?sp=misc_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>Data has been inserted successfully</strong>.
</div>";
                        }
                        else if($_REQUEST['msg']=="edited")
                        {
                            echo "<div class='alert alert-success fade in'><a href='index3.php?sp=misc_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>Data has been edited Successfully</strong>.
</div>";
                        }
                        else if($_REQUEST['msg']=='unsucc')
                        {
                            echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=misc_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>Error-Sory, Data Name is already Exists</strong>.
</div>";
                        }
                        else {
                            echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=misc_setting' class='close' data-dismiss='alert'>&times;</a>
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
                    $assessment_type = $db->getRows('assessment_setting',array('order_by'=>'gradingYearID ASC'));
                    ?>
                    <table  id="assessmenttable" class="display">
                        <thead>
                        <tr>
                            <th>No.</th>
                            <th>Grade Year Name</th>
                            <th>Subject Type</th>
                            <th>#of Assessment</th>
                            <th>Marks for Each</th>
                            <th>Final Term Exam Marks</th>
                            <th>Changed?</th>
                            <th>Status</th>
                            <th>Edit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(!empty($assessment_type)){ $att = 0; foreach($assessment_type as $at){ $att++;
                            ?>
                            <tr>
                                <td><?php echo $att; ?></td>
                                <td><?php echo $db->getData('grading_year','gradingYearName','gradingYearID',$at['gradingYearID']); ?></td>
                                <td><?php echo $db->getData("course_type","courseType","courseTypeID",$at['subjectTypeID']);?></td>
                                <td><?php echo $at['numberOfTerms'];?></td>
                                <td><?php echo $at['assessmentMarks'];?></td>
                                <td><?php echo $at['termExamMarks'];?></td>
                                <td>

                                    <?php if ($at['changeStatus'] == 1) {
                                        ?>
                                        <span class="label label-success">Yes</span>
                                    <?php } else { ?>
                                        <span class="label label-danger">No</span>
                                    <?php } ?>

                                </td>
                                <td>
                                    <?php if ($gy['status'] == 1) {
                                        ?>
                                        <span class="label label-success">Active</span>
                                    <?php } else { ?>
                                        <span class="label label-danger">Not Active</span>
                                    <?php } ?>

                                </td>
                                <td>
                                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#message<?php echo $at['assessmentSettingID'];?>">
          	  <span class="fa fa-edit" aria-hidden="true" title="Edit">
                                </td>
                            </tr>
                            <div id="message<?php echo $at['assessmentSettingID'];?>" class="modal fade" role="dialog">
                                <div class="modal-dialog">
                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Edit Grade System</h4>
                                        </div>
                                        <form name="register" id="register" method="post" enctype="" action="action_grades.php">
                                            <div class="modal-body">
                                                <!--edit-->
                                                <div class="form-group">
                                                    <label for="email">Grade Year Name</label>
                                                    <select name="gradingYearID" id="gradingYearID" class="form-control" required>
                                                        <?php
                                                        $gradingYear = $db->getRows('grading_year',array('order_by'=>'gradingYearID ASC'));
                                                        if(!empty($gradingYear)){
                                                            echo"<option value=''>Please Select Here</option>";
                                                            $count = 0; foreach($gradingYear as $gy){ $count++;
                                                                $gradingYear=$gy['gradingYearName'];
                                                                $gradingYearID=$gy['gradingYearID'];
                                                                ?>
                                                                <option value="<?php echo $gradingYearID;?>"><?php echo $gradingYear;?></option>
                                                            <?php }}

                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="MiddleName">Subject Type</label>
                                                    <select name="academicYearID" id="academicYearID" class="form-control" required>
                                                        <option value='<?php echo $db->getData('academic_year','academicYearID','academicYearID',$gy['academicYearID']);?>'>
                                                            <?php echo $db->getData("academic_year","academicYear","academicYearID",$gy['academicYearID']);?></option>
                                                        <?php
                                                        $academicYear = $db->getRows('academic_year',array('where'=>array('status'=>1),'order_by'=>'academicYear ASC'));
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
                                                    <label for="email">Number of Terms</label>
                                                    <input type="text" id="numberofTerms" name="numberOfTerms" value="<?php echo $gy['numberOfTerms'];?>" class="form-control" required />
                                                </div>

                                                <div class="form-group">
                                                    <label for="email">Status</label>
                                                    <?php if ($gy['status'] == 1) {
                                                        ?>
                                                        <input type="radio" name="status" value="1" checked>Active
                                                        <input type="radio" name="status" value="0">Not Active
                                                    <?php } else { ?>
                                                        <input type="radio" name="status" value="1">Active
                                                        <input type="radio" name="status" value="0" checked>Not Active
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                <input type="hidden" name="gradingYearID" value="<?php echo $gy['gradingYearID'];?>">
                                                <input type="hidden" name="action_type" value="edit_grading_year"/>
                                                <input type="submit" name="doUpdate" value="Update Records" class="btn btn-success">
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        <?php }
                        }?>
                        </tbody>
                    </table>
                </div></div>



            <div class="modal fade" id="add_new_assessment_year" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form name="" method="post" action="action_grades.php">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                                <h4 class="modal-title" id="myModalLabel">Add New Record</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="modal-body">

                                        <div class="form-group">
                                            <label for="MiddleName">Grading Year</label>
                                            <select name="gradingYearID" id="gradingYearID" class="form-control" required>
                                                <?php
                                                $gradingYear = $db->getRows('grading_year',array('order_by'=>'gradingYearID ASC'));
                                                if(!empty($gradingYear)){
                                                    echo"<option value=''>Please Select Here</option>";
                                                    $count = 0; foreach($gradingYear as $gy){ $count++;
                                                        $gradingYear=$gy['gradingYearName'];
                                                        $gradingYearID=$gy['gradingYearID'];
                                                        ?>
                                                        <option value="<?php echo $gradingYearID;?>"><?php echo $gradingYear;?></option>
                                                    <?php }}

                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Subject Type</label>
                                            <select name="subjectTypeID" id="subjectTypeID" class="form-control" required>
                                                <?php
                                                $course_type = $db->getRows('course_type',array('order_by'=>'courseTypeID ASC'));
                                                if(!empty($course_type)){
                                                    echo"<option value=''>Please Select Here</option>";
                                                    foreach($course_type as $ct){
                                                        $courseTypeID=$ct['courseTypeID'];
                                                        $courseType=$ct['courseType'];
                                                        ?>
                                                        <option value="<?php echo $courseTypeID;?>"><?php echo $courseType;?></option>
                                                    <?php }}

                                                ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Number of Assessment</label>
                                            <input type="text" id="numberOfAssessment" name="numberOfAssessment" placeholder="Eg.2" class="form-control" required />
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Marks for each Assessment</label>
                                            <input type="text" id="assessmentMarks" name="assessmentMarks" placeholder="Eg. 20" class="form-control" required />
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Term Exam Marks</label>
                                            <input type="text" id="termExamMarks" name="termExamMarks" placeholder="Eg. 60" class="form-control" required />
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Instructor can Change Marks</label>
                                            <input type="radio" id="changeStatus" name="changeStatus" value="1">Yes
                                            <input type="radio" id="changeStatus" name="changeStatus" value="0" checked>No
                                        </div>



                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                        <input type="hidden" name="action_type" value="addassessment"/>
                                        <input type="submit" name="doSubmit" value="Add Record" class="btn btn-primary">

                                    </div></div></div>
                        </form>
                    </div>
                </div>
            </div>
        <!--end assessment setting-->
       
    </div>
    </div>
</div>