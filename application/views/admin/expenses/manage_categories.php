<?php init_single_head(); ?>
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="manage_categories" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="_buttons">
                                            <a href="#" onclick="new_category(); return false;"
                                               class="btn btn-secondary pull-left display-block"><?php echo _l('new_expense_category'); ?></a>
                                        </div>
                                        <div class="clearfix"></div>
                                        <hr class="mt-4 mb-4"/>
                                        <div class="clearfix"></div>
                                        <?php render_datatable(array(_l('name'), _l('dt_expense_description'), _l('options')), 'expenses-categories'); ?>
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
<?php $this->load->view('admin/expenses/expense_category'); ?>
<?php init_tail(); ?>
<script>
    $(function () {
        initDataTable('.table-expenses-categories', window.location.href, [2], [2]);
    });
</script>
</body>
</html>
