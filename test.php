<?php
ini_set ('display_errors', 1);
error_reporting (E_ALL | E_STRICT);
include("DB.php");
$db=new DBHelper();


//echo $db->insert("","");

/*echo $db->getStudentExamStatus("18/PM/001",167,2,2);

var_dump($db->getStudentSuppSpecialRegNumber(77,1));*/

$studentNumber=$db->getStudentCourseSum(1,1,2,1);
echo $studentNumber;


/*if($db->doMobileLogin("Z/FO/NTA4/016/1807","Maulid2019"))
    echo 1;
else
    echo 0;*/

/*$regNumber="BA/IPA/023/IR.2017";

$debit = $db->getRows('student_fees',array('where'=>array('regNumber'=>$regNumber),' order_by'=>' regNumber ASC'));
if(!empty($debit))
{
    $totalFees=0;
    foreach($debit as $dbt)
    {
        $amount=$dbt['amount'];
        $totalFees+=$amount;
    }
}
else
{
    $totalFees=0;
}

//Payment
$paymentList = $db->getRows('student_payment',array('where'=>array('regNumber'=>$regNumber),'order_by'=>'paymentDate   ASC'));
if(!empty($paymentList))
{
    $totalPayments=0;
    foreach($paymentList as $list)
    {
        $amount=$list['amount'];
        $totalPayments+=$amount;
    }
}
else
{
    $totalPayments=0;
}

$balance=$totalFees-$totalPayments;
echo $totalFees."<br>".$totalPayments."<br>".$balance;*/

/*$student=$db->getRows("instructor_course");
if(!empty($student))
{
    foreach($student as $st)
    {
        $cprogID=$st['courseProgrammeID'];

        $cprog=$db->getRows("courseprogramme",array('where'=>array('courseProgrammeID'=>$cprogID)));
        if(!empty($cprog)) {
            foreach ($cprog as $cp) {
                /*$cpID = $cp['courseProgrammeID'];

                $cID=$cp['courseID'];

                $updateData=array(
                    'courseID'=>$cID,
                    'batchID'=>1
                );
                $condition=array('courseProgrammeID'=>$cprogID);
                $update=$db->update("instructor_course",$updateData,$condition);
            }
        }
        echo "Done";
    }
}*/
/*

echo $db->calculateGrade("Z/DICT/NTA6/016/2030",39,16,0,0,0,0);

echo $db->getMarksID("Z/DICT/NTA6/016/2030",39,16,'','','','');

//$conditions['where'] = array('roleID'=>2);
/*$conditions['return_type'] = 'count';
$user = $db->getRows("userroles",$conditions);
echo $user;*/


/*require('fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(40,10,'Hello World!');
$pdf->Output("mypdf.pdf","");*/

//echo $db->calculateGrade("BA/IPA/040/HRM.2017",32,40,'','','','');
//echo $db->getExamCategoryMark("BA/IPA/040/HRM.2017");
?>

<!-- <div id="example1">
</div>
<script>
var options = {
		   width: "100%",
		   height: "500px"
		};
PDFObject.embed(<?php //$pdf->Output("mypdf.pdf");?>.", "#example1",options);</script>
 -->
<?php
    //echo $db->isCourseExist("BA/IPA/011/RM.2017",252,1);

/*$marks=$db->getExamCategoryMark(2,'BA/IPA/014/HRM.2017');
echo $marks;*/
/*$gpa=$db->getGPA(730,123);
list($x,$y)=explode(".",$gpa);
echo $x;
echo "<br>";
echo $y;*/

//echo password_hash("Hamad",PASSWORD_DEFAULT);

//echo $db->getCurrentAcademicYear();

?>