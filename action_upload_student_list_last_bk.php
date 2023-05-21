<?php
session_start();
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
try {
    include 'DB.php';
    $db = new DBHelper();
    $tblName = 'users';
    $tblUserRole='userroles';
    $tblStudent='student';
    
    if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
        if($_REQUEST['action_type'] == 'add'){
            //upload file
            $file = $_FILES['csv_file']['tmp_name'];
            $handle = fopen($file, "r");
            if ($file == NULL) 
            {
                $boolStatus=false;
            }
            else 
            {
                //$flag=true;
                fgetcsv($handle);
                while(($filesop = fgetcsv($handle, 1000, ",")) !== false) {
                    /*if($flag) {
                        $flag = false;
                        continue;
                    }*/
                    $regNumber = $filesop[0];
                    $firstName = $filesop[1];
                    $middleName = $filesop[2];
                    $lastName = $filesop[3];
                    $gender = $filesop[4];
                    $dob = $filesop[5];
                    $academicYearID = $filesop[6];
                    $level = $filesop[7];


                    $username = strtoupper($regNumber);
                    $password = $db->PwdHash(strtoupper(trim($lastName)));
                    if ($db->isFieldExist($tblName, 'userName', $username)) {
                        $boolStatus = false;
                        $msg = "user";
                    } else {
                        $fname = $firstName;
                        $mname = $middleName;
                        $lname = $lastName;
                        $gender = $gender;
                        $dob=$dob;
                        $programmeID = $_POST['programmeID'];


                        //add users first
                        $userData = array(
                            'userName' => $username,
                            'password' => $password,
                            'firstName' => $fname,
                            'middleName' => $mname,
                            'lastName' => $lname,
                            'status' => 1,
                            'login' => 0
                        );
                        $insert = $db->insert($tblName, $userData);
                        $userID = $insert;


                        if (!empty($userID)) {
                            $userRolesData = array(
                                'userID' => $userID,
                                'roleID' => 2,
                                'status' => 1
                            );
                            $insert_role = $db->insert($tblUserRole, $userRolesData);
                            $studentData = array(
                                'firstName' => ucfirst($fname),
                                'middleName' => ucfirst($mname),
                                'lastName' => ucfirst($lname),
                                'registrationNumber' => strtoupper($regNumber),
                                'gender' => $gender,
                                'dateOfBirth' => $dob,
                                'academicYearID' => $academicYearID,
                                'statusID' => 1,
                                'userID' => $userID
                            );

                            $insert_student = $db->insert($tblStudent, $studentData);
                            $studentID = $insert_student;
                            if (!empty($studentID)) {
                                //academic_information
                                $centerID=$_POST['centerID'];
                                $academicData=array(
                                    'regNumber'=>$regNumber,
                                    'centerID'=>$centerID,
                                    'programmeLevelID'=>$_POST['programmeLevelID'],
                                    'programmeID'=>$programmeID,
                                    'academicYearID'=>$academicYearID,
                                    'statusID'=>1
                                );
                                $insertacademic=$db->insert("student_programme",$academicData);



                                $boolStatus = true;
                            } else {
                                $boolStatus = false;
                            }
                        } else {
                            $boolStatus = false;
                        }

                    }
                }
            }
            
            if($boolStatus)
            {
                header("Location:index3.php?sp=upload_file&msg=succ");
            }
            else
            {
                header("Location:index3.php?sp=upload_file&msg=unsucc");
            }
            
        }
    }
    
} catch (PDOException $ex) {
    header("Location:index3.php?sp=upload_file&msg=error");
}