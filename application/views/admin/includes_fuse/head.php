<!DOCTYPE html>
<html lang="en">
<head>
    <?php $isRTL = (is_rtl() ? 'true' : 'false'); ?>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <?php if (get_option('favicon') != '') { ?>
        <link href="<?php echo base_url('uploads/company/' . get_option('favicon')); ?>" rel="shortcut icon">
    <?php } ?>
    <title><?php if (isset($title)) {
            echo $title;
        } else {
            echo get_option('companyname');
        } ?></title>

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700italic,700,900,900italic"
          rel="stylesheet">
     <!-- K icons css -->
    <link type="text/css" rel="stylesheet" href="<?php echo base_url('/assets/Code/css/k_menu-style.css'); ?>">
    <link type="text/css" rel="stylesheet" href="<?php echo base_url('/assets/Code/css/k_menu-font.css'); ?>">     

    <!-- STYLESHEETS -->
    <style type="text/css">
        [fuse-cloak],
        .fuse-cloak {
            display: none !important;
        }
    </style>

    <link rel="manifest" href="<?php echo base_url('a_manifest.json'); ?>">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="#CDDC39">
    <meta name="apple-mobile-web-app-title" content="Sandbox Admin">
    <link rel="apple-touch-startup-image" href="<?php echo base_url('assets/pwa-icon/splash/launch-640x1136.png'); ?>" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)">
    <link rel="apple-touch-startup-image" href="<?php echo base_url('assets/pwa-icon/splash/launch-750x1294.png'); ?>" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)">
    <link rel="apple-touch-startup-image" href="<?php echo base_url('assets/pwa-icon/splash/launch-1242x2148.png'); ?>" media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)">
    <link rel="apple-touch-startup-image" href="<?php echo base_url('assets/pwa-icon/splash/launch-1125x2436.png'); ?>" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)">
    <link rel="apple-touch-startup-image" href="<?php echo base_url('assets/pwa-icon/splash/launch-1536x2048.png'); ?>" media="(min-device-width: 768px) and (max-device-width: 1024px) and (-webkit-min-device-pixel-ratio: 2) and (orientation: portrait)">
    <link rel="apple-touch-startup-image" href="<?php echo base_url('assets/pwa-icon/splash/launch-1668x2224.png'); ?>" media="(min-device-width: 834px) and (max-device-width: 834px) and (-webkit-min-device-pixel-ratio: 2) and (orientation: portrait)">
    <link rel="apple-touch-startup-image" href="<?php echo base_url('assets/pwa-icon/splash/launch-2048x2732.png'); ?>" media="(min-device-width: 1024px) and (max-device-width: 1024px) and (-webkit-min-device-pixel-ratio: 2) and (orientation: portrait)">

    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo base_url('assets/pwa-icon/iOS/Icon-57.png'); ?>">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo base_url('assets/pwa-icon/iOS/Icon-72.png'); ?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo base_url('assets/pwa-icon/iOS/Icon-114.png'); ?>">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo base_url('assets/pwa-icon/iOS/Icon-144.png'); ?>">
    <link rel="apple-touch-icon" href="<?php echo base_url('assets/pwa-icon/iOS/Icon-57.png'); ?>">

    <meta name="msapplication-TileColor" content="#CDDC39">
    <meta name="theme-color" content="#CDDC39">

    <link href="<?php echo base_url(); ?>assets-old/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="<?php echo base_url('assets-old/plugins/app-build/vendor.css?v='.get_app_version()); ?>" rel="stylesheet">
    <!-- Icons.css -->
    <link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/icons/fuse-icon-font/style.css'); ?>">
    <!-- Animate.css -->
    <link type="text/css" rel="stylesheet"
          href="<?php echo base_url('assets/node_modules/animate.css/animate.min.css'); ?>">
    <!-- PNotify -->
    <link type="text/css" rel="stylesheet"
          href="<?php echo base_url('assets/node_modules/pnotify/dist/PNotifyBrightTheme.css'); ?>">
    <!-- Nvd3 - D3 Charts -->
    <link type="text/css" rel="stylesheet"
          href="<?php echo base_url('assets/node_modules/nvd3/build/nv.d3.min.css'); ?>">
    <!-- Perfect Scrollbar -->
    <link type="text/css" rel="stylesheet"
          href="<?php echo base_url('assets/node_modules/perfect-scrollbar/css/perfect-scrollbar.css'); ?>">
    <!-- Fuse Html -->
    <link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/fuse-html/fuse-html.min.css'); ?>">


    <?php if ($isRTL === 'true') { ?>
        <link href="<?php echo base_url('assets-old/plugins/bootstrap-arabic/css/bootstrap-arabic.min.css'); ?>"
              rel="stylesheet">
    <?php } ?>
    <?php if (isset($calendar_assets)) { ?>        
        <link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/node_modules/fullcalendar/dist/fullcalendar.min.css'); ?>">
        <link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/node_modules/fullcalendar/dist/fullcalendar.print.min.css'); ?>" media="print">
    <?php } ?>
    <?php if (isset($projects_assets)) { ?>
        <link href='<?php echo base_url('assets-old/plugins/jquery-comments/css/jquery-comments.css'); ?>'
              rel='stylesheet'/>
        <link href='<?php echo base_url('assets-old/plugins/gantt/css/style.css'); ?>' rel='stylesheet'/>
    <?php } ?>
    
    <!-- Main CSS -->
    <link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/main1.css'); ?>">

    <!-- / STYLESHEETS -->
