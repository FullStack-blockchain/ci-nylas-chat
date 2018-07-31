<?php init_single_head(); ?>
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content">

                <div id="manage_main_menu" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="no-margin">
                                            <?php echo $title; ?>
                                            <?php if (!is_admin()) { ?><br/>
                                                <small><?php echo _l('reminders_view_none_admin'); ?></small>
                                            <?php } ?>
                                        </h4>
                                        <hr class="mt-4 mb-4"/>
                                        <?php render_datatable(array(
                                            _l('reminder_related'),
                                            _l('reminder_description'),
                                            _l('reminder_date'),
                                            _l('reminder_staff'),
                                            _l('reminder_is_notified'),
                                        ), 'reminders'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php $this->load->view('admin/includes_fuse/modals/reminder', array(
        'id' => '',
        'name' => '',
        'members' => $members,
        'reminder_title' => _l('edit', _l('reminder')))
); ?>
<?php init_tail(); ?>
<script>
    $(function () {
        initDataTable('.table-reminders', admin_url + 'misc/reminders_table', undefined, undefined, undefined, [2, 'asc']);
    });
</script>
</body>
</html>
