 <?php
 $table_data = array(
   '#',
   _l('contract_list_subject'),
   array(
     'name'=>_l('contract_list_client'),
     'th_attrs'=>array('class'=> (isset($client) ? 'not_visible' : ''))
   ),
   _l('contract_types_list_name'),
   _l('contract_value'),
   _l('contract_list_start_date'),
   _l('contract_list_end_date'),
   _l('signature'),
 );
 $custom_fields = get_custom_fields('contracts',array('show_on_table'=>1));

 foreach($custom_fields as $field){
   array_push($table_data,$field['name']);
 }

 $table_data = do_action('contracts_table_columns',$table_data);
 render_datatable($table_data, (isset($class) ? $class : 'contracts'),[],[
    'data-last-order-identifier' => 'contracts',
    'data-default-order'         => get_table_last_order('contracts'),
 ]);
 ?>
