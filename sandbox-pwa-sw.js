//This is the service worker with the Cache-first network

const PRECACHE = 'sandbox-precache-v3';
const RUNTIME = 'runtime-v3';
var FILE_EXT = ['css,js,png'];
var PRECACHE_URLS = [
    "/assets-old/css/reset.min.css?v=2.0.1",
    "/assets-old/plugins/roboto/roboto.css",
    "/assets-old/plugins/bootstrap/css/bootstrap.min.css",
    "/assets-old/plugins/jquery/jquery.min.js",
    "/assets-old/plugins/datatables/datatables.min.css",
    "/assets-old/plugins/font-awesome/css/font-awesome.min.css",
    "/assets-old/plugins/datetimepicker/jquery.datetimepicker.min.css",
    "/assets-old/plugins/bootstrap-select/css/bootstrap-select.min.css",
    "/assets-old/plugins/dropzone/min/basic.min.css",
    "/assets-old/plugins/dropzone/min/dropzone.min.css",
    "/assets-old/plugins/gantt/css/style.css",
    "/assets-old/plugins/jquery-comments/css/jquery-comments.css",
    "/assets-old/plugins/fullcalendar/fullcalendar.min.css",
    "/assets-old/plugins/lightbox/css/lightbox.min.css",
    "/assets-old/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css",
    "/assets-old/css/bs-overides.min.css?v=2.0.1",
    "/assets-old/themes/perfex/css/style.min.css?v=2.0.1",
    "/assets-old/images/user-placeholder.jpg",
    "/assets-old/plugins/bootstrap/js/bootstrap.min.js",
    "/assets-old/plugins/datatables/datatables.min.js",
    "/assets-old/plugins/jquery-validation/jquery.validate.min.js?v=2.0.1",
    "/assets-old/plugins/app-build/bootstrap-select.min.js?v=2.0.1",
    "/assets-old/plugins/datetimepicker/jquery.datetimepicker.full.min.js",
    "/assets-old/plugins/Chart.js/Chart.min.js",
    "/assets-old/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js",
    "/assets-old/themes/perfex/js/global.min.js?v=2.0.1",
    "/assets-old/plugins/lightbox/js/lightbox.min.js",
    "/assets-old/plugins/dropzone/min/dropzone.min.js",
    "/assets-old/plugins/app-build/moment.min.js",
    "/assets-old/plugins/jquery-comments/js/jquery-comments.min.js",
    "/assets-old/plugins/jquery-circle-progress/circle-progress.min.js",
    "/assets-old/plugins/gantt/js/jquery.fn.gantt.min.js",
    "/assets-old/plugins/fullcalendar/fullcalendar.min.js",
    "/assets-old/themes/perfex/js/clients.min.js?v=2.0.1",
    "/assets-old/plugins/lightbox/images/close.png",
    "/assets-old/plugins/lightbox/images/loading.gif",
    "/assets-old/plugins/lightbox/images/prev.png",
    "/assets-old/plugins/lightbox/images/next.png",
    "/assets-old/plugins/roboto/fonts/Regular/Roboto-Regular.woff2?v=1.1.0",
    "/assets-old/plugins/font-awesome/fonts/fontawesome-webfont.woff2?v=4.7.0",
    "/assets-old/plugins/roboto/fonts/Medium/Roboto-Medium.woff2?v=1.1.0",
    "/assets-old/js/main.js",
    "/assets-old/plugins/app-build/bootstrap-select.min.js",
    "/assets-old/plugins/app-build/moment.min.js",
    "/assets-old/plugins/app-build/vendor.css",
    "/assets-old/plugins/app-build/vendor.js",
    "/assets-old/plugins/bootstrap/fonts/glyphicons-halflings-regular.ttf",
    "/assets-old/plugins/bootstrap/fonts/glyphicons-halflings-regular.woff",
    "/assets-old/plugins/bootstrap/fonts/glyphicons-halflings-regular.woff2",
    "/assets-old/plugins/datatables/datatables.min.js",
    "/assets-old/plugins/font-awesome/css/font-awesome.min.css",
    "/assets-old/plugins/font-awesome/fonts/fontawesome-webfont.ttf",
    "/assets-old/plugins/font-awesome/fonts/fontawesome-webfont.woff2",
    "/assets-old/plugins/font-awesome/fonts/fontawesome-webfont.woff",
    "/assets-old/plugins/jquery-validation/jquery.validate.min.js",
    "/assets-old/plugins/jquery/jquery-migrate.min.js",
    "/assets-old/plugins/lightbox/images/close.png",
    "/assets-old/plugins/lightbox/images/loading.gif",
    "/assets-old/plugins/lightbox/images/next.png",
    "/assets-old/plugins/lightbox/images/prev.png",
    "/assets-old/plugins/tinymce/tinymce.min.js",
    "/assets/css/client.css",
    "/assets/css/contract.css",
    "/assets/css/credit-note.css",
    "/assets/css/custom.css",
    "/assets/css/dashboard.css",
    "/assets/css/estimates.css",
    "/assets/css/expense.css",
    "/assets/css/form-builder.css",
    "/assets/css/invoice.css",
    "/assets/css/knowledge-base.css",
    "/assets/css/leads.css",
    "/assets/css/main.css",
    "/assets/css/menu-setup.css",
    "/assets/css/payment.css",
    "/assets/css/project.css",
    "/assets/css/proposal.css",
    "/assets/css/subscriptions.css",
    "/assets/css/support-single.css",
    "/assets/css/support.css",
    "/assets/css/surveys.css",
    "/assets/css/tasks.css",
    "/assets/css/utilities.css",
    "/assets/css/main.css",
    "/assets/fuse-html/fuse-html.min.css",
    "/assets-old/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css",
    "/assets/fuse-html/fuse-html.min.js",
    "/assets/icons/fuse-icon-font/fonts/fuse-iconfont.ttf",
    "/assets/icons/fuse-icon-font/fonts/fuse-iconfont.woff",
    "/assets/icons/fuse-icon-font/style.css",
    "/assets/images/backgrounds/header-bg.png",
    "/assets/js/main.js",
    "/assets/node_modules/animate.css/animate.min.css",
    "/assets/node_modules/d3/d3.min.js",
    "/assets/node_modules/mobile-detect/mobile-detect.min.js",
    "/assets/node_modules/nvd3/build/nv.d3.min.css",
    "/assets/node_modules/nvd3/build/nv.d3.min.js",
    "/assets/node_modules/perfect-scrollbar/css/perfect-scrollbar.css",
    "/assets/node_modules/perfect-scrollbar/dist/perfect-scrollbar.min.js",
    "/assets/node_modules/pnotify/dist/PNotifyBrightTheme.css",
    "/assets/node_modules/pnotify/dist/iife/PNotify.js",
    "/assets/node_modules/pnotify/dist/iife/PNotifyButtons.js",
    "/assets/node_modules/pnotify/dist/iife/PNotifyCallbacks.js",
    "/assets/node_modules/pnotify/dist/iife/PNotifyConfirm.js",
    "/assets/node_modules/pnotify/dist/iife/PNotifyDesktop.js",
    "/assets/node_modules/pnotify/dist/iife/PNotifyHistory.js",
    "/assets/node_modules/pnotify/dist/iife/PNotifyMobile.js",
    "/assets/node_modules/pnotify/dist/iife/PNotifyReference.js",
    "/assets/node_modules/pnotify/dist/iife/PNotifyStyleMaterial.js",
    "/assets/node_modules/popper.js/dist/umd/popper.min.js",
    "/assets-old/plugins/form-builder/form-builder.min.js",
    "/assets-old/plugins/jquery-validation/additional-methods.min.js",
    "/assets-old/plugins/excellentexport/excellentexport.min.js",
    "/assets-old/js/surveys.js",
    "/assets-old/plugins/jquery-validation/additional-methods.min.js",
    "/assets-old/plugins/jquery-nestable/jquery.nestable.js",
    "/assets-old/plugins/font-awesome-icon-picker/css/fontawesome-iconpicker.min.css",
    "/assets-old/plugins/font-awesome-icon-picker/js/fontawesome-iconpicker.js",
    "/assets-old/plugins/jquery/jquery.min.js",
    "/assets-old/plugins/jquery-ui/jquery-ui.min.js",
    "/assets-old/plugins/elFinder/js/elfinder.min.js",
    "/assets-old/plugins/jquery-nestable/jquery.nestable.js",
    "/assets-old/plugins/font-awesome-icon-picker/css/fontawesome-iconpicker.min.css",
    "/assets-old/plugins/font-awesome-icon-picker/js/fontawesome-iconpicker.min.js"
];

