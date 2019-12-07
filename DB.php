<?php
/*
 * conn Class
 * This class is used for database related (connect, insert, update, and delete) operations
 * with PHP Data Objects (PDO)
 * @author    Massoud Hamad
 * @url       http://www.hmytechnologies.com
 */
require_once('dbconfig.php');
define('SALT_LENGTH', 9);
class DBHelper{

    private $conn;
    public function __construct(){
        
       $database = new Database();
        $conn = $database->dbConnection();
        $this->conn = $conn;
    }

    public function runQuery($sql)
    {
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }
    

    public function PwdHash($pwd, $salt = null)
    {
        if ($salt === null)
        {
            $salt = substr(md5(uniqid(rand(), true)), 0, SALT_LENGTH);
        }
        else 
        {
            $salt = substr($salt, 0, SALT_LENGTH);
        }
        return $salt . sha1($pwd . $salt);
    }

    /*
     * Returns rows from the database based on the conditions
     * @param string name of the table
     * @param array select, where, order_by, limit and return_type conditions
     */
    public function getRows($table,$conditions = array()){
        $sql = 'SELECT';
        $sql .= array_key_exists("select",$conditions)?$conditions['select']:'*';
        $sql .= ' FROM '.$table;
        if(array_key_exists("where",$conditions)){
            $sql .= ' WHERE ';
            $i = 0;
            foreach($conditions['where'] as $key => $value){
                $pre = ($i > 0)?' AND ':'';
                $sql .= $pre.$key." = '".$value."'";
                $i++;
            }
        }
        
        if(array_key_exists("order_by",$conditions)){
            $sql .= ' ORDER BY '.$conditions['order_by']; 
        }
        
        if(array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){
            $sql .= ' LIMIT '.$conditions['start'].','.$conditions['limit']; 
        }elseif(!array_key_exists("start",$conditions) && array_key_exists("limit",$conditions)){
            $sql .= ' LIMIT '.$conditions['limit']; 
        }
        
        $query = $this->conn->prepare($sql);
        $query->execute();
        
        if(array_key_exists("return_type",$conditions) && $conditions['return_type'] != 'all'){
            switch($conditions['return_type']){
                case 'count':
                    $data = $query->rowCount();
                    break;
                case 'single':
                    $data = $query->fetch(PDO::FETCH_ASSOC);
                    break;
                default:
                    $data = '';
            }
        }else{
            if($query->rowCount() > 0){
                $data = $query->fetchAll();
            }
        }
        return !empty($data)?$data:false;
    }
    
    /*
     * Insert data into the database
     * @param string name of the table
     * @param array the data for inserting into the table
     */
    public function insert($table,$data){
        try {
            if (!empty($data) && is_array($data)) {
                $columns = '';
                $values = '';
                $i = 0;
                if (!array_key_exists('createdDate', $data)) {
                    $data['createdDate'] = date("Y-m-d H:i:s");
                }
                if (!array_key_exists('modifiedDate', $data)) {
                    $data['modifiedDate'] = date("Y-m-d H:i:s");
                }

                if (!array_key_exists('createdBy', $data)) {
                    $data['createdBy'] = $_SESSION['user_session'];
                }

                /*if(!array_key_exists('pStatus',$data)){
                  $data['pStatus'] = 1;
              }*/

                $columnString = implode(',', array_keys($data));
                $valueString = ":" . implode(',:', array_keys($data));
                $sql = "INSERT INTO " . $table . " (" . $columnString . ") VALUES (" . $valueString . ")";
                $query = $this->conn->prepare($sql);
                foreach ($data as $key => $val) {
                    $query->bindValue(':' . $key, $val);
                }
                $insert = $query->execute();
                return $insert ? $this->conn->lastInsertId() : false;
            } else {
                return false;
            }
        }catch(PDOException $e)
        {
            echo $e->getMessage();
        }
    }
    
    /*
     * Update data into the database
     * @param string name of the table
     * @param array the data for updating into the table
     * @param array where condition on updating data
     */
    public function update($table,$data,$conditions){
        try {
            if (!empty($data) && is_array($data)) {
                $colvalSet = '';
                $whereSql = '';
                $i = 0;
                if (!array_key_exists('modifiedDate', $data)) {
                    $data['modifiedDate'] = date("Y-m-d H:i:s");
                }
                if (!array_key_exists('createdBy', $data)) {
                    $data['createdBy'] = $_SESSION['user_session'];
                }
                foreach ($data as $key => $val) {
                    $pre = ($i > 0) ? ', ' : '';
                    $colvalSet .= $pre . $key . "='" . $val . "'";
                    $i++;
                }
                if (!empty($conditions) && is_array($conditions)) {
                    $whereSql .= ' WHERE ';
                    $i = 0;
                    foreach ($conditions as $key => $value) {
                        $pre = ($i > 0) ? ' AND ' : '';
                        $whereSql .= $pre . $key . " = '" . $value . "'";
                        $i++;
                    }
                }
                $sql = "UPDATE " . $table . " SET " . $colvalSet . $whereSql;
                $query = $this->conn->prepare($sql);
                $update = $query->execute();
                return $update ? $query->rowCount() : false;
            } else {
                return false;
            }
        }catch(PDOException $e)
        {
            echo $e->getMessage();
        }
    }
    
    /*
     * Delete data from the database
     * @param string name of the table
     * @param array where condition on deleting data
     */
    public function delete($table,$conditions){
        $whereSql = '';
        if(!empty($conditions)&& is_array($conditions)){
            $whereSql .= ' WHERE ';
            $i = 0;
            foreach($conditions as $key => $value){
                $pre = ($i > 0)?' AND ':'';
                $whereSql .= $pre.$key." = '".$value."'";
                $i++;
            }
        }
        $sql = "DELETE FROM ".$table.$whereSql;
        $delete = $this->conn->exec($sql);
        return $delete?$delete:false;
    }


    public function doLogin($uname,$upass)
    {
        try
        {
/*            $stmt = $this->conn->prepare("SELECT u.userID,userName,password,status,roleID,departmentID FROM users u,userroles ur WHERE u.userID=ur.userID and userName=:uname and status=:st");*/
            $stmt = $this->conn->prepare("SELECT userID,username,password,status,departmentID FROM users  WHERE username=:uname");
            $stmt->execute(array(':uname'=>$uname));
            $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
            if($stmt->rowCount() > 0)
            {
                if($userRow['status'] == 1) {
                    if ($userRow['password'] === $this->PwdHash($upass, substr($userRow['password'], 0, 9))) {
                        $_SESSION['user_session'] = $userRow['userID'];
                        $_SESSION['department_session']=$userRow['departmentID'];
                        return true;
                    } else {
                        return false;
                    }
                }else
                {
                    return false;
                }
            }
        }
       catch(PDOException $e)
       {
           echo $e->getMessage();
       }
   }

