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
                while(($filesop = fgetcsv($handle, 1000, ",")) !== false) {
                    $firstName = $filesop[0];
                    $middleName = $filesop[1];
                    $lastName = $filesop[2];
                    $gender = $filesop[3];
                    $regNumber = $filesop[4];
                    $dob = $filesop[5];
                    $adminNumber = $filesop[6];
                    $formFourNumber = $filesop[7];
                    $mannerEntryID = $filesop[8];
                    $phoneNumber = $filesop[9];
                    $hosteller=$filesop[10];
                    $sponsor=$filesop[11];
                    $nationality=$filesop[12];

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
                        $academicYearID = $_POST['academicYearID'];
                        $programmeID = $_POST['programmeID'];
                        $admissionnumber = $adminNumber;
                        $formfournumber = $formFourNumber;

                        //add users first
                        $userData = array(
                            'userName' => $username,
                            'password' => $password,
                            'firstName' => $fname,
                            'middleName' => $mname,
                            'lastName' => $lname,
                            'phoneNumber'=>$phoneNumber,
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
                                'programmeID' => $programmeID,
                                'formFourIndexNumber' => $formfournumber,
                                'admissionNumber' => $admissionnumber,
                                'mannerEntryID' => $mannerEntryID,
                                'hosteller'=>$hosteller,
                                'sponsor'=>$sponsor,
                                'citizenship'=>$nationality,
                                'batchID' => 1,
                                'statusID' => 1,
                                'userID' => $userID
                            );

                            $insert_student = $db->insert($tblStudent, $studentData);
                            $studentID = $insert_student;
                            if (!empty($studentID)) {
                                //study year
                                $study_data = array(
                                    'regNumber' => strtoupper($regNumber),
                                    'studyYear' => 1,
                                    'academicYearID' => $academicYearID,
                                    'studyYearStatus' => 1
                                );
                                $insert_study_year = $db->insert("student_study_year", $study_data);
                                //programme fees

                                $amount = $db->getAllFees($programmeID);
                                $today = date('Y-m-d');
                                $invc = date('dm');
                                $invNumber = "INV" . $invc . rand(101, 999);
                                $account_data = array(
                                    'regNumber' => $regNumber,
                                    'studyYear' => 1,
                                    'academicYearID' => $academicYearID,
                                    'amount' => $amount,
                                    'feesID' => 1,
                                    'invoiceNumber' => $invNumber,
                                    'invoiceDate' => $today,
                                    'feesDescription' => 'University/Tuition Fees'
                                );
                                $insert = $db->insert("student_fees", $account_data);

                                if($hosteller=='Yes')
                                {
                                    $amount_hostel = 300000;
                                    $today2 = date('Y-m-d');
                                    $invc2 = date('dm');
                                    $invNumber2 = "INV" . $invc2 . rand(101, 999);
                                    $hostel_data = array(
                                        'regNumber' => $regNumber,
                                        'studyYear' => 1,
                                        'academicYearID' => $academicYearID,
                                        'amount' => $amount_hostel,
                                        'feesID' => 2,
                                        'invoiceNumber' => $invNumber2,
                                        'invoiceDate' => $today2,
                                        'feesDescription' => 'Hostel Fees'
                                    );
                                    $insert = $db->insert("student_fees", $hostel_data);
                                }

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