// The install handler takes care of precaching the resources we always need.
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(PRECACHE)
            .then(cache => cache.addAll(PRECACHE_URLS))
            .then(self.skipWaiting())
    );
});

// The activate handler takes care of cleaning up old caches.
self.addEventListener('activate', event => {
    const currentCaches = [PRECACHE, RUNTIME];
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return cacheNames.filter(cacheName => !currentCaches.includes(cacheName));
        }).then(cachesToDelete => {
            return Promise.all(cachesToDelete.map(cacheToDelete => {
                return caches.delete(cacheToDelete);
            }));
        }).then(() => self.clients.claim())
    );
});

// The fetch handler serves responses for same-origin resources from a cache.
// If no response is found, it populates the runtime cache with the response
// from the network before returning it to the page.
self.addEventListener('fetch', event => {
    // Skip cross-origin requests, like those for Google Analytics.
    if (event.request.url.startsWith(self.location.origin) && event.request.url.indexOf('assets') > -1) {
        event.respondWith(
            caches.match(event.request).then(cachedResponse => {
                if (cachedResponse) {
                    return cachedResponse;
                }

                return caches.open(RUNTIME).then(cache => {
                    return fetch(event.request).then(response => {
                        // Put a copy of the response in the runtime cache.
                        return cache.put(event.request, response.clone()).then(() => {
                            return response;
                        });
                    });
                });
            })
        );
    }
});
