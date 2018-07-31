<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="loader"></div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="panel panel-success">
            <div class="panel-heading">
              <h3 class="panel-title"><a href="javascript:history.back(-1);" class="btn btn-warning">Back</a> <span><?php echo $page_title; ?></span> </h3> 
            </div>
            <div class="panel-body">
                <form class="form-horizontal" name="form_po" id="form_po">
                    <input type="hidden" name="id" value="<?php echo $po->id; ?>" id="id" />
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="vendor_name">Supplier: <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="supplier_name" readonly name="supplier_name" required="required" class="form-control" type="text" value="<?php echo $po->vendor_name;?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="date_issue">Date: <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="date_issue" name="date_issue" value="<?php echo $po->date_issue;?>" required="required" class="form-control" type="text">
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
                                  <input id="po_num" name="po_num" value="<?php echo $po->po_num;?>" required="required" class="form-control" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="pr_number">P.R. Number: <span class="required">*</span>
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                  <input id="pr_number" name="pr_number" value="<?php echo $po->pr_number;?>" required="required" class="form-control" type="text">
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
                                    <input id="supplier_invoice" name="supplier_invoice" value="<?php echo $po->supplier_invoice;?>" required="required" class="form-control" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="rr_num">R.R. No.: 
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="rr_num" name="rr_num" value="<?php echo $po->rr_num;?>" required="required" class="form-control" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="delivery_date">Delivery Date: 
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <input id="delivery_date" name="delivery_date" value="<?php echo $po->delivery_date;?>" required="required" class="form-control" type="text">
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
                                        <option value="cash" <?php echo($po->payment_terms=='cash'?"selected":""); ?>>Cash</option>
                                        <option value="open_account" <?php echo($po->payment_terms=='open_account'?"selected":""); ?>>Open Account</option>
                                        <option value="secured_account" <?php echo($po->payment_terms=='secured_account'?"selected":""); ?>>Secured Account</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="warranty_condition">Condition of warranty <span class="required">*</span>:
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea name="warranty_condition" id="warranty_condition" class="form-control"><?php echo $po->warranty_condition;?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="supplier_invoice">Penalty Clause:
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea class="form-control" readonly><?php echo getConfig('penalty_clause');?></textarea>
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
                                    <?php
                                        $sum = 0;

                                    foreach ($items as $key => $v) :
                                        $total = $v->quantity * $v->unit_price;
                                        $sum = $sum + $total;
                                        $input = '<input type="hidden" value="'.$v->id.'" name="items['.$v->id.']" class="items">';
                                        ?>
                                        <tr>
                                            <td><?php echo $v->id.$input; ?></td>
                                            <td><?php echo $v->product_name; ?></td>
                                            <td><?php echo $v->product_description; ?></td>
                                            <td><?php echo $v->quantity; ?></td>
                                            <td><?php echo number_format($v->unit_price, 2, '.', ','); ?></td>
                                            <td><?php echo number_format($total, 2, '.', ','); ?></td>
                                            
                                        </tr>
                                    <?php endforeach; ?>
                                    
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" align="right"><strong>Total:</strong></td>
                                        <td><strong><?php echo number_format($sum, 2, '.', ','); ?></strong>
                                        <input type="hidden" value="<?php echo number_format($sum, 2, '.', ','); ?>" name="total_amount" id="total_amount" />
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
                                    <p class="form-control"><?php echo ucwords($request->full_name); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="requisitioner">Certified By:
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <select name="auditor" id="auditor" class="form-control">
                                        <option value="">Select User</option>
                                        <?php foreach ($auditor as $key => $value) : ?>
                                            <option value="<?php echo $value->id; ?>" <?php echo ($po->approve_by == $value->id?"selected":""); ?>><?php echo ucwords($value->first_name." ".$value->last_name); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr />
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-3 col-sm-3 col-xs-12">Note to Auditor:
                                </label>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <textarea class="form-control" name="pd_remarks" id="pd_remarks"><?php echo $po->pd_remarks; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="panel-footer">
                <a href="javascript:;" onClick="xwb.reupdatePO()" class="btn btn-info">Update</a>
                <a href="<?php echo base_url('purchase_order/preview/'.$po->id); ?>" target="_blank" class="btn btn-success preview_po">Preview PO</a>
            </div>
        </div>

    </div>
</div>

<!-- Javascript variable from php for this page here -->
<script type="text/javascript">
    xwb_var.vaGetPOItems = '<?php echo base_url('purchase_order/getPOItems'); ?>';
    xwb_var.varReupdatePO = '<?php echo base_url('purchase_order/reupdatePO'); ?>';
    xwb_var.varGetPOBySupplier = '<?php echo base_url('purchase_order/getPOBySupplier'); ?>';
    xwb_var.request_id = <?php echo $request_id; ?>;

</script>
