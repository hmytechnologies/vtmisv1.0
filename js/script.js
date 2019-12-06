/*function print_exam_result() {
    var programmeID = document.getElementById("programID").value;
    var studyYear = document.getElementById("studyYear").value;
    var semesterID = document.getElementById("semesterID").value;
    var batchID = document.getElementById("batchID").value;
    var dataString = 'programmeID=' + programmeID + '&studyYear=' + studyYear + '&semesterID=' + semesterID + '&batchID=' + batchID;
        $('#myPleaseWait').modal('show');
        $.ajax({
            type: "POST",
            /!*url: "ajax_view_semester_result.php",*!/
            url: "print_semester_report_sumait.php",
            data: dataString,
            cache: false,
            success: function(html) {
                $('#add_new_atype_modal').modal('show');
                /!*$("#result").html(html);*!/
            }
        });
    return false;
}*/

function print_exam_result() {
    var programmeID = document.getElementById("programID").value;
    var studyYear = document.getElementById("studyYear").value;
    var semesterID = document.getElementById("semesterID").value;
    var batchID = document.getElementById("batchID").value;
    var dataString = 'programmeID=' + programmeID + '&studyYear=' + studyYear + '&semesterID=' + semesterID + '&batchID=' + batchID;
    $('#myPleaseWait').modal('show');
    $.ajax({
        type: "POST",
        url: "preview_semester_results.php",
        /*url: "print_semester_report_sumait.php",*/
        data: dataString,
        cache: false,
        success: function(html) {
            $('#myPleaseWait').modal('hide');
            $("#result").html(html);
        }
    });
    return false;
}

function view_supp_special_list() {
    var semesterID = document.getElementById("semisterID").value;
    var dataString = 'semesterID=' + semesterID;
    $('#myPleaseWait').modal('show');
    $.ajax({
        type: "POST",
        url: "ajax_view_supp_special_management.php",
        data: dataString,
        cache: false,
        success: function(html) {
            $('#myPleaseWait').modal('hide');
            $("#result").html(html);
        }
    });
    return false;
}

function viewGranduands() {
    var programmeID = document.getElementById("programmeID").value;
    var batchID = document.getElementById("batchID").value;
    var admissionYearID = document.getElementById("admissionYearID").value;
    var graduationDate=document.getElementById("exam_date").value;
    var dataString = 'programmeID=' + programmeID + '&academicYearID=' + admissionYearID + '&batchID=' + batchID + '&graduationDate='+graduationDate;
    $('#myPleaseWait').modal('show');
    $.ajax({
        type: "POST",
        url: "ajax_approve_granduands.php",
        data: dataString,
        cache: false,
        success: function(html) {
            $('#myPleaseWait').modal('hide');
            $("#result").html(html);
        }
    });
    return false;
}

function graduate_report() {
    var programmeID = document.getElementById("programmeID").value;
    var batchID = document.getElementById("batchID").value;
    var admissionYearID = document.getElementById("admissionYearID").value;
    var dataString = 'programmeID=' + programmeID + '&academicYearID=' + admissionYearID + '&batchID=' + batchID;
    $('#myPleaseWait').modal('show');
    $.ajax({
        type: "POST",
        url: "ajax_graduate_list.php",
        data: dataString,
        cache: false,
        success: function(html) {
            $('#myPleaseWait').modal('hide');
            $("#result").html(html);
        }
    });
    return false;
}


function view_supp_result() {
    var programmeID = document.getElementById("programID").value;
    var studyYear = document.getElementById("studyYear").value;
   /* var academicYearID = document.getElementById("academicYearID").value;*/
    var semesterID = document.getElementById("semesterID").value;
    var batchID = document.getElementById("batchID").value;
    var dataString = 'programmeID=' + programmeID + '&studyYear=' + studyYear + '&semesterID=' + semesterID + '&batchID=' + batchID;
    $('#myPleaseWait').modal('show');
    $.ajax({
        type: "POST",
        url: "ajax_view_supp_report.php",
        data: dataString,
        cache: false,
        success: function(html) {
            $('#myPleaseWait').modal('hide');
            $("#result").html(html);
        }
    });
    return false;
}

function view_special_result() {
    var programmeID = document.getElementById("programID").value;
    var studyYear = document.getElementById("studyYear").value;
    var academicYearID = document.getElementById("academicYearID").value;
    var batchID = document.getElementById("batchID").value;
    var dataString = 'programmeID=' + programmeID + '&studyYear=' + studyYear + '&academicYearID=' + academicYearID + '&batchID=' + batchID;
    $('#myPleaseWait').modal('show');
    $.ajax({
        type: "POST",
        url: "ajax_view_special_report.php",
        data: dataString,
        cache: false,
        success: function(html) {
            $('#myPleaseWait').modal('hide');
            $("#result").html(html);
        }
    });
    return false;
}