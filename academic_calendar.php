<?php $db=new DBHelper();
?>
<script src="plugins/knob/jquery.knob.min.js" type="text/javascript"></script>
<div class="row">
    <div class="col-lg-12">
        <h1>Academic Calendar</h1>
    </div></div>

<div class="row">
    <form name="" method="post" action="">
        <div class="col-lg-12">
            <div class="row">

                <div class="col-lg-3">
                    <label for="FirstName">Academic Year</label>
                    <select name="academicYearID" id="academicYearID" class="form-control" required>
                        <?php
                        $academic_year = $db->getRows('academic_year',array('order_by'=>'academicYear ASC'));
                        if(!empty($academic_year)){
                            echo"<option value=''>Please Select Here</option>";
                            $count = 0; foreach($academic_year as $sm){ $count++;
                                $academicYear=$sm['academicYear'];
                                $academicYearID=$sm['academicYearID'];
                                ?>
                                <option value="<?php echo $academicYearID;?>"><?php echo $academicYear;?></option>
                            <?php }
                        }
                        ?>
                    </select>
                </div>



            </div>
            <div class="row">

                <div class="col-lg-3">
                    <label for=""></label>
                    <input type="submit" name="doFind" value="View Records" class="btn btn-primary form-control" /></div>
                <div class="col-lg-6"></div>

            </div>
        </div>
    </form>
