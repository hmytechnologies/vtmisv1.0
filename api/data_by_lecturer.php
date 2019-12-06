<?php
session_start();
require_once '../DB.php';
$db=new DBHelper();
$output = array();
$plevel=$db->getRows("instructor_title",array('where'=>array('status'=>1),'order by titleID ASC'));
if(!empty($plevel))
{
    foreach($plevel as $pl)
    {
        $titleID=$pl['titleID'];
        $title=$pl['title'];
        //get data
        $pldata=$db->getInstructorData($titleID);

        $output[] = array(
            "titleName" => $title,
            "instdata" => $pldata
        );
    }
}
echo json_encode($output);
?>