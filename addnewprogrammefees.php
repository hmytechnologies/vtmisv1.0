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
<form name="" method="post" action="action_add_programme_fees.php">
<div class="row">
<div class="col-lg-12">
<div class="row">
<div class="col-lg-6">
<div class="form-group">
<label for="email">Programme Name</label>
<select name="programmeID[]" class="form-control chosen-select" multiple id="programmeID"   required="">
<?php
$programmes = $db->getRows("programmes",array('order_by'=>'programmeID'));
if(!empty($programmes)){ 
  echo"<option value=''>Please Select Here</option>";
  foreach($programmes as $prg)
   { 
      $programmeName=$prg['programmeName'];
      $programmeID=$prg['programmeID'];
      echo "<option value='$programmeID'>$programmeName</option>";
   }
   }
   ?> 
</select>
</div></div>
<div class="col-lg-4">

                            <label for="MiddleName">Academic Year</label>
                            <select name="admissionYearID" class="form-control" required="">
                              <?php
                               $adYear = $db->getRows('academic_year',array('order_by'=>'academicYear ASC'));
                               if(!empty($adYear)){ 
                                echo"<option value=''>Please Select Here</option>";
                                $count = 0; foreach($adYear as $year){ $count++;
                                $academic_year=$year['academicYear'];
                                $academic_year_id=$year['academicYearID'];
                               ?>
                               <option value="<?php echo $academic_year_id;?>"><?php echo $academic_year;?></option>
                               <?php }}
           ?>
                           </select>
</div>
</div>
<div class="row">
              <table id="admit" class="display nowrap" cellspacing="0">
                  <thead>
                <tr>
                  <th>No</th>
                  <th>Fees Type</th>
                  <th>Amount(TSH)</th>
                  <th>Amount(US$)</th>
                  <th>Paid Once</th>
                </tr>
                  </thead>
                  <tbody>
       <?php
            $users = $db->getRows('feestype',array('where'=>array('feesID'=>1,'feesTypeStatus'=>1),'order_by'=>'feesTypeID ASC'));
            if(!empty($users)){ 
                $count = 0; 
                foreach($users as $user){
                    $count++;
                    ?>
             <tr>
                <td><?php echo $count; ?></td>
                <input type="hidden" name="feesTypeID<?php echo $count;?>" value="<?php echo $user['feesTypeID'];?>">
                <td><?php echo $user['feesType']; ?></td>
                <td><input type="text" name="amounttz<?php echo $count;?>" class="form-control"></td>
                <td><input type="text" name="amountus<?php echo $count;?>" class="form-control"></td>
                <td><input type="radio" name="paid<?php echo $count;?>" value="1">Yes<input type="radio" value="0" name="paid<?php echo $count;?>" checked>No</td>
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