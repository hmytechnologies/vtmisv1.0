<?php
include("DB.php");
$db=new DBHelper();
$academicYearID=$_POST['academicYearID'];
// $semisterID=$_POST['semisterID'];

if($academicYearID)
{
 //$course = $db->getRows('student_course',array('where'=>array('academic_year_id'=>$academicYearID,'semister_id'=>$semisterID),'order_by'=>'course_id DESC'));
  $course = $db->getDistinctCourse1($academicYearID);

 if(!empty($course))
 {
  echo"<option value=''>Please Select Here</option>";
     $i = 0; 
     foreach($course as $c)
     { 
          $i++;
          $courseID=$c['course_id'];
         $courseName = $db->getRows('course',array('where'=>array('courseID'=>$courseID),'order_by'=>'courseName DESC'));
         if(!empty($courseName))
         {
           $count = 0; 
           foreach($courseName as $course)
           {
            $count++;
            $courseID=$course['courseID'];
            $cname=$course['courseName'];
            $courseCode=$course['courseCode'];
        	  echo "<option value='$courseID'>$courseCode-$cname</option>";
                                         
           }
         }
      }
}
else
{
  echo "No Course Found";
}
}
?>