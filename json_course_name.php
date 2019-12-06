<?php
include("DB.php");
$db=new DBHelper();
$courseID=$_POST['courseID'];
if($courseID)
{
    $course = $db->getRows('course',array('where'=>array('courseID'=>$courseID),'order_by'=>'courseName ASC'));
    if(!empty($course)){
        foreach($course as $c)
        {
            $courseTypeID=$c['courseTypeID'];
            $units=$c['units'];
            $courseType=$db->getData("course_type","courseType","courseTypeID",$courseTypeID);

            $courseStatus = $db->getRows('programmemaping',array('where'=>array('courseID'=>$courseID),' order_by'=>' courseID ASC'));
            foreach($courseStatus as $cs)
            {
                $courseStatusID=$cs['courseStatusID'];
                $courseStatusName=$db->getData("coursestatus","courseStatus","courseStatusID",$courseStatusID);
            }

            $course_arr[] = array("courseType" => $courseType, "units" => $units,"courseStatusID"=>$courseStatusID,"courseStatus"=>$courseStatusName);
        }

    }
}
echo json_encode($course_arr);
?>