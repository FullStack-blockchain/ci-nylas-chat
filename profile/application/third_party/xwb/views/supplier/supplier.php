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
                    <table class="table table-striped table_supplier">
                      <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Tel. Number</th>
                            <th>Mobile Number</th>
                            <th>Fax</th>
                            <th>Payment Terms</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        
                      </tbody>
                    </table>
                </div>
            </div>
            <div class="panel-footer">
                <a href="" class="btn btn-info xwb-add-supplier">Add Supplier</a>
            </div>
        </div>

    </div>
</div>

<!-- Javascript variable from php for this page here -->
<script type="text/javascript">
    var xwb_var = {};
    xwb_var.varGetSupplier = '<?php echo base_url('supplier/getSupplier'); ?>';
    xwb_var.varAddSupplier = '<?php echo base_url('supplier/addSupplier'); ?>';
    xwb_var.varEditSupplier = '<?php echo base_url('supplier/editSupplier'); ?>';
    xwb_var.varUpdateSupplier = '<?php echo base_url('supplier/updateSupplier'); ?>';
    xwb_var.varDeleteSupplier = '<?php echo base_url('supplier/deleteSupplier'); ?>';
    
</script>