<?php
   if ($estimate['status'] == $status) { ?>
<li data-estimate-id="<?php echo $estimate['id']; ?>" class="<?php if($estimate['invoiceid'] != NULL){echo 'not-sortable';} ?>">
   <div class="panel-body card">
      <div class="row">
         <div class="col-md-12">
            <h5 class="bold pipeline-heading">
                <a href="<?php echo admin_url('estimates/list_estimates/'.$estimate['id']); ?>" class="f-16 text-dark" onclick="estimate_pipeline_open(<?php echo $estimate['id']; ?>); return false;"><?php echo format_estimate_number($estimate['id']); ?></a>
               <?php if(has_permission('estimates','','edit')){ ?>
               <a href="<?php echo admin_url('estimates/estimate/'.$estimate['id']); ?>" target="_blank" class="pull-right"><small><i class="fa fa-pencil-square-o" aria-hidden="true"></i></small></a>
               <?php } ?>
            </h5>
            <span class="inline-block full-width mb-2">
            <a href="<?php echo admin_url('clients/client/'.$estimate['clientid']); ?>" target="_blank">
            <?php echo $estimate['company']; ?>
            </a>
            </span>
         </div>
         <div class="col-md-12">
            <div class="row">
               <div class="col-md-8 f-12">
                  <p class="bold m-b-5 f-12"><?php echo _l('estimate_total') . ': ' . format_money($estimate['total'],$estimate['symbol']); ?></p>
                  <?php echo _l('estimate_data_date') . ': ' . _d($estimate['date']); ?>
                  <?php if(is_date($estimate['expirydate']) || !empty($estimate['expirydate'])){
                     echo '<br />';
                     echo _l('estimate_data_expiry_date') . ': ' . _d($estimate['expirydate']);
                     } ?>
               </div>
               <div class="col-md-4 text-right">
                  <small><i class="fa fa-paperclip"></i> <?php echo _l('estimate_notes'); ?>: <?php echo total_rows('tblnotes', array(
                     'rel_id' => $estimate['id'],
                     'rel_type' => 'estimate',
                     )); ?></small>
               </div>
               <?php $tags = get_tags_in($estimate['id'],'estimate');
                  if(count($tags) > 0){ ?>
               <div class="col-md-12">
                  <div class="mtop5 kanban-tags">
                     <?php echo render_tags($tags); ?>
                  </div>
               </div>
               <?php } ?>
            </div>
         </div>
      </div>
   </div>
</li>
<?php } ?>
