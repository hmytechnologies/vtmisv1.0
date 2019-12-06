<script src="http://code.jquery.com/jquery-1.4.min.js" type="text/javascript"></script>
<script type="text/javascript">
		$(document).ready(function(){
		   $("#page1").click(function(){
		   	$('#output').load('semester_date_setting.php');
		     //alert("Thanks for visiting!");
		   }); 
 
		   $("#page2").click(function(){
		   	$('#result').load('pages/page2.html');
		     //alert("Thanks for visiting!");
		   });
		 });
	</script>
<div class=" container">

<div class="row"> 
<div class="col-md-12">
<h2 class="text-info" style="font-family:segoe UI;">Annual Settings</h2>
</div>
</div>
<div class="row">
        <div class="col-md-12">
            <hr style="border-color:#6599ff;">
            <p style="font-family:segoe UI;font-size:18px;"> Configure basic information for annual operation(Academic Calendar)</p><br/>
            </div>
            </div>
   <div class="row">
<div class="col-sm-6">         
<div class="row">
<div class="col-md-12"><a href="index3.php?sp=semester_date_setting" class="button">
 <div class="info-box " style="background-color: #ba55b4;color:white;">
  <span class="info-box-icon"><i class="fa fa-calendar"></i></span>
  <div class="info-box-content">
    <span class="info-box-text" style="font-family: segoe ui;">Academic Dates Settings
    </span>
    <!-- <span class="info-box-number">41,410</span> -->
    <!-- The progress section is optional -->
   <div class="progress">
      <div class="progress-bar" style="width: 100%"></div>
    </div>
    <span class="progress-description">
     Set the terms start and finish dates
    </span>
  </div>
  <!-- /.info-box-content -->
</div></a>
<!-- /.info-box -->
</div>

</div>


<!--    <div class="row">
        <div class="col-md-12"><a href="index3.php?sp=semester_course">
                <div class="info-box " style="background-color: #0b8eff;color:white;">
                    <span class="info-box-icon"><i class="fa fa-check-square-o"></i></span>
                    <div class="info-box-content">
    <span class="info-box-text" style="font-family: segoe ui;">Annual Course Assignment
    </span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">
     Assign course to be taught in this year<br/> for each study trade
    </span>
                    </div>

                </div></a>

        </div>

    </div>-->


    <div class="row">
        <div class="col-md-12"><a  href="index3.php?sp=instructor_course">
                <div class="info-box " style="background-color: #808000;color:white;">
                    <span class="info-box-icon"><i class="fa fa-user"></i></span>
                    <div class="info-box-content">
    <span class="info-box-text" style="font-family: segoe ui;">Staff Course Allocation
    </span>
                        <!-- <span class="info-box-number">41,410</span> -->
                        <!-- The progress section is optional -->
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">
    Assign staff to courses taught in this year
    </span>
                    </div>
                    <!-- /.info-box-content -->
                </div></a>
            <!-- /.info-box -->
        </div>

    </div>

    <div class="row">
        <div class="col-md-12"><a href="index3.php?sp=instructor_course_setting">
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-bar-chart"></i></span>
                    <div class="info-box-content">
    <span class="info-box-text" style="font-family: segoe ui;">Instructor Workload
    </span>
                        <!-- <span class="info-box-number">41,410</span> -->
                        <!-- The progress section is optional -->
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">
    View teaching workload for all staff in this department.
    </span>
                    </div>
                    <!-- /.info-box-content -->
                </div></a>
            <!-- /.info-box -->
        </div>

    </div>


<!--<div class="row">
<div class="col-md-12"><a href="index3.php?sp=exam_setting">
 <div class="info-box " style="background-color: #808000;color:white;">
  <span class="info-box-icon"><i class="fa fa-calendar"></i></span>
  <div class="info-box-content">
    <span class="info-box-text" style="font-family: segoe ui;">Exam Date Settings
    </span>
   <div class="progress">
      <div class="progress-bar" style="width: 100%"></div>
    </div>
    <span class="progress-description">
    Set the exam start and finish dates for this semester
    </span>
  </div>
</div></a>
</div>

</div>-->

<!--<div class="row">
<div class="col-md-12"><a href="index3.php?sp=instructor_course_setting">
 <div class="info-box bg-yellow">
  <span class="info-box-icon"><i class="fa fa-bar-chart"></i></span>
  <div class="info-box-content">
    <span class="info-box-text" style="font-family: segoe ui;">Instructor Course Allocation
    </span>

   <div class="progress">
      <div class="progress-bar" style="width: 100%"></div>
    </div>
    <span class="progress-description">
    View teaching workload for all staff in all departments.
    </span>
  </div>

</div></a>
</div>

</div>-->


   <!-- <div class="row">
        <div class="col-md-12"><a href="#">
                <div class="info-box bg-yellow">
                    <span class="info-box-icon"><i class="fa fa-bar-chart"></i></span>
                    <div class="info-box-content">
    <span class="info-box-text" style="font-family: segoe ui;">Other Setting
    </span>
                        <div class="progress">
                            <div class="progress-bar" style="width: 100%"></div>
                        </div>
                        <span class="progress-description">Set the minimum attendance requirements for each course

    </span>
                    </div>
                </div></a>
        </div>

    </div>-->


</div>

<div class="col-sm-6">
<div class="jumbotron bg-blue" style="background-color: grey;">
<div class="row">
	<div class="col-sm-2"><br/>
		<span class="fa fa-info-circle fa-4x" ></span>
	</div>
	<div class="col-sm-10">
		<h2 style="font-family:segoe ui;font-size:42px;">Important</h2>
		
	</div>
</div><hr/>
<div class="row">
	<div class="col-sm-12">
		<p style="font-size:17px;">For each semester, the system needs to understand when classes and exams start and end. You must set these dates for the system to be operational.</p>
	</div>
</div>
</div>
</div>
</div>


<div>
</div>
<div id="output">

</div>

	