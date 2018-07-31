<div id="stats-top" class="hide card mb-4 p-4">
    <div id="invoices_total"></div>
    <div class="mt-4 card">
        <div class="panel-body">
            <?php
            $where_all = '';
            $has_permission_view = has_permission('invoices', '', 'view');
            if (isset($project)) {
                $where_all .= 'project_id=' . $project->id . ' AND ';
            }
            if (!$has_permission_view) {
                $where_all .= get_invoices_where_sql_for_staff(get_staff_user_id());
            }
            $where_all = trim($where_all);
            if (endsWith($where_all, ' AND')) {
                $where_all = substr_replace($where_all, '', -4);
            }
            $total_invoices = total_rows('tblinvoices', $where_all);
            ?>
            <div class="row text-left quick-top-stats">
                <?php foreach ($invoices_statuses as $status) {
                    if ($status == 5) {
                        continue;
                    }
                    $where = array('status' => $status);
                    if (isset($project)) {
                        $where['project_id'] = $project->id;
                    }
                    if (!$has_permission_view) {
                        $where['addedfrom'] = get_staff_user_id();
                    }
                    $total_by_status = total_rows('tblinvoices', $where);
                    $percent = ($total_invoices > 0 ? number_format(($total_by_status * 100) / $total_invoices, 2) : 0);
                    ?>
                    <div class="col col-md mb-3">
                        <div class="row">
                            <div class="col-md-7">
                                <a href="#" data-cview="invoices_<?php echo $status; ?>"
                                   onclick="dt_custom_view('invoices_<?php echo $status; ?>','.table-invoices','invoices_<?php echo $status; ?>',true); return false;">
                                    <h5><?php echo format_invoice_status($status, '', false); ?></h5>
                                </a>
                            </div>
                            <div class="col-md-5 text-right">
                                <?php echo $total_by_status; ?> / <?php echo $total_invoices; ?>
                            </div>
                            <div class="col-md-12">
                                <div class="progress no-margin">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated
                                        <?php echo $percent == 0 ? 'no-percent-text' : ''; ?>
                                        progress-bar-<?php echo get_invoice_status_label($status); ?>"
                                         role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                                         style="width: 0%" data-percent="<?php echo $percent; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
