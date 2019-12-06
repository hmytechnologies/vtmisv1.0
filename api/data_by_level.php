<?php
session_start();
require_once '../DB.php';
$db=new DBHelper();
$output = array();
$plevel=$db->getRows("programme_level",array('where'=>array('status'=>1),'order by programmeLevelCode ASC'));
if(!empty($plevel))
{
    foreach($plevel as $pl)
    {
        $plCode=$pl['programmeLevelCode'];
        $plID=$pl['programmeLevelID'];
        $plName=$pl['programmeLevel'];

        //get data
        $maleData=$db->getDataByLevel($plID,"M");
        $femaleData=$db->getDataByLevel($plID,"F");

        $output[] = array(
            "plCode" => $plCode,
            "maleData" => $maleData,
            "femaleData"=>$femaleData
        );
    }
}
echo json_encode($output);
?>