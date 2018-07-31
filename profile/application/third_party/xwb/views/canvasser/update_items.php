<?php
defined('BASEPATH') or exit('No direct script access allowed');

?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="panel panel-success">
            <div class="panel-heading">
            <a href="<?php echo base_url('canvasser/req_assign');?>" class="btn btn-warning">Back</a>
              <h3 class="panel-title"><?php echo $page_title; ?></h3>
            </div>
            <div class="panel-body">
                <form name="form_update_items" id="form_update_items" accept-charset="utf-8">
                <div class="row">
                    <div class="col-md-4 col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Type of Request: </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input value="<?php echo $canvass->request_name; ?>" class="form-control" readonly placeholder="" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Purpose: </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <textarea class="form-control" readonly placeholder=""><?php echo $canvass->purpose; ?></textarea>
                            </div>
                        </div>
                        
                    </div>
                   
                    <div class="col-md-4 col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Date Needed: </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                                <?php echo ($canvass->date_needed==null?"":date("F j, Y", strtotime($canvass->date_needed))); ?>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-md-4 col-xs-12 col-sm-6">
                        <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Initial Canvass Date: </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input value="<?php echo $canvass->init_canvass_date; ?>" class="form-control" placeholder="" type="text" name="init_canvass_date" id="init_canvass_date">
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <div class="row">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                          <h3 class="panel-title"><span>Canvassed Items / Products</span> 
                          <!-- <a href="javascript:;" onClick="xwb.addItem();" class="btn btn-xs btn-info">Add Item</a></h3> -->
                          <a href="javascript:;" onClick="xwb.supplierSummary(<?php echo $request_id; ?>);" class="btn btn-info btn-xs pull-right">Supplier summary</a></h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table_products table-hover table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Supplier 1</th>
                                            <th>Supplier 2</th>
                                            <th>Supplier 3</th>
                                            <th>Supplier 4</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $counter = 1;
                                        foreach ($items as $key => $value) : ?>

                                        <?php
                                            $supplier = getSuplliersFromCanvassed($value->request_id, $value->product_id, $value->product_name);
                                            //pre($supplier->result(),true);
                                            $supplier_num = $supplier->num_rows();
                                            $supplier = $supplier->result();
                                            $supplier_1 = null;
                                            $supplier_2 = null;
                                            $supplier_3 = null;
                                            $supplier_4 = null;
                                        for ($i=0; $i < $supplier_num; $i++) {
                                            $post_var = $i+1;
                                            ${'supplier_'.$post_var} = $supplier[$i];
                                        }
                                            $row_total = 0;
                                        ?>

                                            <tr>
                                                <td><?php echo $value->product_name; ?>
                                                    <input type="hidden" name="item_id[]" value="<?php echo $value->id; ?>" />
                                                    <input type="hidden" name="product_id[]" value="<?php echo $value->product_id; ?>" />
                                                </td>
                                                <td>
                                                <!-- <input type="text" value="<?php echo $value->quantity; ?>" name="quantity[<?php echo $value->id; ?>]" class="form-control quantity_<?php echo $value->id; ?> quantity" /> -->
                                                <span><?php echo $value->quantity; ?></span>
                                                <select name="unit_measurements[<?php echo $value->id; ?>]" class="unit-measurement form-control" style="width: 100px;">
                                                    <?php
                                                    foreach ($unit_measurements as $key => $unitVal) {
                                                        echo "<option value='".$key."' ".($value->unit_measurement == $key?"selected":"").">".$unitVal."</option>";
                                                    }
                                                    ?>
                                                </select>
                                                </td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                        <?php
                                                            $supplier = (isset($supplier_1)?$supplier_1:null);
                                                            $cp_status = (is_null($supplier)?null:$supplier->cp_status);
                                                            $po_item_id = ($cp_status == null?'new1_'.$value->id:$supplier->id.'_'.$supplier->cp_id);
                                                            $supplier_id = (is_null($supplier)?null:$supplier->supplier_id);

                                                            $supplier_name = (is_null($supplier)?null:$supplier->supplier);
                                                            $unit_price = (is_null($supplier)?0:$supplier->price);
                                                            $quantity = (is_null($supplier)?0:$supplier->quantity);
                                                            $row_total = $row_total + ($unit_price * $quantity);
                                                        ?>
                                                            <select name="supplier1[<?php echo $po_item_id; ?>]" class="supplier supplier_<?php echo $value->id; ?>" style="width: 100%;">
                                                                <option value="">Select Supplier</option>
                                                                <?php

                                                                foreach ($suppliers as $key => $v) :
                                                                    ?>
                                                                    <option value="<?php echo $v->id; ?>" <?php echo ($supplier_id == $v->id?"selected":""); ?>><?php echo $v->supplier_name; ?></option>

                                                                <?php  endforeach;
                                                                if ($supplier_id == 0) :
                                                                ?>
                                                                <option value="<?php echo $supplier_name; ?>" selected><?php echo $supplier_name; ?></option>
                                                                <?php endif; ?>
                                                            </select>
                                                            <input type="hidden" name="po_item_id[]" value="<?php echo $po_item_id; ?>" />
                                                        </div>
                                                        <div class="col-md-2">
                                                            <span class="pull-left width-10p"><input type="checkbox" class="flat supplier-check" name="include_supplier1[<?php echo $po_item_id; ?>]" <?php echo ($cp_status==1?"checked":""); ?> ></span>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="row">
                                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback canvasser-input-col-div">
                                                        <input type="text" class="unit_price form-control has-feedback-left canvasser-input" placeholder="Price" name="unit_price1[<?php echo $po_item_id; ?>]" id="unit_price" value="<?php echo $unit_price; ?>" />
                                                        <span class="form-control-feedback left canvasser-label-icon" aria-hidden="true">Price</span>
                                                    </div>
                                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback canvasser-input-col-div">
                                                        <input type="text" class="qty form-control has-feedback-left canvasser-input" placeholder="Qty" name="qty1[<?php echo $po_item_id; ?>]" id="" value="<?php echo $quantity; ?>" />
                                                        <span class="form-control-feedback left canvasser-label-icon" aria-hidden="true">Qty</span>
                                                    </div>
                                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback canvasser-input-col-div">
                                                        <input readonly type="text" class="supplier-total form-control has-feedback-left canvasser-input" placeholder="Total" name="total1[<?php echo $po_item_id; ?>]" id="" value="<?php echo $unit_price * $quantity; ?>" />
                                                        <span class="form-control-feedback left canvasser-label-icon" aria-hidden="true">Total</span>
                                                    </div>
                                                        
                                                    </div>

                                                    

                                                </td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                        <?php
                                                            $supplier = (isset($supplier_2)?$supplier_2:null);
                                                            $cp_status = (is_null($supplier)?null:$supplier->cp_status);
                                                            $po_item_id = ($cp_status == null?'new2_'.$value->id:$supplier->id.'_'.$supplier->cp_id);
                                                            $supplier_id = (is_null($supplier)?null:$supplier->supplier_id);

                                                            $supplier_name = (is_null($supplier)?null:$supplier->supplier);
                                                            $unit_price = (is_null($supplier)?0:$supplier->price);
                                                            $quantity = (is_null($supplier)?0:$supplier->quantity);
                                                            $row_total = $row_total + ($unit_price * $quantity);
                                                        ?>
                                                            <select name="supplier2[<?php echo $po_item_id; ?>]" class="supplier supplier_<?php echo $value->id; ?>" style="width: 100%;">
                                                                <option value="">Select Supplier</option>
                                                                <?php
                                                                foreach ($suppliers as $key => $v) :
                                                                    ?>
                                                                    <option value="<?php echo $v->id; ?>" <?php echo ($supplier_id == $v->id?"selected":""); ?>><?php echo $v->supplier_name; ?></option>

                                                                <?php  endforeach;
                                                                if ($supplier_id == 0) :
                                                                ?>
                                                                <option value="<?php echo $supplier_name; ?>" selected><?php echo $supplier_name; ?></option>
                                                                <?php endif; ?>
                                                            </select>
                                                            <input type="hidden" name="po_item_id[]" value="<?php echo $po_item_id; ?>" />
                                                        </div>
                                                        <div class="col-md-2">
                                                            <span class="pull-left width-10p"><input type="checkbox" class="flat supplier-check" <?php echo ($cp_status==1?"checked":""); ?> name="include_supplier2[<?php echo $po_item_id; ?>]" ></span>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    
                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback canvasser-input-col-div">
                                                            <input type="text" class="unit_price form-control has-feedback-left canvasser-input" placeholder="Price" name="unit_price2[<?php echo $po_item_id; ?>]" id="unit_price" value="<?php echo $unit_price; ?>" />
                                                            <span class="form-control-feedback left canvasser-label-icon" aria-hidden="true">Price</span>
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback canvasser-input-col-div">
                                                            <input type="text" class="qty form-control has-feedback-left canvasser-input" placeholder="Qty" name="qty2[<?php echo $po_item_id; ?>]" id="" value="<?php echo $quantity; ?>" />
                                                            <span class="form-control-feedback left canvasser-label-icon" aria-hidden="true">Qty</span>
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback canvasser-input-col-div">
                                                            <input readonly type="text" class="supplier-total form-control has-feedback-left canvasser-input" placeholder="Total" name="total2[<?php echo $po_item_id; ?>]" id="" value="<?php echo $unit_price * $quantity; ?>" />
                                                            <span class="form-control-feedback left canvasser-label-icon" aria-hidden="true">Total</span>
                                                        </div>
                                                    </div>

                                                    

                                                </td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                        <?php
                                                            $supplier = (isset($supplier_3)?$supplier_3:null);
                                                            $cp_status = (is_null($supplier)?null:$supplier->cp_status);
                                                            $po_item_id = ($cp_status  == null?'new3_'.$value->id:$supplier->id.'_'.$supplier->cp_id);
                                                            $supplier_id = (is_null($supplier)?null:$supplier->supplier_id);

                                                            $supplier_name = (is_null($supplier)?null:$supplier->supplier);
                                                            $unit_price = (is_null($supplier)?0:$supplier->price);
                                                            $quantity = (is_null($supplier)?0:$supplier->quantity);
                                                            $row_total = $row_total + ($unit_price * $quantity);
                                                        ?>
                                                            <select name="supplier3[<?php echo $po_item_id; ?>]" class="supplier supplier_<?php echo $value->id; ?>" style="width: 100%;">
                                                                <option value="">Select Supplier</option>
                                                                <?php

                                                                foreach ($suppliers as $key => $v) :
                                                                    ?>
                                                                    <option value="<?php echo $v->id; ?>" <?php echo ($supplier_id == $v->id?"selected":""); ?>><?php echo $v->supplier_name; ?></option>

                                                                <?php  endforeach;
                                                                if ($supplier_id == 0) :
                                                                ?>
                                                                <option value="<?php echo $supplier_name; ?>" selected><?php echo $supplier_name; ?></option>
                                                                <?php endif; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <span class="pull-left width-10p"><input type="checkbox" class="flat supplier-check" <?php echo ($cp_status==1?"checked":""); ?> name="include_supplier3[<?php echo $po_item_id; ?>]" /></span>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback canvasser-input-col-div">
                                                            <input type="text" class="unit_price form-control has-feedback-left canvasser-input" placeholder="Price" name="unit_price3[<?php echo $po_item_id; ?>]" id="unit_price" value="<?php echo $unit_price; ?>" />
                                                            <span class="form-control-feedback left canvasser-label-icon" aria-hidden="true">Price</span>
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback canvasser-input-col-div">
                                                            <input type="text" class="qty form-control has-feedback-left canvasser-input" placeholder="Qty" name="qty3[<?php echo $po_item_id; ?>]" id="" value="<?php echo $quantity; ?>" />
                                                            <span class="form-control-feedback left canvasser-label-icon" aria-hidden="true">Qty</span>
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback canvasser-input-col-div">
                                                            <input readonly type="text" class="supplier-total form-control has-feedback-left canvasser-input" placeholder="Total" name="total3[<?php echo $po_item_id; ?>]" id="" value="<?php echo $unit_price * $quantity; ?>" />
                                                            <span class="form-control-feedback left canvasser-label-icon" aria-hidden="true">Total</span>
                                                        </div>
                                                    </div>

                                                    

                                                </td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                        <?php
                                                            $supplier = (isset($supplier_4)?$supplier_4:null);
                                                            $cp_status = (is_null($supplier)?null:$supplier->cp_status);
                                                            $po_item_id = ($cp_status == null?'new4_'.$value->id:$supplier->id.'_'.$supplier->cp_id);
                                                            $supplier_id = (is_null($supplier)?null:$supplier->supplier_id);
                                                            
                                                            $supplier_name = (is_null($supplier)?null:$supplier->supplier);
                                                            $unit_price = (is_null($supplier)?0:$supplier->price);
                                                            $quantity = (is_null($supplier)?0:$supplier->quantity);
                                                            $row_total = $row_total + ($unit_price * $quantity);
                                                        ?>
                                                            <select name="supplier4[<?php echo $po_item_id; ?>]" class="supplier supplier_<?php echo $value->id; ?>" style="width: 100%;">
                                                                <option value="">Select Supplier</option>
                                                                <?php

                                                                foreach ($suppliers as $key => $v) :
                                                                    ?>
                                                                    <option value="<?php echo $v->id; ?>" <?php echo ($supplier_id == $v->id?"selected":""); ?>><?php echo $v->supplier_name; ?></option>

                                                                <?php  endforeach;
                                                                if ($supplier_id == 0) :
                                                                ?>
                                                                <option value="<?php echo $supplier_name; ?>" selected><?php echo $supplier_name; ?></option>
                                                                <?php endif; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <span class="pull-left width-10p"><input type="checkbox" class="flat supplier-check" <?php echo ($cp_status==1?"checked":""); ?> name="include_supplier4[<?php echo $po_item_id; ?>]" /></span>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback canvasser-input-col-div">
                                                            <input type="text" class="unit_price form-control has-feedback-left canvasser-input" placeholder="Price" name="unit_price4[<?php echo $po_item_id; ?>]" id="unit_price" value="<?php echo $unit_price; ?>" />
                                                            <span class="form-control-feedback left canvasser-label-icon" aria-hidden="true">Price</span>
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback canvasser-input-col-div">
                                                            <input type="text" class="qty form-control has-feedback-left canvasser-input" placeholder="Qty" name="qty4[<?php echo $po_item_id; ?>]" id="" value="<?php echo $quantity; ?>" />
                                                            <span class="form-control-feedback left canvasser-label-icon" aria-hidden="true">Qty</span>
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback canvasser-input-col-div">
                                                            <input readonly type="text" class="supplier-total form-control has-feedback-left canvasser-input" placeholder="Total" name="total4[<?php echo $po_item_id; ?>]" id="" value="<?php echo $unit_price * $quantity; ?>" />
                                                            <span class="form-control-feedback left canvasser-label-icon" aria-hidden="true">Total</span>
                                                        </div>
                                                    </div>

                                                    

                                                </td>
                                                <td>
                                                    <input readonly type="text" class="row-total form-control" value="<?php echo $row_total; ?>" placeholder="Total" name="item_total" id="item_total" />
                                                </td>
                                            </tr>
                                        <?php
                                        $counter++;
                                        endforeach; ?>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5" align="right"></td>
                                            <td align="right"><strong>Total: </strong></td>
                                            <td><strong class="net_total"><?php echo $canvass->total_amount; ?></strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            </div>
            
            <div class="panel-footer">
                <a href="javascript:;" class="btn btn-success updatebtn" onClick="xwb.updateItem()">Update</a>
            </div>
        </div>

    </div>
