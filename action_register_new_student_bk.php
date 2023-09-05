
<?php
session_start();
//ini_set ('display_errors', 1);
//error_reporting (E_ALL | E_STRICT);
try {
    include 'DB.php';
    $db = new DBHelper();
    $tblName = 'users';
    $tblUserRole='userroles';
    $tblStudent='student';
    $tblEmployment='employmentstatus';
    $tblSponsor='sponsor';
    $tblDisability='disability';

    if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
        if($_REQUEST['action_type'] == 'add'){
            //Organization details
            $organization = $db->getRows('organization',array('order_by'=>'organizationName DESC'));
            if(!empty($organization))
            {
                foreach($organization as $org)
                {
                    $organizationName=$org['organizationName'];
                    $organizationCode=$org['organizationCode'];
                    $organizationEmail=$org['organizationEmail'];
                }
            }
            else
            {
                $organizationName="Soft Dev Academy";
                $organizationCode="SDVA";
                $organizationEmail="hmy@hmytechnologies.com";
            }


            $regNumber=$_POST['regNumber'];
            $email=$_POST['email'];
            $username=strtoupper($regNumber);
            $pwd=$db->generate_password(8);
            $password=$db->PwdHash($pwd);
            $boolStatus=false;

            /*$fname = strip_tags(trim(ucfirst($_POST['fname'])));
            $mname = strip_tags(trim(ucfirst($_POST['mname'])));
            $lname = strip_tags(trim(ucfirst($_POST['lname'])));*/

            $fname = trim( htmlentities(ucfirst($_POST['fname']),ENT_QUOTES));
            $mname = trim(htmlentities(ucfirst($_POST['mname']),ENT_QUOTES));
            $lname = trim(htmlentities(ucfirst($_POST['lname']),ENT_QUOTES));

            if($db->isFieldExist($tblName,'userName',$username))
            {
                $boolStatus=false;
                $msg="user";
            }
            else if($db->isFieldExist($tblName,'email',$email))
            {
                $boolStatus=false;
                $msg="email";
            }
            else
            {
                /*$gender=$_POST['gender'];
                $phoneNumber=$_POST['phoneNumber'];
                $dob=$_POST['year']."-".$_POST['month']."-".$_POST['date'];
                $academicYearID=$_POST['academicYearID'];
                $mannerOfEntryID=$_POST['mannerOfEntryID'];
                $batchID=$_POST['batchID'];
                $programmeLevelID=$_POST['programmeLevelID'];
                $programmeID=$_POST['programmeID'];
                $admissionnumber=$_POST['admissionnumber'];
                $formfournumber=$_POST['formfournumber'];*/

                $gender=$_POST['gender'];
                $dob=$_POST['year']."-".$_POST['month']."-".$_POST['date'];
                $oname=htmlentities($_POST['oname'],ENT_QUOTES);
                $placeOfBirth=htmlentities($_POST['placeOfBirth'],ENT_QUOTES);
                $mstatus=$_POST['mstatus'];
                $citizenship=htmlentities($_POST['citizenship'],ENT_QUOTES);
                $address=htmlentities($_POST['address'],ENT_QUOTES);
                $appemail=htmlentities($_POST['appemail'],ENT_QUOTES);
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
                $mannerOfEntryID=$_POST['mannerOfEntryID'];
                $batchID=$_POST['batchID'];
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
                $nextPhoneNumber=htmlentities($_POST['nextPhoneNumber'],ENT_QUOTES);
                $relationship=htmlentities($_POST['relationship'],ENT_QUOTES);
                $nemail=$_POST['nemail'];
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
                    'email'=>$_POST['email'],
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
                    'otherNames'=>$oname,
                    'gender'=>$gender,
                    'dateOfBirth'=>$dob,
                    'districtID'=>$districtID,
                    'placeOfBirth'=>$placeOfBirth,
                    'maritalStatus'=>$mstatus,
                    'citizenship'=>$citizenship,
                    'physicalAddress'=>$address,
                    'phoneNumber' =>$phoneNumber,
                    'email'=>$appemail,
                    'nextOfKinName'=>$nextName,
                    'nextOfKinPhoneNumber'=>$nextPhoneNumber,
                    'nextOfKinAddress'=>$nextAddress,
                    'relationship'=>$relationship,
                    'nextOfKinEmail'=>$_POST['nemail'],
                    'disabilityStatus'=>$disability,
                    'employmentStatus'=>$employed,
                    'sponsor'=>$sponsor,
                    'hosteller'=>$hosteller,
                    'religion'=>$religion,
                    'academicYearID'=>$academicYearID,
                    'registrationNumber'=>strtoupper($regNumber),
                    'programmeID'=>$programmeID,
                    'formFourIndexNumber'=>$formfournumber,
                    'admissionNumber'=>$admissionnumber,
                    'mannerEntryID'=>$mannerOfEntryID,
                    'batchID'=>$batchID,
                    'statusID'=>1,
                    'userID'=>$userID
                    /*'firstName'=>$fname,
                    'middleName'=>$mname,
                    'lastName'=>$lname,
                    'registrationNumber'=>strtoupper($regNumber),
                    'gender'=>$gender,
                    'dateOfBirth'=>$dob,
                    'phoneNumber'=>$phoneNumber,
                    'academicYearID'=>$academicYearID,
                    'programmeID'=>$programmeID,
                    'formFourIndexNumber'=>$formfournumber,
                    'admissionNumber'=>$admissionnumber,
                    'email'=>$_POST['email'],
                    'mannerEntryID'=>$mannerOfEntryID,
                    'citizenship'=>$_POST['nationality'],
                    'batchID'=>$batchID,
                    'statusID'=>1,
                    'userID'=>$userID*/
                );
                $insert_std = $db->insert($tblStudent,$studentData);
                $studentID=$insert_std;

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
                $documentData=array(
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
                }


                //roles data
                $userRolesData = array(
                    'userID' =>$userID,
                    'roleID'=>2,
                    'status'=>1
                );
                $insert_role = $db->insert($tblUserRole,$userRolesData);
                //study year
                $study_data=array(
                    'regNumber'=>strtoupper($regNumber),
                    'studyYear'=>1,
                    'academicYearID'=>$academicYearID,
                    'studyYearStatus'=>1
                );
                $insert_study_year=$db->insert("student_study_year",$study_data);
                //programme fees
                $amount=$db->getAllFees($programmeID,$academicYearID);
                $account_data=array(
                    'regNumber'=>$regNumber,
                    'studyYear'=>1,
                    'academicYearID'=>$academicYearID,
                    'amount'=>$amount,
                    'feesID'=>1,
                    'feesDescription'=>'University/Tuition Fees'
                );
                $insert=$db->insert("student_fees",$account_data);
                //send email
                $to = $_POST['email'];
                $subject = 'Login details for Aspire StAR';
                $from = $organizationEmail;

// To send HTML mail, the Content-type header must be set
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
// Create email headers
                $headers .= 'From: ' . $from . "\r\n" .
                    'Reply-To: ' . $from . "\r\n" .
                    'X-Mailer: PHP/' . phpversion();
                $name="$fname $lname";
// Compose a simple HTML email message
                $message = '<html><body>';
                /* $message = '<h1>Welcome to StAR,the extended Student Academic Register</h1>';*/
                $message .= '<h1 style="color:#080;">Dear ' . $name . '</h1>';
                $message .= '<p>Welcome to Aspire UAS, member of SkyChuo Enterprise Resource Planning Management Information System for University/College</p>';
                $message .= '<p>To activate your account you must login using username and password below:</p>';
                $message .= '<p style="color:#f40;font-size:18px;">UserName: ' . $username . '<br>Password: ' . $pwd . '</p>';
                $message .= '<p>Please do not expose your password to any other person. You may change your password at any time if you wish to do so. </p>';
                $message .= '<p>We hope you enjoy using Aspire StAR and all services offered by other software solutions under SkyChuo package.</p>';
                $message .= '<p></p>';
                $message .= '<p>Warm Regards,</p>';
                $message .= '<p></p>';
                $message .= '<p>_________________________</p>';
                $message .= '<p>SkyChuo Account Management Services </p>';
                $message .= '<p>'.$organizationName.'</p>';
                $message .= '<p>SkyChuo is offered by <a href="http://www.hmytechnologies.com" target="_blank">HM&Y Technologies</a></p>';
                $message .= '</body></html>';
// Sending email
                mail($to, $subject, $message, $headers);

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
            $oname=htmlentities($_POST['oname'],ENT_QUOTES);
            $placeOfBirth=htmlentities($_POST['placeOfBirth'],ENT_QUOTES);
            $mstatus=$_POST['mstatus'];
            $citizenship=htmlentities($_POST['citizenship'],ENT_QUOTES);
            $address=htmlentities($_POST['address'],ENT_QUOTES);
            $appemail=htmlentities($_POST['appemail'],ENT_QUOTES);
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
            $mannerOfEntryID=$_POST['mannerOfEntryID'];
            $batchID=$_POST['batchID'];
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
            $nextPhoneNumber=htmlentities($_POST['nextPhoneNumber'],ENT_QUOTES);
            $relationship=htmlentities($_POST['relationship'],ENT_QUOTES);
            $nemail=$_POST['nemail'];
            //Sponsor
            $sponsor=htmlentities($_POST['sponsor'],ENT_QUOTES);
            $sponsorname=htmlentities($_POST['sponsorname'],ENT_QUOTES);
            $sponsoraddress=htmlentities($_POST['sponsoraddress'],ENT_QUOTES);
            $sponsorphonenumber=htmlentities($_POST['sponsorphonenumber'],ENT_QUOTES);


            $editData = array(
                'firstName'=>$fname,
                'middleName'=>$mname,
                'lastName'=>$lname,
                'otherNames'=>$oname,
                'gender'=>$gender,
                'dateOfBirth'=>$dob,
                'districtID'=>$districtID,
                'placeOfBirth'=>$placeOfBirth,
                'maritalStatus'=>$mstatus,
                'citizenship'=>$citizenship,
                'physicalAddress'=>$address,
                'phoneNumber' =>$phoneNumber,
                'email'=>$appemail,
                'nextOfKinName'=>$nextName,
                'nextOfKinPhoneNumber'=>$nextPhoneNumber,
                'nextOfKinAddress'=>$nextAddress,
                'relationship'=>$relationship,
                'nextOfKinEmail'=>$_POST['nemail'],
                'disabilityStatus'=>$disability,
                'employmentStatus'=>$employed,
                'sponsor'=>$sponsor,
                'hosteller'=>$hosteller,
                'religion'=>$religion,
                'academicYearID'=>$academicYearID,
                'registrationNumber'=>strtoupper($regNumber),
                'programmeID'=>$programmeID,
                'formFourIndexNumber'=>$formfournumber,
                'admissionNumber'=>$admissionnumber,
                'mannerEntryID'=>$mannerOfEntryID,
                'batchID'=>$batchID,
                'statusID'=>1
            );
            $condition=array("studentID"=>$studentID);

            $update = $db->update($tblStudent,$editData,$condition);


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
            $documentData=array(
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
            }





            //roles data
            $userID=$db->getData("student","userID","studentID",$studentID);
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
            }


            //update users
            $username=strtoupper($regNumber);
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
            $updateuser=$db->update("users",$userData,$condition2);


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

} catch (PDOException $ex) {
    header("Location:index3.php?sp=rform&msg=error");
}