<!--    --><?php //echo app_stylesheet('assets-old/css', 'style.css'); ?>
    <?php if (file_exists(FCPATH . 'assets/css/custom.css')) { ?>
        <link href="<?php echo base_url('assets/css/custom.css'); ?>" rel="stylesheet">
    <?php } ?>

    <link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/artem_custom.css'); ?>">


    <?php render_custom_styles(array('general','tabs','buttons','admin','modals','tags')); ?>
    <?php render_admin_js_variables(); ?>
    <script>
        appLang['datatables'] = <?php echo json_encode(get_datatables_language_array()); ?>;
        var totalUnreadNotifications = <?php echo $current_user->total_unread_notifications; ?>,
            proposalsTemplates = <?php echo json_encode(get_proposal_templates()); ?>,
            contractsTemplates = <?php echo json_encode(get_contract_templates()); ?>,
            availableTags = <?php echo json_encode(get_tags_clean()); ?>,
            availableTagsIds = <?php echo json_encode(get_tags_ids()); ?>,
            billingAndShippingFields = ['billing_street', 'billing_city', 'billing_state', 'billing_zip', 'billing_country', 'shipping_street', 'shipping_city', 'shipping_state', 'shipping_zip', 'shipping_country'],
            locale = '<?php echo $locale; ?>',
            isRTL = '<?php echo $isRTL; ?>',
            tinymceLang = '<?php echo get_tinymce_language(get_locale_key($app_language)); ?>',
            monthsJSON = '<?php echo json_encode(array(_l('January'), _l('February'), _l('March'), _l('April'), _l('May'), _l('June'), _l('July'), _l('August'), _l('September'), _l('October'), _l('November'), _l('December'))); ?>',
            taskid, taskTrackingStatsData, taskAttachmentDropzone, taskCommentAttachmentDropzone,
            leadAttachmentsDropzone, newsFeedDropzone, expensePreviewDropzone, taskTrackingChart,
            cfh_popover_templates = {}, _table_api;
    </script>
    <?php do_action('app_admin_head'); ?>
</head>
<body class="layout layout-vertical layout-left-navigation layout-below-toolbar layout-below-footer" <?php echo admin_body_class(isset($bodyclass) ? $bodyclass : ''); ?><?php if ($isRTL === 'true') {
    echo 'dir="rtl"';
}; ?>>
<?php do_action('after_body_start'); ?>

