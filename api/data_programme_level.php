<?php
session_start();
require_once '../DB.php';
$db=new DBHelper();
$output = array();
$plevel=$db->getRows("programme_level",array('where'=>array('status'=>1),'order by programmeLevelCode ASC'));
if(!empty($plevel))
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
    foreach($plevel as $pl)
    {
        $plCode=$pl['programmeLevel'];
        $plID=$pl['programmeLevelID'];
        //get data
        $pldata=$db->getCurrentDataByProgrammeLevel($plID,$academicYearID);

        $output[] = array(
            "levelName" => $plCode,
            "levelData" => $pldata
        );
    }
}
echo json_encode($output);
?>