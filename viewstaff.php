<?php 
$db= new DBHelper();
?>
	<div class="row">
		<div class="col-xl-12">
			<div class="breadcrumb-holder">
				<h1 class="main-title float-left">Staff Registration</h1>
				<div class="clearfix">
                    <hr>
                </div>
			</div>
		</div>
	</div>
	<!-- end row -->

	<div class="row">		
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">						
			<?php 
			if(!empty($_REQUEST['msg']))
			{
				if($_REQUEST['msg']=="inserted")
				{
					echo "<div class='alert alert-success alert-dismissible fade show'>
							<a  class='close' data-dismiss='alert'>&times;</a>
							<strong>Successfully registered</strong>.
						</div>";
				}
				else if($_REQUEST['msg']=="exist")
				{
					echo "<div class='alert alert-danger alert-dismissible fade show'>
							<a  class='close' data-dismiss='alert'>&times;</a>
							<strong>Staff already Exist!!!</strong>.
						</div>";
				}
				else if($_REQUEST['msg']=="blocked")
				{
					echo "<div class='alert alert-danger alert-dismissible fade show'>
							<a  class='close' data-dismiss='alert'>&times;</a>
							<strong>Staff has been blocked Successfully!!!</strong>.
						</div>";
				}
				else if($_REQUEST['msg']=="unblocked")
				{
					echo "<div class='alert alert-success alert-dismissible fade show'>
							<a  class='close' data-dismiss='alert'>&times;</a>
							<strong>Staff has been unblocked successfully</strong>.
						</div>";
				}
				else if($_REQUEST['msg']=="updated")
				{
					echo "<div class='alert alert-success alert-dismissible fade show'>
							<a  class='close' data-dismiss='alert'>&times;</a>
							<strong>Staff Information has been edited successfully</strong>.
						</div>";
				}
			}?>
				<div class="card-body">
					<div class="table-responsive">
						<table id="example" class="table table-bordered table-hover" width="100%">
							<thead>
							  <tr>
								<th style="width:50px">Reg. No.</th>
								<th style="width:150px">PFnO</th>
								<th style="width:150px">Staff Name</th>
								<th style="width:150px">Gender</th>
								<th style="width:150px">Date Of Birth</th>
								<th style="width:150px">Address</th>
								<th style="width:150px">Shehia</th>
								<th style="width:120px">Actions</th>
							  </tr>
							</thead>
							<tbody>
							<?php
					          $teacher=$db->getRows('xsms_teacher',array('order_by'=>'teacherCode DESC'));
					          if (!empty($teacher)){$count=0;foreach ($teacher as $teacher)
					          {$count++;
					          ?>
								<tr >
									<th><?php echo $teacher['teacherCode'];?></th>
									<td><?php echo $teacher['pfNo'];?></td>
									<td><?php echo $teacher['firstName']." ".$teacher['middleName']." ".$teacher['lastName'];?></td>
									<td><?php echo $teacher['sex'];?></td>
									<td><?php echo $teacher['dateOfBirth'];?></td>
									<td><?php echo $teacher['physicalAddress'];?></td>
									<td><?php echo $db->getData('ddx_shehia','shehiaName','shehiaCode',$teacher['shehiaID']);?></td>
									<td>
										<a href="index3.php?sp=edit_staff&id=<?php echo $db->my_simple_crypt($teacher['teacherCode'],'e'); ?>" class="btn btn-primary btn-sm">
											<i class="fa fa-pencil" aria-hidden="true"></i>
										</a>
										<?php 
							          		$st=$teacher['status']; 
							          		if ($st==1){?>
							          			<a onclick="return confirm('Are Sure You Want To Block This Staff?')" href="action_teacher.php?block=<?php echo $db->my_simple_crypt($teacher['teacherCode'],'e'); ?>" class="btn btn-danger btn-sm" data-placement="top" data-toggle="tooltip" data-title="Block">
							          				<i class="fa fa-lock" aria-hidden="true"></i>
							          			</a>
							          	<?php }else {?>
							          			<a onclick="return confirm('Are Sure You Want To UnBlock This Staff?')" href="action_teacher.php?unblock=<?php echo $db->my_simple_crypt($teacher['teacherCode'],'e'); ?>" class="btn btn-warning btn-sm" data-placement="top" data-toggle="tooltip" data-title="UnBlock">
							          				<i class="fa fa-unlock" aria-hidden="true"></i>
							          			</a>
							          	<?php }?>
									</td>
								</tr>
								<?php }} ?>
							</tbody>
						</table>
					</div>	
				</div>	


	