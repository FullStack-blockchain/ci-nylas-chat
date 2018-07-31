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
                                                <span><?php echo $value->quantity; ?></span>
                                                </td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                        <?php
                                                            $supplier = (isset($supplier_1)?$supplier_1:null);
                                                            $po_item_id = (is_null($supplier)?'new1_'.$value->id:$supplier->id);

                                                            $supplier_id = (is_null($supplier)?null:$supplier->supplier_id);
                                                            $supplier_name = (is_null($supplier)?null:$supplier->supplier);
                                                            $unit_price = (is_null($supplier)?0:$supplier->unit_price);
                                                            $quantity = (is_null($supplier)?0:$supplier->quantity);
                                                            $row_total = $row_total + ($unit_price * $quantity);
                                                        ?>
                                                            <select name="supplier1[]" class="supplier supplier_<?php echo $value->id; ?>" style="width: 100%;">
                                                                <option value="">Select Supplier</option>
                                                                <?php

                                                                foreach ($suppliers as $key => $v) :
                                                                    ?>
                                                                    <option value="<?php echo $v->id; ?>"><?php echo $v->supplier_name; ?></option>

                                                                <?php  endforeach;
                                                                ?>
                                                            </select>
                                                            <input type="hidden" name="po_item_id[]" value="" />
                                                        </div>
                                                        <div class="col-md-2">
                                                            <span class="pull-left width-10p"><input type="checkbox" class="flat supplier-check" name="include_supplier1[]"></span>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="row">
                                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback canvasser-input-col-div">
                                                        <input type="text" class="unit_price form-control has-feedback-left canvasser-input" placeholder="Price" name="unit_price1[]" id="unit_price" value="" />
                                                        <span class="form-control-feedback left canvasser-label-icon" aria-hidden="true">Price</span>
                                                    </div>
                                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback canvasser-input-col-div">
                                                        <input type="text" class="qty form-control has-feedback-left canvasser-input" placeholder="Qty" name="qty1[]" id="" value="" />
                                                        <span class="form-control-feedback left canvasser-label-icon" aria-hidden="true">Qty</span>
                                                    </div>
                                                    <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback canvasser-input-col-div">
                                                        <input readonly type="text" class="supplier-total form-control has-feedback-left canvasser-input" placeholder="Total" name="total1[]" id="" value="" />
                                                        <span class="form-control-feedback left canvasser-label-icon" aria-hidden="true">Total</span>
                                                    </div>
                                                        
                                                    </div>

                                                    

                                                </td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                        
                                                            <select name="supplier2[]" class="supplier supplier_<?php echo $value->id; ?>" style="width: 100%;">
                                                                <option value="">Select Supplier</option>
                                                                <?php
                                                                foreach ($suppliers as $key => $v) :
                                                                    ?>
                                                                    <option value="<?php echo $v->id; ?>"><?php echo $v->supplier_name; ?></option>

                                                                <?php  endforeach; ?>
                                                            </select>
                                                            <input type="hidden" name="po_item_id[]" value="" />
                                                        </div>
                                                        <div class="col-md-2">
                                                            <span class="pull-left width-10p"><input type="checkbox" class="flat supplier-check" name="include_supplier2[]" ></span>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    
                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback canvasser-input-col-div">
                                                            <input type="text" class="unit_price form-control has-feedback-left canvasser-input" placeholder="Price" name="unit_price2[]" id="unit_price" value="" />
                                                            <span class="form-control-feedback left canvasser-label-icon" aria-hidden="true">Price</span>
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback canvasser-input-col-div">
                                                            <input type="text" class="qty form-control has-feedback-left canvasser-input" placeholder="Qty" name="qty2[]" id="" value="" />
                                                            <span class="form-control-feedback left canvasser-label-icon" aria-hidden="true">Qty</span>
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback canvasser-input-col-div">
                                                            <input readonly type="text" class="supplier-total form-control has-feedback-left canvasser-input" placeholder="Total" name="total2[]" id="" value="" />
                                                            <span class="form-control-feedback left canvasser-label-icon" aria-hidden="true">Total</span>
                                                        </div>
                                                    </div>

                                                    

                                                </td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            <select name="supplier3[]" class="supplier supplier_<?php echo $value->id; ?>" style="width: 100%;">
                                                                <option value="">Select Supplier</option>
                                                                <?php

                                                                foreach ($suppliers as $key => $v) :
                                                                    ?>
                                                                    <option value="<?php echo $v->id; ?>"><?php echo $v->supplier_name; ?></option>

                                                                <?php  endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <span class="pull-left width-10p"><input type="checkbox" class="flat supplier-check" name="include_supplier3[]" /></span>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback canvasser-input-col-div">
                                                            <input type="text" class="unit_price form-control has-feedback-left canvasser-input" placeholder="Price" name="unit_price3[]" id="unit_price" value="" />
                                                            <span class="form-control-feedback left canvasser-label-icon" aria-hidden="true">Price</span>
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback canvasser-input-col-div">
                                                            <input type="text" class="qty form-control has-feedback-left canvasser-input" placeholder="Qty" name="qty3[]" id="" value="" />
                                                            <span class="form-control-feedback left canvasser-label-icon" aria-hidden="true">Qty</span>
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback canvasser-input-col-div">
                                                            <input readonly type="text" class="supplier-total form-control has-feedback-left canvasser-input" placeholder="Total" name="total3[]" id="" value="" />
                                                            <span class="form-control-feedback left canvasser-label-icon" aria-hidden="true">Total</span>
                                                        </div>
                                                    </div>

                                                    

                                                </td>
                                                <td>
                                                    <div class="row">
                                                        <div class="col-md-10">
                                                            <select name="supplier4[]" class="supplier supplier_<?php echo $value->id; ?>" style="width: 100%;">
                                                                <option value="">Select Supplier</option>
                                                                <?php

                                                                foreach ($suppliers as $key => $v) :
                                                                    ?>
                                                                    <option value="<?php echo $v->id; ?>"><?php echo $v->supplier_name; ?></option>

                                                                <?php  endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <span class="pull-left width-10p"><input type="checkbox" class="flat supplier-check" name="include_supplier4[]" /></span>
                                                        </div>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="row">
                                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback canvasser-input-col-div">
                                                            <input type="text" class="unit_price form-control has-feedback-left canvasser-input" placeholder="Price" name="unit_price4[]" id="unit_price" value="" />
                                                            <span class="form-control-feedback left canvasser-label-icon" aria-hidden="true">Price</span>
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback canvasser-input-col-div">
                                                            <input type="text" class="qty form-control has-feedback-left canvasser-input" placeholder="Qty" name="qty4[]" id="" value="" />
                                                            <span class="form-control-feedback left canvasser-label-icon" aria-hidden="true">Qty</span>
                                                        </div>
                                                        <div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback canvasser-input-col-div">
                                                            <input readonly type="text" class="supplier-total form-control has-feedback-left canvasser-input" placeholder="Total" name="total4[]" id="" value="" />
                                                            <span class="form-control-feedback left canvasser-label-icon" aria-hidden="true">Total</span>
                                                        </div>
                                                    </div>

                                                    

                                                </td>
                                                <td>
                                                    <input readonly type="text" class="row-total form-control" value="" placeholder="Total" name="item_total" id="item_total" />
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
<script src="<?php echo base_url('assets/vendors/jQuery-Mask/dist/jquery.mask.min.js'); ?>"></script>

<script type="text/javascript">
    var varUpdateItems = '<?php echo base_url('canvasser/updateItems'); ?>';
    var request_id = <?php echo $request_id; ?>;
    var canvass_id = <?php echo $canvass->id; ?>;

<?php
$prodOptions = '<option value="">Select Product</option>';
if (count($products)>0) {
    foreach ($products as $key => $value) {
        $prodOptions .= '<option value="'.$value->id.'">'.$value->product_name.'</option>';
    }
}
?>

var prodOptions = '<?php echo $prodOptions; ?>';


<?php
$suppOptions = '<option value="">Select Supplier</option>';
foreach ($suppliers as $key => $value) {
    $suppOptions .= '<option value="'.$value->id.'">'.$value->supplier_name.'</option>';
}
?>

var supplierOpt = '<?php echo $suppOptions; ?>';

</script>
