<?php
session_start();
try {
include 'DB.php';
$db = new DBHelper();
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
    if($_REQUEST['action_type'] == 'addshehia')
    {
        $userData = array(
            'shehiaCode'=>$_POST['code'],
            'shehiaName'=>$_POST['name'],
            'districtCode'=>$_POST['districtCode']
        );
        $insert = $db->insert("ddx_shehia",$userData);
        $boolStatus=true;
        header("Location:index3.php?sp=administrative#shehia&msg=succ");
    }
    else if($_REQUEST['action_type'] == 'editshehia')
    {
    	$userData = array(
    			'shehiaCode'=>$_POST['code'],
    			'shehiaName'=>$_POST['name'],
    			'districtCode'=>$_POST['districtCode']
    	);
    	$conditions=array('shehiaCode'=>$_POST['shehiaCode']);
    	$update = $db->update("ddx_shehia",$userData,$conditions);    
    	header("Location:index3.php?sp=administrative#shehia&msg=edited");
    }
    else if($_REQUEST['action_type'] == 'addregion')
    {
    	$userData = array(
    			'regionCode'=>$_POST['code'],
    			'regionName'=>$_POST['name'],
    			'zoneCode'=>$_POST['zoneCode']
    	);    	
    	$insert = $db->insert("ddx_region",$userData);
    	header("Location:index3.php?sp=administrative#region&msg=succ");
    }
    else if($_REQUEST['action_type'] == 'editregion')
    {
    	
    	$userData = array(
    			'regionCode'=>$_POST['code'],
    			'regionName'=>$_POST['name'],
    			'zoneCode'=>$_POST['zoneCode']
    	);
    	$conditions=array("regionCode"=>$_POST['regionCode']);
    	$update = $db->update("ddx_region",$userData,$conditions);
    	header("Location:index3.php?sp=administrative#region&msg=edited");
    }
    else if($_REQUEST['action_type'] == 'adddistrict')
    {
    	$userData = array(
    			'districtCode'=>$_POST['code'],
    			'districtName'=>$_POST['name'],
    			'regionCode'=>$_POST['regionCode']
    	);
    	$insert = $db->insert("ddx_district",$userData);
    	header("Location:index3.php?sp=administrative#district&msg=succ");
    }
    else if($_REQUEST['action_type'] == 'editdistrict')
    {
    	$userData = array(
    			'districtCode'=>$_POST['code'],
    			'districtName'=>$_POST['name'],
    			'regionCode'=>$_POST['regionCode']
    	);
    	$conditions=array("districtCode"=>$_POST['districtCode']);
    	$update = $db->update("ddx_district",$userData,$conditions);
    	header("Location:index3.php?sp=administrative#district&msg=edited");
    }
    else if($_REQUEST['action_type'] == 'addzone')
    {
    	$userData = array(
    			'zoneCode'=>$_POST['code'],
    			'zoneName'=>$_POST['name'],
    			
    	);
    	$insert = $db->insert("ddx_zone",$userData);
    	header("Location:index3.php?sp=administrative#zone&msg=succ");
    }
}

} catch (PDOException $ex) {
 header("Location:index3.php?sp=administrative&msg=error");
 }