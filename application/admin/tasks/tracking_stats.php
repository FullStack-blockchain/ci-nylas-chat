<div class="modal fade" id="task-tracking-stats-modal" tabindex="-1" role="dialog" style="z-index: 10002">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo _l('task_statistics'); ?></h4>
                <button type="button" class="close close-task-stats" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="relative" style="min-height:250px;max-height:250px;">
                    <canvas id="task-tracking-stats-chart" height="250"></canvas>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<script>
    taskTrackingStatsData = <?php echo $stats; ?>;
</script>
