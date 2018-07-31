<div class="col-md-12">
    <div class="panel_s mbot10">
        <div class="_buttons">
            <?php $this->load->view('admin/invoices/invoices_top_stats'); ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12" id="small-table">
            <div class="panel-body card">
                <!-- if invoiceid found in url -->
                <?php echo form_hidden('invoiceid', $invoiceid); ?>
                <?php $this->load->view('admin/invoices/table_html'); ?>
            </div>
        </div>
        <div class="col-md-7 small-table-right-col">
            <div id="invoice" class="hide">
            </div>
        </div>
    </div>
</div>
