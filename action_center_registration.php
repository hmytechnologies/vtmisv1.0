academicYearID<?php
session_start();
try {
include 'DB.php';
$db = new DBHelper();
$tblName = 'center_registration';
if (isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])) {
    if ($_REQUEST['action_type'] == 'add') {
        $imgFile = $_FILES['image']['name'];
        $tmp_dir = $_FILES['image']['tmp_name'];
        $imgSize = $_FILES['image']['size'];

        if (empty($imgFile)) {
            $errMSG = "Please Select Image File.";
            header("Location:index3.php?sp=add_new_center&msg=".$errMSG);
        } else {
            $upload_dir = 'img/'; // upload directory
            $imgExt = strtolower(pathinfo($imgFile, PATHINFO_EXTENSION)); // get image extension

            // valid image extensions
            $valid_extensions = array('jpeg', 'jpg', 'png'); // valid extensions

            // rename uploading image
            $userpic = rand(1000, 1000000) . "." . $imgExt;

            // allow valid image file formats
            if (in_array($imgExt, $valid_extensions)) {
                // Check file size '5MB'
                if ($imgSize < 5000000) {
                    move_uploaded_file($tmp_dir, $upload_dir . $userpic);
                } else {
                    $errMSG = "Sorry, your file is too large.";
                    header("Location:index3.php?sp=add_new_center&msg=".$errMSG);
                }
            } else {
                $errMSG = "Sorry, only JPG, JPEG & PNG files are allowed.";
                header("Location:index3.php?sp=add_new_center&msg=".$errMSG);
            }
        }

        // if no error occured, continue ....
        if (empty($errMSG)) {
            $userData = array(
                'centerName' => $_POST['name'],
                'regNumber'=>$_POST['regNumber'],
                'centerCode' => $_POST['code'],
                'registrationTypeID'=>$_POST['registrationTypeID'],
                'accredidationTypeID'=>$_POST['accredidationTypeID'],
                'ownershipTypeID'=>$_POST['ownershipTypeID'],
                'shehiaID'=>$_POST['shehiaID'],
                'physicalAddress' => $_POST['physicalAddress'],
                'postalAddress' => $_POST['postalAddress'],
                'centerPhoneNumber' => $_POST['phoneNumber'],
                'centerEmail' => $_POST['email'],
                'centerWebsite' => $_POST['website'],
                'establishedYear'=>$_POST['year'],
                'contactPerson' => $_POST['cperson'],
                'contactEmail'=>$_POST['cemail'],
                'contactPhone'=>$_POST['cphoneNumber'],
                'centerStatus'=>1,
                'centerPicture' => $userpic
            );

            $insert = $db->insert($tblName, $userData);
            header("Location:index3.php?sp=center_reg&msg=succ");
        }

    }
    elseif ($_REQUEST['action_type'] == 'edit') {
        if (!empty($_REQUEST['id'])) {




            $userData = array(
                'centerName' => $_POST['name'],
                'regNumber'=>$_POST['regNumber'],
                'centerCode' => $_POST['code'],
                'registrationTypeID'=>$_POST['registrationTypeID'],
                'accredidationTypeID'=>$_POST['accredidationTypeID'],
                'ownershipTypeID'=>$_POST['ownershipTypeID'],
                'shehiaID'=>$_POST['shehiaID'],
                'physicalAddress' => $_POST['physicalAddress'],
                'postalAddress' => $_POST['postalAddress'],
                'centerPhoneNumber' => $_POST['phoneNumber'],
                'centerEmail' => $_POST['email'],
                'centerWebsite' => $_POST['website'],
                //'establishedYear'=>$_POST['year'],
                'contactPerson' => $_POST['cperson'],
                'contactEmail'=>$_POST['cemail'],
                'contactPhone'=>$_POST['cphoneNumber'],
                'centerStatus'=>1
            );
            $condition = array('centerRegistrationID' => $_POST['id']);
            $update = $db->update($tblName, $userData, $condition);
            //upload image
            $imgFile = $_FILES['image']['name'];
            $tmp_dir = $_FILES['image']['tmp_name'];
            $imgSize = $_FILES['image']['size'];
            if(!empty($imgFile)){
                $upload_dir = 'img/'; // upload directory

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
                            'centerPicture'=>$userpic
                        );
                        $condition = array('centerID' => $_POST['id']);
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
            $statusMsg = true;
            header("Location:index3.php?sp=center_reg&msg=edited");
        }
    }
}
}catch (PDOException $ex)
{
    header("Location:index3.php?sp=add_new_center&msg=err");
}