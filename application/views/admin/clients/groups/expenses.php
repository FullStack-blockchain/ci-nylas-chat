<?php if(isset($client)){ ?>
<h4 class="customer-profile-group-heading"><?php echo _l('client_expenses_tab'); ?></h4>
    <div class="p-4">
<?php if(has_permission('expenses','','create')){ ?>
<a href="<?php echo admin_url('expenses/expense?customer_id='.$client->userid); ?>" class="btn btn-info <?php if($client->active == 0){echo ' disabled';} ?>">
    <?php echo _l('new_expense'); ?>
</a>
<?php } ?>
<div id="expenses_total" class="mt-6 mb-2"></div>
<?php $this->load->view('admin/expenses/table_html', array('class'=>'expenses-single-client')); ?>
    </div>
<?php } ?>
