<?php
session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'exam_number';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
   if($_REQUEST['action_type'] == 'add_register_exam') {
       $programmeLevelID=$_POST['programmeLevelID'];
       $programmeID=$_POST['programmeID'];
       $academicYearID = $_POST['academicYearID'];
        //examNumber
       $academicYear=$db->getData("academic_year","academicYear","academicYearID",$academicYearID);
       $programmeCode=$db->getData('programmes','programmeCode','programmeID',$programmeID);
       $programmeLevelCode=$db->getData('programme_level','programmeLevelCode','programmeLevelID',$programmeLevelID);
       $subYear=substr($academicYear,2);


       if (!empty($_POST['regNumber'])) {
           foreach ($_POST['regNumber'] as $rgNo) {

               $serialNumber = $db->getMaxRegNumber($programmeID);
               $finalNumber = $serialNumber + 1;

               if($db->count_digit($finalNumber)>=3)
               {
                   $finalNumber=$finalNumber;
               }
               else if($db->count_digit($finalNumber)>=2)
               {
                   $finalNumber="0".$finalNumber;
               }
               else if($db->count_digit($finalNumber)>=1)
               {
                   $finalNumber="00".$finalNumber;
               }

               $exam_number = "VTC".$programmeLevelCode."/".$programmeCode."/".$subYear."/".$finalNumber;


               $userData = array(
                   'regNumber' => $rgNo,
                   'examNumber' => $exam_number,
                   'academicYearID' => $academicYearID,
                   'programmeID'=>$programmeID,
                   'serialNumber'=>$finalNumber
               );
               $examNumber = $db->getRows("exam_number", array('where' => array('regNumber' => $rgNo, 'academicYearID' => $academicYearID)));
               if (!empty($examNumber)) {
                   $condition = array('regNumber' => $rgNo, 'academicYearID' => $academicYearID);
                   $insert = $db->update($tblName, $userData, $condition);
               } else {
                   $insert = $db->insert($tblName, $userData);
               }
           }
           header("Location:index3.php?sp=register_exam");
       }
   }
}