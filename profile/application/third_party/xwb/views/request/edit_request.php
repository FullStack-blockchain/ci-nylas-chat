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
                

                <form name="form_update_request" id="form_update_request" accept-charset="utf-8">
                    <div class="row">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                              <h3 class="panel-title">Request Details</h3>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-4 col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Type of Request: </label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                              <input value="<?php echo $request->request_name; ?>" class="form-control" name="request_name" id="request_name" type="text">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Purpose: </label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                              <textarea class="form-control" placeholder="" name="purpose" id="purpose"><?php echo $request->purpose; ?></textarea>
                                            </div>
                                        </div>
                                        
                                    </div>
                                    <div class="col-md-4 col-xs-12 col-sm-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Date Needed: </label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <input type="text" id="date_needed" name="date_needed" value="<?php echo $request->date_needed; ?>" required="required" class="form-control col-md-7 col-xs-12">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                                <a href="javascript:;" class="btn btn-xs btn-info xwb-add-item">Add Item</a></h3>

                                <div class="table-responsive">
                                    <table class="table table_products">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Product Name</th>
                                                <th>Product Description</th>
                                                <th>Quantity</th>
                                                <?php if ($current_user->group_name != 'members') : ?>
                                                    <th>Supplier</th>
                                                <?php endif; ?>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($items as $key => $value) : ?>
                                                <tr>
                                                    <td><a class="btn btn-danger btn-xs xwb-delete-item" data-item="<?php echo $value->id; ?>" href="javascript:;">
                                                            <i class="fa fa-times"></i>
                                                        </a><?php echo $value->id; ?>
                                                       </td>
                                                    <td>

                                                        <select name="product[<?php echo $value->id; ?>]" class="product product_<?php echo $value->id; ?>" style="width: 100%;">
                                                            <option value="">Select Supplier</option>
                                                        <?php foreach ($products as $pK => $pV) : ?>
                                                            <option value="<?php echo $pV->id; ?>" <?php echo ($value->product_id==$pV->id?"selected":""); ?>><?php echo $pV->product_name; ?></option>
                                                        <?php endforeach; ?>
                                                        <?php if ($value->product_id == 0) : ?>
                                                            <option value="<?php echo $value->product_name; ?>" selected><?php echo $value->product_name; ?></option>
                                                        <?php endif; ?>
                                                        </select>
                                                        <input type="hidden" name="product_name[<?php echo $value->id; ?>]" class="product_name_<?php echo $value->id; ?>" value="<?php echo $value->product_name; ?>" />
                                                    </td>
                                                    <td>
                                                        <textarea class="form-control product_description_<?php echo $value->id; ?>" placeholder="" name="product_description[<?php echo $value->id; ?>]"><?php echo $value->product_description; ?></textarea>

                                                    </td>
                                                    <td>
                                                        <input value="<?php echo $value->quantity; ?>" class="form-control quantity_<?php echo $value->id; ?>" name="quantity[<?php echo $value->id; ?>]" type="text">
                                                    </td>
                                                    <?php if ($current_user->group_name != 'members') : ?>
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
                                                            <input type="hidden" name="supplier_name[<?php echo $value->id; ?>]" class="supplier_name_<?php echo $value->id; ?>" value="<?php echo $value->supplier; ?>" />
                                                        </td>
                                                    <?php endif; ?>
                                                </tr>
                                            <?php endforeach; ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
            <div class="panel-footer">
                <a href="javascript:;" class="btn btn-success updatebtn xwb-update-request" data-request="<?php echo $request_id; ?>">Update</a>
            </div>
        </div>

    </div>
</div>

<!-- Javascript variable from php for this page here -->
<script src="<?php echo base_url('assets/vendor/igorescobar/jquery-mask-plugin/dist/jquery.mask.min.js'); ?>"></script>

<script type="text/javascript">

    xwb_var.varUpdateItemRequest = '<?php echo base_url('request/updateItemRequest'); ?>';
    xwb_var.varRemoveItem = '<?php echo base_url('request/removeItem'); ?>';

<?php
$prodOptions = '<option value="">Select Product</option>';
foreach ($products as $key => $value) {
    $prodOptions .= '<option value="'.$value->id.'">'.$value->product_name.'</option>';
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

xwb_var.view_req_link  = '<?php echo base_url("request/view_request"); ?>';
</script>
