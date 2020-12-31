<?php
$db=new DBHelper();
?>
<div class="container">
<h3><span class="text-primary">Upload Result for 
<?php 
$courseID=$db->decrypt($_REQUEST['cid']);
$academicYearID=$db->decrypt($_REQUEST['acadID']);
$levelID=$db->decrypt($_REQUEST['lvlID']);

$course = $db->getRows('course',array('where'=>array('courseID'=>$courseID),'order_by'=>'courseID ASC'));
if(!empty($course))
{
    foreach($course as $c)
    {
        $courseCode=$c['courseCode'];
        $courseName=$c['courseName'];
        $courseTypeID=$c['courseTypeID'];
    }
}
echo $courseCode."-".$courseName."-".$db->getData("academic_year","academicYear","academicYearID",$db->decrypt($_REQUEST['acadID']));
?>
-<?php echo $db->getData("programme_level","programmeLevel","programmeLevelID",$levelID);?></span></h3>

<h5 class="text-danger">NB:Your file must have this format(RegNumber/Exam Number,Exam Score,Status(1-Present,0-Absent)) Download Sample <a href="uploaded_file/exam_sheet.csv" class="glyphicon glyphicon-download-alt" target="_blank"></a> </h5>
<hr>

<div class="row">
<div class="col-lg-12">
<?php
if(!empty($_REQUEST['msg']))
{
    if($_REQUEST['msg']=="succ")
    {
        echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Your Records has been uploaded successfully</strong>.
</div>";
    }
    else if($_REQUEST['msg']=="unsucc")
    {
        echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Sorry,Error in uploading data<br>1. Make sure students are registered before uploading<br>2.Make sure file format contains only three two columns (Reg/Exam Number+Exam Score+1/0)<br>
    3.Improper file format! A file must be in the form of reg/exam number, score,present(1,0)<br>
    4. Course Work/Final Exam Marks must be less than or equal to 40/60,Review your Marks before you submit to the system<br>
    5. Contact System Administrator</strong>.
</div>";
    }
    else
    {
        echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>1.Error-Sory, Something Wrong happen, Contact System Administrator for more Information<br>2.Make sure file format contains only three two columns (Reg/Exam Number+Exam Score+1/0)<br>
    3.Improper file format! A file must be in the form of reg/exam number, score,present(1,0)<br>
    4. Course Work/Final Exam Marks must be less than or equal to 40/60,Review your Marks before you submit to the system
   </strong>.
</div>";
    }
}

//          if(!empty($_SESSION['statusMsg'])){
//             echo "<div class='alert alert-success fade in'>
//              <a href='#' class='close' data-dismiss='alert'>&times;</a>
//              <strong>".$_SESSION['statusMsg']."</strong>.
//          </div>";
//              unset($_SESSION['statusMsg']);
//          }

?>
</div>
</div>
<form name="" method="post" action="action_upload_exam_score.php" enctype="multipart/form-data">
<input type="hidden" id="courseID" value="<?php echo $courseID;?>">
<input type="hidden" id="semesterID" value="<?php echo $semesterSettingID;?>">
<input type="hidden" id="batchID" value="<?php echo $batchID;?>">
    <script type="text/javascript">
        $(document).ready(function () {
            $("#exam_date").datepicker({
                dateFormat:"yy-mm-dd",
                changeMonth:true,
                changeYear:true,
            });
        });
    </script>
<div class="row">

<div class="col-lg-3">
<label for="MiddleName">Exam Date</label>
    <input type="text" name="examDate" class="form-control" id="exam_date">
</div>

<div class="col-lg-3">
<div class="form-group">
<label for="email">Exam Category</label>
<select name="examCategoryID" class="form-control" id="examCategoryID">
<?php
$exam_category=$db->getFinalExamCategory();
if(!empty($exam_category)){ 
  echo"<option value=''>Please Select Here</option>";
  foreach($exam_category as $prg)
   { 
      $examCategory=$prg['examCategory'];
      $examCategoryID=$prg['examCategoryID'];
        echo "<option value='$examCategoryID'>$examCategory</option>";
   }
   }
   ?> 
</select>
</div>
</div>
<div class="col-lg-3">
                           <label for="FirstName">Attachment</label>
                            <input type='file' name="csv_file" accept=".csv" />
                        </div>
</div>
<div class="row">
                    <div class="col-lg-6"></div>
                      <div class="col-lg-3">
                      <label for=""></label>
                      <input type="hidden" name="action_type" value="add"/>
                      <input type="hidden" name="courseID" value="<?php echo $courseID;?>">
                      <input type="hidden" name="academicYearID" value="<?php echo $academicYearID;?>">
                      <input type="hidden" name="levelID" value="<?php echo $levelID;?>">
                      <input type="submit" name="doFind" value="Upload File" class="btn btn-primary form-control" /></div>
                      </div>
                   </form>



    <div class="row">
        <div class="col-lg-3">
            <?php
            if($_SESSION['role_session']==3)
            {
                ?>
                <a href="index3.php?sp=instructor_exam_results" class="btn btn-success form-control">Go Back</a>
                <?php
            }
            else {
                ?>
                <a href="index3.php?sp=addresult" class="btn btn-success form-control">Go Back</a>
                <?php
            }
            ?>
        </div>
    </div>
</div>
