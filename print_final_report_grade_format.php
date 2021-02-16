<?php
//call the autoload
ini_set('display_errors', 1);
error_reporting(E_ALL | E_STRICT);
include 'DB.php';
$db = new DBHelper();
require 'vendor/autoload.php';
//load phpspreadsheet class using namespaces
use PhpOffice\PhpSpreadsheet\Spreadsheet;
//call iofactory instead of xlsx writer
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

//styling arrays
//table head style
$tableHead = [
	'font'=>[
		'color'=>[
			'rgb'=>'FFFFFF'
		],
		'bold'=>true,
		'size'=>11
	],
	'fill'=>[
		'fillType' => Fill::FILL_SOLID,
		'startColor' => [
			'rgb' => '538ED5'
		]
	],
];
//even row
$evenRow = [
	'fill'=>[
		'fillType' => Fill::FILL_SOLID,
		'startColor' => [
			'rgb' => '00BDFF'
		]
	]
];
//odd row
$oddRow = [
	'fill'=>[
		'fillType' => Fill::FILL_SOLID,
		'startColor' => [
			'rgb' => '00EAFF'
		]
	]
];

$styleArray = [
	'borders' => [
		'outline' => [
			'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
			'color' => ['argb' => 'FFFFFFFFFF'],
		],
	],
];


//styling arrays end

//make a new spreadsheet object
$spreadsheet = new Spreadsheet();
//get current active sheet (first sheet)
$sheet = $spreadsheet->getActiveSheet();

//set default font
$spreadsheet->getDefaultStyle()
	->getFont()
	->setName('Arial')
	->setSize(10);
$programmeID = $_REQUEST['pid'];
$studyYear = $_REQUEST['styear'];
$semesterID = $_REQUEST['sid'];
$batchID=$_REQUEST['bid'];

//$sheet->getDefaultStyle()->applyFromArray($styleArray);

$organization = $db->getRows('organization', array('order_by' => 'organizationName DESC'));
if (!empty($organization)) {
	foreach ($organization as $org) {
		$organizationName = $org['organizationName'];
		$organizationCode = $org['organizationCode'];
		$organizationPicture = "img/" . $org['organizationPicture'];
	}
} else {
	$organizationName = "Soft Dev Academy";
	$organizationCode = "SDVA";
	$organizationPicture = "img/SkyChuo.png";
}

$drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
$drawing->setName('Logo');
$drawing->setDescription('Logo');
$drawing->setPath($organizationPicture);
$drawing->setHeight(80);
$drawing->setWorksheet($spreadsheet->getActiveSheet());

$programme = $db->getData("programmes", "programmeName", "programmeID", $programmeID);
$semester = $db->getData("semester_setting", "semesterName", "semesterSettingID", $semesterID);

$schoolID = $db->getData("programmes", "schoolID", "programmeID", $programmeID);
$schoolName = $db->getData("schools", "schoolName", "schoolID", $schoolID);
$programmeCode = $db->getData("programmes", "programmeCode", "programmeID", $programmeID);


//heading
$sheet
	->setCellValue('A1',$organizationName);
$sheet
->setCellValue('A2', $schoolName);
$sheet
->setCellValue('C3', $programme."-".$semester."-First Round");

//merge heading
$sheet->mergeCells("A1:M1");
$sheet->mergeCells("A2:M2");
$sheet->mergeCells("C3:M3");

// set cell alignment
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

// set font style
$sheet->getStyle('A1')->getFont()->setSize(15);
$sheet->getStyle('A2')->getFont()->setSize(15);
$sheet->getStyle('A3')->getFont()->setSize(15);
//setting column width
$sheet->getColumnDimension('A')->setWidth(8);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(25);
/* $sheet->getColumnDimension('D')->setWidth(15);
 */



