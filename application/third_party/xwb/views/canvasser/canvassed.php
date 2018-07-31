<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="panel panel-success">
            <div class="panel-heading">
            <a href="<?php echo base_url('canvasser/update_items/'.$canvass->id);?>" class="btn btn-warning">Back</a>
              <h3 class="panel-title"><?php echo $page_title; ?></h3>
            </div>
            <div class="panel-body">
                <form name="form_canvassed_prices" id="form_canvassed_prices" accept-charset="utf-8">
                
                <div class="table-responsive">
                    <table class="table table_canvassed">
                        <thead>
                            <tr>
                                <th>Select</th>
                                          <th>Supplier</th>
                                <th>Product Description</th>
                                          <th>Quantity</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                                          <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                                
                        </tbody>
                        
                    </table>
                </div>
                
            </form>
            </div>
            
            <div class="panel-footer">
                <a href="javascript:;" class="btn btn-success updatebtn" onClick="xwb.addItem()">Add Item</a>
                  <a href="javascript:;" class="btn btn-info updatebtn" onClick="xwb.updateSelectedItem(<?php echo $canvass->id; ?>,<?php echo $item->id; ?>)">Update Selected Item</a>
            </div>
        </div>

    </div>
</div>


<script src="<?php echo base_url('assets/vendors/jQuery-Mask/dist/jquery.mask.min.js'); ?>"></script>

<!-- Javascript variable from php for this page here -->
<script type="text/javascript">
var varGetCanvassed = '<?php echo base_url('canvassed/getCanvassed'); ?>';
var varAddItem = '<?php echo base_url('canvassed/addItem'); ?>';
var varDeleteCanvassed = '<?php echo base_url('canvassed/deleteCanvassed'); ?>';
var varUpdateSelectedItem = '<?php echo base_url('canvassed/updateSelectedItem'); ?>';
var varEditCanvassedItem = '<?php echo base_url('canvassed/editCanvassedItem'); ?>';
var varUpdateCanvassPrice = '<?php echo base_url('canvassed/updateCanvassPrice'); ?>';

var item_id = <?php echo $item->id; ?>;
var canvasser_id  = <?php echo $canvass->id; ?>;

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
if (count($suppliers)>0) {
    foreach ($suppliers as $key => $value) {
        $suppOptions .= '<option value="'.$value->id.'">'.$value->supplier_name.'</option>';
    }
}
?>
var suppOptions = '<?php echo $suppOptions; ?>';
</script>
