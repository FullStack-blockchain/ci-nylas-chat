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
                <div class="row">
                    <form action="<?php echo base_url('request/create_po_submit'); ?>" method="post" accept-charset="utf-8" class="form-horizontal">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">PO Number</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                  <input name="po_num" id="po_num" class="form-control" placeholder="PO Number" type="text">
                                </div>
                             </div>
                             <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Customer Number</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                  <input name="customer_number" id="customer_number" class="form-control" placeholder="Customer Number" type="text">
                                </div>
                             </div>
                             <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Date Issue</label>
                                <div class="col-md-9 col-sm-9 col-xs-12">
                                  <input name="date_issue" id="date_issue" class="form-control" placeholder="Date Issue" type="text">
                                </div>
                             </div>
                        </div>
                        <div class="col-md-6">
                                
                        </div>
                    </form>
                </div>
            </div>
            <div class="panel-footer">

            </div>
        </div>

    </div>
</div>

<!-- Javascript variable from php for this page here -->
<script src="<?php echo base_url('assets/vendors/jQuery-Mask/dist/jquery.mask.min.js'); ?>"></script>
<script type="text/javascript">

</script>
