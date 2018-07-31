<?php include_once(APPPATH . 'views/admin/includes_fuse/helpers_bottom.php'); ?>
<?php do_action('before_js_scripts_render'); ?>
<script src="<?php echo base_url('assets-old/plugins/app-build/vendor.js?v=' . get_app_version()); ?>"></script>
<script src="<?php echo base_url('assets-old/plugins/jquery/jquery-migrate.' . (ENVIRONMENT === 'production' ? 'min.' : '') . 'js'); ?>"></script>
<script src="<?php echo base_url('assets-old/plugins/datatables/datatables.min.js?v=' . get_app_version()); ?>"></script>
<script src="<?php echo base_url('assets-old/plugins/app-build/moment.min.js'); ?>"></script>
<?php app_select_plugin_js($locale); ?>
<script src="<?php echo base_url('assets-old/plugins/tinymce/tinymce.min.js?v=' . get_app_version()); ?>"></script>
<?php app_jquery_validation_plugin_js($locale); ?>
<?php if (get_option('dropbox_app_key') != '') { ?>
    <script type="text/javascript" src="https://www.dropbox.com/static/api/2/dropins.js" id="dropboxjs"
            data-app-key="<?php echo get_option('dropbox_app_key'); ?>"></script>
<?php } ?>
<?php if (isset($media_assets)) { ?>
    <script src="<?php echo base_url('assets-old/plugins/elFinder/js/elfinder.min.js'); ?>"></script>
    <?php if (file_exists(FCPATH . 'assets-old/plugins/elFinder/js/i18n/elfinder.' . get_media_locale($locale) . '.js') && get_media_locale($locale) != 'en') { ?>
        <script src="<?php echo base_url('assets-old/plugins/elFinder/js/i18n/elfinder.' . get_media_locale($locale) . '.js'); ?>"></script>
    <?php } ?>
<?php } ?>
<?php if (isset($projects_assets)) { ?>
    <script src="<?php echo base_url('assets-old/plugins/jquery-comments/js/jquery-comments.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets-old/plugins/gantt/js/jquery.fn.gantt.min.js'); ?>"></script>
<?php } ?>
<?php if (isset($circle_progress_asset)) { ?>
    <script src="<?php echo base_url('assets-old/plugins/jquery-circle-progress/circle-progress.min.js'); ?>"></script>
<?php } ?>
<?php if (isset($calendar_assets)) { ?>
    <script src="<?php echo base_url('assets-old/plugins/fullcalendar/fullcalendar.min.js?v=' . get_app_version()); ?>"></script>
    <?php if (get_option('google_api_key') != '') { ?>
        <script src="<?php echo base_url('assets-old/plugins/fullcalendar/gcal.min.js'); ?>"></script>
    <?php } ?>
    <?php if (file_exists(FCPATH . 'assets-old/plugins/fullcalendar/locale/' . $locale . '.js') && $locale != 'en') { ?>
        <script src="<?php echo base_url('assets-old/plugins/fullcalendar/locale/' . $locale . '.js'); ?>"></script>
    <?php } ?>
<?php } ?>

<!-- JAVASCRIPT -->
<!-- Mobile Detect -->
<script type="text/javascript"
        src="<?php echo base_url('assets/node_modules/mobile-detect/mobile-detect.min.js'); ?>"></script>
<!-- Perfect Scrollbar -->
<script type="text/javascript"
        src="<?php echo base_url('assets/node_modules/perfect-scrollbar/dist/perfect-scrollbar.min.js'); ?>"></script>
<!-- Popper.js -->
<script type="text/javascript"
        src="<?php echo base_url('assets/node_modules/popper.js/dist/umd/popper.min.js'); ?>"></script>
<!-- Nvd3 - D3 Charts -->
<script type="text/javascript" src="<?php echo base_url('assets/node_modules/d3/d3.min.js'); ?>"></script>
<script type="text/javascript"
        src="<?php echo base_url('assets/node_modules/nvd3/build/nv.d3.min.js'); ?>"></script>
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
<script type="text/javascript"
        src="<?php echo base_url('assets-old/js/main.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/main.js'); ?>"></script>
<!-- / JAVASCRIPT -->

<?php
/**
 * Global function for custom field of type hyperlink
 */
echo get_custom_fields_hyperlink_js_function(); ?>
<?php
/**
 * Check for any alerts stored in session
 */
app_js_alerts();
?>
<?php
/**
 * Check pusher real time notifications
 */
if (get_option('pusher_realtime_notifications') == 1) { ?>
    <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
    <script type="text/javascript">
        $(function () {
            // Enable pusher logging - don't include this in production
            // Pusher.logToConsole = true;
            <?php $pusher_options = do_action('pusher_options', array());
            if (!isset($pusher_options['cluster']) && get_option('pusher_cluster') != '') {
                $pusher_options['cluster'] = get_option('pusher_cluster');
            } ?>
            var pusher_options = <?php echo json_encode($pusher_options); ?>;
            var pusher = new Pusher("<?php echo get_option('pusher_app_key'); ?>", pusher_options);
            var channel = pusher.subscribe('notifications-channel-<?php echo get_staff_user_id(); ?>');
            channel.bind('notification', function (data) {
                fetch_notifications();
            });
        });
    </script>
<?php } ?>
<?php
/**
 * End users can inject any javascript/jquery code after all js is executed
 */
do_action('after_js_scripts_render');

?>
<!--<script src="--><?php //echo base_url('./sandbox-pwa-sw-register.js'); ?><!--"></script>-->
