<h4 class="f-18">
    The right to be forgotten
    <small>
        <a href="https://ico.org.uk/for-organisations/guide-to-the-general-data-protection-regulation-gdpr/individual-rights/right-to-erasure/"
           target="_blank">Learn More</a>
    </small>
</h4>
<ul class="nav nav-tabs">
    <li role="presentation" class="nav-item active">
        <a href="#forgotten_options" aria-controls="forgotten_options" role="tab" data-toggle="tab" class="nav-link">
            Config
        </a>
    </li>
    <li role="presentation" class="nav-item">
        <a href="#removal_requests" aria-controls="removal_requests" role="tab" data-toggle="tab" class="nav-link">
            Removal Requests
            <?php if ($not_pending_requests > 0) { ?>
                <span class="badge"><?php echo $not_pending_requests; ?></span>
            <?php } ?>
        </a>
    </li>
</ul>
<div class="tab-content mt-4">
    <div role="tabpanel" class="tab-pane active" id="forgotten_options">
        <h4 class="f-18">Contacts</h4>
        <hr class="mt-4 mb-4">
        <?php render_yes_no_option('gdpr_contact_enable_right_to_be_forgotten', 'Enable contact to request data removal'); ?>
        <hr class="mt-2 mb-2"/>
        <?php render_yes_no_option('gdpr_on_forgotten_remove_invoices_credit_notes', 'When deleting customer, delete also <b>invoices</b> and <b>credit notes</b> related to this customer.'); ?>
        <hr class="mt-2 mb-2"/>
        <?php render_yes_no_option('gdpr_on_forgotten_remove_estimates', 'When deleting customer, delete also estimates related to this customer.'); ?>
        <hr class="mt-4 mb-4">
        <h4 class="f-18">Leads</h4>
        <hr class="mt-4 mb-4">
        <?php render_yes_no_option('gdpr_lead_enable_right_to_be_forgotten', 'Enable lead to request data removal (via public form)'); ?>
        <hr class="mt-2 mb-2"/>
        <?php render_yes_no_option('gdpr_after_lead_converted_delete', 'After lead is converted to customer, delete all lead data'); ?>
        <hr class="mt-2 mb-2"/>
    </div>
    <div role="tabpanel" class="tab-pane" id="removal_requests">
        <table class="table dt-table scroll-responsive" data-order-type="desc" data-order-col="4">
            <thead>
            <tr>
                <th>Request ID</th>
                <th>Request From</th>
                <th>Description</th>
                <th>Request Status</th>
                <th>Request Date</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($requests as $request) { ?>
                <tr>
                    <td data-order="<?php echo $request['id']; ?>"><?php echo $request['id']; ?></td>
                    <td><?php echo $request['request_from']; ?>
                        <?php if (!empty($request['contact_id'])) {
                            echo '<span class="label label-info pull-right">Contact</span>';
                        } else if (!empty($request['lead_id'])) {
                            echo '<span class="label label-info pull-right">Lead</span>';
                        }
                        ?>
                    </td>
                    <td><?php echo $request['description']; ?></td>
                    <td data-order="<?php echo $request['status']; ?>">
                        <select class="selectpicker removalStatus" name="status" data-id="<?php echo $request['id']; ?>"
                                width="100%">
                            <option value="pending"<?php if ($request['status'] == 'pending') {
                                echo ' selected';
                            } ?>>Pending
                            </option>
                            <option value="removed"<?php if ($request['status'] == 'removed') {
                                echo ' selected';
                            } ?>>Removed
                            </option>
                            <option value="refused"<?php if ($request['status'] == 'refused') {
                                echo ' selected';
                            } ?>>Refused
                            </option>
                        </select>
                    </td>
                    <td data-order="<?php echo $request['request_date']; ?>"><?php echo _dt($request['request_date']); ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>

</div>
