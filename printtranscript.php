<?php
session_start();
if($_REQUEST['action']=="getPDF")
{   
    include 'DB.php';
    $db=new DBHelper();
require('fpdf.php');
$studentID=$_REQUEST['studentID'];
class PDF extends FPDF
{
//Page header
function Banner()
{
   $today=date('M d,Y');
	//Logo . 
	$this->setFont('Arial', 'B', 14); 
	$this->Text(50,10,'INSTITUTE OF PUBLIC ADMINISTRATION');
    $this->Image('img/ipa.jpg',80,13,25.98,20.22);
	//$this->Image(file,x,y,w,h,type,link);
	//left address
	$this->setFont('Arial', '', 10); 
	$this->Text(39,20,'Phone:+255242230341');
	$this->Text(39,24,'Fax:+255776523744');
	$this->Text(39,28,'Email:info@ipa.ac.tz');
	//Right Address
	$this->setFont('Arial', '', 10); 
	$this->Text(106,20,'P.O.BOX 169');
	$this->Text(106,24,'Zanzibar-Tanzania');
	$this->Text(106,28,'Website:http://www.ipa.ac.tz');
	
   
    //Arial bold 15
	$this->setFont('Arial', 'B', 14); 
	$this->Text(50,40,'TRANSCRIPT OF EXAMINATION RESULTS');
}

        function OldBanner()
        {
            $today=date('M d,Y');
            //Logo .
            $this->setFont('Arial', 'B', 14);
            $this->Text(40,8,'ZANZIBAR INSTITUTE OF TOURISM DEVELOPMENT');
            $this->Image('img/zitod.jpg',15,12,30,30);
            //$this->Image(file,x,y,w,h,type,link);
            //left address
            $this->setFont('Arial', '', 12);
            $this->Text(48,18,"P.O.BOX 169 Zanzibar-Tanzania");
            $this->Text(48,26,'Phone:+255242230341  Fax:+255776523744');
            $this->Text(48,34,'Email:info@zitod.org  Website:http://www.zitod.org');
            $this->Line(15,42,200,42);

            //Right Address
            /* $this->setFont('Arial', '', 10);
             $this->Text(106,20,'P.O.BOX 169');
             $this->Text(106,24,'Zanzibar-Tanzania');
             $this->Text(106,28,'Website:http://www.zitod.org');*/

            //Arial bold 15
            $this->setFont('Arial', 'B', 14);
            $this->Text(50,50,'TRANSCRIPT OF EXAMINATION RESULTS');
        }

function SetCol($col)
{
    // Set position at a given column
    $this->col = $col;
    $x = 10+$col*65;
    $this->SetLeftMargin($x);
    $this->SetX($x);
}

/*function AcceptPageBreak()
{
    // Method accepting or not automatic page break
    if($this->col<2)
    {
        // Go to next column
        $this->SetCol($this->col+1);
        // Set ordinate to top
        $this->SetY($this->y0);
        // Keep on page
        return false;
    }
    else
    {
        // Go back to first column
        $this->SetCol(0);
        // Page break
        return true;
    }
}*/

//Page footer
function Footer()
{
	$today2=date('Y-m-d H:i:s');
    //Position at 1.5 cm from bottom
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,0,'Page '.$this->PageNo().'of{nb}',0,1,'C');
	
	$this->Cell(300,0,'Printed Date '.$today2.' Zanzibar',0,1,'C');
	
}
		
function BasicTable($header)
{
    // Header
	
    $w = array(15,60,10,15,10,10);
    for($i=0;$i<count($header);$i++)
    $this->Cell($w[$i],4,$header[$i],1,0,'L',1);
		
    $this->Ln();
    // Color and font restoration
		}
}
   // Set text color to blue. 

//
$pdf=new PDF();
$pdf->AliasNbPages();

