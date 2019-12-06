<?php
session_start();
//try {
    include 'DB.php';
    $db = new DBHelper();
    $tblName = 'organization';
    if (isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])) {
        if ($_REQUEST['action_type'] == 'add') {
            $imgFile = $_FILES['image']['name'];
            $tmp_dir = $_FILES['image']['tmp_name'];
            $imgSize = $_FILES['image']['size'];

            if (empty($imgFile)) {
                $errMSG = "Please Select Image File.";
                header("Location:index3.php?sp=organization&msg=".$errMSG);
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
                        header("Location:index3.php?sp=organization&msg=".$errMSG);
                    }
                } else {
                    $errMSG = "Sorry, only JPG, JPEG & PNG files are allowed.";
                    header("Location:index3.php?sp=organization&msg=".$errMSG);
                }
            }

            // if no error occured, continue ....
            if (!isset($errMSG)) {
                $userData = array(
                    'organizationName' => $_POST['name'],
                    'organizationCode' => $_POST['code'],
                    'organizationAddress' => $_POST['physicaladdress'],
                    'organizationPostal' => $_POST['address'],
                    'organizationPhone' => $_POST['phone'],
                    'organizationEmail' => $_POST['email'],
                    'organizationWebsite' => $_POST['website'],
                    'organizationReference' => $_POST['refnumber'],
                    'starLink'=>$_POST['star_link'],
                    'organizationPicture' => $userpic
                );

                $insert = $db->insert($tblName, $userData);
                $statusMsg = true;
                header("Location:index3.php?sp=organization&msg=succ");
            }

        }
        elseif ($_REQUEST['action_type'] == 'edit') {
            if (!empty($_REQUEST['id'])) {

                $userData = array(
                    'organizationName' => $_POST['name'],
                    'organizationCode' => $_POST['code'],
                    'organizationAddress' => $_POST['physicaladdress'],
                    'organizationPostal' => $_POST['address'],
                    'organizationPhone' => $_POST['phone'],
                    'organizationEmail' => $_POST['email'],
                    'organizationWebsite' => $_POST['website'],
                    'organizationReference' => $_POST['refnumber'],
                    'starLink'=>$_POST['star_link']
                );
                $condition = array('organizationID' => $_POST['id']);
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
                                'organizationPicture'=>$userpic
                            );
                            $condition = array('organizationID' => $_POST['id']);
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
                header("Location:index3.php?sp=organization&msg=succ");
            }
        }
    }
/*}catch (PDOException $ex)
{
    header("Location:index3.php?sp=organization&msg=err");
}*/