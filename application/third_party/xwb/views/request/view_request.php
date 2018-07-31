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
                <div class="panel panel-info">
                    <div class="panel-heading">
                      <h3 class="panel-title">Item Status</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-6 col-xs-6 col-sm-6">
                                <table class="table">
                                    <thead>
                                        <tr><th>Current Status</th><th>Action</th></tr>
                                        
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><?php
                                            if ($request->status != 1) {
                                                $status = $this->xwb_purchasing->getStatus('request', $request->status);
                                            } else {
                                                if ($headDenied) {
                                                    $status = '<span class="label label-warning">Head/Recommending approval denied</span>';
                                                } else {
                                                    $status = $this->xwb_purchasing->getStatus('request', $request->status);
                                                }
                                            }
                                                
                                                echo $status;
                                            ;?></td>
                                            <td>
                                            <?php
                                            $statusItemsDenied = $this->statusItemsDenied;
                                            if (in_array($request->status, $statusItemsDenied) || $headDenied) :
                                                if ($headDenied) {
                                                    $viewbtn = 'xwb.viewItemsDenied('.$request->id.')';
                                                } else {
                                                    if ($user_id == $request->user_id) {
                                                        $viewbtn = 'xwb.view_response('.$request->id.')';
                                                    } else {
                                                        $viewbtn = 'xwb.view_res('.$request->id.')';
                                                    }
                                                }
                                                    
                                            ?>
                                                <a href="javascript:;" class="btn-info btn" onClick="<?php echo $viewbtn; ?>">View Reason and Respond</a>
                                            <?php else : ?>
                                                <span>No Action Required</span>
                                            <?php endif; ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <form name="form_update_items" id="form_update_items" accept-charset="utf-8">
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
                                          <input value="<?php echo $request->request_name; ?>" class="form-control" readonly placeholder="" type="text">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Purpose: </label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                          <textarea class="form-control" readonly placeholder=""><?php echo $request->purpose; ?></textarea>
                                        </div>
                                    </div>
                                    
                                </div>

                                <div class="col-md-4 col-xs-12 col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Priority: </label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <?php echo ($request->date_needed==null?"":date("F j, Y", strtotime($request->date_needed))); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr />

                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Product Name</th>
                                            <th>Product Description</th>
                                            <th>Quantity</th>
                                            <?php if ($current_user->group_name != 'members') : ?>
                                                <th>Supplier</th>
                                                <th>Unit Price</th>
                                                <th>Total</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($items as $key => $value) : ?>
                                            <tr>
                                                <td><?php echo $value->id; ?></td>
                                                <td><?php echo $value->product_name; ?></td>
                                                <td><?php echo $value->product_description; ?></td>
                                                <td><span class="qty_<?php echo $value->id; ?>"><?php echo $value->quantity; ?></span></td>
                                                <?php if ($current_user->group_name != 'members') : ?>
                                                    <td><input readonly type="text" value="<?php echo $value->supplier; ?>" name="supplier[<?php echo $value->id; ?>]" class="form-control supplier_<?php echo $value->id; ?>" /></td>
                                                    <td><input readonly type="text" value="<?php echo $value->unit_price; ?>" name="price[<?php echo $value->id; ?>]" class="form-control price_<?php echo $value->id; ?> item_price" /></td>
                                                    <td><input type="text" value="<?php echo ($value->unit_price * $value->quantity); ?>" readonly name="total[<?php echo $value->id; ?>]" class="form-control total_<?php echo $value->id; ?> total" /></td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                        <?php if ($current_user->group_name != 'members') : ?>
                                            <tr>
                                                <td colspan="6" align="right"><strong>Total: </strong></td>
                                                <td><strong class="net_total"><?php echo $request->total_amount; ?></strong</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            </div>
            
            <div class="panel-footer">
                <a href="javascript:window.history.go(-1);" class="btn btn-success">Back</a>

                <?php
                $req_status = array(1,19,17);
                if ($request->user_id == $user_id && (in_array($request->status, $req_status))) : ?>
                    <a href="<?php echo base_url('request/edit_request/'.$request_id); ?>" class="btn btn-warning">Edit Request</a>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>

<!-- Javascript variable from php for this page here -->
<script src="<?php echo base_url('assets/vendor/igorescobar/jquery-mask-plugin/dist/jquery.mask.min.js'); ?>"></script>

<script type="text/javascript">
    var xwb_var = {};
    xwb_var.varGetDeniedReason = '<?php echo base_url('request/getDeniedReason'); ?>';
    xwb_var.varRespond = '<?php echo base_url('request/respond'); ?>';
    xwb_var.varGetDeniedItems = '<?php echo base_url('request/getDeniedItems'); ?>';
    xwb_var.varRespondToHead = '<?php echo base_url('request/respondToHead'); ?>';

</script>
