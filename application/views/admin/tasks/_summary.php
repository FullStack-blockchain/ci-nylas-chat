<div class="row mt-4">
  <?php foreach(tasks_summary_data((isset($rel_id) ? $rel_id : null),(isset($rel_type) ? $rel_type : null)) as $summary){ ?>
    <div class="col col-md mb-3">
        <div class="fuse-widget-front mat-white-bg mat-elevation-z2 ng-tns-c24-4">
            <div class="p-6 text-center">
                <div class="font-size-48 line-height-48" style="color:<?php echo $summary['color']; ?>"><?php echo $summary['total_tasks']; ?></div>
                <div class="h4 mt-6 font-weight-500 f-16" style="color:<?php echo $summary['color']; ?>">
                    <?php echo $summary['name']; ?>
                </div>
                <p class="f-14 text-muted mt-1">
                    <?php echo _l('tasks_view_assigned_to_user'); ?>: <?php echo $summary['total_my_tasks']; ?>
                </p>
            </div>
        </div>
    </div>
    <?php } ?>
  </div>
  <hr class="hr-panel-heading"/>
