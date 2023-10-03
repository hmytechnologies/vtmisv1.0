<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
//try {
    include 'DB.php';
    $db = new DBHelper();
    $tblName = 'users';
    $tblUserRole='userroles';
    $tblStudent='student';
    $tblEmployment='employmentstatus';
    $tblSponsor='sponsor';
    $tblDisability='disability';
    $tblhostel="student_hostel";

    if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
        if($_REQUEST['action_type'] == 'add'){

            $regNumber=$_POST['regNumber'];
            $email=$_POST['email'];
            $username=strtoupper($regNumber);

            $boolStatus=false;

            $fname = trim( htmlentities(ucfirst($_POST['fname']),ENT_QUOTES));
            $mname = trim(htmlentities(ucfirst($_POST['mname']),ENT_QUOTES));
            $lname = trim(htmlentities(ucfirst($_POST['lname']),ENT_QUOTES));

            $pwd = strtoupper(trim($lname));
            $password=$db->PwdHash($pwd);

            if($db->isFieldExist($tblName,'userName',$username))
            {
                $boolStatus=false;
                $msg="user";
            }
            /*else if($db->isFieldExist($tblName,'email',$email))
            {
                $boolStatus=false;
                $msg="email";
            }*/
            else
            {
                $gender=$_POST['gender'];
                $phoneNumber=$_POST['phoneNumber'];
                $dob=$_POST['year']."-".$_POST['month']."-".$_POST['date'];
                $academicYearID=$_POST['academicYearID'];
                //programme/trade info
                $programmeLevelID=$_POST['programmeLevelID'];
                $programmeID=$_POST['programmeID'];

                $admissionnumber=$_POST['admissionnumber'];
                $formfournumber=$_POST['formfournumber'];
                $gender=$_POST['gender'];
                $placeOfBirth=htmlentities($_POST['placeOfBirth'],ENT_QUOTES);
                $mstatus=$_POST['mstatus'];
                $nationalID=htmlentities($_POST['nationalID'],ENT_QUOTES);
                $address=htmlentities($_POST['address'],ENT_QUOTES);
                $phoneNumber=htmlentities($_POST['phoneNumber'],ENT_QUOTES);
                $shehiaID=$_POST['shehiaID'];
                $religion=$_POST['religion'];
                //hostel
                $hosteller=$_POST['hosteller'];

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
                
                //add users first
                $userData = array(
                    'userName'=>$username,
                    'password'=>$password,
                    'firstName'=>$fname,
                    'middleName'=>$mname,
                    'lastName'=>$lname,
                    'phoneNumber'=>$phoneNumber,
                    'email'=>$email,
                    'status'=>1,
                    'login'=>0
                );


                $insert = $db->insert($tblName,$userData);

                $userID=$insert;
                //student details
                $studentData = array(
                    'firstName'=>$fname,
                    'middleName'=>$mname,
                    'lastName'=>$lname,
                    'gender'=>$gender,
                    'dateOfBirth'=>$dob,
                    'shehiaID'=>$shehiaID,
                    'placeOfBirth'=>$placeOfBirth,
                    'maritalStatus'=>$mstatus,
                    'nationalID'=>$nationalID,
                    'physicalAddress'=>$address,
                    'phoneNumber' =>$phoneNumber,
                    'email'=>$email,
                    'nextOfKinName'=>$nextName,
                    'nextOfKinPhoneNumber'=>$nextPhoneNumber,
                    'nextOfKinAddress'=>$nextAddress,
                    'relationship'=>$relationship,
                    'disabilityStatus'=>$disability,
                    'employmentStatus'=>$employed,
                    'sponsor'=>$sponsor,
                    'religion'=>$religion,
                    'academicYearID'=>$academicYearID,
                    'registrationNumber'=>strtoupper($regNumber),
                    'formFourIndexNumber'=>$formfournumber,
                    'admissionNumber'=>$admissionnumber,
                    'statusID'=>1,
                    'userID'=>$userID
                );
                $insert_std = $db->insert($tblStudent,$studentData);
                $studentID=$insert_std;

                //academic_information
                $centerID=$_POST['centerID'];
                $academicData=array(
                    'regNumber'=>$regNumber,
                    'centerID'=>$centerID,
                    'programmeLevelID'=>$_POST['programmeLevelID'],
                    'programmeID'=>$programmeID,
                    'academicYearID'=>$academicYearID,
                    'currentStatus'=>1
                );
                $insertacademic=$db->insert("student_programme",$academicData);

                //hostel info
                $hostelData=array(
                    'regNumber'=>$regNumber,
                    'academicYearID'=>$academicYearID,
                    'hostelStatus'=>$hosteller
                );
                $inserthostel=$db->insert($tblhostel,$hostelData);

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

                if($sponsor=="others")
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

                //documents
               /*$documentData=array(
                    'studentID'=>$studentID,
                    'medical'=>$_POST['medical'],
                    'certificate'=>$_POST['certificate'],
                    'formsixcertificate'=>$_POST['formsixcertificate'],
                    'other_document'=>$_POST['other_document']
                );
                if($db->isFieldExist("documents","studentID", $studentID))
                {
                    $condition=array('studentID'=>$studentID);
                    $update=$db->update("documents",$documentData,$condition);
                }
                else
                {
                    $insertdocument=$db->insert("documents",$documentData);
                }*/




                //roles data
                $userRolesData = array(
                    'userID' =>$userID,
                    'roleID'=>2,
                    'status'=>1
                );
                $insert_role = $db->insert($tblUserRole,$userRolesData);

                //programme fees
                $amount=$db->getAllFees($programmeID,$academicYearID);
                $account_data=array(
                    'regNumber'=>$regNumber,
                    'academicYearID'=>$academicYearID,
                    'amount'=>$amount,
                    'feesID'=>1,
                    'feesDescription'=>'University/Tuition Fees'
                );
                $insert=$db->insert("student_fees",$account_data);


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
                            $update = $db->update($tblStudent,$pictureData,$condition);
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
                $msg="succ";

            }
            
           if($boolStatus)
            {
                header("Location:index3.php?sp=rform&msg=succ");
            }
            else
            {
                header("Location:index3.php?sp=rform&msg=unsucc");
            }


        }
        else if($_REQUEST['action_type'] == 'edit')
        {
            $studentID=$_POST['studentID'];
            $regNumber=$_POST['regNumber'];
            $fname = trim( htmlentities(ucfirst($_POST['fname']),ENT_QUOTES));
            $mname = trim(htmlentities(ucfirst($_POST['mname']),ENT_QUOTES));
            $lname = trim(htmlentities(ucfirst($_POST['lname']),ENT_QUOTES));
            $gender=$_POST['gender'];
            $dob=$_POST['year']."-".$_POST['month']."-".$_POST['date'];
            // $oname=htmlentities($_POST['oname'],ENT_QUOTES);
            $placeOfBirth=htmlentities($_POST['placeOfBirth'],ENT_QUOTES);
            $mstatus=$_POST['mstatus'];
             $citizenship=$_POST['nationalID'];
            $address=htmlentities($_POST['address'],ENT_QUOTES);
            $appemail=htmlentities($_POST['email'],ENT_QUOTES);
            $phoneNumber=htmlentities($_POST['phoneNumber'],ENT_QUOTES);
            $districtID=$_POST['districtID'];
            $hosteller=$_POST['hosteller'];
            $religion=$_POST['religion'];

            //Disability
            $disability=$_POST['disability'];
            if($disability=='ndio')
                $disability="Yes";
            else
                $disability="No";
            $dname=htmlentities($_POST['dname'],ENT_QUOTES);
            $ddescription=htmlentities($_POST['ddescription'],ENT_QUOTES);

            //academic information
            $academicYearID=$_POST['academicYearID'];
            // $mannerOfEntryID=$_POST['mannerOfEntryID'];
            // $batchID=$_POST['batchID'];
            $programmeID=$_POST['programmeID'];
            $admissionnumber=$_POST['admissionnumber'];
            $formfournumber=$_POST['formfournumber'];

            //Employment
            $employed=$_POST['employed'];
            $employer=htmlentities($_POST['employer'],ENT_QUOTES);
            $placework=htmlentities($_POST['placework'],ENT_QUOTES);
            $designation=htmlentities($_POST['designation'],ENT_QUOTES);
            //Next of Kin
            $nextName=htmlentities($_POST['nextName'],ENT_QUOTES);
            $nextAddress=htmlentities($_POST['nextAddress'],ENT_QUOTES);
            $nextPhone=htmlentities($_POST['nextPhoneNumber'],ENT_QUOTES);
            $relationship=htmlentities($_POST['relationship'],ENT_QUOTES);
            // $nemail=$_POST['nemail'];
            //Sponsor
            $sponsor=htmlentities($_POST['sponsor'],ENT_QUOTES);
            $sponsorname=htmlentities($_POST['sponsorname'],ENT_QUOTES);
            $sponsoraddress=htmlentities($_POST['sponsoraddress'],ENT_QUOTES);
            $sponsorphonenumber=htmlentities($_POST['sponsorphonenumber'],ENT_QUOTES);


            $editData = array(
                'firstName'=>$fname,
                'middleName'=>$mname,
                'lastName'=>$lname,
                // 'otherNames'=>$oname,
                'gender'=>$gender,
                'dateOfBirth'=>$dob,
                // 'districtID'=>$districtID,
                'placeOfBirth'=>$placeOfBirth,
                'maritalStatus'=>$mstatus,
                // 'citizenship'=>$citizenship,
                'physicalAddress'=>$address,
                'phoneNumber' =>$phoneNumber,
                'email'=>$appemail,
                'nextOfKinName'=>$nextName,
                'nextOfkinPhoneNumber'=>$nextPhone,
                'nextOfKinAddress'=>$nextAddress,
                'relationship'=>$relationship,
                // 'nextOfKinEmail'=>$_POST['nemail'],
                'disabilityStatus'=>$disability,
                'employmentStatus'=>$employed,
                'sponsor'=>$sponsor,
                // 'hosteller'=>$hosteller,
                'religion'=>$religion,
                'academicYearID'=>$academicYearID,
                'registrationNumber'=>strtoupper($regNumber),
                // 'programmeID'=>$programmeID,
                'formFourIndexNumber'=>$formfournumber,
                'admissionNumber'=>$admissionnumber,
                // 'mannerEntryID'=>$mannerOfEntryID,
                
                'statusID'=>1
            );
            // $condition=array("studentID"=>$studentID);

            $condition = array('studentID' =>  $_POST['studentID']);  
            $update = $db->update($tblStudent,$editData,$condition);

               $center=$_POST['centerID'];
            //    $programmeID=$_POST['programmeID'];
                $student_programmeData=array(
                    'regNumber'=>$regNumber,
                    'centerID'=>$center,
                    'programmeLevelID'=>$_POST['programmeLevelID'],
                    'programmeID'=>$programmeID,
                    'academicYearID'=>$academicYearID
                    
                   
                );

                $datacondition=array("regNumber"=>$regNumber);
                $insertacademic=$db->update("student_programme", $student_programmeData,$datacondition);

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

            //documents
            /*$documentData=array(
                'studentID'=>$studentID,
                'medical'=>$_POST['medical'],
                'certificate'=>$_POST['certificate'],
                'formsixcertificate'=>$_POST['formsixcertificate'],
                'other_document'=>$_POST['other_document']
            );
            if($db->isFieldExist("documents","studentID", $studentID))
            {
                $condition=array('studentID'=>$studentID);
                $update=$db->update("documents",$documentData,$condition);
            }
            else
            {
                $insertdocument=$db->insert("documents",$documentData);
            }*/





            //roles data
            /*$userID=$db->getData("student","userID","studentID",$studentID);
            $userrole=$db->getRows("userroles",array('where'=>array('userID'=>$userID)));
            if(!empty($userrole))
            {
                $rolecondition=array('userID'=>$userID);
                $roleData = array(
                    'roleID' => 2,
                    'status' => 1
                );
                $update_tbl = $db->update($tblUserRole, $roleData,$rolecondition);
            }
            else {
                $userRolesData = array(
                    'userID' => $userID,
                    'roleID' => 2,
                    'status' => 1
                );
                $insert_tbl = $db->insert($tblUserRole, $userRolesData);
            }*/


            //update users
            /*$username=strtoupper($regNumber);
            $userData = array(
                'userName'=>$username,
                'firstName'=>$fname,
                'middleName'=>$mname,
                'lastName'=>$lname,
                'phoneNumber'=>$_POST['phoneNumber'],
                'email'=>$_POST['email'],
                'status'=>1,
                'login'=>0
            );
            $condition2=array("userID"=>$userID);
            $updateuser=$db->update("users",$userData,$condition2);*/


            //image
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
                        $update = $db->update($tblStudent,$pictureData,$condition);
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
            header("Location:index3.php?sp=rform&msg=edit");
        }
    }
    
/*} catch (PDOException $ex) {
    header("Location:index3.php?sp=rform&msg=error");
}*/
// 