</div>
<br><br>
<div class="row">
    <?php
    if(isset($_POST['doFind'])=="View Records") {
        $academicYearID = $_POST['academicYearID'];
        $semester = $db->getRows("semester_setting",array('where'=>array('academicYearID'=>$academicYearID)));
        if (!empty($semester)) {
            ?>
            <div class="col-md-12">
                <div class="box box-solid box-primary">
                    <div class="box-header with-border text-center">
                        <h3 class="box-title">Academic Calendar
                            for <?php echo $db->getData("academic_year","academicYear","academicYearID",$academicYearID);?></h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="" class="table table-striped table-bordered table-condensed">
                            <thead>

                            </thead>
                            <tbody>
                            <?php
                            foreach ($semester as $sm) {
                                // $semisterID=$sm['semesterID'];
                                $academicYearID=$sm['academicYearID'];

                                // $startDate=$sm['startDate'];
                                // $endDate=$sm['endDate'];

                                $semesterStatus=$sm['semesterStatus'];

                                $semesterSettingID=$sm['semesterSettingID'];

                                // $semesterName=$sm['semesterName'];

                                // $endDateRegistration=$sm['endDateRegistration'];
                                $examStartDate=$sm['examStartDate'];
                                $examEndDate=$sm['examEndDate'];
                                // $endDatefinalExam=$sm['endDateFinalExam'];

                               
                                if($semesterStatus==1)
                                    $status="Active";
                                else
                                    $status="Not Active";

                                // $semister = $db->getRows('semister',array('where'=>array('semisterID'=>$semisterID),' order_by'=>' semisterName ASC'));
                                // foreach($semister as $sm)
                                // {
                                //     $semister_name=$sm['semisterName'];
                                // }
                                $academicYear = $db->getRows('academic_year',array('where'=>array('academicYearID'=>$academicYearID),' order_by'=>' academicYear ASC'));
                                foreach($academicYear as $acy)
                                {

                                    $academicYear=$acy['academicYear'];
                                }


                            ?>
                                <tr>
                                    <th>Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Number of Days</th>
                                    <th>Progress</th>
                                    <th>Status</th>
                                </tr>

                                <tr>
                                    <td>Instructional Days</td>
                                    <td><?php echo date('d-m-Y',strtotime($startDate));?></td>
                                    <td><?php echo date('d-m-Y',strtotime($endDate));?></td>
                                    <td><?php echo $db->dateDiff($startDate,$endDate);?></td>
                                    <td>

                                        <div class="progress progress-xs">
                                            <div class="progress-bar progress-bar-success" style="width: <?php echo 50;?>%"></div>
                                        </div>

                                    </td>
                                    <td>
                                        <span class="badge bg-success"><?php echo "50";?>(<?php echo number_format(50);?>%)</span>
                                    </td>
                                </tr>


                                <tr>
                                    <td>Exam Dates</td>
                                    <td><?php echo date('d-m-Y',strtotime($examStartDate));?></td>
                                    <td><?php echo date('d-m-Y',strtotime($examEndDate));?></td>
                                    <td><?php echo $db->dateDiff($examStartDate,$examEndDate);?></td>
                                    <td>

                                        <div class="progress progress-xs">
                                            <div class="progress-bar progress-bar-success" style="width: <?php echo 50;?>%"></div>
                                        </div>

                                    </td>
                                    <td>
                                        <span class="badge bg-success"><?php echo "50";?>(<?php echo number_format(50);?>%)</span>
                                    </td>
                                </tr>


                                <tr>
                                    <td>Course Work Marks Uploading</td>
                                    <td><?php echo date('d-m-Y',strtotime($startDate));?></td>
                                    <td><?php echo date('d-m-Y',strtotime($examStartDate));?></td>
                                    <td><?php echo $db->dateDiff($startDate,$examStartDate);?></td>
                                    <td>

                                        <div class="progress progress-xs">
                                            <div class="progress-bar progress-bar-success" style="width: <?php echo 50;?>%"></div>
                                        </div>

                                    </td>
                                    <td>
                                        <span class="badge bg-success"><?php echo "50";?>(<?php echo number_format(50);?>%)</span>
                                    </td>
                                </tr>


                                <tr>
                                    <td>Final Exam Marks Uploading</td>
                                    <td><?php echo date('d-m-Y',strtotime($examStartDate));?></td>
                                    <td><?php echo date('d-m-Y',strtotime($endDatefinalExam));?></td>
                                    <td><?php echo $db->dateDiff($examStartDate,$endDatefinalExam);?></td>
                                    <td>

                                        <div class="progress progress-xs">
                                            <div class="progress-bar progress-bar-success" style="width: <?php echo 50;?>%"></div>
                                        </div>

                                    </td>
                                    <td>
                                        <span class="badge bg-success"><?php echo "50";?>(<?php echo number_format(50);?>%)</span>
                                    </td>
                                </tr>


                                <tr>
                                    <td>Semester Course Registration</td>
                                    <td><?php echo date('d-m-Y',strtotime($examStartDate));?></td>
                                    <td><?php echo date('d-m-Y',strtotime($endDatefinalExam));?></td>
                                    <td><?php echo $db->dateDiff($examStartDate,$endDatefinalExam);?></td>
                                    <td>

                                        <div class="progress progress-xs">
                                            <div class="progress-bar progress-bar-success" style="width: <?php echo 50;?>%"></div>
                                        </div>

                                    </td>
                                    <td>
                                        <span class="badge bg-success"><?php echo "50";?>(<?php echo number_format(50);?>%)</span>
                                    </td>
                                </tr>


                                <tr>
                                    <td>Fees Payment Dates</td>
                                    <td><?php echo date('d-m-Y',strtotime($examStartDate));?></td>
                                    <td><?php echo date('d-m-Y',strtotime($endDatefinalExam));?></td>
                                    <td><?php echo $db->dateDiff($examStartDate,$endDatefinalExam);?></td>
                                    <td>

                                        <div class="progress progress-xs">
                                            <div class="progress-bar progress-bar-success" style="width: <?php echo 50;?>%"></div>
                                        </div>

                                    </td>
                                    <td>
                                        <span class="badge bg-success"><?php echo "50";?>(<?php echo number_format(50);?>%)</span>
                                    </td>
                                </tr>

                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php
        } else {
            ?>
            <h4 class="text-danger">No Course Found</h4>
            <?php
        }
    }
    ?>
</div>
