<?php
/**
 * Created by PhpStorm.
 * User: massoudhamad
 * Date: 11/3/18
 * Time: 6:46 PM
 */
?>
<script type="text/javascript">
    $(document).ready(function () {
        var sorted=$("#sorted").val();
        $('#courseworklist').dataTable(
            {
                paging: true,
                dom: 'Blfrtip',
                columnDefs: [{
                    orderData: [4, sorted],
                    targets: [4]
                }],
                "order": [[4, "desc" ]],
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
                        orientation: 'landscape',
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
                        <input type="hidden" id="sorted" value="4">
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
            $student = $db->getStudentExamResult($courseID,$semesterSettingID,$batchID);
            if(!empty($student))
            {
                ?>
                <table  id="courseworklist" class="display nowrap">
                    <thead>
                    <tr>
                        <th>No.</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Reg.Number</th>
                        <th>Course Work Marks</th>
                        <th>Remarks</th>
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

                            echo "<tr><td>$count</td><td>$name</td><td>$gender</td><td>$regNumber</td>";
                            $cwk=$db->decrypt($db->getGrade($semesterSettingID,$courseID,$regNumber,1));
                            echo "<td>".$cwk."</td>";
                            if($db->courseWorkRemarks($cwk)=="Fail")
                                $remarks="<span class='text-danger'>Fail</span>";
                            else
                                $remarks="<span class='text-success'>Pass</span>";
                            echo "<td>".$remarks."</td>";
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
    </div>

</div>
