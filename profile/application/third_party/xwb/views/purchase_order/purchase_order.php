<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="panel panel-success">
            <div class="panel-heading">
              <h3 class="panel-title"><?php echo $page_title; ?></h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table_po">
                        <thead>
                            <tr>
                                <th>P.O. #</th>
                                <th>P.R. #</th>
                                <th>Type of Request</th>
                                <th>Supplier</th>
                                <th>Status</th>
                                <th>Auditor</th>
                                <th>Auditor Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
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
    xwb_var.varGetPO = '<?php echo base_url('purchase_order/getPO'); ?>';

</script>
