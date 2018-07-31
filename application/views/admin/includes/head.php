<!DOCTYPE html>
<html lang="en">
<head>
    <?php $isRTL = (is_rtl() ? 'true' : 'false'); ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1" />
    <?php if(get_option('favicon') != ''){ ?>
    <link href="<?php echo base_url('uploads/company/'.get_option('favicon')); ?>" rel="shortcut icon">
    <?php } ?>
    <title><?php if (isset($title)){ echo $title; } else { echo get_option('companyname'); } ?></title>
    <?php echo app_stylesheet('assets-old/css','reset.css'); ?>
    <link href='<?php echo base_url('assets-old/plugins/roboto/roboto.css'); ?>' rel='stylesheet'>
    <link href="<?php echo base_url('assets-old/plugins/app-build/vendor.css?v='.get_app_version()); ?>" rel="stylesheet">
    <?php if($isRTL === 'true'){ ?>
    <link href="<?php echo base_url('assets-old/plugins/bootstrap-arabic/css/bootstrap-arabic.min.css'); ?>" rel="stylesheet">
    <?php } ?>
    <?php if(isset($calendar_assets)){ ?>
    <link href='<?php echo base_url('assets-old/plugins/fullcalendar/fullcalendar.min.css?v='.get_app_version()); ?>' rel='stylesheet' />
    <?php } ?>
    <?php if(isset($projects_assets)){ ?>
    <link href='<?php echo base_url('assets-old/plugins/jquery-comments/css/jquery-comments.css'); ?>' rel='stylesheet' />
    <link href='<?php echo base_url('assets-old/plugins/gantt/css/style.css'); ?>' rel='stylesheet' />
    <?php } ?>
    <?php echo app_stylesheet('assets-old/css','style.css'); ?>
    <?php if(file_exists(FCPATH.'assets-old/css/custom.css')){ ?>
    <link href="<?php echo base_url('assets-old/css/custom.css'); ?>" rel="stylesheet">
    <?php } ?>
    <?php render_custom_styles(array('general','tabs','buttons','admin','modals','tags')); ?>
    <?php render_admin_js_variables(); ?>
    <script>
        appLang['datatables'] = <?php echo json_encode(get_datatables_language_array()); ?>;
        var totalUnreadNotifications = <?php echo $current_user->total_unread_notifications; ?>,
        proposalsTemplates = <?php echo json_encode(get_proposal_templates()); ?>,
        contractsTemplates = <?php echo json_encode(get_contract_templates()); ?>,
        availableTags = <?php echo json_encode(get_tags_clean()); ?>,
        availableTagsIds = <?php echo json_encode(get_tags_ids()); ?>,
        billingAndShippingFields = ['billing_street','billing_city','billing_state','billing_zip','billing_country','shipping_street','shipping_city','shipping_state','shipping_zip','shipping_country'],
        locale = '<?php echo $locale; ?>',
        isRTL = '<?php echo $isRTL; ?>',
        tinymceLang = '<?php echo get_tinymce_language(get_locale_key($app_language)); ?>',
        monthsJSON = '<?php echo json_encode(array(_l('January'),_l('February'),_l('March'),_l('April'),_l('May'),_l('June'),_l('July'),_l('August'),_l('September'),_l('October'),_l('November'),_l('December'))); ?>',
        taskid,taskTrackingStatsData,taskAttachmentDropzone,taskCommentAttachmentDropzone,leadAttachmentsDropzone,newsFeedDropzone,expensePreviewDropzone,taskTrackingChart,cfh_popover_templates = {},_table_api;
    </script>
    <?php do_action('app_admin_head'); ?>
</head>
<body <?php echo admin_body_class(isset($bodyclass) ? $bodyclass : ''); ?><?php if($isRTL === 'true'){ echo 'dir="rtl"';}; ?>>
<?php do_action('after_body_start'); ?>
