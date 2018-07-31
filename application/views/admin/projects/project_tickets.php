<?php
    $this->load->view('admin/tickets/summary',array('project_id'=>$project->id));
    echo form_hidden('project_id',$project->id);
    echo '<div class="clearfix"></div>';
    if(((get_option('access_tickets_to_none_staff_members') == 1 && !is_staff_member()) || is_staff_member())){
        echo '<div class="mb-1"><a href="'.admin_url('tickets/add?project_id='. $project->id).'" class="btn btn-secondary">'._l('new_ticket').'</a></div>';
    }
    echo AdminTicketsTableStructure('tickets-table');
?>
