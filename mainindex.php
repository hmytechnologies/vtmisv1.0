<?php
//foreach($_SESSION['roleID'] as $role=>$user_role) {
$roleID=$_SESSION['roleID'];
    switch ((isset($_GET['sp']) ? $_GET['sp'] : '')) {
        //config. system
        case 'sysconf':
            if(in_array(7,$roleID)==true || in_array(9,$roleID)==true) {
                include('system_configurations.php');
            }
            else {
                include('unauthized.php');
            }
            break;

        case 'academicyear':
            if(in_array(7,$roleID)==true) {
                include('academicyear.php');
            }
            else {
                include('unauthized.php');
            }
            break;

        case 'departments':
            if(in_array(7,$roleID)==true) {
                include('department.php');
            }else {
                include('unauthized.php');
            }
            break;

        case 'edit_campus':
            if(in_array(7,$roleID)==true) {
                include('edit_campus.php');
            }else {
                include('unauthized.php');
            }
            break;

        case 'edit_school':
            if(in_array(7,$roleID)==true) {
                include('edit_school.php');
            }else {
                include('unauthized.php');
            }
            break;

        case 'plevels':
            if(in_array(7,$roleID)==true) {
                include('programmelevels.php');
            }
            else {
                include('unauthized.php');
            }
            break;

        case 'programmes':
            if(in_array(7,$roleID)==true) {
                include('programme.php');
            }
            else {
                include('unauthized.php');
            }
            break;

        case 'administrative':
            if(in_array(7,$roleID)==true) {
                include('administrative.php');
            }else {
                include('unauthized.php');
            }
            break;

            
        

        /*case 'plevels':
            if(in_array(7,$roleID)==true) {
                include('programmelevels.php');
            }
            else {
                include('unauthized.php');
            }
            break;

        case 'programmes':
            if(in_array(7,$roleID)==true) {
                include('programme.php');
            }
            else {
                include('unauthized.php');
            }
            break;*/

        case 'grading_system':
            if(in_array(7,$roleID)==true) {
                include('grading_system.php');
            }else {
                include('unauthized.php');
            }
            break;


        case 'gpa_system':
            if(in_array(7,$roleID)==true) {
                include('gpa_system.php');
            }
            else {
                include('unauthized.php');
            }
            break;

        case 'misc_setting':
            if(in_array(7,$roleID)==true) {
                include('misc_setting.php');
            }
            else {
                include('unauthized.php');
            }
            break;


        case 'pcurricullum':
           // if(in_array(4,$roleID)==true || in_array(7,$roleID)==true || in_array(9,$roleID)==true) {
                include('pcurriculum.php');
            /*}else {
                include('unauthized.php');
            }*/
            break;


        //Semester Configuration
        case 'semester_setting':
            include('semester_setting.php');
            break;

    case 'final_report':
        include('final_report.php');
        break;

        case 'semester_date_setting':
            include('semester_date_setting.php');
            break;


        case 'annual_date_setting':
            include('annual_date_setting.php');
            break;



        case 'student_list':
            include('student_list.php');
            break;


        case 'student_course_list':
            include('student_course_list.php');
            break;


        case 'student_administration':
            include('student_administration.php');
            break;


        case 'course':
            include('courses.php');
            break;

        case 'pmapping':
            include('programming_mapping.php');
            break;

        //Registration Form
        case 'rform':
            include('registernewstudent.php');
            break;

        case 'new_student':
            include('registration_new_form.php');
            break;

        case 'academic_calendar':
            include('academic_calendar.php');
            break;

        //Student form
        case 'studentform':
            include('registration_form.php');
            break;


        case 'edit_student':
            //include('edit_student.php');
            //include('registration_form.php');
            include('student_registration.php');
            break;

            case 'generate_academic_advisor':
                include('generate_academic_advisor.php');
                break;

        case 'view_student_info':
           include('viewstudentinfo.php');
            break;



        case 'course_page':
            include('coursepage.php');
            break;


        case 'norminalroll':
            include('nominalroll.php');
            break;

        case 'searchstudents':
            include('student_search.php');
            break;




        case 'instructor_course_setting':
            include('instructor_course_setting.php');
            break;

        case 'semester_course':
            include('semester_course.php');
            break;

        case 'instructor':
            include('instructor.php');
            break;

        case 'instructor_course':
            include('instructor_course.php');
            break;


        case 'edit_instructor':
            include('edit_instructor.php');
            break;

        case 'view_instructor':
            include('view_instructor.php');
            break;

        case 'instructor_search':
            include('lecturer_search.php');
            break;

        case 'student_register':
            include('studentregister.php');
            break;


        case 'feestype':
            include('feestype.php');
            break;

        case 'programmefees':
            include('programmefees.php');
            break;

        case 'hostelfees':
            include('hostelfees.php');
            break;

        case 'other_fees':
            include('miscellaneous_fees.php');
            break;

        case 'hostel_fees':
            include('hostel_fees.php');
            break;

        case 'addnewprogrammefees':
            include('addnewprogrammefees.php');
            break;


        case 'addnewhostelfees':
            include('addnewhostelfees.php');
            break;



            case 'addnewprogramme':
                include('addnewprogramme.php');
                break;

        case 'programmefeesall':
            include('programmefeesall.php');
            break;


        case 'exam_category_setting':
            include('exam_category_setting.php');
            break;

        //Edit Data
        case 'edit_user':
            include('edit_user.php');
            break;
        case 'edit_department':
            include('edit_department.php');
            break;

        case 'edit_levels':
            include('edit_levels.php');
            break;

        case 'edit_programme':
            include('edit_programme.php');
            break;

        case 'edit_staff':
            include('edit_staff.php');

            break;

        case 'edit_programme_major':
            include('edit_programme_major.php');
            break;


        case 'edit_course':
            include('edit_course.php');
            break;


        //Manage Students

        case 'studentregister':
            include('studentregister.php');
            break;

        case 'addnewstudent':
            include('addnewstudent.php');
            break;

        case 'studentlist':
            include('studentlist.php');
            break;


        case 'view_student_profile':
            //include('student_profile.php');
            include('viewstudentdetails.php');
            break;


        case 'viewstudent':
            include('viewstudent.php');
            break;


        case 'addstudentcourse':
            include('addstudentcourse.php');
            break;

        case 'addfasttrackcourse':
            include('addfasttrackcourse.php');
            break;

        case 'assign_course':
            include('assign_course.php');
            break;

        case 'viewstudentlist':
            include('viewstudentlist.php');
            break;


        case 'assign_user':
            include('assign_user.php');
            break;


        case 'viewassignedcourse':
            include('viewassignedcourse.php');
            break;

        case 'addstudentclass':
            include('addstudentclass.php');
            break;

        case 'courseinfo':
            include('courseinfo.php');
            break;

        case 'pickstudent':
            include('pickstudent.php');
            break;

        case 'academic_advisor':
            include('academic_advisor.php');
            break;


        case 'viewcoursedetails':
            include('viewcoursedetails.php');
            break;

        case 'viewcourse2':
            include('viewcourseinstructor.php');
            break;

        /*case 'courselist':
            include('courselist.php');
            break;*/

        case 'courselist':
            include('semester_course_list.php');
            break;

        case 'course_register':
            include('course_register.php');
            break;

        case 'register_exam':
            include('register_exam.php');
            break;

    case 'exam_list':
        include('exam_list.php');
        break;


        case 'exam_number':
            include('exam_number_list.php');
            break;


        //Examination

        case 'exam_setting':
            include('exam_setting.php');
            break;

        case 'addresult':
            if((in_array(7,$roleID)==true)|| (in_array(4,$roleID)==true) ||(in_array(3,$roleID)==true) ||(in_array(9,$roleID)==true)) {
                include('marksmanagement.php');
            }
            else
            {
                include('unauthized.php');
            }
            break;


        case 'addassessment':
            if((in_array(7,$roleID)==true)|| (in_array(4,$roleID)==true) ||(in_array(3,$roleID)==true) ||(in_array(9,$roleID)==true)) {
                include('assessment_management.php');
            }
            else
            {
                include('unauthized.php');
            }
            break;

        case 'add_score':
            include('add_score.php');
            break;

        case 'edit_score':
            include('edit_score.php');
            break;

        case 'import_score':
            include('add_bulk_score.php');
            break;

        case 'exam_schedule':
            include('exam_schedule.php');
            break;

        case 'register_course':
            include('register_course.php');
            break;


        case 'supp_special':
            if((in_array(7,$roleID)==true)|| (in_array(4,$roleID)==true)) {
                include('supp_special_management.php');
            }
            else
            {
                include('unauthized.php');
            }
            break;


        case 'add_score_sup':
            include('add_score_sup.php');
            break;


        case 'add_score_special':
            include('add_score_special.php');
            break;

        case 'search_student_result':
            include('student_search_result.php');
            break;

        case 'view_score':
            include('view_score.php');
            break;



            case 'view_supp_score':
                include('view_supp_score.php');
                break;

        case 'view_special_score':
            include('view_special_score.php');
            break;


        /*case 'publish':
            include('publish.php');
            break;*/

        case 'publish':
            include('publish_unpublish.php');
            break;


        case 'study_progress':
            include('study_progress.php');
            break;

        case 'approve_graduands':
            if((in_array(7,$roleID)==true)) {
                include('approve_graduands.php');
            }
            else
            {
                include('unauthized.php');
            }
            break;

        case 'student_academic_reports':
            include('student_academic_reports.php');
            break;

        case 'print_result':
            include('print_result.php');
            break;

        case 'annual_result':
            include('annual_result.php');
            break;

        case 'supp_report':
            include('supp_report.php');
            break;

    case 'summary_report':
        include('summary_report.php');
        break;

        case 'special_report':
            include('special_report.php');
            break;

        case 'graduate_report':
            include('graduate_report.php');
            break;


        //Student Panel
        case 'mycourse':
            include('mycourse.php');
            break;

        /*case 'my_bill':
            include('my_bill.php');
            break;*/

        case 'payments':
            include('payments.php');
            break;


        case 'pay_now':
            include('process_student_payment.php');
            break;



            case 'payment_history':
                include('payment_history.php');
                break;



                case 'view_payment_history':
                    include('view_payment_history.php');
                    break;

        case 'programme_curricullum':
            include('programme_curricullum.php');
            break;

        case 'semister_registration':
            include('semester_registration.php');
            break;

        case 'complete_semester_registration':
            include('complete_semester_registration.php');
            break;
        case 'myprofile':
            include('myprofile.php');
            break;


        case 'exam_results':
            include('exam_results.php');
            break;


        case 'misc_date_setting':
            include('misc_date_setting.php');
            break;



        case 'courseworkview':
            include('student_course_work.php');
            break;


            case 'financial_assistant':
                include('financial_assistant.php');
                break;



                case 'bank_information':
                    include('bank_information.php');
                    break;


        //finance

        case 'process_payment':
            include('processpayment.php');
            break;


        case 'paymentinfo':
            include('paymentinfo.php');
            break;

        case 'student_payment_list':
            include('student_payment_list.php');
            break;

        case 'payment_setting':
            include('payment_setting.php');
            break;


        case 'student_payment':
            include('student_payment.php');
            break;



            case 'view_student_payment':
                include('view_student_payments.php');
                break;

        case 'student_discount':
            include('student_discount.php');
            break;


        case 'waiver':
            include('waiver.php');
            break;

        case 'upload_finance_file':
            include('upload_finance_file.php');
            break;


        //End of Student Panel


        //Reports

        case 'nactereport':
            include('nactereport.php');
            break;
        case 'transcript':
            include('candidate_transcript.php');
            break;

        case 'transcript_details':
            include('student_transcript_details.php');
            break;



            case 'commulative_points':
                include('commulative_points.php');
                break;

        //Head of Department

        case 'semester_setting_hod':
            include('semester_setting_hod.php');
            break;

        case 'department_course':
            include('department_course.php');
            break;




        //instructor

        case 'instructor_mycourse':
            include('instructor_my_course.php');
            break;


        case 'instructor_profile':
            include('instructor_profile.php');
            break;

        case 'my_lecturer':
            include('my_instructor.php');
            break;

        case 'hod_dean_info':
            include('hod_dean_info.php');
            break;



        case 'std_lecturer':
            include('student_my_instructor.php');
            break;


        case 'instructor_exam_results':
            include('instructor_exam_results.php');
            break;

        case 'view_course_work':
            include('view_course_work.php');
            break;

            case 'ass_conf':
                include('assessment_configuration.php');
                break;

        case 'marks_configuration':
            include('marks_configuration.php');
            break;

        case 'internal_marks':
            include('internal_marks.php');
            break;

        case 'internal_marks_view':
            include('internal_marks_view.php');
            break;

        case 'add_internal_marks':
            include('add_internal_result.php');
            break;

            case 'term_marks':
            include('term_marks.php');
            break;

        case 'add_term_marks':
            include('add_term_results.php');
            break;

        
    case 'import_term_score':
        include('import_term_results.php');
        break;

        
    case 'view_term_score':
        include('view_term_score.php');
        break;
        //admission officer


        case 'upload_file':
            include('uploaded_file.php');
            break;


        case 'api_registration':
            include('api_registration.php');
            break;


        case 'staffprofile':
            include('staffprofile.php');
            break;

        case 'st_profile':
            include('profile.php');
            break;

        case 'hrinformation':
            include('hrinformation.php');
            break;



            case 'transfer_student':
                include('transfer_student.php');
                break;

        //error page
        case 'error':
            include 'error.php';
            break;

        case 'changepwd':
            include('changepwd.php');
            break;



        //Administrator
        /*case 'user':
            include('viewusers.php');
            break;*/
       /* case 'addnewuser':
            include('addnewuser.php');
            break;*/
        case 'users':
            if(in_array(1,$roleID)==true) {
                include('viewusers.php');
            }else {
                include('unauthized.php');
            }
            break;

    case 'audit':
        if (in_array(1, $roleID) == true) {
            include('viewaudit.php');
        } else {
            include('unauthized.php');
        }
        break;


        case 'user_roles':
            if(in_array(1,$roleID)==true) {
                include('user_roles.php');
            }else {
                include('unauthized.php');
            }
            break;

        case 'assign_roles':
            if(in_array(1,$roleID)==true) {
                include('assign_roles.php');
            }
            else {
                include('unauthized.php');
            }
            break;
        case 'organization':
            if(in_array(1,$roleID)==true) {
                include('organization_info.php');
            }
            else {
                include('unauthized.php');
            }
            break;


            //center

        case 'center_reg':
            if(in_array(1,$roleID)==true) {
                include('center_registration.php');
            }
            else {
                include('unauthized.php');
            }
            break;




            case 'add_new_center':
                if(in_array(1,$roleID)==true) {
                    include('register_new_center.php');
                }
                else {
                    include('unauthized.php');
                }
                break;


        case 'center_programmes':
            if(in_array(1,$roleID)==true) {
                include('center_programme.php');
            }
            else {
                include('unauthized.php');
            }
            break;

        case 'center_setting':
            if(in_array(1,$roleID)==true) {
                include('center_setting.php');
            }
            else {
                include('unauthized.php');
            }
            break;

        case 'register_staff':
            if(in_array(1,$roleID)==true) {
                include('register_staff.php');
            }
            else {
                include('unauthized.php');
            }
            break;


        case 'exam_grading_setting':
            if(in_array(1,$roleID)==true) {
                include('exam_grading_setting.php');
            }
            else {
                include('unauthized.php');
            }
            break;

        case 'center_semester_course':
            //if(in_array(1,$roleID)==true) {
                include('center_semester_course.php');
           /* }
            else {
                include('unauthized.php');
            }*/
            break;



        case 'manage_staff':
            include('viewstaff.php');
            break;

            // case 'edit_staff':
            //     include('edit_staff.php');
            //     break;




            





            
        //default
        default:
            include('frontpage.php');
    }

?>