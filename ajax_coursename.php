<?php
include("DB.php");
$db=new DBHelper();
$academicYearID=$_POST['academicYearID'];
$semisterID=$_POST['semisterID'];

if($academicYearID)
{
 $course = $db->getRows('courseprogramme',array('where'=>array('academicYearID'=>$academicYearID,'semesterID'=>$semisterID),'order_by'=>'courseID DESC'));
 if(!empty($course))
 {
  echo"<option value=''>Please Select Here</option>";
     $i = 0; 
     foreach($course as $c)
     { 
          $i++;
          $courseID=$c['courseID'];
         $courseName = $db->getRows('course',array('where'=>array('course_id'=>$courseID),'order_by'=>'course_name DESC'));
         if(!empty($courseName))
         {
           
           $count = 0; 
           foreach($courseName as $course)
           {
            $count++;
            $courseID=$course['course_id'];
            $cname=$course['course_name'];
        	  echo "<option value='$courseID'>$cname</option>";
                                         
           }
         }
      }
}
}
?>