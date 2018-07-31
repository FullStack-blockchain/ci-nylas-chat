<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<div class="left_col scroll-view">
    <div class="navbar nav_title" style="border: 0;">
      <a href="<?php echo base_url(''); ?>" class="site_title">
        <?php if (getConfig('logo_to_use')=='logo') : ?>
            <div id="preview" class="center">
                <img src="<?php echo base_url('view_image/?path='.getConfig('logo')); ?>" alt="<?php echo (getConfig('company_name')==''?'Company Name':getConfig('company_name')); ?>" class="img-responsive" id="company_logo">
            </div>
        <?php else : ?>
            <h3 class="compony_logo_name">
                <?php echo (getConfig('company_name')==''?'Company Name':getConfig('company_name')); ?>
            </h3>
        <?php endif; ?>
      </a>
    </div>

    <div class="clearfix"></div>

    <!-- menu profile quick info -->
    <div class="profile clearfix">
      <div class="profile_pic">
        <img src="<?php echo base_url('profile/view_image/?width=58&height=58&path='.$current_user->picture_path); ?>" alt="..." class="img-circle profile_img">
      </div>
      <div class="profile_info">
        <span>Welcome,</span>
        <h2><?php echo $current_user->first_name; ?></h2>
      </div>
    </div>
    <!-- /menu profile quick info -->

    <br />

    <!-- sidebar menu -->
    <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
      <div class="menu_section">
        <h3>Menu</h3>
        <ul class="nav side-menu">
                    <li><a href="<?php echo base_url(''); ?>"><i class="fa fa-laptop"></i> Dashboard </a></li>

                    <li><a href="javascript:;"><i class="fa fa-list"></i> Request <span class="badge badge-success pull-right"><?php echo $notification->my_req_action; ?></span><span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <?php if ($current_user->group_name!='property') : ?>
                                <li><a href="<?php echo base_url('request/reqlist'); ?>">All Requests
                                    <span class="badge badge-success pull-right"></span>
                                </a></li>
                            <?php endif; ?>
                            <?php if ($current_user->department_head == 1) : ?>
                                <li><a href="<?php echo base_url('request/staff_request'); ?>">Staff Requests </a></li>
                            <?php endif; ?>
                            <li><a href="<?php echo base_url('request'); ?>">My Requests <span class="badge badge-success pull-right"><?php echo $notification->my_req_action; ?></span> </a></li>
                            <li><a href="<?php echo base_url('request/newreq'); ?>">Requests New </a></li>
                        </ul>
                    </li>

                    <?php if ($current_user->department_head == 1) : ?>
                      <li><a href="<?php echo base_url('approval/for_approval'); ?>"><i class="fa fa-check-circle"></i> Head approval <span class="badge badge-success pull-right"><?php echo $notification->head_approval_action; ?></span> </a></li>
                      <li><a href="javascript:;"><i class="fa fa-bar-chart"></i> Staff Report <span class="fa fa-chevron-down"></span></a>
                          <ul class="nav child_menu">
                              <li><a href="<?php echo base_url('reports/staff_request'); ?>">Request Reports </a></li>
                              <li><a href="<?php echo base_url('reports/staff_items'); ?>">Item Reports </a></li>
                              <li><a href="<?php echo base_url('reports/staff_po'); ?>">PO Reports</a></li>
                          </ul>
                      </li>
                    <?php endif; ?>
                    <?php if ($current_user->group_name=='property') : ?>
                      <li><a href="<?php echo base_url('property/request_done'); ?>"><i class="fa fa-check-circle"></i> Request Done <span class="badge badge-success pull-right"><?php echo $notification->property_action; ?></span> </a></li>
                    <?php endif; ?>
                    <?php if ($current_user->group_name=='canvasser') : ?>
                      <li><a href="<?php echo base_url('canvasser/req_assign'); ?>"><i class="fa fa-database"></i> Requests Assigned <span class="badge badge-success pull-right"><?php echo $notification->canvass_action; ?></span></a></li>
                    <?php endif; ?>
                    <?php if ($current_user->group_name=='budget') : ?>
                      <li><a href="<?php echo base_url('budget/req_approval'); ?>"><i class="fa fa-database"></i> Budget Approval <span class="badge badge-success pull-right"><?php echo $notification->budget_action; ?></span></a></li>
                    <?php endif; ?>
                    <?php if ($current_user->group_name=='auditor') : ?>
                      <li><a href="<?php echo base_url('auditor/audit_list'); ?>"><i class="fa fa-database"></i> For Audit <span class="badge badge-success pull-right"><?php echo $notification->auditor_action; ?></span></a></li>
                    <?php endif; ?>
                    <?php if ($current_user->group_name=='budget') : ?>
                      <li><a href="javascript:;"><i class="fa fa-bar-chart"></i> Reports <span class="fa fa-chevron-down"></span></a>
                          <ul class="nav child_menu">
                              <li><a href="<?php echo base_url('reports/request'); ?>">Request Reports </a></li>
                              <li><a href="<?php echo base_url('reports/items'); ?>">Item Reports </a></li>
                              <li><a href="<?php echo base_url('reports/po'); ?>">PO Reports</a></li>
                          </ul>
                      </li>
                    <?php endif; ?>
                </ul>
      </div>
    </div>
    <!-- /sidebar menu -->

    <!-- /menu footer buttons -->
    <div class="sidebar-footer hidden-small">
      <a data-toggle="tooltip" data-placement="top" title="Settings" href="<?php echo base_url('settings'); ?>">
        <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
      </a>
      <a data-toggle="tooltip" data-placement="top" title="Profile" href="<?php echo base_url('profile'); ?>">
        <span class="glyphicon glyphicon-user" aria-hidden="true"></span>
      </a>
      <a data-toggle="tooltip" data-placement="top" title="Help" href="<?php echo base_url('help'); ?>">
        <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>
      </a>
      <a data-toggle="tooltip" data-placement="top" title="Logout" href="<?php echo base_url('user/auth/logout'); ?>">
        <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
      </a>
    </div>
    <!-- /menu footer buttons -->
    </div>
</div>
