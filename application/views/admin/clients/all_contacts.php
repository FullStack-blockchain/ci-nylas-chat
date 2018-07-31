<?php init_single_head(); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/client.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="all-contacts" class="page-layout simple left-sidebar-floating">

                    <div class="page-header bg-primary text-auto row no-gutters align-items-center justify-content-between p-4">

                        <div class="col-md col-sm-12">
                            <div>
                                <span class="logo-icon mr-4">
                                    <i class="fa fa-user-o s-6"></i>
                                </span>
                                <span class="logo-text h4"><?php echo _l('customer_contacts'); ?></span>
                            </div>
                        </div>
                    </div>
                    <!-- / HEADER -->
                    <div id="wrapper" class="p-4">
                        <div class="content card full-width">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel-body">
                                        <?php if (isset($consent_purposes)) { ?>
                                            <div class="row mbot15">
                                                <div class="col-md-3 contacts-filter-column">
                                                    <div class="select-placeholder">
                                                        <select name="custom_view"
                                                                title="<?php echo _l('gdpr_consent'); ?>"
                                                                id="custom_view" class="selectpicker"
                                                                data-width="100%">
                                                            <option value=""></option>
                                                            <?php foreach ($consent_purposes as $purpose) { ?>
                                                                <option value="consent_<?php echo $purpose['id']; ?>">
                                                                    <?php echo $purpose['name']; ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                        <div class="clearfix"></div>
                                        <?php
                                        $table_data = array(_l('client_firstname'), _l('client_lastname'));
                                        if (is_gdpr() && get_option('gdpr_enable_consent_for_contacts') == '1') {
                                            array_push($table_data, array(
                                                'name' => _l('gdpr_consent') . ' (' . _l('gdpr_short') . ')',
                                                'th_attrs' => array('id' => 'th-consent', 'class' => 'not-export')
                                            ));
                                        }
                                        $table_data = array_merge($table_data, array(
                                            _l('client_email'),
                                            _l('clients_list_company'),
                                            _l('client_phonenumber'),
                                            _l('contact_position'),
                                            _l('clients_list_last_login'),
                                            _l('contact_active'),
                                        ));
                                        $custom_fields = get_custom_fields('contacts', array('show_on_table' => 1));
                                        foreach ($custom_fields as $field) {
                                            array_push($table_data, $field['name']);
                                        }
                                        render_datatable($table_data, 'all-contacts');
                                        ?>
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
<?php $this->load->view('admin/clients/client_js'); ?>
<div id="contact_data"></div>
<div id="consent_data"></div>
<script>
    $(function () {
        var optionsHeading = [];
        var allContactsServerParams = {
            "custom_view": "[name='custom_view']",
        }
        <?php if(is_gdpr() && get_option('gdpr_enable_consent_for_contacts') == '1'){ ?>
        optionsHeading.push($('#th-consent').index());
        <?php } ?>
        _table_api = initDataTable('.table-all-contacts', window.location.href, optionsHeading, optionsHeading, allContactsServerParams, [0, 'asc']);
        if (_table_api) {
            <?php if(is_gdpr() && get_option('gdpr_enable_consent_for_contacts') == '1'){ ?>
            _table_api.on('draw', function () {
                var tableData = $('.table-all-contacts').find('tbody tr');
                $.each(tableData, function () {
                    $(this).find('td:eq(2)').addClass('bg-light-gray');
                });
            });
            $('select[name="custom_view"]').on('change', function () {
                _table_api.ajax.reload()
                    .columns.adjust()
                    .responsive.recalc();
            });
            <?php } ?>
        }
    });
</script>
</body>
</html>
