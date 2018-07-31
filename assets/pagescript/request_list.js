/////////////////////////////////////////
// XWB Purchasing                      //
// Author - Jay-r Simpron              //
// Copyright (c) 2017, Jay-r Simpron   //
//                                     //
// Request list page scripts goes here //
/////////////////////////////////////////

var xwb = (function(library) {
    "use strict";

    var _args = window.xwb_var;
    var xwb = window.xwb;
    var bootbox = window.bootbox;
    var $ = window.jQuery;

    var table_req_items;
    var total_per_supplier;
    var table_done_items;
    var table_attachment;

    var func = {

        /**
        * View Items per request
        * @param int request_id
        *
        * @return mixed
        */

        viewItems : function (request_id){
            var expenditure = "";
            var qty_price = "";
            var supplier = "";
            if(_args.group_name == 'budget'){
                expenditure = '<th>Expenditure</th>';
                supplier = '<th>Supplier</th>';
            }
            
            if(_args.group_name == 'admin'){
                qty_price = '<th>Unit Price</th><th>Total Price</th>';
                supplier = '<th>Supplier</th>';
            }


            bootbox.dialog({
                title: 'Request Items',
                message: '<div class="row">'+
                            '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                '<div class="table-responsive">'+
                                    '<table class="table table_req_items">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>Item</th>'+
                                                '<th>Category</th>'+
                                                '<th>Description</th>'+
                                                supplier+
                                                '<th>Qty</th>'+
                                                expenditure+
                                                qty_price+
                                                '<th>Attachment</th>'+
                                                '<th>ETA</th>'+
                                                '<th>Date Delivered</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody>'+
                                        '</tbody>'+
                                    '<table>'+
                                '</div>'+
                            '</div>'+
                        '</div>',
                className: 'width-90p',
                buttons:{
                    cancel: {
                        label: "Close",
                        className: "btn-warning",
                        callback: function () {

                        }
                    }
                }

            });

            table_req_items = $('.table_req_items').DataTable({
                "ajax": {
                    "url": _args.varGetRequestItems,
                    "data": function ( d ) {
                        d.request_id = request_id;
                    }
                  },
                  "order": [[ 0, "desc" ]]
            });
        },

        /**
         * View total amount per supplier
         * 
         * @param  {Number} canvass_id [Request ID]
         * @return null
         */
        supplierSummary : function (request_id){
            bootbox.dialog({
                title: 'Total Per Supplier',
                message: '<div class="row">'+
                            '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                '<div class="table-responsive">'+
                                    '<table class="table table-total-per-supplier">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>Supplier Name</th>'+
                                                '<th>Total</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody>'+
                                        '</tbody>'+
                                        '<tfoot>'+
                                        '</tfoot>'+
                                    '<table>'+
                                '</div>'+
                            '</div>'+
                        '</div>',
                buttons:{
                    cancel: {
                        label: "Close",
                        className: "btn-warning",
                        callback: function () {

                        }
                    }
                }

            });

            setTimeout(function(){
                total_per_supplier = $('.table-total-per-supplier').DataTable({
                    "ajax": {
                        "url": _args.varSupplierSummary,
                        "data": function ( d ) {
                            d.request_id = request_id;
                        },
                        "dataSrc": function (json) {
                        $(".table-total-per-supplier tfoot").html(json.footer);
                        return json.data;
                        },
                    },
                    "order": [[ 0, "desc" ]],
                    
                });        
            },100);

        },

        /**
        * Return to Requisitioner
        *
        * @param int request_id
        *
        * @return mixed
        */
        toRequisitioner: function (request_id){
            bootbox.dialog({
                title: 'Return Request',
                message: '<div class="row">'+
                            '<form class="form-horizontal">'+
                                '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                    '<div class="form-group">'+
                                        '<label class="col-md-4 control-label" for="message">Message</label>'+
                                        '<div class="col-md-6">'+
                                            '<textarea name="message" id="message" class="form-control"></textarea>'+
                                        '</div> '+
                                    '</div> '+
                                '</div>'+
                            '</form>'+
                        '</div>',
                buttons:{
                    return: {
                        label: "Return",
                        className: "btn-danger",
                        callback: function () {
                            $.ajax({
                                url: _args.varReturnRequest,
                                type: "post",
                                data: {
                                    request_id:request_id,
                                    message:$("#message").val(),
                                },
                                success: function(data){
                                    data = $.parseJSON(data);
                                    if(data.status == true){
                                        xwb.Noty(data.message,'success');
                                        xwb_var.table_request_list.ajax.reload();
                                    }else{
                                        xwb.Noty(data.message,'error');
                                    }
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    console.log(XMLHttpRequest);
                                    console.log(textStatus);
                                    console.log(errorThrown);
                                }
                            });
                        }
                    },
                    cancel: {
                        label: "Close",
                        className: "btn-warning",
                        callback: function () {

                        }
                    },
                }

            });
        },


        /**
        * Assign to canvasser request
        * @param int request_id
        *
        * @return mixed
        */
        toCanvass: function (request_id){
            bootbox.dialog({
                title: 'Assign to Canvasser',
                message: '<div class="row">'+
                            '<form class="form-horizontal">'+
                            '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                '<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="req_cat">Request Category</label>'+
                                    '<div class="col-md-6">'+
                                        '<select name="req_cat" id="req_cat" style="width:100%;">'+
                                        _args.req_cat_opt+
                                        '</select>'+
                                    '</div> '+
                                '</div> '+
                                '<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="canvasser">Canvasser</label>'+
                                    '<div class="col-md-6">'+
                                        '<select name="canvasser" id="canvasser" style="width:100%;">'+
                                        _args.canvasserOptions+
                                        '</select>'+
                                    '</div> '+
                                '</div> '+
                            '</div>'+
                            '</form>'+
                        '</div>',
                buttons:{
                    assign: {
                        label: "Assign",
                        className: "btn-success",
                        callback: function () {
                            $.ajax({
                                url: _args.varAssignCanvasser,
                                type: "post",
                                data: {
                                    request_id:request_id,
                                    user_id:$("#canvasser").val(),
                                    req_cat:$("#req_cat").val()

                                },
                                success: function(data){
                                    data = $.parseJSON(data);
                                    if(data.status == true){
                                        xwb.Noty(data.message,'success');
                                        xwb_var.table_request_list.ajax.reload();
                                        bootbox.hideAll();
                                    }else{
                                        xwb.Noty(data.message,'error');
                                        return false;
                                    }
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    console.log(XMLHttpRequest);
                                    console.log(textStatus);
                                    console.log(errorThrown);
                                }

                            });
                            return false;
                        }
                    },
                    cancel: {
                        label: "Close",
                        className: "btn-warning",
                        callback: function () {

                        }
                    },
                }

            }).init(function(){
                $("#canvasser").select2({});
                $("#req_cat").select2({});
                $('.bootbox.modal').removeAttr('tabindex');
            });

            
            
        },


       /**
        * Approve request
        * @param int request_id
        *
        * @return mixed
        */
        approve: function (request_id){
            bootbox.dialog({
                title: 'Approve and Assign to canvasser',
                message: '<div class="row">'+
                            '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                '<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="canvasser">Canvasser</label>'+
                                    '<div class="col-md-6">'+
                                        '<select name="canvasser" id="canvasser" style="width:100%;">'+
                                        _args.canvasserOptions+
                                        '</select>'+
                                    '</div> '+
                                '</div> '+
                            '</div>'+
                        '</div>',
                buttons:{
                    assign: {
                        label: "Assign",
                        className: "btn-success",
                        callback: function () {
                            $.ajax({
                                url: _args.varAssignCanvasser,
                                type: "post",
                                data: {
                                    request_id:request_id,
                                    user_id:$("#canvasser").val()
                                },
                                success: function(data){
                                    data = $.parseJSON(data);
                                    if(data.status == true){
                                        xwb.Noty(data.message,'success');
                                        xwb_var.table_request_list.ajax.reload();
                                        bootbox.hideAll();
                                    }else{
                                        xwb.Noty(data.message,'error');
                                    }
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    console.log(XMLHttpRequest);
                                    console.log(textStatus);
                                    console.log(errorThrown);
                                }
                            });
                        }
                    },
                    cancel: {
                        label: "Close",
                        className: "btn-warning",
                        callback: function () {

                        }
                    },
                }

            });

            $("#canvasser").select2({});
        },



        /**
        * Deny request
        * @param int request_id
        *
        * @return mixed
        */
        deny: function (request_id){
            bootbox.dialog({
                title: 'Deny request',
                message: '<div class="row">'+
                            '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                '<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="reason">Reason</label>'+
                                    '<div class="col-md-6">'+
                                        '<textarea name="reason" id="reason" class="form-control"></textarea>'+
                                    '</div> '+
                                '</div> '+
                            '</div>'+
                        '</div>',
                buttons:{
                    deny: {
                        label: "Deny",
                        className: "btn-success",
                        callback: function () {
                            $.ajax({
                                url: _args.varDenyRequest,
                                type: "post",
                                data: {
                                    request_id:request_id,
                                    reason:$("#reason").val()
                                },
                                success: function(data){
                                    data = $.parseJSON(data);
                                    if(data.status == true){
                                        xwb.Noty(data.message,'success');
                                        xwb_var.table_request_list.ajax.reload();
                                        bootbox.hideAll();
                                    }else{
                                        xwb.Noty(data.message,'error');
                                    }
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    console.log(XMLHttpRequest);
                                    console.log(textStatus);
                                    console.log(errorThrown);
                                }
                            });
                        }
                    },
                    cancel: {
                        label: "Close",
                        className: "btn-warning",
                        callback: function () {

                        }
                    },
                }

            });

        },


        /**
         * Mark request as Done
         * @param  {[Number]} request_id [Request ID]
         */
        markDone: function (request_id){
            bootbox.dialog({
                title: 'These items will be marked as done',
                message: '<div class="row">'+
                            '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                '<div class="row">'+
                                    '<div class="table-responsive">'+
                                        '<table class="table table_done_items">'+
                                            '<thead>'+
                                                '<tr>'+
                                                    '<th>Item</th>'+
                                                    '<th>PO</th>'+
                                                    '<th>ETD</th>'+
                                                    '<th>Description</th>'+
                                                    '<th>Qty</th>'+
                                                '</tr>'+
                                            '</thead>'+
                                            '<tbody>'+
                                        '<table>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>',
                className: 'width-90p',
                buttons:{
                    done:{
                        label: "Done",
                        className: "btn-info",
                        callback: function(){
                            $.ajax({
                                url: _args.varMarkDone,
                                type: "post",
                                data: {
                                    request_id:request_id,
                                    eta: $("#eta").val()
                                },
                                success: function(data){
                                    data = $.parseJSON(data);
                                    if(data.status == true){
                                        new Noty({
                                            text: data.message,
                                            layout: 'topCenter',
                                            type: 'success',
                                            timeout: 5000,
                                            callbacks: {
                                                onClose: function() {
                                                    xwb_var.table_request_list.ajax.reload();
                                                    bootbox.hideAll();
                                                }
                                            }
                                        }).show();
                                       
                                    }else{
                                        xwb.Noty(data.message,'error');
                                        return false;
                                    }
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    console.log(XMLHttpRequest);
                                    console.log(textStatus);
                                    console.log(errorThrown);
                                }
                            });
                            return false;
                        }
                    },
                    cancel: {
                        label: "Close",
                        className: "btn-warning",
                        callback: function () {

                           

                        }
                    }
                }

            });

            /**
            *generate item list done datatable
            *
            *
            */
            setTimeout(function(){
                table_done_items = $('.table_done_items').DataTable({
                    "order": [[ 0, "desc" ]], //Initial no order.
                    "ajax": {
                        "url": _args.varGetItemDone,
                        "data": function ( d ) {
                            d.request_id = request_id;
                        }
                      },
                });

            },100);


            $("#eta").datepicker({
                minDate:0,
            });

        },

        /**
        * View Attachment
        * @param int po_id
        *
        * @return mixed
        */
        viewAttachmentPreview: function (po_id){

            bootbox.dialog({
                title: "Attachment",
                message: '<div class="row">'+
                    '<div class="col-md-12 col-sm-12 col-xs-12">'+
                        '<div class="panel panel-success">'+
                            '<div class="panel-heading">'+
                              '<h3 class="panel-title">Attachment</h3>'+
                            '</div>'+
                            '<div class="panel-body">'+
                                '<form class="form-horizontal" name="form_add_attachment" id="form_add_attachment">'+
                                    '<input type="hidden" name="po_id" id="po_id" value="'+po_id+'">'+
                                    '<div class="form-group">'+
                                        '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                            '<div class="input-group">'+
                                                '<input id="attachment" name="attachment" type="file">'+
                                            '</div>'+
                                        '</div> '+
                                        '<hr />'+
                                        '<span class="input-group-btn">'+
                                            '<button type="button" onClick="xwb.submitAttachment()" class="btn btn-primary">Upload</button>'+
                                        '</span>'+
                                    '</div> '+
                                '</form>'+
                                '<hr />'+
                                '<div class="table-responsive">'+
                                    '<table class="table table_attachment">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>#</th>'+
                                                '<th>Filename</th>'+
                                                '<th>Action</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody>'+
                                        '</tbody>'+
                                    '</table>'+
                            '</div>'+

                        '</div>'+
                    '</div></div>',
                buttons: {
                    close: {
                        label: "Close",
                        className: "btn-warning",
                        callback: function () {

                        }
                    }
                }
            }); 

            /**
            *generate attachment datatable
            *
            *
            */
            table_attachment = $('.table_attachment').DataTable({
                "ajax": {
                    "url": _args.varGetAttachment,
                    "data": function ( d ) {
                        d.po_id = po_id;
                    }
                  },
                  "order": [[ 0, "desc" ]]
            });

        },


        /**
        * Upload attachment
        *
        *
        * @return void
        */
        submitAttachment: function (){

            $("#form_add_attachment").ajaxForm({
                url: _args.varAddAttachment,
                type: "post",
                beforeSubmit: function(arr, $form, options){
                    var csrf = xwb.getCSRF();
                    arr = $.merge(arr,csrf);
                },
                success: function(data){
                    data = $.parseJSON(data);
                    xwb.setCSRF(data.csrf_hash);
                    if(data.status == true){
                        xwb.Noty(data.message,'success');
                        table_attachment.ajax.reload();
                        $("#attachment").val('');
                    }else{
                        xwb.Noty(data.message,'error');
                    }
                },
            }).submit();
        },

        /**
        * Remove temporary file attachment
        *
        * @param int attachment_id
        *
        * @return void
        */
        deleteReqAttachment: function (attachment_id){
            bootbox.confirm("Are you sure you want to delete this attachment?", function(result){ 
                if(result){
                    $.ajax({
                        url: _args.varRemoveAttachment,
                        type: "post",
                        data: {
                            attachment_id:attachment_id,
                        },
                        success: function(data){
                            data = $.parseJSON(data);
                            if(data.status == true){
                                xwb.Noty(data.message,'success'); 
                                table_attachment.ajax.reload();
                            }else{
                                xwb.Noty(data.message,'error'); 
                            }
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            console.log(XMLHttpRequest);
                            console.log(textStatus);
                            console.log(errorThrown);
                        }
                    });
                }
            });
        },

        /**
        * Set Expenditure per request
        * @param int request_id
        *
        * @return mixed
        */
        setExpenditure: function (request_id){

            bootbox.dialog({
                title: "Set Expenditure",
                message: '<div class="row">'+
                    '<div class="col-md-12 col-sm-12 col-xs-12">'+
                    '<form class="form-horizontal" name="form_set_expenditure" id="form_set_expenditure">'+
                        '<div class="form-group">'+
                            '<label class="col-md-4 control-label" for="expenditure">Expenditure</label>'+
                            '<input type="hidden" name="request_id" id="request_id" value="'+request_id+'">'+
                            '<div class="col-md-6">'+
                                '<select name="expenditure" id="expenditure" class="form-control">'+
                                    '<option value="CAPEX">Capital Expenditure</option>'+
                                    '<option value="OPEX">Operating Expenditure</option>'+
                                '</select>'+
                            '</div> '+
                        '</div> '+
                    '</form> </div>  </div>',
                buttons: {
                    success: {
                        label: "Save",
                        className: "btn-success",
                        callback: function () {
                            var frm_data  = $("#form_set_expenditure").serializeArray();

                            $.ajax({
                                url: _args.varSetExpenditure,
                                type: "post",
                                data: frm_data,
                                success: function(data){
                                    data = $.parseJSON(data);
                                    if(data.status == true){
                                        xwb.Noty(data.message,'success'); 
                                        xwb_var.table_request_list.ajax.reload();
                                        bootbox.hideAll();
                                    }else{
                                        xwb.Noty(data.message,'error'); 
                                        return false;
                                    }
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    console.log(XMLHttpRequest);
                                    console.log(textStatus);
                                    console.log(errorThrown);
                                }
                            });
                            return false;
                        }
                    },
                    cancel: {
                        label: "Cancel",
                        className: "btn-warning",
                        callback: function () {

                        }
                    }
                }
            }); 
        },



        returnToCanvass: function (request_id){
             bootbox.dialog({
                title: 'Return to Canvasser',
                message: '<div class="row">'+
                            '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                '<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="message">Message</label>'+
                                    '<div class="col-md-6">'+
                                        '<textarea name="message" id="message" class="form-control"></textarea>'+
                                    '</div> '+
                                '</div> '+
                            '</div>'+
                        '</div>',
                buttons:{
                    done: {
                        label: "Done",
                        className: "btn-success",
                        callback: function () {
                            $.ajax({
                                url: _args.varReturnCanvass,
                                type: "post",
                                data: {
                                    request_id:request_id,
                                    message:$("#message").val()
                                },
                                success: function(data){
                                    data = $.parseJSON(data);
                                    if(data.status == true){
                                        xwb.Noty(data.message,'success'); 
                                        xwb_var.table_request_list.ajax.reload();
                                        bootbox.hideAll();
                                    }else{
                                        xwb.Noty(data.message,'error'); 
                                        
                                    }
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    console.log(XMLHttpRequest);
                                    console.log(textStatus);
                                    console.log(errorThrown);
                                }
                            });
                        }
                    },
                    cancel: {
                        label: "Close",
                        className: "btn-warning",
                        callback: function () {

                        }
                    },
                }

            });
        },



        /**
        * Forward to budget department
        *
        * @param int request_id
        *
        * @return mixed
        */
        assignToBudget: function (request_id){
            bootbox.dialog({
                title: 'Forward to Budget Department',
                message: '<div class="row">'+
                            '<form class="form-horizontal">'+
                                '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                    '<div class="form-group">'+
                                        '<label class="col-md-4 control-label" for="message">Remarks/Message</label>'+
                                        '<div class="col-md-6">'+
                                            '<textarea name="message" id="message" class="form-control"></textarea>'+
                                        '</div> '+
                                    '</div> '+
                                    '<div class="form-group">'+
                                        '<label class="col-md-4 control-label" for="bdusers">Budget Department</label>'+
                                        '<div class="col-md-6">'+
                                            '<select name="bdusers" id="bdusers" style="width:100%;">'+
                                            _args.BDUsersOptions+
                                            '</select>'+
                                        '</div> '+
                                    '</div> '+
                                '</div>'+
                            '</form>'+
                        '</div>',
                buttons:{
                    assign: {
                        label: "Assign",
                        className: "btn-success",
                        callback: function () {
                            $.ajax({
                                url: _args.varAssignBudget,
                                type: "post",
                                data: {
                                    request_id:request_id,
                                    message:$("#message").val(),
                                    user_id:$("#bdusers").val()
                                },
                                success: function(data){
                                    data = $.parseJSON(data);
                                    if(data.status == true){
                                        xwb.Noty(data.message,'success'); 
                                        xwb_var.table_request_list.ajax.reload();
                                        bootbox.hideAll();
                                    }else{
                                        xwb.Noty(data.message,'error'); 
                                        
                                    }
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    console.log(XMLHttpRequest);
                                    console.log(textStatus);
                                    console.log(errorThrown);
                                }
                            });
                        }
                    },
                    cancel: {
                        label: "Close",
                        className: "btn-warning",
                        callback: function () {

                        }
                    },
                }

            });

            $("#bdusers").select2({});
        },



        /**
        * View reson and response
        *
        * @param int request_id
        *
        * @return mixed
        */
        view_response: function (request_id){
            $.ajax({
                url: _args.varGetResponse,
                type: "post",
                data: {
                    request_id:request_id,
                },
                success: function(data){
                    data = $.parseJSON(data);
                    bootbox.dialog({
                        title: 'Reason and Response window',
                        message: '<div class="row">'+
                                    '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                        '<form class="form-horizontal">'+
                                        '<div class="form-group">'+
                                            '<label class="col-md-4 control-label" for="reason">Reason:</label>'+
                                            '<div class="col-md-6">'+
                                                '<textarea name="reason" readonly id="reason" class="form-control">'+data.reason+'</textarea>'+
                                            '</div> '+
                                        '</div> '+
                                        '<hr class="clearfix" />'+
                                        '<div class="form-group">'+
                                            '<label class="col-md-4 control-label" for="response">Your Response</label>'+
                                            '<div class="col-md-6">'+
                                                '<textarea name="response" id="response" class="form-control"></textarea>'+
                                            '</div> '+
                                        '</div> '+
                                        '</form> '+
                                    '</div>'+
                                '</div>',
                        buttons:{
                            assign: {
                                label: "Respond",
                                className: "btn-success",
                                callback: function () {
                                    $.ajax({
                                        url: _args.varReturnCanvass,
                                        type: "post",
                                        data: {
                                            request_id:request_id,
                                            message:$("#response").val()
                                        },
                                        success: function(data){
                                            data = $.parseJSON(data);
                                            if(data.status == true){
                                                xwb.Noty(data.message,'success'); 
                                                xwb_var.table_request_list.ajax.reload();
                                                
                                            }else{
                                                xwb.Noty(data.message,'error'); 
                                                return false;
                                            }
                                        },
                                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                                            console.log(XMLHttpRequest);
                                            console.log(textStatus);
                                            console.log(errorThrown);
                                        }
                                    });
                                }
                            },
                            cancel: {
                                label: "Close",
                                className: "btn-warning",
                                callback: function () {

                                }
                            },
                        }

                    });

                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        },


        /**
        * View message and response
        *
        * @param int request_id
        *
        * @return mixed
        */
        view_budget_msg: function (request_id){
            $.ajax({
                url: _args.varGetBudgetMsg,
                type: "post",
                data: {
                    request_id:request_id,
                },
                success: function(data){
                    data = $.parseJSON(data);
                    bootbox.dialog({
                        title: 'Reason and Response window',
                        message: '<div class="row">'+
                                    '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                        '<form class="form-horizontal">'+
                                        '<div class="form-group">'+
                                            '<label class="col-md-4 control-label" for="reason">Reason:</label>'+
                                            '<div class="col-md-6">'+
                                                '<textarea name="reason" readonly id="reason" class="form-control">'+data.reason+'</textarea>'+
                                            '</div> '+
                                        '</div> '+
                                        '<hr class="clearfix" />'+
                                        '<div class="form-group">'+
                                            '<label class="col-md-4 control-label" for="response">Your Response</label>'+
                                            '<div class="col-md-6">'+
                                                '<textarea name="response" id="response" class="form-control"></textarea>'+
                                            '</div> '+
                                        '</div> '+
                                        '</form> '+
                                    '</div>'+
                                '</div>',
                        buttons:{
                            assign: {
                                label: "Respond",
                                className: "btn-success",
                                callback: function () {
                                    $.ajax({
                                        url: _args.varReturnBudget,
                                        type: "post",
                                        data: {
                                            request_id:request_id,
                                            message:$("#response").val()
                                        },
                                        success: function(data){
                                            data = $.parseJSON(data);
                                            if(data.status == true){
                                                xwb.Noty(data.message,'success'); 
                                                xwb_var.table_request_list.ajax.reload();
                                            }else{
                                                xwb.Noty(data.message,'error'); 
                                                return false;
                                            }
                                        },
                                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                                            console.log(XMLHttpRequest);
                                            console.log(textStatus);
                                            console.log(errorThrown);
                                        }
                                    });
                                }
                            },
                            cancel: {
                                label: "Close",
                                className: "btn-warning",
                                callback: function () {

                                }
                            },
                        }

                    });

                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        },



        /**
        * View message and response
        *
        * @param int request_id
        *
        * @return mixed
        */
        view_req_msg: function(request_id){
            $.ajax({
                url: _args.varGetReqMsg,
                type: "post",
                data: {
                    request_id:request_id,
                },
                success: function(data){
                    data = $.parseJSON(data);
                    bootbox.dialog({
                        title: 'Reason and Response window',
                        message: '<div class="row">'+
                                    '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                        '<form class="form-horizontal">'+
                                        '<div class="form-group">'+
                                            '<label class="col-md-4 control-label" for="reason">Reason:</label>'+
                                            '<div class="col-md-6">'+
                                                '<textarea name="reason" readonly id="reason" class="form-control">'+data.reason+'</textarea>'+
                                            '</div> '+
                                        '</div> '+
                                        '<hr class="clearfix" />'+
                                        '<div class="form-group">'+
                                            '<label class="col-md-4 control-label" for="response">Your Response</label>'+
                                            '<div class="col-md-6">'+
                                                '<textarea name="response" id="response" class="form-control"></textarea>'+
                                            '</div> '+
                                        '</div> '+
                                        '</form> '+
                                    '</div>'+
                                '</div>',
                        buttons:{
                            assign: {
                                label: "Respond",
                                className: "btn-success",
                                callback: function () {
                                    $.ajax({
                                        url: _args.varReturnRequest,
                                        type: "post",
                                        data: {
                                            request_id:request_id,
                                            message:$("#response").val()
                                        },
                                        success: function(data){
                                            data = $.parseJSON(data);
                                            if(data.status == true){
                                                xwb.Noty(data.message,'success'); 
                                                xwb_var.table_request_list.ajax.reload();
                                            }else{
                                                xwb.Noty(data.message,'error'); 
                                                return false;
                                            }
                                        },
                                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                                            console.log(XMLHttpRequest);
                                            console.log(textStatus);
                                            console.log(errorThrown);
                                        }
                                    });
                                }
                            },
                            cancel: {
                                label: "Close",
                                className: "btn-warning",
                                callback: function () {

                                }
                            },
                        }

                    });

                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        },


        /**
        * Delete Item
        *
        * @param int request_id
        *
        * @return mixed
        */
        deleteItem: function (item_id){
                bootbox.dialog({
                title: 'Delete Item',
                message: '<div class="row">'+
                            '<form class="form-horizontal">'+
                                '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                    '<div class="form-group">'+
                                        '<label class="col-md-4 control-label" for="message">Message</label>'+
                                        '<div class="col-md-6">'+
                                            '<textarea name="message" id="message" class="form-control"></textarea>'+
                                        '</div> '+
                                    '</div> '+
                                '</div>'+
                            '</form>'+
                        '</div>',
                buttons:{
                    assign: {
                        label: "Delete",
                        className: "btn-danger",
                        callback: function () {
                            $.ajax({
                                url: _args.varRemoveItem,
                                type: "post",
                                data: {
                                    item_id:item_id,
                                    message:$("#message").val(),
                                },
                                success: function(data){
                                    data = $.parseJSON(data);
                                    if(data.status == true){
                                        xwb.Noty(data.message,'success'); 
                                        xwb_var.table_request_list.ajax.reload();
                                    }else{
                                        xwb.Noty(data.message,'error'); 
                                        
                                    }
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) {
                                    console.log(XMLHttpRequest);
                                    console.log(textStatus);
                                    console.log(errorThrown);
                                }
                            });
                        }
                    },
                    cancel: {
                        label: "Close",
                        className: "btn-warning",
                        callback: function () {

                        }
                    },
                }

            });


        },





        /**
        * Move request to archive
        *
        * @param int request_id
        *
        * @return void
        */
        archive: function (request_id){
            bootbox.confirm("Are you sure you want to move this request ot archive?", function(result){ 
                if(result){
                    $.ajax({
                        url: _args.varArchiveRequest,
                        type: "post",
                        data: {
                            request_id:request_id,
                        },
                        success: function(data){
                            data = $.parseJSON(data);
                            if(data.status == true){
                                xwb.Noty(data.message,'success'); 
                                xwb_var.table_request_list.ajax.reload();
                            }else{
                                xwb.Noty(data.message,'error'); 
                                
                            }
                        },
                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                            console.log(XMLHttpRequest);
                            console.log(textStatus);
                            console.log(errorThrown);
                        }
                    });
                }
            });
        }

    };

    window.func = func;
    var xwb_var = library(window.jQuery, window, document);
    return func;
}(function($, window, document) {
    "use strict";
    var _args = window.xwb_var;
    var xwb = window.xwb;




    /**
     * Jquery functions goes here
     * 
     */
    $(function() {

        $(document).on('change',".expenditure",function(){
            var val = $(this).val();
            var item_id = $(this).data('itemid');
            $.ajax({
                url: _args.varSetExpenditureItem,
                type: "post",
                data: {
                    expenditure: val,
                    item_id: item_id
                },
                success: function(data){
                    data = $.parseJSON(data);
                    if(data.status == true){
                        xwb.Noty(data.message,'success'); 
                         
                        _args.table_request_list.ajax.reload();
                    }else{
                        xwb.Noty(data.message,'error'); 
                        
                        return false;
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });

        });



        /**
        *generate request list datatable
        *
        *
        */
        _args.table_request_list = $('.table_request_list').DataTable({
            "processing": false, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [[ 0, "desc" ]], //Initial no order.
            "ajax": {
                "url": _args.varGetRequest,
                "data": function ( d ) {
                    //d.extra_search = $('#extra').val();
                }
              },
            "columnDefs": [
                { 
                    "targets": [ 7,9 ], 
                    "orderable": false, //set not orderable
                },
            ],
            "createdRow": function( row, data, dataIndex ) {
                if($(row).find('li.has-action').length != 0){
                    $(row).css('background-color','#dff0d8');
                }
                
              }
        });

        $.datepicker.setDefaults({
            dateFormat: 'yy-mm-dd'
        });

        
        
        $(document).ajaxStart(function() {
            $("div.loader").show();
        }).ajaxStop(function() {
            $("div.loader").hide();
        });


    });
    
    return _args;
}));