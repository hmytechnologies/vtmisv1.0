<?php
$db=new DBHelper();
?>
<script src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
  $(document).ready(function () {
            $('#admit').dataTable(
                {
                    paging: false,
                    searching:false
                });
          });
</script>
<div class="container">
<h4>Define Programme Requirements</h4>
<hr>
<form name="" method="post" action="action_add_exam_category_setting.php">
<div class="row">
<div class="col-lg-12">
<div class="row">
<div class="col-lg-4">
<div class="form-group">
<label for="MiddleName">Programme Level</label>
                            <select name="programmeLevelID[]" id="programmeLevelID[]" multiple class="form-control chosen-select" required>
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
</div></div>
<div class="col-lg-4">
        <label for="MiddleName">Grading Year</label>
        <select name="gradingYearID" id="gradingYearID" class="form-control" required>
            <?php
            $academicYear = $db->getRows('grading_year',array('where'=>array('status'=>1),'order_by'=>'Status ASC'));
            if(!empty($academicYear)){
                echo"<option value=''>Please Select Here</option>";
                $count = 0; foreach($academicYear as $sm){ $count++;
                    $gradingYearID=$sm['gradingYearID'];
                    $gradingYearName=$sm['gradingYearName'];
                    ?>
                    <option value="<?php echo $gradingYearID;?>"><?php echo $gradingYearName;?></option>
                <?php }
            }
            ?>
        </select>
    </div>
</div>
<div class="row">
              <table id="admit" class="display nowrap">
                  <thead>
                <tr>
                  <th>No.</th>
                        <th>Exam Category</th>
                        <th>Max.Mark</th>
                        <th>Weighted Mark</th>
                        <th>Pass Mark</th>
                </tr>
                  </thead>
                  <tbody>
       <?php
            $users = $db->getRows('exam_category',array('order_by'=>'examCategoryID ASC'));    
            if(!empty($users)){ 
                $count = 0; 
                foreach($users as $user){
                    $count++;
         ?>       
             <tr>
                <td><?php echo $count; ?></td>
                <input type="hidden" name="examCategoryID<?php echo $count;?>" value="<?php echo $user['examCategoryID'];?>">
                <td><?php echo $user['examCategory']; ?></td>
                <td><input type="text" name="maxMark<?php echo $count;?>" class="form-control"></td>
                <td><input type="text" name="wMark<?php echo $count;?>" class="form-control"></td>
                <td><input type="text" name="passMark<?php echo $count;?>" class="form-control"></td>
                
                
            </tr>
            <?php } }?>   
                  </tbody>
              </table>
 </div>
</div>
    <div class="row">  
        <div class="col-lg-6"></div>
<div class="col-lg-3">
<input type="hidden" name="action_type" value="add"/>
<input type="hidden" name="number" value="<?php echo $count;?>">
<input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control">        
</div>
<div class="col-lg-3">
<input type="reset" name="doSubmit" value="Cancel" class="btn btn-primary form-control">        
</div></div>

</div>
</form>
</div>
