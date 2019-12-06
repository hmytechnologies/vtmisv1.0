<?php
include("DB.php");
$db=new DBHelper();
$studyYear=$_POST['studyYear'];
if($studyYear) {
    $regNumber = $_POST['regNumber'];
    $acad = $db->getRows('student_study_year', array('where' => array('studyYear' => $studyYear, 'regNumber' => $regNumber), 'order_by' => 'academicYearID ASC'));
    if (!empty($acad)) {
        foreach ($acad as $c) {
            $academicYearID = $c['academicYearID'];
            $studyYearStatus = $c['studyYearStatus'];
            $academicYear = $db->getData("academic_year", "academicYear", "academicYearID", $academicYearID);

            $programmeID = $db->getData("student", "programmeID", "registrationNumber", $regNumber);
            $programmeName = $db->getData("programmes", "programmeName", "programmeID", $programmeID);

            if ($studyYearStatus == 1) {
                $programmeLevelID = $db->getData("programmes", "programmeLevelID", "programmeID", $programmeID);
                $programmeLevelName = $db->getData("programme_level", "programmeLevel", "programmeLevelID", $programmeLevelID);

            } else {
                $programmeLevelID = $db->getData("programmes", "programmeLevelID", "programmeID", $programmeID);

                if ($programmeLevelID == 3) {
                    $programmeLevelID = 1;
                    $programmeLevelName = $db->getData("programme_level", "programmeLevel", "programmeLevelID", $programmeLevelID);
                } else if ($programmeLevelID == 1) {
                    $programmeLevelID = 2;
                    $programmeLevelName = $db->getData("programme_level", "programmeLevel", "programmeLevelID", $programmeLevelID);
                }
            }
        }
    }
}
?>