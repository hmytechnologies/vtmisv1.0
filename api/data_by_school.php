<?php
session_start();
require_once '../DB.php';
$db=new DBHelper();
$output = array();
$center=$db->getRows("center_registration",array('order_by centerCode ASC'));
if(!empty($center))
{
    foreach($center as $sc)
    {
        $centerID=$sc['centerRegistrationID'];
        $centerCode=$sc['centerCode'];
        $centerName=$sc['centerName'];
        //get data
        $maleData=$db->getSchoolCount($centerID,"M");
        $femaleData=$db->getSchoolCount($centerID,"F");

        $output[] = array(
            "schoolCode" => $centerCode,
            "maleData" => $maleData,
            "femaleData"=>$femaleData
        );
    }
}
echo json_encode($output);
?>