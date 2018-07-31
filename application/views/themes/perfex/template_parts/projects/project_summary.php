<?php
   $where = array('clientid'=>get_client_user_id());
   foreach($project_statuses as $status){ ?>
<div class="col-md-2 border-right">
   <?php $where['status'] = $status['id']; ?>
   <h3 class="bold"><a href="<?php echo site_url('clients/projects/'.$status['id']); ?>"><?php echo total_rows('tblprojects',$where); ?></a></h3>
   <span style="color:<?php echo $status['color']; ?>">
   <?php echo $status['name']; ?>
</div>
<?php } ?>
