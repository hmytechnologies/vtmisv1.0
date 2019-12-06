<?php
session_start();
require_once '../DB.php';
$db=new DBHelper();
$output = array();
$programmes=$db->getRows("programme_major",array('where'=>array('programmeStatus'=>1),'order_by programmeLevelID DESC'));
if(!empty($programmes))
{
    $today=date("Y-m-d");
    $sm=$db->readSemesterSetting($today);
    foreach ($sm as $s)
    {
        $semisterID=$s['semesterID'];
        $academicYearID=$s['academicYearID'];
        $semesterName=$s['semesterName'];
        $semesterSettingID=$s['semesterSettingID'];
        $endDateRegistration=$s['endDateRegistration'];
    }
    foreach($programmes as $pg)
    {
        $pgCode=$pg['programmeMajorCode'];
        $programmeID=$pg['programmeMajorID'];
        //get data
        $maleData=$db->getCurrentDataByProgramme($programmeID,$academicYearID,"M");
        $femaleData=$db->getCurrentDataByProgramme($programmeID,$academicYearID,"F");

        $output[] = array(
            "programmeCode" => $pgCode,
            "maleData" => $maleData,
            "femaleData"=>$femaleData
        );
    }
}
echo json_encode($output);
?>