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
                <form class="form-horizontal" accept-charset="utf-8">
                    
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Year: </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <select class="form-control search_opt" name="year" id="year">
                                <!-- <option value="">All</option> -->
                                <?php foreach (range(date('Y'), 2000) as $key => $value) : ?>
                                    <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                <?php endforeach; ?>
                              </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Month: </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <select class="form-control search_opt" name="month" id="month">
                                <option value="">All</option>
                                <?php foreach (range(1, 12) as $key => $value) : ?>
                                    <option value="<?php echo $value; ?>"><?php echo date('F', strtotime(date('Y').'-'.$value.'-'.date('d'))); ?></option>
                                <?php endforeach; ?>
                              </select>
                            </div>
                        </div>
                    </div>
                    <!-- We will use this in the next update -->
                    <!-- <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">SY: </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <select class="form-control search_opt" name="sy" id="sy">
                                <option value="">All</option>
                                <?php foreach ($sy as $key => $value) : ?>
                                    <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                <?php endforeach; ?>
                              </select>
                            </div>
                        </div>
                    </div> -->
                </form>
                   <hr />
                <div class="table-responsive">
                    <table class="table table-striped table_po_reports">
                      <thead>
                        <tr>
                            
                            <th>P.O. #</th>
                            <th>P.R. #</th>
                            <th>Type of Request</th>
                            <th>Supplier</th>
                            <th>Payment Terms</th>
                            <th>Warranty Condition</th>
                            <th>Total Amount</th>
                            <th>Requisitioner</th>
                            <th>Certified By</th>
                            <th>Date Certified</th>
                            <th>Prepared By</th>
                        </tr>
                      </thead>
                      <tbody>
                        
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
    xwb_var.varGetStaffPOReports = '<?php echo base_url('reports/getStaffPOReports'); ?>';

</script>
