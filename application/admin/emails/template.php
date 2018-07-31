<?php init_single_head(); ?>
<style>
 #manage_email_template .p{font-size: 15px}
</style>
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>

            <div class="content custom-scrollbar">

                <div id="manage_email_template" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="f-18 mb-0">
                                            <?php echo $title; ?>
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <?php echo form_open($this->uri->uri_string(),array('id' => 'email-template-form')); ?>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <?php echo render_input('name', 'template_name', $template->name, 'text', array('disabled' => true)); ?>
                                                <?php echo render_input('subject[' . $template->emailtemplateid . ']', 'template_subject', $template->subject); ?>
                                                <?php echo render_input('fromname', 'template_fromname', $template->fromname); ?>
                                                <?php if ($template->slug != 'two-factor-authentication') { ?>
                                                    <i class="fa fa-question-circle" data-toggle="tooltip"
                                                       data-title="<?php echo _l('email_template_only_domain_email'); ?>"></i>
                                                    <?php echo render_input('fromemail', 'template_fromemail', $template->fromemail, 'email'); ?>
                                                <?php } ?>

                                                <div class="form-check mt-4">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" name="plaintext" class="form-check-input"
                                                               id="plaintext" <?php if ($template->plaintext == 1) {
                                                            echo 'checked';
                                                        } ?>>
                                                        <span class="checkbox-icon"></span>
                                                        <span><?php echo _l('send_as_plain_text'); ?></span>
                                                    </label>
                                                </div>

                                                <?php if ($template->slug != 'two-factor-authentication') { ?>

                                                    <div class="form-check mt-1">
                                                        <label class="form-check-label">
                                                            <input type="checkbox" name="disabled" class="form-check-input"
                                                                   id="disabled" <?php if ($template->active == 0) {
                                                                echo 'checked';
                                                            } ?>>
                                                            <span class="checkbox-icon"></span>
                                                            <span data-toggle="tooltip"
                                                                  title="<?php echo _l('disable_email_from_being_sent'); ?>"><?php echo _l('email_template_disabled'); ?></span>
                                                        </label>
                                                    </div>
                                                <?php } ?>
                                                <hr class="mt-4 mb-4"/>
                                                <?php
                                                $editors = array();
                                                array_push($editors, 'message[' . $template->emailtemplateid . ']');
                                                ?>
                                                <h4 class="bold f-17">English</h4>
                                                <p class="bold"><?php echo _l('email_template_email_message'); ?></p>
                                                <?php echo render_textarea('message[' . $template->emailtemplateid . ']', '', $template->message, array('data-url-converter-callback' => 'myCustomURLConverter'), array(), 'p-0 mb-4', 'tinymce tinymce-manual'); ?>
                                                <?php foreach ($available_languages as $language) {
                                                    $lang_template = $this->emails_model->get(array('slug' => $template->slug, 'language' => $language));
                                                    if (count($lang_template) > 0) {
                                                        $lang_used = false;
                                                        if (get_option('active_language') == $language || total_rows('tblstaff', array('default_language' => $language)) > 0 || total_rows('tblclients', array('default_language' => $language)) > 0) {
                                                            $lang_used = true;
                                                        }
                                                        $hide_template_class = '';
                                                        if ($lang_used == false) {
                                                            $hide_template_class = 'hide';
                                                        }
                                                        ?>
                                                        <hr class="mt-1 mb-1"/>
                                                        <h4 class="f-17 bold" style="cursor: pointer"
                                                            onclick='slideToggle("#temp_<?php echo $language; ?>");'><?php echo ucfirst($language); ?></h4>
                                                        <?php
                                                        $lang_template = $lang_template[0];
                                                        array_push($editors, 'message[' . $lang_template['emailtemplateid'] . ']');
                                                        echo '<div id="temp_' . $language . '" class="' . $hide_template_class . '">';
                                                        echo render_input('subject[' . $lang_template['emailtemplateid'] . ']', 'template_subject', $lang_template['subject'],"",[],[],'pt-0');
                                                        echo '<p class="bold mt-2">' . _l('email_template_email_message') . '</p>';
                                                        echo render_textarea('message[' . $lang_template['emailtemplateid'] . ']', '', $lang_template['message'], array('data-url-converter-callback' => 'myCustomURLConverter'), array(), 'pt-0 mb-4', 'tinymce tinymce-manual');
                                                        echo '</div>';
                                                    }
                                                } ?>
                                            </div>
                                            <?php echo form_close(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="f-18 mb-0">
                                            <?php echo _l('available_merge_fields'); ?>
                                        </h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <?php if ($template->type == 'ticket' || $template->type == 'project') { ?>
                                                <div class=" col-md-12">
                                                    <?php if ($template->type != 'project') { ?>
                                                        <div class="alert alert-warning">
                                                            <?php if ($template->type == 'ticket') {
                                                                echo _l('email_template_ticket_warning');
                                                            } else {
                                                                echo _l('email_template_contact_warning');
                                                            } ?>
                                                        </div>
                                                    <?php } else {
                                                        if ($template->slug == 'new-project-discussion-comment-to-staff' || $template->slug == 'new-project-discussion-comment-to-customer') {
                                                            ?>
                                                            <div class="alert alert-info">
                                                                <?php echo _l('email_template_discussion_info'); ?>
                                                            </div>
                                                        <?php }
                                                    }
                                                    ?>
                                                </div>
                                            <?php } ?>
                                            <div class="col-md-12">
                                                <div class="row available_merge_fields_container">
                                                    <?php
                                                    $mergeLooped = array();
                                                    foreach ($available_merge_fields as $field) {
                                                        foreach ($field as $key => $val) {
                                                            echo '<div class="col-md-6 merge_fields_col">';
                                                            echo '<h5 class="bold">' . ucfirst($key) . '</h5>';
                                                            foreach ($val as $_field) {
                                                                if (count($_field['available']) == 0
                                                                    && isset($_field['templates']) && in_array($template->slug, $_field['templates'])) {
                                                                    // Fake data to simulate foreach loop and check the templates key for the available slugs
                                                                    $_field['available'][] = '1';
                                                                }
                                                                foreach ($_field['available'] as $_available) {
                                                                    if (($_available == $template->type || isset($_field['templates']) && in_array($template->slug, $_field['templates'])) && !in_array($_field['name'], $mergeLooped)) {
                                                                        $mergeLooped[] = $_field['name'];
                                                                        echo '<p>' . $_field['name'];
                                                                        echo '<span class="pull-right"><a href="#" class="add_merge_field">';
                                                                        echo $_field['key'];
                                                                        echo '</a>';
                                                                        echo '</span>';
                                                                        echo '</p>';
                                                                    }
                                                                }
                                                            }
                                                            echo '</div>';
                                                        }
                                                    }
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
            </div>

            <nav id="footer" class="bg-white text-auto row no-gutters align-items-center px-6">
                <div class="col-md-12">
                    <button type="submit" id="save_email_template"
                            class="btn btn-secondary text-capitalize pull-right ml-4"><?php echo _l('submit'); ?></button>
                </div>
            </nav>
        </div>
    </div>
</main>
<?php init_tail(); ?>
<script>
    $(function () {
        <?php foreach($editors as $id){ ?>
        init_editor('textarea[name="<?php echo $id; ?>"]', {urlconverter_callback: 'merge_field_format_url'});
        <?php } ?>
        var merge_fields_col = $('.merge_fields_col');
        // If not fields available
        $.each(merge_fields_col, function () {
            var total_available_fields = $(this).find('p');
            if (total_available_fields.length == 0) {
                $(this).remove();
            }
        });
        // Add merge field to tinymce
        $('.add_merge_field').on('click', function (e) {
            e.preventDefault();
            tinymce.activeEditor.execCommand('mceInsertContent', false, $(this).text());
        });

        $("#save_email_template").on("click", function (e) {
            e.preventDefault();
            $("#email-template-form").submit();
        });

        _validate_form($('form'), {
            name: 'required',
            fromname: 'required',
        });
    });
</script>
</body>
</html>
