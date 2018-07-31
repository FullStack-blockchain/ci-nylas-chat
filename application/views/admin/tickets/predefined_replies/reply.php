<?php init_single_head(); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/support.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="priorities-manage" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">
                        <div class="card">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12 mb-3 p-0">
                                        <div class="col-md-8">
                                            <h4 class="no-margin">
                                                <?php echo $title; ?>
                                            </h4>
                                        </div>
                                        <?php if (isset($predefined_reply)) { ?>
                                            <div class="col-md-4">
                                                <a href="<?php echo admin_url('tickets/predefined_reply'); ?>"
                                                   class="btn btn-success pull-right"><?php echo _l('new_predefined_reply'); ?></a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <hr class="full-width pl-0"/>

                                    <div class="col-md-12">
                                        <?php echo form_open($this->uri->uri_string()); ?>

                                        <?php $value = (isset($predefined_reply) ? $predefined_reply->name : ''); ?>
                                        <?php $attrs = (isset($predefined_reply) ? array() : array('autofocus' => true)); ?>
                                        <?php echo render_input('name', 'predefined_reply_add_edit_name', $value, 'text', $attrs); ?>
                                        <?php $contents = '';
                                        if (isset($predefined_reply)) {
                                            $contents = $predefined_reply->message;
                                        } ?>
                                        <?php echo render_textarea('message', '', $contents, array(), array(), '', 'tinymce'); ?>
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
    </div>
</main>
<?php init_tail(); ?>
<script>
    $(function () {
        _validate_form($('form'), {name: 'required'});
    });
</script>
</body>
</html>
