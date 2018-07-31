<?php init_single_head(); ?>
<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,400italic|Roboto+Mono:400,500|Material+Icons"
      rel="stylesheet">
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/homepage.css'); ?>">

<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>

            <div class="content">

                <div id="project-dashboard" class="page-layout simple right-sidebar">

                    <div class="page-content-wrapper custom-scrollbar">

                        <!-- HEADER -->
                        <div class="page-header bg-primary text-auto d-flex flex-column justify-content-between px-6 pt-4 pb-0">

                            <div class="no-gutters align-items-start justify-content-between flex-nowrap">

                                <div>
                                    <span class="h2">Welcome back, <?php echo get_staff_full_name(); ?></span>
                                </div>

                                <button type="button" style="position: absolute;right: 10px;"
                                        class="sidebar-toggle-button btn btn-icon d-block d-xl-none pull-right"
                                        data-fuse-bar-toggle="dashboard-project-sidebar" aria-label="Toggle sidebar">
                                    <i class="icon icon-menu"></i>
                                </button>
                            </div>

                            <div class="row no-gutters align-items-center project-selection">

                                <div class="selected-project h6 px-4 py-2">ACME Corp. Backend App</div>

                                <div class="project-selector">
                                    <button type="button" class="btn btn-icon">
                                        <i class="icon icon-dots-horizontal"></i>
                                    </button>
                                </div>

                            </div>

                        </div>
                        <!-- / HEADER -->

                        <!-- CONTENT -->
                        <div class="page-content">
                            <div class="content">

                                <div class="homepage_button">
                                    <div class="row ml-0 mr-0">
                                        <div class="col-auto" style="min-height: 100px">
                                            <a href="#" class="button_link">
                                                <i class="icon-calendar-today btn-icon" data-toggle="tooltip"
                                                   data-title="My Calendar" data-placement="bottom">
                                                </i>
                                            </a>
                                        </div>

                                        <div class="col-auto" style="min-height: 100px">
                                            <a href="#" class="button_link">
                                                <i class="icon-email-outline btn-icon" data-toggle="tooltip"
                                                   data-title="Email"
                                                   data-placement="bottom">
                                                </i>
                                            </a>
                                        </div>

                                        <div class="col-auto" style="min-height: 100px">
                                            <a href="#" class="button_link">
                                                <i class="icon-folder btn-icon" data-toggle="tooltip"
                                                   data-title="File Manager"
                                                   data-placement="bottom">
                                                </i>
                                            </a>
                                        </div>

                                        <div class="col-auto" style="min-height: 100px">
                                            <a href="#" class="button_link">
                                                <i class="icon-menu btn-icon" data-toggle="tooltip"
                                                   data-title="Projects"
                                                   data-placement="bottom">
                                                </i>
                                            </a>
                                        </div>

                                        <div class="col-auto" style="min-height: 100px">
                                            <a href="#" class="button_link">
                                                <i class="icon-layers btn-icon" data-toggle="tooltip"
                                                   data-title="Sales"
                                                   data-placement="bottom">
                                                </i>
                                            </a>
                                        </div>

                                        <div class="col-auto" style="min-height: 100px">
                                            <a href="#" class="button_link">
                                                <i class="icon-developer-board btn-icon" data-toggle="tooltip"
                                                   data-title="CRM"
                                                   data-placement="bottom">
                                                </i>
                                            </a>
                                        </div>


                                        <div class="col-auto" style="min-height: 100px">
                                            <a href="#" class="button_link">
                                                <i class="icon-file-word btn-icon" data-toggle="tooltip"
                                                   data-title="Word"
                                                   data-placement="bottom">
                                                </i>
                                            </a>
                                        </div>

                                        <div class="col-auto" style="min-height: 100px">
                                            <a href="#" class="button_link">
                                                <i class="icon-file-excel btn-icon" data-toggle="tooltip"
                                                   data-title="Spreadsheet" data-placement="bottom">
                                                </i>
                                            </a>
                                        </div>

                                        <div class="col-auto" style="min-height: 100px">
                                            <a href="#" class="button_link">
                                                <i class="icon-presentation btn-icon" data-toggle="tooltip"
                                                   data-title="Presentation" data-placement="bottom">
                                                </i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- / CONTENT -->

                    </div>

                    <aside class="page-sidebar custom-scrollbar" data-fuse-bar="dashboard-project-sidebar"
                           data-fuse-bar-media-step="lg" data-fuse-bar-position="right">
                        <!-- WIDGET GROUP -->
                        <div class="widget-group">

                            <!-- NOW WIDGET -->
                            <div class="widget widget-now">

                                <div class="widget-header row no-gutters align-items-center justify-content-between pl-4 py-4">

                                    <div class="h6" id="currentDayTime">-</div>

                                    <button type="button" class="btn btn-icon" aria-label="Options">
                                        <i class="icon icon-dots-vertical"></i>
                                    </button>
                                </div>

                                <div class="widget-content d-flex flex-column align-items-center justify-content-center p-4 pb-6"
                                     id="currentDate">
                                    <div class="month text-muted">-</div>
                                    <div class="day text-muted">-</div>
                                    <div class="year text-muted">-</div>
                                </div>

                            </div>
                            <!-- / NOW WIDGET -->

                            <div class="divider"></div>

                            <!-- WEATHER WIDGET -->
                            <div class="widget widget-weather">

                                <div class="widget- header row no-gutters align-items-center justify-content-between pl-4 py-4">

                                    <div class="row no-gutters align-items-center">
                                        <i class="icon-map-marker mr-2"></i>
                                        <span class="h6" id="currentCountry">-</span>
                                    </div>

                                    <button type="button" class="btn btn-icon" aria-label="Options">
                                        <i class="icon icon-dots-vertical"></i>
                                    </button>

                                </div>

                                <div class="d-flex flex-column align-items-center justify-content-center p-4 pb-8">

                                    <div class="today-weather row no-gutters align-items-center justify-content-center">
                                        <i class="icon-weather-pouring s-10 mr-4"></i>
                                        <span class="h1">22</span>
                                        <span class="h1 text-muted">&deg;</span>
                                        <span class="h1 text-muted">C</span>
                                    </div>

                                </div>

                                <div class="row no-gutters align-items-center justify-content-between p-4">

                                    <div class="row no-gutters align-items-center">
                                        <i class="icon-weather-windy icon mr-2 s-5"></i>
                                        <span>12</span>
                                        <span class="text-muted ml-1">KMH</span>
                                    </div>

                                    <div class="row align-items-center">
                                        <i class="icon-compass-outline icon mr-2 s-5"></i>
                                        <span>NW</span>
                                    </div>

                                    <div class="row no-gutters align-items-center">
                                        <i class="icon-umbrella icon mr-2 s-5"></i>
                                        <span>98%</span>
                                    </div>

                                </div>

                                <div class="row no-gutters align-items-center justify-content-between p-4">

                                    <span class="">Sunday</span>

                                    <div class="row no-gutters align-items-center">
                                        <i class="mr-4 icon-weather-pouring"></i>
                                        <span class="h5">21</span>
                                        <span class="h5 text-muted">&deg;</span>
                                        <span class="h5 text-muted">C</span>
                                    </div>

                                </div>

                                <div class="row no-gutters align-items-center justify-content-between p-4">

                                    <span class="">Sunday</span>

                                    <div class="row no-gutters align-items-center">
                                        <i class="mr-4 icon-weather-pouring"></i>
                                        <span class="h5">19</span>
                                        <span class="h5 text-muted">&deg;</span>
                                        <span class="h5 text-muted">C</span>
                                    </div>

                                </div>

                                <div class="row no-gutters align-items-center justify-content-between p-4">

                                    <span class="">Tuesday</span>

                                    <div class="row no-gutters align-items-center">
                                        <i class="mr-4 icon-weather-partlycloudy"></i>
                                        <span class="h5">24</span>
                                        <span class="h5 text-muted">&deg;</span>
                                        <span class="h5 text-muted">C</span>
                                    </div>

                                </div>
                            </div>
                            <!-- / WEATHER WIDGET -->

                            <div class="divider"></div>
                        </div>
                        <!-- / WIDGET GROUP -->
                    </aside>
                </div>

            </div>
        </div>
    </div>
</main>
<?php init_tail(); ?>
<script>
    $(document).ready(function () {
        $("#currentDayTime").html(moment().format('dddd, HH:MM:SS'))
        $("#currentDate").html('<div class="month text-muted">' + moment().format('MMM') + '</div><div class="day text-muted">' + moment().format('D') + '</div><div class="year text-muted">' + moment().format('YYYY') + '</div>');

        $.get("https://ipinfo.io", function(response) {
            $.ajax({
                url: "https://geoip-db.com/jsonp",
                jsonpCallback: "callback",
                dataType: "jsonp",
                success: function( location ) {
                    $('#currentCountry').html(location.country_name);
                }
            });
        }, "jsonp");
    })
</script>

</body>
</html>
