<h4 class="f-18">
    The right of access/right to rectification
    <small>
        <a href="https://ico.org.uk/for-organisations/guide-to-the-general-data-protection-regulation-gdpr/individual-rights/right-of-access/" target="_blank">Learn More</a>
    </small>
</h4>
<hr class="mt-4 mb-4" />
<h4 class="bold f-16">Contacts</h4>
<hr class="mt-4 mb-4" />
<p>
    The customers area gives your customers access to login and view their personal information. Also the customers area provide with access to update their personal information like first name, last name, email address, phone etc...
</p>
<p>Below you can find <b>additional options</b> you may want to allow the contacts to modify.</p>
<hr class="mt-4 mb-4" />
<p class="font-medium">Profile/Contact</p>
<?php render_yes_no_option('allow_primary_contact_to_view_edit_billing_and_shipping', 'allow_primary_contact_to_view_edit_billing_and_shipping'); ?>
<small>Updating billing and shipping details from customers area won't affect already created invoices, estimates, credit notes.</small></p>
<hr class="mt-2 mb-2" />
<?php render_yes_no_option('allow_contact_to_delete_files', 'allow_contact_to_delete_files'); ?>
<hr class="mt-4 mb-4" />
<h4 class="bold f-16" id="access_leads">Leads</h4>
<hr class="mt-4 mb-4" />
<?php render_yes_no_option('gdpr_enable_lead_public_form', 'Enable public form for leads', 'The leads you add in the system will have unique URL to view their information you store for them and they will be able to update the information when they access the URL.'); ?>
<hr class="mt-2 mb-2" />
<?php render_yes_no_option('gdpr_show_lead_custom_fields_on_public_form', 'Show lead custom fields on public form'); ?>
<hr class="mt-2 mb-2" />
<?php render_yes_no_option('gdpr_lead_attachments_on_public_form', 'Show lead attachments on public form and allow attachments to removed by the lead'); ?>
