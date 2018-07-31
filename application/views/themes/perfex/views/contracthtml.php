<div class="mtop30">
   <div class="mbot30">
      <?php echo get_dark_company_logo(); ?>
   </div>
   <h4 class="pull-left no-mtop"><?php echo $contract->subject; ?><br />
      <small><?php echo $contract->type_name; ?></small>
   </h4>
   <div class="visible-xs">
      <div class="clearfix"></div>
   </div>
   <?php if($contract->signed == 0) { ?>
   <button type="submit" id="accept_action" class="btn btn-success pull-right"><?php echo _l('e_signature_sign'); ?></button>
   <?php } else { ?>
   <span class="success-bg content-view-status"><?php echo _l('is_signed'); ?></span>
   <?php } ?>
   <?php echo form_open($this->uri->uri_string()); ?>
   <button type="submit" class="btn btn-default pull-right mright5">
   <i class="fa fa-file-pdf-o"></i> <?php echo _l('clients_invoice_html_btn_download'); ?></button>
   <?php echo form_hidden('action','contract_pdf'); ?>
   <?php echo form_close(); ?>
   <?php if(is_client_logged_in() && has_contact_permission('contracts')){ ?>
   <a href="<?php echo site_url('clients/contracts/'); ?>" class="btn btn-default mright5 pull-right">
     <?php echo _l('client_go_to_dashboard'); ?>
  </a>
  <?php } ?>
   <div class="clearfix"></div>
   <div class="row">
      <div class="col-md-8">
         <div class="panel_s mtop20">
            <div class="panel-body tc-content padding-30">
               <?php echo $contract->content; ?>
            </div>
         </div>
      </div>
      <div class="col-md-4">
         <div class="mtop20">
            <ul class="nav nav-tabs nav-tabs-flat mbot15" role="tablist">
               <li role="presentation" class="<?php if(!$this->input->get('tab') || $this->input->get('tab') === 'summary'){echo 'active';} ?>">
                  <a href="#summary" aria-controls="summary" role="tab" data-toggle="tab">
                  <i class="fa fa-file-text-o" aria-hidden="true"></i> <?php echo _l('summary'); ?></a>
               </li>
               <li role="presentation" class="<?php if($this->input->get('tab') === 'discussion'){echo 'active';} ?>">
                  <a href="#discussion" aria-controls="discussion" role="tab" data-toggle="tab">
                   <i class="fa fa-commenting-o" aria-hidden="true"></i> <?php echo _l('discussion'); ?>
                  </a>
               </li>
            </ul>
            <div class="tab-content">
               <div role="tabpanel" class="tab-pane<?php if(!$this->input->get('tab') || $this->input->get('tab') === 'summary'){echo ' active';} ?>" id="summary">
                  <address>
                     <?php echo format_organization_info(); ?>
                  </address>
                  <div class="row mtop20">
                     <?php if($contract->contract_value != 0){ ?>
                     <div class="col-md-12 contract-value">
                        <h4 class="bold mbot30">
                           <?php echo _l('contract_value'); ?>:
                           <?php echo format_money($contract->contract_value,$this->currencies_model->get_base_currency()->symbol); ?>
                        </h4>
                     </div>
                     <?php } ?>
                     <div class="col-md-4 text-muted contract-number">
                        #
                     </div>
                     <div class="col-md-8 contract-number">
                        <?php echo $contract->id; ?>
                     </div>
                     <div class="col-md-4 text-muted contract-start-date">
                        <?php echo _l('contract_start_date'); ?>
                     </div>
                     <div class="col-md-8 contract-start-date">
                        <?php echo _d($contract->datestart); ?>
                     </div>
                     <?php if(!empty($contract->dateend)){ ?>
                     <div class="col-md-4 text-muted contract-end-date">
                        <?php echo _l('contract_end_date'); ?>
                     </div>
                     <div class="col-md-8 contract-end-date">
                        <?php echo _d($contract->dateend); ?>
                     </div>
                     <?php } ?>
                     <?php if(!empty($contract->type_name)){ ?>
                     <div class="col-md-4 text-muted contract-type">
                        <?php echo _l('contract_type'); ?>
                     </div>
                     <div class="col-md-8 contract-type">
                        <?php echo $contract->type_name; ?>
                     </div>
                     <?php } ?>
                     <?php if($contract->signed == 1){ ?>
                     <div class="col-md-4 text-muted contract-type">
                        <?php echo _l('date_signed'); ?>
                     </div>
                     <div class="col-md-8 contract-type">
                        <?php echo _d(explode(' ', $contract->acceptance_date)[0]); ?>
                     </div>
                     <?php } ?>
                  </div>
                  <?php if(count($contract->attachments) > 0){ ?>
                  <div class="contract-attachments">
                     <div class="clearfix"></div>
                     <hr />
                     <p class="bold mbot15"><?php echo _l('contract_files'); ?></p>
                     <?php foreach($contract->attachments as $attachment){
                        $attachment_url = site_url('download/file/contract/'.$attachment['attachment_key']);
                        if(!empty($attachment['external'])){
                           $attachment_url = $attachment['external_link'];
                        }
                        ?>
                     <div class="col-md-12 row mbot15">
                        <div class="pull-left"><i class="<?php echo get_mime_class($attachment['filetype']); ?>"></i></div>
                        <a href="<?php echo $attachment_url; ?>"><?php echo $attachment['file_name']; ?></a>
                     </div>
                     <?php } ?>
                  </div>
                  <?php } ?>
               </div>
               <div role="tabpanel" class="tab-pane<?php if($this->input->get('tab') === 'discussion'){echo ' active';} ?>" id="discussion">
                  <?php echo form_open($this->uri->uri_string()) ;?>
                  <div class="contract-comment">
                     <textarea name="content" rows="4" class="form-control"></textarea>
                     <button type="submit" class="btn btn-info mtop10 pull-right"><?php echo _l('proposal_add_comment'); ?></button>
                     <?php echo form_hidden('action','contract_comment'); ?>
                  </div>
                  <?php echo form_close(); ?>
                  <div class="clearfix"></div>
                  <?php
                     $contractComments = '';
                     foreach ($comments as $comment) {
                      $contractComments .= '<div class="contract_comment mtop10 mbot20" data-commentid="' . $comment['id'] . '">';
                      if($comment['staffid'] != 0){
                        $contractComments .= staff_profile_image($comment['staffid'], array(
                          'staff-profile-image-small',
                          'media-object img-circle pull-left mright10'
                        ));
                      }
                      $contractComments .= '<div class="media-body valign-middle">';
                      $contractComments .= '<div class="mtop5">';
                      $contractComments .= '<b>';
                      if($comment['staffid'] != 0){
                        $contractComments .= get_staff_full_name($comment['staffid']);
                      } else {
                       $contractComments .= _l('is_customer_indicator');
                     }
                     $contractComments .= '</b>';
                     $contractComments .= ' - <small class="mtop10 text-muted">' . time_ago($comment['dateadded']) . '</small>';
                     $contractComments .= '</div>';
                     $contractComments .= '<br />';
                     $contractComments .= check_for_links($comment['content']) . '<br />';
                     $contractComments .= '</div>';
                     $contractComments .= '</div>';
                     }
                     echo $contractComments; ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php
   get_template_part('identity_confirmation_form',array('formData'=>form_hidden('action','sign_contract')));
?>
