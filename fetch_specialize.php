<?php 
	require_once 'session.php';
	require_once 'DB.php';
	$db = new DBHelper();
	{ ?>
		<option value="">Select Specialization ...</option>
		<?php 
		$specialization=$db->getRows('xsms_specialization',array('order_by'=>'specializationCode ASC'));
		if(!empty($specialization))
		{
			foreach ( $specialization as $specialization )
			{ ?>
				<option value="<?php echo $specialization['specializationCode'];?>">
					<?php echo $specialization['specializationName'];?>
				</option>
				<?php 
			} 
		} 	
	}
?>