</div>

<!-- Switchery -->
<link href="<?php echo base_url('assets/vendors/switchery/dist/switchery.min.css'); ?>" rel="stylesheet">
<script src="<?php echo base_url('assets/vendors/switchery/dist/switchery.min.js'); ?>"></script>

<!-- iCheck -->
<link href="<?php echo base_url('assets/vendors/iCheck/skins/flat/green.css'); ?>" rel="stylesheet">
<script src="<?php echo base_url('assets/vendors/iCheck/icheck.min.js'); ?>"></script>

<!-- Javascript variable from php for this page here -->
<script src="<?php echo base_url('assets/vendor/igorescobar/jquery-mask-plugin/dist/jquery.mask.min.js'); ?>"></script>

<script type="text/javascript">
    xwb_var.varUpdateItems = '<?php echo base_url('canvasser/updateItems'); ?>';
    xwb_var.varSupplierSummary = '<?php echo base_url('canvasser/supplierSummary'); ?>';
    xwb_var.request_id = <?php echo $request_id; ?>;
    xwb_var.canvass_id = <?php echo $canvass->id; ?>;

<?php
$prodOptions = '<option value="">Select Product</option>';
if (count($products)>0) {
    foreach ($products as $key => $value) {
        $prodOptions .= '<option value="'.$value->id.'">'.$value->product_name.'</option>';
    }
}
?>

xwb_var.prodOptions = '<?php echo $prodOptions; ?>';


<?php
$suppOptions = '<option value="">Select Supplier</option>';
foreach ($suppliers as $key => $value) {
    $suppOptions .= '<option value="'.$value->id.'">'.$value->supplier_name.'</option>';
}
?>

xwb_var.supplierOpt = '<?php echo $suppOptions; ?>';

</script>
