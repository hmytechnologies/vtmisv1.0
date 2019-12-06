<?php
$db=new DBHelper();
        $organization = $db->getRows('organization',array('order_by'=>'organizationName DESC'));
        if(!empty($organization))
        {
            foreach($organization as $org)
            {
                $organizationName=$org['organizationName'];
                $organizationCode=$org['organizationCode'];
                $organizationPicture="img/".$org['organizationPicture'];
            }
        }
        else
        {
            $organizationName="Soft Dev Academy";
            $organizationCode="SDVA";
            $organizationPicture="img/SkyChuo.png";
        }
        ?>
<footer class="main-footer navbar-fixed-bottom">
        <!-- To the right -->
        <div class="pull-right hidden-xs">
            This product is licensed to <?php
            echo $organizationName."(".$organizationCode.")";
            ?>
        </div>
        <!-- Default to the left -->
        <strong>Copyright &copy;2017-<?php echo date('Y');?></strong>
      </footer>