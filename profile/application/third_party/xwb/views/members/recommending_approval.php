<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">

        <div class="panel panel-success">
            <div class="panel-heading">
              <a href="<?php echo base_url('member/for_approval'); ?>" class="btn btn-warning">Back</a><h3 class="panel-title"><?php echo $page_title; ?></h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Select Head Users</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <select name="head_users" id="head_users" style="width: 100%;">
                        <?php foreach ($head_users as $key => $value) : ?>
                            <option value="<?php echo $value; ?>"> <?php echo ucwords($value->first_name." ".$value->last_name." (".$value->description.")"); ?> </option>
                        <?php endforeach; ?>
                      </select>
                    </div>
                  </div>
            </div>
            <div class="panel-footer">

            </div>
        </div>

    </div>
</div>

<!-- Javascript variable from php for this page here -->
<script type="text/javascript">
$(document).ready(function(){
    $("#head_users").select2({});
});
</script>