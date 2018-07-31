<h4 class="f-18">
    General Config
</h4>
<hr class="mt-4 mb-r" />
<?php render_yes_no_option('enable_gdpr','Enable GDPR'); ?>
<hr class="mt-3 mb-3" />
<?php render_yes_no_option('show_gdpr_in_customers_menu','Show GDPR link in customers area navigation'); ?>
<hr class="mt-3 mb-3" />
<?php render_yes_no_option('show_gdpr_link_in_footer','Show GDPR link in customers area footer'); ?>
<hr class="mt-3 mb-3" />
<p class="mt-4">
    GDPR page top information block
</p>
<?php echo render_textarea('settings[gdpr_page_top_information_block]','',get_option('gdpr_page_top_information_block'),array(),array(),'pt-0','tinymce'); ?>
