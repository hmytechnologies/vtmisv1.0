<?php
require_once 'DB.php';
$db= new DBHelper();

$table="xsms_teacher";
if(isset($_REQUEST['action_type']) && (!empty($_REQUEST['action_type'])))
{
	$staffCode=$db->getRows($table,array('order_by'=>'teacherCode DESC','return_type'=>'single'));
	$code=$staffCode['teacherCode']+1;
	$date=date("Y-m-d h:i:s");
	if($_REQUEST['action_type']=='addBasicInfo')
	{
		if (!empty($_FILES['photo']['name'])) 
		{
			$imgFile = $_FILES['photo']['name'];
			$tmp_dir = $_FILES['photo']['tmp_name'];
			$imgSize = $_FILES['photo']['size'];
			
			$upload_dir = 'assets/staff_images/'; // upload directory
			
			$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
			
			// valid image extensions
			$valid_extensions = array('png','jpg','jpeg'); // valid extensions
			
			// rename uploading image
			$userpic = rand(1000,1000000).".".$imgExt;
			
			// allow valid image file formats
			if(in_array($imgExt, $valid_extensions)){
				// Check file size '5MB'
				if($imgSize < 5000000){
					move_uploaded_file($tmp_dir,$upload_dir.$userpic);
			
				}
			}
		}
		else
		{
			$userpic = 'default.png';
		}
        $staffData = array(
            'teacherCode'=>$code,
        	'schoolCode'=>$id_school,
             'pfNo' => $_POST['pfNo'],
            'employmentNO' => $_POST['employmentNo'],
			'firstName' => $_POST['fname'],
			'middleName' => $_POST['mname'],
			'lastName' => $_POST['lname'],
        	'sex'=>$_POST['gender'],
        	'meritalStatus'=>$_POST['maritalStatus'],
			'dateOfBirth'=>$_POST['dob'],
			'physicalAddress'=>$_POST['address'],
        	'phoneNumber'=>$_POST['phone'],
        	'staffEmail'=>$_POST['email'],
        	'nationalityCode'=>$_POST['nationality'],
			'nextOfKin'=>$_POST['nextOfKinName'],
        	'positionCode'=>$_POST['positionCode'],
			'nextOfKinTelephone'=>$_POST['nextOfKinTelephone'],
			'nextOfKinAddress'=>$_POST['nextOfKinAddress'],
			'employmentDate'=>$_POST['employmentDate'],
			'confirmationDate'=>$_POST['confirmationDate'],
			'recruitmentTypeCode'=>$_POST['recruitmentTypeCode'],
        	'contractStartDate'=>$_POST['contractStartDate'],
        	'contractEndDate'=>$_POST['contractEndDate'],
			'employmentStatusCode'=>0,
        	'shehiaID'=>$_POST['shehiaId'],
			'photo'=>$userpic,
        	'status'=>1
		);
		
       
			$staffCode=$db->getRows('xsms_staff_educational_background',array('order_by'=>'staffEducationBackgroundCode DESC','return_type'=>'single'));
			$staffEducationBackgroundCode=$staffCode['staffEducationBackgroundCode']+1;
			if (!empty($_POST['subjectCombId'])) 
			{
				foreach ($_POST['subjectCombId'] as $CombValue) 
				{
					$staffBackgroundData = array(
						'staffEducationBackgroundCode'=>$staffEducationBackgroundCode++,
						'teacherCode'=>$id_school,
						'staffTypeCode' => $_POST['staffTypeId'],
						'specializationCode' => $_POST['specialize_id'],
						'combinationCode' => $CombValue,
						'staffLevelCode' => $_POST['staffLevelId'],
						'award' => $_POST['award'],
						'institution' => $_POST['institution'],
						'year' => $_POST['yearId'],
						'status'=>1,
					);
					$insert = $db->insert('xsms_staff_educational_background',$staffBackgroundData);
				}
			}
			$insertStaff = $db->insert($table,$staffData);
       		// header("Location:index3.php?sp=view_staffs&msg=inserted");
		}
    }


// EDITTING ...
if (isset($_POST['DoEdit'])){
	$sId=$_POST['staffID'];
	$date=date("Y-m-d h:i:s");
	
	$imgFile = $_FILES['photo']['name'];
	$tmp_dir = $_FILES['photo']['tmp_name'];
	$imgSize = $_FILES['photo']['size'];
	
	$upload_dir = 'assets/staff_images/'; // upload directory
	
	$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // get image extension
	
	// valid image extensions
	$valid_extensions = array('png','jpg','jpeg'); // valid extensions
	
	// rename uploading image
	$userpic = rand(1000,1000000).".".$imgExt;
	
	// allow valid image file formats
	if(in_array($imgExt, $valid_extensions)){
		// Check file size '5MB'
		if($imgSize < 5000000){
			move_uploaded_file($tmp_dir,$upload_dir.$userpic);
	
		}
	}
	$staffData = array(
            'pfNo' => $_POST['pfNo'],
            'employmentNO' => $_POST['employmentNo'],
			'firstName' => $_POST['fname'],
			'middleName' => $_POST['mname'],
			'lastName' => $_POST['lname'],
        	'sex'=>$_POST['gender'],
        	'meritalStatus'=>$_POST['maritalStatus'],
			'dateOfBirth'=>$_POST['dob'],
			'physicalAddress'=>$_POST['address'],
			'positionCode'=>$_POST['positionCode'],
        	'phoneNumber'=>$_POST['phone'],
        	'staffEmail'=>$_POST['email'],
        	'nationalityCode'=>$_POST['nationality'],
			'nextOfKin'=>$_POST['nextOfKinName'],
			'nextOfKinTelephone'=>$_POST['nextOfKinTelephone'],
			'nextOfKinAddress'=>$_POST['nextOfKinAddress'],
			'employmentDate'=>$_POST['employmentDate'],
			'confirmationDate'=>$_POST['confirmationDate'],
			'recruitmentTypeCode'=>$_POST['recruitmentTypeCode'],
        	'contractStartDate'=>$_POST['contractStartDate'],
        	'contractEndDate'=>$_POST['contractEndDate'],
			'employmentStatusCode'=>0,

        	'shehiaCode'=>$_POST['shehiaId'],

			'photo'=>$userpic,
        	'modifiedDate'=>$date
        );
	$Conditions=array('teacherCode'=>$sId);
	$std=$db->update($table, $staffData, $Conditions);
	header("location:index3.php?sp=view_staffs&msg=updated");
}

// BLOCKING
if (isset($_GET['block'])){
	$dId=$db->my_simple_crypt($_GET['block'],'d');
	$progData = array(
			'status'=>0,
	);
	$conditions=array('teacherCode'=>$dId);
	$update=$db->update($table, $progData, $conditions);
	header("location: index3.php?sp=view_staffs&msg=blocked");
}

// UNBLOCKING
if (isset($_GET['unblock'])){
	$dId=$db->my_simple_crypt($_GET['unblock'],'d');
	$progData = array(
			'status'=>1,
	);
	$conditions=array('teacherCode'=>$dId);
	$update=$db->update($table, $progData, $conditions);
	header("location: index3.php?sp=view_staffs&msg=unblocked");
}
?>