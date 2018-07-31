<?php if (!isset($column)) {
    $column = 'col-md-5ths';
}
?>
<div class="staff_logged_time mb-4">

    <div class="row">
        <div class="col col-md mb-3">
            <div class="fuse-widget-front mat-white-bg mat-elevation-z2 ng-tns-c24-4">
                <div class="p-6 text-center">
                    <div class="f-28 line-height-28">
                        <?php echo seconds_to_time_format($logged_time['total']); ?>
                    </div>

                    <div class="h5 f-16 mt-8 font-weight-500 text-success">
                        <?php echo _l('staff_stats_total_logged_time'); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col col-md mb-3">
            <div class="fuse-widget-front mat-white-bg mat-elevation-z2 ng-tns-c24-4">
                <div class="p-6 text-center">
                    <div class="f-28 line-height-28">
                        <?php echo seconds_to_time_format($logged_time['last_month']); ?>
                    </div>

                    <div class="h5 f-16 mt-8 font-weight-500 text-info">
                        <?php echo _l('staff_stats_last_month_total_logged_time'); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col col-md mb-3">
            <div class="fuse-widget-front mat-white-bg mat-elevation-z2 ng-tns-c24-4">
                <div class="p-6 text-center">
                    <div class="f-28 line-height-28">
                        <?php echo seconds_to_time_format($logged_time['this_month']); ?>
                    </div>

                    <div class="h5 f-16 mt-8 font-weight-500 text-success">
                        <?php echo _l('staff_stats_this_month_total_logged_time'); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col col-md mb-3">
            <div class="fuse-widget-front mat-white-bg mat-elevation-z2 ng-tns-c24-4">
                <div class="p-6 text-center">
                    <div class="f-28 line-height-28">
                        <?php echo seconds_to_time_format($logged_time['last_week']); ?>
                    </div>

                    <div class="h5 f-16 mt-8 font-weight-500 text-info">
                        <?php echo _l('staff_stats_last_week_total_logged_time'); ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col col-md mb-3">
            <div class="fuse-widget-front mat-white-bg mat-elevation-z2 ng-tns-c24-4">
                <div class="p-6 text-center">
                    <div class="f-28 line-height-28">
                        <?php echo seconds_to_time_format($logged_time['this_week']); ?>
                    </div>

                    <div class="h5 f-16 mt-8 font-weight-500 text-success">
                        <?php echo _l('staff_stats_this_week_total_logged_time'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="clearfix"></div>
