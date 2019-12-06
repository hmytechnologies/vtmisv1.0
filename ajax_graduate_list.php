<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#onlydata').DataTable(
            {
                paging: false,
                dom: 'Blfrtip'
            });
    });
</script>
<?php
include ('DB.php');
$db=new DBHelper();
$academicYearID=$_POST['academicYearID'];
$programmeID=$_POST['programmeID'];
$batchID=$_POST['batchID'];
$duration=$db->getData("programmes","programmeDuration","programmeID",$programmeID);
$student=$db->graduateList($programmeID,$duration,$academicYearID,$batchID);
if(!empty($student))
{
    ?>
    <div class="row">
        <h4><span class="text-danger" id="titleheader">
                List of Graduated Student in <?php echo $db->getData("programmes","programmeName","programmeID",$programmeID);?> for the year
                    <?php echo $db->getData("academic_year","academicYear","academicYearID",$academicYearID);?>
                </span></h4>
        <hr>
            <table id="onlydata" class="table table-bordered table-responsive-xl table-hover display">
                <thead>
                <tr>
                    <th>No.</th>
                    <th>Full Name</th>
                    <th>Gender</th>
                    <th>Reg.Number</th>
                    <th>GPA</th>
                    <th>Graduation Date</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $count = 0;
                foreach($student as $st)
                {
                    $count++;
                    $regNumber=$st['registrationNumber'];
                    $fname=$st['firstName'];
                    $mname=$st['middleName'];
                    $lname=$st['lastName'];
                    $name="$fname $mname $lname";
                    ?>
                        <td><?php echo $count;?></td>
                        <td><?php echo $name; ?></td>
                        <td><?php echo $st['gender']; ?></td>
                        <td><?php echo $st['registrationNumber']; ?></td>
                        <td><?php echo $st['gpa'];?></td>
                        <td><?php echo $st['gdate'];?></td>
                        </td>
                    </tr>
                    <?php
                }
                ?>

                </tbody>
            </table>
    </div>

    <?php
}
else
{
    ?>
    <h4><span class="text-danger">No Student(s) found graduation list....</span> </h4>
    <?php
}
?>