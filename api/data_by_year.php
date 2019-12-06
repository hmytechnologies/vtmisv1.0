<?php
session_start();
require_once '../DB.php';
$db=new DBHelper();
$output = array();
$academic_year=$db->getRows("academic_year",array('order_by academicYearID ASC'));
if(!empty($academic_year))
{
    foreach($academic_year as $ac)
    {
        $acYear=$ac['academicYear'];
        $acYearID=$ac['academicYearID'];
        //get data
        $maleData=$db->getDataByAcademicYear($acYearID,"M");
        $femaleData=$db->getDataByAcademicYear($acYearID,"F");

        $output[] = array(
            "academicYear" => $acYear,
            "maleData" => $maleData,
            "femaleData"=>$femaleData
        );
    }
}
echo json_encode($output);
?>