<?php init_single_head(); ?>
<style>
    #manage_gdpr .nav-tabs.nav-tabs-horizontal {
        border-bottom: 1px solid #dddddd;
    }

    #manage_gdpr .nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, #manage_gdpr .nav-tabs > li.active > a:hover {
        color: #495057;
        background-color: transparent;
        border: none
    }

    #manage_gdpr .nav-tabs .nav-item.active:not(.dropdown-toggle):after, #manage_gdpr .nav-tabs .nav-item.show .nav-item:not(.dropdown-toggle):after {
        content: '';
        position: absolute;
        width: 100%;
        left: 0;
        right: 0;
        bottom: 0;
        height: 2px;
        background-color: #3C4252;
    }

    #manage_gdpr .nav-tabs .nav-link {
        line-height: 25px;
        padding: 5px 15px;
        height: 4rem;
    }

</style>
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>

            <div class="content custom-scrollbar">

                <div id="manage_gdpr" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">
                        <div class="row">
                            <?php if (!is_gdpr()) { ?>
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="col-md-12 text-center">
                                                <h4><?php echo _l('gdpr_not_enabled'); ?></h4>
                                                <a href="<?php echo admin_url('gdpr/enable'); ?>"
                                                   class="btn btn-info"><?php echo _l('enable_gdpr'); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?>

                                <div class="col-md-3">
                                    <div class="card p-0">
                                        <div class="card-body p-0">
                                            <ul class="nav flex-column nav-pills">
                                                <li class="nav-item">
                                                    <a class="nav-link <?php if ($page == 'general') {
                                                        echo 'active';
                                                    } ?>"
                                                       href="<?php echo admin_url('gdpr/index?page=general'); ?>"><?php echo _l('settings_group_general'); ?></a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link <?php if ($page == 'portability') {
                                                        echo 'active';
                                                    } ?>"
                                                       href="<?php echo admin_url('gdpr/index?page=portability'); ?>"><?php echo _l('gdpr_right_to_data_portability'); ?></a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link <?php if ($page == 'forgotten') {
                                                        echo 'active';
                                                    } ?>"
                                                       href="<?php echo admin_url('gdpr/index?page=forgotten'); ?>"><?php echo _l('gdpr_right_to_erasure'); ?></a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link <?php if ($page == 'informed') {
                                                        echo 'active';
                                                    } ?>"
                                                       href="<?php echo admin_url('gdpr/index?page=informed'); ?>"><?php echo _l('gdpr_right_to_be_informed'); ?></a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link <?php if ($page == 'rectification') {
                                                        echo 'active';
                                                    } ?>"
                                                       href="<?php echo admin_url('gdpr/index?page=rectification'); ?>"><?php echo _l('gdpr_right_of_access'); ?>
                                                        /<?php echo _l('gdpr_right_to_rectification'); ?></a>
                                                </li>
                                                <li class="nav-item">
                                                    <a class="nav-link <?php if ($page == 'consent') {
                                                        echo 'active';
                                                    } ?>"
                                                       href="<?php echo admin_url('gdpr/index?page=consent'); ?>"><?php echo _l('gdpr_consent'); ?></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    <div class="card">
                                        <div class="card-body">
                                            <?php if ($save == true) { ?>
                                                <?php echo form_open(admin_url('gdpr/save?page=' . $page), array('id' => 'save-gdpr-form')); ?>
                                            <?php } ?>
                                            <?php do_action('before_admin_gdpr_settings'); ?>
                                            <?php $this->load->view('admin/gdpr/pages/' . $page); ?>
                                            <?php if ($save == true) { ?>
                                                <?php echo form_close(); ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <?php if (is_gdpr()) { ?>
                <?php if ($save == true) { ?>
                    <nav id="footer" class="bg-white text-auto row no-gutters align-items-center px-6">
                        <div class="col-md-12">
                            <button type="submit" id="save_gdpr"
                                    class="btn btn-secondary text-capitalize pull-right ml-4"><?php echo _l('submit'); ?></button>
                        </div>
                    </nav>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
    <div id="page-tail"></div>
</main>

<?php init_tail(); ?>
<script>
    $(function () {
        $('.removalStatus').on('change', function (e) {
            var id = $(this).attr('data-id');
            var val = $(this).selectpicker('val');

            // Event is invoked twice? Second is jQuery object
            if (typeof(val) != 'string') {
                return;
            }
            requestGet('gdpr/change_removal_request_status/' + id + '/' + val);
        });

        $("#save_gdpr").on("click", function (e) {
            e.preventDefault();
            $("#save-gdpr-form").submit();
        });
    });
</script>
</body>
</html>
