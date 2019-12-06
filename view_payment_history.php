<?php $db=new DBHelper();
?>
    <div class="content">
        <h1>Student Payment History</h1>

                <style type="text/css">
                    .bs-example{
                        margin: 10px;
                    }
                </style>
                <div class="row">
                </div>
                <hr>
                <h3>View/Assign Course In a Semester</h3>
                <div class="row">
                    <form name="" method="post" action="">
                        <div class="col-lg-4">

                            <label for="MiddleName">Admission Year</label>
                            <select name="admissionYearID" class="form-control" required>
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
                      <!--  <div class="col-lg-4">
                            <label for="MiddleName">Programme Name</label>
                            <select name="programmeID" class="form-control" required>

                                <?php
/*                                $programmes = $db->getRows('programmes',array('order_by'=>'programmeName ASC'));
                                if(!empty($programmes)){
                                    echo"<option value=''>Please Select Here</option>";
                                    echo "<option value='all'>All Programmes</option>";
                                    $count = 0; foreach($programmes as $prog){ $count++;
                                        $programme_name=$prog['programmeName'];
                                        $programme_id=$prog['programmeID'];
                                        */?>
                                        <option value="<?php /*echo $programme_id;*/?>"><?php /*echo $programme_name;*/?></option>
                                    <?php /*}}
                                */?>
                            </select>
                        </div>-->
                        <div class="col-lg-4">
                            <label for=""></label>
                            <input type="submit" name="doFind" value="Find Records" class="btn btn-primary form-control" /></div>
                    </form>
                </div>
                <div class="row"><br></div>


                <?php
                if(!empty($_REQUEST['msg']))
                {
                    if($_REQUEST['msg']=="succ")
                    {
                        echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                <strong>Semester Course data has been inserted successfully</strong>.
            </div>";
                    }
                    else if($_REQUEST['msg']=="deleted") {
                        echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
                <strong>Semester Course Data has been delete successfully</strong>.
            </div>";
                    }
                }
                ?>
                <div class="row">
                    <?php
                    if(isset($_POST['doFind'])=="Find Records") {
                    $academicYearID = $_POST['admissionYearID'];
                    $paymentList = $db->getRows('student_payment',array('where'=>array('academicYearID'=>$academicYearID),'order_by'=>'paymentDate   ASC'));
                    if(!empty($paymentList))
                    {
                    ?>
                    <h4><span class="text-danger" id="titleheader">
                List of Payments for <?php echo $db->getData("academic_year", "academicYear", "academicYearID", $academicYearID); ?>
                </span></h4>
                    <hr>
                    <form name="register" id="register" method="post" action="action_update_student_fees.php">
                        <table  id="example" class="display nowrap">
                            <thead>
                            <tr>
                                <th>No.</th>
                                <th>Full Name</th>
                                <th>Reg.Number</th>
                                <th>Amount</th>
                                <th>Receipt Number</th>
                                <th>Payment Date</th>
                                <th>Paid-in By</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $count = 0;$total=0;
                            foreach ($paymentList as $list) {
                                $count++;
                                $regNumber=$list['regNumber'];
                                $amount=$list['amount'];
                                $total+=$amount;
                                $receiptNumber=$list['receiptNumber'];
                                $paymentDate=$list['paymentDate'];
                                $createdBy=$list['createdBy'];

                            $user_list = $db->getRows('users', array('where' => array('userID' => $createdBy), ' order_by' => 'firstName ASC'));
                            if(!empty($user_list)) {
                                foreach ($user_list as $ulst) {
                                    $fname = $ulst['firstName'];
                                    $mname = $ulst['middleName'];
                                    $lname = $ulst['lastName'];
                                    $acc_name="$fname $mname $lname";
                                }
                            }
                            else
                            {
                                $acc_name="None";
                            }

                                $student_list = $db->getRows('student', array('where' => array('registrationNumber' => $regNumber), ' order_by' => 'firstName ASC'));
                                foreach($student_list as $lst) {
                                    $fname = $lst['firstName'];
                                    $mname = $lst['middleName'];
                                    $lname = $lst['lastName'];
                                    $sponsor=$lst['sponsor'];
                                    $programmeID=$lst['programmeID'];
                                    $name = "$fname $mname $lname";
                                    ?>
                                    <tr>
                                        <td><?php echo $count; ?></td>
                                        <td><?php echo $name ?></td>
                                        <td><?php echo $list['regNumber']; ?></td>
                                        <td><?php echo number_format($amount,2);?></td>
                                        <td><?php echo $receiptNumber;?></td>
                                        <td><?php echo $paymentDate;?></td>
                                        <td><?php echo $acc_name;?></td>
                                    </tr>

                                    <?php
                                }
                            }
                            ?>

                            <?php
                            } else {
                                ?>
                                <h4><span class="text-danger">No Student(s) found......</span></h4>
                                <?php
                            }
                            ?>
                            </tbody>
                            <tfoot>
                            <th colspan="3">Total Amount</th>
                            <th colspan="4"><?php echo number_format($total,2);?></th>
                            </tfoot>
                        </table>
                        <?php
                        }
                        ?>
                </div>
            </div>
