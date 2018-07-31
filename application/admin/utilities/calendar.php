<?php init_single_head(); ?>
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="calendar_page" class="page-layout simple full-width">

                    <!-- HEADER -->
                    <div class="page-header bg-primary text-auto p-6">

                        <!-- HEADER CONTENT-->
                        <div class="header-content d-flex flex-column justify-content-between">

                            <!-- HEADER TOP -->
                            <div class="header-top d-flex flex-column flex-sm-row align-items-center  justify-content-center justify-content-sm-between">

                                <div class="logo row align-items-center no-gutters mb-4 mb-sm-0">
                                    <i class="logo-icon icon-calendar-today mr-4"></i>
                                    <span class="logo-text h4">Calendar</span>
                                </div>

                                <!-- TOOLBAR -->
                                <div class="toolbar row no-gutters align-items-center">

                                    <!--                                    <button type="button" class="btn btn-icon" aria-label="Search">-->
                                    <!--                                        <i class="icon icon-magnify"></i>-->
                                    <!--                                    </button>-->

                                    <button id="calendar-today-button" type="button" class="btn btn-icon"
                                            aria-label="Today" title="Today" data-toggle="tooltip"
                                            data-placement="bottom">
                                        <i class="icon icon-calendar-today"></i>
                                    </button>

                                    <button type="button" class="btn btn-icon change-view" data-view="agendaDay"
                                            aria-label="Day" title="Day" data-toggle="tooltip" data-placement="bottom">
                                        <i class="icon icon-view-day"></i>
                                    </button>

                                    <button type="button" class="btn btn-icon change-view" data-view="agendaWeek"
                                            aria-label="Week" title="Week" data-toggle="tooltip"
                                            data-placement="bottom">
                                        <i class="icon icon-view-week"></i>
                                    </button>

                                    <button type="button" class="btn btn-icon change-view" data-view="month"
                                            aria-label="Month" title="Month" data-toggle="tooltip"
                                            data-placement="bottom">
                                        <i class="icon icon-view-module"></i>
                                    </button>

                                    <button type="button" class="btn btn-icon" id="calendar-filter-toggle"
                                            data-view="month" aria-label="Filter By" title="Filter By"
                                            data-toggle="tooltip" data-placement="bottom">
                                        <i class="icon icon-filter"></i>
                                    </button>
                                </div>
                                <!-- / TOOLBAR -->
                            </div>
                            <!-- / HEADER TOP -->

                            <!-- HEADER BOTTOM -->
                            <div class="header-bottom row align-items-center justify-content-center">

                                <button id="calendar-previous-button" type="button" class="btn btn-icon"
                                        aria-label="Previous">
                                    <i class="icon icon-chevron-left"></i>
                                </button>

                                <div id="calendar-view-title" class="h5">
                                    Calendar title
                                </div>

                                <button id="calendar-next-button" type="button" class="btn btn-icon" aria-label="Next">
                                    <i class="icon icon-chevron-right"></i>
                                </button>
                            </div>
                            <!-- / HEADER BOTTOM -->
                        </div>
                        <!-- / HEADER CONTENT -->

                        <!-- ADD EVENT BUTTON -->
                        <button id="add-event-button" type="button" class="btn btn-danger btn-fab"
                                aria-label="<?php echo _l('utility_calendar_new_event_title'); ?>"
                                title="<?php echo _l('utility_calendar_new_event_title'); ?>" data-toggle="tooltip"
                                data-placement="bottom">
                            <i class="icon icon-plus"></i>
                        </button>
                        <!-- / ADD EVENT BUTTON -->

                    </div>
                    <!-- / HEADER -->

                    <!-- CONTENT -->
                    <div class="page-content p-6">
                        <div class="dt-loader hide"></div>
                        <?php $this->load->view('admin/utilities/calendar_filters'); ?>
                        <div id="calendar"></div>
                    </div>
                    <!-- / CONTENT -->
                </div>

            </div>
        </div>
    </div>
</main>
<?php $this->load->view('admin/utilities/calendar_template'); ?>
<script>
    google_api = '<?php echo get_option('google_api_key'); ?>';
    calendarIDs = '<?php echo json_encode($google_ids_calendars); ?>';
</script>
<?php init_tail(); ?>
<!--<script src="--><?php //echo base_url('assets/node_modules/moment/min/moment.min.js'); ?><!--"></script>-->
<!--<script src="--><?php //echo base_url('assets/node_modules/fullcalendar/dist/fullcalendar.min.js'); ?><!--"></script>-->
<!--<script src="--><?php //echo base_url('assets/js/apps/calendar/calendar.js'); ?><!--"></script>-->
</body>
</html>
