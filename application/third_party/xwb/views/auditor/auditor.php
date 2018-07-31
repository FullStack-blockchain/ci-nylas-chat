<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>



<div class="row">

    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="panel panel-success">
                <div class="panel-heading">
                  <h3 class="panel-title">Announcement</h3>
                </div>
                <div class="panel-body">
                    <?php echo getConfig('announcement'); ?>
                </div>
        </div>

      </div>
    </div>
    <hr />
    <div class="row">
        

        <?php
        $gauges = [];
        foreach ($gauge_data as $key => $value) :
        ?>
        <div class="col-md-4 col-sm-4 col-xs-12">
          <div class="x_panel tile fixed_height_320">
            <div class="x_title">
              <h2><?php echo $value['request_name']; ?></h2>
              <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a>
                </li>
              </ul>
              <div class="clearfix"></div>
            </div>
            <div class="x_content">
              <div class="dashboard-widget-content">
                <ul class="quick-list">
                    <?php
                    $done = true;
                    $counter = 1;
                    foreach ($progress_label as $k => $v) :
                        if ($done) {
                            $icon = 'check';
                            $color = 'text-success';
                        } else {
                            $icon = 'times';
                            $color = 'text-warning';
                        }
                    ?>
                        <li><i class="fa fa-<?php echo $icon." ".$color; ?>"></i><a href="#"><?php echo $v; ?></a></li>
                    <?php

                    if ($value['status_level'] == $k) {
                        $done = false;
                    }
                    if ($done) {
                        $counter++;
                    }
                    endforeach; ?>
                </ul>

                <div class="sidebar-widget">
                    <h4><strong></strong><?php echo $progress_label[$value['status_level']]; ?></h4>
                    <canvas width="150" height="80" id="chart_gauge_<?php echo $value['req_id']; ?>" class="" style="width: 160px; height: 100px;"></canvas>
                    <div class="goal-wrapper">
                      <span id="gauge-text-<?php echo $value['req_id']; ?>" class="gauge-value pull-left">0</span>
                      <span class="gauge-value pull-left">%</span>
                      <span id="goal-text" class="goal-value pull-right">100%</span>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

    <?php
    $gauges[] = array('chart_gauge_'.$value['req_id'],$counter,$value['req_id']);
        endforeach; ?>
    </div>
</div>
<script src="<?php echo base_url('assets/vendors/gauge.js/dist/gauge.min.js'); ?>"></script>

<!-- Javascript variable from php for this page here -->
<script type="text/javascript">

xwb_var.gauges = <?php echo json_encode($gauges); ?>;
xwb_var.level_count = <?php echo count($progress_label); ?>;

</script>
