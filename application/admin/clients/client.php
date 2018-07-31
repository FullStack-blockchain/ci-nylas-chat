<?php init_single_head(); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/client.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="customer-profile" class="page-layout simple left-sidebar-floating">

                    <div class="page-header bg-primary text-auto row no-gutters align-items-center justify-content-between p-4">

                        <div class="col-md col-sm-12">
                            <div>
                                <span class="logo-icon mr-4">
                                    <i class="fa fa-user-o s-6"></i>
                                </span>
                                <span class="logo-text h4">
                                <?php if (isset($client)) {
                                    echo '#' . $client->userid . ' ' . $title;
                                } else {
                                    echo _l('client_add_edit_profile');
                                } ?>
                                </span>
                            </div>
                        </div>

                        <div class="col-auto ml-4">
                            <?php if ($group == 'profile') { ?>
                                <div class="btn-bottom-toolbar btn-toolbar-container-out text-right">
                                    <button class="btn btn-info only-save customer-form-submiter">
                                        <?php echo _l('submit'); ?>
                                    </button>
                                    <?php if (!isset($client)) { ?>
                                        <button class="btn btn-info save-and-add-contact customer-form-submiter">
                                            <?php echo _l('save_customer_and_add_contact'); ?>
                                        </button>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- / HEADER -->

                    <div class="page-content p-4">
                        <div class="content">
                            <div class="row">
                                <div class="col-md-12">
                                    <?php if (isset($client) && $client->registration_confirmed == 0 && is_admin()) { ?>
                                        <div class="alert alert-warning">
                                            <?php echo _l('customer_requires_registration_confirmation'); ?>
                                            <br/>
                                            <a href="<?php echo admin_url('clients/confirm_registration/' . $client->userid); ?>"><?php echo _l('confirm_registration'); ?></a>
                                        </div>
                                    <?php } else if (isset($client) && $client->active == 0 && $client->registration_confirmed == 1) { ?>
                                        <div class="alert alert-warning">
                                            <?php echo _l('customer_inactive_message'); ?>
                                            <br/>
                                            <a href="<?php echo admin_url('clients/mark_as_active/' . $client->userid); ?>"><?php echo _l('mark_as_active'); ?></a>
                                        </div>
                                    <?php } ?>
                                    <?php if (isset($client) && $client->leadid != NULL) { ?>
                                        <div class="alert alert-info">
                                            <a href="<?php echo admin_url('leads/index/' . $client->leadid); ?>"
                                               onclick="init_lead(<?php echo $client->leadid; ?>); return false;"><?php echo _l('customer_from_lead', _l('lead')); ?></a>
                                        </div>
                                    <?php } ?>
                                    <?php if (isset($client) && (!has_permission('customers', '', 'view') && is_customer_admin($client->userid))) { ?>
                                        <div class="alert alert-info">
                                            <?php echo _l('customer_admin_login_as_client_message', get_staff_full_name(get_staff_user_id())); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                                <?php if (isset($client)) { ?>
                                    <div class="col-md-3 col-sm-4">
                                        <div class="card">
                                            <?php $this->load->view('admin/clients/tabs'); ?>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="col-md-<?php if (isset($client)) {
                                    echo(9 . ' col-sm-8');
                                } else {
                                    echo 12;
                                } ?>">
                                    <div class="card">
                                        <?php if (isset($client)) { ?>
                                            <?php echo form_hidden('isedit'); ?>
                                            <?php echo form_hidden('userid', $client->userid); ?>
                                            <div class="clearfix"></div>
                                        <?php } ?>
                                        <div class="tab-content">
                                            <?php $this->load->view('admin/clients/groups/' . $group); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if ($group == 'profile') { ?>
                                <div class="btn-bottom-pusher"></div>
                            <?php } ?>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>
<?php init_tail(); ?>
<?php if (isset($client)) { ?>
    <script>
        $(function () {
            init_rel_tasks_table(<?php echo $client->userid; ?>, 'customer');
        });
    </script>
<?php } ?>
<?php if (!empty(get_option('google_api_key')) && !empty($client->latitude) && !empty($client->longitude)) { ?>
    <script>
        var latitude = '<?php echo $client->latitude; ?>';
        var longitude = '<?php echo $client->longitude; ?>';
        var mapMarkerTitle = '<?php echo $client->company; ?>';
    </script>
    <?php echo app_script('assets-old/js', 'map.js'); ?>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=<?php echo get_option('google_api_key'); ?>&callback=initMap"></script>
<?php } ?>
<?php $this->load->view('admin/clients/client_js'); ?>
</body>
</html>
