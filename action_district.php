<?php
session_start();
include 'DB.php';
$db=new DBHelper();
$table="hrmx_district";
//try{
if(isset($_REQUEST['action_type']) && (!empty($_REQUEST['action_type'])))
{
	if($_REQUEST['action_type']=='add')
	
	{
		
		
		$row=$db->getRows($table,array('order_by'=>'districtCode DESC','return_type'=>'single'));
		
		$code=$row['districtCode']+1;
		
		
		$data = array(
					'districtCode'=>$code,
        			'districtName' => $_POST['districtName'],
        			'regionCode' => $_POST['regionCode'],
					'status'=>1
        	);
		if($db->isFieldExist($table,'districtName',$_POST['districtName']))
		{
			$boolStatus=false;
		}
		else
		{
			$insert = $db->insert($table,$data);
		
			$boolStatus=true;
		}
		if($boolStatus)
		{
			header("Location:index3.php?sp=districts&msg=succ");
		}
		else
		{
			header("Location:index3.php?sp=districts&msg=unsucc");
		}
		//}
		
		 
   
	}
	else if($_REQUEST['action_type']=="edit")
	{
	
	
		$data = array(
	
				'districtName' => $_POST['districtName'],
        		'regionCode' => $_POST['regionCode'],
	
		);
		$conditions=array('districtCode'=>$_POST['districtCode']);
		$update=$db->update($table, $data, $conditions);
		header("location:index3.php?sp=districts&msg=edited");
	
	}
	else if($_REQUEST['action_type']=="block")
	{
		$data = array(
	
				'status'=>0
	
		);
		$code=$db->my_simple_crypt($_REQUEST['id'],'d');
		
		$conditions=array('districtCode'=>$code);
		$update=$db->update($table, $data, $conditions);
		header("location:index3.php?sp=districts&msg=blocked");
	
	}
	else if($_REQUEST['action_type']=="unblock")
	{
	    $code=$db->my_simple_crypt($_REQUEST['id'],'d');
		$data = array(
	
				'status'=>1
	
		);
		$conditions=array('districtCode'=>$code);
		$update=$db->update($table, $data, $conditions);
		header("location:index3.php?sp=districts&msg=unblocked");
	
	}

	
}
	
		
/* }
 catch (PDOException $ex)
 	{
 		header("Location:index3.php?sp=districts&msg=error");
 	} */
?>