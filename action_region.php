<?php
session_start();
include 'DB.php';
$db=new DBHelper();
if (isset($_REQUEST['action_type'])&&!empty($_REQUEST['action_type'])) {
    
    if ($_REQUEST['action_type']=='add')
    {
       $getCode=$db->getRows("hrmx_region",array('order_by'=>'regionCode DESC','return_type'=>'single'));
       $code=$getCode['regionCode']+1;
       $name=$_POST['regionName'];
       $data=array(
           'regionCode'=>$code,
           'regionName'=>$name,
           'status'=>1
       );
       if ($db->isFieldExist("hrmx_region", "regionName", $name))
       {
           header("location:index3.php?sp=regions&msg=unsucc");
       }
       else{
           $insert=$db->insert("hrmx_region", $data);
           header("location:index3.php?sp=regions&msg=succ");
           
       }
    }
    elseif ($_REQUEST['action_type']=='edit')
    {
        $code=$_POST['regionCode'];
        $name=$_POST['regionName'];
        $data=array(
            
            'regionName'=>$name,
        );
        $condition=array('regionCode'=>$code);
        $update=$db->update("hrmx_region",$data,$condition);
        header("location:index3.php?sp=regions&msg=edited");
    }
    elseif ($_REQUEST['action_type']=='unblock')
    {
        $data=array(
            
            'status'=>1,
        );
        
        $code=$db->my_simple_crypt($_REQUEST['id'],'d');
        $condition=array('regionCode'=>$code);
        $update=$db->update("hrmx_region", $data, $condition);
        header("location:index3.php?sp=regions&msg=unblock");
    }
    elseif ($_REQUEST['action_type']=='block')
    {
        $data=array(
            
            'status'=>0,
        );
        
        $code=$db->my_simple_crypt($_REQUEST['id'],'d');
        $condition=array('regionCode'=>$code);
        $update=$db->update("hrmx_region", $data, $condition);
        header("location:index3.php?sp=regions&msg=unblock");
    }
}
?>