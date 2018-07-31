<?php init_single_head(); ?>
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content">

                <div id="utilities-manage" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">

                        <div class="col-md-8 offset-md-2">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="no-margin"><?php echo $title; ?></h4>
                                </div>
                                <div class="card-body">

                                    <?php echo form_open($this->uri->uri_string()); ?>

                                    <?php $value = (isset($announcement) ? $announcement->name : ''); ?>
                                    <?php echo render_input('name', 'announcement_name', $value); ?>

                                    <p class="bold mt-4"><?php echo _l('announcement_message'); ?></p>
                                    <?php $contents = '';
                                    if (isset($announcement)) {
                                        $contents = $announcement->message;
                                    } ?>
                                    <?php echo render_textarea('message', '', $contents, array(), array(), 'pt-0', 'tinymce'); ?>

                                    <div class="form-check form-check-inline mt-4 ">
                                        <label class="form-check-label">
                                            <input type="checkbox" name="showtostaff" class="form-check-input"
                                                   id="showtostaff" <?php if (isset($announcement)) {
                                                if ($announcement->showtostaff == 1) {
                                                    echo 'checked';
                                                }
                                            } else {
                                                echo 'checked';
                                            } ?>>
                                            <span class="checkbox-icon"></span>
                                            <span><?php echo _l('announcement_show_to_staff'); ?></span>
                                        </label>
                                    </div>

                                    <div class="form-check form-check-inline mt-4 ">
                                        <label class="form-check-label">
                                            <input type="checkbox" name="showtousers" class="form-check-input"
                                                   id="showtousers" <?php if (isset($announcement)) {
                                                if ($announcement->showtousers == 1) {
                                                    echo 'checked';
                                                }
                                            } ?>>
                                            <span class="checkbox-icon"></span>
                                            <span><?php echo _l('announcement_show_to_clients'); ?></span>
                                        </label>
                                    </div>

                                    <div class="form-check form-check-inline mt-4 ">
                                        <label class="form-check-label">
                                            <input type="checkbox" name="showname" class="form-check-input"
                                                   id="showname" <?php if (isset($announcement)) {
                                                if ($announcement->showname == 1) {
                                                    echo 'checked';
                                                }
                                            } ?>>
                                            <span class="checkbox-icon"></span>
                                            <span><?php echo _l('announcement_show_my_name'); ?></span>
                                        </label>
                                    </div>


                                    <button type="submit"
                                            class="btn btn-info pull-right mt-4"><?php echo _l('submit'); ?></button>
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
        _validate_form($('form'), {name: 'required'});
    });
</script>
</body>
</html>