$header = array('Code', 'Course Name', 'Unit', 'Grade','Point','GPA');
// Data loading


 $student = $db->getRows('student', ['where'=>array('student_id'=>$studentID),' order_by'=>' student_id ASC']);
 if(!empty($student))
                {
     $count = 0; 
                    foreach($student as $std)
                    { 
                      $count++;
                      $studentID=$std['student_id'];
                      $fname=$std['fname'];
                      $mname=$std['mname'];
                      $lname=$std['lname'];
                      $gender=$std['gender'];
                      $regNumber=$std['registration_number'];
                      $programmeID=$std['programme_id'];
                      $levelID=$std['programme_level_id'];
                      $statusID=$std['status_id'];
                      $name="$fname $mname $lname";

$pdf->AddPage('P');
$pdf->setFont('Arial', '', 8); 
$pdf->Banner();
$pdf->setFont('Arial', '', 8);
$stdPicture=$db->getRows('student_picture',array('where'=>array('studentID'=>$studentID),' order_by'=>'studentID ASC'));
                        if(!empty($stdPicture))
                        {
                            foreach($stdPicture as $pct)
                            {
                                $studentPic=$pct['studentPic'];
                                $pdf->Image("student_images/".$studentPic,155,12,25,27);
                            }
                        }	
$pdf->Ln(30);
$pdf->Cell(20,4,'');$pdf->Cell(70,4,'NAME:'.$name,1);$pdf->Cell(30,4,'SEX:'.$gender,1);$pdf->Cell(50,4,'REG.NO:'.$regNumber,1);
$pdf->Ln(4);
$pdf->Cell(20,4,'');$pdf->Cell(50,4,'CITEZENSHIP: Tanzanian',1);$pdf->Cell(100,4,'ADDRESS:'.$address1,1);
$pdf->Ln(4);
$pdf->Cell(20,4,'');$pdf->Cell(50,4,'DATE OF BIRTH:'.$dob,1);$pdf->Cell(50,4,'ADMITTED:'.$db->getData("academic_year","academic_year","academic_year_id",$admission_year),1);$pdf->Cell(50,4,'COMPLETED:'.$graduation_date,1);
$pdf->Ln(4);
$pdf->Cell(20,4,'');$pdf->Cell(150,4,'ADMITTED ON THE BASIS OF:'.$db->getData("manner_entry","manner_entry","id",$manner_entry_id),1);
$pdf->Ln(4);
$pdf->Cell(20,4,'');$pdf->Cell(150,4,'DEPARTMENT/SECTION:'.$db->getData("departments","department_name","department_id",$department_id),1);
$pdf->Ln(4);
$pdf->Cell(20,4,'');$pdf->Cell(150,4,'NAME OF THE PROGRAMME:'.$db->getData("programme_level","programme_level","programme_level_id",$levelID),1);
$pdf->Ln(4);
$pdf->Cell(20,4,'');$pdf->Cell(150,4,'MAJOR STUDY AREA:'.$db->getData("programmes","programme_name","programme_id",$programmeID),1);
$pdf->Ln(8);


$academic=$db->getAcademicYear($studentID);
if(!empty($academic))
{
    $total_points=0;$total_units=0;
    foreach($academic as $acYear)
    {
        $academicYear=$acYear['academic_year'];
        $academicYearID=$acYear['academic_year_id'];
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(20,4,'');$pdf->Cell(150,4,'ACADEMIC YEAR:'.$academicYear);
	$pdf->Ln(4);
	
       $semester=$db->getSemester($studentID);
       if(!empty($semester))
        {
          
            foreach($semester as $sm)
            {
                $semester_name=$sm['semister_name'];
                $semesterID=$sm['semister_id'];
                $pdf->SetFont('Arial','B',10);
                $pdf->Cell(20,4,'');$pdf->Cell(150,4,'SEMESTER NAME:'.$semester_name);
                $pdf->Ln(4);
                $course=$db->getCourse($studentID,$academicYearID,$semesterID);
                $tunits=0;
                $tpoints=0;
                foreach ($course as $cs) 
                {
                    $courseID=$cs['course_id'];
                    $units=$cs['units'];
                    $courseCode=$cs['course_code'];
                    $courseName=$cs['course_name'];
                    $cwk=$db->getGrade($semesterID,$academicYearID,$courseID,$studentID,1);
                    $sfe=$db->getGrade($semesterID,$academicYearID,$courseID,$studentID,2);
                    $total=$cwk+$sfe;
                    if($total>=80)
                    {
                        $grade="A";
                                       $points=$units*5;
                                    }
                                    else if($total>=70)
                                    {
                                       $grade="B";
                                       $points=$units*4;
                                    }
                                    else if($total>=60)
                                    {
                                      $grade="C";
                                      $points=$units*3;
                                    }
                                    else if($total>=50)
                                   {
                                      $grade="D";
                                      $points=$units*2;
                                   }
                                   else
                                   {
                                    $grade="F";
                                    $points=$units*0;
                                   }

                                   $tpoints=$tpoints+$points;
                                    $tunits=$tunits+$units;
                                    $gpa=round($tpoints/$tunits,1); 
                                    $total_points=$total_points+$tpoints;
                                    $total_units=$total_units+$tunits; 
                                    if($gpa>=2.0)
                                    $gparemarks="Pass";
                                    else
                                    $gparemarks="Fail";

                                    if(($grade=="D")or ($grade=="F"))
                                    {
                                     $countsupp=$countsupp+1;
                                    }
                                    else
                                    {
                                      $countpass=$countpass+1;
                                    }
                               				
					$pdf->SetFont('Arial','',8);
					$pdf->Cell(20,4,'');
					$pdf->Cell(15,4,$courseCode,1);
					$pdf->Cell(90,4,$courseName,1);
					$pdf->Cell(10,4,$units,1);
					$pdf->Cell(15,4,$grade,1);
					$pdf->Cell(10,4,$points,1);
					$pdf->Cell(10,4,'',1);
					$pdf->Ln();
					$i++;
                                }
                                //Sub Total
					$pdf->Cell(20,4,'');
					$pdf->Cell(15,4,'',1);
					$pdf->Cell(90,4,'GPA',1);
					$pdf->Cell(10,4,$tunits,1);
					$pdf->Cell(15,4,'',1);
					$pdf->Cell(10,4,$tpoints,1);
					$pdf->Cell(10,4,$gpa,1);
                                        $pdf->Ln(8);
                            }
                            
                            //$pdf->SetAutoPageBreak();
                            
                        }
                        
						
	$pdf->Ln(6);
                                       
    }
				$pdf->Ln(4);
}
		
		

$tgpa=$db->convert_gpa($total_points/$total_units);	
	$pdf->Ln(6);
	if($tgpa>=4.4)
	$classremark="First Class(HONORS)";
	else if($tgpa>=3.5)
	$classremark="Upper Second";
	else if($tgpa>=2.7)
	$classremark="Lower Second";
	else
	$classremark="Pass";	
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(20,4,'');$pdf->Cell(80,4,'OVERALL GPA: '.$tgpa);$pdf->Cell(70,4,'CLASSIFICATION:'.$classremark);
	$pdf->Ln(10);
	$pdf->Cell(20,4,'');$pdf->Cell(80,4,'.......................................');$pdf->Cell(70,4,'...........................................');
	$pdf->Ln(4);
	$pdf->Cell(30,4,'');$pdf->Cell(80,4,'For Director');$pdf->Cell(70,4,'Date');
	$pdf->Ln(5);
	$pdf->SetFont('Arial','I',8);
	$pdf->Cell(170,4,'END OF TRANSCRIPT','0','','C');
	
    $pdf->Ln(5);
	
	$pdf->Cell(20,4,'');$pdf->Cell(70,4,'1. The Transcript will be valid only if it bears the College Seal');
	$pdf->Ln(4);
	$pdf->Cell(20,4,'');$pdf->Cell(70,4,'2. Points=Grade Points Multiplied by Number of Units');
	$pdf->Ln(4);
	$pdf->Cell(20,4,'');$pdf->Cell(70,4,'3. Key to the Grades and other Symbols for College Exam: SEE THE TABLE BELOW ');
	$pdf->Ln(4);
	
	$pdf->Cell(20,4,'');$pdf->Cell(22,4,'Grade',1);$pdf->Cell(22,4,'A',1);$pdf->Cell(21,4,'B+',1);$pdf->Cell(21,4,'B',1);
	$pdf->Cell(21,4,'C',1); $pdf->Cell(21,4,'D',1);$pdf->Cell(22,4,'E',1);
	$pdf->Ln(4);
	$pdf->Cell(20,4,'');$pdf->Cell(22,4,'Marks',1);$pdf->Cell(22,4,'70-100',1);$pdf->Cell(21,4,'60-69',1);$pdf->Cell(21,4,'50-59',1);$pdf->Cell(21,4,'40-49',1); $pdf->Cell(21,4,'35-39',1);$pdf->Cell(22,4,'0-34',1);
	$pdf->Ln(4);
	$pdf->Cell(20,4,'');$pdf->Cell(22,4,'Grade Points',1);$pdf->Cell(22,4,'5',1);$pdf->Cell(21,4,'4',1);$pdf->Cell(21,4,'3',1);$pdf->Cell(21,4,'2',1); $pdf->Cell(21,4,'1',1);$pdf->Cell(22,4,'0',1);
	$pdf->Ln(4);
	$pdf->Cell(20,4,'');$pdf->Cell(22,4,'Remarks',1);$pdf->Cell(22,4,'Excellent',1);$pdf->Cell(21,4,'Very Good',1);$pdf->Cell(21,4,'Good',1);$pdf->Cell(21,4,'Satisfactory',1); $pdf->Cell(21,4,'Marginal Fail',1);$pdf->Cell(22,4,'Absolutely Fail',1);
	$pdf->Ln(7);
	$pdf->Ln(4);
	$pdf->Cell(20,4,'');$pdf->Cell(70,4,'4. Key to the Classification Awards: SEE THE TABLE BELOW ');
	$pdf->Ln(4);
	$pdf->Cell(20,4,'');$pdf->Cell(75,4,'CLASS OF AWARD',1);$pdf->Cell(75,4,'CUMMULATIVE GPA',1);
	$pdf->Ln(4);
	$pdf->Cell(20,4,'');$pdf->Cell(75,4,'First Class',1);$pdf->Cell(75,4,'4.4-5.0',1);
	$pdf->Ln(4);
	$pdf->Cell(20,4,'');$pdf->Cell(75,4,'Upper Second Class',1);$pdf->Cell(75,4,'3.5-4.3',1);
	$pdf->Ln(4);
	$pdf->Cell(20,4,'');$pdf->Cell(75,4,'Lower Second Class',1);$pdf->Cell(75,4,'2.7-3.4',1);
	$pdf->Ln(4);
	$pdf->Cell(20,4,'');$pdf->Cell(75,4,'Pass',1);$pdf->Cell(75,4,'2.0-2.6',1);
}
	
//$pdf->Output("paycheck.pdf","D");

                }
$pdf->Output();
}
?>
