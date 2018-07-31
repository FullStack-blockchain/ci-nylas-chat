<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1">

    <title><?php if (isset($title)) {
            echo $title;
        } ?></title>

    <link rel="manifest" href="<?php echo base_url('manifest.json'); ?>">

    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="#CDDC39">
    <meta name="apple-mobile-web-app-title" content="Sandbox Bufulo">
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


    <?php if (get_option('favicon') != '') { ?>
        <link href="<?php echo base_url('uploads/company/' . get_option('favicon')); ?>" rel="shortcut icon">
    <?php } ?>
    <?php if (!isset($exclude_reset_css)) { ?>
        <?php echo app_stylesheet('assets-old/css', 'reset.css'); ?>
    <?php } ?>
    <link href='<?php echo base_url('assets-old/plugins/roboto/roboto.css'); ?>' rel='stylesheet'>
    <link href="<?php echo base_url('assets-old/plugins/bootstrap/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <?php if (is_rtl(true)) { ?>
        <link rel="stylesheet"
              href="<?php echo base_url('assets-old/plugins/bootstrap-arabic/css/bootstrap-arabic.min.css'); ?>">
    <?php } ?>
    <script src="<?php echo base_url('assets-old/plugins/jquery/jquery.min.js'); ?>"></script>
    <link href="<?php echo base_url('assets-old/plugins/datatables/datatables.min.css'); ?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css"
          href="<?php echo base_url('assets-old/plugins/font-awesome/css/font-awesome.min.css'); ?>">
    <link href="<?php echo base_url('assets-old/plugins/datetimepicker/jquery.datetimepicker.min.css'); ?>"
          rel="stylesheet">
    <link href="<?php echo base_url('assets-old/plugins/bootstrap-select/css/bootstrap-select.min.css'); ?>"
          rel="stylesheet">
    <?php if (is_client_logged_in()) { ?>
        <link href="<?php echo base_url('assets-old/plugins/dropzone/min/basic.min.css'); ?>" rel='stylesheet'>
        <link href="<?php echo base_url('assets-old/plugins/dropzone/min/dropzone.min.css'); ?>" rel='stylesheet'>
        <link href='<?php echo base_url('assets-old/plugins/gantt/css/style.css'); ?>' rel='stylesheet'/>
        <link href='<?php echo base_url('assets-old/plugins/jquery-comments/css/jquery-comments.css'); ?>'
              rel='stylesheet'/>
        <link href='<?php echo base_url('assets-old/plugins/fullcalendar/fullcalendar.min.css'); ?>' rel='stylesheet'/>
    <?php } ?>
    <link href="<?php echo base_url('assets-old/plugins/lightbox/css/lightbox.min.css'); ?>" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css"
          href="<?php echo base_url('assets-old/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css'); ?>">
    <?php echo app_stylesheet('assets-old/css', 'bs-overides.css'); ?>
    <?php echo app_stylesheet(template_assets_path() . '/css', 'style.css'); ?>
    <?php if (file_exists(FCPATH . 'assets-old/css/custom.css')) { ?>
        <link href="<?php echo base_url('assets-old/css/custom.css'); ?>" rel="stylesheet" type='text/css'>
    <?php } ?>
    <?php render_custom_styles(array('general', 'tabs', 'buttons', 'customers', 'modals')); ?>
    <?php $isRTL = (is_rtl(true) ? 'true' : 'false'); ?>
    <!-- DO NOT REMOVE -->
    <?php do_action('app_customers_head', array('language' => $language)); ?>
</head>
<body class="customers <?php if (isset($bodyclass)) {
    echo $bodyclass;
} ?>" <?php if (is_rtl(true)) {
    echo 'dir="rtl"';
} ?>>
