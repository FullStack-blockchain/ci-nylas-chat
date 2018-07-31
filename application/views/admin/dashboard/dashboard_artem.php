<?php init_single_head(); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/dashboard.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside_artem(); ?>

        <div class="content-wrapper">
                <nav id="toolbar" class="bg-white">

                    <div class="row no-gutters align-items-center flex-nowrap">

                        <div class="col">

                            <div class="row no-gutters align-items-center flex-nowrap">

                                <button type="button" class="toggle-aside-button btn btn-icon d-block d-lg-none" data-fuse-bar-toggle="aside">
                                    <i class="icon icon-menu"></i>
                                </button>

                                <div class="toolbar-separator d-block d-lg-none"></div>

                                <div class="shortcuts-wrapper row no-gutters align-items-center px-0 px-sm-2">

                                    <div class="shortcuts row no-gutters align-items-center d-none d-md-flex">

                                        <a href="apps-chat.html" class="shortcut-button btn btn-icon mx-1">
                                            <i class="icon icon-hangouts"></i>
                                        </a>

                                        <a href="apps-contacts.html" class="shortcut-button btn btn-icon mx-1">
                                            <i class="icon icon-account-box"></i>
                                        </a>

                                        <a href="apps-mail.html" class="shortcut-button btn btn-icon mx-1">
                                            <i class="icon icon-email"></i>
                                        </a>

                                    </div>

                                    <div class="add-shortcut-menu-button dropdown px-1 px-sm-3">

                                        <div class="dropdown-toggle btn btn-icon" role="button" id="dropdownShortcutMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="icon icon-star text-amber-600"></i>
                                        </div>

                                        <div class="dropdown-menu" aria-labelledby="dropdownShortcutMenu">

                                            <a class="dropdown-item" href="#">
                                                <div class="row no-gutters align-items-center justify-content-between flex-nowrap">
                                                    <div class="row no-gutters align-items-center flex-nowrap">
                                                        <i class="icon icon-calendar-today"></i>
                                                        <span class="px-3">Calendar</span>
                                                    </div>
                                                    <i class="icon icon-pin s-5 ml-2"></i>
                                                </div>
                                            </a>

                                            <a class="dropdown-item" href="#">
                                                <div class="row no-gutters align-items-center justify-content-between flex-nowrap">
                                                    <div class="row no-gutters align-items-center flex-nowrap">
                                                        <i class="icon icon-folder"></i>
                                                        <span class="px-3">File Manager</span>
                                                    </div>
                                                    <i class="icon icon-pin s-5 ml-2"></i>
                                                </div>
                                            </a>

                                            <a class="dropdown-item" href="#">
                                                <div class="row no-gutters align-items-center justify-content-between flex-nowrap">
                                                    <div class="row no-gutters align-items-center flex-nowrap">
                                                        <i class="icon icon-checkbox-marked"></i>
                                                        <span class="px-3">To-Do</span>
                                                    </div>
                                                    <i class="icon icon-pin s-5 ml-2"></i>
                                                </div>
                                            </a>

                                        </div>
                                    </div>
                                </div>

                                <div class="toolbar-separator"></div>

                            </div>
                        </div>

                        <div class="col-auto">

                            <div class="row no-gutters align-items-center justify-content-end">

                                <div class="user-menu-button dropdown">

                                    <div class="dropdown-toggle ripple row align-items-center no-gutters px-2 px-sm-4" role="button" id="dropdownUserMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div class="avatar-wrapper">
                                            <img class="avatar" src="../assets/images/avatars/profile.jpg">
                                            <i class="status text-green icon-checkbox-marked-circle s-4"></i>
                                        </div>
                                        <span class="username mx-3 d-none d-md-block">John Doe</span>
                                    </div>

                                    <div class="dropdown-menu" aria-labelledby="dropdownUserMenu">

                                        <a class="dropdown-item" href="#">
                                            <div class="row no-gutters align-items-center flex-nowrap">
                                                <i class="icon-account"></i>
                                                <span class="px-3">My Profile</span>
                                            </div>
                                        </a>

                                        <a class="dropdown-item" href="#">
                                            <div class="row no-gutters align-items-center flex-nowrap">
                                                <i class="icon-email"></i>
                                                <span class="px-3">Inbox</span>
                                            </div>
                                        </a>

                                        <a class="dropdown-item" href="#">
                                            <div class="row no-gutters align-items-center flex-nowrap">
                                                <i class="status text-green icon-checkbox-marked-circle"></i>
                                                <span class="px-3">Online</span>
                                            </div>
                                        </a>

                                        <div class="dropdown-divider"></div>

                                        <a class="dropdown-item" href="#">
                                            <div class="row no-gutters align-items-center flex-nowrap">
                                                <i class="icon-logout"></i>
                                                <span class="px-3">Logout</span>
                                            </div>
                                        </a>

                                    </div>
                                </div>

                                <div class="toolbar-separator"></div>

                                <button type="button" class="search-button btn btn-icon">
                                    <i class="icon icon-magnify"></i>
                                </button>

                                <div class="toolbar-separator"></div>

                                <div class="language-button dropdown">

                                    <div class="dropdown-toggle ripple row align-items-center justify-content-center no-gutters px-0 px-sm-4" role="button" id="dropdownLanguageMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <div class="row no-gutters align-items-center">
                                            <img class="flag mr-2" src="../assets/images/flags/us.png">
                                            <span class="d-none d-md-block">EN</span>
                                        </div>
                                    </div>

                                    <div class="dropdown-menu" aria-labelledby="dropdownLanguageMenu">

                                        <a class="dropdown-item" href="#">
                                            <div class="row no-gutters align-items-center flex-nowrap">
                                                <img class="flag" src="../assets/images/flags/us.png">
                                                <span class="px-3">English</span>
                                            </div>
                                        </a>

                                        <a class="dropdown-item" href="#">
                                            <div class="row no-gutters align-items-center flex-nowrap">
                                                <img class="flag" src="../assets/images/flags/es.png">
                                                <span class="px-3">Spanish</span>
                                            </div>
                                        </a>

                                        <a class="dropdown-item" href="#">
                                            <div class="row no-gutters align-items-center flex-nowrap">
                                                <img class="flag" src="../assets/images/flags/tr.png">
                                                <span class="px-3">Turkish</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <div class="toolbar-separator"></div>

                                <button type="button" class="quick-panel-button btn btn-icon" data-fuse-bar-toggle="quick-panel-sidebar">
                                    <i class="icon icon-format-list-bulleted"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </nav>
                <div class="content custom-scrollbar">

                    <div id="project-dashboard" class="page-layout simple right-sidebar">

                        <div class="page-content-wrapper custom-scrollbar">

                            <!-- HEADER -->
                            <div class="page-header bg-primary text-auto d-flex flex-column justify-content-between px-6 pt-4 pb-0">

                                <div class="row no-gutters align-items-start justify-content-between flex-nowrap">

                                    <div>
                                        <span class="h2">Welcome back, John!</span>
                                    </div>

                                    <button type="button" class="sidebar-toggle-button btn btn-icon d-block d-xl-none" data-fuse-bar-toggle="dashboard-project-sidebar" aria-label="Toggle sidebar">
                                        <i class="icon icon-menu"></i>
                                    </button>
                                </div>

                                <div class="row no-gutters align-items-center project-selection">

                                    <div class="selected-project h6 px-4 py-2">ACME Corp. Backend App</div>

                                    <div class="project-selector">
                                        <button type="button" class="btn btn-icon">
                                            <i class="icon icon-dots-horizontal"></i>
                                        </button>
                                    </div>

                                </div>

                            </div>
                            <!-- / HEADER -->

                            <!-- CONTENT -->
                            <div class="page-content">

                                <ul class="nav nav-tabs" id="myTab" role="tablist">

                                    <li class="nav-item">
                                        <a class="nav-link btn active" id="home-tab" data-toggle="tab" href="#home-tab-pane" role="tab" aria-controls="home-tab-pane" aria-expanded="true">Home</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link btn" id="budget-summary-tab" data-toggle="tab" href="#budget-summary-tab-pane" role="tab" aria-controls="budget-summary-tab-pane">Budget Summary</a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link btn" id="team-members-tab" data-toggle="tab" href="#team-members-tab-pane" role="tab" aria-controls="team-members-tab-pane">Team Members</a>
                                    </li>
                                </ul>

                                <div class="tab-content">
                                    <div class="tab-pane fade show active p-3" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab">
                                    </div>
                                    <div class="tab-pane fade p-3" id="budget-summary-tab-pane" role="tabpanel" aria-labelledby="budget-summary-tab">
                                    </div>
                                    <div class="tab-pane fade p-6" id="team-members-tab-pane" role="tabpanel" aria-labelledby="team-members-tab">
                                    </div>
                                </div>

                            </div>
                            <!-- / CONTENT -->

                        </div>

                        <aside class="page-sidebar custom-scrollbar" data-fuse-bar="dashboard-project-sidebar" data-fuse-bar-media-step="lg" data-fuse-bar-position="right">
                            <!-- WIDGET GROUP -->
                            <div class="widget-group">

                                <!-- NOW WIDGET -->
                                <div class="widget widget-now">

                                    <div class="widget-header row no-gutters align-items-center justify-content-between pl-4 py-4">

                                        <div class="h6">Monday, 16:37:16</div>

                                        <button type="button" class="btn btn-icon" aria-label="Options">
                                            <i class="icon icon-dots-vertical"></i>
                                        </button>
                                    </div>

                                    <div class="widget-content d-flex flex-column align-items-center justify-content-center p-4 pb-6">
                                        <div class="month text-muted">May</div>
                                        <div class="day text-muted">8</div>
                                        <div class="year text-muted">2017</div>
                                    </div>

                                </div>
                                <!-- / NOW WIDGET -->

                                <div class="divider"></div>

                                <!-- WEATHER WIDGET -->
                                <div class="widget widget-weather">

                                    <div class="widget- header row no-gutters align-items-center justify-content-between pl-4 py-4">

                                        <div class="row no-gutters align-items-center">
                                            <i class="icon-map-marker mr-2"></i>
                                            <span class="h6">New York</span>
                                        </div>

                                        <button type="button" class="btn btn-icon" aria-label="Options">
                                            <i class="icon icon-dots-vertical"></i>
                                        </button>

                                    </div>

                                    <div class="d-flex flex-column align-items-center justify-content-center p-4 pb-8">

                                        <div class="today-weather row no-gutters align-items-center justify-content-center">
                                            <i class="icon-weather-pouring s-10 mr-4"></i>
                                            <span class="h1">22</span>
                                            <span class="h1 text-muted">&deg;</span>
                                            <span class="h1 text-muted">C</span>
                                        </div>

                                    </div>

                                    <div class="row no-gutters align-items-center justify-content-between p-4">

                                        <div class="row no-gutters align-items-center">
                                            <i class="icon-weather-windy icon mr-2 s-5"></i>
                                            <span>12</span>
                                            <span class="text-muted ml-1">KMH</span>
                                        </div>

                                        <div class="row align-items-center">
                                            <i class="icon-compass-outline icon mr-2 s-5"></i>
                                            <span>NW</span>
                                        </div>

                                        <div class="row no-gutters align-items-center">
                                            <i class="icon-umbrella icon mr-2 s-5"></i>
                                            <span>98%</span>
                                        </div>

                                    </div>

                                    <div class="row no-gutters align-items-center justify-content-between p-4">

                                        <span class="">Sunday</span>

                                        <div class="row no-gutters align-items-center">
                                            <i class="mr-4 icon-weather-pouring"></i>
                                            <span class="h5">21</span>
                                            <span class="h5 text-muted">&deg;</span>
                                            <span class="h5 text-muted">C</span>
                                        </div>

                                    </div>

                                    <div class="row no-gutters align-items-center justify-content-between p-4">

                                        <span class="">Sunday</span>

                                        <div class="row no-gutters align-items-center">
                                            <i class="mr-4 icon-weather-pouring"></i>
                                            <span class="h5">19</span>
                                            <span class="h5 text-muted">&deg;</span>
                                            <span class="h5 text-muted">C</span>
                                        </div>

                                    </div>

                                    <div class="row no-gutters align-items-center justify-content-between p-4">

                                        <span class="">Tuesday</span>

                                        <div class="row no-gutters align-items-center">
                                            <i class="mr-4 icon-weather-partlycloudy"></i>
                                            <span class="h5">24</span>
                                            <span class="h5 text-muted">&deg;</span>
                                            <span class="h5 text-muted">C</span>
                                        </div>

                                    </div>
                                </div>
                                <!-- / WEATHER WIDGET -->

                                <div class="divider"></div>
                            </div>
                            <!-- / WIDGET GROUP -->
                        </aside>
                    </div>

                    <script type="text/javascript" src="../assets/js/apps/dashboard/project.js"></script>

                </div>
                <nav id="footer" class="bg-dark text-auto row no-gutters align-items-center px-6">
                    <a class="btn btn-secondary text-capitalize" href="http://themeforest.net/item/fuse-angularjs-material-design-admin-template/12931855?ref=srcn" target="_blank">
                        <i class="icon icon-cart mr-2 s-4"></i>Purchase FUSE Bootstrap
                    </a>
                </nav>
            </div>

    </div>
</main>
<script>
    google_api = '<?php echo get_option('google_api_key'); ?>';
    calendarIDs = '<?php echo json_encode($google_ids_calendars); ?>';
</script>
<?php init_tail(); ?>
<?php $this->load->view('admin/utilities/calendar_template'); ?>
<?php $this->load->view('admin/dashboard/dashboard_js'); ?>
</body>
</html>
