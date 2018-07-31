<div class="widget relative" id="widget-<?php echo basename(__FILE__,".php"); ?>" data-name="<?php echo _l('quick_stats'); ?>">
      <div class="widget-dragger"></div>
      <div class="row">
      <?php
         $initial_column = 'col-lg-3';
         if(!is_staff_member() && ((!has_permission('invoices','','view') && !has_permission('invoices','','view_own') && (get_option('allow_staff_view_invoices_assigned') == 0
           || (get_option('allow_staff_view_invoices_assigned') == 1 && !staff_has_assigned_invoices()))))) {
            $initial_column = 'col-lg-6';
         } else if(!is_staff_member() || (!has_permission('invoices','','view') && !has_permission('invoices','','view_own') && (get_option('allow_staff_view_invoices_assigned') == 1 && !staff_has_assigned_invoices()) || (get_option('allow_staff_view_invoices_assigned') == 0 && (!has_permission('invoices','','view') && !has_permission('invoices','','view_own'))))) {
            $initial_column = 'col-lg-4';
         }
      ?>
         <?php if(has_permission('invoices','','view') || has_permission('invoices','','view_own') || (get_option('allow_staff_view_invoices_assigned') == '1' && staff_has_assigned_invoices())){ ?>
         <div class="quick-stats-invoices col-xs-12 col-md-6 col-sm-6 m-b-15 <?php echo $initial_column; ?>">
            <div class="top_stats_wrapper card p-3 position-relative">
               <?php
                  $total_invoices = total_rows('tblinvoices','status NOT IN (5,6)'.(!has_permission('invoices','','view') ? ' AND ' . get_invoices_where_sql_for_staff(get_staff_user_id()) : ''));
                  $total_invoices_awaiting_payment = total_rows('tblinvoices','status NOT IN (2,5,6)'.(!has_permission('invoices','','view') ? ' AND ' . get_invoices_where_sql_for_staff(get_staff_user_id()) : ''));
                  $percent_total_invoices_awaiting_payment = ($total_invoices > 0 ? number_format(($total_invoices_awaiting_payment * 100) / $total_invoices,2) : 0);
                  ?>
               <p class="text-uppercase mtop5" style="min-height: calc(100% - 26px);"><i class="hidden-sm fa fa-balance-scale"></i> <?php echo _l('invoices_awaiting_payment'); ?>
                  <span class="pull-right"><?php echo $total_invoices_awaiting_payment; ?> / <?php echo $total_invoices; ?></span>
               </p>
               <div class="clearfix"></div>
               <div class="progress" style="margin-bottom: 0">
                  <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger
                  <?php echo $percent_total_invoices_awaiting_payment==0?'no-percent-text':''; ?>" role="progressbar"
                       aria-valuenow="<?php echo $percent_total_invoices_awaiting_payment; ?>" aria-valuemin="0"
                       aria-valuemax="100" style="width: 0%;white-space:nowrap;"
                       data-percent="<?php echo $percent_total_invoices_awaiting_payment; ?>">
                  </div>
               </div>
            </div>
         </div>
         <?php } ?>
         <?php if(is_staff_member()){ ?>
         <div class="quick-stats-leads col-xs-12 col-md-6 col-sm-6 m-b-15 <?php echo $initial_column; ?>">
            <div class="top_stats_wrapper card p-3 position-relative">
               <?php
                  $where = '';
                  if(!is_admin()){
                    $where .= '(addedfrom = '.get_staff_user_id().' OR assigned = '.get_staff_user_id().')';
                  }
                          // Junk leads are excluded from total
                  $total_leads = total_rows('tblleads',($where == '' ? 'junk=0' : $where .= ' AND junk =0'));
                  if($where == ''){
                   $where .= 'status=1';
                  } else {
                   $where .= ' AND status =1';
                  }
                  $total_leads_converted = total_rows('tblleads',$where);
                  $percent_total_leads_converted = ($total_leads > 0 ? number_format(($total_leads_converted * 100) / $total_leads,2) : 0);
                  ?>
               <p class="text-uppercase mtop5" style="min-height: calc(100% - 26px);"><i class="hidden-sm fa fa-tty"></i> <?php echo _l('leads_converted_to_client'); ?>
                  <span class="pull-right"><?php echo $total_leads_converted; ?> / <?php echo $total_leads; ?></span>
               </p>
               <div class="clearfix"></div>
               <div class="progress" style="margin-bottom: 0">
                  <div class="progress-bar progress-bar-striped progress-bar-animated bg-success
                  <?php echo $percent_total_leads_converted==0?'no-percent-text':''; ?>" role="progressbar"
                       aria-valuenow="<?php echo $percent_total_leads_converted; ?>" aria-valuemin="0" style="width: 0%;white-space:nowrap;"
                       aria-valuemax="100" data-percent="<?php echo $percent_total_leads_converted; ?>">
                  </div>
               </div>
            </div>
         </div>
         <?php } ?>
         <div class="quick-stats-projects col-xs-12 col-md-6 col-sm-6 m-b-15 <?php echo $initial_column; ?>">
            <div class="top_stats_wrapper card p-3 position-relative">
               <?php
                  $_where = '';
                  $project_status = get_project_status_by_id(2);
                  if(!has_permission('projects','','view')){
                    $_where = 'id IN (SELECT project_id FROM tblprojectmembers WHERE staff_id='.get_staff_user_id().')';
                  }
                  $total_projects = total_rows('tblprojects',$_where);
                  $where = ($_where == '' ? '' : $_where.' AND ').'status = 2';
                  $total_projects_in_progress = total_rows('tblprojects',$where);
                  $percent_in_progress_projects = ($total_projects > 0 ? number_format(($total_projects_in_progress * 100) / $total_projects,2) : 0);
                  ?>
               <p class="text-uppercase mtop5" style="min-height: calc(100% - 26px);"><i class="hidden-sm fa fa-cubes"></i> <?php echo _l('projects') . ' ' . $project_status['name']; ?><span class="pull-right"><?php echo $total_projects_in_progress; ?> / <?php echo $total_projects; ?></span></p>
               <div class="clearfix"></div>
                <div class="progress" style="margin-bottom: 0">
                    <div class="progress-bar progress-bar-striped progress-bar-animated
                    <?php echo $percent_in_progress_projects==0?'no-percent-text':''; ?>" role="progressbar"
                         aria-valuenow="<?php echo $percent_in_progress_projects; ?>"
                         aria-valuemin="0" style="width: 0%;white-space:nowrap;background-color:<?php echo $project_status['color']; ?>"
                         aria-valuemax="100"  data-percent="<?php echo $percent_in_progress_projects; ?>">
                    </div>
                </div>
            </div>
         </div>
         <div class="quick-stats-tasks col-xs-12 col-md-6 col-sm-6 m-b-15 <?php echo $initial_column; ?>">
            <div class="top_stats_wrapper card p-3 position-relative">
               <?php
                  $_where = '';
                  if (!has_permission('tasks', '', 'view')) {
                    $_where = 'tblstafftasks.id IN (SELECT taskid FROM tblstafftaskassignees WHERE staffid = ' . get_staff_user_id() . ')';
                  }
                  $total_tasks = total_rows('tblstafftasks',$_where);
                  $where = ($_where == '' ? '' : $_where.' AND ').'status != 5';
                  $total_not_finished_tasks = total_rows('tblstafftasks',$where);
                  $percent_not_finished_tasks = ($total_tasks > 0 ? number_format(($total_not_finished_tasks * 100) / $total_tasks,2) : 0);
                  ?>
               <p class="text-uppercase mtop5" style="min-height: calc(100% - 26px);"><i class="hidden-sm fa fa-tasks"></i> <?php echo _l('tasks_not_finished'); ?> <span class="pull-right">
                  <?php echo $total_not_finished_tasks; ?> / <?php echo $total_tasks; ?>
                  </span>
               </p>
               <div class="clearfix"></div>
               <div class="progress" style="margin-bottom: 0">
                  <div class="progress-bar progress-bar-striped progress-bar-animated progress-bar-default
                  <?php echo $percent_not_finished_tasks==0?'no-percent-text':''; ?>"
                       role="progressbar" aria-valuenow="<?php echo $percent_not_finished_tasks; ?>" aria-valuemin="0"
                       aria-valuemax="100" style="width: 0%;white-space:nowrap;" data-percent="<?php echo $percent_not_finished_tasks; ?>">
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
