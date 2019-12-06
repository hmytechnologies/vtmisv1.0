<?php
session_start();
include 'DB.php';
$db = new DBHelper();
$tblName = 'grades';
if(isset($_REQUEST['action_type']) && !empty($_REQUEST['action_type'])){
if($_REQUEST['action_type'] == 'add'){
        $userData = array(
            'gradeCode' =>strtoupper($_POST['gradeCode']),
            'gradePoints'=>$_POST['gradePoints'],
            'startMark' => $_POST['startMark'],
            'endMark'=>$_POST['endMark'],
            'programmeLevelID'=>$_POST['programmeLevelID'],
            'academicYearID'=>$_POST['academicYearID'],
            'remarkID'=>$_POST['remarkID'],
            'status'=>1
        );
        $insert = $db->insert($tblName,$userData);
        $statusMsg = true;
        header("Location:index3.php?sp=misc_setting&msg=succ");
    }elseif($_REQUEST['action_type'] == 'edit_grade'){
        if(!empty($_POST['gradeID'])){
            $userData = array(
                'gradeCode' =>strtoupper($_POST['gradeCode']),
                'gradePoints'=>$_POST['gradePoints'],
                'startMark' => $_POST['startMark'],
                'endMark'=>$_POST['endMark'],
                'programmeLevelID'=>$_POST['programmeLevelID'],
                'academicYearID'=>$_POST['academicYearID'],
                'remarkID'=>$_POST['remarkID'],
                'status'=>1
            );
            $condition = array('gradeID' => $_POST['gradeID']);
            $update = $db->update($tblName,$userData,$condition);
            $statusMsg = true;
            header("Location:index3.php?sp=misc_setting&msg=edited");
        }
    }
    elseif($_REQUEST['action_type'] == 'deactivate'){
        if(!empty($_POST['id'])){
            $userData = array(
                'status'=>0
            );
            $condition = array('gradeID' => $_POST['id']);
            $update = $db->update($tblName,$userData,$condition);
            $statusMsg = true;
            header("Location:index3.php?sp=misc_setting&msg=deactivate");
        }
    }
    elseif($_REQUEST['action_type']=='addgradingyear')
    {
        $userData = array(
            'gradingYearName' =>$_POST['gradeName'],
            'academicYearID'=>$_POST['academicYearID'],
            'numberOfTerms'=>$_POST['numberOfTerms'],
            'status'=>1
        );
        $insert = $db->insert("grading_year",$userData);
        header("Location:index3.php?sp=misc_setting&msg=succ");
    }
    elseif($_REQUEST['action_type']=='edit_grading_year')
    {
        if(!empty($_POST['gradingYearID'])) {
            $userData = array(
                'gradingYearName' => $_POST['gradeYearName'],
                'academicYearID' => $_POST['academicYearID'],
                'numberOfTerms' => $_POST['numberOfTerms'],
                'status' => $_POST['status']
            );
            $condition=array('gradingYearID'=>$_POST['gradingYearID']);
            $update = $db->update("grading_year", $userData,$condition);
            header("Location:index3.php?sp=misc_setting&msg=succ");
        }
    }
    else if($_REQUEST['action_type']=='addassessment')
    {

        $userData = array(
            'gradingYearID' =>$_POST['gradingYearID'],
            'subjectTypeID'=>$_POST['subjectTypeID'],
            'numberOfTerms'=>$_POST['numberOfAssessment'],
            'assessmentMarks'=>$_POST['assessmentMarks'],
            'termExamMarks'=>$_POST['termExamMarks'],
            'changeStatus'=>$_POST['changeStatus'],
            'status'=>1
        );
        $insert = $db->insert("assessment_setting",$userData);
        header("Location:index3.php?sp=misc_setting&msg=succ");

    }
}