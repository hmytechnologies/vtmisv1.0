<?php
session_start();
try {
    include 'DB.php';
    $db = new DBHelper();
    $tblName = 'student';
    $tblEmployment='employmentstatus';
    $tblSponsor='sponsor';
    $tblDisability='disability';
    
    $studentID=$_POST['studentID'];
    
    if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
        if($_REQUEST['action_type'] == 'edit'){
           // $fname = htmlentities(trim($_POST['fname']),ENT_QUOTES);
            //$mname = htmlentities(trim($_POST['mname']),ENT_QUOTES);
            //$lname = htmlentities(trim($_POST['lname']),ENT_QUOTES);
           // $oname=htmlentities(trim($_POST['oname']),ENT_QUOTES);
            $dob=$_POST['year']."-".$_POST['month']."-".$_POST['date'];
            $placeOfBirth=htmlentities($_POST['placeOfBirth'],ENT_QUOTES);
            $mstatus=$_POST['mstatus'];
            $citizenship=htmlentities($_POST['citizenship'],ENT_QUOTES);
            $address=htmlentities($_POST['address'],ENT_QUOTES);
            $appemail=htmlentities($_POST['appemail'],ENT_QUOTES);
            $phoneNumber=htmlentities($_POST['phoneNumber'],ENT_QUOTES);
            $religion=htmlentities($_POST['religion'],ENT_QUOTES);
            $districtID=htmlentities($_POST['districtID'],ENT_QUOTES);
            //Disability
            $disability=$_POST['disability'];
            if($disability=='ndio')
                $disability="Yes";
                else
                    $disability="No";
                    $dname=htmlentities($_POST['dname'],ENT_QUOTES);
                    $ddescription=htmlentities($_POST['ddescription'],ENT_QUOTES);
                    //Employment
                    $employed=$_POST['employed'];
                    $employer=htmlentities($_POST['employer'],ENT_QUOTES);
                    $placework=htmlentities($_POST['placework'],ENT_QUOTES);
                    $designation=htmlentities($_POST['designation'],ENT_QUOTES);
                    //Next of Kin
                    $nextName=htmlentities($_POST['nextName'],ENT_QUOTES);
                    $nextAddress=htmlentities($_POST['nextAddress'],ENT_QUOTES);
                    $nextPhoneNumber=htmlentities($_POST['nextPhoneNumber'],ENT_QUOTES);
                    $relationship=htmlentities($_POST['relationship'],ENT_QUOTES);
                    //Sponsor
                    $sponsor=htmlentities($_POST['sponsor'],ENT_QUOTES);
                    $sponsorname=htmlentities($_POST['sponsorname'],ENT_QUOTES);
                    $sponsoraddress=htmlentities($_POST['sponsoraddress'],ENT_QUOTES);
                    $sponsorphonenumber=htmlentities($_POST['sponsorphonenumber'],ENT_QUOTES);
                    
                    //update applicants first
                    $studentData = array(
                        //'firstName'=>$fname,
                        //'middleName'=>$mname,
                        //'lastName'=>$lname,
                        //'otherNames'=>$oname,
                        'placeOfBirth'=>$placeOfBirth,
                        'dateOfBirth'=>$dob,
                        'maritalStatus'=>$mstatus,
                        'citizenship'=>$citizenship,
                        'physicalAddress'=>$address,
                        'phoneNumber' =>$phoneNumber,
                        'email'=>$appemail,
                        'religion'=>$religion,
                        'nextOfKinName'=>$nextName,
                        'nextOfkinPhoneNumber'=>$nextPhoneNumber,
                        'nextOfKinAddress'=>$nextAddress,
                        'relationship'=>$relationship,
                        'disabilityStatus'=>$disability,
                        'employmentStatus'=>$employed,
                        'districtID'=>$districtID,
                        'sponsor'=>$sponsor,
                        'rgStatus'=>1
                    );
                    $condition=array('studentID'=>$studentID);
                    $update = $db->update($tblName,$studentData,$condition);
                    
                    if($disability=="Yes"){
                        $disabilityData=array(
                            'studentID'=>$studentID,
                            'disabilityName'=>$dname,
                            'disabilityDescription'=>$ddescription
                        );
                        if($db->isFieldExist($tblDisability, "studentID", $studentID))
                        {
                            $condition=array('studentID'=>$studentID);
                            $update=$db->update($tblDisability,$disabilityData,$condition);
                        }
                        else
                        {
                            $insertDisability=$db->insert($tblDisability,$disabilityData);
                        }
                        
                    }
                    
                    if($employed=="yes")
                    {
                        
                        $employmentData=array(
                            'studentID'=>$studentID,
                            'employer'=>$employer,
                            'placeOfWork'=>$placework,
                            'designation'=>$designation
                        );
                        if($db->isFieldExist($tblEmployment,"studentID", $studentID))
                        {
                            $condition=array('studentID'=>$studentID);
                            $update=$db->update($tblEmployment,$employmentData,$condition);
                        }
                        else
                        {
                            $insertEmp=$db->insert($tblEmployment,$employmentData);
                        }
                    }
                    
                    if($sponsor==4)
                    {
                        $sponsorData=array(
                            'studentID'=>$studentID,
                            'sponsorName'=>$sponsorname,
                            'sponsorAddress'=>$sponsoraddress,
                            'sponsorPhoneNumber'=>$sponsorphonenumber
                        );
                        if($db->isFieldExist($tblSponsor, "studentID", $studentID))
                        {
                            $condition=array('studentID'=>$studentID);
                            $update=$db->update($tblSponsor,$sponsorData,$condition);
                        }
                        else
                        {
                            $insertSponsor=$db->insert($tblSponsor,$sponsorData);
                        }
                    }
                    
                    //upload image
                    $imgFile = $_FILES['photo']['name'];
                    $tmp_dir = $_FILES['photo']['tmp_name'];
                    $imgSize = $_FILES['photo']['size'];
                    if(!empty($imgFile)){
                    $upload_dir = 'student_images/'; // upload directory
                    
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
                           $pictureData=array(
                               'studentPicture'=>$userpic
                           );
                            $condition=array('studentID'=>$studentID);
                            $update = $db->update($tblName,$pictureData,$condition);
                        }
                        else{
                            $errMSG = "Sorry, your image file is too large.";
                            $boolStaus=false;
                        }
                    }
                    else
                    {
                        $errMSG = "Sorry, only png,jpg,jpeg files are allowed.";
                        $boolStaus=false;
                    }
                    }
                    
                    $boolStatus=true;
                    
        }
        
        if($boolStatus)
        {
            header("Location:index3.php");
        }
        else
        {
            header("Location:index2.php?sp=studentform&msg=unsucc");
        }
    }
    
} catch (PDOException $ex) {
    header("Location:index2.php?sp=studentform&msg=error");
    //echo "Data Error".$ex->getMessage();
}