$col=4;
$row=5;
$academicYearID = $db->getData("semester_setting", "academicYearID", "semesterSettingID", $semesterID);
$student = $db->getStudentProgramme($programmeID, $semesterID, $studyYear,$batchID, $academicYearID);
if (!empty($student)) {
	$sheet
	->setCellValue('A5', "No")
	->setCellValue('B5', "Reg.Number")
	->setCellValue('C5', "Full Name");
	$course = $db->getCourseCredit($programmeID, $semesterID, $studyYear, $academicYearID);
	foreach (range('D', 'N') as $columnID) {
		$sheet->getColumnDimension($columnID)
			->setAutoSize(true);
	}
    foreach ($course as $cs) {
		$sheet->setCellValueByColumnAndRow($col,$row,$cs['courseCode']);
		//$sheet->getActiveSheet()->getColumnDimension($col)->setWidth(12);
		//$spreadsheet->getActiveSheet()->getColumnDimension('D','R')->setAutoSize(true);
		//$sheet->getColumnDimension($col)->setAutoSize(true);
        $col++;
    }
	$sheet->setCellValueByColumnAndRow($col, $row, "GPA");
	$col++;
	$sheet->setCellValueByColumnAndRow($col, $row, "GPA Class");
	$col++;
	$sheet->setCellValueByColumnAndRow($col, $row, "Remarks");

	$row = 0;
	//$col++;
	$col=0;
	$row=6;
	$count=0;
	$tsup = 0;
	$tpass = 0;
	$tfail = 0;
	$tothers = 0;
	$tincomp = 0;
	$tsupf = 0;
	$tpassf = 0;
	$tfailf = 0;
	$tothersf = 0;
	$tincompf = 0;

	$fclass = 0;
	$uclass = 0;
	$lcass = 0;
	$pclass = 0;
	$ffclass = 0;
	$fclassf = 0;
	$uclassf = 0;
	$lcassf = 0;
	$pclassf = 0;
	$ffclassf = 0;

	$maxgpa = 0;
	$mingpa = 6;
	$gpaarr = array();
	$tlgpa = 0;
    foreach ($student as $st) {
        $count++;
		$regNumber = $st['regNumber'];
		$fname = $st['firstName'];
		$lname = $st['lastName'];
		$fname = str_replace("&#039;", "'", $fname);
		$lname = str_replace("&#039;", "'", $lname);
		$name = $fname. " ".$lname;
		$gender = $st['gender'];
		$statusID = $st['statusID'];
       /*  $studentDetails = $db->getRows('student', array('where' => array('registrationNumber' => $regNumber), ' order_by' => 'firstName ASC'));
        foreach ($studentDetails as $std) {
            $fname = $std['firstName'];
            $mname = $std['middleName'];
            $lname = $std['lastName'];
            $name = "$fname $mname $lname";
            $gender = $std['gender'];
			$dob = $std['dateOfBirth'];
			$statusID=$std['statusID']; */
			$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $count);
			$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $regNumber);
			$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $fname." ".$lname);
			$col++;
			//Grades
			$course = $db->getCourseCredit($programmeID, $semesterID, $studyYear, $academicYearID);
			$tunits = 0;
			$tpoints = 0;
			$countpass = 0;
			$countsupp = 0;
			$countincomp=0;
            foreach ($course as $cs) {
                $courseID = $cs['courseID'];
                $units = $cs['units'];

				// $student_course = $db->getStudentExamCourse($regNumber, $semesterID, $courseID);
				$student_course = $db->getStudentGPAExamCourse($regNumber, $semesterID, $courseID);
                if (!empty($student_course)) {
					/* $cwk = $db->decrypt($db->getGrade($semesterID, $courseID, $regNumber, 1));
                    $sfe = $db->decrypt($db->getFinalGrade($semesterID, $courseID, $regNumber, 2));
                    $sup = $db->decrypt($db->getGrade($semesterID, $courseID, $regNumber, 3));
                    $spc = $db->decrypt($db->getFinalGrade($semesterID, $courseID, $regNumber, 4));
                    $prj = $db->decrypt($db->getGrade($semesterID, $courseID, $regNumber, 5));
					$pt = $db->decrypt($db->getGrade($semesterID, $courseID, $regNumber, 6)); */
					$cwk = 0;
					$sfe = 0;
					$sup = 0;
					$spc = 0;
					$prj = 0;
					$pt = 0;
						foreach ($student_course as $st) {
							$examScore = $st['examScore'];
							$examCategoryID = $st['examCategoryID'];
							if ($examCategoryID == 1) {
								$cwk = $db->decrypt($examScore);
							} elseif ($examCategoryID == 3) {
								$sup = $db->decrypt($examScore);
							} elseif ($examCategoryID == 4) {
								$spc = $db->decrypt($examScore);
							} elseif ($examCategoryID == 5
							) {
								$prj = $db->decrypt($examScore);
							} elseif ($examCategoryID == 6) {
								$pt = $db->decrypt($examScore);
							}
						}

						$sfe = $db->decrypt($db->getFinalGrade($semesterID, $courseID, $regNumber, 2));

                    $passCourseMark = $db->getExamCategoryMark(1, $regNumber, $studyYear);
                    $passFinalMark = $db->getExamCategoryMark(2, $regNumber, $studyYear);
                    $tmarks = $db->calculateTotal($cwk, $sfe, $sup, $spc, $prj, $pt);
                    /* if (!empty($sup)) {
                        $passMark = $db->getExamCategoryMark(3, $regNumber, $studyYear);
                        if ($tmarks >= $passMark) {
                            $grade = "C";
                        } else {
                            $grade = "D";
                        }
                        $gradeID = $db->getMarksID($regNumber, $studyYear, $cwk, $sfe, $sup, $spc, $prj, $pt);
                        $gradePoint = $db->getData("grades", "gradePoints", "gradeID", $gradeID);
                    } else if (!empty($pt)) {
                        /* $passMark = $db->getExamCategoryMark(6, $regNumber, $studyYear);
                        $gradeID = $db->getMarksID($regNumber, $studyYear, $cwk, $sfe, $sup, $spc, $prj, $pt);
                        if ($tmarks >= $passMark) {
                            $grade = $db->getData("grades", "gradeCode", "gradeID", $gradeID);
                        } else {
                            $grade = "D";
                        }
                        $gradePoint = $db->getData("grades", "gradePoints", "gradeID", $gradeID);
                    } elseif (!empty($prj)) {
                        $passMark = $db->getExamCategoryMark(5, $regNumber, $studyYear);
                        $gradeID = $db->getMarksID($regNumber, $studyYear, $cwk, $sfe, $sup, $spc, $prj, $pt);
                        if ($tmarks >= $passMark) {
                            $grade = $db->getData("grades", "gradeCode", "gradeID", $gradeID);
                        } else {
                            $grade = "D";
                        }
                        $gradePoint = $db->getData("grades", "gradePoints", "gradeID", $gradeID);
					} else
					*/
					/* if (empty($cwk) || empty($sfe)) {
                        $grade = "I";
                        $gradePoint = 0;
                    } elseif ($cwk < $passCourseMark) {
                        $grade = "E";
                        $gradePoint = 0;
                    } elseif ($sfe < $passFinalMark) {
                        $grade = "E";
                        $gradePoint = 0;
					} else 
					{ */
						$gradeID = $db->getMarksID($regNumber, $studyYear, $cwk, $sfe, $sup, $spc, $prj, $pt);
						$gradePoint = $db->getData("grades", "gradePoints", "gradeID", $gradeID);
						$grade = $db->calculateGradeEx($regNumber, $studyYear, $cwk, $sfe, $sup, $spc, $prj, $pt);
					//}
                        
                    $points = $gradePoint * $units;
                    $tpoints += $points;
                    $tunits += $units;
					$gpa = $db->getGPA($tpoints, $tunits);
					$gpaRemarksClassess = $db->getGPARemarks($studyYear, $regNumber, $gpa);

                    if (($grade == "D") or ($grade == "F") or ($grade == "E")) {
						$countsupp = $countsupp + 1;
					} 
					else if($grade=="I")
					{
						$countincomp+=1;
					}else {
                        $countpass = $countpass + 1;
					}

					if ($countincomp > 0) {
						$gparemarks = "INC";
					} elseif ($countsupp > 0) {
						$gparemarks = "Supp";
					} else {
						$gparemarks = "Pass";
					}

                    /* if ($gpa < 2) {
                        $gparemarks = "Fail";
                    } elseif ($countsupp > 0) {
                        $gparemarks = "Supp";
                    } else {
                        $gparemarks = "Pass";
                    } */
                } else {
                    $cwk = "-";
                    $sfe = "-";
                    $totalMarks = "-";
                    $grade = "-";
                    $units = "-";
                    $points = "-";
                }

				$sheet->setCellValueByColumnAndRow($col, $row, $grade);
				$col++;
			}
			$sheet->setCellValueByColumnAndRow($col, $row, $gpa);
			$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $gpaRemarksClassess);
			$col++;
			$sheet->setCellValueByColumnAndRow($col, $row, $gparemarks);
		//}
		$row++;
		$col=0;

		//Summary
		$programmeLevelID = $db->getData("programmes", "programmeLevelID", "programmeID", $programmeID);

		if ($gender == "M") {
			if ($statusID == 1) {
				if ($gparemarks == "Pass")
					$tpass += 1;
				else if ($gparemarks == "Supp")
					$tsup += 1;
				else if ($gparemarks == "Fail")
					$tfail += 1;
				else if ($gparemarks == "INC")
					$tincomp += 1;
			} else {
				$tothers += 1;
			}

			//countsgpa
			if ($programmeLevelID == 1 || $programmeLevelID == 2) {
				if ($gpa >= 3.5)
					$fclass += 1;
				else if ($gpa >= 3.0)
					$lcass += 1;
				else if ($gpa >= 2)
					$pclass += 1;
				else
					$ffclass += 1;
			} else {
				if ($gpa >= 4.4)
					$fclass += 1;
				else if ($gpa >= 3.5)
					$uclass += 1;
				else if ($gpa >= 3.0)
					$lcass += 1;
				else if ($gpa >= 2)
					$pclass += 1;
				else
					$ffclass += 1;
			}
		} else {
			if ($statusID == 1) {
				if ($gparemarks == "Pass")
					$tpassf += 1;
				else if ($gparemarks == "Supp")
					$tsupf += 1;
				else if ($gparemarks == "Fail")
					$tfailf += 1;
				else if ($gparemarks == "INC")
					$tincompf += 1;
			} else {
				$tothersf += 1;
			}


			//countsgpa
			if ($programmeLevelID == 1 || $programmeLevelID == 2) {
				if ($gpa >= 3.5)
					$fclassf += 1;
				else if ($gpa >= 3.0)
					$lcassf += 1;
				else if ($gpa >= 2)
					$pclassf += 1;
				else
					$ffclassf += 1;
			} else {
				if ($gpa >= 4.4)
					$fclassf += 1;
				else if ($gpa >= 3.5)
					$uclassf += 1;
				else if ($gpa >= 3.0)
					$lcassf += 1;
				else if ($gpa >= 2)
					$pclassf += 1;
				else
					$ffclassf += 1;
			}
		}
		$gpaarr[] = $gpa;

		if ($maxgpa < $gpa)
			$maxgpa = $gpa;

		if ($mingpa > $gpa)
			$mingpa = $gpa;

		$tlgpa += $gpa;

	}
	//summary

	/* $pdf->Cell(50, 6, "SUMMARY OF PERFOMANCE STATISTICS");
	$pdf->Ln(6);
	$pdf->SetFont('Arial', '', 12);
	$pdf->Cell(30, 6, 'Remarks', 1);
	$pdf->Cell(30, 6, "Pass", 1);
	$pdf->Cell(30, 6, "Supp", 1);
	$pdf->Cell(30, 6, "Fail", 1);
	$pdf->Cell(30, 6, "Incomplete", 1, 0, 'L');
	$pdf->Cell(40, 6, "Other Remarks", 1, 0, 'L');

	$pdf->Ln(6);
	$pdf->Cell(30, 6, 'Male', 1);
	$pdf->Cell(30, 6, $tpass, 1);
	$pdf->Cell(30, 6, $tsup, 1);
	$pdf->Cell(30, 6, $tfail, 1);
	$pdf->Cell(30, 6, $tincomp, 1, 0, 'L');
	$pdf->Cell(40, 6, $tothers, 1, 0, 'L');

	$pdf->Ln(6);
	$pdf->Cell(30, 6, 'Female', 1);
	$pdf->Cell(30, 6, $tpassf, 1);
	$pdf->Cell(30, 6, $tsupf, 1);
	$pdf->Cell(30, 6, $tfailf, 1);
	$pdf->Cell(30, 6, $tincompf, 1, 0, 'L');
	$pdf->Cell(40, 6, $tothersf, 1, 0, 'L');

	$pdf->Ln(6);
	$pdf->Cell(30, 6, 'Subtotal', 1);
	$pdf->Cell(30, 6, $tpass + $tpassf, 1);
	$pdf->Cell(30, 6, $tsup + $tsupf, 1);
	$pdf->Cell(30, 6, $tfail + $tfailf, 1);
	$pdf->Cell(30, 6, $tincompf + $tincomp, 1, 0, 'L');
	$pdf->Cell(40, 6, $tothers + $tothersf, 1, 0, 'L'); */

	$row++;
	$sheet->setCellValue("B$row", "SUMMARY OF PERFOMANCE STATISTICS");
	$row++;
	$sheet->setCellValue("B$row", "Pass");
	$sheet->setCellValue("C$row", $tpass + $tpassf);
	$row++;
	$sheet->setCellValue("B$row", "Supp");
	$sheet->setCellValue("C$row", $tsup + $tsupf);
	$row++;
	$sheet->setCellValue("B$row", "Fail");
	$sheet->setCellValue("C$row", $tfail + $tfailf);
	$row++;
	$sheet->setCellValue("B$row", "Incomplete");
	$sheet->setCellValue("C$row", $tincomp + $tincompf);
	$row++;
	$sheet->setCellValue("B$row", "Others");
	$sheet->setCellValue("C$row", $tothers + $tothersf);
	$row++;

	/* $sheet->setCellValue("A$row", "Sub Total");
	$sheet->setCellValue("B$row", $tpass + $tpassf);
	$sheet->setCellValue("C$row", $tsup + $tsupf);
	$sheet->setCellValue("D$row", $tfail + $tfailf);
	$sheet->setCellValue("E$row", $tincomp + $tincompf);
	$sheet->setCellValue("F$row", $tothers + $tothersf); */
	/*$sheet->setCellValue("A$row", "Male");
	$sheet->setCellValue("B$row", $tpass);
	$sheet->setCellValue("C$row", $tsup);
	$sheet->setCellValue("D$row", $tfail);
	$sheet->setCellValue("E$row", $tincomp);
	$sheet->setCellValue("F$row", $tothers);
	$row++;
	$sheet->setCellValue("A$row", "Female");
	$sheet->setCellValue("B$row", $tpassf);
	$sheet->setCellValue("C$row", $tsupf);
	$sheet->setCellValue("D$row", $tfailf);
	$sheet->setCellValue("E$row", $tincompf);
	$sheet->setCellValue("F$row", $tothersf);
	$row++;
	$sheet->setCellValue("A$row", "Sub Total");
	$sheet->setCellValue("B$row", $tpass+$tpassf);
	$sheet->setCellValue("C$row", $tsup+$tsupf);
	$sheet->setCellValue("D$row", $tfail+$tfailf);
	$sheet->setCellValue("E$row", $tincomp+$tincompf);
	$sheet->setCellValue("F$row", $tothers+$tothersf);
	$row+=2; */
	
	$sheet->setCellValue("B$row", "	SUMMARY OF GPA CLASSES STATISTICS");
	$row++;
	$sheet->setCellValue("B$row", "First Class");
	$sheet->setCellValue("C$row", $fclass + $fclassf);
	$row++;
	$sheet->setCellValue("B$row", "Upper Second");
	$sheet->setCellValue("C$row", $uclass + $uclassf);
	$row++;
	$sheet->setCellValue("B$row", "Lower Second");
	$sheet->setCellValue("C$row", $lcass + $lcassf);
	$row++;
	$sheet->setCellValue("B$row", "Pass");
	$sheet->setCellValue("C$row", $pclass + $pclassf);
	$row++;
	$sheet->setCellValue("B$row", "Fail");
	$sheet->setCellValue("C$row", $ffclass + $ffclassf);
	
	/* $sheet->setCellValue("A$row", "Remarks");
	$sheet->setCellValue("B$row", "First Class");
	$sheet->setCellValue("C$row", "Upper Second");
	$sheet->setCellValue("D$row", "Lower Second");
	$sheet->setCellValue("E$row", "Pass");
	$sheet->setCellValue("F$row", "Fail");
	$row++;
	$sheet->setCellValue("A$row", "Male");
	$sheet->setCellValue("B$row", $fclass);
	$sheet->setCellValue("C$row", $uclass);
	$sheet->setCellValue("D$row", $lcass);
	$sheet->setCellValue("E$row", $pclass);
	$sheet->setCellValue("F$row", $ffclass);
	$row++;
	$sheet->setCellValue("A$row", "Female");
	$sheet->setCellValue("B$row", $fclassf);
	$sheet->setCellValue("C$row", $uclassf);
	$sheet->setCellValue("D$row", $lcassf);
	$sheet->setCellValue("E$row", $pclassf);
	$sheet->setCellValue("F$row", $ffclassf);
	$row++;
	$sheet->setCellValue("A$row", "Sub Total");
	$sheet->setCellValue("B$row", $fclass + $fclassf);
	$sheet->setCellValue("C$row", $uclass + $uclassf);
	$sheet->setCellValue("D$row", $lcass + $lcassf);
	$sheet->setCellValue("E$row", $pclass + $pclassf);
	$sheet->setCellValue("F$row", $ffclass + $ffclassf); */
}



$sheet->getStyle(
	'A1:' .
	$sheet->getHighestColumn() .
	$sheet->getHighestRow()
)->applyFromArray($styleArray);

//

//$sheet->getStyle("A1:A".$row)->applyFromArray($styleArray);



//set the header first, so the result will be treated as an xlsx file.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

//make it an attachment so we can define filename
header('Content-Disposition: attachment;filename="Semester_Report_"'.$programmeCode."-".$semester.'".xlsx"');

//create IOFactory object
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
//save into php output
$writer->save('php://output');