    public function doMobileLogin($uname,$upass)
    {
        try
        {
            $stmt = $this->conn->prepare("SELECT u.userID,registrationNumber,password,u.status FROM users u,student s  WHERE u.userID=s.userID AND userName=:uname");
            $stmt->execute(array(':uname'=>$uname));
            $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
            if($stmt->rowCount() > 0)
            {
                if($userRow['status'] == 1) {
                    if ($userRow['password'] === $this->PwdHash($upass, substr($userRow['password'], 0, 9))) {
                    return $userRow;
                    } else {
                        return false;
                    }
                }else
                {
                    return false;
                }
            }
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
    }

 
   public function is_loggedin()
   {
      if(isset($_SESSION['user_session']))
      {
         return true;
      }
   }
 
   public function redirect($url)
   {
       header("Location: $url");
   }
 
   public function doLogout()
   {
        session_destroy();
        unset($_SESSION['user_session']);
        return true;
   }

   public function readSemesterSetting()
   {
        $query = $this->conn->prepare("SELECT academicYearID from academic_year where status=:sts");
        $query->execute(array(':sts'=>1));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }
    //getCurrentAcademicYear
    public function getCurrentAcademicYear()
    {
        $current_year=$this->getRows("academic_year",array('where'=>array('status'=>1)));
        if(!empty($current_year))
        {
            foreach($current_year as $cyear)
            {
                $academicYearID=$cyear['academicYearID'];
            }
        }
        return $academicYearID;
    }
    //get Single Record
    public function getData($table,$attrName,$id,$id2)
    {
        $query = $this->getRows($table,array('where'=>array($id=>$id2),' order_by'=>' courseID ASC'));
        if(!empty($query))
        {
            foreach ($query as  $q) {
                $attrName=$q[$attrName];
                return $attrName;
            }
        }
    }


    //get filtered Course Programme
    public function getCenterCourseProgramme($centerID,$ID,$academicYearID,$levelID)
    {

        $query = $this->conn->prepare("SELECT DISTINCT courseID,courseStatus from courseprogramme where  programmeID=:progID AND academicYearID=:acdID AND programmeLevelID=:plevel AND courseID NOT IN (SELECT courseID from center_programme_course where programmeID=:progrID and academicYearID=:acadID and programmeLevelID=:progLevelID AND centerID=:center)");
        $query->execute(array(':progID'=>$ID,':acdID'=>$academicYearID,':plevel'=>$levelID,':progrID'=>$ID,':acadID'=>$academicYearID,':progLevelID'=>$levelID,':center'=>$centerID));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }


    //get filtered Course Programme
     public function getCourseProgramme($ID,$academicYearID,$levelID)
    {
        
        $query = $this->conn->prepare("SELECT DISTINCT courseID,courseStatusID from programmemaping where  programmeID=:progID AND courseID NOT IN (SELECT courseID from courseprogramme where programmeID=:progrID and academicYearID=:acadID and programmeLevelID=:progLevelID)");
        $query->execute(array(':progID'=>$ID,':progrID'=>$ID,':acadID'=>$academicYearID,':progLevelID'=>$levelID));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    //get filtered Course Programme
    public function getCourseByMapping($departmentID,$ID,$bID,$stdY,$semID)
    {

        $query = $this->conn->prepare("SELECT p.courseID,studyYear,semesterID,courseStatusID from programmemaping p,course c where c.courseID=p.courseID AND c.departmentID=:deptID AND  programmeID=:progID AND p.courseID NOT IN (SELECT courseID from courseprogramme where programmeID=:progrID and batchID=:batID and studyYear=:study and semesterSettingID=:semID)");
        $query->execute(array(':deptID'=>$departmentID,':progID'=>$ID,':progrID'=>$ID,':batID'=>$bID,':study'=>$stdY,':semID'=>$semID));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    //get filtered Course Programme
     public function filterCourse($ID)
    {
        $query = $this->conn->prepare("SELECT * from course where status=:st AND courseID NOT IN (SELECT courseID from programmemaping where programmeID=:programmeID order by courseName)");
        $query->execute(array(':programmeID'=>$ID,'st'=>1));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function filterTrade($ID)
    {
        $query = $this->conn->prepare("SELECT * from programmes where status=:st AND programmeID NOT IN (SELECT programmeID from center_programme where centerRegistrationID=:centerID order by programmeID ASC)");
        $query->execute(array(':centerID'=>$ID,'st'=>1));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function getCenterProgramme($ID)
    {
        $query = $this->conn->prepare("SELECT DISTINCT programmeID from center_programme where centerRegistrationID=:centerID");
        $query->execute(array(':centerID'=>$ID));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }


    //get filtered Course Programme
     public function filterRecords($proID,$semID,$btID,$sYear,$regNumber)
     {
        
        $query = $this->conn->prepare("SELECT DISTINCT(courseID),courseStatus from courseprogramme where  programmeID=:programmeID AND semesterSettingID=:semisterID AND batchID=:batchID AND studyYear=:studyYear AND courseID NOT IN (SELECT courseID from student_course where regNumber=:rNumber)");
        $query->execute(array(':programmeID'=>$proID,':semisterID'=>$semID,':batchID'=>$btID,':studyYear'=>$sYear,':rNumber'=>$regNumber));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

     public function getExamResult($cID,$semID,$bID)
     {
        
        $query = $this->conn->prepare("SELECT DISTINCT(regNumber) from exam_result  where semesterSettingID=:semesterID  AND courseID=:courseID AND batchID=:batchID");
        
        $query->execute(array(':semesterID'=>$semID,':courseID'=>$cID,':batchID'=>$bID));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }
    
    
    public function getFinalResult($cID,$semID,$bID)
    {
        $query = $this->conn->prepare("SELECT DISTINCT(regNumber) from final_result  where semesterSettingID=:semesterID  AND courseID=:courseID AND batchID=:batchID");   
        $query->execute(array(':semesterID'=>$semID,':courseID'=>$cID,':batchID'=>$bID));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC))
        {
            $data[] = $row;
        }
        return $data;
    }

    public function getStudentProgramme($programmeID,$semID,$studyYear,$batchID,$acadID)
    {
        
        $query = $this->conn->prepare("SELECT DISTINCT(e.regNumber) from exam_result e,student s,student_study_year sy 
        where s.registrationNumber=e.regNumber 
        and s.registrationNumber =sy.regNumber 
        and s.programmeID=:progID 
        and s.batchID=:bid 
        and semesterSettingID=:semID 
        and sy.studyYear=:study 
        and sy.academicYearID=:academicID
        ORDER BY e.regNumber ASC");
        $query->execute(array(':progID'=>$programmeID,':bid'=>$batchID,':semID'=>$semID,':study'=>$studyYear,':academicID'=>$acadID));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }
    
    public function getStudentAnnualProgramme($programmeID,$academicYearID,$studyYear,$batchID)
    {
        $query = $this->conn->prepare("SELECT DISTINCT(e.regNumber) FROM exam_result e,student s,student_study_year sy WHERE s.registrationNumber = e.regNumber AND s.registrationNumber = sy.regNumber AND sy.academicYearID=:acadID AND s.programmeID = :progID AND s.batchID = :bid AND sy.studyYear = :study");
        $query->execute(array(':acadID'=>$academicYearID,':progID'=>$programmeID,':bid'=>$batchID,':study'=>$studyYear));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    /*public function getStudentSuppProgramme($programmeID,$academicYearID,$studyYear,$batchID)
    {
        $query = $this->conn->prepare("SELECT DISTINCT(e.regNumber) FROM exam_result e,student s,student_study_year sy,semester_setting ss WHERE s.registrationNumber = e.regNumber AND s.registrationNumber = sy.regNumber AND ss.semesterSettingID = e.semesterSettingID AND s.registrationNumber = sy.regNumber AND ss.academicYearID=:acadID AND s.programmeID = :progID AND s.batchID = :bid AND sy.studyYear = :study AND examCategoryID=:ecatID");
        $query->execute(array(':acadID'=>$academicYearID,':progID'=>$programmeID,':bid'=>$batchID,':study'=>$studyYear,':ecatID'=>3));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }*/

    public function getStudentSuppProgramme($programmeID,$semesterID,$studyYear,$batchID)
    {
        $query = $this->conn->prepare("SELECT DISTINCT(e.regNumber) FROM exam_result e,student s,student_study_year sy,semester_setting ss WHERE s.registrationNumber = e.regNumber AND s.registrationNumber = sy.regNumber AND ss.semesterSettingID = e.semesterSettingID AND s.registrationNumber = sy.regNumber AND ss.semesterSettingID=:semID AND s.programmeID = :progID AND s.batchID = :bid AND sy.studyYear = :study AND examCategoryID=:ecatID");
        $query->execute(array(':semID'=>$semesterID,':progID'=>$programmeID,':bid'=>$batchID,':study'=>$studyYear,':ecatID'=>3));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function getStudentSpecialProgramme($programmeID,$academicYearID,$studyYear,$batchID)
    {
        $query = $this->conn->prepare("SELECT DISTINCT(en.regNumber) FROM final_result f,student s,student_study_year sy,semester_setting ss,exam_number en WHERE s.registrationNumber = en.regNumber AND s.registrationNumber = sy.regNumber AND en.examNumber=f.examNumber AND ss.semesterSettingID = f.semesterSettingID AND s.registrationNumber = sy.regNumber AND ss.academicYearID=:acadID AND s.programmeID = :progID AND s.batchID = :bid AND sy.studyYear = :study AND examCategoryID=:ecatID");
        $query->execute(array(':acadID'=>$academicYearID,':progID'=>$programmeID,':bid'=>$batchID,':study'=>$studyYear,':ecatID'=>4));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }


    public function getExamScore($semID,$acadID,$cID,$stdID,$exmCatID)
    {
        $query = $this->conn->prepare("SELECT exam_score from exam_result  where academic_year_id=:academicYearID and semister_id=:semisterID and course_id=:courseID and student_id=:studentID and exam_category_id=:examCategoryID");
        $query->execute(array(':academicYearID'=>$acadID,':semisterID'=>$semID,':courseID'=>$cID,':studentID'=>$stdID,'examCategoryID'=>$exmCatID));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function getDistinctCourse($acadID,$semID)
    {
        $query = $this->conn->prepare("SELECT DISTINCT(courseID) from student_course where academicYearID=:academicYearID AND semisterID=:semisterID");
        $query->execute(array(':academicYearID'=>$acadID,':semisterID'=>$semID));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    /*public function getCourseCredit($progID,$semesterID,$studyID,$acadeID)
    {
        
        $query = $this->conn->prepare("SELECT DISTINCT(e.courseID),courseName,courseCode,units from student s,course c,exam_result e,student_study_year sy
        where s.registrationNumber=e.regNumber 
        AND s.registrationNumber=sy.regNumber
        AND s.programmeID=:progID 
        AND c.courseID=e.courseID 
        AND semesterSettingID=:semID
        AND sy.studyYear=:study
        AND sy.academicYearID=:acadID");
        $query->execute(array('progID'=>$progID,':semID'=>$semesterID,':study'=>$studyID,':acadID'=>$acadeID));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }*/

    public function getCourseCredit($progID,$semesterID,$studyID,$acadeID)
    {

        $query = $this->conn->prepare("SELECT DISTINCT(e.courseID),courseName,courseCode,units,courseTypeID from student s,courseprogramme cp,course c,exam_result e,student_study_year sy
        where s.registrationNumber=e.regNumber 
        AND s.registrationNumber=sy.regNumber
        AND c.courseID=cp.courseID
        AND cp.programmeID=s.programmeID
        AND cp.programmeID=:progID 
        AND c.courseID=e.courseID 
        AND e.semesterSettingID=:semID
        AND sy.studyYear=:study
        AND sy.academicYearID=:acadID");
        $query->execute(array('progID'=>$progID,':semID'=>$semesterID,':study'=>$studyID,':acadID'=>$acadeID));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

   /* public function getCourseCredit($progID,$semesterID)
    {

        $query = $this->conn->prepare("SELECT DISTINCT(e.courseID),courseName,courseCode,units from student s,course c,exam_result e where s.registrationNumber=e.regNumber AND s.programmeID=:progID AND c.courseID=e.courseID AND semesterSettingID=:semID");
        $query->execute(array('progID'=>$progID,':semID'=>$semesterID));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }*/
    
    public function getAnnualCourseCredit($progID,$academicYearID)
    {
        
        $query = $this->conn->prepare("SELECT DISTINCT
    (e.courseID),courseName,courseCode,units,e.semesterSettingID
FROM
    student s,
    course c,
    exam_result e,
    semester_setting ss
WHERE
    s.registrationNumber = e.regNumber
        AND s.programmeID = :progID
        AND c.courseID = e.courseID
        AND ss.semesterSettingID=e.semesterSettingID
        AND ss.academicYearID=:acadID");
        $query->execute(array('progID'=>$progID,':acadID'=>$academicYearID));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    /*public function getAnnualSuppCourseCredit($regNumber,$progID,$academicYearID)
    {

        $query = $this->conn->prepare("SELECT DISTINCT
    (e.courseID),courseName,courseCode,units,e.semesterSettingID
FROM
    student s,
    course c,
    exam_result e,
    semester_setting ss
WHERE
    s.registrationNumber = e.regNumber
        AND s.programmeID = :progID
        AND c.courseID = e.courseID
        AND ss.semesterSettingID=e.semesterSettingID
        AND ss.academicYearID=:acadID
        AND examCategoryID=:ecID
        AND s.registrationNumber=e.regNumber
        AND e.regNumber=:regno");
        $query->execute(array('progID'=>$progID,':acadID'=>$academicYearID,':ecID'=>3,':regno'=>$regNumber));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }*/

    public function getSuppCourseCredit($regNumber,$progID,$semesterID)
    {

        $query = $this->conn->prepare("SELECT DISTINCT
    (e.courseID),courseName,courseCode,units,e.semesterSettingID
FROM
    student s,
    course c,
    exam_result e,
    semester_setting ss
WHERE
    s.registrationNumber = e.regNumber
        AND s.programmeID = :progID
        AND c.courseID = e.courseID
        AND ss.semesterSettingID=e.semesterSettingID
        AND ss.semesterSettingID=:semID
        AND examCategoryID=:ecID
        AND s.registrationNumber=e.regNumber
        AND e.regNumber=:regno");
        $query->execute(array('progID'=>$progID,':semID'=>$semesterID,':ecID'=>3,':regno'=>$regNumber));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }


    public function getAnnualSpecialCourseCredit($regNumber,$progID,$academicYearID)
    {

        $query = $this->conn->prepare("SELECT DISTINCT
    (f.courseID),courseName,courseCode,units,f.semesterSettingID
FROM
    student s,
    course c,
    final_result f,
    semester_setting ss,
     exam_number en
WHERE
    s.registrationNumber = en.regNumber
        AND en.examNumber=f.examNumber
        AND s.programmeID = :progID
        AND c.courseID = f.courseID
        AND ss.semesterSettingID=f.semesterSettingID
        AND ss.academicYearID=:acadID
        AND examCategoryID=:ecID
        AND en.regNumber=:regno");
        $query->execute(array('progID'=>$progID,':acadID'=>$academicYearID,':ecID'=>4,':regno'=>$regNumber));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }


    public function getStudentCourseCredit($regNumber)
    {

        $query = $this->conn->prepare("SELECT DISTINCT
    (sc.courseID),courseName,courseCode,units,sc.semesterSettingID
FROM
    student s,
    course c,
    student_course sc
WHERE
    s.registrationNumber = sc.regNumber
    AND sc.regNumber=:regNumber
        AND c.courseID = sc.courseID");
        $query->execute(array(':regNumber'=>$regNumber));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }
    
    public function getStudentExamCourse($regNumber,$semesterID,$courseID)
    {
        
        $query = $this->conn->prepare("SELECT DISTINCT
    (e.courseID),courseName,courseCode,units,e.semesterSettingID
FROM
    student s,
    course c,
    exam_result e,
    semester_setting ss
WHERE
    s.registrationNumber = e.regNumber
        AND e.regNumber=:regNo
        AND c.courseID = e.courseID
        AND e.courseID=:cid
        AND ss.semesterSettingID=e.semesterSettingID
        AND e.semesterSettingID=:semID");
        $query->execute(array(':cid'=>$courseID,':regNo'=>$regNumber,':semID'=>$semesterID));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function getStudentExamCourseBySitting($regNumber,$semesterID,$courseID,$examSiting)
    {

        $query = $this->conn->prepare("SELECT DISTINCT
    (e.courseID),courseName,courseCode,units,e.semesterSettingID
FROM
    student s,
    course c,
    exam_result e,
    semester_setting ss
WHERE
    s.registrationNumber = e.regNumber
        AND e.regNumber=:regNo
        AND c.courseID = e.courseID
        AND e.courseID=:cid
        AND ss.semesterSettingID=e.semesterSettingID
        AND e.semesterSettingID=:semID
        AND e.examSitting=:esitt");
        $query->execute(array(':cid'=>$courseID,':regNo'=>$regNumber,':semID'=>$semesterID,':esitt'=>$examSiting));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function isFieldExist($table,$field,$field2)
    {
      $query=$this->getRows($table,array('where'=>array($field=>$field2),'order_by'=>$field.' ASC'));
        if(!empty($query))
        {
            return true;
        }
        else
        {
          return false;
        }
    }

    /*public function getGrade($semesterID,$courseID,$regNumber,$examCategoryID)
    {
        $examScore="";
        $score=$this->getRows('exam_result',array('where'=>array('regNumber'=>$regNumber,'semesterSettingID'=>$semesterID,'courseID'=>$courseID,'examCategoryID'=>$examCategoryID),' order_by'=>'regNumber ASC'));
        if(!empty($score))
        {
          foreach ($score as $sc)
          {
                $examScore=$sc['examScore'];
          }
        }
        else
        {
                $examScore="";
        }
        return $examScore;
 }*/

    public function getGrade($semesterID,$courseID,$regNumber,$examCategoryID)
    {

        $query=$this->conn->prepare("SELECT examScore from student_course sc,exam_result er where sc.courseID=er.courseID AND sc.regNumber=er.regNumber and er.regNumber=:rNumber and er.semesterSettingID=:sem and sc.semesterSettingID=:seme and er.courseID=:cid and examCategoryID=:ecatID");
        $query->execute(array(':rNumber'=>$regNumber,':sem'=>$semesterID,':seme'=>$semesterID,':cid'=>$courseID,':ecatID'=>$examCategoryID));
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $score=$row['examScore'];
        $examScore="";
        //$score=$this->getRows('exam_result',array('where'=>array('regNumber'=>$regNumber,'semesterSettingID'=>$semesterID,'courseID'=>$courseID,'examCategoryID'=>$examCategoryID),' order_by'=>'regNumber ASC'));

        if(!empty($score))
        {
                $examScore=$score;
        }
        else
        {
            $examScore="";
        }
        return $examScore;
    }


    public function getCourseExamScore($semesterID,$courseID,$batchID,$examCategoryID)
    {

        $query=$this->conn->prepare("SELECT regNumber from student_course sc,exam_result er where sc.courseID=er.courseID AND sc.regNumber=er.regNumber and er.semesterSettingID=sc.semesterSettingID and er.semesterSettingID=:sem and er.courseID=:cid and examCategoryID=:ecatID");
        $query->execute(array(':sem'=>$semesterID,':seme'=>$semesterID,':cid'=>$courseID,':ecatID'=>$examCategoryID));
        $row = $query->fetch(PDO::FETCH_ASSOC);

        $score=$row['examScore'];


       /* if(!empty($score))
        {
            $examScore=$score;
        }
        else
        {
            $examScore="";
        }
        return $examScore;*/
    }

 public function calculateTotal($cwk,$sfe,$sup,$spc,$prj,$pt)
 {
    if(!empty($sup))
    {
        $tmarks=$sup;
    }
    else if(!empty($spc))
    {
        $tmarks=$spc+$cwk;
    }
    else if(!empty($pt))
    {
        $tmarks=$pt;
    }
    else if(!empty($prj))
    {
        $tmarks=$prj;
    }
    else {
        if(empty($cwk))
        {
            $tmarks=$sfe;
        }
        else if(empty($sfe))
        {
            $tmarks=$cwk;
        }
        else
        {
            $tmarks=$cwk+$sfe;
        }
       }
       return round($tmarks);
 }

    public function calculateTotalResults($cwk,$sfe,$spc,$prj,$pt)
    {
        if(!empty($spc))
        {
            $tmarks=$spc+$cwk;
        }
        else if(!empty($pt))
        {
            $tmarks=$pt;
        }
        else if(!empty($prj))
        {
            $tmarks=$prj;
        }
        else {
            if(empty($cwk))
            {
                $tmarks=$sfe;
            }
            else if(empty($sfe))
            {
                $tmarks=$cwk;
            }
            else
            {
                $tmarks=$cwk+$sfe;
            }
        }
        return round($tmarks);
    }

    public function getExamCategoryMark($exam_category,$regNumber)
    {
        $programmeLevelID=$this->getProgrammeLevelID($regNumber);
        $exam_category_marks=$this->getRows("exam_category_setting",array('where'=>array('examCategoryID'=>$exam_category,'programmeLevelID'=>$programmeLevelID)));
        $gradeOutput="";
        if(!empty($exam_category_marks))
        {
            foreach($exam_category_marks as $gd)
            {
                $passMark=$gd['passMark'];
                $gradeOutput=$passMark;
            }
        }
        else
        {
            $gradeOutput=null;
        }

        return $gradeOutput;
    }

    public function getExamCategoryMaxMark($exam_category,$regNumber)
    {
        $programmeLevelID=$this->getProgrammeLevelID($regNumber);
        $exam_category_marks=$this->getRows("exam_category_setting",array('where'=>array('examCategoryID'=>$exam_category,'programmeLevelID'=>$programmeLevelID)));
        $gradeOutput="";
        if(!empty($exam_category_marks))
        {
            foreach($exam_category_marks as $gd)
            {
                $maxMark=$gd['mMark'];
                $gradeOutput=$maxMark;
            }
        }
        else
        {
            $gradeOutput=null;
        }

        return $gradeOutput;
    }

/*
    public function calculateGrade($cwk,$sfe,$sup,$spc,$prj,$pt)
    {
        $tmarks=$this->calculateTotal($cwk,$sfe,$sup,$spc,$prj,$pt);
        if(!empty($sup))
        {
            if($tmarks>=50)
                $grade="C";
            else
                $grade="D";
        }
        else if(!empty($pt))
        {
            if($tmarks>=80)
            {
                $grade="A";
            }else if($tmarks>=65)
            {
                $grade="B";
            }else if($tmarks>=50)
            {
                $grade="C";
            }else if($tmarks>=40)
            {
                $grade="D";
            }else
            {
                $grade="F";
            }
        }
        else if(!empty($prj))
        {
            if($tmarks>=80)
            {
                $grade="A";
            }else if($tmarks>=65)
            {
                $grade="B";
            }else if($tmarks>=50)
            {
                $grade="C";
            }else if($tmarks>=40)
            {
                $grade="D";
            }else{
                $grade="F";
            }
        }
        else if(empty($cwk)||empty($sfe))
        {
            $grade="I";
        }
        else
        {
            if($tmarks>=80)
            {
                $grade="A";
            }else if($tmarks>=65)
            {
                $grade="B";
            }else if($tmarks>=50)
            {
                $grade="C";
            }else if($tmarks>=40)
            {
                $grade="D";
            }else{
                $grade="F";
            }
        }
        return $grade;
    }*/

    public function getMarksID($regNumber,$cwk,$sfe,$sup,$spc,$prj,$pt)
    {
        $programmeLevelID=$this->getProgrammeLevelID($regNumber);
        $tmarks=$this->calculateTotal($cwk,$sfe,$sup,$spc,$prj,$pt);
        $grade=$this->getRows("grades",array('where'=>array('programmeLevelID'=>$programmeLevelID)));
        $gradeOutput="";
        if(!empty($grade))
        {
            foreach($grade as $gd)
            {
                $startMark=$gd['startMark'];
                $endMark=$gd['endMark'];
                $gradeID=$gd['gradeID'];

                if($tmarks>=$endMark &&  $tmarks<=$startMark)
                {
                    $gradeOutput=$gradeID;
                }
            }
        }
        else
        {
            //$gradeOutput=$this->calculateGrade($cwk,$sfe,$sup,$spc,$prj,$pt);
            $gradeOutput=null;
        }

        return $gradeOutput;
    }

    public function getMarksOutputID($regNumber,$cwk,$sfe,$spc,$prj,$pt)
    {
        $programmeLevelID=$this->getProgrammeLevelID($regNumber);
        $tmarks=$this->calculateTotalResults($cwk,$sfe,$spc,$prj,$pt);
        $grade=$this->getRows("grades",array('where'=>array('programmeLevelID'=>$programmeLevelID)));
        $gradeOutput="";
        if(!empty($grade))
        {
            foreach($grade as $gd)
            {
                $startMark=$gd['startMark'];
                $endMark=$gd['endMark'];
                $gradeID=$gd['gradeID'];

                if($tmarks>=$endMark &&  $tmarks<=$startMark)
                {
                    $gradeOutput=$gradeID;
                }
            }
        }
        else
        {
            //$gradeOutput=$this->calculateGrade($cwk,$sfe,$sup,$spc,$prj,$pt);
            $gradeOutput=null;
        }

        return $gradeOutput;
    }

 public function calculateGrade($regNumber,$cwk,$sfe,$sup,$spc,$prj,$pt)
 {
    $tmarks=$this->calculateTotal($cwk,$sfe,$sup,$spc,$prj,$pt);
    $gradeID=$this->getMarksID($regNumber,$cwk,$sfe,$sup,$spc,$prj,$pt);
    if(!empty($sup))
    {
        $passMark=$this->getExamCategoryMark(3,$regNumber);
         if($tmarks>=$passMark)
             $grade="C";
         else
             $grade="D";
    }
    else if(!empty($pt))
    {
        $passMark=$this->getExamCategoryMark(6,$regNumber);
        if($tmarks>=$passMark)
            $grade=$this->getData("grades","gradeCode","gradeID",$gradeID);
        else
            $grade="D";
    }
    else if(!empty($prj))
    {
        $passMark=$this->getExamCategoryMark(5,$regNumber);
        if($tmarks>=$passMark)
            $grade=$this->getData("grades","gradeCode","gradeID",$gradeID);
        else
            $grade="D";
    }
    /*else if(empty($cwk)||empty($sfe))
    {
        $grade="N"; //No marks found for coursework or final exam
    }*/
    else if(empty($cwk))
    {
        $grade="N"; //Repeat Course
    }
    else if(empty($sfe))
    {
        //$status=$db->getMarksStatus($)
        //get status either A1 or A0,if A0 then Carry Over otherwise special exam
    }
    else
    {
        $passCourseMark=$this->getExamCategoryMark(1,$regNumber);
        $passFinalMark=$this->getExamCategoryMark(2,$regNumber);
        if($cwk<$passCourseMark)
            $grade="I"; //Incomplete CourseWork-Course Repeat
        else if($sfe<$passFinalMark) {
            $grade="N1"; //Marks is not sufficient -Supplementary or Course Repeat is depend on university policy
        }
        else {
            $grade = $this->getData("grades", "gradeCode", "gradeID", $gradeID);
        }
    }
    return $grade;
 }

    public function calculateGradeFirstSit($regNumber,$cwk,$sfe,$spc,$prj,$pt)
    {
        $tmarks=$this->calculateTotalResults($cwk,$sfe,$spc,$prj,$pt);
        $gradeID=$this->getMarksOutputID($regNumber,$cwk,$sfe,$spc,$prj,$pt);
        if(!empty($pt))
        {
            $passMark=$this->getExamCategoryMark(6,$regNumber);
            if($tmarks>=$passMark)
                $grade=$this->getData("grades","gradeCode","gradeID",$gradeID);
            else
                $grade="D";
        }
        else if(!empty($prj))
        {
            $passMark=$this->getExamCategoryMark(5,$regNumber);
            if($tmarks>=$passMark)
                $grade=$this->getData("grades","gradeCode","gradeID",$gradeID);
            else
                $grade="D";
        }
        /*else if(empty($cwk)||empty($sfe))
        {
            $grade="N"; //No marks found for coursework or final exam
        }*/
        else if(empty($cwk))
        {
            $grade="N"; //Repeat Course
        }
        else if(empty($sfe))
        {
            //$status=$db->getMarksStatus($)
            //get status either A1 or A0,if A0 then Carry Over otherwise special exam
        }
        else
        {
            $passCourseMark=$this->getExamCategoryMark(1,$regNumber);
            $passFinalMark=$this->getExamCategoryMark(2,$regNumber);
            if($cwk<$passCourseMark)
                $grade="I"; //Incomplete CourseWork-Course Repeat
            else if($sfe<$passFinalMark) {
                $grade="N1"; //Marks is not sufficient -Supplementary or Course Repeat is depend on university policy
            }
            else {
                $grade = $this->getData("grades", "gradeCode", "gradeID", $gradeID);
            }
        }
        return $grade;
    }
 
 public function courseRemarks($regNumber,$cwk,$sfe,$sup,$spc,$prj,$pt)
 {
        //$gradeID=$this->getMarksID($regNumber,$cwk,$sfe,$sup,$spc,$prj,$pt);
        //$grade=$this->getData("grades","gradeCode","gradeID",$gradeID);
        $grade= $this->calculateGrade($regNumber,$cwk, $sfe, $sup, $spc, $prj, $pt);
        if($grade=="A" || $grade=="B" ||$grade=="C" ||$grade=="B+")
             $remarks="PASS";
        /*else if($grade=="I")
             $remarks="INCOMPLETE";*/
        else if($grade=="I")
            $remarks="COURSE REPEAT";
        else if($grade=="F")
            $remarks="COURSE REPEAT";
        else if($grade=="E")
            $remarks="SUPP";
        else if($grade=="N")
            $remarks="COURSE REPEAT";
        else if($grade=="A1")
            $remarks="SPECIAL";
        else if($grade=="A0")
            $remarks="COURSE REPEAT";
        else if($grade=="D")
        {
            if($sup>0)
                $remarks="COURSE REPEAT";
            else
                $remarks="SUPP";
        }
        return $remarks;
 }

    /*public function courseRemarks($cwk,$sfe,$sup,$spc,$prj,$pt)
    {
        $tmarks=$this->calculateTotal($cwk, $sfe, $sup, $spc, $prj, $pt);
        $grade= $this->calculateGrade($cwk, $sfe, $sup, $spc, $prj, $pt);
        if($grade=="A" || $grade=="B" ||$grade=="C")
            $remarks="PASS";
        else if($grade=="I")
            $remarks="INCOMPLETE";
        else
            $remarks="FAIL";
        return $remarks;
    }*/

    public function courseWorkRemarks($marks)
    {
        if($marks>=16)
            $remarks="Pass";
        else
            $remarks="Fail";
        return $remarks;
    }
 
 public function getInstructorCourse($deptID,$semID)
 {
     $query = $this->conn->prepare("SELECT DISTINCT
    cp.courseID,courseCode,courseName,courseTypeID,units,batchID
FROM
    course c,
    courseprogramme cp
WHERE
    c.courseID = cp.courseID
        AND departmentID = :departID
        AND cp.semesterSettingID = :semeID
        AND cp.courseID NOT IN (SELECT 
            ic.courseID
        FROM
            instructor_course ic
        WHERE
            semesterSettingID = :semeeID)");
        /*$query = $this->conn->prepare("SELECT DISTINCT
    courseID, courseStatus,batchID
FROM
    courseprogramme cp,
    programmes p
WHERE
    p.programmeID = cp.programmeID
        AND p.departmentID = :departID
        AND cp.semesterSettingID = :semeID
        AND cp.courseID NOT IN (SELECT 
            ic.courseID
        FROM
            instructor_course ic
        WHERE
            semesterSettingID = :semeeID)");*/
        $query->execute(array(':departID'=>$deptID,':semeID'=>$semID,':semeeID'=>$semID));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
}

    public function getSemesterAllocationCourse($centerID,$semID)
    {
        $query = $this->conn->prepare("SELECT DISTINCT
    cp.courseID,courseCode,courseName,programmeID,programmeLevelID,classNumber,centerProgrammeCourseID
FROM
    course c,
    center_programme_course cp    
WHERE
        c.courseID = cp.courseID
        AND cp.academicYearID = :semeID
        AND centerID=:center
        AND staffID IS NULL");
        $query->execute(array(':center'=>$centerID,':semeID'=>$semID));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

//getDeanCourse
    public function getInstructorDeanCourse($deptID,$semID)
    {

        $query = $this->conn->prepare("SELECT DISTINCT
    cp.courseID,courseCode,courseName,courseTypeID,units,batchID
FROM
    course c,
    courseprogramme cp,
    schools s,
    departments d
WHERE
    c.courseID = cp.courseID
    AND s.schoolID=d.schoolID
    AND d.departmentID=c.departmentID
    AND d.schoolID = :departID
    AND cp.semesterSettingID = :semeID
    AND cp.courseID NOT IN (SELECT 
            ic.courseID
        FROM
            instructor_course ic
        WHERE
            semesterSettingID = :semeeID)");

       /* $query = $this->conn->prepare("SELECT DISTINCT
    cp.courseID,courseCode,courseName,units,batchID
FROM
    course c,
    courseprogramme cp,
    schools s
WHERE
    c.courseID = cp.courseID
        AND s.departmentID=cp.departmentID
        AND s.schoolID = :departID
        AND cp.semesterSettingID = :semeID
        AND cp.courseID NOT IN (SELECT 
            ic.courseID
        FROM
            instructor_course ic
        WHERE
            semesterSettingID = :semeeID)");*/
        $query->execute(array(':departID'=>$deptID,':semeID'=>$semID,':semeeID'=>$semID));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function getCourseInstructor($deptID,$semID)
    {
        $query = $this->conn->prepare("SELECT DISTINCT
    cp.courseID,courseCode,courseName,programmeLevelID,classNumber,centerProgrammeCourseID,staffID
FROM
    course c,
    center_programme_course cp    
WHERE
        c.courseID = cp.courseID
        AND centerID=:center
        AND cp.academicYearID = :semeID
        ");

        $query->execute(array(':center'=>$deptID,':semeID'=>$semID));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function getSemesterCourseInstructor($semID)
    {
        $query = $this->conn->prepare("SELECT 
    DISTINCT ic.courseID, ic.batchID, ic.instructorID, instructorCourseID
FROM
    instructor_course ic,
    instructor i,
    course c
WHERE
    i.instructorID = ic.instructorID
        AND c.courseID=ic.courseID
        AND ic.semesterSettingID = :semeID");
        $query->execute(array(':semeID'=>$semID));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

public function getCourseDeanInstructor($deptID,$semID)
{
    $query = $this->conn->prepare("SELECT 
    DISTINCT ic.courseID, ic.batchID, ic.instructorID, instructorCourseID
FROM
    instructor_course ic,
    instructor i,
    courseprogramme cp,
    course c,
    schools s,
    departments d
WHERE
        s.schoolID=d.schoolID
        AND d.departmentID=c.departmentID
        AND d.schoolID = :departID
        AND d.departmentID=i.departmentID
        AND i.instructorID = ic.instructorID
		AND ic.courseID=cp.courseID
        AND ic.semesterSettingID = :semeID");
    $query->execute(array(':departID'=>$deptID,':semeID'=>$semID));
    $data = array();
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    return $data;
}

/* public function getCourseInstructor($deptID,$semID)
 {
     $query = $this->conn->prepare("SELECT courseID,batchID,ic.instructorID,instructorCourseID from instructor_course ic,instructor i where i.instructorID=ic.instructorID and i.departmentID=:departID and ic.semesterSettingID=:semeID");
     $query->execute(array(':departID'=>$deptID,':semeID'=>$semID));
     $data = array();
     while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
         $data[] = $row;
     }
     return $data;
 }*/


public function getCourse($stdID,$acadID,$semID)
    {
        
        $query = $this->conn->prepare("SELECT DISTINCT(er.course_id),courseName,courseCode,units from student s,course c,exam_result er where s.studentID=er.studentID AND er.studentID=:studentID AND c.courseID=er.courseID AND academic_yearID=:academicYearID AND semister_id=:semisterID");
        
        $query->execute(array(':studentID'=>$stdID,':academicYearID'=>$acadID,':semisterID'=>$semID));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }
        
public function getAcademicYear($studentID)
{
    $query = $this->conn->prepare("SELECT DISTINCT(ac.academic_year_id),academic_year from academic_year ac,exam_result er,student s where ac.academic_year_id=er.academic_year_id and s.student_id=er.student_id and er.student_id=:stdID group by academic_year order by academic_year ASC "); 
        $query->execute(array(':stdID'=>$studentID));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
}

    public function getTranscriptAcademicYear($regNumber)
    {
        $query = $this->conn->prepare("SELECT DISTINCT
    (sm.academicYearID),academicYear
FROM
    semester_setting sm,
    exam_result er,
    student s,
     academic_year ay
WHERE
      ay.academicYearID=sm.academicYearID
      AND sm.semesterSettingID = er.semesterSettingID
      AND s.registrationNumber = er.regNumber
      AND er.regNumber = :regNo
ORDER BY academicYearID ASC");
        $query->execute(array(':regNo'=>$regNumber));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

public function getSemester($regNumber)
{
    $query = $this->conn->prepare("SELECT DISTINCT
    (sm.semesterSettingID), semesterName
FROM
    semester_setting sm,
    exam_result er,
    student_course sc,
    student s
WHERE
    sm.semesterSettingID = er.semesterSettingID
        AND s.registrationNumber = er.regNumber
        AND s.registrationNumber=sc.regNumber
        AND sm.semesterSettingID=sc.semesterSettingID
        AND er.regNumber = :regNo
ORDER BY semesterSettingID DESC"); 
        $query->execute(array(':regNo'=>$regNumber));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
}

public function getStudentSemester($regNumber)
{
    $query = $this->conn->prepare("SELECT DISTINCT
    (sm.semesterSettingID), semesterName
FROM
    semester_setting sm,
    exam_result er,
    student s
WHERE
    sm.semesterSettingID = er.semesterSettingID
        AND s.registrationNumber = er.regNumber
        AND er.regNumber = :regNo 
        AND er.status=:st
ORDER BY semesterSettingID DESC");
    $query->execute(array(':regNo'=>$regNumber,':st'=>1));
    $data = array();
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    return $data;
}

public function convert_gpa($number)
{
    //list($int,$dec) = explode(".",$number);
    $data=explode(".",$number);
    $int=@$data[0];
    $dec=@$data[1];
	if($dec==0)
	    $the_number=$int.".0";
	else
        $the_number = $int.".".substr($dec,0,1);
	return $the_number;
}


public function getStudentCount($courseID,$semisterID)
{
    $query = $this->conn->prepare("SELECT COUNT(er.regNumber) as studentNumber from exam_result er,student s where s.registrationNumber=er.regNumber and courseID=:cID and semesterSettingID=:semeID"); 
        $query->execute(array(':cID'=>$courseID,'semeID'=>$semisterID));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
}

public function getDistinctProgrammeFees()
{
    try
    {
        $query=$this->conn->prepare("SELECT DISTINCT pf.programID,programFeesStatus,programmeName,academicYearID from programmes p, programmefees pf where p.programmeID=pf.programID order by academicYearID,programFeesStatus ASC");
        $query->execute();
        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
        
    } catch (PDOException $ex) {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}

public function getProgrammeFees($programmeID,$academicYearID)
{
    try
    {
        
        $query=$this->conn->prepare("SELECT DISTINCT(pf.programID),programmeName,academicYearID from programmes p, programmefees pf where p.programmeID=pf.programID and pf.programID=:pID and academicYearID=:acadID");
        $query->execute(array(':pID'=>$programmeID,':acadID'=>$academicYearID));
        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
        
    } catch (PDOException $ex) {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}

public function getPrintedProgrammeFees($programmeMajorID)
{
    try
    {
        
        $query=$this->conn->prepare("SELECT DISTINCT(pf.programID) as programID,programName,academicYearID from programs p,programmemajor pm, programmefees pf where p.programID=pm.programmeID and p.programID=pf.programID and pm.programmeMajorID=:pMajorID");
        $query->execute(array(':pMajorID'=>$programmeMajorID));
        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
        
    } catch (PDOException $ex) {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}

public function getSumFees($feesType,$programmeID,$academicYearID)
{
    $query=$this->conn->prepare("SELECT SUM($feesType) as sumFees from programmefees pf,programmes p where p.programmeID=pf.programID and pf.programID=:pID and academicYearID=:ayID");
    $query->execute(array(':pID'=>$programmeID,':ayID'=>$academicYearID));
    $row=$query->fetch(PDO::FETCH_ASSOC);
    $sumFees=$row['sumFees'];
    return $sumFees;
}

public function checkCourseExist($courseID,$regNumber,$academicYearID)
{
    $query=$this->conn->prepare("SELECT courseID FROM student_course where regNumber=:rNumber AND courseID=:cID AND academicYearID=:acadID");
    $query->execute(array(':rNumber'=>$regNumber,':cID'=>$courseID,':acadID'=>$academicYearID));
    $row=$query->fetchAll();
    if(count($row)>0)
        return true;
    else 
        return false;
}

public function getSemesterCourse($semesterID,$role,$depID)
{
    try
    {
        $query=$this->conn->prepare("SELECT DISTINCT courseProgrammeID,c.courseID,courseCode,courseName,units,courseTypeID,programmeLevelID,programmeID,courseStatus FROM courseprogramme cp,course c
        where c.courseID=cp.courseID and academicYearID=:sID");
        $query->execute(array(':sID'=>$semesterID));

    $data=array();
    while($row=$query->fetch(PDO::FETCH_ASSOC))
    {
        $data[]=$row;
    }
        return $data;
    }
    catch (PDOException $ex) 
    {
        echo "Getting Data Error: ".$ex->getMessage();
    }
    
}



    public function getMappingCourseList($role,$depID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT DISTINCT c.courseID,courseCode,courseName,courseTypeID,programmeLevelID FROM programmemaping p,course c
        where c.courseID=p.courseID");
            $query->execute();

            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }

    }

    public function getAssessmentCourse($center,$roleID,$academicYearID)
    {
        try
        {
            if($center=='all')
            {
                $query = $this->conn->prepare("SELECT DISTINCT centerProgrammeCourseID,c.courseID,courseCode,courseName,courseTypeID,programmeLevelID,programmeID,classNumber,staffID FROM center_programme_course cp,course c where c.courseID=cp.courseID and academicYearID=:sID");
                $query->execute(array(':sID' => $academicYearID));
            }
            else {
                    $query = $this->conn->prepare("SELECT DISTINCT centerProgrammeCourseID,c.courseID,courseCode,courseName,courseTypeID,programmeLevelID,programmeID,classNumber,staffID FROM center_programme_course cp,course c where c.courseID=cp.courseID and cp.centerID=:center and  academicYearID=:sID");
                    $query->execute(array(':center'=>$center,':sID' => $academicYearID));
            }

            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }

    }

    public function getCourseInfo($centerProgrammeCourseID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT DISTINCT c.courseID,courseCode,courseName,courseTypeID,programmeLevelID,programmeID,classNumber,staffID,academicYearID FROM center_programme_course cp,course c where c.courseID=cp.courseID and centerProgrammeCourseID=:sID");
            $query->execute(array(':sID'=>$centerProgrammeCourseID));

            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }

    }

    public function getCenterAnnualCourse($academicYear,$centerID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT DISTINCT c.courseID,courseCode,courseName,units,courseTypeID,programmeLevelID,courseStatus FROM courseprogramme cp,course c where c.courseID=cp.courseID and academicYearID=:sID");
            $query->execute(array(':sID'=>$academicYear));

            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }

    }

    public function getCenterProgrammes($centerID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT DISTINCT cp.programmeID,programmeName from programmes p,center_registration cr, center_programme cp where p.programmeID=cp.programmeID AND cr.centerRegistrationID=cp.centerRegistrationID AND cp.centerRegistrationID=:center");
            $query->execute(array(':center'=>$centerID));
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }

    }

public function getSemesterProgrammeCourse($programmeID,$semesterID)
{
    try
    {
        $query=$this->conn->prepare("SELECT courseID,programmeLevelID,courseStatus FROM courseprogramme where programmeID=:pgID and academicYearID=:sID");
        $query->execute(array('pgID'=>$programmeID,':sID'=>$semesterID));
        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
    }
    catch (PDOException $ex)
    {
        echo "Getting Data Error: ".$ex->getMessage();
    }
    
}

    public function getSemesterPublishCourse($semesterID)
    {
        try
        {
            /*$query=$this->conn->prepare("SELECT courseID,batchID,studyYear,courseStatus FROM course c,student_course sc,courseprogramme cp where c.courseID=sc.courseID and c.courseID=sc.courseID and programmeID=:pgID and semesterSettingID=:sID and batchID=:bid");
            $query->execute(array('pgID'=>$programmeID,':sID'=>$semesterID,':bid'=>$batchID));*/
            $query=$this->conn->prepare("SELECT DISTINCT (courseID),batchID,studyYear FROM courseprogramme where semesterSettingID=:sID");
            $query->execute(array(':sID'=>$semesterID));
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }

    }

public function getSemesterProgrammeCourseStudy($programmeID,$semesterID,$batchID,$stuID)
{
    try
    {
        $query=$this->conn->prepare("SELECT courseID,courseProgrammeID,batchID FROM courseprogramme where programmeID=:pgID and semesterSettingID=:sID and batchID=:bid and studyYear=:study");
        $query->execute(array('pgID'=>$programmeID,':sID'=>$semesterID,':bid'=>$batchID,':study'=>$stuID));
        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
    }
    catch (PDOException $ex)
    {
        echo "Getting Data Error: ".$ex->getMessage();
    }
    
}


public function getSemesterInstructorCourse($courseID,$semesterID)
{
    try
    {
        $query=$this->conn->prepare("SELECT courseID,courseProgrammeID,batchID FROM courseprogramme where semesterSettingID=:sID and courseID=:cid");
        $query->execute(array(':sID'=>$semesterID,':cid'=>$courseID));
        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
    }
    catch (PDOException $ex)
    {
        echo "Getting Data Error: ".$ex->getMessage();
    }
    
}


    public function getSemesterCourseDistinct($courseID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT DISTINCT semesterSettingID FROM student_course where courseID=:cid");
            $query->execute(array(':cid'=>$courseID));
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }

    }

public function getStudentCourseSum($centerID,$academicYearID,$programmeLevelID,$progID)
{
    try
    {
        $query=$this->conn->prepare("SELECT COUNT(regNumber) as studentNumber FROM student_programme  where academicYearID=:acadID and  programmeLevelID=:levelID and centerID=:center and programmeID=:pID and currentStatus=:st");
        $query->execute(array(':acadID'=>$academicYearID,':levelID'=>$programmeLevelID,':center'=>$centerID,':pID'=>$progID,':st'=>1));
        $row=$query->fetch(PDO::FETCH_ASSOC);
        $number=$row['studentNumber'];
        return $number;
    }
    catch (PDOException $ex)
    {
        echo "Getting Data Error: ".$ex->getMessage();
    }
    
}

    public function getStudentCourseCount($courseID,$semesterID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT COUNT(sc.regNumber) as studentNumber FROM student_course sc,student s  where courseID=:cID AND semesterSettingID=:sID and s.registrationNumber=sc.regNumber");
            $query->execute(array(':cID'=>$courseID,':sID'=>$semesterID));
            $row=$query->fetch(PDO::FETCH_ASSOC);
            $number=$row['studentNumber'];
            return $number;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }

    }

    public function getStudentSuppSpecialRegNumber($courseID,$semesterID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT regNumber FROM student_course where courseID=:cID AND semesterSettingID=:sID");
            $query->execute(array(':cID'=>$courseID,':sID'=>$semesterID));
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;

        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }

    }

    public function getStudentSuppCount($courseID,$semesterID,$batchID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT COUNT(DISTINCT(f.examNumber)) as studentNumber FROM final_result f,exam_number en where courseID=:cID AND f.semesterSettingID=:sID and en.examNumber=f.examNumber and batchID=:batch");
            $query->execute(array(':cID'=>$courseID,':sID'=>$semesterID,':batch'=>$batchID));
            $row=$query->fetch(PDO::FETCH_ASSOC);
            $number=$row['studentNumber'];
            return $number;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }

    }


    public function getStudentSpecialCount($courseID,$semesterID,$batchID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT COUNT(DISTINCT(f.examNumber)) as studentNumber FROM final_result f,exam_number en where courseID=:cID AND f.semesterSettingID=:sID and en.examNumber=f.examNumber and batchID=:batch");
            /*$query=$this->conn->prepare("SELECT COUNT(DISTINCT(regNumber)) as studentNumber FROM student_course where regNumber NOT IN(SELECT examNumber from
            final_result f,exam_number en where courseID=:cID AND f.semesterSettingID=:sID and en.examNumber=f.examNumber and batchID=:batch and present=:rmarks");
            $query=$this->conn->prepare("SELECT COUNT(DISTINCT(regNumber)) as studentNumber FROM student_course where courseID = :scid AND semesterSettingID = :ssID AND 
            regNumber NOT IN(SELECT examNumber from final_result f where courseID=:cID AND f.semesterSettingID=:sID and batchID=:batch)");*/
            $query->execute(array(':cID'=>$courseID,':sID'=>$semesterID,':batch'=>$batchID));
/*            $query->execute(array(':scid'=>$courseID,':ssID'=>$semesterID,':cID'=>$courseID,':sID'=>$semesterID,':batch'=>$batchID));*/
            $row=$query->fetch(PDO::FETCH_ASSOC);
            $number=$row['studentNumber'];
            return $number;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }

    }

public function encrypt($sData){
    $id=(double)$sData*18293823.45;
    return base64_encode($id);
}
public function decrypt($sData){
    $url_id=base64_decode($sData);
    $id=(double)$url_id/18293823.45;
    return $id;
}


public function my_simple_crypt($string, $action = 'e' )
{
    // you may change these values to your own
    $secret_key = 'hmytechnologies@2017_yahya_mam';
    $secret_iv = 'hmytechnologies@2017_yahya_hamida';
    
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
    
    if( $action == 'e' ) {
        $output = base64_encode(openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
    }
    else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
    }
    
    return $output;
}

public function getExamCategory($courseTypeID)
{
    try
    {
        if($courseTypeID==2 || $courseTypeID==6 || $courseTypeID==7)
        {
            $query=$this->conn->prepare("SELECT examCategoryID,examCategory from exam_category where examCategoryID=5 or examCategoryID=6");
            $query->execute();
        }
        else 
        {
            $query=$this->conn->prepare("SELECT examCategoryID,examCategory from exam_category where examCategoryID=1 or examCategoryID=2");
            $query->execute();
        }
        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
    }
    catch (PDOException $ex)
    {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}


    public function getFinalExamCategory()
    {
        try
        {
                $query=$this->conn->prepare("SELECT examCategoryID,examCategory from exam_category where (examCategoryID=3 OR examCategoryID=4 OR examCategoryID=5)");
                $query->execute();
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }

    public function getAssessmentTypeCategory($courseTypeID)
    {
        try
        {
            if($courseTypeID==1)
            {
                $query=$this->conn->prepare("SELECT assessmentTypeID,assessmentType from assessment_type where subjectTypeID=:assID");
                $query->execute(array(':assID'=>1));
            }
            else if($courseTypeID==2)
            {
                $query=$this->conn->prepare("SELECT assessmentTypeID,assessmentType from assessment_type where subjectTypeID=:assID");
                $query->execute(array(':assID'=>2));
            }
            else
            {
                $query=$this->conn->prepare("SELECT assessmentTypeID,assessmentType from assessment_type where subjectTypeID=:assID");
                $query->execute(array(':assID'=>3));
            }
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }

    public function getExamCategorySetting()
    {
        try
        {

            $query=$this->conn->prepare("SELECT examCategoryID,examCategory from exam_category where examCategoryID=4 or examCategoryID=7 or examCategoryID=8");
            $query->execute();
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }

//getMaximumNumber
public function getMaxSerialNumber($programmeID,$semesterSettingID)
{
    try
    {
        $query=$this->conn->prepare("SELECT
            MAX(serialNumber) as serialNumber
        from
            exam_number
        where
                programmeID =:prgID
        AND
                semesterSettingID=:smid");
        $query->execute(array(':prgID'=>$programmeID,':smid'=>$semesterSettingID));
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $serialNumber=$row['serialNumber'];
        }
        return $serialNumber;
        
    } catch (PDOException $ex) {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}

//count number of digits
public function count_digit($number)
{
    return strlen((string) $number);
}
//To get student list for adding result
public function getStudentScoreList($courseID, $academicYearID,$programmeLevelID,$programmeID)
{
    try
    {
            $query=$this->conn->prepare("SELECT sc.regNumber from student s, student_course sc 
            WHERE s.registrationNumber=sc.regNumber
            AND sc.courseID=:cid
            AND sc.academicYearID=:acadID
            AND sc.programmeID=:pid
            AND sc.programmeLevelID=:plID
            ORDER BY sc.regNumber ASC");
            $query->execute(array(':cid'=>$courseID,':acadID'=>$academicYearID,':pid'=>$programmeID,':plID'=>$programmeLevelID));
        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
    }
    catch (PDOException $ex)
    {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}


    public function getStudentTermList($centerID,$academicYearID,$programmeLevelID,$programmeID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT sc.regNumber from student s, student_programme sc 
            WHERE s.registrationNumber=sc.regNumber
            AND sc.academicYearID=:acadID
            AND sc.programmeID=:pid
            AND sc.programmeLevelID=:plID
            AND sc.centerID=:center
            ORDER BY sc.regNumber ASC");
            $query->execute(array(':acadID'=>$academicYearID,':pid'=>$programmeID,':plID'=>$programmeLevelID,':center'=>$centerID));
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }

//adding result for final exam
    public function getStudentExamList($courseID,$academicYearID,$levelID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT DISTINCT e.examNumber,e.regNumber from student s, student_programme sp,programmemaping p, exam_number e
            WHERE s.registrationNumber=e.regNumber
            AND e.regNumber=sp.regNumber
            AND sp.programmeLevelID=p.programmeLevelID
            AND p.courseID=:cid
            AND sp.programmeLevelID=:lvlID
            AND e.academicYearID=:acadID
            ORDER BY e.examNumber ASC");
            $query->execute(array(':cid'=>$courseID,':lvlID'=>$levelID,':acadID'=>$academicYearID));
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }

    public function getStudentSuppList($courseID,$semesterID,$batchID)
    {
        try
        {
                $query=$this->conn->prepare("SELECT  DISTINCT en.regNumber from final_result f,exam_number en,student s
            WHERE s.registrationNumber=en.regNumber 
            AND en.examNumber=f.examNumber
            AND f.courseID=:cid
            AND f.semesterSettingID=:sid
            AND f.batchID=:bid
            ");
                $query->execute(array(':cid'=>$courseID,':sid'=>$semesterID,':bid'=>$batchID));
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }

    public function getStudentSpecialList($courseID,$semesterID,$batchID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT  DISTINCT f.examNumber,en.regNumber,firstName,middleName,lastName from final_result f,exam_number en,student s
            WHERE s.registrationNumber=en.regNumber 
            AND en.examNumber=f.examNumber
            AND f.courseID=:cid
            AND f.semesterSettingID=:sid
            AND f.batchID=:bid
            ");
            $query->execute(array(':cid'=>$courseID,':sid'=>$semesterID,':bid'=>$batchID));
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }


//getExamScore
/*public function getStudentExamResult($courseID,$semesterID,$batchID)
{
    try
    {
        $query=$this->conn->prepare("SELECT sc.regNumber from student s, student_course sc,exam_number e
        WHERE s.registrationNumber=sc.regNumber
        AND s.registrationNumber=e.regNumber
        AND sc.semesterSettingID=e.semesterSettingID
        AND sc.courseID=:cid
        AND sc.semesterSettingID=:sid
        AND s.batchID=:bid");
        $query->execute(array(':cid'=>$courseID,':sid'=>$semesterID,':bid'=>$batchID));
        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
    }
    catch (PDOException $ex)
    {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}
*/
//updateExamScore
    public function getStudentExamResultReport($programmeID,$courseID,$semesterID,$batchID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT DISTINCT sc.regNumber from student s, student_course sc,programmes p
        WHERE p.programmeID = s.programmeID
        AND s.programmeID=:prgID
        AND s.registrationNumber=sc.regNumber
        AND sc.courseID=:cid
        AND sc.semesterSettingID=:sid
        AND s.batchID=:bid
        ORDER BY sc.regNumber ASC");
            $query->execute(array(':prgID'=>$programmeID,':cid'=>$courseID,':sid'=>$semesterID,':bid'=>$batchID));
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }

    public function getStudentExamResult($courseID,$semesterID,$batchID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT DISTINCT sc.regNumber from student s, student_course sc
        WHERE s.registrationNumber=sc.regNumber
        AND sc.courseID=:cid
        AND sc.semesterSettingID=:sid
        AND s.batchID=:bid");
            $query->execute(array(':cid'=>$courseID,':sid'=>$semesterID,':bid'=>$batchID));
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }

    public function getStudentSuppExamResult($courseID,$semesterID,$batchID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT DISTINCT sc.regNumber from student s, student_course sc,exam_result er
        WHERE s.registrationNumber=sc.regNumber
          AND s.registrationNumber=er.regNumber
          AND er.examcategoryID=:ecID
        AND er.courseID=:cid
        AND er.semesterSettingID=:sid
        AND s.batchID=:bid");
            $query->execute(array(':ecID'=>3,':cid'=>$courseID,':sid'=>$semesterID,':bid'=>$batchID));
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }

    public function getStudentSpecialExamResult($courseID,$semesterID,$batchID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT DISTINCT en.regNumber from student s, exam_number en,final_result fr
        WHERE s.registrationNumber=en.regNumber
          AND en.examNumber=fr.examNumber
          AND fr.examcategoryID=:ecID
        AND fr.courseID=:cid
        AND fr.semesterSettingID=:sid
        AND s.batchID=:bid");
            $query->execute(array(':ecID'=>4,':cid'=>$courseID,':sid'=>$semesterID,':bid'=>$batchID));
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }

//getFinalresult
public function getStudentFinalResult($courseID,$semesterID,$batchID)
{
    try
    {
            $query=$this->conn->prepare("SELECT DISTINCT sc.regNumber, from student s, student_course sc
            WHERE s.registrationNumber=sc.regNumber
            AND sc.courseID=:cid
            AND sc.semesterSettingID=:sid
            AND s.batchID=:bid");
            $query->execute(array(':cid'=>$courseID,':sid'=>$semesterID,':bid'=>$batchID));
        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
    }
    catch (PDOException $ex)
    {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}

public function getFinalGrade($academicYearID,$courseID,$regNumber,$examCategoryID)
{
    $examScore="";
    
    $query=$this->conn->prepare("SELECT examScore from exam_number en,final_result fr
            WHERE 
            en.examNumber=fr.examNumber
            AND en.regNumber=:rNumber
            AND fr.courseID=:cid
            AND fr.academicYearID=:sid
            AND fr.examCategoryID=:exam");
    $query->execute(array('rNumber'=>$regNumber,':cid'=>$courseID,':sid'=>$academicYearID,':exam'=>$examCategoryID));
    while($row=$query->fetch(PDO::FETCH_ASSOC))
    {
        $examScore=$row['examScore'];
    }
    return $examScore;
}

  /*  public function getFinalGrade($semesterID,$courseID,$regNumber,$examCategoryID)
    {
        $examScore="";

        $query=$this->conn->prepare("SELECT examScore from final_result
            WHERE examNumber=:rNumber
            AND courseID=:cid
            AND semesterSettingID=:sid
            AND examCategoryID=:exam");
        $query->execute(array('rNumber'=>$regNumber,':cid'=>$courseID,':sid'=>$semesterID,':exam'=>$examCategoryID));
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $examScore=$row['examScore'];
        }
        return $examScore;
    }*/

    /*public function getFinalGrade($semesterID,$courseID,$regNumber,$examCategoryID)
    {
        $examScore="";

        $query=$this->conn->prepare("SELECT examScore from exam_result WHERE
            regNumber=:rNumber
            AND courseID=:cid
            AND semesterSettingID=:sid
            AND examCategoryID=:exam");
        $query->execute(array('rNumber'=>$regNumber,':cid'=>$courseID,':sid'=>$semesterID,':exam'=>$examCategoryID));
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $examScore=$row['examScore'];
        }
        return $examScore;
    }*/

//getExamScore
public function getStudentSearchResult($regNumber,$semesterID)
{
    try
    {
        $query=$this->conn->prepare("SELECT distinct(sc.courseID),courseStatus,er.semesterSettingID from student_course sc,exam_result er where sc.courseID=er.courseID AND sc.regNumber=er.regNumber and er.regNumber=:rNumber and er.semesterSettingID=:sem and sc.semesterSettingID=:seme");
        $query->execute(array(':rNumber'=>$regNumber,':sem'=>$semesterID,':seme'=>$semesterID));
        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
    }
    catch (PDOException $ex)
    {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}

    public function getStudentYearResult($regNumber,$academicYearID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT distinct(sc.courseID),courseStatus,er.semesterSettingID from student_course sc,exam_result er,semester_setting ss where sc.courseID=er.courseID AND sc.regNumber=er.regNumber AND ss.semesterSettingID=er.semesterSettingID and ss.academicYearID=:acadID and er.regNumber=:rNumber");
            $query->execute(array(':acadID'=>$academicYearID,':rNumber'=>$regNumber));
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }

public function getStudentResult($regNumber,$semesterID)
{
    try
    {
        $query=$this->conn->prepare("SELECT distinct(sc.courseID),courseStatus,er.semesterSettingID from student_course sc,exam_result er where sc.courseID=er.courseID AND sc.regNumber=er.regNumber and er.regNumber=:rNumber and er.semesterSettingID=:sem and er.status=:st");
        $query->execute(array(':rNumber'=>$regNumber,':sem'=>$semesterID,':st'=>1));
        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
    }
    catch (PDOException $ex)
    {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}

    public function getStudentCourse($regNumber,$semesterID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT distinct(sc.courseID),courseStatus,semesterSettingID from student_course sc where regNumber=:rNumber and semesterSettingID=:sem");
            $query->execute(array(':rNumber'=>$regNumber,':sem'=>$semesterID));
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }

public function getStudentByDepartment($admissionYear,$departID)
{
    try
    {
        $query=$this->conn->prepare("SELECT
    studentID,
    registrationNumber,
    firstName,
    middleName,
    lastName,
    gender,
    sp.programmeID,
    sp.programmeLevelID,
    s.statusID
FROM
    student s,
    programmes p,
    center_registration cr,
    student_programme sp
WHERE
		p.programmeID = sp.programmeID
        AND s.registrationNumber=sp.regNumber
        AND cr.centerRegistrationID = sp.centerID
        AND sp.centerID=:center
        AND sp.academicYearID = :yID");
        $query->execute(array(':center'=>$departID,':yID'=>$admissionYear));
        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
    }
    catch (PDOException $ex)
    {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}
 

public function getStudentByLevel($admissionYear,$level)
{
    try
    {
        $query=$this->conn->prepare("SELECT studentID,registrationNumber,firstName,middleName,lastName,gender,s.programmeID,batchID,statusID from student s,programmes p,programme_level pl where p.programmeID=s.programmeID AND pl.programmeLevelID=p.programmeLevelID and pl.programmeLevelID=:level and academicYearID=:yID");


        $query=$this->conn->prepare("SELECT
    studentID,
    registrationNumber,
    firstName,
    middleName,
    lastName,
    gender,
    sp.programmeID,
    sp.programmeLevelID,
    s.statusID
FROM
    student s,
    programmes p,
    programme_level pl,
    student_programme sp
WHERE
		p.programmeID = sp.programmeID
        AND s.registrationNumber=sp.regNumber
        AND pl.programmeLevelID=sp.programmeLevelID
        AND sp.programmeLevelID=:level
        AND sp.academicYearID = :yID");


        $query->execute(array(':level'=>$level,':yID'=>$admissionYear));
        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
    }
    catch (PDOException $ex)
    {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}

//all fees per programme and academic year
public function getAllFees($programmeID)
{
    try
    {
        $query=$this->conn->prepare("SELECT SUM(feesTZ) as sumfee from programmefees p where programID=:pid and programFeesStatus=:st");
        $query->execute(array(':pid'=>$programmeID,':st'=>1));
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $sumofallfees=$row['sumfee'];
        }
        return $sumofallfees;
    }
    catch (PDOException $ex)
    {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}

//get only paid once
public function getOnceFees($programmeID)
{
    try
    {
        $query=$this->conn->prepare("SELECT SUM(feesTZ) as sumfee from programmefees where programID=:pid and paidOnce=:once and programFeesStatus=:st");
        $query->execute(array(':pid'=>$programmeID,':once'=>1,':st'=>1));
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $sumpaidonce=$row['sumfee'];
        }
        return $sumpaidonce;
    }
    catch (PDOException $ex)
    {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}

public function getProgrammeLevelID($regNumber)
{
    try
    {
        $query=$this->conn->prepare("SELECT p.programmeLevelID from programme_level pl,student s, programmes p where pl.programmeLevelID=p.programmeLevelID and p.programmeID=s.programmeID and registrationNumber=:rNumber");
        $query->execute(array(':rNumber'=>$regNumber));
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $programmeLevelID=$row['programmeLevelID'];
        }
        return $programmeLevelID;
    }
    catch (PDOException $ex)
    {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}

/*public function getMarksGrade($regNumber,$cwk,$sfe,$sup,$spc,$prj,$pt)
{
    $programmeLevelID=$this->getProgrammeLevelID($regNumber);
    $tmarks=$this->calculateTotal($cwk,$sfe,$sup,$spc,$prj,$pt);
    $grade=$this->getRows("grades",array('where'=>array('programmeLevelID'=>$programmeLevelID)));
    $gradeOutput="";
    if(!empty($grade))
    {   
        foreach($grade as $gd)
        {
            $startMark=$gd['startMark'];
            $endMark=$gd['endMark'];
            $gradeCode=$gd['gradeCode'];
            if($tmarks>=$endMark &&  $tmarks<=$startMark)
            {
                $gradeOutput=$gradeCode;
            }
        }
    }
    else 
    {
        $gradeOutput=$this->calculateGrade($cwk,$sfe,$sup,$spc,$prj,$pt);
    }
    
    return $gradeOutput; 
}*/

public function getGPA($tpoints,$tunits)
{
    if($tpoints>0) {
        $cgpa = $tpoints / $tunits;
        $gpa = $cgpa;
    }
    else {
            $gpa = "0.0";
        }
        //return $gpa;
    return $this->convert_gpa($gpa);
}



public function getProgrammeStudyYear($depID,$semID)
{
    try
    {
        $query=$this->conn->prepare("SELECT DISTINCT(cp.programmeID),studyYear from courseprogramme cp,programmes p,departments d where p.programmeID=cp.programmeID and d.departmentID=p.departmentID and d.departmentID=:dep and semesterSettingID=:sem");
        $query->execute(array(':dep'=>$depID,':sem'=>$semID));
        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
    }
    catch (PDOException $ex)
    {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}

public function getInstructorCourseProgramme($depID,$semID)
{
    try
    {
        $query=$this->conn->prepare("SELECT DISTINCT(staffID) from center_programme_course where  centerID=:center and academicYearID=:acadID");
        $query->execute(array(':center'=>$depID,':acadID'=>$semID));
        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
    }
    catch (PDOException $ex)
    {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}

public function getCourseAllocationProgramme($progID,$study,$semID)
{
    try
    {
        $query=$this->conn->prepare("SELECT courseProgrammeID,courseID,batchID,courseStatus from courseprogramme  where programmeID=:pid and studyYear=:study and semesterSettingID=:sem");
        $query->execute(array(':pid'=>$progID,':study'=>$study,':sem'=>$semID));
        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
    }
    catch (PDOException $ex)
    {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}

public function getIsntructorCourseProgramme($instructorID,$semID)
{
    try
    {
        $query=$this->conn->prepare("SELECT courseID,classNumber,programmeLevelID,programmeID from center_programme_course where staffID=:insID and academicYearID=:sem");
        $query->execute(array(':insID'=>$instructorID,':sem'=>$semID));
        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
    }
    catch (PDOException $ex)
    {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}

public function getStudentCourseInfo($cid,$semID,$batchID)
{
    try
    {
        $query=$this->conn->prepare("SELECT regNumber,courseStatus from student_course sc,student s where s.registrationNumber=sc.regNumber and s.batchID=:bid and courseID=:courseID and  semesterSettingID=:sem");
        $query->execute(array(':bid'=>$batchID,':courseID'=>$cid,':sem'=>$semID));
        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
    }
    catch (PDOException $ex)
    {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}

    public function getStudentCourseList($cid,$semID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT regNumber,courseStatus from student_course sc,student s where s.registrationNumber=sc.regNumber and courseID=:courseID and  semesterSettingID=:sem");
            $query->execute(array(':courseID'=>$cid,':sem'=>$semID));
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }

public function checkStatus($courseID,$academicYearID,$programmeID,$programmeLevelID,$column)
{
    try
    {
        $query=$this->conn->prepare("SELECT $column as status from exam_result where courseID=:cid and academicYearID=:acadID and programmeLevelID=:levelID AND programmeID=:pID");
        $query->execute(array(':cid'=>$courseID,':acadID'=>$academicYearID,':levelID'=>$programmeLevelID,':pID'=>$programmeID));
        $row=$query->fetch(PDO::FETCH_ASSOC);
        $status=$row['status'];
        return $status;
    }
    catch (PDOException $ex)
    {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}

    public function getTotalMarks($courseID,$semesterID,$batchID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT examScore from exam_result where courseID=:cid and semesterSettingID=:sem and batchID=:bid");
            $query->execute(array(':cid'=>$courseID,':sem'=>$semesterID,':bid'=>$batchID));
            $exam_score=0;
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $exam_score += $this->decrypt($row['examScore']);
            }
            return $exam_score;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }





public function checkExamResultStatus($courseID,$semesterID,$batchID)
{
    try
    {
        $query=$this->conn->prepare("SELECT examScore from exam_result where courseID=:cid and academicYearID=:sem");
        $query->execute(array(':cid'=>$courseID,':sem'=>$semesterID,'bid'=>$batchID));
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $exam_score=$row['examScore'];
        }
        if(!empty($exam_score))
            return true;
        else 
            return false;
    }
    catch (PDOException $ex)
    {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}
    public function checkFinalResultStatus($courseID,$semesterID,$batchID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT examScore from final_result where courseID=:cid and semesterSettingID=:sem and batchID=:bid");
            $query->execute(array(':cid'=>$courseID,':sem'=>$semesterID,'bid'=>$batchID));
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $exam_score=$row['examScore'];
            }
            if(!empty($exam_score))
                return true;
            else
                return false;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }

public function approveGraduatedList($programmeID,$studyYear,$academicYearID,$batchID)
{
    try
    {
        $query=$this->conn->prepare("SELECT registrationNumber,firstName,middleName,lastName,gender from student s, student_study_year sy where 
        s.registrationNumber=sy.regNumber and programmeID=:pid and batchID=:bid and statusID=:st and sy.academicYearID=:acadID and studyYear=:styear");
        $query->execute(array('pid'=>$programmeID,':bid'=>$batchID,':st'=>1,':acadID'=>$academicYearID,':styear'=>$studyYear));
        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
        
    }
    catch (PDOException $ex)
    {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}

    public function graduateList($programmeID,$studyYear,$academicYearID,$batchID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT registrationNumber,firstName,middleName,lastName,gender,gpa,date_format(graduationDate,'%d-%m-%Y') as gdate from student s, student_study_year sy,graduate_list gl where s.registrationNumber=sy.regNumber and s.registrationNumber=gl.regNumber and programmeID=:pid and batchID=:bid and statusID=:st and sy.academicYearID=:acadID and studyYear=:styear order by firstName");
            $query->execute(array('pid'=>$programmeID,':bid'=>$batchID,':st'=>2,':acadID'=>$academicYearID,':styear'=>$studyYear));
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;

        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }


    public function transcriptList($regNumber)
    {
        try
        {
            $query=$this->conn->prepare("SELECT registrationNumber,programmeID,batchID,studentPicture,firstName,middleName,lastName,gender,date_format(graduationDate,'%d-%m-%Y') as gdate from student s, graduate_list gl where s.registrationNumber=gl.regNumber and gl.regNumber=:reg and statusID=:st");
            $query->execute(array('reg'=>$regNumber,':st'=>2));
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;

        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }

public function getStudentCredits($regNumber)
{
    try
    {
        $query=$this->conn->prepare("SELECT SUM(units) as credits from student_course sc, course c where c.courseID=sc.courseID and sc.regNumber=:regNo");
        $query->execute(array(':regNo'=>$regNumber));
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $credits=$row['credits'];
        }
        return $credits;
        
    }
    catch (PDOException $ex)
    {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}


public function getStudentPassCredits($regNumber)
{
    try
    {
        $query=$this->conn->prepare("SELECT SUM(units) as credits from student_course sc, course c where c.courseID=sc.courseID and sc.regNumber=:regNo");
        $query->execute(array(':regNo'=>$regNumber));
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $credits=$row['credits'];
        }
        return $credits;
        
    }
    catch (PDOException $ex)
    {
        echo "Getting Data Error: ".$ex->getMessage();
    }
}

public function getGPARemarks($regNumber,$gpa)
 {
 $programmeLevelID=$this->getProgrammeLevelID($regNumber);
 $gpa_class=$this->getRows("gpa",array('where'=>array('programmeLevelID'=>$programmeLevelID)));
 if(!empty($gpa_class))
 {
     $gpaOutput="";
     foreach($gpa_class as $gd)
     {
         $startPoint=$gd['startPoint'];
         $endPoint=$gd['endPoint'];
         $gpaClass=$gd['gpaClass'];
         if($gpa>=$endPoint &&  $gpa<=$startPoint)
         {
             $gpaOutput=$gpaClass;
         }
    }
   }
   return $gpaOutput;
 }
 
 public function getInstructorAcademicCourse($academicYearID,$instructorID)
 {
     try
     {
          $query=$this->conn->prepare("SELECT courseID,classNumber,programmeID,programmeLevelID FROM center_programme_course cp,instructor i
             where i.instructorID=cp.staffID and cp.staffID=:instructor and academicYearID=:acdID");
             $query->execute(array(':instructor'=>$instructorID,':acdID'=>$academicYearID));

           /*$query=$this->conn->prepare("SELECT courseID,courseProgrammeID,batchID FROM courseprogramme cp,instructor i
            where i.instructorID=cp.instructorID and cp.instructorID=:instructor and  cp.semesterSettingID=:sID");
            $query->execute(array(':instructor'=>$instructorID,':sID'=>$semesterID));*/
         $data=array();
         while($row=$query->fetch(PDO::FETCH_ASSOC))
         {
             $data[]=$row;
         }
         return $data;
     }
     catch (PDOException $ex)
     {
         echo "Getting Data Error: ".$ex->getMessage();
     }
     
 }

    public function getSemesterBatchCourse($semesterID,$courseID,$batchID,$instructorID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT courseID,batchID FROM instructor_course ic,instructor i
               where i.instructorID=ic.instructorID and ic.instructorID=:instructor and semesterSettingID=:sID and courseID=:cID and batchID=:bID");
               $query->execute(array(':instructor'=>$instructorID,':sID'=>$semesterID,':cID'=>$courseID,':bID'=>$batchID));

           /* $query=$this->conn->prepare("SELECT courseID,courseProgrammeID,batchID,cp.instructorID,courseGradeID,passMarkID FROM courseprogramme cp
            where semesterSettingID=:sID and courseID=:cID and batchID=:bID");
            $query->execute(array(':sID'=>$semesterID,':cID'=>$courseID,':bID'=>$batchID));*/
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }

    }
 
 
 public function isExamNumberExist($examNumber,$semesterID)
 {
     $query=$this->getRows("exam_number",array('where'=>array('examNumber'=>$examNumber,'semesterSettingID'=>$semesterID)));
     if(!empty($query))
     {
         return true;
     }
     else
     {
         return false;
     }
 }

    public function isRegNumberExist($regNumber,$courseID,$semesterID)
    {
        $query=$this->getRows("student_course",array('where'=>array('regNumber'=>$regNumber,'courseID'=>$courseID,'semesterSettingID'=>$semesterID)));
        if(!empty($query))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
 
 public function getMaxStudyYear($regNumber)
 {
     try
     {
         $query=$this->conn->prepare("SELECT MAX(studyYear) as studyYear from student_study_year where regNumber=:regNo and studyYearStatus=:st");
         $query->execute(array(':regNo'=>$regNumber,':st'=>1));
         while($row=$query->fetch(PDO::FETCH_ASSOC))
         {
             $studyYear=$row['studyYear'];
         }
         return $studyYear;
     }
     catch (PDOException $ex)
     {
         echo "Getting Data Error: ".$ex->getMessage();
     }
 }

    public function getRemarksGPA($gpa,$countSupp)
    {
        if($gpa<2 || $countSupp>0)
            $gparemarks="Fail";
        else
            $gparemarks="Pass";
        return $gparemarks;
    }
    public function getRoleName()
    {
        try
        {
            $query=$this->conn->prepare("SELECT roleID,roleName from roles where roleName NOT LIKE  :role1 and roleName NOT LIKE :role2");
            $query->execute(array(':role1'=>'Student',':role2'=>'Instructor'));
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }

    public function getStudentProgrammeList($programmeID,$semID,$studyYear,$batchID)
    {
        try {
            $query = $this->conn->prepare("SELECT DISTINCT(s.registrationNumber) from student s,student_study_year sy where s.registrationNumber = sy.regNumber and s.programmeID=:progID and s.batchID=:bid and semesterSettingID=:semID and sy.studyYear=:study");
            $query->execute(array(':progID' => $programmeID, ':bid' => $batchID, ':semID' => $semID, ':study' => $studyYear));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        }catch(PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }

    public function getStudentList($programmeID,$programmeLevelID,$academicYearID)
    {
        try {
            $query = $this->conn->prepare("SELECT DISTINCT(s.registrationNumber) from student s,student_programme sp where s.registrationNumber = sp.regNumber and sp.programmeID=:progID and sp.programmeLevelID=:plevelID and sp.academicYearID=:acadID");
            $query->execute(array(':progID' => $programmeID, ':plevelID' => $programmeLevelID,':acadID' => $academicYearID));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        }catch(PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }

    public function isCourseExist($regNumber,$courseID,$semesterSettingID)
    {
        $query=$this->conn->prepare("SELECT COUNT(regNumber) as number from student_course where regNumber=:regNo AND courseID=:cID AND semesterSettingID=:sID");
        $query->execute(array(':regNo' => $regNumber, ':cID' => $courseID, ':sID' => $semesterSettingID));
        $row=$query->fetch(PDO::FETCH_ASSOC);
        if($row['number']>0)
            return true;
        else
            return false;
    }

    public function standDeviation($arr)
    {
        $num_of_elements = count($arr);
        $variance = 0.0;
        // calculating mean using array_sum() method
        $average = array_sum($arr)/$num_of_elements;
        foreach($arr as $i)
        {
            // sum of squares of differences between
            // all numbers and means.
            $variance += pow(($i - $average), 2);
        }
        return (float)sqrt($variance/$num_of_elements);
    }

    public function getExamStatus($courseid,$sid,$bid,$eCategoryID,$status)
    {
        try
        {
            if($eCategoryID==2 || $eCategoryID==4) {
                $query = $this->conn->prepare("SELECT COUNT(*) as countStatus from final_result where courseID=:cid and semesterSettingID=:sem and batchID=:bid and examCategoryID=:ecatid and present=:prt");
            }else
            {
                $query = $this->conn->prepare("SELECT COUNT(*) as countStatus from exam_result where courseID=:cid and semesterSettingID=:sem and batchID=:bid and examCategoryID=:ecatid and present=:prt");
            }
            $query->execute(array(':cid'=>$courseid,':sem'=>$sid,':bid'=>$bid,':ecatid'=>$eCategoryID,':prt'=>$status));
            $row=$query->fetch(PDO::FETCH_ASSOC);
            $value=$row['countStatus'];
            return $value;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }


    public function getStudentExamStatus($regNumber,$courseid,$sid,$eCategoryID)
    {
        try
        {
            if($eCategoryID==2 || $eCategoryID==4) {
                $query = $this->conn->prepare("SELECT present from final_result where courseID=:cid and semesterSettingID=:sem and examNumber=:exmNumber and examCategoryID=:ecatid");
            }else
            {
                $query = $this->conn->prepare("SELECT present from exam_result where courseID=:cid and semesterSettingID=:sem and regNumber=:exmNumber and examCategoryID=:ecatid");
            }
            $query->execute(array(':cid'=>$courseid,':sem'=>$sid,':exmNumber'=>$regNumber,':ecatid'=>$eCategoryID));
            $row=$query->fetch(PDO::FETCH_ASSOC);
            $value=$row['present'];
            return $value;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }

    public function getStudentStatus($sid,$status)
    {
        try
        {
            if($status==1) {
                $query = $this->conn->prepare("SELECT COUNT(*) as countStatus from student s,student_course sc where s.registrationNumber=sc.regNumber AND sc.semesterSettingID=:sem  and s.statusID=:st");
            }
            else
            {
                $query = $this->conn->prepare("SELECT COUNT(*) as countStatus from student s,student_status ss where s.registrationNumber=ss.regNumber AND ss.semesterSettingID=:sem  and s.statusID<>:st");
            }
            $query->execute(array(':sem'=>$sid,':st'=>$status));
            $row=$query->fetch(PDO::FETCH_ASSOC);
            $value=$row['countStatus'];
            return $value;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }

    public function generate_password($length = 20){
        $chars =  'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.
            '0123456789=!@#$%&*';

        $str = '';
        $max = strlen($chars) - 1;

        for ($i=0; $i < $length; $i++)
            $str .= $chars[random_int(6, $max)];

        return $str;
    }

    public function getCourseList($regNumber)
    {
        try {
            $query = $this->conn->prepare("SELECT
            c.courseID,courseCode, courseName,courseTypeID,studentCourseID,academicYearID
        FROM
            student_course sc,
            course c
        WHERE
            c.courseID = sc.courseID
                AND regNumber = :regNum");
            /*$query = $this->conn->prepare("SELECT
            c.courseID,courseCode, courseName,courseTypeID,academicYearID,courseStatus,staffID
        FROM
            student_course sc,
            course c,
            course_programme_course cpc,
            courseprogramme cp
        WHERE
            c.courseID = sc.courseID
                AND regNumber = :regNum");*/
            $query->execute(array(':regNum'=>$regNumber));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        }catch(PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }


    public function getProgrammeMaxMarks($progID,$eCatID)
    {
        try {
            $query = $this->conn->prepare("SELECT 
            mMark
        FROM
            exam_category_setting ec,programmes p
        WHERE
            p.programmeLevelID=ec.programmeLevelID
            AND p.programmeID=:proID
            AND ec.examCategoryID=:ecID");
            $query->execute(array(':proID'=>$progID,':ecID'=>$eCatID));
            $row = $query->fetch(PDO::FETCH_ASSOC);
            $marks=$row['mMark'];
            return $marks;
        }catch(PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }


    public function getStudentPaymentList($programmeID,$academicYearID)
    {
        try {
            if($programmeID=="all")
            {
                $query = $this->conn->prepare("SELECT DISTINCT(s.registrationNumber),studyYear from student s,student_study_year sy where s.registrationNumber = sy.regNumber and sy.academicYearID=:acadID");
                $query->execute(array(':acadID' => $academicYearID));
            }
            else
            {
                $query = $this->conn->prepare("SELECT DISTINCT(s.registrationNumber),studyYear from student s,student_study_year sy where s.registrationNumber = sy.regNumber and s.programmeID=:progID  and sy.academicYearID=:acadID");
                $query->execute(array(':progID' => $programmeID,':acadID' => $academicYearID));
            }
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        }catch(PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }




    public function getStudentFees($regNumber,$academicYearID,$studyYearID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT SUM(amount) as sumfee from student_fees s where regNumber=:regNo and academicYearID=:acadID and studyYear=:std");
            $query->execute(array(':regNo'=>$regNumber,':acadID'=>$academicYearID,':std'=>$studyYearID));
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $sumofallfees=$row['sumfee'];
            }
            return $sumofallfees;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }


    public function getStudentTransfer($centerID,$levelID,$programmeID,$academicYearID)
    {
        try {
            if($centerID=='all')
            {
                $query = $this->conn->prepare("SELECT DISTINCT(s.registrationNumber),centerID from student s,student_programme sp where s.registrationNumber = sp.regNumber and programmeLevelID=:levelID and sp.programmeID=:progID  and sp.academicYearID=:acadID and currentStatus=:st and statusID=:stt");
                $query->execute(array(':levelID'=>$levelID,':progID' => $programmeID, ':acadID' => $academicYearID, ':st' => 1,':stt'=>1));
            }
            else {
                $query = $this->conn->prepare("SELECT DISTINCT(s.registrationNumber),centerID from student s,student_programme sp where s.registrationNumber = sp.regNumber and centerRegistrationID=:centerID and programmeLevelID=:levelID and sp.programmeID=:progID  and sp.academicYearID=:acadID and currentStatus=:st and statusID=:stt");
                $query->execute(array(':centerID'=>$centerID,':levelID'=>$levelID,':progID' => $programmeID, ':acadID' => $academicYearID, ':st' => 1,':stt'=>1));
            }
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        }catch(PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }

    public function checkCourseResult($courseID,$regNumber)
    {
        try
        {
            $query=$this->conn->prepare("SELECT examScore from exam_result where courseID=:cid and regNumber=:regNumber");
            $query->execute(array(':cid'=>$courseID,':regNumber'=>$regNumber));
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $exam_score=$row['examScore'];
            }
            if(!empty($exam_score))
                return true;
            else
                return false;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }


    public function getStudentYearStatus($academicYear,$status)
    {
        try
        {
            $query=$this->conn->prepare("SELECT 
            studentID,
            registrationNumber,
            firstName,
            middleName,
            lastName,
            gender,
            programmeID
        FROM
            student s,
            student_status st,
            student_programme sp,
            academic_year ac
        WHERE
            s.registrationNumber=sp.regNumber
                AND s.registrationNumber = st.regNumber
                AND sp.academicYearID = ac.academicYearID
                AND sp.academicYearID = :acadID
                AND s.statusID = st.statusID
                AND st.statusID = :status");
            $query->execute(array(':acadID'=>$academicYear,':status'=>$status));
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }

    }



    public function getStudentContinueList($academicYearID,$programmeID,$studyYear)
    {
        try {
            $query = $this->conn->prepare("SELECT studentID,registrationNumber,firstName,middleName,lastName,gender,batchID,statusID
            from student s,student_study_year sy 
            where
            s.registrationNumber = sy.regNumber and  s.programmeID=:progID and sy.academicYearID = :acadID  and sy.studyYear=:study");
            $query->execute(array(':progID' => $programmeID, ':acadID' => $academicYearID,':study' => $studyYear));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        }catch(PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }

    public function getStudentExamNumber($programmeLevelID,$programmeID,$academicYearID)
    {
        try {
            $query = $this->conn->prepare("SELECT studentID,registrationNumber,firstName,middleName,lastName,gender,statusID
            from student s,student_programme sp 
            where
            s.registrationNumber = sp.regNumber and sp.programmeLevelID=:levelID and sp.programmeID=:progID and sp.academicYearID = :acadID  and currentStatus=:st");
            $query->execute(array(':levelID'=>$programmeLevelID,':progID' => $programmeID, ':acadID' => $academicYearID,':st'=>1));
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        }catch(PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }



    //getpassmark
    public function getPassMark($courseID,$semesterSettingID,$batchID)
    {
        $query = $this->conn->prepare("SELECT passMark from exam_category_setting ec,courseprogramme cp,programmes p, programme_level pl
            where pl.programmeLevelID=p.programmeLevelID
            AND p.programmeID=cp.programmeID
            AND ec.programmeLevelID=pl.programmeLevelID
            AND cp.courseID=:courseID
            AND semesterSettingID=:semesterID
            AND batchID=:baID
            AND examCategoryID=:eCatID");
            $query->execute(array(':courseID'=>$courseID,':semesterID'=>$semesterSettingID,':baID'=>$batchID,':eCatID'=>2));

            $row = $query->fetch(PDO::FETCH_ASSOC);
            $marks=$row['passMark'];
            return $marks;
    }


    public function getStudentAllCount($gender)
    {
        try
        {
                $query = $this->conn->prepare("SELECT 
                COUNT(gender) AS number
          FROM
          student 
          WHERE
          statusID=:st
          AND gender=:gender");
                $query->execute(array(':st'=>1,':gender' => $gender));
            $row=$query->fetch(PDO::FETCH_ASSOC);
            $value=$row['number'];
            return $value;
        }
        catch(PDOException $exception)
        {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }




    /*public function getCurrentDataByProgramme($programmeID,$academicYearID,$gender)
    {
        try
        {
            $query = $this->conn->prepare("SELECT 
                COUNT(gender) AS number
          FROM
          student s,programmes p
          WHERE
          s.programmeID=p.programmeID
          AND s.programmeID=:proID
          AND academicYearID=:acadID
          AND statusID=:st
          AND gender=:gender");
            $query->execute(array(':proID'=>$programmeID,':acadID'=>$academicYearID,':st'=>1,':gender' => $gender));
            $row=$query->fetch(PDO::FETCH_ASSOC);
            $value=$row['number'];
            return $value;
        }
        catch(PDOException $exception)
        {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }*/


    public function getCurrentDataByProgramme($programmeID,$academicYearID,$gender)
    {
        try
        {
            $query = $this->conn->prepare("SELECT 
                COUNT(gender) AS number
          FROM
          student s,programmes p,programme_major pm
          WHERE
          pm.programmeMajorID=p.programmeMajorID
          AND p.programmeMajorID=:proID
          AND p.programmeID=s.programmeID
          AND statusID=:st
          AND gender=:gender");
            $query->execute(array(':proID'=>$programmeID,':st'=>1,':gender' => $gender));
            $row=$query->fetch(PDO::FETCH_ASSOC);
            $value=$row['number'];
            return $value;
        }
        catch(PDOException $exception)
        {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }


    public function getDataByAcademicYear($academicYearID,$gender)
    {
        try
        {
            $query = $this->conn->prepare("SELECT 
                COUNT(gender) AS number
          FROM
          student
          WHERE
          academicYearID=:acadID
          AND gender=:gender");
            $query->execute(array(':acadID'=>$academicYearID,':gender' => $gender));
            $row=$query->fetch(PDO::FETCH_ASSOC);
            $value=$row['number'];
            return $value;
        }
        catch(PDOException $exception)
        {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }


    public function getSchoolCount($centerID,$gender)
    {
        try
        {
            $data=0;
            $query=$this->conn->prepare("SELECT 
            count(gender) as number
        from
            student st,
            programmes p,
            center_registration cs,
            student_programme sp
        where
                cs.centerRegistrationID = sp.centerID
                AND p.programmeID =  sp.programmeID
                AND sp.centerID=:centerID
                AND gender=:gnd");
            $query->execute(array(':centerID'=>$centerID,':gnd'=>$gender));
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data=$row['number'];
            }
            return $data;
        } catch (PDOException $ex) {
            echo "Data Eroor".$ex->getMessage();
        }
    }

    /*public function getCurrentDataByProgrammeLevel($programmeLevelID,$academicYearID)
    {
        try
        {
            $query = $this->conn->prepare("SELECT 
                COUNT(gender) AS number
          FROM
          student s,programmes p,programme_level pl
          WHERE
          pl.programmeLevelID=p.programmeLevelID
          AND s.programmeID=p.programmeID
          AND p.programmeLevelID=:proID
          AND academicYearID=:acadID
          AND statusID=:st");
            $query->execute(array(':proID'=>$programmeLevelID,':acadID'=>$academicYearID,':st'=>1));
            $row=$query->fetch(PDO::FETCH_ASSOC);
            $value=$row['number'];
            return $value;
        }
        catch(PDOException $exception)
        {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }*/


    public function getCurrentDataByProgrammeLevel($programmeLevelID,$academicYearID)
    {
        try
        {
            $query = $this->conn->prepare("SELECT 
                COUNT(gender) AS number
          FROM
          student s,programmes p,programme_level pl
          WHERE
          pl.programmeLevelID=p.programmeLevelID
          AND s.programmeID=p.programmeID
          AND p.programmeLevelID=:proID
          AND statusID=:st");
            $query->execute(array(':proID'=>$programmeLevelID,':st'=>1));
            $row=$query->fetch(PDO::FETCH_ASSOC);
            $value=$row['number'];
            return $value;
        }
        catch(PDOException $exception)
        {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getInstructorData($title)
    {
        try
        {
            $query = $this->conn->prepare("SELECT 
                COUNT(*) AS number
          FROM
          instructor
          WHERE
          titleID=:titl
          AND InstructorStatus=:st");
            $query->execute(array(':titl'=>$title,':st'=>1));
            $row=$query->fetch(PDO::FETCH_ASSOC);
            $value=$row['number'];
            return $value;
        }
        catch(PDOException $exception)
        {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getInstructorDataByTime($empType)
    {
        try
        {
            $query = $this->conn->prepare("SELECT 
                COUNT(*) AS number
          FROM
          instructor
          WHERE
          employmentStatusID=:empStatus
          AND InstructorStatus=:st");
            $query->execute(array(':empStatus'=>$empType,':st'=>1));
            $row=$query->fetch(PDO::FETCH_ASSOC);
            $value=$row['number'];
            return $value;
        }
        catch(PDOException $exception)
        {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getSponsorData($spnType)
    {
        try
        {
            $query = $this->conn->prepare("SELECT 
                COUNT(*) AS number
          FROM
          student
          WHERE
          sponsor=:sponsorT
          AND statusID=:st");
            $query->execute(array(':sponsorT'=>$spnType,':st'=>1));
            $row=$query->fetch(PDO::FETCH_ASSOC);
            $value=$row['number'];
            return $value;
        }
        catch(PDOException $exception)
        {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }


    public function getDataByLevel($programmeLevelID,$gender)
    {
        try
        {
            $query = $this->conn->prepare("SELECT 
                COUNT(gender) AS number
          FROM
          student s,student_programme sp,programme_level pl
          WHERE
          pl.programmeLevelID=sp.programmeLevelID
          AND sp.programmeLevelID=:proID
          AND s.statusID=:st
          AND gender=:gnd");
            $query->execute(array(':proID'=>$programmeLevelID,':st'=>1,':gnd'=>$gender));
            $row=$query->fetch(PDO::FETCH_ASSOC);
            $value=$row['number'];
            return $value;
        }
        catch(PDOException $exception)
        {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }


    public function getStudentEntryCount($entry)
    {
        try
        {
            $query = $this->conn->prepare("SELECT 
                COUNT(*) AS number
          FROM
          student 
          WHERE
          statusID=:st
          AND mannerEntryID=:entry");
            $query->execute(array(':st'=>1,':entry'=>$entry));
            $row=$query->fetch(PDO::FETCH_ASSOC);
            $value=$row['number'];
            return $value;
        }
        catch(PDOException $exception)
        {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }

    public function getStudentDisableCount()
    {
        try
        {
            $query = $this->conn->prepare("SELECT 
                COUNT(disabilityStatus) AS number
          FROM
          student 
          WHERE
          disabilityStatus=:st");
            $query->execute(array(':st'=>'Yes'));
            $row=$query->fetch(PDO::FETCH_ASSOC);
            $value=$row['number'];
            return $value;
        }
        catch(PDOException $exception)
        {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }



    public function getInstructorList($roleID,$departmentID)
    {
        if($roleID==4)
        {
            $query = $this->conn->prepare("SELECT instructorID,instructorName,salutation,title,officeNumber,email,phoneNumber,departmentID,instructorStatus,instructorImage,employmentStatus,gender FROM instructor WHERE departmentID=:deptID");
            $query->execute(array(':deptID'=>$departmentID));
        }
        else if($roleID == 9)
        {
            $query = $this->conn->prepare("SELECT instructorID,instructorName,salutation,title,officeNumber,email,phoneNumber,i.departmentID,instructorStatus,instructorImage,employmentStatus,gender FROM instructor i,schools s,departments d where s.schoolID=d.schoolID and d.departmentID=i.departmentID AND  s.schoolID=:deptID");
            $query->execute(array(':deptID'=>$departmentID));
        }

        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
    }


    public function getDeanListExam()
    {
            $query = $this->conn->prepare("SELECT 
    instructorID,
    i.firstName,
    i.lastName,
    salutation,
    titleID,
    officeNumber,
    u.departmentID as officeID,
    i.email,
    i.departmentID,
    i.phoneNumber
FROM
    instructor i,
    users u,
    userroles ur
WHERE
    u.userID = i.userID
        AND u.userID = ur.userID
        AND ur.roleID=:role");
            $query->execute(array(':role'=>9));

        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
    }

    public function getDeanListStudent($userID)
    {
        $query = $this->conn->prepare("SELECT
            instructorID,
            i.firstName,
            i.lastName,
            salutation,
            titleID,
            officeNumber,
            i.departmentID,
            u.departmentID as officeID,
            i.email,
            i.phoneNumber
        FROM
            student s,
            instructor i,
            users u,
            userroles ur,
            programmes p,
            schools sc
        WHERE
            u.userID = i.userID
            AND u.userID = ur.userID
            AND u.userID=s.userID
            AND p.programmeID=s.programmeID
            AND sc.schoolID=p.schoolID
            AND s.userID=:user
            AND ur.roleID = :role");
        $query->execute(array(':user'=>$userID,':role'=>9));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function getHoDListExam()
    {
        $query = $this->conn->prepare("SELECT 
    instructorID,
    i.firstName,
    i.lastName,
    salutation,
    titleID,
    officeNumber,
    i.departmentID,
    u.departmentID as officeID,
    i.email,
    i.phoneNumber
FROM
    instructor i,
    users u,
    userroles ur
WHERE
    u.userID = i.userID
        AND u.userID = ur.userID
        AND ur.roleID=:role");
        $query->execute(array(':role' => 4));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function getHoDListStudent($roleID,$departmentID)
    {
        $query = $this->conn->prepare("SELECT 
    instructorID,
    i.firstName,
    i.lastName,
    salutation,
    titleID,
    officeNumber,
    i.departmentID,
    u.departmentID as officeID,
    i.email,
    i.phoneNumber
FROM
    instructor i,
    users u,
    userroles ur
WHERE
    u.userID = i.userID
        AND u.userID = ur.userID
        AND i.departmentID=:deptID
        AND ur.roleID=:role");
        $query->execute(array(':deptID' => $departmentID, ':role' => 4));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }
    public function getHoDListDean($roleID,$departmentID)
    {
        $query = $this->conn->prepare("SELECT 
    instructorID,
    i.firstName,
    i.lastName,
    salutation,
    titleID,
    officeNumber,
    i.departmentID,
    u.departmentID as officeID,
    i.email,
    i.phoneNumber
FROM
    instructor i,
    users u,
    userroles ur
WHERE
    u.userID = i.userID
        AND u.userID = ur.userID
        AND i.departmentID=:deptID
        AND ur.roleID=:role");
        $query->execute(array(':deptID' => $departmentID, ':role' => 4));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }


    public function getHoDListHoD($roleID,$departmentID)
    {
        $query = $this->conn->prepare("SELECT
    instructorID,
    i.firstName,
    i.lastName,
    salutation,
    titleID,
    officeNumber,
    i.departmentID,
    u.departmentID as officeID,
    i.email,
    i.phoneNumber
FROM
    instructor i,
    users u,
    userroles ur
WHERE
    u.userID = i.userID
        AND u.userID = ur.userID
        AND i.departmentID=:deptID
        AND ur.roleID=:role");
            $query->execute(array(':deptID'=>$departmentID,':role'=>4));

        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
    }


    public function getName($tableName,$attributeID,$attributeID2)
    {
        $data= $this->getRows($tableName,array('where'=>array($attributeID=>$attributeID2),' order_by'=>firstName.' ASC'));
        if(!empty($data))
        {
            foreach($data as $dt)
            {
               $fname=$dt['firstName'];
               $lname=$dt['lastName'];
               $attributeName="$fname $lname";
                return $attributeName;
            }
        }
    }


    public function getExamCategoryMarks($programmeID,$examCategoryID,$value)
    {
        $query = $this->conn->prepare("SELECT $value from exam_category_setting ec,programmes p, programme_level pl
            where pl.programmeLevelID=p.programmeLevelID
            AND ec.programmeLevelID=pl.programmeLevelID
            AND p.programmeID=:pID
            AND examCategoryID=:eCatID");
        $query->execute(array(':pID'=>$programmeID,':eCatID'=>$examCategoryID));
        $row = $query->fetch(PDO::FETCH_ASSOC);
        $marks=$row[$value];
        return $marks;
    }

    public function searchLecturer($search_text)
    {
        try {
            $query = $this->conn->prepare("SELECT *
            FROM instructor 
            WHERE (firstName LIKE :search OR lastName LIKE :search)");
            $query->execute(array(':search' => '%' . $search_text . '%'));

            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        } catch (PDOException $exception) {
            echo "Getting Data error: " . $exception->getMessage();
        }
    }



    public function getAcademicAdvisor($userID)
    {
        $query = $this->conn->prepare("SELECT 
    instructorID,
    i.firstName,
    i.lastName,
    i.salutation,
    i.departmentID
FROM
    instructor i,
    schools s,
    departments d,
    programmes p,
    student st,
    users u,
    userroles ur
WHERE
    s.schoolID=d.schoolID
    AND d.departmentID=i.departmentID
    AND s.schoolID=p.schoolID
    AND u.userID=ur.userID
    AND u.userID=i.userID
    AND p.programmeID=st.programmeID
    AND employmentStatusID=:emID
    AND instructorStatus=:stID
    AND st.userID=:user");
        $query->execute(array(':emID'=>1,':stID'=>1,':user'=>$userID));

        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
    }


    public function getStudentBill($regNumber)
    {
        $query = $this->conn->prepare("SELECT DISTINCT(feesID) as feesID,SUM(amount) as amount,feesDescription
FROM
    student_fees
WHERE
    regNumber=:regNo
    GROUP BY feesID,feesDescription");
        $query->execute(array(':regNo'=>$regNumber));

        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
    }


    public function dateDiff ($d1, $d2) {

        // Return the number of days between the two dates:
        return round(abs(strtotime($d1) - strtotime($d2))/86400);

    } // end function dateDiff


    //get filtered Course Programme
    /*public function getCourseExamProgramme($ID,$semID,$batchID)
    {

        $query = $this->conn->prepare("SELECT DISTINCT(programmeID) from courseprogramme cp,student_course sc,course c where c.courseID=cp.courseID AND c.courseID=sc.courseID AND sc.courseID=:cid and sc.semesterSettingID=:semID and cp.batchID=:baID");
        $query->execute(array(':cid'=>$ID,':semID'=>$semID,':baID'=>$batchID));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }*/

    public function getCourseExamProgramme($ID,$semID)
    {

        $query = $this->conn->prepare("SELECT DISTINCT(s.programmeID) from student s,student_course sc,course c,programmes p where p.programmeID=s.programmeID AND s.registrationNumber=sc.regNumber AND c.courseID=sc.courseID AND sc.courseID=:cid and sc.semesterSettingID=:semID");
        $query->execute(array(':cid'=>$ID,':semID'=>$semID));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    public function getCenterDistinctProgramme($levelID)
    {
        $query = $this->conn->prepare("SELECT DISTINCT programmeID from center_programme where programmeLevelID=:levelID");
        $query->execute(array(':levelID'=>$levelID));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }

    //getMaximumNumber
    public function getMaxRegNumber($programmeID)
    {
        try {
            $query = $this->conn->prepare("SELECT
            MAX(serialNumber) as serialNumber
        from
            exam_number
        where
                programmeID =:majorID");
            $query->execute(array(':majorID' => $programmeID));
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $serialNumber = $row['serialNumber'];
            }
            return $serialNumber;

        } catch (PDOException $ex) {
            echo "Getting Data Error: " . $ex->getMessage();
        }
    }


    public function getStudentNumber($academicYearID,$programmeLevelID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT COUNT(regNumber) as studentNumber FROM student_programme  where academicYearID=:acadID and programmeLevelID=:levelID and currentStatus=:st");
            $query->execute(array(':acadID'=>$academicYearID,':levelID'=>$programmeLevelID,':st'=>1));
            $row=$query->fetch(PDO::FETCH_ASSOC);
            $number=$row['studentNumber'];
            return $number;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }

    }


    public function getIndividualStudentExamResult($courseID,$academicYearID,$levelID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT DISTINCT sc.regNumber from student s, student_course sc
        WHERE s.registrationNumber=sc.regNumber
        AND sc.courseID=:cid
        AND sc.semesterSettingID=:sid
        AND s.batchID=:bid");
            $query->execute(array(':cid'=>$courseID,':sid'=>$semesterID,':bid'=>$batchID));
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }


    public function getCenterMappingCourseList($depID,$acadID,$proID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT DISTINCT
    c.courseID,
    courseCode,
    courseName,
    courseTypeID,
    cp.programmeLevelID,
    p.courseStatusID
FROM
	center_programme cp,
    programmemaping p,
    course c
WHERE
    c.courseID = p.courseID
    AND p.programmeID=cp.programmeID
    AND cp.programmeID=:progID
    AND cp.centerRegistrationID=:centerID
    AND c.courseID NOT IN (SELECT courseID FROM center_programme_course WHERE academicYearID =:acadID AND centerID =:center and programmeID=:pid)
    ");
            $query->execute(array(':progID'=>$proID,':centerID'=>$depID,':acadID'=>$acadID,':center'=>$depID,':pid'=>$proID));
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }

    }


    public function getAdmittedStudentList($center,$academicYearID)
    {
        try {
            if($center=='all') {

                $query = $this->conn->prepare("SELECT DISTINCT(s.registrationNumber),studentID,firstName,middleName,lastName,gender,dateOfBirth,email,admissionNumber,rgStatus,sp.academicYearID from student s,student_programme sp where s.registrationNumber = sp.regNumber and sp.academicYearID=:acadID and currentStatus=:st");
                $query->execute(array(':acadID' => $academicYearID, ':st' => 1));
            }
            else
            {
                $query = $this->conn->prepare("SELECT DISTINCT(s.registrationNumber),studentID,firstName,middleName,lastName,gender,dateOfBirth,email,admissionNumber,rgStatus,sp.academicYearID from student s,student_programme sp where s.registrationNumber = sp.regNumber and centerID=:center and sp.academicYearID=:acadID and currentStatus=:st");
                $query->execute(array(':center' => $center, ':acadID' => $academicYearID, ':st' => 1));
            }
            $data = array();
            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $row;
            }
            return $data;
        }catch(PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }
    }

    public function getCenterMappingProgrammeList($depID)
    {
        try
        {
            $query=$this->conn->prepare("SELECT DISTINCT
	distinct p.programmeID,programmeName
FROM
	center_programme cp,
    programmes p
WHERE
    p.programmeID=cp.programmeID
    AND cp.centerRegistrationID= :centerID");
            $query->execute(array(':centerID'=>$depID));
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }

    }


    public function getInstructor($departmentID)
    {

            $query = $this->conn->prepare("SELECT instructorID,instructorName,salutation,officeNumber,email,phoneNumber,departmentID,instructorStatus FROM instructor where centerID=:center");
            $query->execute(array(':center'=>$departmentID));
        $data=array();
        while($row=$query->fetch(PDO::FETCH_ASSOC))
        {
            $data[]=$row;
        }
        return $data;
    }


    public function getInstructorAssessmentCourse($academicYearID,$instructorID)
    {
        try
        {
                $query = $this->conn->prepare("SELECT DISTINCT centerProgrammeCourseID,c.courseID,courseCode,courseName,courseTypeID,programmeLevelID,programmeID,classNumber,staffID FROM center_programme_course cp,course c where c.courseID=cp.courseID and staffID=:st and academicYearID=:sID");
                $query->execute(array(':st'=>$instructorID,':sID' => $academicYearID));
            $data=array();
            while($row=$query->fetch(PDO::FETCH_ASSOC))
            {
                $data[]=$row;
            }
            return $data;
        }
        catch (PDOException $ex)
        {
            echo "Getting Data Error: ".$ex->getMessage();
        }

    }

    /*public function getStudentProgrammeInfo($regNumber,$academicYearID)
    {

        $query = $this->conn->prepare("SELECT centerName,programmeLevel,programmeName from center_registration cr,programme_level pl, programme p,student_programme sp WHERE cr.centerRegistrationID=sp.centerRegistrationID");
        $query->execute(array(':cid'=>$ID,':semID'=>$semID));
        $data = array();
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $data[] = $row;
        }
        return $data;
    }*/

}
?>
