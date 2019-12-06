<?php $db=new DBHelper();
?>
<div class="container">
<h3>Upload Student Payment From CSV File</h3>

<h5 class="text-danger">NB:Your file must have this format(RegNumber,Amount Paid(without comma or decimal),Receipt Number,Date of Payment(YYYY-MM-DD))</h5>	
<hr>
<div class="row">
<?php 
if(!empty($_REQUEST['msg']))
{
  if($_REQUEST['msg']=="succ")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Payment data has been uploaded successfully</strong>.
</div>";
  }
  
  else if($_REQUEST['msg']=="error") {
      echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Something wrong happening, contact System Administrator</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="unsucc") {
      echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Something wrong happening, contact System Administrator</strong>.
</div>";
  }
}
?> 
</div>
            <form name="" method="post" action="action_upload_student_payment.php" enctype="multipart/form-data">
            <div class="row">        
                        <div class="col-lg-3">
                           <label for="FirstName">Semester Name</label>
                            <select name="semesterID" class="form-control" required>
                              <?php
                                 $semister = $db->getRows('semester_setting',array('order_by'=>'semesterName ASC'));
                                 if(!empty($semister)){
                                  echo"<option value=''>Please Select Here</option>";
                                  $count = 0; foreach($semister as $sm){ $count++;
                                  $semister_name=$sm['semesterName'];
                                  $semister_id=$sm['semesterSettingID'];
                                 ?>
                                 <option value="<?php echo $semister_id;?>"><?php echo $semister_name;?></option>
                                 <?php }}

                                 ?>
                           </select>
                        </div>
                        
                         <div class="col-lg-2">
                           <label for="FirstName">CSV/Excel File</label>
                            <input type='file' name="csv_file" accept=".csv" />
                        </div>
               
                      <div class="col-lg-3">
                      <label for=""></label>
                      <input type="hidden" name="action_type" value="add"/>
                      <input type="submit" name="doFind" value="Upload File" class="btn btn-primary form-control" /></div>
                      </div>
                   </form>

                   <div class="row"><br></div>
                   
</div>