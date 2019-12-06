<?php
session_start();
require_once '../DB.php';
$db=new DBHelper();
$output = array();
$plevel=$db->getRows("sponsor_type",array('where'=>array('status'=>1),'order by sponsorTypeID ASC'));
if(!empty($plevel))
{
    foreach($plevel as $pl)
    {
        $spnID=$pl['sponsorTypeID'];
        $spnName=$pl['sponsorCode'];
        //get data
        $pldata=$db->getSponsorData($spnID);

        $output[] = array(
            "spnName" => $spnName,
            "spnData" => $pldata
        );
    }
}
echo json_encode($output);
?>