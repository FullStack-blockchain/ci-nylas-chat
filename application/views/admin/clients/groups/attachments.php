<div class="modal fade" id="customer_file_share_file_with" data-total-contacts="<?php echo count($contacts); ?>"
     tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo _l('share_file_with'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php echo form_hidden('file_id'); ?>
                <?php echo render_select('share_contacts_id[]', $contacts, array('id', array('firstname', 'lastname')), 'customer_contacts', array(get_primary_contact_user_id($client->userid)), array('multiple' => true, 'data-actions-box' => true), array(), '', '', false); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="button" class="btn btn-info"
                        onclick="do_share_file_contacts();"><?php echo _l('confirm'); ?></button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<h4 class="no-margin bold p-4"><?php echo _l('customer_attachments'); ?>
    <div class="text-secondary f-14">Files from projects and tasks linked to the customer are not shown on this table.
    </div>
</h4>
<hr/>
<div class="p-4">
    <?php if (isset($client)) { ?>
        <?php echo form_open_multipart(admin_url('clients/upload_attachment/' . $client->userid), array('class' => 'dropzone', 'id' => 'client-attachments-upload')); ?>
        <input type="file" name="file" multiple/>
        <?php echo form_close(); ?>
        <div class="text-right mtop15">
            <div id="dropbox-chooser"></div>
        </div>
        <div class="attachments">
            <div class="mtop25">

                <table class="table dt-table scroll-responsive" data-order-col="2" data-order-type="desc">
                    <thead>
                    <tr>
                        <th width="30%"><?php echo _l('customer_attachments_file'); ?></th>
                        <th><?php echo _l('customer_attachments_show_in_customers_area'); ?></th>
                        <th><?php echo _l('file_date_uploaded'); ?></th>
                        <th><?php echo _l('options'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($attachments as $type => $attachment) {
                        $download_indicator = 'id';
                        $key_indicator = 'rel_id';
                        $upload_path = get_upload_path_by_type($type);
                        if ($type == 'invoice' || $type == 'proposal' || $type == 'estimate' || $type == 'credit_note') {
                            $url = site_url() . 'download/file/sales_attachment/';
                            $download_indicator = 'attachment_key';
                        } else if ($type == 'contract') {
                            $url = site_url() . 'download/file/contract/';
                            $download_indicator = 'attachment_key';
                        } else if ($type == 'lead') {
                            $url = site_url() . 'download/file/lead_attachment/';
                        } else if ($type == 'task') {
                            $url = site_url() . 'download/file/taskattachment/';
                            $download_indicator = 'attachment_key';
                        } else if ($type == 'ticket') {
                            $url = site_url() . 'download/file/ticket/';
                            $key_indicator = 'ticketid';
                        } else if ($type == 'customer') {
                            $url = site_url() . 'download/file/client/';
                            $download_indicator = 'attachment_key';
                        } else if ($type == 'expense') {
                            $url = site_url() . 'download/file/expense/';
                            $download_indicator = 'rel_id';
                        }
                        ?>
                        <?php foreach ($attachment as $_att) {
                            ?>
                            <tr id="tr_file_<?php echo $_att['id']; ?>">
                            <td>
                                <?php
                                $path = $upload_path . $_att[$key_indicator] . '/' . $_att['file_name'];
                                $is_image = false;
                                if (!isset($_att['external'])) {
                                    $attachment_url = $url . $_att[$download_indicator];
                                    $is_image = is_image($path);
                                    $img_url = site_url('download/preview_image?path=' . protected_file_url_by_path($path, true) . '&type=' . $_att['filetype']);
                                    $lightBoxUrl = site_url('download/preview_image?path=' . protected_file_url_by_path($path) . '&type=' . $_att['filetype']);
                                } else if (isset($_att['external']) && !empty($_att['external'])) {

                                    if (!empty($_att['thumbnail_link'])) {
                                        $is_image = true;
                                        $img_url = optimize_dropbox_thumbnail($_att['thumbnail_link']);
                                    }

                                    $attachment_url = $_att['external_link'];
                                }
                                if ($is_image) {
                                    echo '<div class="preview_image">';
                                }
                                ?>
                                <a href="<?php if ($is_image) {
                                    echo isset($lightBoxUrl) ? $lightBoxUrl : $img_url;
                                } else {
                                    echo $attachment_url;
                                } ?>"<?php if ($is_image) { ?> data-lightbox="customer-profile" <?php } ?>
                                   class="display-block">
                                    <?php if ($is_image) { ?>
                                        <div class="table-image">
                                            <div class="text-center"><i class="fa fa-spinner fa-spin mtop30"></i></div>
                                            <img src="#" class="img-table-loading" data-orig="<?php echo $img_url; ?>">
                                        </div>
                                    <?php } else { ?>
                                        <i class="<?php echo get_mime_class($_att['filetype']); ?>"></i> <?php echo $_att['file_name']; ?>
                                    <?php } ?>
                                </a>
                                <?php if ($is_image) {
                                    echo '</div>';
                                } ?>
                            </td>
                            <td>
                                <div class="onoffswitch" <?php if ($type != 'customer') { ?> data-toggle="tooltip" data-title="<?php echo _l('customer_attachments_show_notice'); ?>" <?php } ?>>
                                    <label class="switch onoffswitch-label" for="<?php echo $_att['id']; ?>">
                                        <input type="checkbox" <?php if ($type != 'customer') {
                                            echo 'disabled';
                                        } ?> id="<?php echo $_att['id']; ?>" data-id="<?php echo $_att['id']; ?>"
                                               class="onoffswitch-checkbox customer_file"
                                               data-switch-url="<?php echo admin_url(); ?>misc/toggle_file_visibility" <?php if (isset($_att['visible_to_customer']) && $_att['visible_to_customer'] == 1) {
                                            echo 'checked';
                                        } ?>>
                                        <span class="slider"></span>
                                    </label>
                                </div>
                                <?php if ($type == 'customer' && $_att['visible_to_customer'] == 1) {
                                    $file_visibility_message = '';
                                    $total_shares = total_rows('tblcustomerfiles_shares', array('file_id' => $_att['id']));

                                    if ($total_shares == 0) {
                                        $file_visibility_message = _l('file_share_visibility_notice');
                                    } else {
                                        $share_contacts_id = get_customer_profile_file_sharing(array('file_id' => $_att['id']));
                                        if (count($share_contacts_id) == 0) {
                                            $file_visibility_message = _l('file_share_visibility_notice');
                                        }
                                    }
                                    echo '<div class="mt-3"><span class="text-warning' . (empty($file_visibility_message) || total_rows('tblcontacts', array('userid' => $client->userid)) == 0 ? ' hide' : '') . '">' . $file_visibility_message . '</span></div>';
                                    if (isset($share_contacts_id) && count($share_contacts_id) > 0) {
                                        $names = '';
                                        $contacts_selected = '';
                                        foreach ($share_contacts_id as $file_share) {
                                            $names .= get_contact_full_name($file_share['contact_id']) . ', ';
                                            $contacts_selected .= $file_share['contact_id'] . ',';
                                        }
                                        if ($contacts_selected != '') {
                                            $contacts_selected = substr($contacts_selected, 0, -1);
                                        }
                                        if ($names != '') {
                                            echo '<div class="mt-3"><a href="#" onclick="do_share_file_contacts(\'' . $contacts_selected . '\',' . $_att['id'] . '); return false;"><i class="fa fa-pencil-square-o"></i></a> ' . _l('share_file_with_show', mb_substr($names, 0, -2)) . '</div>';
                                        }
                                    }
                                }
                                ?>
                            </td>
                            <td data-order="<?php echo $_att['dateadded']; ?>"><?php echo _dt($_att['dateadded']); ?></td>
                            <td>
                                <?php if (!isset($_att['external'])) { ?>
                                    <button type="button" data-toggle="modal"
                                            data-file-name="<?php echo $_att['file_name']; ?>"
                                            data-filetype="<?php echo $_att['filetype']; ?>"
                                            data-path="<?php echo $path; ?>" data-target="#send_file"
                                            class="btn btn-info btn-icon"><i class="fa fa-envelope"></i></button>
                                <?php } else if (isset($_att['external']) && !empty($_att['external'])) {
                                    echo '<a href="' . $_att['external_link'] . '" class="btn btn-info btn-icon" target="_blank"><i class="fa fa-dropbox"></i></a>';
                                } ?>
                                <?php if ($type == 'customer') { ?>
                                    <a href="<?php echo admin_url('clients/delete_attachment/' . $_att['rel_id'] . '/' . $_att['id']); ?>"
                                       class="btn btn-danger btn-icon _delete"><i class="fa fa-remove"></i></a>
                                <?php } ?>
                            </td>
                        <?php } ?>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>
        <?php
        include_once(APPPATH . 'views/admin/clients/modals/send_file_modal.php');
    } ?>
</div>