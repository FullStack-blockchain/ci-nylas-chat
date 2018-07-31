<div id="proposal-wrapper">
   <?php ob_start(); ?>
   <table class="table items no-margin">
      <thead>
         <tr>
            <th align="center">#</th>
            <th class="description" width="50%" align="left"><?php echo _l('estimate_table_item_heading'); ?></th>
            <?php
               $custom_fields = get_items_custom_fields_for_table_html($proposal->id,'proposal');
               foreach($custom_fields as $cf){
                echo '<th class="custom_field" align="left">' . $cf['name'] . '</th>';
               }
               $qty_heading = _l('estimate_table_quantity_heading');
               if($proposal->show_quantity_as == 2){
                  $qty_heading = _l('estimate_table_hours_heading');
               } else if($proposal->show_quantity_as == 3){
                  $qty_heading = _l('estimate_table_quantity_heading') .'/'._l('estimate_table_hours_heading');
               }
               ?>
            <th align="right"><?php echo $qty_heading; ?></th>
            <th align="right"><?php echo _l('estimate_table_rate_heading'); ?></th>
            <?php if(get_option('show_tax_per_item') == 1){ ?>
            <th align="right"><?php echo _l('estimate_table_tax_heading'); ?></th>
            <?php } ?>
            <th align="right"><?php echo _l('estimate_table_amount_heading'); ?></th>
         </tr>
      </thead>
      <tbody>
         <?php
            $items_data = get_table_items_and_taxes($proposal->items,'proposal');
            $taxes = $items_data['taxes'];
            echo $items_data['html'];
            ?>
      </tbody>
   </table>
   <div class="row mtop15">
      <div class="col-md-6 col-md-offset-6">
         <table class="table text-right">
            <tbody>
               <tr id="subtotal">
                  <td><span class="bold"><?php echo _l('estimate_subtotal'); ?></span>
                  </td>
                  <td class="subtotal">
                     <?php echo format_money($proposal->subtotal,$proposal->symbol); ?>
                  </td>
               </tr>
               <?php if(is_sale_discount_applied($proposal)){ ?>
               <tr>
                  <td>
                     <span class="bold"><?php echo _l('estimate_discount'); ?>
                     <?php if(is_sale_discount($proposal,'percent')){ ?>
                     (<?php echo _format_number($proposal->discount_percent,true); ?>%)
                     <?php } ?></span>
                  </td>
                  <td class="discount">
                     <?php echo '-' . format_money($proposal->discount_total,$proposal->symbol); ?>
                  </td>
               </tr>
               <?php } ?>
               <?php
                  foreach($taxes as $tax){
                   echo '<tr class="tax-area"><td class="bold">'.$tax['taxname'].' ('._format_number($tax['taxrate']).'%)</td><td>'.format_money($tax['total_tax'], $proposal->symbol).'</td></tr>';
                  }
                  ?>
               <?php if((int)$proposal->adjustment != 0){ ?>
               <tr>
                  <td>
                     <span class="bold"><?php echo _l('estimate_adjustment'); ?></span>
                  </td>
                  <td class="adjustment">
                     <?php echo format_money($proposal->adjustment,$proposal->symbol); ?>
                  </td>
               </tr>
               <?php } ?>
               <tr>
                  <td><span class="bold"><?php echo _l('estimate_total'); ?></span>
                  </td>
                  <td class="total">
                     <?php echo format_money($proposal->total,$proposal->symbol); ?>
                  </td>
               </tr>
            </tbody>
         </table>
      </div>
   </div>
   <?php
      if(get_option('total_to_words_enabled') == 1){ ?>
   <div class="col-md-12 text-center">
      <p class="bold"><?php echo  _l('num_word').': '.$this->numberword->convert($proposal->total,$proposal->currency_name); ?></p>
   </div>
   <?php }
      $items = ob_get_contents();
      ob_end_clean();
      $proposal->content = str_replace('{proposal_items}',$items,$proposal->content);
      ?>
   <div class="mtop30">
      <div class="row">
        <div class="col-md-12">
         <div class="mbot30">
          <?php echo get_dark_company_logo(); ?>
        </div>
        </div>
         <div class="col-md-12">
            <div class="pull-left">
               <h4 class="bold no-mtop"># <?php echo format_proposal_number($proposal->id); ?><br />
                  <small><?php echo $proposal->subject; ?></small>
               </h4>
            </div>
            <div class="visible-xs">
               <div class="clearfix"></div>
            </div>
            <?php if(($proposal->status != 2 && $proposal->status != 3)){
               if(!empty($proposal->open_till) && date('Y-m-d',strtotime($proposal->open_till)) < date('Y-m-d')){
                 echo '<span class="warning-bg content-view-status">'._l('proposal_expired').'</span>';
               } else { ?>
            <?php if($identity_confirmation_enabled == '1'){ ?>
            <button type="button" id="accept_action" class="btn btn-success pull-right mleft5">
              <i class="fa fa-check"></i> <?php echo _l('proposal_accept_info'); ?>
            </button>
            <?php } else { ?>
            <?php echo form_open($this->uri->uri_string()); ?>
            <button type="submit" data-loading-text="<?php echo _l('wait_text'); ?>" autocomplete="off" class="btn btn-success pull-right mleft5"><i class="fa fa-check"></i> <?php echo _l('proposal_accept_info'); ?></button>
            <?php echo form_hidden('action','accept_proposal'); ?>
            <?php echo form_close(); ?>
            <?php } ?>
            <?php echo form_open($this->uri->uri_string()); ?>
            <button type="submit" data-loading-text="<?php echo _l('wait_text'); ?>" autocomplete="off" class="btn btn-default pull-right mleft5"><i class="fa fa-remove"></i> <?php echo _l('proposal_decline_info'); ?></button>
            <?php echo form_hidden('action','decline_proposal'); ?>
            <?php echo form_close(); ?>
            <?php } ?>
            <!-- end expired proposal -->
            <?php } else {
               if($proposal->status == 2){
                 echo '<span class="danger-bg content-view-status">'._l('proposal_status_declined').'</span>';
               } else if($proposal->status == 3){
                 echo '<span class="success-bg content-view-status">'._l('proposal_status_accepted').'</span>';
               }
               } ?>
            <?php echo form_open($this->uri->uri_string()); ?>
            <button type="submit" class="btn btn-default pull-right mleft5"><i class="fa fa-file-pdf-o"></i> <?php echo _l('clients_invoice_html_btn_download'); ?></button>
            <?php echo form_hidden('action','proposal_pdf'); ?>
            <?php echo form_close(); ?>
            <?php if(is_client_logged_in() && has_contact_permission('proposals')){ ?>
                <a href="<?php echo site_url('clients/proposals/'); ?>" class="btn btn-default mleft5 pull-right">
                <?php echo _l('client_go_to_dashboard'); ?>
            </a>
            <?php } ?>
            <div class="clearfix"></div>
         </div>
         <div class="col-md-8 proposal-left">
            <div class="panel_s mtop20">
               <div class="panel-body proposal-content tc-content padding-30">
                  <?php echo $proposal->content; ?>
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
                  <?php if($proposal->allow_comments == 1){ ?>
                  <li role="presentation" class="<?php if($this->input->get('tab') === 'discussion'){echo 'active';} ?>">
                     <a href="#discussion" aria-controls="discussion" role="tab" data-toggle="tab">
                      <i class="fa fa-commenting-o" aria-hidden="true"></i> <?php echo _l('discussion'); ?>
                     </a>
                  </li>
                  <?php } ?>
               </ul>
               <div class="tab-content">
                  <div role="tabpanel" class="tab-pane<?php if(!$this->input->get('tab') || $this->input->get('tab') === 'summary'){echo ' active';} ?>" id="summary">
                     <address>
                        <?php echo format_organization_info(); ?>
                     </address>
                     <hr />
                     <p class="bold">
                        <?php echo _l('proposal_information'); ?>
                     </p>
                     <address class="no-margin">
                        <?php echo format_proposal_info($proposal, 'html'); ?>
                     </address>
                     <div class="row mtop20">
                        <?php if($proposal->total != 0){ ?>
                        <div class="col-md-12">
                           <h4 class="bold mbot30"><?php echo _l('proposal_total_info',format_money($proposal->total,$this->currencies_model->get($proposal->currency)->symbol)); ?></h4>
                        </div>
                        <?php } ?>
                        <div class="col-md-4 text-muted proposal-status">
                           <?php echo _l('proposal_status'); ?>
                        </div>
                        <div class="col-md-8 proposal-status">
                           <?php echo format_proposal_status($proposal->status,'', false); ?>
                        </div>
                        <div class="col-md-4 text-muted proposal-date">
                           <?php echo _l('proposal_date'); ?>
                        </div>
                        <div class="col-md-8 proposal-date">
                           <?php echo _d($proposal->date); ?>
                        </div>
                        <?php if(!empty($proposal->open_till)){ ?>
                        <div class="col-md-4 text-muted proposal-open-till">
                           <?php echo _l('proposal_open_till'); ?>
                        </div>
                        <div class="col-md-8 proposal-open-till">
                           <?php echo _d($proposal->open_till); ?>
                        </div>
                        <?php } ?>
                     </div>
                     <?php if(count($proposal->attachments) > 0 && $proposal->visible_attachments_to_customer_found == true){ ?>
                     <div class="proposal-attachments">
                        <hr />
                        <p class="bold mbot15"><?php echo _l('proposal_files'); ?></p>
                        <?php foreach($proposal->attachments as $attachment){
                           if($attachment['visible_to_customer'] == 0){continue;}
                           $attachment_url = site_url('download/file/sales_attachment/'.$attachment['attachment_key']);
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
                  <?php if($proposal->allow_comments == 1){ ?>
                  <div role="tabpanel" class="tab-pane<?php if($this->input->get('tab') === 'discussion'){echo ' active';} ?>" id="discussion">
                     <?php echo form_open($this->uri->uri_string()) ;?>
                     <div class="proposal-comment">
                        <textarea name="content" rows="4" class="form-control"></textarea>
                        <button type="submit" class="btn btn-info mtop10 pull-right"><?php echo _l('proposal_add_comment'); ?></button>
                        <?php echo form_hidden('action','proposal_comment'); ?>
                     </div>
                     <?php echo form_close(); ?>
                     <div class="clearfix"></div>
                     <?php
                        $proposal_comments = '';
                        foreach ($comments as $comment) {
                         $proposal_comments .= '<div class="proposal_comment mtop10 mbot20" data-commentid="' . $comment['id'] . '">';
                         if($comment['staffid'] != 0){
                           $proposal_comments .= staff_profile_image($comment['staffid'], array(
                             'staff-profile-image-small',
                             'media-object img-circle pull-left mright10'
                           ));
                         }
                         $proposal_comments .= '<div class="media-body valign-middle">';
                         $proposal_comments .= '<div class="mtop5">';
                         $proposal_comments .= '<b>';
                         if($comment['staffid'] != 0){
                           $proposal_comments .= get_staff_full_name($comment['staffid']);
                         } else {
                           $proposal_comments .= _l('is_customer_indicator');
                         }
                         $proposal_comments .= '</b>';
                         $proposal_comments .= ' - <small class="mtop10 text-muted">' . time_ago($comment['dateadded']) . '</small>';
                         $proposal_comments .= '</div>';
                         $proposal_comments .= '<br />';
                         $proposal_comments .= check_for_links($comment['content']) . '<br />';
                         $proposal_comments .= '</div>';
                         $proposal_comments .= '</div>';
                        }
                        echo $proposal_comments; ?>
                  </div>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>
   </div>
   <?php
      if($identity_confirmation_enabled == '1'){
        get_template_part('identity_confirmation_form',array('formData'=>form_hidden('action','accept_proposal')));
      }
      ?>
   <script>
      $(function(){
        $(".proposal-left table").wrap("<div class='table-responsive'></div>");
            // Create lightbox for proposal content images
            $('.proposal-content img').wrap( function(){ return '<a href="' + $(this).attr('src') + '" data-lightbox="proposal"></a>'; });
          });

   </script>
</div>
