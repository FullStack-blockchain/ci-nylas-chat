<nav id="toolbar" class="bg-white">

    <div class="row no-gutters align-items-center flex-nowrap">

        <div class="col col-md">

            <div class="row no-gutters align-items-center flex-nowrap">

                <button type="button" class="toggle-aside-button btn btn-icon d-block d-lg-none"
                        data-fuse-bar-toggle="aside">
                    <i class="icon icon-menu"></i>
                </button>

                <div class="toolbar-separator d-block d-lg-none m-hidden-search"></div>
                <a href="<?php echo admin_url('utilities/file_manager'); ?>" title="File Manager" class="header-icon-button btn btn-icon m-hidden-search"
                    data-placement="bottom">
                    <i class="fa fa-folder f-24"></i>
                </a>
                <div class="toolbar-separator"></div>

                <button type="button" id="main-header-search-button" class="search-button btn btn-icon">
                    <i class="icon icon-magnify"></i>
                </button>

                <div class="search-section fade" data-toggle="tooltip" data-placement="bottom"
                     data-title="<?php echo _l('search_by_tags'); ?>">
                    <input type="search" id="search_input" class="form-control"
                           placeholder="<?php echo _l('top_search_placeholder'); ?>">
                    <div id="search_results"></div>
                    <div id="top_search_button">
                        <button class="btn"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                <div class="toolbar-separator m-hidden-search"></div>

            </div>
        </div>

        <div class="col-auto m-hidden-search">

            <div class="row no-gutters align-items-center justify-content-end">                
                <div class="user-menu-button dropdown">

                    <div class="dropdown-toggle ripple row align-items-center no-gutters px-2 px-sm-4" role="button"
                         id="dropdownUserMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="avatar-wrapper">
                            <?php echo staff_profile_image($current_user->staffid, array('img', 'img-responsive avatar', 'staff-profile-image-small', 'pull-left')); ?>
                        </div>
                        <span class="username mx-3 d-none d-md-block"><?php echo get_staff_full_name(); ?></span>
                    </div>

                    <div class="dropdown-menu" aria-labelledby="dropdownUserMenu">

                        <a class="dropdown-item" href="<?php echo admin_url('profile'); ?>">
                            <div class="row no-gutters align-items-center flex-nowrap">
                                <i class="icon-account"></i>
                                <span class="px-3"><?php echo _l('nav_my_profile'); ?></span>
                            </div>
                        </a>

                        <a class="dropdown-item" href="<?php echo admin_url('staff/timesheets'); ?>">
                            <div class="row no-gutters align-items-center flex-nowrap">
                                <i class="icon-clock"></i>
                                <span class="px-3"><?php echo _l('my_timesheets'); ?></span>
                            </div>
                        </a>

                        <a class="dropdown-item" href="<?php echo admin_url('staff/edit_profile'); ?>">
                            <div class="row no-gutters align-items-center flex-nowrap">
                                <i class="icon-account-edit"></i>
                                <span class="px-3"><?php echo _l('nav_edit_profile'); ?></span>
                            </div>
                        </a>

                        <div class="dropdown-divider"></div>

                        <a class="dropdown-item" href="#" onclick="logout(); return false;">
                            <div class="row no-gutters align-items-center flex-nowrap">
                                <i class="icon-logout"></i>
                                <span class="px-3"><?php echo _l('nav_logout'); ?></span>
                            </div>
                        </a>

                    </div>
                </div>

                <?php if (get_option('disable_language') == 0) { ?>
                    <div class="toolbar-separator d-none d-md-block "></div>

                    <div class="language-button dropdown d-none d-md-block">

                        <div class="dropdown-toggle ripple row align-items-center justify-content-center no-gutters px-0 px-sm-4"
                             role="button" id="dropdownLanguageMenu" data-toggle="dropdown" aria-haspopup="true"
                             aria-expanded="false">
                            <div class="row no-gutters align-items-center">
                                <span><?php echo ucfirst($current_user->default_language); ?></span>
                            </div>
                        </div>

                        <div class="dropdown-menu language-dropdown pull-right" aria-labelledby="dropdownLanguageMenu">

                            <a class="dropdown-item <?php if ($current_user->default_language == "") {
                                echo 'active';
                            } ?>" href="<?php echo admin_url('staff/change_language'); ?>">
                                <div class="row no-gutters align-items-center flex-nowrap">
                                    <span class="px-3"><?php echo _l('system_default_string'); ?></span>
                                </div>
                            </a>
                            <?php foreach ($this->app->get_available_languages() as $user_lang) { ?>
                                <a class="dropdown-item <?php if ($current_user->default_language == $user_lang) {
                                    echo 'active"';
                                } ?>" href="<?php echo admin_url('staff/change_language/' . $user_lang); ?>">
                                    <div class="row no-gutters align-items-center flex-nowrap">
                                        <span class="px-3"><?php echo ucfirst($user_lang); ?></span>
                                    </div>
                                </a>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>

                <?php if (is_staff_member()) { ?>
                    <div class="toolbar-separator d-none d-md-block"></div>

                    <button type="button" class="header-icon-button btn btn-icon open_newsfeed d-none d-md-block" data-toggle="tooltip"
                            title="<?php echo _l('whats_on_your_mind'); ?>" data-placement="bottom">
                        <i class="icon icon-share-variant"></i>
                    </button>
                <?php } ?>

                <div class="toolbar-separator d-none d-md-block"></div>

                <a href="<?php echo admin_url('todo'); ?>" class="header-icon-button btn btn-icon d-none d-md-block" data-toggle="tooltip"
                   title="<?php echo _l('nav_todo_items'); ?>" data-placement="bottom">
                    <i class="fa fa-check-square-o f-24"></i>
                    <span class="label bg-warning icon-total-indicator nav-total-todos<?php if ($current_user->total_unfinished_todos == 0) {
                        echo ' hide';
                    } ?>"><?php echo $current_user->total_unfinished_todos; ?></span>
                </a>

                <div class="toolbar-separator"></div>

                <div class="timer-dropdown" data-toggle="tooltip" title="<?php echo _l('nav_todo_items'); ?>"
                     data-placement="bottom">
                    <button href="#" class="dropdown-toggle header-icon-button btn btn-icon" data-toggle="dropdown">
                        <i class="fa fa-clock-o f-28"></i>
                        <span class="label bg-success icon-total-indicator icon-started-timers<?php if ($totalTimers = count($startedTimers) == 0) {
                            echo ' hide';
                        } ?>">
                             <?php echo count($startedTimers); ?>
                        </span>
                    </button>
                    <ul class="dropdown-menu animated fadeIn started-timers-top width350" id="started-timers-top">
                        <?php $this->load->view('admin/tasks/started_timers', array('startedTimers' => $startedTimers)); ?>
                    </ul>
                </div>

                <div class="toolbar-separator"></div>

                <div class="notifications-dropdown pull-right" data-toggle="tooltip" title="<?php echo _l('nav_notifications'); ?>" data-placement="bottom">
                    <button href="#" class="dropdown-toggle header-icon-button btn btn-icon" data-toggle="dropdown">
                        <i class="fa fa-bell-o f-24"></i>
                        <?php if ($current_user->total_unread_notifications > 0) { ?>
                            <span class="label icon-total-indicator bg-warning icon-notifications"><?php echo $current_user->total_unread_notifications; ?></span>
                        <?php } ?>
                    </button>
                    <?php $this->load->view('admin/includes_fuse/notifications'); ?>
                </div>
                <div class="clear-fix"></div>
            </div>
        </div>
    </div>
</nav>