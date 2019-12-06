<?php
session_start();
require_once '../DB.php';
$db=new DBHelper();
$output = array();
$plevel=$db->getRows("instructor_emp",array('where'=>array('status'=>1),'order by empID ASC'));
if(!empty($plevel))
{
    foreach($plevel as $pl)
    {
        $empID=$pl['empID'];
        $empType=$pl['empType'];
        //get data
        $pldata=$db->getInstructorDataByTime($empID);

        $output[] = array(
            "empType" => $empType,
            "empData" => $pldata
        );
    }
}
echo json_encode($output);
?>