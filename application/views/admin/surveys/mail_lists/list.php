<?php init_single_head(); ?>
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="utilities-manage" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">
                        <div class="col-md-6 col-md-offset-3">
                            <div class="card">
                                <h5 class="card-header"> <?php echo $title; ?></h5>
                                <div class="card-body">
                                    <?php if (isset($list)) { ?>
                                        <?php if (has_permission('surveys', '', 'create')) { ?>
                                            <a href="<?php echo admin_url('surveys/mail_list'); ?>"
                                               class="btn btn-success pull-left mbot20 display-block"><?php echo _l('new_mail_list'); ?></a>
                                        <?php } ?>
                                        <a href="<?php echo admin_url('surveys/mail_lists'); ?>"
                                           class="btn btn-default ml-4 pull-left mbot20 display-block"><?php echo _l('mail_lists'); ?></a>
                                        <div class="clearfix"></div>
                                        <hr class="mt-4 mb-4"/>
                                    <?php } ?>
                                    <?php echo form_open($this->uri->uri_string()); ?>

                                    <?php $value = (isset($list) ? $list->name : ''); ?>
                                    <?php echo render_input('name', 'mail_list_add_edit_name', $value); ?>
                                    <div class="form-group">
                                        <a href="#" class="btn btn-default add_list_custom_field"
                                           onclick="add_list_custom_field()"><i
                                                    class="fa fa-plus line-height-25 text-green"></i> <?php echo _l('mail_list_add_edit_customfield'); ?>
                                        </a>
                                    </div>
                                    <div class="list_custom_fields_area mt-4 mb-6">
                                        <?php
                                        if (isset($list)) {
                                            if (count($custom_fields) > 0) {
                                                foreach ($custom_fields as $field) { ?>
                                                    <div class="input-group list_custom_field mt-5">
                                                        <input type="text"
                                                               name="list_custom_fields_update[<?php echo $field['customfieldid']; ?>]"
                                                               value="<?php echo $field['fieldname']; ?>"
                                                               class="form-control">
                                                        <span class="input-group-addon">
											<a href="#"
                                               onclick="remove_list_custom_field(this,<?php echo $field['customfieldid']; ?>)"><i
                                                        class="fa fa-remove text-danger"></i></a>
										</span>
                                                    </div>
                                                <?php }
                                            }
                                        }
                                        ?>
                                    </div>
                                    <button type="submit"
                                            class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
                                    <?php echo form_close(); ?>
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
        _validate_form($('form'), {
            name: 'required'
        });
    });

    // Will add mail list custom field
    function add_list_custom_field(listid) {

        var name = "list_custom_fields_add[]";
        if (typeof(listid) !== 'undefined') {
            name = 'list_custom_fields_update[' + listid + ']'
        }

        $('.list_custom_fields_area').append('<div class="input-group list_custom_field mt-5"><input type="text" name="' + name + '" placeholder="Enter field name" class="form-control"><span class="input-group-addon"><a href="#" onclick="remove_list_custom_field(this)"><i class="fa fa-remove text-danger"></i></a></span></div>')
    }

    // Remove mail list custom field / if is edit removes from database
    function remove_list_custom_field(field, fieldid) {
        if (typeof(fieldid) !== 'undefined') {
            $.get(admin_url + 'surveys/remove_list_custom_field/' + fieldid, function (response) {
                if (response.success == true) {
                    alert_float('success', response.message);
                    $(field).parents('.list_custom_field').remove();
                } else {
                    alert_float('warning', response.message);
                }
            }, 'json');
        } else {
            $(field).parents('.list_custom_field').remove();
        }
    }
</script>
</body>
</html>
