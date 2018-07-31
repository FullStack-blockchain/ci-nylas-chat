<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="loader"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="panel panel-success">
            <div class="panel-heading">
              <h3 class="panel-title"><?php echo $page_title; ?></h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" name="form_po" id="form_po">
                    <input type="hidden" name="id" name="id" id="id" />
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="supplier">Supplier: <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="supplier" id="supplier" style="width:100%;">
                                            <option value="">Select Supplier</option>
                                        <?php foreach ($supplier as $k => $v) : ?>
                                                <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date_issue">Date: <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="date_issue" name="date_issue" required="required" class="form-control" type="text">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="po_num">P.O. Number: <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input id="po_num" name="po_num" required="required" class="form-control" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pr_number">P.R. Number: <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input id="pr_number" name="pr_number" required="required" class="form-control" type="text">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="supplier_invoice">Supplier Invoice:
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="supplier_invoice" name="supplier_invoice" required="required" class="form-control" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="rr_num">R.R. No.: 
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="rr_num" name="rr_num" required="required" class="form-control" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="delivery_date">Delivery Date: 
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="delivery_date" name="delivery_date" required="required" class="form-control" type="text">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="payment_terms">Terms of Payment <span class="required">*</span>:
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="payment_terms" id="payment_terms" class="form-control">
                                        <option value="">Select Option</option>
                                        <option value="cash">Cash</option>
                                        <option value="open_account">Open Account</option>
                                        <option value="secured_account">Secured Account</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="warranty_condition">Condition of warranty <span class="required">*</span>:
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea name="warranty_condition" id="warranty_condition" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="supplier_invoice">Penalty Clause:
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <p class=""><?php echo getConfig('penalty_clause');?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="table-responsive">
                            <table class="table table-striped jambo_table bulk_action table_po_items">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item Name</th>
                                        <th>Description</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" align="right"><strong>Total:</strong></td>
                                        <td><strong class="total_amount">0</strong>
                                        <input type="hidden" value="0" name="total_amount" id="total_amount" />
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="requisitioner">Requisitioner:
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <!-- <input id="requisitioner" name="requisitioner" readonly value="<?php echo $request->full_name; ?>" required="required" class="form-control" type="text"> -->
                                    <p class="form-control"><?php echo $request->full_name; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="requisitioner">Approved By:
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="auditor" id="auditor" class="form-control">
                                        <option value="">Select Auditor</option>
                                        <?php foreach ($auditor as $key => $value) : ?>
                                            <option value="<?php echo $value->id; ?>"><?php echo ucwords($value->first_name." ".$value->last_name); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="panel-footer">
                <a href="javascript:;" onClick="xwb.updatePO()" class="btn btn-info po_update">Update</a>
                <a href="" target="_blank" class="btn btn-success disabled preview_po">Preview PO</a>
            </div>
        </div>

    </div>
</div>

<!-- Javascript variable from php for this page here -->
<script type="text/javascript">
    xwb_var.vaGetPOItems = '<?php echo base_url('purchase_order/getPOItems'); ?>';
    xwb_var.varUpdatePO = '<?php echo base_url('purchase_order/updatePO'); ?>';
    xwb_var.varGetPOBySupplier = '<?php echo base_url('purchase_order/getPOBySupplier'); ?>';
    xwb_var.request_id = <?php echo $request_id; ?>;

</script>
