<script type="text/javascript" src="plugins/jQuery/jQuery-2.1.4.min.js"></script>

<script type="text/javascript" src="js/jquery.min.js"></script>
<script src="js/jquery-1.4.2.min.js"></script>
 <script type="text/javascript">
$(document).ready(function () {
    $("#studentdata").DataTable({
        "ajax": "api/studentdata.php",
        "dom": 'Blfrtip',
        "scrollX": true,
        "paging": true,
        "buttons": [
            {
                extend: 'excel',
                title: 'List of all Student',
                footer: false,
                exportOptions: {
                    columns: [0, 1, 2, 3, 5, 6, 7]
                }
            },
            {
                extend: 'print',
                title: 'List of all Student',
                footer: false,
                exportOptions: {
                    columns: [0, 1, 2, 3, 5, 6, 7]
                }
            },
            {
                extend: 'pdfHtml5',
                title: 'List of all Student',
                footer: true,
                exportOptions: {
                    columns: [0, 1, 2, 3, 5, 6, 7]
                }
            }
        ],
        "order": []
    });
});

</script>

<?php $db=new DBHelper();?>
<div class="container">
  <div class="content">
        <h2>Student Registration</h2>
        <hr>
<div class="row">
        
<div class="col-lg-12">       
<?php 
if(!empty($_REQUEST['msg']))
{
  if($_REQUEST['msg']=="succ")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Student data has been inserted successfully</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="unsucc")
  {
    echo "<div class='alert alert-danger fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Student with this Registration Number/Email already exist!</strong>.
</div>";
  }else if($_REQUEST['msg']=="error")
  {
    echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Sorry, Please Contact System Administrator</strong>.
</div>";
  }
  else if($_REQUEST['msg']=="edit")
  {
      echo "<div class='alert alert-success fade in'><a href='#' class='close' data-dismiss='alert'>&times;</a>
    <strong>Student data has been edited successfully</strong>.
</div>";
  }
}
?> 
</div> 
     
    </div>

      <div class="row">
<div class="col-md-12">
<div class="pull-right">


<a href='index3.php?sp=new_student'><span class="btn btn-success">Register New Student</span></a>

</div>
 </div>
            <?php
            $academicYearID=$db->getCurrentAcademicYear();
            ?>
 <!--<h3>List of Registered Student for the Academic Year <?php /*echo $db->getData("academic_year","academicYear","academicYearID",$academicYearID); */?></h3>-->
            <h3>List of Registered Student in <?php echo $db->getData('academic_year','academicYear','academicYearID',$academicYearID);?></h3>
 <br><br>
</div>
            <div class="row">
 <div class="col-md-12">   
<table  id="studentdata" class="display">
  <thead>
  <tr>
    <th>No.</th>
    <th>Name</th>
    <th>Gender</th>
    <th>Date of Birth</th>
    <th>Registration Number</th>
      <th>Center Name</th>
      <th>Trade Level</th>
    <th>Trade Name</th>
    <th>Edit</th>
    <th>View</th>
     </tr>
  </thead>
 </table>
 </div></div>


    </div>
            </div>