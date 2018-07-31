<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!-- top navigation -->
<div class="top_nav">
  <div class="nav_menu">
    <nav>
      <div class="nav toggle">
        <a id="menu_toggle"><i class="fa fa-bars"></i></a>
      </div>

      <ul class="nav navbar-nav navbar-right">
        <li class="">
          <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
            <img src="<?php echo base_url('profile/view_image/?width=58&height=58&path='.$current_user->picture_path); ?>" alt=""><?php echo $current_user->first_name; ?>
            <span class=" fa fa-angle-down"></span>
          </a>
          <ul class="dropdown-menu dropdown-usermenu pull-right">
            <li><a href="<?php echo base_url('profile'); ?>"> Profile</a></li>
            <li>
                <a href="<?php echo base_url('settings'); ?>">
                    <span>Settings</span>
                </a>
            </li>
            <li><a href="<?php echo base_url('help'); ?>">Help</a></li>
            <li><a href="<?php echo base_url('user/auth/logout'); ?>"><i class="fa fa-sign-out pull-right"></i> Log Out</a></li>
          </ul>
        </li>

      </ul>
    </nav>
  </div>
</div>
<!-- /top navigation -->
