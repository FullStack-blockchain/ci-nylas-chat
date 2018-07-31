<?php init_single_head(); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/dashboard.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>

            <div class="content custom-scrollbar">

                <div id="server-dashboard" class="page-layout simple full-width">

                    <div class="screen-options-area"></div>
                    <div class="screen-options-btn">
                        <?php echo _l('dashboard_options'); ?>
                    </div>

                    <!-- CONTENT -->
                    <div class="page-content p-6">

                        <!-- WIDGET GROUP -->
                        <div class="widget-group row">

                            <?php include_once(APPPATH . 'views/admin/includes_fuse/alerts.php'); ?>

                            <?php do_action('before_start_render_dashboard_content'); ?>

                            <div class="col-md-12" data-container="top-12">
                                <?php render_dashboard_widgets('top-12'); ?>
                            </div>

                            <?php do_action('after_dashboard_top_container'); ?>

                            <div class="col-md-6" data-container="middle-left-6">
                                <?php render_dashboard_widgets('middle-left-6'); ?>
                            </div>
                            <div class="col-md-6" data-container="middle-right-6">
                                <?php render_dashboard_widgets('middle-right-6'); ?>
                            </div>

                            <?php do_action('after_dashboard_half_container'); ?>

                            <div class="col-md-8 p-3" data-container="left-8">
                                <?php render_dashboard_widgets('left-8'); ?>
                            </div>
                            <div class="col-md-4 p-3" data-container="right-4">
                                <?php render_dashboard_widgets('right-4'); ?>
                            </div>

                            <div class="clear-fix"></div>

                            <div class="col-md-4" data-container="bottom-left-4">
                                <?php render_dashboard_widgets('bottom-left-4'); ?>
                            </div>
                            <div class="col-md-4" data-container="bottom-middle-4">
                                <?php render_dashboard_widgets('bottom-middle-4'); ?>
                            </div>
                            <div class="col-md-4" data-container="bottom-right-4">
                                <?php render_dashboard_widgets('bottom-right-4'); ?>
                            </div>

                            <?php do_action('after_dashboard'); ?>

                        </div>
                        <!-- / WIDGET GROUP -->
                    </div>
                    <!-- / CONTENT -->
                </div>

            </div>
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
