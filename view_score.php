<script type="text/javascript">
    $(document).ready(function () {
        $('#view_score').dataTable(
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
                        //orientation: 'portrait',
                    }

                ]
            });
    });
</script>
<?php
$db=new DBHelper();
?>
<div class="container">
<div class="content">
<h4>View Result for <span class="text-danger">
<?php

$courseID=$db->decrypt($_REQUEST['cid']);
$academicYearID=$db->decrypt($_REQUEST['acadID']);
$programmeLevelID=$db->decrypt($_REQUEST['lvlID']);

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
-<?php echo $db->getData("programme_level","programmeLevel","programmeLevelID",$programmeLevelID);?></span></h4>
<hr>
<div class="row"> 
<?php
$student = $db->getStudentExamList($courseID,$academicYearID,$programmeLevelID);
if(!empty($student))
{
                    ?>
                    <table  id="view_score" class="display nowrap">
                      <thead>
                      <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Reg.Number</th>
                          <th>Exam Number</th>
                        <th>CW</th>
                        <th>SFE</th>
                        <th>TTL</th>
                        <th>GRD</th>
                        <th>RMK</th>
                          <!--<th>Edit</th>-->
                         </tr>
                      </thead>
                      <tbody>
                    <?php 
                        $count = 0; 
                        foreach($student as $st)
                            { 
                                $count++;
                                $regNumber=$st['regNumber'];
                                $examNumber=$st['examNumber'];
                                $studentDetails=$db->getRows('student',array('where'=>array('registrationNumber'=>$regNumber),' order_by'=>'firstName ASC'));
                                foreach ($studentDetails as $std) {
                                # code...
                                $fname=$std['firstName'];
                                $mname=$std['middleName'];
                                $lname=$std['lastName'];
                                $name="$fname $mname $lname";
                                $gender=$std['gender'];
                                //$regNumber=$std['registrationNumber'];
                                echo "<tr><td>$count</td><td>$name</td><td>$gender</td><td>$regNumber</td><td>$examNumber</td>";
                              
                                //include("grade.php");
                                
                                $cwk=$db->decrypt($db->getGrade($semesterSettingID,$courseID,$regNumber,1));
                                $sfe=$db->decrypt($db->getFinalGrade($academicYearID,$courseID,$regNumber,3));
                                $sup=$db->decrypt($db->getGrade($semesterSettingID,$courseID,$regNumber,3));
                                $spc=$db->decrypt($db->getFinalGrade($semesterSettingID,$courseID,$regNumber,4));
                                $prj=$db->decrypt($db->getGrade($semesterSettingID,$courseID,$regNumber,5));
                                $pt=$db->decrypt($db->getGrade($semesterSettingID,$courseID,$regNumber,6));

                                if(!empty($sup)) {
                                    $sfe = $sup;
                                    $cwk="NAN";
                                }
                                else if(!empty($spc))
                                    $sfe=$spc;
                                else if(!empty($prj)) {
                                    $cwk="NAN";
                                    $sfe = $prj;
                                }
                                else if(!empty($pt)) {
                                    $sfe = $pt;
                                    $cwk="NAN";
                                }
                                else
                                    $sfe=$sfe;

/*                                echo "<td>".$cwk."</td><td>".$sfe."</td><td>".$sup."</td><td>".$spc."</td><td>".$pro."</td><td>".$pt."</td>";*/
                                //$gradeID=$db->getMarksID($regNumber,$cwk,$sfe,$sup,$spc,$prj,$pt);


                                    $present=$db->getStudentExamStatus($regNumber,$courseID,$semesterSettingID,2);
                                echo "<td>".$cwk."</td><td>".$sfe."</td>";
                                echo "<td>".$db->calculateTotal($cwk, $sfe, $sup, $spc, $prj, $pt)."</td>";
                                if($present==1)
                                {
                                    echo "<td>" . $db->calculateGrade($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt) . "</td>";
                                    echo "<td>" . $db->courseRemarks($regNumber, $cwk, $sfe, $sup, $spc, $prj, $pt) . "</td>";
                                }
                                else if($present==0)
                                {
                                    $grade="A0";
                                    echo "<td>A0</td>";
                                    echo "<td>COURSE REPEAT</td>";
                                }
                                else
                                {
                                    $grade="A1";
                                    echo "<td>A1</td>";
                                    echo "<td>SPECIAL EXAM</td>";
                                }

                                    $editButton = '
    	   <div class="btn-group">
    	         <a href="index3.php?sp=edit_score&cid='.$db->my_simple_crypt($courseID,'e').'&sid='.$db->my_simple_crypt($semesterSettingID,'e').'&regno='.$db->my_simple_crypt($regNumber,'e').'&bid='.$db->my_simple_crypt($batchID,'e').'" class="glyphicon glyphicon-edit"></a>
    	   </div>';
                                ?>
                                    <!--<td>
                                        <?php
/*                                        echo $editButton;
                                        */?>
                                    </td>-->
                      </tr>
                                    <!--<div id="#count<?php /*echo $count;*/?>&regNumber=<?php /*echo $regNumber;*/?>" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Edit Course Work For <?php /*echo $regNumber;*/?></h4>
                                                </div>
                                                <form name="register" id="register" method="post" enctype="" action="action_grades.php">
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="email">Score Marks</label>
                                                            <input type="text" id="marks" name="score_marks" value="<?php /*echo $cwk;*/?>" maxlength="2" class="form-control" required />
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        <input type="hidden" name="gradeID" value="<?php /*echo $gd['gradeID'];*/?>">
                                                        <input type="hidden" name="action_type" value="edit_grade"/>
                                                        <input type="submit" name="doUpdate" value="Update Records" class="btn btn-success">
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>-->

                                    <!--edit final exam-->
                                    <!--<div id="message2" class="modal fade" role="dialog">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Edit Grade System</h4>
                                                </div>
                                                <form name="register" id="register" method="post" enctype="" action="action_grades.php">
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="email">Grade Code</label>
                                                            <input type="text" id="code" name="gradeCode" value="<?php /*echo $gd['gradeCode'];*/?>" maxlength="2" class="form-control" required />
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                        <input type="hidden" name="gradeID" value="<?php /*echo $gd['gradeID'];*/?>">
                                                        <input type="hidden" name="action_type" value="edit_grade"/>
                                                        <input type="submit" name="doUpdate" value="Update Records" class="btn btn-success">
                                                    </div>
                                                </form>
                                            </div>

                                        </div>
                                    </div>-->
                                    <!--end edit of final exam-->

                                    <?php
                             ?>
                
                 <?php
                }
                }
                ?>
                 </tbody>
                 </table>
                <?php
              }
                else
                    { 
                        ?>
                        <h4 class="text-danger">No Result(s) found......</h4>
                        <?php 
                    } 
                   ?>
                  
        </div>
        </div>
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
        <div class="col-lg-3">
            <!--<button type="button" class="btn btn-primary pull-right form-control" style="margin-right: 5px;">
                <i class="fa fa-download"></i>Print Report
            </button>-->
            <button class="btn btn-primary pull-right form-control" style="margin-right: 5px;" data-toggle="modal" data-target="#add_new_atype_modal"><i class="fa fa-download"></i>Print Report</button>
        </div>
    </div>

</div>

<div id="add_new_atype_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Preview Course Result</h4>
            </div>
            <div class="modal-body">
                <embed src="print_score_report.php?action=getPDF&cid=<?php echo $courseID;?>&bid=<?php echo $batchID;?>&sid=<?php echo $semesterSettingID;?>"
                       frameborder="0" width="100%" height="600px">

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</div>

