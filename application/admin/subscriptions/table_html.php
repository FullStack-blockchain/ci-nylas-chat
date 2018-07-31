   <?php
   $table_data = array(
    array(
      'name'=>'#',
      'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-id')
    ),
    array(
      'name'=>_l('subscription_name'),
      'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-subscription-name')
    ),
    array(
      'name'=>_l('client'),
      'th_attrs'=>array('class'=>'toggleable'.(isset($client)? ' not_visible' : ''), 'id'=>'th-company')
    ),
    array(
      'name'=>_l('project'),
      'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-project')
    ),
    array(
      'name'=>_l('subscription_status'),
      'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-status')
    ),
    array(
      'name'=>_l('next_billing_cycle'),
      'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-next-billing-cycle')
    ),
    array(
      'name'=>_l('date_subscribed'),
      'th_attrs'=>array('class'=>'toggleable', 'id'=>'th-date-subscribed')
    ),
  );
   render_datatable($table_data,'subscriptions',
    array(),
    array(
      'id'=>'table-subscriptions',
      'data-url'=>$url,
      'data-last-order-identifier' => 'subscriptions',
      'data-default-order'         => get_table_last_order('subscriptions'),
    ));

   add_action('after_js_scripts_render', 'init_subscriptions_table_js');

   function init_subscriptions_table_js(){
    ?>
    <script>
      $(function(){
        var SubscriptionsServerParams = {};
        $.each($('._hidden_inputs._filters input'),function(){
          SubscriptionsServerParams[$(this).attr('name')] = '[name="'+$(this).attr('name')+'"]';
        });
          var url = $('#table-subscriptions').data('url');
          initDataTable('.table-subscriptions', url, undefined, undefined, SubscriptionsServerParams, <?php echo do_action('subscriptions_table_default_order',json_encode(array(6,'desc'))); ?>);
      });
    </script>
    <?php
  }
?>
