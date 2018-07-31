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
                          <a href="javascript:;" onClick="xwb.addItem();" class="btn btn-xs btn-info">Add Item</a></h3>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table_products">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Product Name</th>
                                            <th>Product Description</th>
                                            <th>Quantity</th>
                                            <th>Supplier</th>
                                            <th>Unit Price</th>
                                            <th>Total</th>
                                            <th>Canvassed</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $counter = 1;
                                        foreach ($items as $key => $value) : ?>
                                            <tr>
                                                <td><?php echo $value->id; ?></td>
                                                <td><?php echo $value->product_name; ?></td>
                                                <td><?php echo $value->product_description; ?></td>
                                                <td>
                                                <input type="text" value="<?php echo $value->quantity; ?>" name="quantity[<?php echo $value->id; ?>]" class="form-control quantity_<?php echo $value->id; ?> quantity" />
                                                </td>
                                                <td>
                                                    <select name="supplier[<?php echo $value->id; ?>]" class="supplier supplier_<?php echo $value->id; ?>" style="width: 100%;">
                                                        <option value="">Select Supplier</option>
                                                        <?php

                                                        foreach ($suppliers as $key => $v) :
                                                            ?>
                                                            <option value="<?php echo $v->id; ?>" <?php echo ($value->supplier_id == $v->id?"selected":""); ?>><?php echo $v->supplier_name; ?></option>

                                                        <?php  endforeach;
                                                        if ($value->supplier_id == 0) :
                                                        ?>
                                                        <option value="<?php echo $value->supplier; ?>" selected><?php echo $value->supplier; ?></option>
                                                        <?php endif; ?>
                                                    </select>
                                                    <!-- <input type="text" value="<?php echo $value->supplier; ?>" name="supplier[<?php echo $value->id; ?>]" class="form-control supplier_<?php echo $value->id; ?>" /> -->
                                                </td>
                                                <td><input type="text" value="<?php echo $value->unit_price; ?>" name="price[<?php echo $value->id; ?>]" class="form-control price_<?php echo $value->id; ?> item_price" /></td>
                                                <td><input type="text" value="<?php echo ($value->unit_price * $value->quantity); ?>" readonly name="total[<?php echo $value->id; ?>]" class="form-control total_<?php echo $value->id; ?> total" /></td>
                                                <td><a href="<?php echo base_url('canvasser/canvassed/'.$canvass->id.'/'.$value->id);?>" class="btn btn-xs btn-info">Canvassed</a></td>
                                            </tr>
                                        <?php
                                        $counter++;
                                        endforeach; ?>
                                            
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="6" align="right"><strong>Total: </strong></td>
                                            <td><strong class="net_total"><?php echo $canvass->total_amount; ?></strong></td>
                                            <td></td>
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
