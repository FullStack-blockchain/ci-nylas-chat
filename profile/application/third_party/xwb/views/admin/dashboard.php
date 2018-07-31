<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="row">
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
          <div class="icon"><i class="fa fa-list"></i></div>
          <div class="count"><?php echo $new_requests->num_rows(); ?></div>
          <h3>New Requests</h3>
          <p>Requests created by Users subject for approval</p>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
          <div class="icon"><i class="fa fa-check"></i></div>
          <div class="count"><?php echo $new_reqapproval->num_rows(); ?></div>
          <h3>Head / Recommending Approval</h3>
          <p>Requests for approval by the Department Head</p>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
          <div class="icon"><i class="fa fa-sort-amount-desc"></i></div>
          <div class="count"><?php echo $to_canvass->num_rows(); ?></div>
          <h3>Canvass</h3>
          <p>Requests processed and endorsed for canvassing</p>
        </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="tile-stats">
          <div class="icon"><i class="fa fa-money"></i></div>
          <div class="count"><?php echo $req_budget_to_apprvoe->num_rows(); ?></div>
          <h3>Budget Approval</h3>
          <p>Requests pending Budget Approval</p>
        </div>
    </div>
</div>



<!-- On going Request -->
<div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Request Created  <small>1 Week progress</small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                        </ul>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <div class="col-md-9 col-sm-12 col-xs-12">
                      <div class="demo-container" style="height:280px">
                        <div id="chart_ongoing_req" class="ongoing-req-placeholder" style="height:280px"></div>
                      </div>
                    </div>

                    <div class="col-md-3 col-sm-12 col-xs-12">
                      <div>
                        <div class="x_title">
                          <h2>Users Requested</h2>
                          <div class="clearfix"></div>
                        </div>
                        <ul class="list-unstyled top_profiles scroll-view">
                        <?php

                        foreach ($graph_users as $key => $value) : ?>
                          <li class="media event">
                            <div class="media-body">
                              <a class="title" href="#"><?php echo ucwords($value[0]); ?></a>
                              <p> <strong><?php echo ucwords($value[2]); ?></strong></p>
                              <p> <small><?php echo ucwords($value[1]); ?></small></p>
                            </div>
                          </li>  
                        <?php endforeach; ?>
                        </ul>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>

<!-- Chart.js -->
<script src="<?php echo base_url('assets/vendors/Chart.js/dist/Chart.min.js'); ?>"></script>

<!-- Sparkline -->
<script src="<?php echo base_url('assets/vendors/jquery-sparkline/dist/jquery.sparkline.min.js'); ?>"></script>

<!-- Flot -->
<script src="<?php echo base_url('assets/vendors/Flot/jquery.flot.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendors/Flot/jquery.flot.pie.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendors/Flot/jquery.flot.time.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendors/Flot/jquery.flot.stack.js'); ?>"></script>
<script src="<?php echo base_url('assets/vendors/Flot/jquery.flot.resize.js'); ?>"></script>

<script src="<?php echo base_url('assets/vendors/DateJS/build/date.js'); ?>"></script>


<!-- Javascript variable from php for this page here -->
<script type="text/javascript">

xwb_var.graph_data = <?php echo json_encode($graph_data); ?>;


</script>