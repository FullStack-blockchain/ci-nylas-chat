///////////////////////////////////////
// XWB Purchasing                    //
// Author - Jay-r Simpron            //
// Copyright (c) 2017, Jay-r Simpron //
//                                   //
// Request Script goes here          //
///////////////////////////////////////

var xwb = (function(library) {
    "use strict";
    var func = function (){};
    var $ = window.jQuery;
    var _args = window.xwb_var;
    var xwb = window.xwb;
    var bootbox = window.bootbox;

    var table_req_items;
    var table_attachment;

    $.extend(func, {

    

        /**
        * Edit Request
        * @param int req_id
        *
        * @return mixed
        */
        editRequest : function (req_id){
            $.ajax({
                url: _args.varEditRequest,
                type: "post",
                data: {
                    req_id: req_id
                },
                success: function(data){
                    var edit_data = $.parseJSON(data);
                    bootbox.dialog({
                        title: "Edit Request",
                        message: '<div class="row">  ' +
                            '<div class="col-md-12 col-sm-12 col-xs-12"> ' +
                            '<form class="form-horizontal" name="form_edit_req" id="form_edit_req"> ' +
                                '<input type="hidden" id="id" name="id" />'+
                                '<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="name">Purchase No.</label>'+
                                    '<div class="col-md-6">'+
                                        '<input id="prno" name="prno" type="text" placeholder="PR No." class="form-control input-md"> '+
                                    '</div> '+
                                '</div> '+
                                '<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="date_requested">Date Requested</label>'+
                                    '<div class="col-md-6">'+
                                        '<input id="date_requested" name="date_requested" type="text" placeholder="Date Requested" class="form-control input-md"> '+
                                    '</div> '+
                                '</div> '+
                                '<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="user_id">Username</label>'+
                                    '<div class="col-md-6">'+
                                        '<input id="user_id" name="user_id" type="text" placeholder="Username" class="form-control input-md"> '+
                                    '</div> '+
                                '</div> '+
                                '<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="dept">Department</label>'+
                                    '<div class="col-md-6">'+
                                        '<input id="dept" name="dept" type="text" placeholder="Department" class="form-control input-md"> '+
                                    '</div> '+
                                '</div> '+
                                '<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="request_type">Type of Request</label>'+
                                    '<div class="col-md-6">'+
                                        '<input id="request_type" name="request_type" type="text" placeholder="Type of Request" class="form-control input-md"> '+
                                    '</div> '+
                                '</div> '+
                                '<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="request_item">Item</label>'+
                                    '<div class="col-md-6">'+
                                        '<input id="request_item" name="request_item" type="text" placeholder="Item" class="form-control input-md"> '+
                                    '</div> '+
                                '</div> '+
                                '<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="request_description">Description</label>'+
                                    '<div class="col-md-6">'+
                                        '<input id="request_description" name="request_description" type="text" placeholder="Description" class="form-control input-md"> '+
                                    '</div> '+
                                '</div> '+
                                '<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="request_qty">Quantity</label>'+
                                    '<div class="col-md-6">'+
                                        '<input id="request_qty" name="request_qty" type="text" placeholder="Quantity" class="form-control input-md"> '+
                                    '</div> '+
                                '</div> '+
                                '<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="state">Status</label>'+
                                    '<div class="col-md-6">'+
                                        '<input id="state" name="state" type="text" placeholder="Status" class="form-control input-md"> '+
                                    '</div> '+
                                '</div> '+
                            '</form> </div>  </div>',
                        buttons: {
                            success: {
                                label: "Update",
                                className: "btn-success",
                                callback: function () {
                                    var frm_data  = $("#form_edit_req").serializeArray();

                                    $.ajax({
                                        url: _args.varUpdateRequest,
                                        type: "post",
                                        data: frm_data,
                                        success: function(data){
                                            data = $.parseJSON(data);
                                            if(data.status == true){
                                                xwb.Noty(data.message, 'success'); 
                                                xwb_var.table_request.ajax.reload();
                                                bootbox.hideAll();
                                            }else{
                                                xwb.Noty(data.message, 'error'); 
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
                    $("#form_edit_req").populate(edit_data);   
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    console.log(XMLHttpRequest);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });


        },

        /**
        * View Items per request
        * @param int request_id
        *
        * @return mixed
        */
        viewItems: function (request_id){
            var expenditure = "";
            var qty_price = "";
            if(_args.group_name == 'budget')
                expenditure = '<th>Expenditure</th>';
            
            if(_args.group_name == 'admin')
                qty_price = '<th>Unit Price</th><th>Total Price</th>';


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
                                                '<th>Qty</th>'+
                                                expenditure+
                                                qty_price+
                                                '<th>Attachment</th>'+
                                                '<th>ETA</th>'+
                                                '<th>Date Delivered</th>'+
                                                '<th>Follow-up Date</th>'+
                                            '</tr>'+
                                        '</thead>'+
                                        '<tbody>'+
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

            }).init(function(){
                table_req_items = $('.table_req_items').DataTable({
                    "ajax": {
                        "url": _args.varGetRequestItems,
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
         * Add Request
         * 
         * @return mixed
         */
        addRequest: function (){
            bootbox.dialog({
                title: "Add Request",
                message: '<div class="row">'+
                    '<div class="col-md-12 col-sm-12 col-xs-12"> ' +
                            '<form class="form-horizontal" name="form_add_req" id="form_add_req"> ' +
                                '<input type="hidden" id="id" name="id" />'+
                                '<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="name">Purchase No.</label>'+
                                    '<div class="col-md-6">'+
                                        '<input id="prno" name="prno" type="text" placeholder="PR No." class="form-control input-md"> '+
                                    '</div> '+
                                '</div> '+
                                '<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="date_requested">Date Requested</label>'+
                                    '<div class="col-md-6">'+
                                        '<input id="date_requested" name="date_requested" type="text" placeholder="Date Requested" class="form-control input-md"> '+
                                    '</div> '+
                                '</div> '+
                                '<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="user_id">Username</label>'+
                                    '<div class="col-md-6">'+
                                        '<input id="user_id" name="user_id" type="text" placeholder="Username" class="form-control input-md"> '+
                                    '</div> '+
                                '</div> '+
                                '<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="dept">Department</label>'+
                                    '<div class="col-md-6">'+
                                        '<input id="dept" name="dept" type="text" placeholder="Department" class="form-control input-md"> '+
                                    '</div> '+
                                '</div> '+
                                '<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="request_type">Type of Request</label>'+
                                    '<div class="col-md-6">'+
                                        '<input id="request_type" name="request_type" type="text" placeholder="Type of Request" class="form-control input-md"> '+
                                    '</div> '+
                                '</div> '+
                                '<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="request_item">Item</label>'+
                                    '<div class="col-md-6">'+
                                        '<input id="request_item" name="request_item" type="text" placeholder="Item" class="form-control input-md"> '+
                                    '</div> '+
                                '</div> '+
                                '<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="request_description">Description</label>'+
                                    '<div class="col-md-6">'+
                                        '<input id="request_description" name="request_description" type="text" placeholder="Description" class="form-control input-md"> '+
                                    '</div> '+
                                '</div> '+
                                '<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="request_qty">Quantity</label>'+
                                    '<div class="col-md-6">'+
                                        '<input id="request_qty" name="request_qty" type="text" placeholder="Quantity" class="form-control input-md"> '+
                                    '</div> '+
                                '</div> '+
                                '<div class="form-group">'+
                                    '<label class="col-md-4 control-label" for="state">Status</label>'+
                                    '<div class="col-md-6">'+
                                        '<input id="state" name="state" type="text" placeholder="Status" class="form-control input-md"> '+
                                    '</div> '+
                                '</div> '+
                    '</form> </div>  </div>',
                buttons: {
                    success: {
                        label: "Save",
                        className: "btn-success",
                        callback: function () {
                            var frm_data  = $("#form_add_req").serializeArray();

                            $.ajax({
                                url: _args.varAddRequest,
                                type: "post",
                                data: frm_data,
                                success: function(data){
                                    data = $.parseJSON(data);
                                    if(data.status == true){
                                        xwb.Noty(data.message,'success');
                                        xwb_var.table_request.ajax.reload();
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


        /**
        * Delete request
        * @param int id
        *
        * @return mixed
        */
        deleteRequest: function (id){
            bootbox.confirm("Are you sure you want to delete this record?", function(result){ 
                if(result){
                    $.ajax({
                        url: _args.varDeleteRequest,
                        type: "post",
                        data: {
                            'id':id
                        },
                        success: function(data){
                            data = $.parseJSON(data);
                            if(data.status == true){
                                xwb.Noty(data.message,'success'); 
                                xwb_var.table_request.ajax.reload();
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
                        table_attachment.ajax.reload();
                        $("#attachment").val('');
                    }else{
                        xwb.Noty(data.message, 'error'); 
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
        }



    });

    window.func = func;

    var xwb_var = library(window.jQuery, window, document);
    return func;
}(function($, window, document) {
    "use strict";
    var _args = window.xwb_var;

    /*declares datatable*/
    var table_request;
    var table_request_list;

    // var table_attachment;


    /**
     * Jquery Functions goes here
     * 
     */
    $(function() {
        /**
        *generate request datatable
        *
        *
        */
        table_request = $('.table_request').DataTable({
            "ajax": {
                "url": _args.varGetRequest,
                "data": function ( d ) {
                    //d.extra_search = $('#extra').val();
                }
              },
              "order": [[ 0, "desc" ]],
              "createdRow": function( row, data, dataIndex ) {
                if($(row).find('a.has-action').length != 0){
                    $(row).css('background-color','#dff0d8');
                }
                
              }
        });



        /**
        *generate request datatable
        *
        *
        */
        table_request_list = $('.table_request_list').DataTable({
            "ajax": {
                "url": _args.varGetRequestList,
                "data": function ( d ) {
                    //d.extra_search = $('#extra').val();
                }
              },
              "order": [[ 0, "desc" ]],
              "createdRow": function( row, data, dataIndex ) {
                if($(row).find('a.has-action').length != 0){
                    $(row).css('background-color','#dff0d8');
                }
                
              }
        });

        /**
         * Auto save expenditure when changed
         */
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
                         
                        _args.table_req_items.ajax.reload();
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

    });

}));
