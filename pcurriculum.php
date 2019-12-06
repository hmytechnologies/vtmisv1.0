<script src="bootbox/bootbox.min.js" type="text/javascript"></script>
   <script type="text/javascript">
    $(document).ready(function () {
        $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
            localStorage.setItem('activeTab', $(e.target).attr('href'));
        });
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            $('#myTab a[href="' + activeTab + '"]').tab('show');
        }
    });

   
</script>
<script type="text/javascript">
    $(document).ready(function () {
        var titleheader = $('#titleheader').text();
        $("#pcurricullum").DataTable({
            "dom": 'Blfrtip',
            "scrollX":true,
            "paging":true,
            "buttons":[
                {
                    extend:'excel',
                    title: titleheader,
                    footer:false,
                    exportOptions:{
                        columns: [0, 1, 2,3,4,5,6,7]
                    }
                },
                ,
                {
                    extend:'csvHtml5',
                    title: titleheader,
                    customize: function (csv) {
                        return titleheader+"\n"+  csv +"\n";
                    },
                    exportOptions:{
                        columns: [0, 1, 2,3,4,5,6,7]
                    }
                },
                {
                    extend: 'pdfHtml5',
                    title: titleheader,
                    footer: true,
                    exportOptions: {
                        columns: [0, 1, 2, 3,4,5,6,7]
                    },

                }

            ],
            "order": []
        });
    });
</script>
<style type="text/css">
	.bs-example{
		margin: 10px;
	}
</style>
<?php $db=new DBHelper();
?>
<div class="container">
  <h1>Trade Curriculum</h1>
  <hr>
    <div class="col-md-12"> 
    <div class="row">
      
           <h3>Select trade to views its curriculum</h3>
            <div class="row">
            <form name="" method="post" action="">
                       <div class="col-lg-4">
                           <label for="MiddleName">Trade Name<?php echo $_SESSION['role_session'];?></label>
                            <select name="programmeID" class="form-control" required="">
                              <?php
                                if($_SESSION['main_role_session']==7)
                                {
                                    $programmes = $db->getRows('programmes',array('order_by'=>'programmeName ASC'));
                                }
                                else {
                                    $programmes = $db->getCenterMappingProgrammeList($_SESSION['department_session']);
                                }
                               if(!empty($programmes)){ 
                                echo"<option value=''>Please Select Here</option>";
                                $count = 0; foreach($programmes as $prog){ $count++;
                                $programme_name=$prog['programmeName'];
                                $programme_id=$prog['programmeID'];
                               ?>
                               <option value="<?php echo $programme_id;?>"><?php echo $programme_name;?></option>
                               <?php }}
                               else
                               {
                                   ?>
                                   <option value=""><?php echo "No Data Found";?></option>
                                <?php
                               }
           ?>
                           </select>
                        </div>
                      <div class="col-lg-4">
                      <label for=""></label>
                      <input type="submit" name="doSearch" value="View Curriculum" class="btn btn-primary form-control" /></div>
          </form>          
        </div>
        <div class="row">
            <hr>
        </div>

        <div class="row">
        
            <?php
            //Save Records Buttoon

            if((isset($_POST['doSearch'])=="View Curriculum")||(isset($_REQUEST['action'])=="getRecords"))
            {
              $programmeID=$_POST['programmeID'];
              $programmeID=$_REQUEST['programmeID'];
                ?>
                
                <?php
                $data= $db->getRows("programmes",array('where'=>array('programmeID'=>$programmeID),' order_by'=>'programmeName ASC'));
               if(!empty($data))
               { 
                    $i = 0; 
                    foreach($data as $dt)
                    { 
                        $i++;
                        $programme_name=$dt['programmeName'];
                    } 
                }  
                ?>
                <?php 
                    
                       $data= $db->getRows("programmemaping",array('where'=>array('programmeID'=>$programmeID),' order_by'=>'programmeLevelID ASC'));
                       if(!empty($data))
                       {
                       ?>
                       <h4 class="text-info" id="titleheader">List of Registerd Subjects for:<?php echo $programme_name;?></h4>

                       <table  id="pcurricullum" class="display nowrap">
                        <thead>
                        <tr>
                          <th>No.</th>
                          <th>Subject Code</th>
                          <th>Subject Name</th>
                          <th>Subject Type</th>
                            <th>Level</th>
                          <th>Status</th>
                            <th>Outline</th>
                           </tr>
                        </thead>
                        <tbody>
                       <?php  
                            $count = 0; $totalCredits=0;
                            foreach($data as $dt)
                            { 
                                $count++;
                                $programmeMappingID=$dt['programmeMappingID'];
                                $courseID=$dt['courseID'];  
                                $levelID=$dt['programmeLevelID'];
                                $courseStatusID=$dt['courseStatusID'];
                                $courseStatus=$dt['courseStatus'];

                       $course=$db->getRows("course",array('where'=>array('courseID'=>$courseID),' order_by'=>'courseName ASC'));
                       if(!empty($course))
                       {
                            foreach($course as $c)
                            { 
                                $cCode=$c['courseCode'];  
                                $cName=$c['courseName'];
                                $courseOutline=$c['courseOutline'];
                                $courseTypeID=$c['courseTypeID'];
                              }
                      }



                              $course_status = $db->getRows('coursestatus',array('where'=>array('courseStatusID'=>$courseStatusID),'order_by'=>'courseStatus ASC'));
                                 if(!empty($course_status)){
                                  foreach($course_status as $cstatus){
                                  $courseStatusName=$cstatus['courseStatus'];
                                  $courseStatusID=$cstatus['courseStatusID'];
                                }
                              }
                             
                 ?>    
                        <tr>
                            <td><?php echo $count;?></td>
                            <td><?php echo $cCode;?></td>
                            <td><?php echo $cName;?></td>
                            <td><?php echo $db->getData("course_type","courseType","courseTypeID",$courseTypeID);?></td>
                            <td><?php echo $db->getData("programme_level","programmeLevel","programmeLevelID",$levelID);?></td>
                             <td><?php echo $courseStatusName;?></td>
                            <td>
                                <?php
                                if(!empty($courseOutline)) {
                                    ?>
                                <a href="course_outline/<?php echo $courseOutline;?>" class="glyphicon glyphicon-download-alt" target="_blank"></a>
                                    <?php
                                }
                                else
                                {
                                    ?>
                                    Not Uploaded
                                <?php
                                }
                                ?>

                            </td>
                            
                            
                            </tr>
                        
                  <?php
                     
                  }
                    ?>
                    </tbody>
                       </table>
        </div>

                    <?php
               }
                else
                {
                  echo "<h4 class='text-danger'>No Registered Subject</h4>";
                }
            }

                ?>
            
    
    </div>

        

  </div>
  </div>

  