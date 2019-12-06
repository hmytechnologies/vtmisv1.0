 <script type="text/javascript" src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
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
    <h1>Exam Date Settings</h1>
    <hr>
    <div class="col-md-6">

    </div>
    <div class="col-md-6">
        <div class="pull-right">
            <a href="index3.php?sp=semester_setting" class="btn btn-warning">Back to Main Setting</a>
        </div>
    </div>
  <div class="content">
     <?php 
if(!empty($_REQUEST['msg']))
{
    if($_REQUEST['msg']=="succ")
    {
        echo "<div class='alert alert-success fade in'><a href='index3.php?sp=exam_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>Exam data has been inserted successfully</strong>.
</div>";
    }
    else if($_REQUEST['msg']=="exist") {
        echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=exam_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>Exam data already exist</strong>.
</div>";
    }
    else if($_REQUEST['msg']=="date") {
        echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=exam_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>Date data are not in correct format</strong>.
</div>";
    }
    else if($_REQUEST['msg']=="deleted") {
        echo "<div class='alert alert-danger fade in'><a href='index3.php?sp=exam_setting' class='close' data-dismiss='alert'>&times;</a>
    <strong>Exam Data has been delete successfully</strong>.
</div>";
    }
}
?>
        <div id="semestercourse">
            <h4>Please select exams start and end dates for this semester</h4>
            <form name="" method="post" action="action_exam_setting.php">
            <div class="row">
            <div class="col-lg-3">
                           <label for="FirstName">Semester Name</label>
                            <select name="semisterID" class="form-control" required>
                              <?php
                                 $semister = $db->getRows('semester_setting',array('order_by'=>'semesterName ASC'));
                                 if(!empty($semister)){
                                  echo"<option value=''>Please Select Here</option>";
                                  $count = 0; foreach($semister as $sm){ $count++;
                                  $semisterName=$sm['semesterName'];
                                  $semisterSettingID=$sm['semesterSettingID'];
                                 ?>
                                 <option value="<?php echo $semisterSettingID;?>"><?php echo $semisterName;?></option>
                                 <?php }}

                                 ?>
                           </select>
                        </div>
                        <div class="col-lg-3">
                           <label for="FirstName">Start Date</label>
                           <div class="input-group date form_date col-md-12" data-date="" data-date-format="yyyy-mm-dd" 
                            data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" name="startDate" value="" id="pickyDate"> 
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>

                         <div class="col-lg-3">
                           <label for="FirstName">End Date</label>
                             <div class="input-group date form_date col-md-12" data-date="" data-date-format="yyyy-mm-dd" 
                            data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                            <input class="form-control" size="16" type="text" name="endDate" value="" id="pickyDate2"> 
                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                            </div>
                        </div>
                <div class="col-lg-3">
                    <label for=""></label>
                    <input type="hidden" name="action_type" value="add"/>
                    <input type="submit" name="doFind" value="Save Records" class="btn btn-primary form-control" /></div>

       </div>             


        </form>


        <br>
        <div class="row"><br></div>
        
        <div class="row">
            <h3>List of Registered Semesters</h3>
            <?php
               $exam= $db->getRows('exam_setting',array(' order_by'=>'startDate ASC'));
    
               if(!empty($exam))
                {
                    ?>
                    <table  id="exampleexampleexample" class="display nowrap">
                      <thead>
                      <tr>
                        <th>No.</th>
                        <th>Semester Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Action</th>
                         </tr>
                      </thead>
                      <tbody>
                    <?php 
                    $count = 0; 
                    foreach($exam as $sm)
                    { 
                      $count++;
                      $examSettingID=$sm['examSettingID'];
                      $semesterSettingID=$sm['semesterSettingID'];
                      $startDate=$sm['startDate'];
                      $endDate=$sm['endDate'];
                      $examStatus=$sm['examStatus'];
                      if($examStatus==1)
                        $status="Active";
                      else
                        $status="Not Active";

                          ?>
                          <tr>
                          <td><?php echo $count;?></td>
                          <td><?php echo $db->getData("semester_setting","semesterName","semesterSettingID",$semesterSettingID);?></td>
                          <td><?php echo date('d-m-Y',strtotime($startDate));?></td>
                          <td><?php echo date('d-m-Y',strtotime($endDate));?></td>
                          <td><?php echo $status;?></td>
                          <td><!--<a href="action_exam_setting.php?action_type=delete&id=<?php /*echo $examSettingID; */?>"
                                class="glyphicon glyphicon-trash" onclick="return confirm('Are you sure you want to delete this Semester Setting?');"></a>-->
                              <button type="button" class="btn btn-success" data-toggle="modal" data-target="#message<?php echo $examSettingID;?>">
                                  <span class="glyphicon glyphicon-edit" aria-hidden="true"></span>
                                  <span><strong></strong></span>
                          </td>
                          </tr>

                        <div id="message<?php echo $examSettingID;?>" class="modal fade" role="dialog">
                            <div class="modal-dialog">

                                <!-- Modal content-->
                                <div class="modal-content">
                                    <form name="" id="" role="form" method="post" action="action_exam_setting.php">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel">Update Record</h4>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="modal-body">

                                                    <div class="form-group">

                                                        <label for="FirstName">Semester Name</label>
                                                        <select name="semisterID" class="form-control" required>
                                                            <option value="<?php echo $semesterSettingID;?>"><?php echo $db->getData("semester_setting","semesterName","semesterSettingID",$semesterSettingID);?></option>
                                                            <?php
                                                            $semister = $db->getRows('semester_setting',array('order_by'=>'semesterName ASC'));
                                                            if(!empty($semister)){
                                                                $count = 0; foreach($semister as $sm){ $count++;
                                                                    $semister_name=$sm['semesterName'];
                                                                    $semister_id=$sm['semesterSettingID'];
                                                                    ?>
                                                                    <option value="<?php echo $semister_id;?>"><?php echo $semister_name;?></option>
                                                                <?php }
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="FirstName">Start Date</label>
                                                        <div class="input-group date form_date col-md-12" data-date="" data-date-format="yyyy MM dd"
                                                             data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                            <input class="form-control" size="16" type="text" name="startDate" value="<?php echo $startDate;?>" id="pickyDate4">
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="FirstName">End Date</label>
                                                        <div class="input-group date form_date col-md-12" data-date="" data-date-format="yyyy MM dd"
                                                             data-link-field="dtp_input2" data-link-format="yyyy-mm-dd">
                                                            <input class="form-control" size="16" type="text" name="endDate" value="<?php echo $endDate;?>" id="pickyDate5">
                                                            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Status</label>
                                                    <?php
                                                    if($examStatus==1) {
                                                        ?>
                                                        <input type="radio" id="status" name="status" value="1"
                                                               checked/>Yes
                                                        <input type="radio" id="status" name="status" value="0"/>No
                                                        <?php
                                                    }else
                                                    {
                                                        ?>
                                                        <input type="radio" id="status" name="status" value="1"/>Yes
                                                        <input type="radio" id="status" name="status" value="0" checked/>No
                                                        <?php
                                                    }
                                                    ?>
                                                </div>

                                            </div></div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                            <input type="hidden" name="action_type" value="edit"/>
                                            <input type="hidden" name="id" value="<?php echo $examSettingID;?>">
                                            <input type="submit" name="doSubmit" value="Update Record" class="btn btn-primary">
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>

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
                        <h4 class="text-danger">No Exam Setting found......</h4>
                        <?php 
                    } 
                   ?>
                   
                 <?php
        
?>
        </div>
        </div>
        
        
    </div>
    </div>