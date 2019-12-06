<?php $db=new DBHelper();
?>
<div class="container">
    <div class="content">
        <h1>Exam Register Management</h1>
        <hr>
        <div class="tab-content">
            <!-- Current Semester -->
            <div id="currentdata" class="tab-pane fade in active">

                <script type="text/javascript" src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
                <script type="text/javascript">
                    $(document).ready(function()
                    {
                        $("#programmeLevelID").change(function()
                        {
                            var programmeLevelID=$(this).val();
                            var centerID=$("#centerID").val();
                            var dataString = 'programmeLevelID='+programmeLevelID+'&centerID='+centerID;
                            $.ajax
                            ({
                                type: "POST",
                                url: "ajax_programme.php",
                                data: dataString,
                                cache: false,
                                success: function(html)
                                {
                                    $("#programmeID").html(html);
                                    console.log(dataString);
                                }
                            });

                        });

                    });
                </script>
                <script type="text/javascript">
                    $(document).ready(function() {
                        $('#selecctall').click(function(event) {  //on click
                            if(this.checked) { // check select status
                                $('.checkbox1').each(function() { //loop through each checkbox
                                    this.checked = true;  //select all checkboxes with class "checkbox1"
                                });
                            }else{
                                $('.checkbox1').each(function() { //loop through each checkbox
                                    this.checked = false; //deselect all checkboxes with class "checkbox1"
                                });
                            }
                        });

                    });
                </script>

                <style type="text/css">
                    .bs-example{
                        margin: 10px;
                    }
                </style>
                <h3>Register Exam</h3>
                <form name="" method="post" action="">
                    <div class="row">

                        <?php
                        if($_SESSION['main_role_session']==7)
                        {
                            $centerID='all';
                        }
                        else
                        {
                            $centerID=$_SESSION['department_session'];
                        }

                        ?>
                        <input type="text" hidden id="centerID" value="<?php echo $centerID;?>">

                        <div class="col-lg-3">
                            <label for="Physical Address">Trade Level</label>
                            <select name="programmeLevelID" id="programmeLevelID"  class="form-control" required>

                                    <option value="">Select Here</option>
                                    <?php
                                $level = $db->getRows('programme_level',array('order_by'=>'programmeLevelCode ASC'));
                                if(!empty($level)){

                                    $count = 0; foreach($level as $lvl){ $count++;
                                        $programmeLevelID=$lvl['programmeLevelID'];
                                        $programmeLevel=$lvl['programmeLevel'];
                                        ?>
                                        <option value="<?php echo $programmeLevelID;?>"><?php echo $programmeLevel;?></option>
                                    <?php }}?>
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <label for="Physical Address">Trade Name</label>
                            <select name="programmeID" id="programmeID"  class="form-control" required>
                                <option value="">Select Here</option>
                            </select>
                        </div>

                        <div class="col-lg-3">
                            <label for="LastName">Academic Year</label>
                            <select name="academicYearID" id="academicYearID"  class="form-control" required>
                                <?php
                                /*$academicYear = $db->getRows('academic_year',array('where'=>array('status'=>1),'order_by'=>'academicYear ASC'));*/
                                $academicYear = $db->getRows('academic_year',array('order_by'=>'academicYear ASC'));
                                if(!empty($academicYear)){
                                    $count = 0;
                                    foreach($academicYear as $yr){ $count++;
                                        $academicYearID=$yr['academicYearID'];
                                        $academicYear=$yr['academicYear'];
                                        ?>
                                        <option value="<?php echo $academicYearID;?>"><?php echo $academicYear;?></option>
                                    <?php }
                                }?>
                            </select>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-9"></div>
                        <div class="col-lg-3">
                            <label for=""></label>
                            <input type="submit" name="doFind" value="Find Records" class="btn btn-primary form-control" /></div>
                    </div>
                </form>

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
                    if(isset($_POST['doFind'])=="Find Records")
                    {
                        $programmeLevelID=$_POST['programmeLevelID'];
                        $programmeID=$_POST['programmeID'];
                        $academicYearID=$_POST['academicYearID'];

                            ?>
                            <div class="row">
                                <h4 class="text-danger" id="titleheader">
                                    List of Registered Student for <?php echo $db->getData("programme_level","programmeLevel","programmeLevelID",$programmeLevelID);?>
                                    <?php echo $db->getData("programmes","programmeName","programmeID",$programmeID);?>
                                    - <?php echo $db->getData("academic_year","academicYear","academicYearID",$academicYearID);?>
                                </h4>
                            </div>
                            <form name="" method="post" action="action_update_exam_list.php">
                                <?php
                                $student = $db->getStudentExamNumber($programmeLevelID,$programmeID,$academicYearID);

                                if(!empty($student))
                                {
                                ?>
                                <hr>
                                <table  id="onlydata" border=1 class="table table-striped table-bordered table-condensed">
                                    <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th><input type="checkbox" id="selecctall"/></th>
                                        <th>Full Name</th>
                                        <th>Reg.Number</th>
                                        <th>Exam Number</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $count = 0;
                                    foreach($student as $st)
                                    {
                                        $count++;
                                        $studentID=$st['studentID'];
                                        $fname=$st['firstName'];
                                        $mname=$st['middleName'];
                                        $lname=$st['lastName'];
                                        $name="$fname $mname $lname";
                                        $regNumber=$st['registrationNumber'];
                                        $examNumber=$db->getRows("exam_number",array('where'=>array('regNumber'=>$regNumber,'academicYearID'=>$academicYearID)));
                                        if(!empty($examNumber))
                                        {
                                            foreach($examNumber as $exam)
                                            {
                                                $exam_number=$exam['examNumber'];
                                            }
                                        }
                                    else
                                    {
                                        $exam_number="None";
                                    }
                                        ?>
                                        <tr>
                                            <td><?php echo $count; ?></td>
                                            <td>
                                                <?php
                                                if(!empty($examNumber))
                                                {
                                                    ?>
                                                    No
                                                    <?php
                                                }
                                                else {
                                                    ?>
                                                    <input class="checkbox1" type="checkbox" name="regNumber[]"
                                                           value="<?php echo $regNumber; ?>">
                                                    <?php
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $name ?></td>
                                            <td><?php echo $regNumber; ?></td>
                                            <td><?php echo $exam_number;?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>

                                    <?php
                                    }
                                    else
                                    {
                                        ?>
                                        <h4><span class="text-danger">No Student(s) found......<?php echo $academicYearID;?></span> </h4>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>

                                <br />
                                <div class="row">
                                    <div class="col-lg-6"></div>
                                    <div class="col-lg-3">
                                        <input type="hidden" name="action_type" value="add_register_exam"/>
                                        <input type="hidden" name="number_subject" value="<?php echo $count;?>">
                                        <input type="hidden" name="programmeLevelID" value="<?php echo $programmeLevelID;?>">
                                        <input type="hidden" name="programmeID" value="<?php echo $programmeID;?>">
                                        <input type="hidden" name="academicYearID" value="<?php echo $academicYearID;?>">
                                        <input type="submit" name="doSubmit" value="Generate Numbers" class="btn btn-success form-control"/>
                                    </div>
                                    <div class="col-lg-3">
                                        <input type="reset" value="Cancel" class="btn btn-danger form-control" />
                                    </div>
                                </div>
                            </form>
                        <?php
                    }
                    ?>
                </div>
            </div>
            <!-- End of Current Semester -->
        </div>

    </div></div>