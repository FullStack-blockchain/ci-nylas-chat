///////////////////////////////////////
// XWB Purchasing                    //
// Author - Jay-r Simpron            //
// Copyright (c) 2017, Jay-r Simpron //
//                                   //
// Assigned request Script goes here //
///////////////////////////////////////
var xwb = (function(library) {
    "use strict";
    var func = function (){};
    var $ = window.jQuery;
    var xwb = window.xwb;
    var _args = window.xwb_var;
    var bootbox = window.bootbox;

    var table_req_items;
    var total_per_supplier;

    $.extend(func, {
        /**
        * View Items per request
        * @param int request_id
        *
        * @return mixed
        */
        viewItems: function (request_id){
            bootbox.dialog({
                title: 'Request Items',
                message: '<div class="row">'+
                            '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                '<div class="table-responsive">'+
                                    '<table class="table table_req_items">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>Item</th>'+
                                                '<th>Attachment</th>'+
                                                '<th>Description</th>'+
                                                '<th>Supplier</th>'+
                                                '<th>Qty</th>'+
                                                '<th>Price</th>'+
                                                '<th>Total</th>'+
                                                '<th>Action</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody>'+
                                    '<table>'+
                                '</div>'+
                            '</div>'+
                        '</div>',
                size: 'large',
                buttons:{
                    cancel: {
                        label: "Close",
                        className: "btn-warning",
                        callback: function () {

                        }
                    }
                }

            }).init(function(){
                table_req_items = $('.table_req_items').DataTable({
                    "ajax": {
                        "url": _args.varGetRequestItemsCanvasser,
                        "data": function ( d ) {
                            d.request_id = request_id;
                        }
                    },
                    "order": [[ 0, "desc" ]]
                });
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
            _args.table_attachment = $('.table_attachment').DataTable({
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
                        xwb.Noty(data.message, 'success'); 
                        _args.table_attachment.ajax.reload();
                        $("#attachment").val('');
                    }else{
                        xwb.Noty(data.message, 'error'); 
                    }
                },
            }).submit();
        },


        /**
        * Mark item as done
        *
        * @param int request_id
        *
        * @return mixed
        */
        done: function (request_id){
         bootbox.dialog({
                title: 'Request Bought',
                message: '<div class="row">'+
                            '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                '<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="reason">Remarks</label>'+
                                    '<div class="col-md-6">'+
                                        '<textarea name="reason" id="reason" class="form-control"></textarea>'+
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
                                url: _args.varRequestDone,
                                type: "post",
                                data: {
                                    request_id:request_id,
                                    remarks:$("#reason").val()
                                },
                                success: function(data){
                                    data = $.parseJSON(data);
                                    if(data.status == true){
                                        xwb.Noty(data.message,'success');

                                        xwb_var.table_assigned_req.ajax.reload();
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
        * @param int canvass_id
        *
        * @return mixed
        */
        assignToBudget: function (canvass_id){
            bootbox.dialog({
                title: 'Forward to Budget Department',
                message: '<div class="row">'+
                            '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                '<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="bdusers">Budget Department</label>'+
                                    '<div class="col-md-6">'+
                                        '<select name="bdusers" id="bdusers" style="width:100%;">'+
                                        _args.BDUsersOptions+
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
                                url: _args.varAssignBudget,
                                type: "post",
                                data: {
                                    canvass_id:canvass_id,
                                    user_id:$("#bdusers").val()
                                },
                                success: function(data){
                                    data = $.parseJSON(data);
                                    if(data.status == true){
                                        xwb.Noty(data.message,'success');
                                        xwb_var.table_assigned_req.ajax.reload();
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
        * Forward to purchasing department
        *
        * @param int canvass_id
        *
        * @return mixed
        */
        assignToAdmin: function (canvass_id){
            bootbox.dialog({
                title: 'Forward to Purchasing Department Admin',
                message: '<div class="row">'+
                            '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                '<p>Please make sure you have reviewed the request properly. Do you want to assign it to purchasing department?</p>'+
                                /*'<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="pdusers">Admin</label>'+
                                    '<div class="col-md-6">'+
                                        '<select name="pdusers" id="pdusers" style="width:100%;">'+
                                        PDUsersOptions+
                                        '</select>'+
                                    '</div> '+
                                '</div> '+*/
                            '</div>'+
                        '</div>',
                buttons:{
                    assign: {
                        label: "Assign",
                        className: "btn-success",
                        callback: function () {
                            $.ajax({
                                url: _args.varAssignPurchasing,
                                type: "post",
                                data: {
                                    canvass_id:canvass_id,
                                    //user_id:$("#pdusers").val()
                                },
                                success: function(data){
                                    data = $.parseJSON(data);
                                    if(data.status == true){
                                        xwb.Noty(data.message,'success');
                                        xwb_var.table_assigned_req.ajax.reload();
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

            $("#pdusers").select2({});
        },

        /**
        * View reason and response
        *
        * @param int canvass_id
        *
        * @return mixed
        */
        view_response: function (canvass_id){
            $.ajax({
                url: _args.varGetResponse,
                type: "post",
                data: {
                    canvass_id:canvass_id,
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
                                        url: _args.varRespond,
                                        type: "post",
                                        data: {
                                            canvass_id:canvass_id,
                                            response:$("#response").val()
                                        },
                                        success: function(data){
                                            data = $.parseJSON(data);
                                            if(data.status == true){
                                                xwb.Noty(data.message,'success'); 
                                                xwb_var.table_assigned_req.ajax.reload();
                                                
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
        * Return to requisitioner
        *
        * @param int canvass_id
        *
        * @return mixed
        */
        toRequisitioner: function (canvass_id){
            bootbox.dialog({
                title: 'Return to Requisitioner',
                message: '<div class="row">'+
                            '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                '<form class="form-horizontal">'+
                                    '<div class="form-group">'+
                                        '<label class="col-md-4 control-label" for="message">Message: </label>'+
                                        '<div class="col-md-6">'+
                                            '<textarea name="message" id="message" class="form-control"></textarea>'+
                                        '</div> '+
                                    '</div> '+
                                '</form> '+
                            '</div>'+
                        '</div>',
                buttons:{
                    assign: {
                        label: "Return",
                        className: "btn-success",
                        callback: function () {
                            $.ajax({
                                url: _args.varReturnRequisitioner,
                                type: "post",
                                data: {
                                    canvass_id:canvass_id,
                                    message:$("#message").val()
                                },
                                success: function(data){
                                    data = $.parseJSON(data);
                                    if(data.status == true){
                                        xwb.Noty(data.message,'success'); 
                                        xwb_var.table_assigned_req.ajax.reload();
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
                                         
                                        table_req_items.ajax.reload();
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
         * View total amount per supplier
         * 
         * @param  {Number} canvass_id [Request ID]
         * @return null
         */
        supplierSummary: function (canvass_id){
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

            }).init(function(){
               total_per_supplier = $('.table-total-per-supplier').DataTable({
                    "ajax": {
                        "url": _args.varSupplierSummary,
                        "data": function ( d ) {
                            d.canvass_id = canvass_id;
                        },
                        "dataSrc": function (json) {
                        $(".table-total-per-supplier tfoot").html(json.footer);
                        return json.data;
                        },
                      },
                      "order": [[ 0, "desc" ]]
                }); 
            });

        },




    });


    var xwb_var = library(window.jQuery, window, document);
    return func;
}(function($, window, document) {
    "use strict";
    var _args = window.xwb_var;


    $(function() {
        /**
        *generate canvasser assigned request datatable
        *
        *
        */
        _args.table_assigned_req = $('.table_assigned_req').DataTable({
            "processing": false, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [[ 0, "desc" ]], //Initial no order.
            "ajax": {
                "url": _args.varGetAssignedRequest,
                "data": function ( d ) {
                    //d.extra_search = $('#extra').val();
                }
              },
            "columnDefs": [
                { 
                    "targets": [ 4,6 ], 
                    "orderable": false, //set not orderable
                },
            ],
            "createdRow": function( row, data, dataIndex ) {
                if($(row).find('li.has-action').length != 0){
                    $(row).css('background-color','#dff0d8');
                }
                
            }
        });


        $(document).ajaxStart(function() {
            $("div.loader").show();
        }).ajaxStop(function() {
            $("div.loader").hide();
        });

    });
    return _args;
}));
