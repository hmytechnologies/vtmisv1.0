<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
try {
    include("DB.php");
    $db = new DBHelper();
    $tblRegistration='exam_number';
    if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type']))
    {
        if($_REQUEST['action_type'] == 'register')
        {            
            $semesterID=$db->decrypt($_REQUEST['sid']);
            $regno=$_REQUEST['regno'];
            $regNumber=$db->my_simple_crypt($regno,'d');
            
            $semester_setting=$db->getRows("semester_setting",array('where'=>array('semesterSettingID'=>$semesterID)));
            foreach($semester_setting as $sm)
            {
                $academicYearID=$sm['academicYearID'];
                $semester_id=$sm['semesterID'];
            }
            
            $academicYear=$db->getData("academic_year","academicYear","academicYearID",$academicYearID);
            $semester=$db->getData("semister","semisterName","semisterID",$semester_id);
            
            $year=explode("/",$academicYear);
            $year1=$year[0];
            $yearSub=substr((string)$year1,2,3);//17
            
            if($semester=="First Semester")
                $smcode=1;
            else 
                $smcode=2;
            
            $student=$db->getRows("student",array('where'=>array('registrationNumber'=>$regNumber)));
           foreach($student as $sm)
           {
               $regNumber=$sm['registrationNumber'];
               $admissionYearID=$sm['academicYearID'];
               $programmeID=$sm['programmeID'];
           }
           
           $admissionYear=$db->getData("academic_year","academicYear","academicYearID",$admissionYearID);
           
           $adyear=explode("/",$admissionYear);
           $admyear=$adyear[0];
           $adyearSub=substr((string)$admyear,2,3);//17
           
           $programmeCode=$db->getData("programmes","programmeCode","programmeID",$programmeID);
                   
                        //applicantregistration table
                        $number= $db->getMaxSerialNumber($programmeID,$semesterID);
                        if($number=="")
                        {
                            $number=0;
                        }
                        else
                        {
                            $number=$number;
                        }
                        $finalNumber=$number+1;
                        
                        
                            if($db->count_digit($finalNumber)==1)
                            {
                                $finalNumber="00".$finalNumber;
                            }
                            else if($db->count_digit($finalNumber)==2)
                            {
                                $finalNumber="0".$finalNumber;
                            }
                            else
                            {
                                $finalNumber=$finalNumber;
                            }
                       
                            $examNumber=$programmeCode."".$adyearSub."".$yearSub."".$smcode."".$finalNumber;
                        $regData=array(
                            'programmeID'=>$programmeID,
                            'semesterSettingID'=>$semesterID,
                            'regNumber'=>$regNumber,
                            'examNumber'=>$examNumber,
                            'serialNumber'=>$finalNumber
                        );
                        $insert=$db->insert($tblRegistration,$regData);
                        $boolStatus=true;           
            }
            if($boolStatus)
            {
                header("Location:index3.php");
            }
            else
            {
                header("Location:index3.php?sp=semester_registration&msg=unsucc");
            }
    }
}catch (PDOException $ex) {
    header("Location:index3.php?sp=semester_registration&msg=error");
 }
?>
 