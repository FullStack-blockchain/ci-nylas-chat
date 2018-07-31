///////////////////////////////////////
// XWB Purchasing                    //
// Author - Jay-r Simpron            //
// Copyright (c) 2017, Jay-r Simpron //
//                                   //
// For approval Script goes here     //
///////////////////////////////////////
var xwb = (function(library) {
    "use strict";
    var $ = window.jQuery;
    var bootbox = window.bootbox;
    var xwb = window.xwb;
    var xwb_var = window.xwb_var;
    var _args;
    var func = {
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
                                                '<th>Description</th>'+
                                                '<th>Qty</th>'+
                                                '<th>Note</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody>'+
                                    '<table>'+
                                '</div>'+
                            '</div>'+
                        '</div>',
                size: "large",
                buttons:{
                    cancel: {
                        label: "Close",
                        className: "btn-warning",
                        callback: function () {

                        }
                    }
                }

            });

            xwb_var.table_req_items = $('.table_req_items').DataTable({
                "ajax": {
                    "url": xwb_var.varGetRequestItems,
                    "data": function ( d ) {
                        d.request_id = request_id;
                    }
                  },
                  "order": [[ 0, "desc" ]]
            });
        },

        /**
        * View approval Items
        * @param int approval_id
        *
        * @return mixed
        */
        viewApprovalItems : function (approval_id){
            bootbox.dialog({
                title: 'Request Items',
                message: '<div class="row">'+
                            '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                '<div class="table-responsive">'+
                                    '<table class="table table_req_items">'+
                                        '<thead>'+
                                            '<tr>'+
                                                '<th>Item</th>'+
                                                '<th>Description</th>'+
                                                '<th>Qty</th>'+
                                                '<th>Assigned To</th>'+
                                                '<th>Attachment</th>'+
                                                '<th>Date Updated</th>'+
                                                '<th>Requisitioner\' Note</th>'+
                                                '<th>Your Note</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody>'+
                                    '<table>'+
                                '</div>'+
                            '</div>'+
                        '</div>',
                className: "width-90p",
                buttons:{
                    cancel: {
                        label: "Close",
                        className: "btn-warning",
                        callback: function () {

                        }
                    },
                    approveAll: {
                        label: "Approve All",
                        className: "btn-info",
                        callback: function () {
                            bootbox.prompt("Your note here", function(result){ 
                                var data, item_id, className;
                                if(result!=null){
                                    data = [];
                                    item_id = [];
                                    $("textarea[class^='officers_note_']").each(function(i,n){
                                        className = $(n).prop('class');
                                        className = className.split('_');
                                        item_id.push(className[2]);
                                    });
                                    
                                    data.push({name:'item_id',value:item_id});
                                    data.push({name:'officers_note',value:result});

                                    $.ajax({
                                        url: xwb_var.varApproveAllItem,
                                        type: "post",
                                        data: data,
                                        success: function(data){
                                            data = $.parseJSON(data);
                                            if(data.status == true){
                                                xwb.Noty(data.message,'success');
                                                xwb_var.table_req_items.ajax.reload();
                                                _args.table_forapproval_request.ajax.reload();

                                            }else{
                                                xwb.Noty(data.message,'error');
                                            }

                                            return false;
                                        },
                                        error: function(XMLHttpRequest, textStatus, errorThrown) {
                                            console.log(XMLHttpRequest);
                                            console.log(textStatus);
                                            console.log(errorThrown);
                                        }
                                    });

                                }
                            });
                            return false;
                        }
                    }
                },

                /**
                * Approve request
                * @param int request_id
                *
                * @return mixed
                */
                approve: function (request_id){
                    bootbox.dialog({
                        title: 'Approve and File Request to Purchasing Department',
                        message: '<div class="row">'+
                                    '<div class="col-md-12 col-sm-12 col-xs-12">'+
                                        '<div class="form-group">'+
                                            '<label class="col-md-4 control-label" for="dept_head_note">Remarks</label>'+
                                            '<div class="col-md-6">'+
                                                '<textarea name="dept_head_note" id="dept_head_note" class="form-control"></textarea>'+
                                            '</div> '+
                                        '</div> '+
                                    '</div>'+
                                '</div>',
                        buttons:{
                            approve: {
                                label: "Approve",
                                className: "btn-success",
                                callback: function () {
                                    $.ajax({
                                        url: xwb_var.varApproveToPurchasing,
                                        type: "post",
                                        data: {
                                            request_id:request_id,
                                            dept_head_note:$("#dept_head_note").val()
                                        },
                                        success: function(data){
                                            data = $.parseJSON(data);
                                            if(data.status == true){
                                                xwb.Noty(data.message,'success');
                                                _args.table_forapproval_request.ajax.reload();
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
                }

            }).init(function(){
                xwb_var.table_req_items = $('.table_req_items').DataTable({
                "ajax": {
                    "url": xwb_var.varGetReqApprovaltItems,
                    "data": function ( d ) {
                        d.approval_id = approval_id;
                    }
                  },
                  "order": [[ 0, "desc" ]]
                });
            });

            
        },

        /**
        * Approve Item Request
        * @param int item_approval_id
        *
        * @return mixed
        */
        approveItem: function (item_approval_id){
            $.ajax({
                url: xwb_var.varApproveItem,
                type: "post",
                data: {
                    item_approval_id:item_approval_id,
                    officers_note:$(".officers_note_"+item_approval_id).val()
                },
                success: function(data){
                    data = $.parseJSON(data);
                    if(data.status == true){
                        xwb.Noty(data.message,'success');
                        xwb_var.table_req_items.ajax.reload();
                        _args.table_forapproval_request.ajax.reload();
                    }else{
                        xwb.Noty(data.message,'error');
                    }

                    return false;
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        },
        /**
        * Deny Item Request
        * @param int item_approval_id
        *
        * @return mixed
        */
        denyItem: function (item_approval_id){
            $.ajax({
                url: xwb_var.varDenyItem,
                type: "post",
                data: {
                    item_approval_id:item_approval_id,
                    officers_note:$(".officers_note_"+item_approval_id).val()
                },
                success: function(data){
                    data = $.parseJSON(data);
                    if(data.status == true){
                        xwb.Noty(data.message,'success');
                        xwb_var.table_req_items.ajax.reload();
                        _args.table_forapproval_request.ajax.reload();
                    }else{
                        xwb.Noty(data.message,'error');
                    }

                    return false;
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
        },

        /**
        * View Attachment for the items preview
        *
        * @param element el
        *
        * @return mixed
        */
        viewAttachmentPreview: function (po_id) {

            bootbox.dialog({
                title: "Attachment",
                message: '<div class="row">'+
                    '<div class="col-md-12 col-sm-12 col-xs-12">'+
                        '<div class="panel panel-success">'+
                            '<div class="panel-heading">'+
                              '<h3 class="panel-title">Attachment</h3>'+
                            '</div>'+
                            '<div class="panel-body">'+
                                '<div class="table-responsive">'+
                                    '<input type="hidden" name="po_id" id="po_id" value="'+po_id+'">'+
                                    '<table class="table table_attachment_preview">'+
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
            }).on('shown.bs.modal',function() { 

                /**
                *generate attachment datatable
                *
                *
                */
                xwb_var.table_attachment_preview = $('.table_attachment_preview').DataTable({
                    "ajax": {
                        "url": xwb_var.varGetAttachment,
                        "data": function ( d ) {
                            d.po_id = po_id;
                        }
                      },
                      "order": [[ 0, "desc" ]]
                });
            }); 


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
                        url: xwb_var.varRemoveAttachment,
                        type: "post",
                        data: {
                            attachment_id:attachment_id,
                        },
                        success: function(data){
                            data = $.parseJSON(data);
                            if(data.status == true){
                                xwb.Noty(data.message,'success');
                                xwb_var.table_attachment.ajax.reload();
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

    };
    _args = library(window.jQuery, window, document);
    return func;
}(function($, window, document) {
    "use strict";
    var _args = window.xwb_var;
    
    /**
     * Jquery functions goes here
     * 
     */
    $(function() {
        /**
        *generate for approval request datatable
        *
        *
        */
        _args.table_forapproval_request = $('.table_forapproval_request').DataTable({
            "processing": false, //Feature control the processing indicator.
            "serverSide": true, //Feature control DataTables' server-side processing mode.
            "order": [[ 0, "desc" ]], //Initial no order.
            "ajax": {
                "url": _args.varGetReqForApproval,
                "data": function ( d ) {

                }
              },
            "columnDefs": [
                { 
                    "targets": [ 5,7 ], 
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