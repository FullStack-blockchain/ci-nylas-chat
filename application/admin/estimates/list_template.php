<div class="col-md-12">
    <?php $this->load->view('admin/estimates/estimates_top_stats'); ?>

    <div class="row">
        <div class="col-md-12" id="small-table">
            <div class="panel-body card">
                <!-- if estimateid found in url -->
                <?php echo form_hidden('estimateid', $estimateid); ?>
                <?php $this->load->view('admin/estimates/table_html'); ?>
            </div>
        </div>
        <div class="col-md-7 small-table-right-col">
            <div id="estimate" class="hide card">
            </div>
        </div>
    </div>
</div>
