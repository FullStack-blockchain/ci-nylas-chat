<?php init_single_head(); ?>
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>

            <div class="content custom-scrollbar">

                <div id="manage_custom_fields" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="_buttons">
                                            <a href="<?php echo admin_url('custom_fields/field'); ?>"
                                               class="btn btn-info pull-left display-block"><?php echo _l('new_custom_field'); ?></a>
                                        </div>
                                        <div class="clearfix"></div>
                                        <hr class="mt-4 mb-4"/>
                                        <div class="clearfix"></div>
                                        <?php render_datatable(
                                            array(
                                                _l('id'),
                                                _l('custom_field_dt_field_name'),
                                                _l('custom_field_dt_field_to'),
                                                _l('custom_field_dt_field_type'),
                                                _l('kb_article_slug'),
                                                _l('custom_field_add_edit_active'),
                                            ), 'custom-fields'); ?>
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
<?php init_tail(); ?>
<script>
    $(function () {
        initDataTable('.table-custom-fields', window.location.href);
    });
</script>
</body>
</html>
