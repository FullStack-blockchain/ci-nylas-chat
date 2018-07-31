<div class="widget card m-b-15<?php if (!is_staff_member()) {
    echo ' hide';
} ?> card p-3 m-b-15" id="widget-<?php echo basename(__FILE__, ".php"); ?>"
     data-name="<?php echo _l('s_chart', _l('leads')); ?>">
    <?php if (is_staff_member()) { ?>
        <div class="card-body">
            <div class="widget-dragger"></div>
            <p class="padding-5"><?php echo _l('home_lead_overview'); ?></p>
            <hr class="hr-panel-heading-dashboard">
            <div class="relative" style="height:250px">
                <canvas class="chart" height="250" id="leads_status_stats"></canvas>
            </div>
        </div>
    <?php } ?>
</div>
