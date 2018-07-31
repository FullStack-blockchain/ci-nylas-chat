<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">

		<div class="panel panel-success">
            <div class="panel-heading">
              <h3 class="panel-title"><?php echo $page_title; ?></h3>
            </div>
            <div class="panel-body">
				<div class="table-responsive">
					<table class="table table-striped table_department">
					  <thead>
					    <tr>
					      <th>Title</th>
					      <th>Action</th>
					    </tr>
					  </thead>
					  <tbody>
					  <?php foreach ($doc_helper as $key => $value) {
					  	echo $value;
					  } ?>
					  </tbody>
					</table>
				</div>
            </div>
            <div class="panel-footer">
            	
            </div>
		</div>

	</div>
</div>

<!-- Javascript variable from php for this page here -->
<script type="text/javascript">
	var varGetDept = '<?php echo base_url('department/getDept'); ?>';
	var varEditDept = '<?php echo base_url('department/editDept'); ?>';
	var varUpdateDept = '<?php echo base_url('department/updateDept'); ?>';
	var varAddDept = '<?php echo base_url('department/addDept'); ?>';
	var varDeleteDept = '<?php echo base_url('department/deleteDept'); ?>';

</script>