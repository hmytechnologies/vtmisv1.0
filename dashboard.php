<h1>
    Dashboard
</h1>
<ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Dashboard</li>
</ol>

    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>
                        <?php
                        $total=$db->getStudentAllCount("M")+$db->getStudentAllCount("F");
                        echo $total;
                        ?>
                    </h3>

                    <p>Total</p>
                </div>
                <div class="icon">
                    <i class="ion ion-bag"></i>
                </div>
                <a href="#" class="small-box-footer">100%</a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>
                        <?php
                        echo $db->getStudentAllCount("M");
                        ?>
                    </h3>

                    <p>Male Students</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <span class="small-box-footer"><?php
                    echo round((($db->getStudentAllCount("M"))/$total)*100,2);
                    ?>%
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3><?php
                        echo $db->getStudentAllCount("F");
                        ?></h3>

                    <p>Female Students</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                <span class="small-box-footer">
                    <?php
                    echo round((($db->getStudentAllCount("F"))/$total)*100,2);
                    ?>%
            </div>
        </div>


        <!--<div class="col-lg-2 col-xs-6">
            <div class="small-box bg-blue">
                <div class="inner">
                    <h3><?php
/*                        echo $db->getStudentEntryCount(1);
                        */?></h3>

                    <p>Direct Entry</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <span class="small-box-footer"><?php
/*                    echo round((($db->getStudentEntryCount(1))/$total)*100,2);
                    */?>%
            </div>
        </div>

        <div class="col-lg-2 col-xs-6">
            <div class="small-box bg-purple">
                <div class="inner">
                    <h3><?php
/*                        echo $db->getStudentEntryCount(2);
                        */?></h3>

                    <p>Equivalent Entry</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <span class="small-box-footer"><?php
/*                    echo round((($db->getStudentEntryCount(2))/$total)*100,2);
                    */?>%</span>
            </div>
        </div>-->

        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>
                        <?php
                        echo $db->getStudentDisableCount();
                        ?>
                    </h3>
                    <p>Students with disabilities</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <span class="small-box-footer">
                <?php
                echo round((($db->getStudentDisableCount())/$total)*100,2);
                ?>%</span>
            </div>
        </div>
        <!-- ./col -->



    </div>


    <div class="row">

        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Student Enrolment for Five Years Period</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="comboBarLineChart"></canvas>
                    </div>
                </div>
            </div>

        </div>



        <div class="col-md-6">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Student Enrolment per Programme Levels</h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="chart">
                        <canvas id="lineChart"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- end row -->

<div class="row">
    <div class="col-md-4">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Lecturers Data</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="chart">
                    <canvas id="pieChart" style="height: 300px"></canvas>
                </div>
            </div>

        </div>
    </div>
    <div class="col-md-4">
       <div class="box box-danger">
             <div class="box-header with-border">
                 <h3 class="box-title">Fulltime Vs Parttime</h3>

                 <div class="box-tools pull-right">
                     <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                     </button>
                     <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                 </div>
             </div>
             <div class="box-body">
                 <canvas id="doghoutChart" style="height:300px"></canvas>
             </div>
         </div>

    </div>

    <!--<div class="col-md-4">
        <div class="box box-danger">
            <div class="box-header with-border">
                <h3 class="box-title">Student By Means of Payments</h3>

                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                <canvas id="secondPieChart" style="height:300px"></canvas>
            </div>
        </div>

    </div>-->
</div>
