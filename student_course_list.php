<script type="text/javascript">
    $(document).ready(function () {
        var titleheader = $('#titleheader').text();
        $("#student_list").DataTable({
            "dom": 'Blfrtip',

            "paging":true,
            "buttons":[
                {
                    extend:'excel',
                    title: titleheader,
                    footer:false,
                    exportOptions:{
                        columns: [0, 1, 2,3,4,5]
                    }
                },
                ,
                {
                    extend:'csvHtml5',
                    title: titleheader,
                    customize: function (csv) {
                        return titleheader+"\n"+  csv +"\n";
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: titleheader,
                    footer: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3,4,5]
                    },

                }
                ,
                {
                    extend: 'copy',
                    title: titleheader,
                    footer: true,
                    exportOptions: {
                        columns: [5]
                    },

                }

            ],
            "order": []
        });
    });
</script>
<div class="container">

    <?php $db=new DBHelper();
    ?>
    <?php
    $courseID=$db->decrypt($_REQUEST['id']);
    $semesterSettingID=$db->decrypt($_REQUEST['sid']);

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
    ?>

    <div class="row">
        <div class="col-md-12">

            <h3 id="titleheader">Registered Student for <?php echo  $courseCode."-".$courseName."-". $db->getData("semester_setting","semesterName","semesterSettingID",$db->decrypt($_REQUEST['sid']));?></h3>
            <hr>
            <table  id="student_list" class="display">
                <thead>
                <tr>
                    <th width="20">No.</th>
                    <th width="170">Registration Number</th>
                    <th width="200">Name</th>
                    <th width="50">Gender</th>
                    <th>Programme Registered</th>
                    <th>Batch</th>
                    <th>Email</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $getStudentCourse=$db->getStudentCourseList($courseID,$semesterSettingID);
                if(!empty($getStudentCourse))
                {
                    $count = 0;
                    foreach($getStudentCourse as $cid)
                    {
                        $count++;
                        $regNumber=$cid['regNumber'];
                        $student = $db->getRows('student',array('where'=>array('registrationNumber'=>$regNumber),'order_by'=>'studentID ASC'));

                        if(!empty($student)){
                            foreach($student as $std){
                                $studentID=$std['studentID'];
                                $fname=$std['firstName'];
                                $mname=$std['middleName'];
                                $lname=$std['lastName'];
                                $gender=$std['gender'];
                                $dob=$std['dateOfBirth'];
                                $programmeID=$std['programmeID'];
                                $name="$fname $mname $lname";
                                $registrationNumber=$std['registrationNumber'];
                                $batchID=$std['batchID'];
                                $email=$std['email'];
                                echo "<tr><td>$count</td><td>$regNumber</td><td>$name</td><td>$gender</td><td>".$db->getData("programmes","programmeName","programmeID",$programmeID)."</td><td>".$db->getData("batch","batchName","batchID",$batchID)."</td><td>$email</td></tr>";
                            }
                        }
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3">
                <a href="index3.php?sp=course_register" class="btn btn-success form-control">Go Back</a>
        </div>
    </div>

</div>
