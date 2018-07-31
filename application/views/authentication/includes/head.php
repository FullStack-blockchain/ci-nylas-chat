<!DOCTYPE html>
<html lang="en">
<head>
    <title>
        <?php echo get_option('companyname'); ?> - Authentication
    </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <?php if (get_option('favicon') != '') { ?>
        <link href="<?php echo base_url('uploads/company/' . get_option('favicon')); ?>" rel="shortcut icon">
    <?php } ?>

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
    <!-- Main CSS -->
    <link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/main.css'); ?>">
    <!-- / STYLESHEETS -->


    <?php if (is_rtl()) { ?>
        <link href="<?php echo base_url('assets-old/plugins/bootstrap-arabic/css/bootstrap-arabic.min.css'); ?>"
              rel="stylesheet">
    <?php } ?>

    <!-- JAVASCRIPT -->
    <!-- jQuery -->
    <script type="text/javascript"
            src="<?php echo base_url('assets/node_modules/jquery/dist/jquery.min.js'); ?>"></script>
    <!-- Mobile Detect -->
    <script type="text/javascript"
            src="<?php echo base_url('assets/node_modules/mobile-detect/mobile-detect.min.js'); ?>"></script>
    <!-- Perfect Scrollbar -->
    <script type="text/javascript"
            src="<?php echo base_url('assets/node_modules/perfect-scrollbar/dist/perfect-scrollbar.min.js'); ?>"></script>
    <!-- Popper.js -->
    <script type="text/javascript"
            src="<?php echo base_url('assets/node_modules/popper.js/dist/umd/popper.min.js'); ?>"></script>
    <!-- Bootstrap -->
    <script type="text/javascript"
            src="<?php echo base_url('assets/node_modules/bootstrap/dist/js/bootstrap.min.js'); ?>"></script>
    <!-- Nvd3 - D3 Charts -->
    <script type="text/javascript" src="<?php echo base_url('assets/node_modules/d3/d3.min.js'); ?>"></script>
    <script type="text/javascript"
            src="<?php echo base_url('assets/node_modules/nvd3/build/nv.d3.min.j'); ?>s"></script>
    <!-- Data tables -->
    <script type="text/javascript"
            src="<?php echo base_url('assets/node_modules/datatables.net/js/jquery.dataTables.js'); ?>"></script>
    <script type="text/javascript"
            src="<?php echo base_url('assets/node_modules/datatables-responsive/js/dataTables.responsive.js'); ?>"></script>
    <!-- PNotify -->
    <script type="text/javascript"
            src="<?php echo base_url('assets/node_modules/pnotify/dist/iife/PNotify.js'); ?>"></script>
    <script type="text/javascript"
            src="<?php echo base_url('assets/node_modules/pnotify/dist/iife/PNotifyStyleMaterial.js'); ?>"></script>
    <script type="text/javascript"
            src="<?php echo base_url('assets/node_modules/pnotify/dist/iife/PNotifyButtons.js'); ?>"></script>
    <script type="text/javascript"
            src="<?php echo base_url('assets/node_modules/pnotify/dist/iife/PNotifyCallbacks.js'); ?>"></script>
    <script type="text/javascript"
            src="<?php echo base_url('assets/node_modules/pnotify/dist/iife/PNotifyMobile.js'); ?>"></script>
    <script type="text/javascript"
            src="<?php echo base_url('assets/node_modules/pnotify/dist/iife/PNotifyHistory.js'); ?>"></script>
    <script type="text/javascript"
            src="<?php echo base_url('assets/node_modules/pnotify/dist/iife/PNotifyDesktop.js'); ?>"></script>
    <script type="text/javascript"
            src="<?php echo base_url('assets/node_modules/pnotify/dist/iife/PNotifyConfirm.js'); ?>"></script>
    <script type="text/javascript"
            src="<?php echo base_url('assets/node_modules/pnotify/dist/iife/PNotifyReference.js'); ?>"></script>
    <!-- Fuse Html -->
    <script type="text/javascript" src="<?php echo base_url('assets/fuse-html/fuse-html.min.js'); ?>"></script>
    <!-- Main JS -->
    <script type="text/javascript" src="<?php echo base_url('assets/js/main.js'); ?>"></script>
    <!-- / JAVASCRIPT -->


    <?php if (get_option('recaptcha_secret_key') != '' && get_option('recaptcha_site_key') != '') { ?>
        <script src='https://www.google.com/recaptcha/api.js'></script>
    <?php } ?>
    <?php if (file_exists(FCPATH . 'assets-old/css/custom.css')) { ?>
        <link href="<?php echo base_url('assets-old/css/custom.css'); ?>" rel="stylesheet">
    <?php } ?>
    <?php do_action('app_admin_login_head'); ?>

    <script src="<?php echo base_url('sandbox-pwa-sw-register.js'); ?>"></script>


</head>
