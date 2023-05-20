<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#image')
                    .attr('src', e.target.result)
                    .width(150)
                    .height(150);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?php $db=new DBHelper();
?>
<div class="container">
    <div class="content">
        <h3>Student Academic Reports</h3>
        <hr>
                <div class="row">

                    <?php
                    $db=new DBhelper();
                        $searchStudent=$db->my_simple_crypt($_REQUEST['regNo'],'d');

                        $student = $db->transcriptList($searchStudent);
                        if(!empty($student))
                        {
                            ?>
                            <div class="box box-solid box-primary">
                                <div class="box-header with-border text-center">
                                    <h3 class="box-title">Personal Information</h3>
                                </div>
                                <div class="box-body">
                                    <table class="table table-striped table-bordered table-condensed">
                                        <thead>
                                        <tr>
                                            <th>Student Name</th>
                                            <th>Reg.No</th>
                                            <th>Gender</th>
                                            <th>Level</th>
                                            <th>Programme Name</th>
                                            <th>Study Mode</th>
                                            <th>Status</th>
                                            <th>Picture</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $count = 0;
                                        foreach($student as $std)
                                        {
                                            $count++;
                                            $fname=$std['firstName'];
                                            $mname=$std['middleName'];
                                            $lname=$std['lastName'];
                                            $gender=$std['gender'];
                                            $regNumber=$std['registrationNumber'];
                                            $programmeID=$std['programmeID'];
                                            // $batchID=$std['batchID'];
                                            $studentPicture=$std['studentPicture'];
                                            $name="$fname $mname $lname";


                                            echo "<tr><td>$name</td><td>$regNumber</td><td>$gender</td><td>";

                                            $programmeLevelID=$db->getData("programmes","programmeLevelID","programmeID",$programmeID);
                                            $level= $db->getRows('programme_level',array('where'=>array('programmeLevelID'=>$programmeLevelID),' order_by'=>' programmeLevelCode ASC'));
                                            if(!empty($level))
                                            {
                                                foreach ($level as $lvl) {
                                                    $programme_level_code=$lvl['programmeLevelCode'];
                                                    echo "$programme_level_code</td><td>";
                                                }
                                            }

                                            $programme= $db->getRows('programmes',array('where'=>array('programmeID'=>$programmeID),' order_by'=>' programmeName ASC'));
                                            if(!empty($programme))
                                            {
                                                foreach ($programme as $pro) {
                                                    $programmeName=$pro['programmeName'];
                                                    $programmeDuration=$pro['programmeDuration'];
                                                    echo "$programmeName</td>";
                                                }
                                            }
                                            // echo "<td>".$db->getData("batch","batchName","batchID",$batchID)."</td><td>Graduate</td>";
                                            echo "<td>Graduate</td>";
                                        }
                                        ?>
                                        <td>
                                            <?php
                                            if(!empty($studentPicture)) {
                                                ?>
                                                    <img id="image" src="student_images/<?php echo $studentPicture;?>" height="120px" width="120px;" />
                                                <?php
                                            }
                                            ?>
                                        </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                    <script type="text/javascript">
                        $(document).ready(function () {
                            $("#exam_date").datepicker({
                                dateFormat:"yy-mm-dd",
                                changeMonth:true,
                                changeYear:true,
                            });
                        });
                    </script>


                            <script>
                                $(document).ready(function(){
                                    $("#studyYear").change(function(){
                                        var studyYear = $(this).val();
                                        var regNumber=$("#regNo").val();

                                        $.ajax({
                                            url: 'json_study_year.php',
                                            type: 'post',
                                            data: {studyYear:studyYear,regNumber:regNumber},
                                            dataType: 'json',
                                            success:function(response){

                                                var len = response.length;

                                                $("#sel_acad").empty();
                                               /* $("#sele_programme").empty();*/
                                                /*$("#sel_plevel").empty();*/
                                                for( var i = 0; i<len; i++){
                                                    var academicYear = response[i]['academicYear'];
                                                    var academicYearID = response[i]['academicYearID'];
                                                    var programmeID=response[i]['programmeID'];
                                                    var programmeName=response[i]['programmeName'];
                                                    var programmeLevelID=response[i]['programmeLevelID'];
                                                    var programmeLevelName=response[i]['programmeLevelName'];

                                                    $("#sel_acad").append("<option value='"+academicYearID+"' selected>"+academicYear+"</option>");
                                                    $("#sel_plevel").append("<option value='"+programmeLevelID+"' selected>"+programmeLevelName+"</option>");
                                                    /*$("#sele_programme").append("<option value='"+programmeID+"' selected>"+programmeName+"</option>");*/


                                                }
                                            }
                                        });
                                    });

                                });
                            </script>


                            <script type="text/javascript">
                                $(document).ready(function()
                                {
                                    $("#sel_plevel").change(function()
                                    {
                                        var programmeLevelID=$(this).val();
                                        var dataString = 'programmeLevelID='+programmeLevelID;
                                        $.ajax
                                        ({
                                            type: "POST",
                                            url: "ajax_programme.php",
                                            data: dataString,
                                            cache: false,
                                            success: function(html)
                                            {
                                                $("#sele_programme").html(html);
                                            }
                                        });

                                    });

                                });
                            </script>

                            <form name="" method="post" action="action_student_transcript.php">
                        <input type="hidden" id="regNo" value="<?php echo $regNumber;?>">
                        <div class="row">
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="email">Study Year</label>
                                    <select name="studyYearID" id="studyYear" class="form-control" required>
                                        <?php
                                        echo "<option value=''>Please Select Here</option>";
                                        $studyYear = $db->getRows("student_study_year", array('where' => array('regNumber' => $searchStudent)));
                                        if (!empty($studyYear)) {
                                            foreach ($studyYear as $styear) {
                                                ?>
                                                <option value="<?php echo $styear['studyYear']; ?>"><?php echo $styear['studyYear']; ?></option>
                                                <?php
                                            }
                                        } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="email">Academic Year</label>
                                    <select name="academicYearID" id="sel_acad" class="form-control">
                                        <option value="">--show--</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="email">Programme Level</label>
                                    <select name="programmeLevelID" id="sel_plevel" class="form-control" required>
                                        <option value="" selected>Select Here</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-3">
                                <div class="form-group">
                                    <label for="email">Programme Name</label>
                                    <select name="programmeID" id="sele_programme" class="form-control" required>
                                        <option value="">Select Here</option>
                                    </select>

                                </div>
                            </div>

                        </div>

                        <br>
                        <div class="row">
                            <div class="col-lg-6"></div>
                            <div class="col-lg-3">
                                <input type="hidden" name="action_type" value="add"/>
                                <input type="hidden" name="regNumber" value="<?php echo $regNumber;?>">
                                <input type="submit" name="doSubmit" value="Save Records" class="btn btn-primary form-control"/>
                            </div>
                            <div class="col-lg-3">
                                <input type="reset" value="Cancel" class="btn btn-primary form-control"/>
                            </div>
                        </div>
                    </form>



                            <!--view list of registered data-->
                             <br>
    <br>
        <?php
        $transcriptlist = $db->getRows("student_transcript",array('where'=>array('regNumber'=>$regNumber)));
        if (!empty($transcriptlist)) {
        ?>
         <div class="col-md-12">
                            <div class="box box-solid box-primary">
                                <div class="box-header with-border text-center">
                                    <h3 class="box-title">Registered List</h3>
                                </div>
        <div class="box-body">
             <table id="" class="table table-striped table-bordered table-condensed">
                 <thead>
                 <tr>
                     <th>Study Year</th>
                     <th>Academic Year</th>
                     <th>Programme Level</th>
                     <th>Programme Name</th>
                     <th>Action Date</th>
                     <th>Preview</th>
                     <th>Drop</th>
                 </tr>
                 </thead>
                 <tbody>
                 <?php
                 foreach ($transcriptlist as $tr) {
                     ?>

                     <tr>
                         <td><?php echo $tr['studyYear'];?></td>
                         <td><?php echo $db->getData("academic_year","academicYear","academicYearID",$tr['academicYearID']);?></td>
                         <td><?php echo $db->getData("programme_level","programmeLevel","programmeLevelID",$tr['programmeLevelID']);?></td>
                         <td><?php echo $db->getData("programmes","programmeName","programmeID",$tr['programmeID']);?></td>

                         <td><?php echo date('d-m-Y H:m:i',strtotime($tr['createdDate']));?></td>

                        <td> <button class="btn btn-primary pull-right form-control" style="margin-right: 5px;" data-toggle="modal" data-target="#view_transcript<?php echo $tr['ID'];?>"><i class="fa fa-download"></i>Preview</button></td>
                         <td>
                                 <a href="action_student_transcript.php?action_type=drop&id='.<?php echo $db->my_simple_crypt($tr['ID'],'e');?>&regNo=<?php echo $db->my_simple_crypt($tr['regNumber'],'e');?>" class="btn btn-primary glyphicon glyphicon-trash" role="button"></a>
                         </td>

                     </tr>
                     <div id="view_transcript<?php echo $tr['ID'];?>" class="modal fade" role="dialog">
                         <div class="modal-dialog modal-lg">

                             <!-- Modal content-->
                             <div class="modal-content">
                                 <div class="modal-header">
                                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                                     <h4 class="modal-title">Preview Transcript of <?php echo $tr['ID'];?></h4>
                                 </div>
                                 <div class="modal-body">
                                     <!--<embed src="print_patial_transcript_report.php?action=getPDF&regNo=<?php /*echo $regNumber;*/?>&academicYearID=<?php /*echo $std['acamicYearID'];*/?>" frameborder="0" width="100%" height="600px">-->
                                     <embed src="print_patial_transcript_report.php?action=getPDF&id=<?php echo $tr['ID'];?>" frameborder="0" width="100%" height="600px">
                                     <div class="modal-footer">
                                         <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                     </div>
                                 </div>

                             </div>
                         </div>
                     </div>
                 <?php
                 } ?>
                 </tbody>
             </table>
         </div>
     </div>
 </div>
        <?php
        }
        ?>
                            <!--end of view-->
                            <?php
                        }
                        else
                        {
                            echo "<h3 class='text-danger'>Student with Reg.Number: ".$searchStudent." are not in graduate list</h3>";
                        }
                    ?>

                </div>



        <br><br>
        <div class="row">
            <div class="col-lg-3">
                    <a href="index3.php?sp=student_academic_reports" class="btn btn-success form-control">Go Back</a>
            </div>
        </div>
            </div>

        </div>