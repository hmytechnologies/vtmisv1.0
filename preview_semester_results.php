<?php
    $programmeID=$_POST['programmeID'];
    $studyYear=$_POST['studyYear'];
    $batchID=$_POST['batchID'];
    $semesterID=$_POST['semesterID'];
    ?>
<embed src="print_semester_report_sumait.php?action=getPDF&prgID=<?php echo $programmeID;?>&bid=<?php echo $batchID;?>&sid=<?php echo $semesterID;?>&syear=<?php echo $studyYear;?>" frameborder="0" width="100%" height="600px">
