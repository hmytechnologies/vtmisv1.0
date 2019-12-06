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
$semesterSettingID=$db->decrypt($_REQUEST['sid']);
$batchID=$db->decrypt($_REQUEST['bid']);

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
echo $courseCode."-".$courseName."-".$db->getData("semester_setting","semesterName","semesterSettingID",$db->decrypt($_REQUEST['sid']));
?>
-<?php echo $db->getData("batch","batchName","batchID",$batchID);?></span></h4>
        <hr>
        <div class="row">
            <?php
            $student = $db->getStudentSuppExamResult($courseID,$semesterSettingID,$batchID);
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
                        <!--<th>CW</th>
                        <th>Supp</th>-->
                        <th>TTL</th>
                        <th>GRD</th>
                        <th>RMK</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $count = 0;
                    foreach($student as $st)
                    {
                        $count++;
                        $regNumber=$st['regNumber'];
                        $studentDetails=$db->getRows('student',array('where'=>array('registrationNumber'=>$regNumber),' order_by'=>'firstName ASC'));
                        foreach ($studentDetails as $std) {
                            # code...
                            $fname=$std['firstName'];
                            $mname=$std['middleName'];
                            $lname=$std['lastName'];
                            $name="$fname $mname $lname";
                            $gender=$std['gender'];
                            //$regNumber=$std['registrationNumber'];
                            echo "<tr><td>$count</td><td>$name</td><td>$gender</td><td>$regNumber</td>";

                            //include("grade.php");

                            $cwk=$db->decrypt($db->getGrade($semesterSettingID,$courseID,$regNumber,1));
                            $sfe=$db->decrypt($db->getFinalGrade($semesterSettingID,$courseID,$regNumber,2));
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

                           /* echo "<td>".$cwk."</td><td>".$sfe."</td>";*/
                            echo "<td>".$db->calculateTotal($cwk, $sfe, $sup, $spc, $prj, $pt)."</td>";
                            echo "<td>".$db->calculateGrade($regNumber,$cwk, $sfe, $sup, $spc, $prj, $pt)."</td>";
                            echo "<td>".$db->courseRemarks($regNumber,$cwk, $sfe, $sup, $spc, $prj, $pt)."</td>";
                            /*<td>".$db->getData("grades","gradeCode","gradeID",$gradeID)."</td>*/
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

                <a href="index3.php?sp=supp_special" class="btn btn-success form-control">Go Back</a>

        </div>
       <!-- <div class="col-lg-3">
            <!--<button type="button" class="btn btn-primary pull-right form-control" style="margin-right: 5px;">
                <i class="fa fa-download"></i>Print Report
            </button>
            <button class="btn btn-primary pull-right form-control" style="margin-right: 5px;" data-toggle="modal" data-target="#add_new_atype_modal"><i class="fa fa-download"></i>Print Report</button>
        </div>-->
    </div>

</div>

<!--<div id="add_new_atype_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Preview Course Result</h4>
            </div>
            <div class="modal-body">
                <embed src="print_supp_report.php?action=getPDF&cid=<?php /*echo $courseID;*/?>&bid=<?php /*echo $batchID;*/?>&sid=<?php /*echo $semesterSettingID;*/?>"
                       frameborder="0" width="100%" height="600px">

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</div>-->

